<?php
/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\Commune;

/**
 * Class PaymentMethodTest
 *
 * @author João Rebelo
 */
class PaymentMethodTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(PaymentMethod::class))->testReflection(PaymentMethod::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGet(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $this->assertInstanceOf(PaymentMethod::class, $payMeth);
        $this->assertNull($payMeth->getPaymentMechanism());

        try {
            $payMeth->getPaymentAmount();
            $this->fail(
                "Get PaymentAmount without be set should throw "
                . "\Error"
            );
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try {
            $payMeth->getPaymentDate();
            $this->fail(
                "Get PaymentDate without be set should throw "
                . "\Error"
            );
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        $wrong = new Decimal("-1.9");
        $payMeth->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payMeth->setPaymentAmount($wrong));
        $this->assertSame($wrong, $payMeth->getPaymentAmount());
        $this->assertNotEmpty($payMeth->getErrorRegistor()->getOnSetValue());

        $mechanism = PaymentMechanism::PR;
        $payMeth->setPaymentMechanism($mechanism);
        $this->assertSame($mechanism, $payMeth->getPaymentMechanism());
        $payMeth->setPaymentMechanism(null);
        $this->assertNull($payMeth->getPaymentMechanism());

        $amount = new Decimal("499.59");
        $this->assertTrue($payMeth->setPaymentAmount($amount));
        $this->assertSame($amount, $payMeth->getPaymentAmount());

        $date = new RDate();
        $payMeth->setPaymentDate($date);
        $this->assertSame($date, $payMeth->getPaymentDate());
    }

    /**
     *
     * @return PaymentMethod
     */
    public function createPaymentMethod(): PaymentMethod
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $payMeth->setPaymentMechanism(PaymentMechanism::PR);
        $payMeth->setPaymentAmount(new Decimal("409.79"));
        $payMeth->setPaymentDate(new RDate());
        return $payMeth;
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNodeWrongName(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(
            $payMeth->getPaymentMechanism()?->value,
            (string)$payMethNode->{PaymentMethod::N_PAYMENT_MECHANISM}
        );

        $this->assertSame(
            $payMeth->getPaymentAmount()->toFloat(),
            (float)$payMethNode->{PaymentMethod::N_PAYMENT_AMOUNT}
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(Pattern::SQL_DATE),
            (string)$payMethNode->{PaymentMethod::N_PAYMENT_DATE}
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode2(): void
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);

        $node = new \SimpleXMLElement(
            "<" . Payment::N_PAYMENT . "></" . Payment::N_PAYMENT . ">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(
            0,
            $node->{PaymentMethod::N_PAYMENT_METHOD}->{PaymentMethod::N_PAYMENT_MECHANISM}->count()
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $xml     = $payMeth->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new PaymentMethod(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $payMeth->getPaymentMechanism(),
            $parsed->getPaymentMechanism()
        );

        $this->assertSame(
            $payMeth->getPaymentAmount()->toFloat(), $parsed->getPaymentAmount()->toFloat()
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(Pattern::SQL_DATE),
            $parsed->getPaymentDate()->format(Pattern::SQL_DATE)
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml2(): void
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);
        $node = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $xml  = $payMeth->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new PaymentMethod(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getPaymentMechanism());

        $this->assertSame(
            $payMeth->getPaymentAmount()->toFloat(), $parsed->getPaymentAmount()->toFloat()
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(Pattern::SQL_DATE),
            $parsed->getPaymentDate()->format(Pattern::SQL_DATE)
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $payNode = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $pay     = new PaymentMethod(new ErrorRegister());
        $xml     = $pay->createXmlNode($payNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($pay->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($pay->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($pay->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $payNode = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $pay     = new PaymentMethod(new ErrorRegister());
        $pay->setPaymentAmount(new Decimal("-9.99"));

        $xml = $pay->createXmlNode($payNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($pay->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($pay->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($pay->getErrorRegistor()->getLibXmlError());
    }
}
