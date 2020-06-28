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
    MovementOfGoods,
    StockMovement
};
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementOfGoodsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(MovementOfGoods::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $movOfGo = new MovementOfGoods();
        $this->assertInstanceOf(MovementOfGoods::class, $movOfGo);
        $this->assertSame(0, \count($movOfGo->getStockMovement()));
    }

    /**
     *
     */
    public function testNumberOfMovementLines()
    {
        $movOfGo = new MovementOfGoods();
        $entries = [0, 999];
        foreach ($entries as $num) {
            $movOfGo->setNumberOfMovementLines($num);
            $this->assertSame($num, $movOfGo->getNumberOfMovementLines());
        }
        try {
            $movOfGo->setNumberOfMovementLines(-1);
            $this->fail("Set NumberOfMovementLines to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testTotalQuantityIssued()
    {
        $movOfGo = new MovementOfGoods();
        $stack   = [0.0, 9.99];
        foreach ($stack as $qt) {
            $movOfGo->setTotalQuantityIssued($qt);
            $this->assertSame($qt, $movOfGo->getTotalQuantityIssued());
        }
        try {
            $movOfGo->setTotalQuantityIssued(-0.001);
            $this->fail("Set TotalQuantityIssued to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testStockMovement()
    {
        $movOfGo = new MovementOfGoods();
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $stkMov = new StockMovement();
            $stkMov->setAtDocCodeID(\strval($n));
            $index  = $movOfGo->addToStockMovement($stkMov);
            $this->assertSame($n, $index);
            $this->assertSame(
                \strval($n), $movOfGo->getStockMovement()[$n]->getAtDocCodeID()
            );
        }

        $this->assertSame($nMax, \count($movOfGo->getStockMovement()));

        $unset = 2;
        $movOfGo->unsetStockMovement($unset);
        $this->assertFalse($movOfGo->issetStockMovement($unset));
        $this->assertSame($nMax - 1, \count($movOfGo->getStockMovement()));
    }

    /**
     * Reads MovementOfGoods from the Demo SAFT in Test\Ressources
     * and parse then to MovementOfGoods class, after that generate a xml from the
     * class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $movStkDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS};

        if ($movStkDocsXml->count() === 0) {
            $this->fail("No StockMovement in XML");
        }

        $movStkDoc = new MovementOfGoods();
        $movStkDoc->parseXmlNode($movStkDocsXml);

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $movStkDoc->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($movStkDocsXml, $xml);
            $this->assertTrue($assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}