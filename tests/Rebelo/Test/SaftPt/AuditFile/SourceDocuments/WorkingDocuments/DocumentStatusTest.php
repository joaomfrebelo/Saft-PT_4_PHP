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
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentStatusTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $docStatus = new DocumentStatus();
        $this->assertInstanceOf(DocumentStatus::class, $docStatus);
        $this->assertNull($docStatus->getReason());

        $status = WorkStatus::N;
        $docStatus->setWorkStatus(new WorkStatus($status));
        $this->assertSame($status, $docStatus->getWorkStatus()->get());

        $date = new RDate();
        $docStatus->setWorkStatusDate($date);
        $this->assertSame($date, $docStatus->getWorkStatusDate());

        $reason = "Reason of status";
        $docStatus->setReason($reason);
        $this->assertSame($reason, $docStatus->getReason());
        $docStatus->setReason(null);
        $this->assertNull($docStatus->getReason());
        $docStatus->setReason(str_pad($reason, 50, "A"));
        $this->assertSame(50, \strlen($docStatus->getReason()));

        $sourceId = "operator";
        $docStatus->setSourceID($sourceId);
        $this->assertSame($sourceId, $docStatus->getSourceID());
        $docStatus->setSourceID(str_pad($sourceId, 50, "A"));
        $this->assertSame(30, \strlen($docStatus->getSourceID()));
        try {
            $docStatus->setSourceID("");
            $this->fail("Set SourceID to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $srcBill = SourceBilling::M;
        $docStatus->setSourceBilling(new SourceBilling($srcBill));
        $this->assertSame($srcBill, $docStatus->getSourceBilling()->get());
    }

    /**
     * Reads all workstaus from the Demo SAFT in Test\Ressources
     * and parse them to workstatus class, after that generate a xml from the
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
            $this->fail("No invoices in XML");
        }

        for ($i = 0; $i < $workdocStack->count(); $i++) {
            $workdocStackXml = $workdocStack[$i];
            $statusStack     = $workdocStackXml->{DocumentStatus::N_DOCUMENTSTATUS};

            if ($statusStack->count() === 0) {
                $this->fail("No documemntstatus in Workdoc");
            }

            for ($l = 0; $l < $statusStack->count(); $l++) {
                /* @var $statusXml \SimpleXMLElement */
                $statusXml = $statusStack[$l];
                $docStatus = new DocumentStatus();
                $docStatus->parseXmlNode($statusXml);

                $xmlRootNode      = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode    = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $workingdocsNode  = $sourceDocNode->addChild(WorkingDocuments::N_WORKINGDOCUMENTS);
                $workdocStackNode = $workingdocsNode->addChild(WorkDocument::N_WORKDOCUMENT);

                $xml = $docStatus->createXmlNode($workdocStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($statusXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Document '%s' with error '%s'",
                            $workdocStackXml->{WorkDocument::N_DOCUMENTNUMBER},
                            $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                            $workdocStackXml->{WorkDocument::N_DOCUMENTNUMBER},
                            $e->getMessage()));
                }
            }
        }
    }
}