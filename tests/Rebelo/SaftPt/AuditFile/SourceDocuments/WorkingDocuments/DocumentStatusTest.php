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
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\Enum\EnumException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentStatusTest extends TestCase
{

    use TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $this->assertInstanceOf(DocumentStatus::class, $docStatus);
        $this->assertNull($docStatus->getReason());

        $this->assertFalse($docStatus->issetSourceBilling());
        $this->assertFalse($docStatus->issetSourceID());
        $this->assertFalse($docStatus->issetWorkStatus());
        $this->assertFalse($docStatus->issetWorkStatusDate());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testWorkStatus(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $status    = WorkStatus::N;
        $docStatus->setWorkStatus(new WorkStatus($status));
        $this->assertSame($status, $docStatus->getWorkStatus()->get());
        $this->assertTrue($docStatus->issetWorkStatus());
    }

    /**
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testWorkStatusDate(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $date      = new RDate();
        $docStatus->setWorkStatusDate($date);
        $this->assertSame($date, $docStatus->getWorkStatusDate());
        $this->assertTrue($docStatus->issetWorkStatusDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testReason(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $reason    = "Reason of status";
        $this->assertTrue($docStatus->setReason($reason));
        $this->assertSame($reason, $docStatus->getReason());
        $this->assertTrue($docStatus->setReason(null));
        $this->assertNull($docStatus->getReason());
        $this->assertTrue($docStatus->setReason(str_pad($reason, 50, "A")));
        $this->assertSame(50, \strlen($docStatus->getReason()));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSourceID(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $sourceId  = "operator";
        $this->assertTrue($docStatus->setSourceID($sourceId));
        $this->assertSame($sourceId, $docStatus->getSourceID());
        $this->assertTrue($docStatus->issetSourceID());
        $this->assertTrue($docStatus->setSourceID(str_pad($sourceId, 50, "A")));
        $this->assertSame(30, \strlen($docStatus->getSourceID()));

        $this->assertFalse($docStatus->setSourceID(""));
        $this->assertSame("", $docStatus->getSourceID());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testSourceBilling(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $srcBill   = SourceBilling::M;
        $docStatus->setSourceBilling(new SourceBilling($srcBill));
        $this->assertSame($srcBill, $docStatus->getSourceBilling()->get());
        $this->assertTrue($docStatus->issetSourceBilling());
    }

    /**
     * Reads all workstaus from the Demo SAFT in Test\Ressources
     * and parse then to workstatus class, after that generate a xml from the
     * workstatus class and test if the xml strings are equal
     *
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $docStatus = null;
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

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
                $this->fail("No document status in Work document");
            }

            for ($l = 0; $l < $statusStack->count(); $l++) {
                /* @var $statusXml \SimpleXMLElement */
                $statusXml = $statusStack[$l];
                $docStatus = new DocumentStatus(new ErrorRegister());
                $docStatus->parseXmlNode($statusXml);

                $xmlRootNode      = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 https://raw.githubusercontent.com/joaomfrebelo/Saft-PT_4_PHP/master/src/Rebelo/SaftPt/Validate/Schema/SAFTPT_1_04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode    = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $workingDocsNode  = $sourceDocNode->addChild(WorkingDocuments::N_WORKINGDOCUMENTS);
                $workDocStackNode = $workingDocsNode->addChild(WorkDocument::N_WORKDOCUMENT);

                $xml = $docStatus->createXmlNode($workDocStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($statusXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $workdocStackXml->{WorkDocument::N_DOCUMENTNUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $workdocStackXml->{WorkDocument::N_DOCUMENTNUMBER},
                            $e->getMessage()
                        )
                    );
                }
            }
            $this->assertNotNull($docStatus);
            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docStatus?->getErrorRegistor()->getLibXmlError());
            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docStatus?->getErrorRegistor()->getOnCreateXmlNode());
            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docStatus?->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $docStatusNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORKDOCUMENT."></".WorkDocument::N_WORKDOCUMENT.">"
        );
        $docStatus     = new DocumentStatus(new ErrorRegister());
        $xml           = $docStatus->createXmlNode($docStatusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $docStatusNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORKDOCUMENT."></".WorkDocument::N_WORKDOCUMENT.">"
        );
        $docStatus     = new DocumentStatus(new ErrorRegister());
        $docStatus->setReason("");
        $docStatus->setSourceID("");

        $xml = $docStatus->createXmlNode($docStatusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
    }
}
