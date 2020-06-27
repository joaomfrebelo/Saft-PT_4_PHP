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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    ShipFrom,
    ShipTo
};
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\{
    Line,
    MovementType,
    StockMovement,
    DocumentStatus,
    DocumentTotals,
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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(StockMovement::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $stkMov = new StockMovement();
        $this->assertInstanceOf(StockMovement::class, $stkMov);
        $this->assertNull($stkMov->getPeriod());
        $this->assertNull($stkMov->getTransactionID());
        $this->assertNull($stkMov->getEacCode());
        $this->assertNull($stkMov->getMovementComments());
        $this->assertNull($stkMov->getShipTo());
        $this->assertNull($stkMov->getShipFrom());
        $this->assertNull($stkMov->getMovementEndTime());
        $this->assertNull($stkMov->getAtDocCodeID());
        $this->assertSame(0, \count($stkMov->getLine()));
    }

    /**
     *
     */
    public function testDocumentNumber()
    {
        $stkMov = new StockMovement();
        $docNum = "GT GT/999";
        $stkMov->setDocumentNumber($docNum);
        $this->assertSame($docNum, $stkMov->getDocumentNumber());
        try {
            $stkMov->setDocumentNumber("ORCA /1");
            $this->fail("Set a wrong DocumentNumber should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testDocumentStatus()
    {
        $stkMov = new StockMovement();
        $status = new DocumentStatus();
        $stkMov->setDocumentStatus($status);
        $this->assertInstanceOf(
            DocumentStatus::class, $stkMov->getDocumentStatus()
        );
    }

    /**
     *
     */
    public function testAtcud()
    {
        $stkMov = new StockMovement();
        $atcud  = "999";
        $stkMov->setAtcud($atcud);
        $this->assertSame($atcud, $stkMov->getAtcud());
        try {
            $stkMov->setAtcud(str_pad($atcud, 120, "A"));
            $this->fail("Set a wrong ATCUD should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testHash()
    {
        $stkMov = new StockMovement();
        $hash   = \md5("hash");
        $stkMov->setHash($hash);
        $this->assertSame($hash, $stkMov->getHash());
        try {
            $stkMov->setHash(str_pad($hash, 200, "A"));
            $this->fail("Set a Hash length to big should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testHashControl()
    {
        $stkMov  = new StockMovement();
        $control = "1";
        $stkMov->setHashControl($control);
        $this->assertSame($control, $stkMov->getHashControl());
        try {
            $stkMov->setHashControl(\str_pad("Z1", 71, "9"));
            $this->fail("Set a wrong HashControl should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testPeriod()
    {
        $stkMov = new StockMovement();
        $period = 9;
        $stkMov->setPeriod($period);
        $this->assertSame($period, $stkMov->getPeriod());
        try {
            $stkMov->setPeriod(0);
            $this->fail("Set periodo to less than 1 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $stkMov->setPeriod(13);
            $this->fail("Set periodo to greater than 12 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $stkMov->setPeriod(null);
        $this->assertNull($stkMov->getPeriod());
    }

    /**
     *
     */
    public function testMovementDate()
    {
        $stkMov = new StockMovement();
        $date   = new RDate();
        $stkMov->setMovementDate($date);
        $this->assertSame($date, $stkMov->getMovementDate());
    }

    /**
     *
     */
    public function testMovementType()
    {
        $stkMov = new StockMovement();
        $type   = MovementType::GT;
        $stkMov->setMovementType(new MovementType($type));
        $this->assertSame($type, $stkMov->getMovementType()->get());
    }

    /**
     *
     */
    public function testSourceID()
    {
        $stkMov = new StockMovement();
        $source = "Rebelo";
        $stkMov->setSourceID($source);
        $this->assertSame($source, $stkMov->getSourceID());
        $stkMov->setSourceID(\str_pad($source, 50, "9"));
        $this->assertSame(30, \strlen($stkMov->getSourceID()));
    }

    /**
     *
     */
    public function testEACCode()
    {
        $stkMov  = new StockMovement();
        $eaccode = "49499";
        $stkMov->setEacCode($eaccode);
        $this->assertSame($eaccode, $stkMov->getEacCode());
        $stkMov->setEacCode(null);
        $this->assertNull($stkMov->getEacCode());
        try {
            $stkMov->setEacCode("9999");
            $this->fail("Set a wrong eaccode should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $stkMov->setEacCode("999999");
            $this->fail("Set a wrong eaccode should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSystemEntryDate()
    {
        $stkMov = new StockMovement();
        $date   = new RDate();
        $stkMov->setSystemEntryDate($date);
        $this->assertSame($date, $stkMov->getSystemEntryDate());
    }

    /**
     *
     */
    public function testTransactionId()
    {
        $stkMov      = new StockMovement();
        $transaction = new \Rebelo\SaftPt\AuditFile\TransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $stkMov->setTransactionID($transaction);
        $this->assertSame($transaction, $stkMov->getTransactionID());
        $stkMov->setTransactionID(null);
        $this->assertNull($stkMov->getTransactionID());
    }

    /**
     *
     */
    public function testCustomerId()
    {
        $stkMov = new StockMovement();
        $id     = "A999";
        $stkMov->setCustomerID($id);
        $this->assertSame($id, $stkMov->getCustomerID());
        try {
            $stkMov->setCustomerID(\str_pad($id, 31, "999999"));
            $this->fail("Set a wrong customerid should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $stkMov->setSupplierID("999999");
            $this->fail("Set SupplierID with customerid setted should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testSupplierId()
    {
        $stkMov = new StockMovement();
        $id     = "A999";
        $stkMov->setSupplierID($id);
        $this->assertSame($id, $stkMov->getSupplierID());
        try {
            $stkMov->setSupplierID(\str_pad($id, 31, "999999"));
            $this->fail("Set a wrong SupplierID should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $stkMov->setCustomerID("999999");
            $this->fail("Set CustometID with SupplierID setted should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testMovementComments()
    {
        $stkMov = new StockMovement();
        $com    = "Movement comment";
        $stkMov->setMovementComments($com);
        $this->assertSame($com, $stkMov->getMovementComments());
        $stkMov->setMovementComments(\str_pad($com, 99, "9"));
        $this->assertSame(60, \strlen($stkMov->getMovementComments()));
        try {
            $stkMov->setMovementComments("");
            $this->fail("Set MovementComments to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $stkMov->setMovementComments(null);
        $this->assertNull($stkMov->getMovementComments());
    }

    /**
     *
     */
    public function testShipTo()
    {
        $stkMov = new StockMovement();
        $shipTo = new ShipTo();
        $stkMov->setShipTo($shipTo);
        $this->assertInstanceOf(ShipTo::class, $stkMov->getShipTo());
        $stkMov->setShipTo(null);
        $this->assertNull($stkMov->getShipTo());
    }

    /**
     *
     */
    public function testShipFrom()
    {
        $stkMov   = new StockMovement();
        $shipFrom = new ShipFrom();
        $stkMov->setShipFrom($shipFrom);
        $this->assertInstanceOf(ShipFrom::class, $stkMov->getShipFrom());
        $stkMov->setShipFrom(null);
        $this->assertNull($stkMov->getShipFrom());
    }

    /**
     *
     */
    public function testMovementEndTime()
    {
        $stkMov  = new StockMovement();
        $endTime = new RDate();
        $stkMov->setMovementEndTime($endTime);
        $this->assertSame($endTime, $stkMov->getMovementEndTime());
        $stkMov->setMovementEndTime(null);
        $this->assertNull($stkMov->getMovementEndTime());
    }

    /**
     *
     */
    public function testMovementStartTime()
    {
        $stkMov    = new StockMovement();
        $startTime = new RDate();
        $stkMov->setMovementStartTime($startTime);
        $this->assertSame($startTime, $stkMov->getMovementStartTime());
    }

    /**
     *
     */
    public function testATDocCodeID()
    {
        $stkMov = new StockMovement();
        $code   = "ATCODE";
        $stkMov->setAtDocCodeID($code);
        $this->assertSame($code, $stkMov->getAtDocCodeID());
        try {
            $stkMov->setAtDocCodeID("");
            $this->fail("Set ATDocCodeID to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $stkMov->setAtDocCodeID(\str_pad("A", 201, "A"));
            $this->fail("Set ATDocCodeID with a length greater then 200 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $stkMov->setAtDocCodeID(null);
        $this->assertNull($stkMov->getAtDocCodeID());
    }

    /**
     *
     */
    public function testLine()
    {
        $stkMov = new StockMovement();
        $nMax   = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $line  = new Line();
            $line->setLineNumber($n + 1);
            $index = $stkMov->addToLine($line);
            $this->assertSame($n, $index);
            $this->assertSame(
                $n + 1, $stkMov->getLine()[$n]->getLineNumber()
            );
        }

        $this->assertSame($nMax, \count($stkMov->getLine()));

        $unset = 2;
        $stkMov->unsetLine($unset);
        $this->assertFalse($stkMov->issetLine($unset));
        $this->assertSame($nMax - 1, \count($stkMov->getLine()));
    }

    /**
     *
     */
    public function testDocumentTotals()
    {
        $stkMov = new StockMovement();
        $totals = new DocumentTotals();
        $stkMov->setDocumentTotals($totals);
        $this->assertSame($totals, $stkMov->getDocumentTotals());
    }

    /**
     * Reads all StockMovement from the Demo SAFT in Test\Ressources
     * and parse then to StockMovement class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

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
            $stkMov    = new StockMovement();
            $stkMov->parseXmlNode($stkMovXml);

            $xmlRootNode         = new \SimpleXMLElement(
                '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
            );
            $sourceDocNode       = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $movementodgoodsNode = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);

            $xml = $stkMov->createXmlNode($movementodgoodsNode);

            try {
                $assertXml = $this->xmlIsEqual($stkMovXml, $xml);
                $this->assertTrue($assertXml,
                    \sprintf("Fail on Document '%s' with error '%s'",
                        $stkMovXml->{StockMovement::N_DOCUMENTNUMBER},
                        $assertXml)
                );
            } catch (\Exception | \Error $e) {
                $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                        $stkMovXml->{StockMovement::N_DOCUMENTNUMBER},
                        $e->getMessage()));
            }
        }
    }
}