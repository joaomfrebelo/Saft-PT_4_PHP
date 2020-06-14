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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkDocumentTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WorkDocument::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $workDocument = new WorkDocument();
        $this->assertInstanceOf(WorkDocument::class, $workDocument);
        $this->assertNull($workDocument->getPeriod());
        $this->assertNull($workDocument->getEacCode());
        $this->assertNull($workDocument->getTransactionID());
        $this->assertSame(0, \count($workDocument->getLine()));
    }

    /**
     *
     */
    public function testDocumentNumber()
    {
        $workDocument = new WorkDocument();
        $docNum       = "ORC OR/999";
        $workDocument->setDocumentNumber($docNum);
        $this->assertSame($docNum, $workDocument->getDocumentNumber());
        try {
            $workDocument->setDocumentNumber("ORCA /1");
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
        $workDocument = new WorkDocument();
        $status       = new DocumentStatus();
        $workDocument->setDocumentStatus($status);
        $this->assertInstanceOf(
            DocumentStatus::class, $workDocument->getDocumentStatus()
        );
    }

    /**
     *
     */
    public function testAtcud()
    {
        $workDocument = new WorkDocument();
        $atcud        = "999";
        $workDocument->setAtcud($atcud);
        $this->assertSame($atcud, $workDocument->getAtcud());
        try {
            $workDocument->setAtcud(str_pad($atcud, 120, "A"));
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
        $workDocument = new WorkDocument();
        $hash         = \md5("hash");
        $workDocument->setHash($hash);
        $this->assertSame($hash, $workDocument->getHash());
        try {
            $workDocument->setHash(str_pad($hash, 200, "A"));
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
        $workDocument = new WorkDocument();
        $control      = "1";
        $workDocument->setHashControl($control);
        $this->assertSame($control, $workDocument->getHashControl());
        try {
            $workDocument->setHashControl(\str_pad("Z1", 71, "9"));
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
        $workDocument = new WorkDocument();
        $period       = 9;
        $workDocument->setPeriod($period);
        $this->assertSame($period, $workDocument->getPeriod());
        try {
            $workDocument->setPeriod(0);
            $this->fail("Set periodo to less than 1 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $workDocument->setPeriod(13);
            $this->fail("Set periodo to greater than 12 should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $workDocument->setPeriod(null);
        $this->assertNull($workDocument->getPeriod());
    }

    /**
     *
     */
    public function testWorkDate()
    {
        $workDocument = new WorkDocument();
        $date         = new RDate();
        $workDocument->setWorkDate($date);
        $this->assertSame($date, $workDocument->getWorkDate());
    }

    /**
     *
     */
    public function testWorkType()
    {
        $workDocument = new WorkDocument();
        $type         = WorkType::CC;
        $workDocument->setWorkType(new WorkType($type));
        $this->assertSame($type, $workDocument->getWorkType()->get());
    }

    /**
     *
     */
    public function testSourceID()
    {
        $workDocument = new WorkDocument();
        $source       = "Rebelo";
        $workDocument->setSourceID($source);
        $this->assertSame($source, $workDocument->getSourceID());
        $workDocument->setSourceID(\str_pad($source, 50, "9"));
        $this->assertSame(30, \strlen($workDocument->getSourceID()));
    }

    /**
     *
     */
    public function testEACCode()
    {
        $workDocument = new WorkDocument();
        $eaccode      = "49499";
        $workDocument->setEacCode($eaccode);
        $this->assertSame($eaccode, $workDocument->getEacCode());
        $workDocument->setEacCode(null);
        $this->assertNull($workDocument->getEacCode());
        try {
            $workDocument->setEacCode("9999");
            $this->fail("Set a wrong eaccode should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $workDocument->setEacCode("999999");
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
        $workDocument = new WorkDocument();
        $date         = new RDate();
        $workDocument->setSystemEntryDate($date);
        $this->assertSame($date, $workDocument->getSystemEntryDate());
    }

    /**
     *
     */
    public function testTransactionId()
    {
        $workDocument = new WorkDocument();
        $transaction  = new \Rebelo\SaftPt\AuditFile\TransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $workDocument->setTransactionID($transaction);
        $this->assertSame($transaction, $workDocument->getTransactionID());
        $workDocument->setTransactionID(null);
        $this->assertNull($workDocument->getTransactionID());
    }

    /**
     *
     */
    public function testCustomerId()
    {
        $workDocument = new WorkDocument();
        $id           = "A999";
        $workDocument->setCustomerID($id);
        $this->assertSame($id, $workDocument->getCustomerID());
        try {
            $workDocument->setCustomerID(\str_pad($id, 31, "999999"));
            $this->fail("Set a wrong customerid should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testLine()
    {
        $workDocument = new WorkDocument();
        $nMax         = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $line  = new Line();
            $line->setLineNumber($n + 1);
            $index = $workDocument->addToLine($line);
            $this->assertSame($n, $index);
            $this->assertSame(
                $n + 1, $workDocument->getLine()[$n]->getLineNumber()
            );
        }

        $this->assertSame($nMax, \count($workDocument->getLine()));

        $unset = 2;
        $workDocument->unsetLine($unset);
        $this->assertFalse($workDocument->issetLine($unset));
        $this->assertSame($nMax - 1, \count($workDocument->getLine()));
    }

    /**
     *
     */
    public function testDocumentTotals()
    {
        $workDocument = new WorkDocument();
        $totals       = new DocumentTotals();
        $workDocument->setDocumentTotals($totals);
        $this->assertSame($totals, $workDocument->getDocumentTotals());
    }

    /**
     * Reads all WorkDocument from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $workdocStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{WorkingDocuments::N_WORKINGDOCUMENTS}
            ->{WorkDocument::N_WORKDOCUMENT};

        if ($workdocStack->count() === 0) {
            $this->fail("No workdocuments in XML");
        }

        for ($n = 0; $n < $workdocStack->count(); $n++) {
            /* @var $workdocXml \SimpleXMLElement */
            $workdocXml = $workdocStack[$n];
            $workdoc    = new WorkDocument();
            $workdoc->parseXmlNode($workdocXml);

            $xmlRootNode     = new \SimpleXMLElement(
                '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
            );
            $sourceDocNode   = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $workingdocsNode = $sourceDocNode->addChild(WorkingDocuments::N_WORKINGDOCUMENTS);

            $xml = $workdoc->createXmlNode($workingdocsNode);

            try {
                $assertXml = $this->xmlIsEqual($workdocXml, $xml);
                $this->assertTrue($assertXml,
                    \sprintf("Fail on Document '%s' with error '%s'",
                        $workdocXml->{WorkDocument::N_DOCUMENTNUMBER},
                        $assertXml)
                );
            } catch (\Exception | \Error $e) {
                $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                        $workdocXml->{WorkDocument::N_DOCUMENTNUMBER},
                        $e->getMessage()));
            }
        }
    }
}