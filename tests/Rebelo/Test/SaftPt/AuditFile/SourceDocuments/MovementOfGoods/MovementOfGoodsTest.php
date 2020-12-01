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
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\{
    MovementOfGoods
};
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc;

/**
 * MovementOfGoodsTest
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementOfGoodsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(MovementOfGoods::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $movOfGo = new MovementOfGoods(new ErrorRegister());
        $this->assertInstanceOf(MovementOfGoods::class, $movOfGo);
        $this->assertSame(0, \count($movOfGo->getStockMovement()));

        $this->assertFalse($movOfGo->issetNumberOfMovementLines());
        $this->assertFalse($movOfGo->issetTotalQuantityIssued());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfMovementLines(): void
    {
        $movOfGo = new MovementOfGoods(new ErrorRegister());
        $entries = [0, 999];
        foreach ($entries as $num) {
            $this->assertTrue($movOfGo->setNumberOfMovementLines($num));
            $this->assertSame($num, $movOfGo->getNumberOfMovementLines());
            $this->assertTrue($movOfGo->issetNumberOfMovementLines());
        }

        $wrong = -1;
        $this->assertFalse($movOfGo->setNumberOfMovementLines($wrong));
        $this->assertSame($wrong, $movOfGo->getNumberOfMovementLines());
        $this->assertNotEmpty($movOfGo->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalQuantityIssued(): void
    {
        $movOfGo = new MovementOfGoods(new ErrorRegister());
        $stack   = [0.0, 9.99];

        foreach ($stack as $num) {
            $this->assertTrue($movOfGo->setTotalQuantityIssued($num));
            $this->assertSame($num, $movOfGo->getTotalQuantityIssued());
            $this->assertTrue($movOfGo->issetTotalQuantityIssued());
        }

        $wrong = -1.0;
        $this->assertFalse($movOfGo->setTotalQuantityIssued($wrong));
        $this->assertSame($wrong, $movOfGo->getTotalQuantityIssued());
        $this->assertNotEmpty($movOfGo->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testStockMovement(): void
    {
        $movOfGo = new MovementOfGoods(new ErrorRegister());
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $stkMov = $movOfGo->addStockMovement();
            $stkMov->setAtDocCodeID(\strval($n));
            $this->assertSame(
                \strval($n), $movOfGo->getStockMovement()[$n]->getAtDocCodeID()
            );
        }

        $this->assertSame($nMax, \count($movOfGo->getStockMovement()));
    }

    /**
     * Reads MovementOfGoods from the Demo SAFT in Test\Ressources
     * and parse then to MovementOfGoods class, after that generate a xml from the
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

        $movStkDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS};

        if ($movStkDocsXml->count() === 0) {
            $this->fail("No StockMovement in XML");
        }

        $movStkDoc = new MovementOfGoods(new ErrorRegister());
        $movStkDoc->parseXmlNode($movStkDocsXml);

        $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $movStkDoc->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($movStkDocsXml, $xml);
            $this->assertTrue(
                $assertXml, \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }

        $this->assertEmpty($movStkDoc->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($movStkDoc->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($movStkDoc->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $movOfGoodsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $movOfGoods     = new MovementOfGoods(new ErrorRegister());
        $xml            = $movOfGoods->createXmlNode($movOfGoodsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($movOfGoods->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($movOfGoods->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($movOfGoods->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $movOfGoodsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $movOfGoods     = new MovementOfGoods(new ErrorRegister());
        $movOfGoods->setNumberOfMovementLines(-1);
        $movOfGoods->setTotalQuantityIssued(-9.0);

        $xml = $movOfGoods->createXmlNode($movOfGoodsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($movOfGoods->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($movOfGoods->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($movOfGoods->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function movOfGoodsTableTotalCalc() : void
    {
        $movOfGoods = new MovementOfGoods(new ErrorRegister());
        $this->assertNull($movOfGoods->getMovOfGoodsTableTotalCalc());

        $movOfGoods->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());
        $this->assertInstanceOf(
            MovOfGoodsTableTotalCalc::class,
            $movOfGoods->getMovOfGoodsTableTotalCalc(),
        );
    }
    
    /**
     * @author João Rebelo
     * @test
     */
    public function testGetOrder(): void
    {
        $movementOfGoods = new MovementOfGoods(new ErrorRegister());
        $docNo      = array(
            "GT GT/1",
            "GD GD/4",
            "GT GT/5",
            "GT GT/2",
            "GT GT/9",
            "GT GT/4",
            "GT GT/3",
            "GT GT/10",
            "GD GD/3",
            "GD GD/2",
            "GD GD/1",
            "GT B/3",
            "GT B/1",
            "GT B/2",
        );
        foreach ($docNo as $no) {
            $movementOfGoods->addStockMovement()->setDocumentNumber($no);
        }

        $order = $movementOfGoods->getOrder();
        $this->assertSame(array("GD", "GT"), \array_keys($order));
        $this->assertSame(array("GD"), \array_keys($order["GD"]));
        $this->assertSame(array("B", "GT"), \array_keys($order["GT"]));
        $this->assertSame(
            array(1, 2, 3, 4, 5, 9, 10), \array_keys($order["GT"]["GT"])
        );
        $this->assertSame(array(1, 2, 3), \array_keys($order["GT"]["B"]));
        $this->assertSame(array(1, 2, 3, 4), \array_keys($order["GD"]["GD"]));

        foreach ($order as $type => $serieStack) {
            foreach ($serieStack as $serie => $noSatck) {
                foreach ($noSatck as $no => $stkMv) {
                    /* @var $stkMv \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement */
                    $this->assertSame(
                        \sprintf("%s %s/%s", $type, $serie, $no),
                        $stkMv->getDocumentNumber()
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
        $stkMv = new MovementOfGoods(new ErrorRegister());
        $stkNo      = array(
            "GT GT/1",
            "GD GD/4",
            "GT GT/1",
            "GT GT/2",
            "GT GT/9",
            "GT GT/4",
            "GT GT/3",
            "GT GT/10",
            "GD GD/3",
            "GD GD/2",
            "GD GD/1",
            "GT B/3",
            "GT B/1",
            "GT B/2",
        );
        foreach ($stkNo as $no) {
            $stkMv->addStockMovement()->setDocumentNumber($no);
        }

        $stkMv->getOrder();
        $this->assertNotEmpty($stkMv->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNoNumber(): void
    {
        $stkMov = new MovementOfGoods(new ErrorRegister());
        $stkMvNo      = array(
            "GT GT/1",
            "GT B/2"
        );
        foreach ($stkMvNo as $no) {
            $stkMov->addStockMovement()->setDocumentNumber($no);
        }
        $stkMov->addStockMovement();
        $stkMov->getOrder();
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getValidationErrors());
    }
    
}