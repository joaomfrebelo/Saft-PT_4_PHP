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

use PHPUnit\Framework\TestCase;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;
use Rebelo\SaftPt\Validate\DocTableTotalCalc;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkingDocumentsTest extends TestCase
{

    use TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(WorkingDocuments::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $workingDocs = new WorkingDocuments(new ErrorRegister());
        $this->assertInstanceOf(WorkingDocuments::class, $workingDocs);
        $this->assertSame(0, \count($workingDocs->getWorkDocument()));

        $this->assertFalse($workingDocs->issetNumberOfEntries());
        $this->assertFalse($workingDocs->issetTotalCredit());
        $this->assertFalse($workingDocs->issetTotalDebit());
        $this->assertNull($workingDocs->getDocTableTotalCalc());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfEntries(): void
    {
        $workingDocs = new WorkingDocuments(new ErrorRegister());
        $entries     = [0, 999];
        foreach ($entries as $num) {
            $this->assertTrue($workingDocs->setNumberOfEntries($num));
            $this->assertSame($num, $workingDocs->getNumberOfEntries());
            $this->assertTrue($workingDocs->issetNumberOfEntries());
        }

        $wrong = -1;
        $this->assertFalse($workingDocs->setNumberOfEntries($wrong));
        $this->assertSame($wrong, $workingDocs->getNumberOfEntries());
        $this->assertNotEmpty($workingDocs->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalDebit(): void
    {
        $workingDocs = new WorkingDocuments(new ErrorRegister());
        $debitStack  = [0.0, 9.99];
        foreach ($debitStack as $debit) {
            $this->assertTrue($workingDocs->setTotalDebit($debit));
            $this->assertSame($debit, $workingDocs->getTotalDebit());
            $this->assertTrue($workingDocs->issetTotalDebit());
        }

        $wrong = -19.9;
        $this->assertFalse($workingDocs->setTotalDebit($wrong));
        $this->assertSame($wrong, $workingDocs->getTotalDebit());
        $this->assertNotEmpty($workingDocs->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalCredit(): void
    {
        $workingDocs = new WorkingDocuments(new ErrorRegister());
        $creditStack = [0.0, 9.99];
        foreach ($creditStack as $credit) {
            $this->assertTrue($workingDocs->setTotalCredit($credit));
            $this->assertSame($credit, $workingDocs->getTotalCredit());
            $this->assertTrue($workingDocs->issetTotalCredit());
        }

        $wrong = -19.9;
        $this->assertFalse($workingDocs->setTotalCredit($wrong));
        $this->assertSame($wrong, $workingDocs->getTotalCredit());
        $this->assertNotEmpty($workingDocs->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWorkDocument(): void
    {
        $workingDocs = new WorkingDocuments(new ErrorRegister());
        $nMax        = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $workDoc = $workingDocs->addWorkDocument();
            $workDoc->setAtcud(\strval($n));
            $this->assertSame(
                \strval($n), $workingDocs->getWorkDocument()[$n]->getAtcud()
            );
        }

        $this->assertSame($nMax, \count($workingDocs->getWorkDocument()));
    }

    /**
     * Reads WorkingDocuments from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * class and test if the xml strings are equal
     * @throws AuditFileException
     * @throws DateFormatException
     * @throws DateParseException
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $workingDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{WorkingDocuments::N_WORKINGDOCUMENTS};

        if ($workingDocsXml->count() === 0) {
            $this->fail("No WorkingDocs in XML");
        }

        $workingDoc = new WorkingDocuments(new ErrorRegister());
        $workingDoc->parseXmlNode($workingDocsXml);

        $xmlRootNode   = (new AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $workingDoc->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($workingDocsXml, $xml);
            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }

        $this->assertEmpty($workingDoc->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($workingDoc->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($workingDoc->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $workingDocsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $workingDocs     = new WorkingDocuments(new ErrorRegister());
        $xml             = $workingDocs->createXmlNode($workingDocsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($workingDocs->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($workingDocs->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($workingDocs->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $workingDocsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $workingDocs     = new WorkingDocuments(new ErrorRegister());
        $workingDocs->setNumberOfEntries(-1);
        $workingDocs->setTotalCredit(-0.99);
        $workingDocs->setTotalDebit(-0.95);

        $xml = $workingDocs->createXmlNode($workingDocsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($workingDocs->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($workingDocs->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($workingDocs->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetOrder(): void
    {
        $workingDoc = new WorkingDocuments(new ErrorRegister());
        $docNumber  = array(
            "FO FO/1",
            "CM CM/4",
            "FO FO/5",
            "FO FO/2",
            "FO FO/9",
            "FO FO/4",
            "FO FO/3",
            "FO FO/10",
            "CM CM/3",
            "CM CM/2",
            "CM CM/1",
            "FO B/3",
            "FO B/1",
            "FO B/2",
        );
        foreach ($docNumber   as $no) {
            $workingDoc->addWorkDocument()->setDocumentNumber($no);
        }

        $order = $workingDoc->getOrder();
        $this->assertSame(array("CM", "FO"), \array_keys($order));
        $this->assertSame(array("CM"), \array_keys($order["CM"]));
        $this->assertSame(array("B", "FO"), \array_keys($order["FO"]));
        $this->assertSame(
            array(1, 2, 3, 4, 5, 9, 10), \array_keys($order["FO"]["FO"])
        );
        $this->assertSame(array(1, 2, 3), \array_keys($order["FO"]["B"]));
        $this->assertSame(array(1, 2, 3, 4), \array_keys($order["CM"]["CM"]));

        foreach ($order as $type => $serieStack) {
            foreach ($serieStack as $serie => $noStack) {
                foreach ($noStack as $no => $workDoc) {
                    $this->assertSame(
                        \sprintf("%s %s/%s", $type, $serie, $no),
                        $workDoc->getDocumentNumber()
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
        $workingDoc = new WorkingDocuments(new ErrorRegister());
        $docNumber  = array(
            "FO FO/1",
            "CM CM/4",
            "FO FO/1",
            "FO FO/2",
            "FO FO/9",
            "FO FO/4",
            "FO FO/3",
            "FO FO/10",
            "CM CM/3",
            "CM CM/2",
            "CM CM/1",
            "FO B/3",
            "FO B/1",
            "FO B/2",
        );
        foreach ($docNumber as $no) {
            $workingDoc->addWorkDocument()->setDocumentNumber($no);
        }

        $workingDoc->getOrder();
        $this->assertNotEmpty($workingDoc->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNoNumber(): void
    {
        $workDocs = new WorkingDocuments(new ErrorRegister());
        $docNo    = array(
            "FO FO/1",
            "FO B/2"
        );
        foreach ($docNo as $no) {
            $workDocs->addWorkDocument()->setDocumentNumber($no);
        }
        $workDocs->addWorkDocument();
        $workDocs->getOrder();
        $this->assertNotEmpty($workDocs->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocTableTotalCalc(): void
    {
        $workingDoc = new WorkingDocuments(new ErrorRegister());
        $workingDoc->setDocTableTotalCalc(new DocTableTotalCalc);
        $this->assertInstanceOf(
            DocTableTotalCalc::class,
            $workingDoc->getDocTableTotalCalc()
        );
    }
}
