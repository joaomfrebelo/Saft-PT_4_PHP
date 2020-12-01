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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WorkDocument::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $this->assertInstanceOf(WorkDocument::class, $workDocument);
        $this->assertNull($workDocument->getPeriod());
        $this->assertNull($workDocument->getEacCode());
        $this->assertNull($workDocument->getTransactionID(false));
        $this->assertNull($workDocument->getDocTotalcal());
        $this->assertSame(0, \count($workDocument->getLine()));

        $this->assertFalse($workDocument->issetAtcud());
        $this->assertFalse($workDocument->issetCustomerID());
        $this->assertFalse($workDocument->issetDocumentNumber());
        $this->assertFalse($workDocument->issetDocumentStatus());
        $this->assertFalse($workDocument->issetDocumentTotals());
        $this->assertFalse($workDocument->issetHash());
        $this->assertFalse($workDocument->issetHashControl());
        $this->assertFalse($workDocument->issetSourceID());
        $this->assertFalse($workDocument->issetSystemEntryDate());
        $this->assertFalse($workDocument->issetWorkDate());
        $this->assertFalse($workDocument->issetWorkType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentNumber(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $docNum       = "ORC OR/999";
        $this->assertTrue($workDocument->setDocumentNumber($docNum));
        $this->assertTrue($workDocument->issetDocumentNumber());
        $this->assertSame($docNum, $workDocument->getDocumentNumber());

        $wrong = "ORCA /1";
        $this->assertFalse($workDocument->setDocumentNumber($wrong));
        $this->assertSame($wrong, $workDocument->getDocumentNumber());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentStatus(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentStatus::class, $workDocument->getDocumentStatus()
        );
        $this->assertTrue($workDocument->issetDocumentStatus());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testAtcud(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $atcud        = "999";
        $this->assertTrue($workDocument->setAtcud($atcud));
        $this->assertTrue($workDocument->issetAtcud());
        $this->assertSame($atcud, $workDocument->getAtcud());

        $wrong = \str_pad($atcud, 120, "A");
        $this->assertFalse($workDocument->setAtcud($wrong));
        $this->assertSame($wrong, $workDocument->getAtcud());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHash(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $hash         = \md5("hash");
        $this->assertTrue($workDocument->setHash($hash));
        $this->assertTrue($workDocument->issetHash());
        $this->assertSame($hash, $workDocument->getHash());

        $wrong = \str_pad($hash, 200, "A");
        $this->assertFalse($workDocument->setHash($wrong));
        $this->assertSame($wrong, $workDocument->getHash());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHashControl(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $control      = "1";
        $this->assertTrue($workDocument->setHashControl($control));
        $this->assertTrue($workDocument->issetHashControl());
        $this->assertSame($control, $workDocument->getHashControl());

        $wrong = \str_pad("Z1", 71, "9");
        $this->assertFalse($workDocument->setHashControl($wrong));
        $this->assertSame($wrong, $workDocument->getHashControl());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testPeriod(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $period       = 9;
        $this->assertTrue($workDocument->setPeriod($period));
        $this->assertSame($period, $workDocument->getPeriod());

        $wrong = 0;
        $this->assertFalse($workDocument->setPeriod($wrong));
        $this->assertSame($wrong, $workDocument->getPeriod());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());

        $wrong2 = 13;
        $workDocument->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($workDocument->setPeriod($wrong2));
        $this->assertSame($wrong2, $workDocument->getPeriod());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());

        $workDocument->getErrorRegistor()->cleaeAllErrors();
        $this->assertTrue($workDocument->setPeriod(null));
        $this->assertNull($workDocument->getPeriod());
        $this->assertEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWorkDate(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $date         = new RDate();
        $workDocument->setWorkDate($date);
        $this->assertSame($date, $workDocument->getWorkDate());
        $this->assertTrue($workDocument->issetWorkDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWorkType(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $type         = WorkType::CC;
        $workDocument->setWorkType(new WorkType($type));
        $this->assertSame($type, $workDocument->getWorkType()->get());
        $this->assertTrue($workDocument->issetWorkType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSourceID(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $source       = "Rebelo";
        $this->assertTrue($workDocument->setSourceID($source));
        $this->assertSame($source, $workDocument->getSourceID());
        $this->assertTrue($workDocument->issetSourceID());
        $this->assertTrue($workDocument->setSourceID(\str_pad($source, 50, "9")));
        $this->assertSame(30, \strlen($workDocument->getSourceID()));

        $this->assertFalse($workDocument->setSourceID(""));
        $this->assertSame("", $workDocument->getSourceID());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEACCode(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $eaccode      = "49499";
        $this->assertTrue($workDocument->setEacCode($eaccode));
        $this->assertSame($eaccode, $workDocument->getEacCode());
        $workDocument->setEacCode(null);
        $this->assertNull($workDocument->getEacCode());

        $wrong = "9999";
        $this->assertFalse($workDocument->setEacCode($wrong));
        $this->assertSame($wrong, $workDocument->getEacCode());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());

        $wrong2 = "999999";
        $workDocument->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($workDocument->setEacCode($wrong2));
        $this->assertSame($wrong2, $workDocument->getEacCode());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSystemEntryDate(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $date         = new RDate();
        $workDocument->setSystemEntryDate($date);
        $this->assertSame($date, $workDocument->getSystemEntryDate());
        $this->assertTrue($workDocument->issetSystemEntryDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTransactionId(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $transaction  = $workDocument->getTransactionID();
        $transaction->setDate(new RDate());
        $transaction->setDocArchivalNumber("A");
        $transaction->setJournalID("9");
        $this->assertSame($transaction, $workDocument->getTransactionID());
        $workDocument->setTransactionIDAsNull();
        $this->assertNull($workDocument->getTransactionID(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCustomerId(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $id           = "A999";
        $this->assertTrue($workDocument->setCustomerID($id));
        $this->assertTrue($workDocument->issetCustomerID());
        $this->assertSame($id, $workDocument->getCustomerID());

        $wrong = \str_pad($id, 31, "999999");
        $this->assertFalse($workDocument->setCustomerID($wrong));
        $this->assertSame($wrong, $workDocument->getCustomerID());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testLine(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $nMax         = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $line = $workDocument->addLine();
            $this->assertSame(
                $n + 1, $workDocument->getLine()[$n]->getLineNumber()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocumentTotals(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentTotals::class, $workDocument->getDocumentTotals()
        );
        $this->assertTrue($workDocument->issetDocumentTotals());
    }

    /**
     * Reads all WorkDocument from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

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
            $workdoc    = new WorkDocument(new ErrorRegister());
            $workdoc->parseXmlNode($workdocXml);

            $xmlRootNode     = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
            $sourceDocNode   = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $workingdocsNode = $sourceDocNode->addChild(WorkingDocuments::N_WORKINGDOCUMENTS);

            $xml = $workdoc->createXmlNode($workingdocsNode);
            
            try {
                $assertXml = $this->xmlIsEqual($workdocXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $workdocXml->{WorkDocument::N_DOCUMENTNUMBER},
                        $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $workdocXml->{WorkDocument::N_DOCUMENTNUMBER},
                        $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($workdoc->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($workdoc->getErrorRegistor()->getOnSetValue());
            $this->assertEmpty($workdoc->getErrorRegistor()->getLibXmlError());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $workDocNode = new \SimpleXMLElement(
            "<".WorkingDocuments::N_WORKINGDOCUMENTS."></".WorkingDocuments::N_WORKINGDOCUMENTS.">"
        );
        $workDoc     = new WorkDocument(new ErrorRegister());
        $xml         = $workDoc->createXmlNode($workDocNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($workDoc->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($workDoc->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($workDoc->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $workDocNode = new \SimpleXMLElement(
            "<".WorkingDocuments::N_WORKINGDOCUMENTS."></".WorkingDocuments::N_WORKINGDOCUMENTS.">"
        );
        $workDoc     = new WorkDocument(new ErrorRegister());
        $workDoc->setAtcud("");
        $workDoc->setCustomerID("");
        $workDoc->setDocumentNumber("");
        $workDoc->setEacCode("");
        $workDoc->setHash("");
        $workDoc->setHashControl("");
        $workDoc->setPeriod(-1);
        $workDoc->setSourceID("");

        $xml = $workDoc->createXmlNode($workDocNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($workDoc->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($workDoc->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($workDoc->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocTotalcal(): void
    {
        $workDoc = new WorkDocument(new ErrorRegister());
        $workDoc->setDocTotalcal(new \Rebelo\SaftPt\Validate\DocTotalCalc());
        $this->assertInstanceOf(
            \Rebelo\SaftPt\Validate\DocTotalCalc::class,
            $workDoc->getDocTotalcal()
        );
    }
}