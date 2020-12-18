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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
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
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(SalesInvoices::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $this->assertInstanceOf(SalesInvoices::class, $salesInvoices);
        $this->assertSame(0, \count($salesInvoices->getInvoice()));

        $this->assertFalse($salesInvoices->issetNumberOfEntries());
        $this->assertFalse($salesInvoices->issetTotalCredit());
        $this->assertFalse($salesInvoices->issetTotalDebit());
        $this->assertNull($salesInvoices->getDocTableTotalCalc());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfEntries(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $entries       = [0, 999];
        foreach ($entries as $num) {
            $this->assertTrue($salesInvoices->setNumberOfEntries($num));
            $this->assertSame($num, $salesInvoices->getNumberOfEntries());
            $this->assertTrue($salesInvoices->issetNumberOfEntries());
        }

        $wrong = -1;
        $this->assertFalse($salesInvoices->setNumberOfEntries($wrong));
        $this->assertSame($wrong, $salesInvoices->getNumberOfEntries());
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalDebit(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $debitStack    = [0.0, 9.99];
        foreach ($debitStack as $debit) {
            $this->assertTrue($salesInvoices->setTotalDebit($debit));
            $this->assertSame($debit, $salesInvoices->getTotalDebit());
            $this->assertTrue($salesInvoices->issetTotalDebit());
        }

        $wrong = -19.9;
        $this->assertFalse($salesInvoices->setTotalDebit($wrong));
        $this->assertSame($wrong, $salesInvoices->getTotalDebit());
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalCredit(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $creditStack   = [0.0, 9.99];
        foreach ($creditStack as $credit) {
            $this->assertTrue($salesInvoices->setTotalCredit($credit));
            $this->assertSame($credit, $salesInvoices->getTotalCredit());
            $this->assertTrue($salesInvoices->issetTotalCredit());
        }

        $wrong = -19.9;
        $this->assertFalse($salesInvoices->setTotalCredit($wrong));
        $this->assertSame($wrong, $salesInvoices->getTotalCredit());
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInvoice(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $nMax          = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $invoice = $salesInvoices->addInvoice();
            $invoice->setAtcud(\strval($n));
            $this->assertSame(
                \strval($n), $salesInvoices->getInvoice()[$n]->getAtcud()
            );
        }

        $this->assertSame($nMax, \count($salesInvoices->getInvoice()));
    }

    /**
     * Reads SalesInvoices from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $salesInvoicesXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{SalesInvoices::N_SALESINVOICES};

        if ($salesInvoicesXml->count() === 0) {
            $this->fail("No SalesInvoices in XML");
        }



        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $salesInvoices->parseXmlNode($salesInvoicesXml);

        $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $salesInvoices->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($salesInvoicesXml, $xml);
            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }

        $this->assertEmpty($salesInvoices->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($salesInvoices->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $workingDocsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $salesInvoices   = new SalesInvoices(new ErrorRegister());
        $xml             = $salesInvoices->createXmlNode($workingDocsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($salesInvoices->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $workingDocsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $salesInvoices   = new SalesInvoices(new ErrorRegister());
        $salesInvoices->setNumberOfEntries(-1);
        $salesInvoices->setTotalCredit(-0.99);
        $salesInvoices->setTotalDebit(-0.95);

        $xml = $salesInvoices->createXmlNode($workingDocsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($salesInvoices->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($salesInvoices->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetOrder(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $inoiceNo      = array(
            "FT FT/1",
            "FS FS/4",
            "FT FT/5",
            "FT FT/2",
            "FT FT/9",
            "FT FT/4",
            "FT FT/3",
            "FT FT/10",
            "FS FS/3",
            "FS FS/2",
            "FS FS/1",
            "FT B/3",
            "FT B/1",
            "FT B/2",
        );
        foreach ($inoiceNo as $no) {
            $salesInvoices->addInvoice()->setInvoiceNo($no);
        }

        $order = $salesInvoices->getOrder();
        $this->assertSame(array("FS", "FT"), \array_keys($order));
        $this->assertSame(array("FS"), \array_keys($order["FS"]));
        $this->assertSame(array("B", "FT"), \array_keys($order["FT"]));
        $this->assertSame(
            array(1, 2, 3, 4, 5, 9, 10), \array_keys($order["FT"]["FT"])
        );
        $this->assertSame(array(1, 2, 3), \array_keys($order["FT"]["B"]));
        $this->assertSame(array(1, 2, 3, 4), \array_keys($order["FS"]["FS"]));

        foreach ($order as $type => $serieStack) {
            foreach ($serieStack as $serie => $noSatck) {
                foreach ($noSatck as $no => $invoice) {
                    /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
                    $this->assertSame(
                        \sprintf("%s %s/%s", $type, $serie, $no),
                        $invoice->getInvoiceNo()
                    );
                }
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDuplicateNumber(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $inoiceNo      = array(
            "FT FT/1",
            "FS FS/4",
            "FT FT/1",
            "FT FT/2",
            "FT FT/9",
            "FT FT/4",
            "FT FT/3",
            "FT FT/10",
            "FS FS/3",
            "FS FS/2",
            "FS FS/1",
            "FT B/3",
            "FT B/1",
            "FT B/2",
        );
        foreach ($inoiceNo as $no) {
            $salesInvoices->addInvoice()->setInvoiceNo($no);
        }

        $salesInvoices->getOrder();
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNoNumber(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $inoiceNo      = array(
            "FT FT/1",
            "FT B/2"
        );
        foreach ($inoiceNo as $no) {
            $salesInvoices->addInvoice()->setInvoiceNo($no);
        }
        $salesInvoices->addInvoice();
        $salesInvoices->getOrder();
        $this->assertNotEmpty($salesInvoices->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocTableTotalCalc(): void
    {
        $salesInvoices = new SalesInvoices(new ErrorRegister());
        $salesInvoices->setDocTableTotalCalc(new \Rebelo\SaftPt\Validate\DocTableTotalCalc);
        $this->assertInstanceOf(
            \Rebelo\SaftPt\Validate\DocTableTotalCalc::class,
            $salesInvoices->getDocTableTotalCalc()
        );
    }
}
