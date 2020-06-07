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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;

/**
 * Class PaymentMethodTest
 *
 * @author João Rebelo
 */
class PaymentMethodTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(PaymentMethod::class);
        $this->assertTrue(true);
    }

    public function testInstanceSetGet()
    {
        $payMeth = new PaymentMethod();
        $this->assertInstanceOf(PaymentMethod::class, $payMeth);
        $this->assertNull($payMeth->getPaymentMechanism());
        try {
            $payMeth->getPaymentAmount();
            $this->fail("Get PaymentAmout without be setted should throw "
                ."\Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }
        try {
            $payMeth->getPaymentDate();
            $this->fail("Get PaymentDate without be setted should throw "
                ."\Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }
        try {
            $payMeth->setPaymentAmount(-1.9);
            $this->fail("A negative PaymentAmounte should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }

        $mechanism = new PaymentMechanism(PaymentMechanism::PR);
        $payMeth->setPaymentMechanism($mechanism);
        $this->assertSame($mechanism, $payMeth->getPaymentMechanism());
        $payMeth->setPaymentMechanism(null);
        $this->assertNull($payMeth->getPaymentMechanism());

        $amount = 499.59;
        $payMeth->setPaymentAmount($amount);
        $this->assertSame($amount, $payMeth->getPaymentAmount());

        $date = new RDate();
        $payMeth->setPaymentDate($date);
        $this->assertSame($date, $payMeth->getPaymentDate());
    }

    /**
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod
     */
    public function createPaymentMethod(): PaymentMethod
    {
        $payMeth = new PaymentMethod();
        $payMeth->setPaymentMechanism(
            new PaymentMechanism(PaymentMechanism::PR)
        );
        $payMeth->setPaymentAmount(409.79);
        $payMeth->setPaymentDate(new RDate());
        return $payMeth;
    }

    public function testCreateXmlNodeWrongName()
    {
        $payMeth = new PaymentMethod();
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->createXmlNode($node);
            $this->fail("Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testParseXmlNodeWrongName()
    {
        $payMeth = new PaymentMethod();
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $payMeth->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testCreateXmlNode()
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(
            $payMeth->getPaymentMechanism()->get(),
            (string) $node->{PaymentMethod::N_PAYMENTMETHOD}->{PaymentMethod::N_PAYMENTMECHANISM}
        );

        $this->assertSame(
            $payMeth->getPaymentAmount(),
            (float) $node->{PaymentMethod::N_PAYMENTMETHOD}->{PaymentMethod::N_PAYMENTAMOUNT}
        );

        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            (string) $node->{PaymentMethod::N_PAYMENTMETHOD}->{PaymentMethod::N_PAYMENTDATE}
        );
    }

    public function testCreateXmlNode2()
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);

        $node = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );

        $payMethNode = $payMeth->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $payMethNode);

        $this->assertSame(0,
            $node->{PaymentMethod::N_PAYMENTMETHOD}->{PaymentMethod::N_PAYMENTMECHANISM}->count()
        );
    }

    public function testeParseXml()
    {
        $payMeth = $this->createPaymentMethod();
        $node    = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $xml     = $payMeth->createXmlNode($node)->asXML();

        $parsed = new PaymentMethod();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($payMeth->getPaymentMechanism()->get(),
            $parsed->getPaymentMechanism()->get());
        $this->assertSame($payMeth->getPaymentAmount(),
            $parsed->getPaymentAmount());
        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            $parsed->getPaymentDate()->format(RDate::SQL_DATE)
        );
    }

    public function testeParseXml2()
    {
        $payMeth = $this->createPaymentMethod();
        $payMeth->setPaymentMechanism(null);
        $node    = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $xml     = $payMeth->createXmlNode($node)->asXML();

        $parsed = new PaymentMethod();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getPaymentMechanism());
        $this->assertSame($payMeth->getPaymentAmount(),
            $parsed->getPaymentAmount());
        $this->assertSame(
            $payMeth->getPaymentDate()->format(RDate::SQL_DATE),
            $parsed->getPaymentDate()->format(RDate::SQL_DATE)
        );
    }
}