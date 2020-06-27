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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotalsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentTotals::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $doctotals = new DocumentTotals();
        $this->assertInstanceOf(DocumentTotals::class, $doctotals);
        $this->assertNull($doctotals->getCurrency());

        $taxPayable = 9.49;
        $doctotals->setTaxPayable($taxPayable);
        $this->assertSame($taxPayable, $doctotals->getTaxPayable());
        try {
            $doctotals->setTaxPayable(-0.01);
            $this->fail("Set TaxPayable to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $netTotal = 9.49;
        $doctotals->setNetTotal($netTotal);
        $this->assertSame($netTotal, $doctotals->getNetTotal());
        try {
            $doctotals->setNetTotal(-0.01);
            $this->fail("Set NetTotal to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $grossTotal = 9.49;
        $doctotals->setGrossTotal($grossTotal);
        $this->assertSame($grossTotal, $doctotals->getGrossTotal());
        try {
            $doctotals->setGrossTotal(-0.01);
            $this->fail("Set GrossTotal to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $currency = new Currency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $doctotals->setCurrency($currency);
        $this->assertInstanceOf(Currency::class, $doctotals->getCurrency());
    }

    /**
     * Reads all DocumentTotals from the Demo SAFT in Test\Ressources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $stockMovDocStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS}
            ->{StockMovement::N_STOCKMOVEMENT};

        if ($stockMovDocStack->count() === 0) {
            $this->fail("No workdocuments in XML");
        }

        for ($i = 0; $i < $stockMovDocStack->count(); $i++) {
            $stcoMovStackXml = $stockMovDocStack[$i];
            $totalsStack     = $stcoMovStackXml->{DocumentTotals::N_DOCUMENTTOTALS};

            if ($totalsStack->count() === 0) {
                $this->fail("No DocumemntTotals in StockMovement");
            }

            for ($l = 0; $l < $totalsStack->count(); $l++) {
                /* @var $totalsXml \SimpleXMLElement */
                $totalsXml = $totalsStack[$l];
                $docTotals = new DocumentTotals();
                $docTotals->parseXmlNode($totalsXml);

                $xmlRootNode       = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode     = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $stockMovDocsNode  = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);
                $stockMovStackNode = $stockMovDocsNode->addChild(StockMovement::N_STOCKMOVEMENT);

                $xml = $docTotals->createXmlNode($stockMovStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($totalsXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Document '%s' with error '%s'",
                            $stcoMovStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                            $stcoMovStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $e->getMessage()));
                }
            }
        }
    }
}