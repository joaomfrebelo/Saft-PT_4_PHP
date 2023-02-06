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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;

/**
 * Class DocumentStatusTest
 *
 * @author João Rebelo
 */
class DocumentStatusTest extends TestCase
{

    use TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $status = new DocumentStatus(new ErrorRegister());
        $this->assertInstanceOf(DocumentStatus::class, $status);
        $this->assertNull($status->getReason());
        $this->assertFalse($status->issetPaymentStatus());
        $this->assertFalse($status->issetPaymentStatusDate());
        $this->assertFalse($status->issetSourceID());
        $this->assertFalse($status->issetSourcePayment());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPaymentStatus(): void
    {
        $status    = new DocumentStatus(new ErrorRegister());
        $payStatus = new PaymentStatus(PaymentStatus::N);
        $status->setPaymentStatus($payStatus);
        $this->assertSame($payStatus->get(), $status->getPaymentStatus()->get());
        $this->assertTrue($status->issetPaymentStatus());
    }

    /**
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testSetGetPaymentStatusDate(): void
    {
        $status = new DocumentStatus(new ErrorRegister());
        $date   = new RDate();
        $status->setPaymentStatusDate($date);
        $this->assertSame(
            $date->format(RDate::DATE_T_TIME),
            $status->getPaymentStatusDate()->format(RDate::DATE_T_TIME)
        );
        $this->assertTrue($status->issetPaymentStatusDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetReason(): void
    {
        $status = new DocumentStatus(new ErrorRegister());
        $reason = "Test reason";
        $this->assertTrue($status->setReason($reason));
        $this->assertSame($reason, $status->getReason());
        $this->assertTrue($status->setReason(null));
        $this->assertNull($status->getReason());
        $this->assertTrue($status->setReason(\str_pad("A", 99, "9")));
        $this->assertSame(50, \strlen($status->getReason()));

        $status->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($status->setReason(""));
        $this->assertSame("", $status->getReason());
        $this->assertNotEmpty($status->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSourceID(): void
    {
        $status   = new DocumentStatus(new ErrorRegister());
        $sourceID = "Source ID test";
        $this->assertTrue($status->setSourceID($sourceID));
        $this->assertTrue($status->issetSourceID());
        $this->assertSame($sourceID, $status->getSourceID());
        $this->assertTrue($status->setSourceID(\str_pad("A", 99, "9")));
        $this->assertSame(30, \strlen($status->getSourceID()));

        $status->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($status->setSourceID(""));
        $this->assertSame("", $status->getSourceID());
        $this->assertNotEmpty($status->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSourcePayment(): void
    {
        $status    = new DocumentStatus(new ErrorRegister());
        $sourcePay = new SourcePayment(SourcePayment::M);
        $status->setSourcePayment($sourcePay);
        $this->assertTrue($status->issetSourcePayment());
        $this->assertSame($sourcePay->get(), $status->getSourcePayment()->get());
    }

    /**
     * Reads all Payment's lines from the Demo SAFT in Test\Ressources
     * and parse then to Line class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @throws AuditFileException
     * @throws DateFormatException
     * @throws DateParseException
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $status = null;
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

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
                $status  = new DocumentStatus(new ErrorRegister());
                $status->parseXmlNode($lineXml);


                $xmlRootNode   = (new AuditFile())->createRootElement();
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $status->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Payment '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO}, $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $e->getMessage()
                        )
                    );
                }
            }
        }

        $this->assertNotNull($status);
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($status?->getErrorRegistor()->getLibXmlError());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($status?->getErrorRegistor()->getOnCreateXmlNode());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($status?->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws DateFormatException
     * @throws AuditFileException
     * @throws DateParseException
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXmlNull(): void
    {
        $status    = new DocumentStatus(new ErrorRegister());
        $payStatus = new PaymentStatus(PaymentStatus::N);
        $status->setPaymentStatus($payStatus);
        $date      = new RDate();
        $status->setPaymentStatusDate($date);
        $status->setReason(null);
        $sourceID  = "Source ID test";
        $status->setSourceID($sourceID);
        $sourcePay = new SourcePayment(SourcePayment::M);
        $status->setSourcePayment($sourcePay);

        $xmlRootNode   = (new AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
        $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
        $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

        $xml = $status->createXmlNode($payNode);

        $parsed = new DocumentStatus(new ErrorRegister());
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

        $this->assertEmpty($status->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($status->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($status->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $statusNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $status     = new DocumentStatus(new ErrorRegister());
        $xml        = $status->createXmlNode($statusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($status->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($status->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($status->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $statusNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $status      = new DocumentStatus(new ErrorRegister());
        $status->setReason("");
        $status->setSourceID("");

        $xml = $status->createXmlNode($statusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($status->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($status->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($status->getErrorRegistor()->getLibXmlError());
    }
}
