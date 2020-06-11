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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\Payments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;

/**
 * Class DocumentStatusTest
 *
 * @author João Rebelo
 */
class DocumentStatusTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $status = new DocumentStatus();
        $this->assertInstanceOf(DocumentStatus::class, $status);
        $this->assertNull($status->getReason());

        $payStatus = new PaymentStatus(PaymentStatus::N);
        $status->setPaymentStatus($payStatus);
        $this->assertSame($payStatus->get(), $status->getPaymentStatus()->get());

        $date = new RDate();
        $status->setPaymentStatusDate($date);
        $this->assertSame(
            $date->format(RDate::DATE_T_TIME),
            $status->getPaymentStatusDate()->format(RDate::DATE_T_TIME));

        $reason = "Test reason";
        $status->setReason($reason);
        $this->assertSame($reason, $status->getReason());
        $status->setReason(null);
        $this->assertNull($status->getReason());
        $status->setReason(\str_pad("A", 99, "9"));
        $this->assertSame(50, \strlen($status->getReason()));

        try {
            $status->setReason("");
            $this->fail("Set reason to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $sourceID = "Source ID teste";
        $status->setSourceID($sourceID);
        $this->assertSame($sourceID, $status->getSourceID());
        $status->setSourceID(\str_pad("A", 99, "9"));
        $this->assertSame(30, \strlen($status->getSourceID()));

        try {
            $status->setSourceID("");
            $this->fail("Set sourceID to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $sourcePay = new SourcePayment(SourcePayment::M);
        $status->setSourcePayment($sourcePay);
        $this->assertSame($sourcePay->get(), $status->getSourcePayment()->get());
    }

    /**
     * Reads all Payments's lines from the Demo SAFT in Test\Ressources
     * and parse them to Line class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $paymentsStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{Payments::N_PAYMENTS}
            ->{Payment::N_PAYMENT};

        if ($paymentsStack->count() === 0) {
            $this->fail("No Payment in XML");
        }

        for ($i = 0; $i < $paymentsStack->count(); $i++) {
            $paymentXml  = $paymentsStack[$i];
            $statusStack = $paymentXml->{DocumentStatus::N_DOCUMENTSTATUS};

            if ($statusStack->count() === 0) {
                $this->fail("No document status in Invoice");
            }

            for ($l = 0; $l < $statusStack->count(); $l++) {
                /* @var $lineXml \SimpleXMLElement */
                $lineXml = $statusStack[$l];
                $status  = new DocumentStatus();
                $status->parseXmlNode($lineXml);


                $xmlRootNode   = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $status->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Payment '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO}, $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $e->getMessage()));
                }
            }
        }
    }

    /**
     * 
     */
    public function testCreateParseXmlNull()
    {
        $status    = new DocumentStatus();
        $payStatus = new PaymentStatus(PaymentStatus::N);
        $status->setPaymentStatus($payStatus);
        $date      = new RDate();
        $status->setPaymentStatusDate($date);
        $status->setReason(null);
        $sourceID  = "Source ID teste";
        $status->setSourceID($sourceID);
        $sourcePay = new SourcePayment(SourcePayment::M);
        $status->setSourcePayment($sourcePay);

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
        $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
        $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

        $xml = $status->createXmlNode($payNode);

        $parsed = new DocumentStatus();
        $parsed->parseXmlNode($xml);

        $this->assertNull($parsed->getReason());

        $this->assertSame(
            $status->getPaymentStatus()->get(),
            $parsed->getPaymentStatus()->get()
        );

        $this->assertSame(
            $status->getPaymentStatusDate()->format(RDate::DATE_T_TIME),
            $parsed->getPaymentStatusDate()->format(RDate::DATE_T_TIME)
        );

        $this->assertSame(
            $status->getSourceID(), $parsed->getSourceID()
        );

        $this->assertSame(
            $status->getSourcePayment()->get(),
            $parsed->getSourcePayment()->get()
        );
    }
}