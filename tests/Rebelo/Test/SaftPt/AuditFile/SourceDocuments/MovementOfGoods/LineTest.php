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
use Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTax;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class LineTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(Line::class, $line);
        $this->assertSame([], $line->getOrderReferences());
        $this->assertNull($line->getProductSerialNumber(false));
        $this->assertNull($line->getDebitAmount());
        $this->assertNull($line->getCreditAmount());
        $this->assertNull($line->getTaxExemptionReason());
        $this->assertNull($line->getTaxExemptionCode());
        $this->assertNull($line->getSettlementAmount());
        $this->assertNull($line->getCustomsInformation(false));

        $this->assertFalse($line->issetDescription());
        $this->assertFalse($line->issetLineNumber());
        $this->assertFalse($line->issetProductCode());
        $this->assertFalse($line->issetProductDescription());
        $this->assertFalse($line->issetQuantity());
        $this->assertFalse($line->issetUnitOfMeasure());
        $this->assertFalse($line->issetUnitPrice());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetLineNumber(): void
    {
        $line = new Line(new ErrorRegister());
        $num  = 1;
        $this->assertTrue($line->setLineNumber(1));
        $this->assertSame($num, $line->getLineNumber());
        $this->assertTrue($line->issetLineNumber());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetOrderReferences(): void
    {
        $line = new Line(new ErrorRegister());
        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $ordRef = $line->addOrderReferences();
            $ori    = "Order ".\strval($n);
            $ordRef->setOriginatingON($ori);
            /* @var $getOrd \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences */
            $getOrd = $line->getOrderReferences()[$n];
            $this->assertSame($ori, $getOrd->getOriginatingON());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetProductCode(): void
    {
        $line    = new Line(new ErrorRegister());
        $proCode = "Product code";
        $this->assertTrue($line->setProductCode($proCode));
        $this->assertSame($proCode, $line->getProductCode());
        $this->assertTrue($line->issetProductCode());

        $wrong = \str_pad($proCode, 70, "9");
        $this->assertFalse($line->setProductCode($wrong));
        $this->assertSame($wrong, $line->getProductCode());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setProductCode(""));
        $this->assertSame("", $line->getProductCode());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetProductDescription(): void
    {
        $line           = new Line(new ErrorRegister());
        $proDescription = "Product description";
        $this->assertTrue($line->setProductDescription($proDescription));
        $this->assertTrue($line->issetProductDescription());
        $this->assertSame($proDescription, $line->getProductDescription());
        $this->assertTrue($line->setProductDescription(\str_pad("A", 299, "A")));
        $this->assertSame(200, \strlen($line->getProductDescription()));

        $wrong = "A";
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setProductDescription($wrong));
        $this->assertSame($wrong, $line->getProductDescription());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setProductDescription(""));
        $this->assertSame("", $line->getProductDescription());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetQuantity(): void
    {
        $line = new Line(new ErrorRegister());
        $qt   = 1.99;
        $this->assertTrue($line->setQuantity($qt));
        $this->assertSame($qt, $line->getQuantity());
        $this->assertTrue($line->issetQuantity());

        $wrong = -0.0001;
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setQuantity($wrong));
        $this->assertSame($wrong, $line->getQuantity());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetUnitOfMeasure(): void
    {
        $line = new Line(new ErrorRegister());
        $unit = "Unidade";
        $this->assertTrue($line->setUnitOfMeasure($unit));
        $this->assertTrue($line->issetUnitOfMeasure());
        $this->assertSame($unit, $line->getUnitOfMeasure());
        $this->assertTrue($line->setUnitOfMeasure(\str_pad($unit, 70, "9")));
        $this->assertSame(20, \strlen($line->getUnitOfMeasure()));

        $this->assertFalse($line->setUnitOfMeasure(""));
        $this->assertSame("", $line->getUnitOfMeasure());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetUnitPrice(): void
    {
        $line  = new Line(new ErrorRegister());
        $price = 1.99;
        $this->assertTrue($line->setUnitPrice($price));
        $this->assertSame($price, $line->getUnitPrice());
        $this->assertTrue($line->issetUnitPrice());

        $wrong = -0.0001;
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setUnitPrice($wrong));
        $this->assertSame($wrong, $line->getUnitPrice());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetDescription(): void
    {
        $line = new Line(new ErrorRegister());
        $desc = "Line description";
        $this->assertTrue($line->setDescription($desc));
        $this->assertTrue($line->issetDescription());
        $this->assertSame($desc, $line->getDescription());
        $this->assertTrue($line->setDescription(\str_pad($desc, 299, "9")));
        $this->assertSame(200, \strlen($line->getDescription()));

        $this->assertFalse($line->setDescription(""));
        $this->assertSame("", $line->getDescription());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetProductSerialNumber(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(
            ProductSerialNumber::class, $line->getProductSerialNumber()
        );
        $line->setProductSerialNumberAsNull();
        $this->assertNull($line->getProductSerialNumber(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetDebitCredit(): void
    {
        $line = new Line(new ErrorRegister());
        $deb  = 9.09;
        $cre  = 19.49;

        $this->assertTrue($line->setDebitAmount($deb));
        $this->assertSame($deb, $line->getDebitAmount());
        $this->assertTrue($line->setDebitAmount(null));
        $this->assertNull($line->getDebitAmount());

        $this->assertTrue($line->setCreditAmount($cre));
        $this->assertSame($cre, $line->getCreditAmount());
        $this->assertTrue($line->setCreditAmount(null));
        $this->assertNull($line->getCreditAmount());


        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        $line->setCreditAmount($cre);
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setDebitAmount($deb));
        $this->assertSame($deb, $line->getDebitAmount());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        $line->setDebitAmount($deb);
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setCreditAmount($cre));
        $this->assertSame($cre, $line->getCreditAmount());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTax(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(MovementTax::class, $line->getTax());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxExemptionReason(): void
    {
        $line   = new Line(new ErrorRegister());
        $reason = "Tax Exception Reason";
        $this->assertTrue($line->setTaxExemptionReason($reason));
        $this->assertSame($reason, $line->getTaxExemptionReason());
        $this->assertTrue($line->setTaxExemptionReason(\str_pad($reason, 99, "9")));
        $this->assertSame(60, \strlen($line->getTaxExemptionReason()));

        $wrong = "AAAAA";
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setTaxExemptionReason($wrong));
        $this->assertSame($wrong, $line->getTaxExemptionReason());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($line->setTaxExemptionReason(null));
        $this->assertNull($line->getTaxExemptionReason());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxExemptionCode(): void
    {
        $line = new Line(new ErrorRegister());
        $line->setTaxExemptionCode(new TaxExemptionCode(TaxExemptionCode::M01));
        $this->assertInstanceOf(
            TaxExemptionCode::class, $line->getTaxExemptionCode()
        );
        $line->setTaxExemptionCode(null);
        $this->assertNull($line->getTaxExemptionCode());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSettlementAmount(): void
    {
        $line = new Line(new ErrorRegister());
        $sett = 9.09;
        $this->assertTrue($line->setSettlementAmount($sett));
        $this->assertSame($sett, $line->getSettlementAmount());
        $this->assertTrue($line->setSettlementAmount(null));
        $this->assertNull($line->getSettlementAmount());

        $wrong = -0.0001;
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setSettlementAmount($wrong));
        $this->assertSame($wrong, $line->getSettlementAmount());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetCustomsInformation(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(
            CustomsInformation::class, $line->getCustomsInformation()
        );
        $line->setCustomsInformationAsNull();
        $this->assertNull($line->getCustomsInformation(false));
    }

    /**
     * Reads all invoices's lines from the Demo SAFT in Test\Ressources
     * and parse then to Line class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $movStockStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS}
            ->{StockMovement::N_STOCKMOVEMENT};

        if ($movStockStack->count() === 0) {
            $this->fail("No invoices in XML");
        }

        for ($i = 0; $i < $movStockStack->count(); $i++) {
            $movStockXml = $movStockStack[$i];
            $lineStack   = $movStockXml->{Line::N_LINE};

            if ($lineStack->count() === 0) {
                $this->fail("No lines in StockMovement");
            }

            for ($l = 0; $l < $lineStack->count(); $l++) {
                /* @var $lineXml \SimpleXMLElement */
                $lineXml = $lineStack[$l];
                $line    = new Line(new ErrorRegister());
                $line->parseXmlNode($lineXml);


                $xmlRootNode         = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
                $sourceDocNode       = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $movementOfGoodsNode = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);
                $movStockNode        = $movementOfGoodsNode->addChild(StockMovement::N_STOCKMOVEMENT);

                $xml = $line->createXmlNode($movStockNode);
                $xml->asXML("d:/todelete/gt.xml");
                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' Line '%s' with error '%s'",
                            $movStockXml->{StockMovement::N_DOCUMENTNUMBER},
                            $lineXml->{Line::N_LINENUMBER}, $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' Line '%s' with error '%s'",
                            $movStockXml->{StockMovement::N_DOCUMENTNUMBER},
                            $lineXml->{Line::N_LINENUMBER}, $e->getMessage()
                        )
                    );
                }

                $this->assertEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
                $this->assertEmpty($line->getErrorRegistor()->getOnSetValue());
                $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $lineNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $line     = new Line(new ErrorRegister());
        $xml      = $line->createXmlNode($lineNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($line->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $lineNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $line     = new Line(new ErrorRegister());
        $line->setCreditAmount(-9.09);
        $line->setDebitAmount(-4.49);
        $line->setDescription("");
        $line->setLineNumber(-1);
        $line->setProductCode("");
        $line->setProductDescription("");
        $line->setQuantity(-4.59);
        $line->setSettlementAmount(-7.99);
        $line->setTaxExemptionReason("");
        $line->setUnitOfMeasure("");
        $line->setUnitPrice(-2.99);

        $xml = $line->createXmlNode($lineNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
    }
}