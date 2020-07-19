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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ExportType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SourceDocuments,
    SalesInvoices\SalesInvoices,
    MovementOfGoods\MovementOfGoods,
    WorkingDocuments\WorkingDocuments,
    Payments\Payments
};

/**
 * Class SourceDocumentsTest
 *
 * @author João Rebelo
 */
class SourceDocumentsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(SourceDocuments::class);
        $this->assertTrue(true);
    }

    public function testInstanceAndSetGet()
    {
        $sourceDoc = new SourceDocuments();
        $this->assertInstanceOf(SourceDocuments::class, $sourceDoc);

        $sourceDoc->setSalesInvoices(new SalesInvoices());
        $this->assertInstanceOf(
            SalesInvoices::class, $sourceDoc->getSalesInvoices()
        );

        $sourceDoc->setMovementOfGoods(new MovementOfGoods());
        $this->assertInstanceOf(
            MovementOfGoods::class, $sourceDoc->getMovementOfGoods()
        );

        $sourceDoc->setWorkingDocuments(new WorkingDocuments());
        $this->assertInstanceOf(
            WorkingDocuments::class, $sourceDoc->getWorkingDocuments()
        );

        $sourceDoc->setPayments(new Payments());
        $this->assertInstanceOf(
            Payments::class, $sourceDoc->getPayments()
        );
    }

    /**
     * Reads SourceDocuments from the Demo SAFT in Test\Ressources
     * and parse then to SourceDocuments class, after that generate a xml from the
     * class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $sourceDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS};

        if ($sourceDocsXml->count() === 0) {
            $this->fail("No SourceDocs in XML");
        }

        $sourceDoc = new SourceDocuments();
        $sourceDoc->setExportType(new ExportType(ExportType::C));
        $sourceDoc->parseXmlNode($sourceDocsXml);

        $xmlRootNode = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );

        $auditNode = $xmlRootNode->addChild(
            \Rebelo\SaftPt\AuditFile\AuditFile::N_AUDITFILE
        );

        $xml = $sourceDoc->createXmlNode($auditNode);

        try {
            $assertXml = $this->xmlIsEqual($sourceDocsXml, $xml);
            $this->assertTrue($assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}