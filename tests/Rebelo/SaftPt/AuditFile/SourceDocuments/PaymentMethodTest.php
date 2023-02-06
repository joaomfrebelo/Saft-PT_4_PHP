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

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class PaymentMethodTest
 *
 * @author João Rebelo
 */
class PaymentMethodTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(PaymentMethod::class);
        $this->assertTrue(true);
    }

    /**
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGet(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $this->assertInstanceOf(PaymentMethod::class, $payMeth);
        $this->assertNull($payMeth->getPaymentMechanism());

        try {
            $payMeth->getPaymentAmount();
            $this->fail(
                "Get PaymentAmount without be set should throw "
                ."\Error"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try {
            $payMeth->getPaymentDate();
            $this->fail(
                "Get PaymentDate without be set should throw "
                ."\Error"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        $wrong = -1.9;
        $payMeth->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payMeth->setPaymentAmount($wrong));
        $this->assertSame($wrong, $payMeth->getPaymentAmount());
        $this->assertNotEmpty($payMeth->getErrorRegistor()->getOnSetValue());

        $mechanism = new PaymentMechanism(PaymentMechanism::PR);
        $payMeth->setPaymentMechanism($mechanism);
        $this->assertSame($mechanism, $payMeth->getPaymentMechanism());
        $payMeth->setPaymentMechanism(null);
        $this->assertNull($payMeth->getPaymentMechanism());

        $amount = 499.59;
        $this->assertTrue($payMeth->setPaymentAmount($amount));
        $this->assertSame($amount, $payMeth->getPaymentAmount());

        $date = new RDate();
        $payMeth->setPaymentDate($date);
        $this->assertSame($date, $payMeth->getPaymentDate());
    }

    /**
     *
     * @return PaymentMethod
     * @throws DateFormatException
     */
    public function createPaymentMethod(): PaymentMethod
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $payMeth->setPaymentMechanism(
            new PaymentMechanism(PaymentMechanism::PR)
        );
        $payMeth->setPaymentAmount(409.79);
        $payMeth->setPaymentDate(new RDate());
        return $payMeth;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $payMeth = new PaymentMethod(new ErrorRegister());
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @throws DateFormatException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(
            $payMeth->getPaymentMechanism()->get(),
            (string) $payMethNode->{PaymentMethod::N_PAYMENTMECHANISM}
        );

        $this->assertSame(
            $payMeth->getPaymentAmount(),
            (float) $payMethNode->{PaymentMethod::N_PAYMENTAMOUNT}
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            (string) $payMethNode->{PaymentMethod::N_PAYMENTDATE}
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws DateFormatException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode2(): void
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);

        $node = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(
            0,
            $node->{PaymentMethod::N_PAYMENTMETHOD}->{PaymentMethod::N_PAYMENTMECHANISM}->count()
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws DateFormatException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXml(): void
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $xml     = $payMeth->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new PaymentMethod(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $payMeth->getPaymentMechanism()->get(),
            $parsed->getPaymentMechanism()->get()
        );

        $this->assertSame(
            $payMeth->getPaymentAmount(), $parsed->getPaymentAmount()
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            $parsed->getPaymentDate()->format(RDate::SQL_DATE)
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXml2(): void
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);
        $node    = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $xml     = $payMeth->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new PaymentMethod(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getPaymentMechanism());

        $this->assertSame(
            $payMeth->getPaymentAmount(), $parsed->getPaymentAmount()
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            $parsed->getPaymentDate()->format(RDate::SQL_DATE)
        );

        $this->assertEmpty($payMeth->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payMeth->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $payNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
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
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $payNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $pay     = new PaymentMethod(new ErrorRegister());
        $pay->setPaymentAmount(-9.99);

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
