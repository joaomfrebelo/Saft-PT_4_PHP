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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    ShipFrom,
    ShipTo,
    WithholdingTax,
    WithholdingTaxType
};
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\{
    Line,
    Invoice,
    InvoiceType,
    DocumentStatus,
    DocumentTotals,
    SpecialRegimes,
    SalesInvoices
};

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class InvoiceTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Invoice::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $invoice = new Invoice();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNull($invoice->getPeriod());
        $this->assertNull($invoice->getTransactionID());
        $this->assertNull($invoice->getEacCode());
        $this->assertNull($invoice->getShipTo());
        $this->assertNull($invoice->getShipFrom());
        $this->assertNull($invoice->getMovementEndTime());
        $this->assertNull($invoice->getMovementStartTime());
        $this->assertSame(0, \count($invoice->getLine()));
        $this->assertSame(0, \count($invoice->getWithholdingTax()));
    }

    /**
     *
     */
    public function testDocumentNumber()
    {
        $invoice = new Invoice();
        $docNum  = "FT FT/999";
        $invoice->setInvoiceNo($docNum);
        $this->assertSame($docNum, $invoice->getInvoiceNo());
        try {
            $invoice->setInvoiceNo("ORCA /1");
            $this->fail("Set a wrong DocumentNumber should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testDocumentStatus()
    {
        $invoice = new Invoice();
        $status  = new DocumentStatus();
        $invoice->setDocumentStatus($status);
        $this->assertInstanceOf(
            DocumentStatus::class, $invoice->getDocumentStatus()
        );
    }

    /**
     *
     */
    public function testAtcud()
    {
        $invoice = new Invoice();
        $atcud   = "999";
        $invoice->setAtcud($atcud);
        $this->assertSame($atcud, $invoice->getAtcud());
        try {
            $invoice->setAtcud(str_pad($atcud, 120, "A"));
            $this->fail("Set a wrong ATCUD should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testHash()
    {
        $invoice = new Invoice();
        $hash    = \md5("hash");
        $invoice->setHash($hash);
        $this->assertSame($hash, $invoice->getHash());
        try {
            $invoice->setHash(str_pad($hash, 200, "A"));
            $this->fail("Set a Hash length to big should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testHashControl()
    {
        $invoice = new Invoice();
        $control = "1";
        $invoice->setHashControl($control);
        $this->assertSame($control, $invoice->getHashControl());
        try {
            $invoice->setHashControl(\str_pad("Z1", 71, "9"));
            $this->fail("Set a wrong HashControl should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testPeriod()
    {
        $invoice = new Invoice();
        $period  = 9;
        $invoice->setPeriod($period);
        $this->assertSame($period, $invoice->getPeriod());
        try {
            $invoice->setPeriod(0);
            $this->fail("Set periodo to less than 1 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $invoice->setPeriod(13);
            $this->fail("Set periodo to greater than 12 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $invoice->setPeriod(null);
        $this->assertNull($invoice->getPeriod());
    }

    /**
     *
     */
    public function testInvoiceDate()
    {
        $invoice = new Invoice();
        $date    = new RDate();
        $invoice->setInvoiceDate($date);
        $this->assertSame($date, $invoice->getInvoiceDate());
    }

    /**
     *
     */
    public function testInvoiceType()
    {
        $invoice = new Invoice();
        $type    = InvoiceType::FT;
        $invoice->setInvoiceType(new InvoiceType($type));
        $this->assertSame($type, $invoice->getInvoiceType()->get());
    }

    /**
     *
     */
    public function testSpecialRegimes()
    {
        $sepReg  = new SpecialRegimes();
        $invoice = new Invoice();
        $invoice->setSpecialRegimes($sepReg);
        $this->assertInstanceOf(
            SpecialRegimes::class, $invoice->getSpecialRegimes()
        );
    }

    /**
     *
     */
    public function testSourceID()
    {
        $invoice = new Invoice();
        $source  = "Rebelo";
        $invoice->setSourceID($source);
        $this->assertSame($source, $invoice->getSourceID());
        $invoice->setSourceID(\str_pad($source, 50, "9"));
        $this->assertSame(30, \strlen($invoice->getSourceID()));
    }

    /**
     *
     */
    public function testEACCode()
    {
        $invoice = new Invoice();
        $eaccode = "49499";
        $invoice->setEacCode($eaccode);
        $this->assertSame($eaccode, $invoice->getEacCode());
        $invoice->setEacCode(null);
        $this->assertNull($invoice->getEacCode());
        try {
            $invoice->setEacCode("9999");
            $this->fail("Set a wrong eaccode should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $invoice->setEacCode("999999");
            $this->fail("Set a wrong eaccode should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSystemEntryDate()
    {
        $invoice = new Invoice();
        $date    = new RDate();
        $invoice->setSystemEntryDate($date);
        $this->assertSame($date, $invoice->getSystemEntryDate());
    }

    /**
     *
     */
    public function testTransactionId()
    {
        $invoice     = new Invoice();
        $transaction = new \Rebelo\SaftPt\AuditFile\TransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $invoice->setTransactionID($transaction);
        $this->assertSame($transaction, $invoice->getTransactionID());
        $invoice->setTransactionID(null);
        $this->assertNull($invoice->getTransactionID());
    }

    /**
     *
     */
    public function testCustomerId()
    {
        $invoice = new Invoice();
        $id      = "A999";
        $invoice->setCustomerID($id);
        $this->assertSame($id, $invoice->getCustomerID());
        try {
            $invoice->setCustomerID(\str_pad($id, 31, "999999"));
            $this->fail("Set a wrong customerid should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testShipTo()
    {
        $invoice = new Invoice();
        $shipTo  = new ShipTo();
        $invoice->setShipTo($shipTo);
        $this->assertInstanceOf(ShipTo::class, $invoice->getShipTo());
        $invoice->setShipTo(null);
        $this->assertNull($invoice->getShipTo());
    }

    /**
     *
     */
    public function testShipFrom()
    {
        $invoice  = new Invoice();
        $shipFrom = new ShipFrom();
        $invoice->setShipFrom($shipFrom);
        $this->assertInstanceOf(ShipFrom::class, $invoice->getShipFrom());
        $invoice->setShipFrom(null);
        $this->assertNull($invoice->getShipFrom());
    }

    /**
     *
     */
    public function testMovementEndTime()
    {
        $invoice = new Invoice();
        $endTime = new RDate();
        $invoice->setMovementEndTime($endTime);
        $this->assertSame($endTime, $invoice->getMovementEndTime());
        $invoice->setMovementEndTime(null);
        $this->assertNull($invoice->getMovementEndTime());
    }

    /**
     *
     */
    public function testMovementStartTime()
    {
        $invoice   = new Invoice();
        $startTime = new RDate();
        $invoice->setMovementStartTime($startTime);
        $this->assertSame($startTime, $invoice->getMovementStartTime());
        $invoice->setMovementStartTime(null);
        $this->assertNull($invoice->getMovementStartTime());
    }

    /**
     *
     */
    public function testLine()
    {
        $invoice = new Invoice();
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $line  = new Line();
            $line->setLineNumber($n + 1);
            $index = $invoice->addToLine($line);
            $this->assertSame($n, $index);
            $this->assertSame(
                $n + 1, $invoice->getLine()[$n]->getLineNumber()
            );
        }

        $this->assertSame($nMax, \count($invoice->getLine()));

        $unset = 2;
        $invoice->unsetLine($unset);
        $this->assertFalse($invoice->issetLine($unset));
        $this->assertSame($nMax - 1, \count($invoice->getLine()));
    }

    /**
     *
     */
    public function testDocumentTotals()
    {
        $invoice = new Invoice();
        $totals  = new DocumentTotals();
        $invoice->setDocumentTotals($totals);
        $this->assertSame($totals, $invoice->getDocumentTotals());
    }

    /**
     *
     */
    public function testWithholdingTax()
    {
        $invoice = new Invoice();
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $tax   = new WithholdingTax();
            $tax->setWithholdingTaxAmount($n + 0.99);
            $index = $invoice->addToWithholdingTax($tax);
            $this->assertSame($index, $n);
            $this->assertSame(
                $n + 0.99,
                $invoice->getWithholdingTax()[$index]->getWithholdingTaxAmount()
            );
        }
        $unset = 2;
        $invoice->unsetWithholdingTax($unset);
        $this->assertSame($nMax - 1, \count($invoice->getWithholdingTax()));
        $this->assertFalse($invoice->issetWithholdingTax($unset));
    }

    /**
     * Reads all Invoice from the Demo SAFT in Test\Ressources
     * and parse then to Invoice class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $invoiceStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{SalesInvoices::N_SALESINVOICES}
            ->{Invoice::N_INVOICE};

        if ($invoiceStack->count() === 0) {
            $this->fail("No Invoices in XML");
        }

        for ($n = 0; $n < $invoiceStack->count(); $n++) {
            /* @var $invoiceXml \SimpleXMLElement */
            $invoiceXml = $invoiceStack[$n];
            $invoice    = new Invoice();
            $invoice->parseXmlNode($invoiceXml);

            $xmlRootNode   = new \SimpleXMLElement(
                '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
            );
            $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $invoiceNode   = $sourceDocNode->addChild(SalesInvoices::N_SALESINVOICES);

            $xml = $invoice->createXmlNode($invoiceNode);

            file_put_contents("d:/todelete/xml.xml", $xml->asXML());
            file_put_contents("d:/todelete/saftxml.xml", $invoiceXml->asXML());
            try {
                $assertXml = $this->xmlIsEqual($invoiceXml, $xml);
                $this->assertTrue($assertXml,
                    \sprintf("Fail on Document '%s' with error '%s'",
                        $invoiceXml->{Invoice::N_INVOICENO}, $assertXml)
                );
            } catch (\Exception | \Error $e) {
                $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                        $invoiceXml->{Invoice::N_INVOICENO}, $e->getMessage()));
            }
        }
    }
}