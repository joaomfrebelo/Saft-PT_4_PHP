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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    SalesInvoices\Invoice,
    SalesInvoices\SalesInvoices
};

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SalesInvoicesTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(SalesInvoices::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $salesInvoices = new SalesInvoices();
        $this->assertInstanceOf(SalesInvoices::class, $salesInvoices);
        $this->assertSame(0, \count($salesInvoices->getInvoice()));
    }

    /**
     *
     */
    public function testNumberOfEntries()
    {
        $salesInvoices = new SalesInvoices();
        $entries       = [0, 999];
        foreach ($entries as $num) {
            $salesInvoices->setNumberOfEntries($num);
            $this->assertSame($num, $salesInvoices->getNumberOfEntries());
        }
        try {
            $salesInvoices->setNumberOfEntries(-1);
            $this->fail("Set NumberOdEntries to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testTotalDebit()
    {
        $salesInvoices = new SalesInvoices();
        $debitStack    = [0.0, 9.99];
        foreach ($debitStack as $debit) {
            $salesInvoices->setTotalDebit($debit);
            $this->assertSame($debit, $salesInvoices->getTotalDebit());
        }
        try {
            $salesInvoices->setTotalDebit(-0.19);
            $this->fail("Set TotalDebit to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testTotalCredit()
    {
        $salesInvoices = new SalesInvoices();
        $creditStack   = [0.0, 9.99];
        foreach ($creditStack as $creditStack) {
            $salesInvoices->setTotalCredit($creditStack);
            $this->assertSame($creditStack, $salesInvoices->getTotalCredit());
        }
        try {
            $salesInvoices->setTotalDebit(-0.19);
            $this->fail("Set TotalCredit to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testInvoice()
    {
        $salesInvoices = new SalesInvoices();
        $nMax          = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $invoice = new Invoice();
            $invoice->setAtcud(\strval($n));
            $index   = $salesInvoices->addToInvoice($invoice);
            $this->assertSame($n, $index);
            $this->assertSame(
                \strval($n), $salesInvoices->getInvoice()[$n]->getAtcud()
            );
        }

        $this->assertSame($nMax, \count($salesInvoices->getInvoice()));

        $unset = 2;
        $salesInvoices->unsetInvoice($unset);
        $this->assertFalse($salesInvoices->issetInvoice($unset));
        $this->assertSame($nMax - 1, \count($salesInvoices->getInvoice()));
    }

    /**
     * Reads SalesInvoices from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $salesInvoicesXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{SalesInvoices::N_SALESINVOICES};

        if ($salesInvoicesXml->count() === 0) {
            $this->fail("No SalesInvoices in XML");
        }



        $salesInvoices = new SalesInvoices();
        $salesInvoices->parseXmlNode($salesInvoicesXml);

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $salesInvoices->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($salesInvoicesXml, $xml);
            $this->assertTrue($assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}