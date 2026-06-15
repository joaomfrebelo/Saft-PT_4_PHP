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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\TXmlTest;
use Rebelo\SaftPt\Validate\DocTotalCalc;

/**
 * Line
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class WorkDocumentTest extends TestCase
{

    use TXmlTest;

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(WorkDocument::class))->testReflection(WorkDocument::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $this->assertInstanceOf(WorkDocument::class, $workDocument);
        $this->assertNull($workDocument->getPeriod());
        $this->assertNull($workDocument->getEacCode());
        $this->assertNull($workDocument->getTransactionID(false));
        $this->assertNull($workDocument->getDocTotalCalc());
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
     */
    #[Test]
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
     */
    #[Test]
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
     */
    #[Test]
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
     */
    #[Test]
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
     */
    #[Test]
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
     */
    #[Test]
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
        $workDocument->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($workDocument->setPeriod($wrong2));
        $this->assertSame($wrong2, $workDocument->getPeriod());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());

        $workDocument->getErrorRegistor()->clearAllErrors();
        $this->assertTrue($workDocument->setPeriod(null));
        $this->assertNull($workDocument->getPeriod());
        $this->assertEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testWorkType(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $type         = WorkType::CC;
        $workDocument->setWorkType($type);
        $this->assertSame($type, $workDocument->getWorkType());
        $this->assertTrue($workDocument->issetWorkType());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testEACCode(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $eacCode      = "49499";
        $this->assertTrue($workDocument->setEacCode($eacCode));
        $this->assertSame($eacCode, $workDocument->getEacCode());
        $workDocument->setEacCode(null);
        $this->assertNull($workDocument->getEacCode());

        $wrong = "9999";
        $this->assertFalse($workDocument->setEacCode($wrong));
        $this->assertSame($wrong, $workDocument->getEacCode());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());

        $wrong2 = "999999";
        $workDocument->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($workDocument->setEacCode($wrong2));
        $this->assertSame($wrong2, $workDocument->getEacCode());
        $this->assertNotEmpty($workDocument->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testTransactionId(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $transaction  = $workDocument->getTransactionID();
        $transaction?->setDate(new RDate());
        $transaction?->setDocArchivalNumber("A");
        $transaction?->setJournalID("9");
        $this->assertSame($transaction, $workDocument->getTransactionID());
        $workDocument->setTransactionIDAsNull();
        $this->assertNull($workDocument->getTransactionID(false));
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testLine(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $nMax         = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $workDocument->addLine();
            $this->assertSame(
                $n + 1, $workDocument->getLine()[$n]->getLineNumber()
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentTotals(): void
    {
        $workDocument = new WorkDocument(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentTotals::class, $workDocument->getDocumentTotals()
        );
        $this->assertTrue($workDocument->issetDocumentTotals());
    }

    /**
     * Reads all WorkDocument from the Demo SAFT in Test\Resources
     * and parse then to WorkDocument class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $workDocumentsStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCE_DOCUMENTS}
            ->{WorkingDocuments::N_WORKING_DOCUMENTS}
            ->{WorkDocument::N_WORK_DOCUMENT};

        if ($workDocumentsStack->count() === 0) {
            $this->fail("No work documents in XML");
        }

        for ($n = 0; $n < $workDocumentsStack->count(); $n++) {
            /* @var $workDocumentsXml \SimpleXMLElement */
            $workDocumentsXml = $workDocumentsStack[$n];
            $workDocument     = new WorkDocument(new ErrorRegister());
            $workDocument->parseXmlNode($workDocumentsXml);

            $xmlRootNode          = (new AuditFile())->createRootElement();
            $sourceDocNode        = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
            $workingDocumentsNode = $sourceDocNode->addChild(WorkingDocuments::N_WORKING_DOCUMENTS);

            $xml = $workDocument->createXmlNode($workingDocumentsNode);

            try {
                $assertXml = $this->xmlIsEqual($workDocumentsXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $workDocumentsXml->{WorkDocument::N_DOCUMENT_NUMBER},
                        $assertXml
                    )
                );
            } catch (\Exception|\Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $workDocumentsXml->{WorkDocument::N_DOCUMENT_NUMBER},
                        $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($workDocument->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($workDocument->getErrorRegistor()->getOnSetValue());
            $this->assertEmpty($workDocument->getErrorRegistor()->getLibXmlError());
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $workDocNode = new \SimpleXMLElement(
            "<" . WorkingDocuments::N_WORKING_DOCUMENTS . "></" . WorkingDocuments::N_WORKING_DOCUMENTS . ">"
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $workDocNode = new \SimpleXMLElement(
            "<" . WorkingDocuments::N_WORKING_DOCUMENTS . "></" . WorkingDocuments::N_WORKING_DOCUMENTS . ">"
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
     */
    #[Test]
    public function testDocTotalCalculation(): void
    {
        $workDoc = new WorkDocument(new ErrorRegister());
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $this->assertInstanceOf(
            DocTotalCalc::class,
            $workDoc->getDocTotalCalc()
        );
    }
}
