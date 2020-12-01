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
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    ShipFrom,
    ShipTo
};
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\{
    MovementType,
    StockMovement,
    DocumentStatus,
    MovementOfGoods
};

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class StockMovementTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(StockMovement::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $this->assertInstanceOf(StockMovement::class, $stkMov);
        $this->assertNull($stkMov->getPeriod());
        $this->assertNull($stkMov->getTransactionID(false));
        $this->assertNull($stkMov->getEacCode());
        $this->assertNull($stkMov->getMovementComments());
        $this->assertNull($stkMov->getShipTo(false));
        $this->assertNull($stkMov->getShipFrom(false));
        $this->assertNull($stkMov->getMovementEndTime());
        $this->assertNull($stkMov->getAtDocCodeID());
        $this->assertSame(0, \count($stkMov->getLine()));

        $this->assertFalse($stkMov->issetAtcud());
        $this->assertFalse($stkMov->issetCustomerID());
        $this->assertFalse($stkMov->issetDocumentNumber());
        $this->assertFalse($stkMov->issetDocumentStatus());
        $this->assertFalse($stkMov->issetDocumentTotals());
        $this->assertFalse($stkMov->issetHash());
        $this->assertFalse($stkMov->issetHashControl());
        $this->assertFalse($stkMov->issetMovementDate());
        $this->assertFalse($stkMov->issetMovementType());
        $this->assertFalse($stkMov->issetSourceID());
        $this->assertFalse($stkMov->issetSupplierID());
        $this->assertFalse($stkMov->issetSystemEntryDate());
        $this->assertFalse($stkMov->issetMovementStartTime());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentNumber(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $docNum = "GT GT/999";
        $this->assertTrue($stkMov->setDocumentNumber($docNum));
        $this->assertTrue($stkMov->issetDocumentNumber());
        $this->assertSame($docNum, $stkMov->getDocumentNumber());

        $wrong = "ORCA /1";
        $this->assertFalse($stkMov->setDocumentNumber($wrong));
        $this->assertSame($wrong, $stkMov->getDocumentNumber());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentStatus(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentStatus::class, $stkMov->getDocumentStatus()
        );
        $this->assertTrue($stkMov->issetDocumentStatus());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testAtcud(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $atcud  = "999";
        $this->assertTrue($stkMov->setAtcud($atcud));
        $this->assertTrue($stkMov->issetAtcud());
        $this->assertSame($atcud, $stkMov->getAtcud());

        $wrong = \str_pad($atcud, 120, "A");
        $this->assertFalse($stkMov->setAtcud($wrong));
        $this->assertSame($wrong, $stkMov->getAtcud());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHash(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $hash   = \md5("hash");
        $this->assertTrue($stkMov->setHash($hash));
        $this->assertTrue($stkMov->issetHash());
        $this->assertSame($hash, $stkMov->getHash());

        $wrong = \str_pad($hash, 200, "A");
        $this->assertFalse($stkMov->setHash($wrong));
        $this->assertSame($wrong, $stkMov->getHash());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHashControl(): void
    {
        $stkMov  = new StockMovement(new ErrorRegister());
        $control = "1";
        $this->assertTrue($stkMov->setHashControl($control));
        $this->assertTrue($stkMov->issetHashControl());
        $this->assertSame($control, $stkMov->getHashControl());

        $wrong = \str_pad("Z1", 71, "9");
        $this->assertFalse($stkMov->setHashControl($wrong));
        $this->assertSame($wrong, $stkMov->getHashControl());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testPeriod(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $period = 9;
        $this->assertTrue($stkMov->setPeriod($period));
        $this->assertSame($period, $stkMov->getPeriod());

        $wrong = 0;
        $this->assertFalse($stkMov->setPeriod($wrong));
        $this->assertSame($wrong, $stkMov->getPeriod());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $wrong2 = 13;
        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($stkMov->setPeriod($wrong2));
        $this->assertSame($wrong2, $stkMov->getPeriod());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertTrue($stkMov->setPeriod(null));
        $this->assertNull($stkMov->getPeriod());
        $this->assertEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementDate(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $date   = new RDate();
        $stkMov->setMovementDate($date);
        $this->assertSame($date, $stkMov->getMovementDate());
        $this->assertTrue($stkMov->issetMovementDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementType(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $type   = MovementType::GT;
        $stkMov->setMovementType(new MovementType($type));
        $this->assertSame($type, $stkMov->getMovementType()->get());
        $this->assertTrue($stkMov->issetMovementType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSourceID(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $source = "Rebelo";
        $this->assertTrue($stkMov->setSourceID($source));
        $this->assertSame($source, $stkMov->getSourceID());
        $this->assertTrue($stkMov->issetSourceID());
        $this->assertTrue($stkMov->setSourceID(\str_pad($source, 50, "9")));
        $this->assertSame(30, \strlen($stkMov->getSourceID()));

        $this->assertFalse($stkMov->setSourceID(""));
        $this->assertSame("", $stkMov->getSourceID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEACCode(): void
    {
        $stkMov  = new StockMovement(new ErrorRegister());
        $eaccode = "49499";
        $this->assertTrue($stkMov->setEacCode($eaccode));
        $this->assertSame($eaccode, $stkMov->getEacCode());
        $stkMov->setEacCode(null);
        $this->assertNull($stkMov->getEacCode());

        $wrong = "9999";
        $this->assertFalse($stkMov->setEacCode($wrong));
        $this->assertSame($wrong, $stkMov->getEacCode());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $wrong2 = "999999";
        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($stkMov->setEacCode($wrong2));
        $this->assertSame($wrong2, $stkMov->getEacCode());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSystemEntryDate(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $date   = new RDate();
        $stkMov->setSystemEntryDate($date);
        $this->assertSame($date, $stkMov->getSystemEntryDate());
        $this->assertTrue($stkMov->issetSystemEntryDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTransactionId(): void
    {
        $stkMov      = new StockMovement(new ErrorRegister());
        $transaction = $stkMov->getTransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $this->assertSame($transaction, $stkMov->getTransactionID());
        $stkMov->setTransactionIDAsNull();
        $this->assertNull($stkMov->getTransactionID(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCustomerId(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $id     = "A999";
        $this->assertTrue($stkMov->setCustomerID($id));
        $this->assertTrue($stkMov->issetCustomerID());
        $this->assertSame($id, $stkMov->getCustomerID());

        $wrong = \str_pad($id, 31, "999999");
        $this->assertFalse($stkMov->setCustomerID($wrong));
        $this->assertSame($wrong, $stkMov->getCustomerID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($stkMov->setSupplierID($wrong));
        $this->assertSame($wrong, $stkMov->getSupplierID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSupplierId(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $id     = "A999";
        $this->assertTrue($stkMov->setSupplierID($id));
        $this->assertTrue($stkMov->issetSupplierID());
        $this->assertSame($id, $stkMov->getSupplierID());

        $wrong = \str_pad($id, 31, "999999");
        $this->assertFalse($stkMov->setSupplierID($wrong));
        $this->assertSame($wrong, $stkMov->getSupplierID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($stkMov->setCustomerID($wrong));
        $this->assertSame($wrong, $stkMov->getCustomerID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementComments(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $com    = "Movement comment";
        $this->assertTrue($stkMov->setMovementComments($com));
        $this->assertSame($com, $stkMov->getMovementComments());

        $this->assertFalse($stkMov->setMovementComments(""));
        $this->assertSame("", $stkMov->getMovementComments());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($stkMov->setMovementComments(null));
        $this->assertNull($stkMov->getMovementComments());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipTo(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $stkMov->getShipTo();
        $this->assertInstanceOf(ShipTo::class, $stkMov->getShipTo());
        $stkMov->setShipToAsNull();
        $this->assertNull($stkMov->getShipTo(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipFrom(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $stkMov->getShipFrom();
        $this->assertInstanceOf(ShipFrom::class, $stkMov->getShipFrom());
        $stkMov->setShipFromAsNull();
        $this->assertNull($stkMov->getShipFrom(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementEndTime(): void
    {
        $stkMov  = new StockMovement(new ErrorRegister());
        $endTime = new RDate();
        $stkMov->setMovementEndTime($endTime);
        $this->assertSame($endTime, $stkMov->getMovementEndTime());
        $stkMov->setMovementEndTime(null);
        $this->assertNull($stkMov->getMovementEndTime());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testMovementStartTime(): void
    {
        $stkMov    = new StockMovement(new ErrorRegister());
        $startTime = new RDate();
        $stkMov->setMovementStartTime($startTime);
        $this->assertSame($startTime, $stkMov->getMovementStartTime());
        $this->assertTrue($stkMov->issetMovementStartTime());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testATDocCodeID(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $code   = "ATCODE";
        $stkMov->setAtDocCodeID($code);
        $this->assertSame($code, $stkMov->getAtDocCodeID());

        $wrong = \str_pad("A", 201, "A");
        $this->assertFalse($stkMov->setAtDocCodeID($wrong));
        $this->assertSame($wrong, $stkMov->getAtDocCodeID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $stkMov->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($stkMov->setAtDocCodeID(""));
        $this->assertSame("", $stkMov->getAtDocCodeID());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());

        $stkMov->setAtDocCodeID(null);
        $this->assertNull($stkMov->getAtDocCodeID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testLine(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $nMax   = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $stkMov->addLine();
            $this->assertSame(
                $n + 1, $stkMov->getLine()[$n]->getLineNumber()
            );
        }

        $this->assertSame($nMax, \count($stkMov->getLine()));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentTotals(): void
    {
        $stkMov = new StockMovement(new ErrorRegister());
        $totals = $stkMov->getDocumentTotals();
        $this->assertSame($totals, $stkMov->getDocumentTotals());
    }

    /**
     * Reads all StockMovement from the Demo SAFT in Test\Ressources
     * and parse then to StockMovement class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $stkMovStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS}
            ->{StockMovement::N_STOCKMOVEMENT};

        if ($stkMovStack->count() === 0) {
            $this->fail("No StockMovements in XML");
        }

        for ($n = 0; $n < $stkMovStack->count(); $n++) {
            /* @var $stkMovXml \SimpleXMLElement */
            $stkMovXml = $stkMovStack[$n];
            $stkMov    = new StockMovement(new ErrorRegister());
            $stkMov->parseXmlNode($stkMovXml);

            $xmlRootNode         = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
            $sourceDocNode       = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $movementodgoodsNode = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);

            $xml = $stkMov->createXmlNode($movementodgoodsNode);

            try {
                $assertXml = $this->xmlIsEqual($stkMovXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $stkMovXml->{StockMovement::N_DOCUMENTNUMBER},
                        $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $stkMovXml->{StockMovement::N_DOCUMENTNUMBER},
                        $e->getMessage()
                    )
                );
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $stkMovNode = new \SimpleXMLElement(
            "<".MovementOfGoods::N_MOVEMENTOFGOODS."></".MovementOfGoods::N_MOVEMENTOFGOODS.">"
        );
        $stkMov     = new StockMovement(new ErrorRegister());
        $xml        = $stkMov->createXmlNode($stkMovNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($stkMov->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($stkMov->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $stkMovNode = new \SimpleXMLElement(
            "<".MovementOfGoods::N_MOVEMENTOFGOODS."></".MovementOfGoods::N_MOVEMENTOFGOODS.">"
        );
        $stkMov     = new StockMovement(new ErrorRegister());
        $stkMov->setAtDocCodeID("");
        $stkMov->setAtcud("");
        $stkMov->setCustomerID("");
        $stkMov->setDocumentNumber("");
        $stkMov->setEacCode("");
        $stkMov->setHash("");
        $stkMov->setHashControl("");
        $stkMov->setMovementComments("");
        $stkMov->setPeriod(0);
        $stkMov->setSourceID("");
        $stkMov->setSupplierID("");

        $xml = $stkMov->createXmlNode($stkMovNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($stkMov->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($stkMov->getErrorRegistor()->getLibXmlError());
    }
}