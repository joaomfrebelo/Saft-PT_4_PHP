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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{MovementOfGoods\MovementOfGoods,
    Payments\Payments,
    SalesInvoices\SalesInvoices,
    WorkingDocuments\WorkingDocuments};
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;

/**
 * Class SourceDocumentsTest
 *
 * @author João Rebelo
 */
class SourceDocumentsTest extends TestCase
{

    use TXmlTest;

    /**
     *
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(SourceDocuments::class);
        $this->assertTrue(true);
    }

    public function testInstance(): void
    {
        $sourceDoc = new SourceDocuments(new ErrorRegister());
        $this->assertNull($sourceDoc->getSalesInvoices(false));
        $this->assertNull($sourceDoc->getMovementOfGoods(false));
        $this->assertNull($sourceDoc->getWorkingDocuments(false));
        $this->assertNull($sourceDoc->getPayments(false));
    }

    public function testInstanceAndSetGet(): void
    {
        $sourceDoc = new SourceDocuments(new ErrorRegister());
        $this->assertInstanceOf(SourceDocuments::class, $sourceDoc);

        $this->assertInstanceOf(
            SalesInvoices::class, $sourceDoc->getSalesInvoices()
        );

        $sourceDoc->setSalesInvoicesAsNull();
        $this->assertNull($sourceDoc->getSalesInvoices(false));

        $this->assertInstanceOf(
            MovementOfGoods::class, $sourceDoc->getMovementOfGoods()
        );

        $sourceDoc->setMovementOfGoodsAsNull();
        $this->assertNull($sourceDoc->getMovementOfGoods(false));

        $this->assertInstanceOf(
            WorkingDocuments::class, $sourceDoc->getWorkingDocuments()
        );

        $sourceDoc->setWorkingDocumentsAsNull();
        $this->assertNull($sourceDoc->getWorkingDocuments(false));

        $this->assertInstanceOf(
            Payments::class, $sourceDoc->getPayments()
        );

        $sourceDoc->setPaymentsAsNull();
        $this->assertNull($sourceDoc->getPayments(false));
    }

    /**
     * Reads SourceDocuments from the Demo SAFT in Test\Ressources
     * and parse then to SourceDocuments class, after that generate a xml from the
     * class and test if the xml strings are equal
     * @throws AuditFileException
     * @throws DateFormatException
     * @throws DateParseException
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $sourceDocsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS};

        if ($sourceDocsXml->count() === 0) {
            $this->fail("No SourceDocs in XML");
        }

        $sourceDoc = new SourceDocuments(new ErrorRegister());
        $sourceDoc->parseXmlNode($sourceDocsXml);

        $xmlRootNode = (new AuditFile())->createRootElement();

        $auditNode = $xmlRootNode->addChild(
            AuditFile::N_AUDITFILE
        );

        $xml = $sourceDoc->createXmlNode($auditNode);

        try {
            $assertXml = $this->xmlIsEqual($sourceDocsXml, $xml);
            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}
