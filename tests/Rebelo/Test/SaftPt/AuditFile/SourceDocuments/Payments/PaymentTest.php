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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\TransactionID;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class PaymentTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Payment::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $payment = new Payment();
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertNull($payment->getPeriod());
        $this->assertNull($payment->getTransactionID());
        $this->assertNull($payment->getDescription());
        $this->assertNull($payment->getSystemID());
        $this->assertSame(0, \count($payment->getPaymentMethod()));
        $this->assertSame(0, \count($payment->getWithholdingTax()));
    }

    public function testSetGetPaymentRefNo()
    {
        $payment = new Payment();
        $refNo   = "FT FT/1";
        $payment->setPaymentRefNo($refNo);
        $this->assertSame($refNo, $payment->getPaymentRefNo());
        try {
            $payment->setPaymentRefNo("");
            $this->fail("Set PaymentRefNo to a empty string must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $payment->setPaymentRefNo(\str_pad($refNo, 61, "1", STR_PAD_RIGHT));
            $this->fail("Set PaymentRefNo to a string length higer than 60 must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $payment->setPaymentRefNo("FTFT/1");
            $this->fail("Set PaymentRefNo must respect the regexp or throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    public function testSetGetAtcud()
    {
        $payment = new Payment();
        $atcud   = "ATCUD";
        $payment->setATCUD($atcud);
        $this->assertSame($atcud, $payment->getATCUD());
        try {
            $payment->setATCUD("");
            $this->fail("Set ATCUD to a empty string must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $payment->setATCUD(\str_pad($atcud, 101, "A"));
            $this->fail("Set ATCUD to a string length higer than 60 must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetPeriod()
    {
        $payment = new Payment();
        $period  = 9;
        $payment->setPeriod($period);
        $this->assertSame($period, $payment->getPeriod());
        try {
            $payment->setPeriod(0);
            $this->fail("Set Period to a number less than 1 must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $payment->setPeriod(13);
            $this->fail("Set Period to a number higer than 12 must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $this->assertSame($period, $payment->getPeriod());
        $payment->setPeriod(null);
        $this->assertNull($payment->getPeriod());
        $payment->setPeriod($period);
        $this->assertSame($period, $payment->getPeriod());
    }

    /**
     *
     */
    public function testSetGetTransactionID()
    {
        $payment = new Payment();
        $trans   = new TransactionID();
        $payment->setTransactionID($trans);
        $this->assertInstanceOf(TransactionID::class,
            $payment->getTransactionID());
        $payment->setTransactionID(null);
        $this->assertNull($payment->getTransactionID());
    }

    /**
     *
     */
    public function testSetGetTransactionDate()
    {
        $payment = new Payment();
        $date    = new RDate();
        $payment->setTransactionDate($date, false);
        $this->assertSame($date, $payment->getTransactionDate());
        $this->assertNull($payment->getPeriod());
        $payment->setTransactionDate($date);
        $this->assertSame(
            $date->format(RDate::MONTH_SHORT), \strval($payment->getPeriod())
        );
    }

    /**
     *
     */
    public function testSetGetPaymentType()
    {
        $payment = new Payment();
        $type    = new PaymentType(PaymentType::RC);
        $payment->setPaymentType($type);
        $this->assertSame($type->get(), $payment->getPaymentType()->get());
    }

    /**
     *
     */
    public function testSetGetDescription()
    {
        $payment = new Payment();
        $desc    = "Descriptin of payment";
        $payment->setDescription($desc);
        $this->assertSame($desc, $payment->getDescription());

        $payment->setDescription(\str_pad($desc, 299, "A"));
        $this->assertSame(200, \strlen($payment->getDescription()));

        try {
            $payment->setDescription("");
            $this->fail("Set descriptin with a empty string must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $payment->setDescription(null);
        $this->assertNull($payment->getDescription());
    }

    /**
     *
     */
    public function testSetGetSystmeId()
    {
        $payment  = new Payment();
        $systemID = "System ID";
        $payment->setSystemID($systemID);
        $this->assertSame($systemID, $payment->getSystemID());

        $payment->setSystemID(\str_pad($systemID, 99, "A"));
        $this->assertSame(60, \strlen($payment->getSystemID()));

        try {
            $payment->setSystemID("");
            $this->fail("Set System ID with a empty string must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $payment->setSystemID(null);
        $this->assertNull($payment->getSystemID());
    }

    /**
     *
     */
    public function testSetGetDocumentStatus()
    {
        $payment = new Payment();
        $status  = new DocumentStatus();
        $payment->setDocumentStatus($status);
        $this->assertInstanceOf(DocumentStatus::class,
            $payment->getDocumentStatus());
    }

    /**
     *
     */
    public function testPymentMethod()
    {
        $payment = new Payment();
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $method = new PaymentMethod();
            $method->setPaymentAmount($n + 0.99);
            $index  = $payment->addToPaymentMethod($method);
            $this->assertSame($index, $n);
            $this->assertSame(
                $n + 0.99,
                $payment->getPaymentMethod()[$index]->getPaymentAmount()
            );
        }
        $unset = 2;
        $payment->unsetPaymentMethod($unset);
        $this->assertSame($nMax - 1, \count($payment->getPaymentMethod()));
        $this->assertFalse($payment->issetPaymentMethod($unset));
    }

    /**
     *
     */
    public function testSetGetSourceId()
    {
        $payment  = new Payment();
        $sourceID = "Source ID";
        $payment->setSourceID($sourceID);
        $this->assertSame($sourceID, $payment->getSourceID());

        $payment->setSourceID(\str_pad($sourceID, 99, "A"));
        $this->assertSame(30, \strlen($payment->getSourceID()));

        try {
            $payment->setSourceID("");
            $this->fail("Set Source ID with a empty string must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetSystemEntryDate()
    {
        $payment = new Payment();
        $date    = new RDate();
        $payment->setSystemEntryDate($date);
        $this->assertSame($date, $payment->getSystemEntryDate());
    }

    /**
     *
     */
    public function testLine()
    {
        $payment = new Payment();
        $nMax    = 9;
        for ($n = 1; $n < $nMax; $n++) {
            $line  = new Line();
            $line->setLineNumber($n);
            $index = $payment->addToLine($line);
            $this->assertSame($index, $n - 1);
            $this->assertSame($n, $payment->getLine()[$index]->getLineNumber()
            );
        }
        $unset = 2;
        $payment->unsetLine($unset);
        $this->assertSame($nMax - 2, \count($payment->getLine()));
        $this->assertFalse($payment->issetPaymentMethod($unset));
    }

    /**
     *
     */
    public function testSetGetDocumentTotals()
    {
        $payment = new Payment();
        $totals  = new DocumentTotals();
        $payment->setDocumentTotals($totals);
        $this->assertInstanceOf(
            DocumentTotals::class, $payment->getDocumentTotals()
        );
    }

    /**
     *
     */
    public function testWithholdingTax()
    {
        $payment = new Payment();
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $tax   = new WithholdingTax();
            $tax->setWithholdingTaxAmount($n + 0.99);
            $index = $payment->addToWithholdingTax($tax);
            $this->assertSame($index, $n);
            $this->assertSame(
                $n + 0.99,
                $payment->getWithholdingTax()[$index]->getWithholdingTaxAmount()
            );
        }
        $unset = 2;
        $payment->unsetWithholdingTax($unset);
        $this->assertSame($nMax - 1, \count($payment->getWithholdingTax()));
        $this->assertFalse($payment->issetWithholdingTax($unset));
    }

    /**
     * Reads all Payments  from the Demo SAFT in Test\Ressources
     * and parse them to Payment class, after tahr generate a xml from the
     * Payment class and test if the xml strings are equal
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
            /* @var $paymentXml \SimpleXMLElement */
            $paymentXml = $paymentsStack[$i];
            $payment    = new Payment();
            $payment->parseXmlNode($paymentXml);

            $xmlRootNode   = new \SimpleXMLElement(
                '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
            );
            $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);

            $xml = $payment->createXmlNode($paymentsNode);

            try {
                $assertXml = $this->xmlIsEqual($paymentXml, $xml);

                $this->assertTrue(
                    $assertXml,
                    \sprintf("Fail on Payment index '%s' with mwssage '%s'",
                        $i + 1, $assertXml)
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf("Fail on Payment index '%s' with mwssage '%s'",
                        $i + 1, $e->getMessage()));
            }
        }
    }
}