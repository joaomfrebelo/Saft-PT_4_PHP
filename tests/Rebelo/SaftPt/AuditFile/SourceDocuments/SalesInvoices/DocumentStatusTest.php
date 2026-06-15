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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\Commune;

/**
 * Class DocumentStatusTest
 *
 * @author João Rebelo
 */
class DocumentStatusTest extends TestCase
{

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
        $this->assertInstanceOf(
            DocumentStatus::class, $docStatus
        );

        $this->assertNull($docStatus->getReason());
        $this->assertFalse($docStatus->issetInvoiceStatus());
        $this->assertFalse($docStatus->issetInvoiceStatusDate());
        $this->assertFalse($docStatus->issetSourceBilling());
        $this->assertFalse($docStatus->issetSourceID());

        try {
            $docStatus->getInvoiceStatus();
            $this->fail("getInvoiceStatus should throw error before set");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getInvoiceStatusDate();
            $this->fail("getInvoiceStatusDate should throw error before set");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getSourceBilling();
            $this->fail("getSourceBilling should throw error before set");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getSourceID();
            $this->fail("getSourceID should throw error before set");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInvoiceStatus(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $status    = InvoiceStatus::N;
        $docStatus->setInvoiceStatus($status);
        $this->assertSame($status, $docStatus->getInvoiceStatus());
        $this->assertTrue($docStatus->issetInvoiceStatus());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInvoiceStatusDate(): void
    {
        $date      = new RDate();
        $docStatus = new DocumentStatus(new ErrorRegister());
        $docStatus->setInvoiceStatusDate($date);
        $this->assertSame(
            $date->format(Pattern::DATE_T_TIME),
            $docStatus->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME)
        );
        $this->assertTrue($docStatus->issetInvoiceStatusDate());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testReason(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $reason    = "Test reason";
        $this->assertTrue($docStatus->setReason($reason));
        $this->assertSame($reason, $docStatus->getReason());
        $this->assertTrue($docStatus->setReason(null));
        $this->assertNull($docStatus->getReason());
        $this->assertTrue($docStatus->setReason(\str_pad($reason, 51, "9")));
        $this->assertSame(50, \strlen($docStatus->getReason()));

        $this->assertFalse($docStatus->setReason(""));
        $this->assertSame("", $docStatus->getReason());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSourceID(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $sourceID  = "Test sourceID";
        $this->assertTrue($docStatus->setSourceID($sourceID));
        $this->assertSame($sourceID, $docStatus->getSourceID());
        $this->assertTrue($docStatus->issetSourceID());
        $this->assertTrue($docStatus->setSourceID(\str_pad($sourceID, 31, "9")));
        $this->assertSame(30, \strlen($docStatus->getSourceID()));

        $this->assertFalse($docStatus->setSourceID(""));
        $this->assertSame("", $docStatus->getSourceID());
        $this->assertNotEmpty($docStatus->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSourceBilling(): void
    {
        $billing   = SourceBilling::P;
        $docStatus = new DocumentStatus(new ErrorRegister());
        $docStatus->setSourceBilling($billing);
        $this->assertSame($billing, $docStatus->getSourceBilling());
        $this->assertTrue($docStatus->issetSourceBilling());
    }

    /**
     * @author João Rebelo
     */
    public function createDocumentStatus(): DocumentStatus
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(new RDate());
        $docStatus->setReason("Reason test");
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Test source ID");
        return $docStatus;
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $docStatus = $this->createDocumentStatus();
        $node      = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docStaNode = $docStatus->createXmlNode($node);

        $this->assertInstanceOf(\SimpleXMLElement::class, $docStaNode);

        $this->assertSame(
            DocumentStatus::N_DOCUMENT_STATUS, $docStaNode->getName()
        );

        $this->assertSame(
            $docStatus->getInvoiceStatus()->value,
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_INVOICE_STATUS}
        );

        $this->assertSame(
            $docStatus->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME),
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_INVOICE_STATUS_DATE}
        );

        $this->assertSame(
            $docStatus->getReason(),
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_REASON}
        );

        $this->assertSame(
            $docStatus->getSourceBilling()->value,
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_SOURCE_BILLING}
        );

        $this->assertSame(
            $docStatus->getSourceID(),
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_SOURCE_ID}
        );
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeNullReason(): void
    {
        $docStatus = $this->createDocumentStatus();
        $docStatus->setReason(null);
        $node      = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docStaNode = $docStatus->createXmlNode($node);

        $this->assertInstanceOf(\SimpleXMLElement::class, $docStaNode);

        $this->assertSame(
            DocumentStatus::N_DOCUMENT_STATUS, $docStaNode->getName()
        );

        $this->assertSame(
            $docStatus->getInvoiceStatus()->value,
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_INVOICE_STATUS}
        );

        $this->assertSame(
            $docStatus->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME),
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_INVOICE_STATUS_DATE}
        );

        $this->assertSame(
            0,
            $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_REASON}->count()
        );

        $this->assertSame(
            $docStatus->getSourceBilling()->value,
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_SOURCE_BILLING}
        );

        $this->assertSame(
            $docStatus->getSourceID(),
            (string) $node->{DocumentStatus::N_DOCUMENT_STATUS}->{DocumentStatus::N_SOURCE_ID}
        );
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docSta = $this->createDocumentStatus();
        $xml    = $docSta->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentStatus(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($docSta->getInvoiceStatus(), $parsed->getInvoiceStatus());

        $this->assertSame(
            $docSta->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME),
            $parsed->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME)
        );

        $this->assertSame($docSta->getReason(), $parsed->getReason());

        $this->assertSame($docSta->getSourceBilling(), $parsed->getSourceBilling());

        $this->assertSame($docSta->getSourceID(), $parsed->getSourceID());

        $this->assertEmpty($docSta->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docSta->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docSta->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlReasonNull(): void
    {
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docSta = $this->createDocumentStatus();
        $docSta->setReason(null);
        $xml    = $docSta->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentStatus(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($docSta->getInvoiceStatus(), $parsed->getInvoiceStatus());

        $this->assertSame(
            $docSta->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME),
            $parsed->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME)
        );

        $this->assertSame($docSta->getReason(), $parsed->getReason());

        $this->assertSame($docSta->getSourceBilling(), $parsed->getSourceBilling());

        $this->assertSame($docSta->getSourceID(), $parsed->getSourceID());

        $this->assertEmpty($docSta->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docSta->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docSta->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $docStatus = new DocumentStatus(new ErrorRegister());
        $node      = new \SimpleXMLElement("<root></root>");
        try {
            $docStatus->createXmlNode($node);
            $this->fail(
                "Creat a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNodeWrongName(): void
    {
        $docStat = new DocumentStatus(new ErrorRegister());
        $node    = new \SimpleXMLElement("<root></root>");
        try {
            $docStat->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $statusNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $status     = new DocumentStatus(new ErrorRegister());
        $xml        = $status->createXmlNode($statusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($status->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($status->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($status->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $statusNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $status     = new DocumentStatus(new ErrorRegister());
        $status->setReason("");
        $status->setSourceID("");

        $xml = $status->createXmlNode($statusNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($status->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($status->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($status->getErrorRegistor()->getLibXmlError());
    }
}
