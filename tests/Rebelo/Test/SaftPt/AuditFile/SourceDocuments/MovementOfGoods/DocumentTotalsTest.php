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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

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
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentTotals::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $doctotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $doctotals);
        $this->assertNull($doctotals->getCurrency(false));
        $this->assertFalse($doctotals->issetGrossTotal());
        $this->assertFalse($doctotals->issetTaxPayable());
        $this->assertFalse($doctotals->issetNetTotal());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $net       = 19.99;
        $this->assertTrue($docTotals->setNetTotal($net));
        $this->assertTrue($docTotals->issetNetTotal());
        $this->assertSame($net, $docTotals->getNetTotal());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $gross     = 99.99;
        $docTotals->setGrossTotal($gross);
        $this->assertTrue($docTotals->issetGrossTotal());
        $this->assertSame($gross, $docTotals->getGrossTotal());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetCurrency(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $currency  = $docTotals->getCurrency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());
    }

    /**
     * Reads all DocumentTotals from the Demo SAFT in Test\Ressources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
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
                $docTotals = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($totalsXml);

                $xmlRootNode       = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
                $sourceDocNode     = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $stockMovDocsNode  = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);
                $stockMovStackNode = $stockMovDocsNode->addChild(StockMovement::N_STOCKMOVEMENT);

                $xml = $docTotals->createXmlNode($stockMovStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($totalsXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stcoMovStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stcoMovStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $e->getMessage()
                        )
                    );
                }

                $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
                $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
                $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $xml        = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $totals->setGrossTotal(-9.03);
        $totals->setGrossTotal(-9.45);
        $totals->setTaxPayable(-9.74);

        $xml = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }
}