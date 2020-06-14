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
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkingDocumentsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WorkingDocuments::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $workingDocs = new WorkingDocuments();
        $this->assertInstanceOf(WorkingDocuments::class, $workingDocs);
        $this->assertSame(0, \count($workingDocs->getWorkDocument()));
    }

    /**
     *
     */
    public function testNumberOfEntries()
    {
        $workingDocs = new WorkingDocuments();
        $entries     = [0, 999];
        foreach ($entries as $num) {
            $workingDocs->setNumberOfEntries($num);
            $this->assertSame($num, $workingDocs->getNumberOfEntries());
        }
        try {
            $workingDocs->setNumberOfEntries(-1);
            $this->fail("Set NumberOdEntries to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testTotalDebit()
    {
        $workingDocs = new WorkingDocuments();
        $debitStack  = [0.0, 9.99];
        foreach ($debitStack as $debit) {
            $workingDocs->setTotalDebit($debit);
            $this->assertSame($debit, $workingDocs->getTotalDebit());
        }
        try {
            $workingDocs->setTotalDebit(-0.19);
            $this->fail("Set TotalDebit to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testTotalCredit()
    {
        $workingDocs = new WorkingDocuments();
        $creditStack = [0.0, 9.99];
        foreach ($creditStack as $creditStack) {
            $workingDocs->setTotalCredit($creditStack);
            $this->assertSame($creditStack, $workingDocs->getTotalCredit());
        }
        try {
            $workingDocs->setTotalDebit(-0.19);
            $this->fail("Set TotalCredit to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testWorkDocument()
    {
        $workingDocs = new WorkingDocuments();
        $nMax        = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $workDoc = new WorkDocument();
            $workDoc->setAtcud(\strval($n));
            $index   = $workingDocs->addToWorkDocument($workDoc);
            $this->assertSame($n, $index);
            $this->assertSame(
                \strval($n), $workingDocs->getWorkDocument()[$n]->getAtcud()
            );
        }

        $this->assertSame($nMax, \count($workingDocs->getWorkDocument()));

        $unset = 2;
        $workingDocs->unsetWorkDocument($unset);
        $this->assertFalse($workingDocs->issetWorkDocument($unset));
        $this->assertSame($nMax - 1, \count($workingDocs->getWorkDocument()));
    }

    /**
     * Reads WorkingDocuments from the Demo SAFT in Test\Ressources
     * and parse then to WorkDocument class, after that generate a xml from the
     * class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $workingDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{WorkingDocuments::N_WORKINGDOCUMENTS};

        if ($workingDocsXml->count() === 0) {
            $this->fail("No WorkingDocs in XML");
        }



        $workingDoc = new WorkingDocuments();
        $workingDoc->parseXmlNode($workingDocsXml);

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $workingDoc->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($workingDocsXml, $xml);
            $this->assertTrue($assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}