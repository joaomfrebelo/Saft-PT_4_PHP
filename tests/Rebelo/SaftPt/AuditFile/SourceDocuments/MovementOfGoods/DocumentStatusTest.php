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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Commune;
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
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(DocumentStatus::class))->testReflection(DocumentStatus::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $this->assertInstanceOf(DocumentStatus::class, $docStatus);
        $this->assertNull($docStatus->getReason());
        $this->assertFalse($docStatus->issetSourceID());
        $this->assertFalse($docStatus->issetMovementStatus());
        $this->assertFalse($docStatus->issetMovementStatusDate());
        $this->assertFalse($docStatus->issetSourceBilling());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetMovementStatus(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $status    = MovementStatus::N;
        $docStatus->setMovementStatus($status);
        $this->assertSame($status, $docStatus->getMovementStatus());
        $this->assertTrue($docStatus->issetMovementStatus());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetMovementStatusDate(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $date      = new RDate();
        $docStatus->setMovementStatusDate($date);
        $this->assertSame($date, $docStatus->getMovementStatusDate());
        $this->assertTrue($docStatus->issetMovementStatusDate());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetReason(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $reason    = "Reason of status";
        $this->assertTrue($docStatus->setReason($reason));
        $this->assertSame($reason, $docStatus->getReason());
        $this->assertTrue($docStatus->setReason(null));
        $this->assertNull($docStatus->getReason());
        $this->assertTrue($docStatus->setReason(str_pad($reason, 50, "A")));
        $this->assertSame(50, \strlen($docStatus->getReason()));

        $this->assertFalse($docStatus->setReason(""));
        $this->assertSame("", $docStatus->getReason());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetSourceID(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $sourceId  = "operator";
        $docStatus->setSourceID($sourceId);
        $this->assertSame($sourceId, $docStatus->getSourceID());
        $this->assertTrue($docStatus->issetSourceID());
        $docStatus->setSourceID(str_pad($sourceId, 50, "A"));
        $this->assertSame(30, \strlen($docStatus->getSourceID()));

        $this->assertFalse($docStatus->setSourceID(""));
        $this->assertSame("", $docStatus->getSourceID());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetSourceBilling(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $srcBill   = SourceBilling::M;
        $docStatus->setSourceBilling($srcBill);
        $this->assertSame($srcBill, $docStatus->getSourceBilling());
        $this->assertTrue($docStatus->issetSourceBilling());
    }

    /**
     * Reads all work status from the Demo SAFT in Test\Resources
     * and parse then to work status class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXml(): void
    {
        $docStatus = null;
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $stockMovDocStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCE_DOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENT_OF_GOODS}
            ->{StockMovement::N_STOCK_MOVEMENT};

        if ($stockMovDocStack->count() === 0) {
            $this->fail("No StockMovements in XML");
        }

        for ($i = 0; $i < $stockMovDocStack->count(); $i++) {
            $stockMovDocStackXml = $stockMovDocStack[$i];
            $statusStack         = $stockMovDocStackXml->{DocumentStatus::N_DOCUMENT_STATUS};

            if ($statusStack->count() === 0) {
                $this->fail("No document status in StockMov");
            }

            for ($l = 0; $l < $statusStack->count(); $l++) {
                /* @var $statusXml \SimpleXMLElement */
                $statusXml = $statusStack[$l];
                $docStatus = new DocumentStatus(new ErrorRegister());
                $docStatus->parseXmlNode($statusXml);

                $xmlRootNode       = (new AuditFile())->createRootElement();
                $sourceDocNode     = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
                $movOfGoodsNode    = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENT_OF_GOODS);
                $stockMovStackNode = $movOfGoodsNode->addChild(StockMovement::N_STOCK_MOVEMENT);

                $xml = $docStatus->createXmlNode($stockMovStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($statusXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovDocStackXml->{StockMovement::N_DOCUMENT_NUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovDocStackXml->{StockMovement::N_DOCUMENT_NUMBER},
                            $e->getMessage()
                        )
                    );
                }
            }
        }

        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $productNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCK_MOVEMENT."></".StockMovement::N_STOCK_MOVEMENT.">"
        );
        $docStatus   = new DocumentStatus(new ErrorRegister());
        $xml         = $docStatus->createXmlNode($productNode)->asXML();
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
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $docStatusNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCK_MOVEMENT."></".StockMovement::N_STOCK_MOVEMENT.">"
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
