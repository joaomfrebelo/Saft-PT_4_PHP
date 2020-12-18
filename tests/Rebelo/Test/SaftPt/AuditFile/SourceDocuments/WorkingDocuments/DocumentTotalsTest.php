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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotalsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentTotals::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $doctotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $doctotals);
        $this->assertNull($doctotals->getCurrency(false));

        $this->assertFalse($doctotals->issetGrossTotal());
        $this->assertFalse($doctotals->issetNetTotal());
        $this->assertFalse($doctotals->issetTaxPayable());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTaxPayable(): void
    {
        $doctotals  = new DocumentTotals(new ErrorRegister());
        $taxPayable = 9.49;
        $this->assertTrue($doctotals->setTaxPayable($taxPayable));
        $this->assertSame($taxPayable, $doctotals->getTaxPayable());
        $this->assertTrue($doctotals->issetTaxPayable());

        $wrong = -0.01;
        $this->assertFalse($doctotals->setTaxPayable($wrong));
        $this->assertSame($wrong, $doctotals->getTaxPayable());
        $this->assertNotEmpty($doctotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNetTotal(): void
    {
        $doctotals = new DocumentTotals(new ErrorRegister());
        $netTotal  = 9.49;
        $this->assertTrue($doctotals->setNetTotal($netTotal));
        $this->assertSame($netTotal, $doctotals->getNetTotal());
        $this->assertTrue($doctotals->issetNetTotal());
        $wrong     = -0.01;
        $this->assertFalse($doctotals->setNetTotal($wrong));
        $this->assertSame($wrong, $doctotals->getNetTotal());
        $this->assertNotEmpty($doctotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGrossTotal(): void
    {
        $doctotals  = new DocumentTotals(new ErrorRegister());
        $grossTotal = 9.49;
        $this->assertTrue($doctotals->setGrossTotal($grossTotal));
        $this->assertSame($grossTotal, $doctotals->getGrossTotal());
        $this->assertTrue($doctotals->issetGrossTotal());
        $wrong      = -0.01;
        $this->assertFalse($doctotals->setGrossTotal(-0.01));
        $this->assertSame($wrong, $doctotals->getGrossTotal());
        $this->assertNotEmpty($doctotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCurrency(): void
    {
        $doctotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(Currency::class, $doctotals->getCurrency());
    }

    /**
     * Reads all DocumentTotals from the Demo SAFT in Test\Ressources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @author João Rebelo
     * @test
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

        for ($i = 0; $i < $workdocStack->count(); $i++) {
            $workdocStackXml = $workdocStack[$i];
            $totalsStack     = $workdocStackXml->{DocumentTotals::N_DOCUMENTTOTALS};

            if ($totalsStack->count() === 0) {
                $this->fail("No DocumemntTotals in Workdoc");
            }

            for ($l = 0; $l < $totalsStack->count(); $l++) {
                /* @var $totalsXml \SimpleXMLElement */
                $totalsXml = $totalsStack[$l];
                $docTotals = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($totalsXml);

                $xmlRootNode      = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode    = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $workingdocsNode  = $sourceDocNode->addChild(WorkingDocuments::N_WORKINGDOCUMENTS);
                $workdocStackNode = $workingdocsNode->addChild(WorkDocument::N_WORKDOCUMENT);

                $xml = $docTotals->createXmlNode($workdocStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($totalsXml, $xml);
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

            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
            /* @phpstan-ignore-next-line */
            $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $docTotalsNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORKDOCUMENT."></".WorkDocument::N_WORKDOCUMENT.">"
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $docTotalsNode = new \SimpleXMLElement(
            "<".WorkDocument::N_WORKDOCUMENT."></".WorkDocument::N_WORKDOCUMENT.">"
        );
        $docTotals     = new DocumentTotals(new ErrorRegister());
        $docTotals->setGrossTotal(-1.0);
        $docTotals->setNetTotal(-2.0);
        $docTotals->setTaxPayable(-0.5);

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
