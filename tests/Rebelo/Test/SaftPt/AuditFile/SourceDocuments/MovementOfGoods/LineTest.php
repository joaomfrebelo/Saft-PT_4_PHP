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
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $line = new Line();
        $this->assertInstanceOf(Line::class, $line);
        $this->assertSame([], $line->getOrderReferences());
        $this->assertNull($line->getProductSerialNumber());
        $this->assertNull($line->getDebitAmount());
        $this->assertNull($line->getCreditAmount());
        $this->assertNull($line->getTaxExemptionReason());
        $this->assertNull($line->getTaxExemptionCode());
        $this->assertNull($line->getSettlementAmount());
        $this->assertNull($line->getCustomsInformation());
    }

    /**
     *
     */
    public function testSetGetLineNumber()
    {
        $line = new Line();
        $num  = 1;
        $line->setLineNumber(1);
        $this->assertSame($num, $line->getLineNumber());
    }

    /**
     *
     */
    public function testSetGetOrderReferences()
    {
        $line = new Line();
        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $ordRef = new OrderReferences();
            $ori    = "Order ".\strval($n);
            $ordRef->setOriginatingON($ori);
            $index  = $line->addToOrderReferences($ordRef);
            $this->assertSame($n, $index);
            /* @var $getOrd \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences */
            $getOrd = $line->getOrderReferences()[$n];
            $this->assertSame($ori, $getOrd->getOriginatingON());
        }
        $uset = 2;
        $line->unsetOrderReferences($uset);
        $this->assertFalse($line->issetOrderReferences($uset));
        $this->assertSame($nMax - 1, \count($line->getOrderReferences()));
    }

    /**
     *
     */
    public function testSetGetProductCode()
    {
        $line    = new Line();
        $proCode = "Product code";
        $line->setProductCode($proCode);
        $this->assertSame($proCode, $line->getProductCode());
        try {
            $line->setProductCode(\str_pad($proCode, 70, "9"));
            $this->fail("ProductCode length greater than 60 must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $line->setProductCode("");
            $this->fail("ProductCode empty must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetProductDescription()
    {
        $line           = new Line();
        $proDescription = "Product description";
        $line->setProductDescription($proDescription);
        $this->assertSame($proDescription, $line->getProductDescription());
        $line->setProductDescription(\str_pad("A", 299, "A"));
        $this->assertSame(200, \strlen($line->getProductDescription()));
        try {
            $line->setProductDescription("A");
            $this->fail("ProductDescription length less than 2 must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetQuantity()
    {
        $line = new Line();
        $qt   = 1.99;
        $line->setQuantity($qt);
        $this->assertSame($qt, $line->getQuantity());
        try {
            $line->setQuantity(-0.0001);
            $this->fail("Quantity less than zero must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetUnitOfMeasure()
    {
        $line = new Line();
        $unit = "Unidade";
        $line->setUnitOfMeasure($unit);
        $this->assertSame($unit, $line->getUnitOfMeasure());
        $line->setUnitOfMeasure(\str_pad($unit, 70, "9"));
        $this->assertSame(20, \strlen($line->getUnitOfMeasure()));
        try {
            $line->setUnitOfMeasure("");
            $this->fail("UnitOfMeasure empty must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetUnitPrice()
    {
        $line  = new Line();
        $price = 1.99;
        $line->setUnitPrice($price);
        $this->assertSame($price, $line->getUnitPrice());
        try {
            $line->setUnitPrice(-0.0001);
            $this->fail("UnitPrice less than zero must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetDescription()
    {
        $line = new Line();
        $desc = "Line description";
        $line->setDescription($desc);
        $this->assertSame($desc, $line->getDescription());
        $line->setDescription(\str_pad($desc, 299, "9"));
        $this->assertSame(200, \strlen($line->getDescription()));
        try {
            $line->setDescription("");
            $this->fail("Description empty must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetProductSerialNumber()
    {
        $line = new Line();
        $line->setProductSerialNumber(new ProductSerialNumber());
        $this->assertInstanceOf(ProductSerialNumber::class,
            $line->getProductSerialNumber());
        $line->setProductSerialNumber(null);
        $this->assertNull($line->getProductSerialNumber());
    }

    public function testSetGetDebitCredit()
    {
        $line = new Line();
        $deb  = 9.09;
        $cre  = 19.49;

        $line->setDebitAmount($deb);
        $this->assertSame($deb, $line->getDebitAmount());
        $line->setDebitAmount(null);
        $this->assertNull($line->getDebitAmount());

        $line->setCreditAmount($cre);
        $this->assertSame($cre, $line->getCreditAmount());
        $line->setCreditAmount(null);
        $this->assertNull($line->getCreditAmount());

        try {
            $line->setDebitAmount($deb);
            $line->setCreditAmount($cre);
            $this->fail("When set CreditAmount without DebiAmout be null should throw"
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        try {
            $line->setCreditAmount($cre);
            $line->setDebitAmount($deb);
            $this->fail("When set DebiAmout without  CreditAmount be null should throw"
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetTax()
    {
        $line = new Line();
        $line->setTax(new MovementTax());
        $this->assertInstanceOf(MovementTax::class, $line->getTax());
        $line->setTax(null);
        $this->assertNull($line->getTax());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionReason()
    {
        $line   = new Line();
        $reason = "Tax Exception Reason";
        $line->setTaxExemptionReason($reason);
        $this->assertSame($reason, $line->getTaxExemptionReason());
        $line->setTaxExemptionReason(\str_pad($reason, 99, "9"));
        $this->assertSame(60, \strlen($line->getTaxExemptionReason()));
        try {
            $line->setTaxExemptionReason("AAAAA");
            $this->fail("TaxExemptionReason with length less than 6 must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $line->setTaxExemptionReason(null);
        $this->assertNull($line->getTaxExemptionReason());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionCode()
    {
        $line = new Line();
        $line->setTaxExemptionCode(new TaxExemptionCode(TaxExemptionCode::M01));
        $this->assertInstanceOf(TaxExemptionCode::class,
            $line->getTaxExemptionCode());
        $line->setTaxExemptionCode(null);
        $this->assertNull($line->getTaxExemptionCode());
    }

    /**
     *
     */
    public function testSettlementAmount()
    {
        $line = new Line();
        $sett = 9.09;
        $line->setSettlementAmount($sett);
        $this->assertSame($sett, $line->getSettlementAmount());
        $line->setSettlementAmount(null);
        $this->assertNull($line->getSettlementAmount());
    }

    /**
     *
     */
    public function testSetGetCustomsInformation()
    {
        $line = new Line();
        $line->setCustomsInformation(new CustomsInformation());
        $this->assertInstanceOf(CustomsInformation::class,
            $line->getCustomsInformation());
        $line->setCustomsInformation(null);
        $this->assertNull($line->getCustomsInformation());
    }

    /**
     * Reads all invoices's lines from the Demo SAFT in Test\Ressources
     * and parse them to Line class, after tahr generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
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
                $line    = new Line();
                $line->parseXmlNode($lineXml);


                $xmlRootNode         = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode       = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $MovementOfGoodsNode = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);
                $movStockNode        = $MovementOfGoodsNode->addChild(StockMovement::N_STOCKMOVEMENT);

                $xml = $line->createXmlNode($movStockNode);

                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Document '%s' Line '%s' with error '%s'",
                            $movStockXml->{StockMovement::n_DOCUMENTNUMBER},
                            $lineXml->{Line::N_LINENUMBER}, $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' Line '%s' with error '%s'",
                            $movStockXml->{StockMovement::n_DOCUMENTNUMBER},
                            $lineXml->{Line::N_LINENUMBER}, $e->getMessage()));
                }
            }
        }
    }
}