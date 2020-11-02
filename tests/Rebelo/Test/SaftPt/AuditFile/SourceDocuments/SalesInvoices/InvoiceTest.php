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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    ShipFrom,
    ShipTo
};
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\{
    Invoice,
    InvoiceType,
    DocumentStatus,
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
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Invoice::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNull($invoice->getPeriod());
        $this->assertNull($invoice->getTransactionID(false));
        $this->assertNull($invoice->getEacCode());
        $this->assertNull($invoice->getShipTo(false));
        $this->assertNull($invoice->getShipFrom(false));
        $this->assertNull($invoice->getMovementEndTime());
        $this->assertNull($invoice->getMovementStartTime());
        $this->assertNull($invoice->getDocTotalcal());
        $this->assertSame(0, \count($invoice->getLine()));
        $this->assertSame(0, \count($invoice->getWithholdingTax()));

        $this->assertFalse($invoice->issetAtcud());
        $this->assertFalse($invoice->issetCustomerID());
        $this->assertFalse($invoice->issetDocumentStatus());
        $this->assertFalse($invoice->issetDocumentTotals());
        $this->assertFalse($invoice->issetHash());
        $this->assertFalse($invoice->issetHashControl());
        $this->assertFalse($invoice->issetInvoiceDate());
        $this->assertFalse($invoice->issetInvoiceNo());
        $this->assertFalse($invoice->issetInvoiceType());
        $this->assertFalse($invoice->issetSourceID());
        $this->assertFalse($invoice->issetSpecialRegimes());
        $this->assertFalse($invoice->issetSystemEntryDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentNumber(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $docNum  = "FT FT/999";
        $this->assertTrue($invoice->setInvoiceNo($docNum));
        $this->assertTrue($invoice->issetInvoiceNo());
        $this->assertSame($docNum, $invoice->getInvoiceNo());

        $wrong = "ORCA /1";
        $this->assertFalse($invoice->setInvoiceNo($wrong));
        $this->assertSame($wrong, $invoice->getInvoiceNo());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());

        $invoice->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($invoice->setInvoiceNo(""));
        $this->assertSame("", $invoice->getInvoiceNo());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentStatus(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentStatus::class, $invoice->getDocumentStatus()
        );
        $this->assertTrue($invoice->issetDocumentStatus());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testAtcud(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $atcud   = "999";
        $invoice->setAtcud($atcud);
        $this->assertTrue($invoice->issetAtcud());
        $this->assertSame($atcud, $invoice->getAtcud());

        $wrong = \str_pad($atcud, 120, "A");
        $this->assertFalse($invoice->setAtcud($wrong));
        $this->assertSame($wrong, $invoice->getAtcud());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHash(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $hash    = \md5("hash");
        $this->assertTrue($invoice->setHash($hash));
        $this->assertTrue($invoice->issetHash());
        $this->assertSame($hash, $invoice->getHash());

        $wrong = \str_pad($hash, 200, "A");
        $this->assertFalse($invoice->setHash($wrong));
        $this->assertSame($wrong, $invoice->getHash());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHashControl(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $control = "1";
        $this->assertTrue($invoice->setHashControl($control));
        $this->assertTrue($invoice->issetHashControl());
        $this->assertSame($control, $invoice->getHashControl());

        $wrong = \str_pad("Z1", 71, "9");
        $this->assertFalse($invoice->setHashControl($wrong));
        $this->assertSame($wrong, $invoice->getHashControl());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testPeriod(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $period  = 9;
        $this->assertTrue($invoice->setPeriod($period));
        $this->assertSame($period, $invoice->getPeriod());

        $wrong = 0;
        $this->assertFalse($invoice->setPeriod($wrong));
        $this->assertSame($wrong, $invoice->getPeriod());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());

        $wrong2 = 13;
        $invoice->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($invoice->setPeriod($wrong2));
        $this->assertSame($wrong2, $invoice->getPeriod());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($invoice->setPeriod(null));
        $this->assertNull($invoice->getPeriod());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInvoiceDate(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $date    = new RDate();
        $invoice->setInvoiceDate($date);
        $this->assertSame($date, $invoice->getInvoiceDate());
        $this->assertTrue($invoice->issetInvoiceDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInvoiceType(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $type    = InvoiceType::FT;
        $invoice->setInvoiceType(new InvoiceType($type));
        $this->assertSame($type, $invoice->getInvoiceType()->get());
        $this->assertTrue($invoice->issetInvoiceType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSpecialRegimes(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $this->assertInstanceOf(
            SpecialRegimes::class, $invoice->getSpecialRegimes()
        );
        $this->assertTrue($invoice->issetSpecialRegimes());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSourceID(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $source  = "Rebelo";
        $this->assertTrue($invoice->setSourceID($source));
        $this->assertTrue($invoice->issetSourceID());
        $this->assertSame($source, $invoice->getSourceID());
        $this->assertTrue($invoice->setSourceID(\str_pad($source, 50, "9")));
        $this->assertSame(30, \strlen($invoice->getSourceID()));

        $this->assertFalse($invoice->setInvoiceNo(""));
        $this->assertSame("", $invoice->getInvoiceNo());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEACCode(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $eaccode = "49499";
        $this->assertTrue($invoice->setEacCode($eaccode));
        $this->assertSame($eaccode, $invoice->getEacCode());
        $this->assertTrue($invoice->setEacCode(null));
        $this->assertNull($invoice->getEacCode());

        $wrong = "9999";
        $this->assertFalse($invoice->setEacCode($wrong));
        $this->assertSame($wrong, $invoice->getEacCode());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());

        $wrong2 = "999999";
        $invoice->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($invoice->setEacCode($wrong2));
        $this->assertSame($wrong2, $invoice->getEacCode());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSystemEntryDate(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $date    = new RDate();
        $invoice->setSystemEntryDate($date);
        $this->assertSame($date, $invoice->getSystemEntryDate());
        $this->assertTrue($invoice->issetSystemEntryDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTransactionId(): void
    {
        $invoice     = new Invoice(new ErrorRegister());
        $transaction = $invoice->getTransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $this->assertSame($transaction, $invoice->getTransactionID());
        $invoice->setTransactionIDAsNull();
        $this->assertNull($invoice->getTransactionID(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCustomerId(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $id      = "A999";
        $invoice->setCustomerID($id);
        $this->assertTrue($invoice->issetCustomerID());
        $this->assertSame($id, $invoice->getCustomerID());

        $wrong = \str_pad($id, 31, "999999");
        $this->assertFalse($invoice->setCustomerID($wrong));
        $this->assertSame($wrong, $invoice->getCustomerID());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipTo(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $this->assertInstanceOf(ShipTo::class, $invoice->getShipTo());
        $invoice->setShipToAsNull();
        $this->assertNull($invoice->getShipTo(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipFrom(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $this->assertInstanceOf(ShipFrom::class, $invoice->getShipFrom());
        $invoice->setShipFromAsNull();
        $this->assertNull($invoice->getShipFrom(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementEndTime(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $endTime = new RDate();
        $invoice->setMovementEndTime($endTime);
        $this->assertSame($endTime, $invoice->getMovementEndTime());
        $invoice->setMovementEndTime(null);
        $this->assertNull($invoice->getMovementEndTime());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementStartTime(): void
    {
        $invoice   = new Invoice(new ErrorRegister());
        $startTime = new RDate();
        $invoice->setMovementStartTime($startTime);
        $this->assertSame($startTime, $invoice->getMovementStartTime());
        $invoice->setMovementStartTime(null);
        $this->assertNull($invoice->getMovementStartTime());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testLine(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $invoice->addLine();
            $this->assertSame(
                $n + 1, $invoice->getLine()[$n]->getLineNumber()
            );
        }

        $this->assertSame($nMax, \count($invoice->getLine()));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentTotals(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $totals  = $invoice->getDocumentTotals();
        $this->assertSame($totals, $invoice->getDocumentTotals());
        $this->assertTrue($invoice->issetDocumentTotals());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWithholdingTax(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $tax = $invoice->addWithholdingTax();
            $tax->setWithholdingTaxAmount($n + 0.99);
            $this->assertSame(
                $n + 0.99,
                $invoice->getWithholdingTax()[$n]->getWithholdingTaxAmount()
            );
        }

        $this->assertSame($nMax, \count($invoice->getWithholdingTax()));
    }

    /**
     * Reads all Invoice from the Demo SAFT in Test\Ressources
     * and parse then to Invoice class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
            return;
        }

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
            $invoice    = new Invoice(new ErrorRegister());
            $invoice->parseXmlNode($invoiceXml);

            $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
            $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $invoiceNode   = $sourceDocNode->addChild(SalesInvoices::N_SALESINVOICES);

            $xml = $invoice->createXmlNode($invoiceNode);
            $xml->asXML("d:/todelete/invoice.xml");
            try {
                $assertXml = $this->xmlIsEqual($invoiceXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $invoiceXml->{Invoice::N_INVOICENO}, $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $invoiceXml->{Invoice::N_INVOICENO}, $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($invoice->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($invoice->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($invoice->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $invoiceNode = new \SimpleXMLElement(
            "<".SalesInvoices::N_SALESINVOICES."></".SalesInvoices::N_SALESINVOICES.">"
        );
        $invoice     = new Invoice(new ErrorRegister());
        $xml         = $invoice->createXmlNode($invoiceNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($invoice->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($invoice->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $invoiceNode = new \SimpleXMLElement(
            "<".SalesInvoices::N_SALESINVOICES."></".SalesInvoices::N_SALESINVOICES.">"
        );
        $invoice     = new Invoice(new ErrorRegister());
        $invoice->setAtcud("");
        $invoice->setCustomerID("");
        $invoice->setEacCode("");
        $invoice->setHash("");
        $invoice->setHashControl("");
        $invoice->setInvoiceNo("");
        $invoice->setPeriod(0);
        $invoice->setSourceID("");

        $xml = $invoice->createXmlNode($invoiceNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($invoice->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($invoice->getErrorRegistor()->getLibXmlError());
    }

   /**
     * @author João Rebelo
     * @test
     */
    public function testDocTotalcal(): void
    {
        $invoice = new Invoice(new ErrorRegister());
        $invoice->setDocTotalcal(new \Rebelo\SaftPt\Validate\DocTotalCalc());
        $this->assertInstanceOf(
            \Rebelo\SaftPt\Validate\DocTotalCalc::class,
            $invoice->getDocTotalcal()
        );
    }
}