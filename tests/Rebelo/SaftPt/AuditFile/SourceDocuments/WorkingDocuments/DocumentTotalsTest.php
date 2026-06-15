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

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\TXmlTest;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotalsTest extends TestCase
{

    use TXmlTest;

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(DocumentTotals::class))->testReflection(DocumentTotals::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $docTotals);
        $this->assertNull($docTotals->getCurrency(false));

        $this->assertFalse($docTotals->issetGrossTotal());
        $this->assertFalse($docTotals->issetNetTotal());
        $this->assertFalse($docTotals->issetTaxPayable());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPayable(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $taxPayable = new Decimal("9.49");
        $this->assertTrue($docTotals->setTaxPayable($taxPayable));
        $this->assertSame($taxPayable, $docTotals->getTaxPayable());
        $this->assertTrue($docTotals->issetTaxPayable());

        $wrong = new Decimal("-0.01");
        $this->assertFalse($docTotals->setTaxPayable($wrong));
        $this->assertSame($wrong, $docTotals->getTaxPayable());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $netTotal  = new Decimal("9.49");
        $this->assertTrue($docTotals->setNetTotal($netTotal));
        $this->assertSame($netTotal, $docTotals->getNetTotal());
        $this->assertTrue($docTotals->issetNetTotal());
        $wrong     = new Decimal("-0.01");
        $this->assertFalse($docTotals->setNetTotal($wrong));
        $this->assertSame($wrong, $docTotals->getNetTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testGrossTotal(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("9.49");
        $this->assertTrue($docTotals->setGrossTotal($grossTotal));
        $this->assertSame($grossTotal, $docTotals->getGrossTotal());
        $this->assertTrue($docTotals->issetGrossTotal());
        $wrong      = new Decimal("-0.01");
        $this->assertFalse($docTotals->setGrossTotal($wrong));
        $this->assertSame($wrong, $docTotals->getGrossTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCurrency(): void
    {
        $documentTotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(Currency::class, $documentTotals->getCurrency());
    }

    /**
     * Reads all DocumentTotals from the Demo SAFT in Test\Resources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXml(): void
    {
        $docTotals = null;
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $workDocumentStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCE_DOCUMENTS}
            ->{WorkingDocuments::N_WORKING_DOCUMENTS}
            ->{WorkDocument::N_WORK_DOCUMENT};

        if ($workDocumentStack->count() === 0) {
            $this->fail("No work documents in XML");
        }

        for ($i = 0; $i < $workDocumentStack->count(); $i++) {
            $workDocumentStackXml = $workDocumentStack[$i];
            $totalsStack     = $workDocumentStackXml->{DocumentTotals::N_DOCUMENT_TOTALS};

            if ($totalsStack->count() === 0) {
                $this->fail("No Document Totals in Work documents");
            }

            for ($l = 0; $l < $totalsStack->count(); $l++) {
                /* @var $totalsXml \SimpleXMLElement */
                $totalsXml = $totalsStack[$l];
                $docTotals = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($totalsXml);

                /** @noinspection HtmlUnknownAttribute */
                $xmlRootNode      = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01  '.
                    'https://raw.githubusercontent.com/joaomfrebelo/Saft-PT_4_PHP/master/src/Rebelo/'.
                    'SaftPt/Validate/Schema/SAFTPT_1_04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode    = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
                $workingDocumentsNode  = $sourceDocNode->addChild(WorkingDocuments::N_WORKING_DOCUMENTS);
                $workDocumentStackNode = $workingDocumentsNode->addChild(WorkDocument::N_WORK_DOCUMENT);

                $xml = $docTotals->createXmlNode($workDocumentStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($totalsXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $workDocumentStackXml->{WorkDocument::N_DOCUMENT_NUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $workDocumentStackXml->{WorkDocument::N_DOCUMENT_NUMBER},
                            $e->getMessage()
                        )
                    );
                }
            }

            $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $docTotalsNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORK_DOCUMENT."></".WorkDocument::N_WORK_DOCUMENT.">"
        );
        $docTotals     = new DocumentTotals(new ErrorRegister());
        $xml           = $docTotals->createXmlNode($docTotalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $docTotalsNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORK_DOCUMENT."></".WorkDocument::N_WORK_DOCUMENT.">"
        );
        $docTotals     = new DocumentTotals(new ErrorRegister());
        $docTotals->setGrossTotal(new Decimal(new Decimal("-1.0")));
        $docTotals->setNetTotal(new Decimal(new Decimal("-2.0")));
        $docTotals->setTaxPayable(new Decimal(new Decimal("-0.5")));

        $xml = $docTotals->createXmlNode($docTotalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
    }
}
