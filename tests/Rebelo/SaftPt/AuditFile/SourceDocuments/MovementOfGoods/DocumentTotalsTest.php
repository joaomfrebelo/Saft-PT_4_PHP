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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\TXmlTest;

/**
 * Line
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class DocumentTotalsTest extends TestCase
{

    use TXmlTest;

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(DocumentTotals::class))->testReflection(DocumentTotals::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $docTotals);
        $this->assertNull($docTotals->getCurrency(false));
        $this->assertFalse($docTotals->issetGrossTotal());
        $this->assertFalse($docTotals->issetTaxPayable());
        $this->assertFalse($docTotals->issetNetTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $net       = new Decimal("19.99");
        $this->assertTrue($docTotals->setNetTotal($net));
        $this->assertTrue($docTotals->issetNetTotal());
        $this->assertSame($net, $docTotals->getNetTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $gross     = new Decimal("99.99");
        $docTotals->setGrossTotal($gross);
        $this->assertTrue($docTotals->issetGrossTotal());
        $this->assertSame($gross, $docTotals->getGrossTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetCurrency(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $currency  = $docTotals->getCurrency();
        $currency?->setCurrencyCode(CurrencyCode::ISO_GBP);
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());
    }

    /**
     * Reads all DocumentTotals from the Demo SAFT in Test\Resources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @throws AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $stockMovDocStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCE_DOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENT_OF_GOODS}
            ->{StockMovement::N_STOCK_MOVEMENT};

        if ($stockMovDocStack->count() === 0) {
            $this->fail("No work documents in XML");
        }

        for ($i = 0; $i < $stockMovDocStack->count(); $i++) {
            $stockMovStackXml = $stockMovDocStack[$i];
            $totalsStack      = $stockMovStackXml->{DocumentTotals::N_DOCUMENT_TOTALS};

            if ($totalsStack->count() === 0) {
                $this->fail("No DocumentTotals in StockMovement");
            }

            for ($l = 0; $l < $totalsStack->count(); $l++) {
                /* @var $totalsXml \SimpleXMLElement */
                $totalsXml = $totalsStack[$l];
                $docTotals = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($totalsXml);

                $xmlRootNode       = (new AuditFile())->createRootElement();
                $sourceDocNode     = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
                $stockMovDocsNode  = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENT_OF_GOODS);
                $stockMovStackNode = $stockMovDocsNode->addChild(StockMovement::N_STOCK_MOVEMENT);

                $xml = $docTotals->createXmlNode($stockMovStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($totalsXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovStackXml->{StockMovement::N_DOCUMENT_NUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception|\Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovStackXml->{StockMovement::N_DOCUMENT_NUMBER},
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<" . StockMovement::N_STOCK_MOVEMENT . "></" . StockMovement::N_STOCK_MOVEMENT . ">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $xml        = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<" . StockMovement::N_STOCK_MOVEMENT . "></" . StockMovement::N_STOCK_MOVEMENT . ">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $totals->setGrossTotal(new Decimal("-9.03"));
        $totals->setGrossTotal(new Decimal("-9.45"));
        $totals->setTaxPayable(new Decimal("-9.74"));

        $xml = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }
}
