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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus;

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
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
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
        $this->assertFalse($docStatus->issetSourceID());
        $this->assertFalse($docStatus->issetMovementStatus());
        $this->assertFalse($docStatus->issetMovementStatusDate());
        $this->assertFalse($docStatus->issetSourceBilling());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetMovementStatus(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $status    = MovementStatus::N;
        $docStatus->setMovementStatus(new MovementStatus($status));
        $this->assertSame($status, $docStatus->getMovementStatus()->get());
        $this->assertTrue($docStatus->issetMovementStatus());
    }

    /**
     * @author João Rebelo
     * @test
     */
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
     * @test
     */
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
     * @test
     */
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
     * @test
     */
    public function testSetGetSourceBilling(): void
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
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $stockMovDocStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{MovementOfGoods::N_MOVEMENTOFGOODS}
            ->{StockMovement::N_STOCKMOVEMENT};

        if ($stockMovDocStack->count() === 0) {
            $this->fail("No StockMovements in XML");
        }

        for ($i = 0; $i < $stockMovDocStack->count(); $i++) {
            $stockMovDocStackXml = $stockMovDocStack[$i];
            $statusStack         = $stockMovDocStackXml->{DocumentStatus::N_DOCUMENTSTATUS};

            if ($statusStack->count() === 0) {
                $this->fail("No documemntstatus in StockMov");
            }

            for ($l = 0; $l < $statusStack->count(); $l++) {
                /* @var $statusXml \SimpleXMLElement */
                $statusXml = $statusStack[$l];
                $docStatus = new DocumentStatus(new ErrorRegister());
                $docStatus->parseXmlNode($statusXml);

                $xmlRootNode       = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
                $sourceDocNode     = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $movOfGoodsNode    = $sourceDocNode->addChild(MovementOfGoods::N_MOVEMENTOFGOODS);
                $stockMovStackNode = $movOfGoodsNode->addChild(StockMovement::N_STOCKMOVEMENT);

                $xml = $docStatus->createXmlNode($stockMovStackNode);

                try {
                    $assertXml = $this->xmlIsEqual($statusXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovDocStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $stockMovDocStackXml->{StockMovement::N_DOCUMENTNUMBER},
                            $e->getMessage()
                        )
                    );
                }
            }
        }

        /* @phpstan-ignore-next-line */
        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $productNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $docStatus   = new DocumentStatus(new ErrorRegister());
        $xml         = $docStatus->createXmlNode($productNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docStatus->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $docStatusNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $docStatus     = new DocumentStatus(new ErrorRegister());
        $docStatus->setReason("");
        $docStatus->setSourceID("");

        $xml = $docStatus->createXmlNode($docStatusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docStatus->getErrorRegistor()->getLibXmlError());
    }
}