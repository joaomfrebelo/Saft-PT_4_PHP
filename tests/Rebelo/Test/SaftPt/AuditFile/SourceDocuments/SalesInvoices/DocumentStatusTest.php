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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;

/**
 * Class DocumentStatusTest
 *
 * @author João Rebelo
 */
class DocumentStatusTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $docStatus = new DocumentStatus();
        $this->assertInstanceOf(
            DocumentStatus::class, $docStatus
        );

        $this->assertNull($docStatus->getReason());

        try {
            $docStatus->getInvoiceStatus();
            $this->fail("getInvoiceStatus should throw error before setted");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getInvoiceStatusDate();
            $this->fail("getInvoiceStatusDate should throw error before setted");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getSourceBilling();
            $this->fail("getSourceBilling should throw error before setted");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $docStatus->getSourceID();
            $this->fail("getSourceID should throw error before setted");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }
    }

    public function testInvoiceStatus()
    {
        $docStatus = new DocumentStatus();
        $status    = InvoiceStatus::N;
        $docStatus->setInvoiceStatus(new InvoiceStatus($status));
        $this->assertSame($status, $docStatus->getInvoiceStatus()->get());
    }

    public function testInvoiceStatusDate()
    {
        $date      = new RDate();
        $docStatus = new DocumentStatus();
        $docStatus->setInvoiceStatusDate($date);
        $this->assertSame(
            $date->format(RDate::DATE_T_TIME),
            $docStatus->getInvoiceStatusDate()->format(RDate::DATE_T_TIME)
        );
    }

    public function testReazon()
    {
        $docStatus = new DocumentStatus();
        $reason    = "Test reason";
        $docStatus->setReason($reason);
        $this->assertSame($reason, $docStatus->getReason());
        $docStatus->setReason(null);
        $this->assertNull($docStatus->getReason());
        try {
            $docStatus->setReason("");
            $this->fail("Reason should throw AuditFileException "
                ."when setted to an empty string");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(AuditFileException::class, $ex);
        }
        $docStatus->setReason(\str_pad($reason, 51, "9"));
        $this->assertSame(50, \strlen($docStatus->getReason()));
    }

    public function testSourceID()
    {
        $docStatus = new DocumentStatus();
        $sourceID  = "Test sourceID";
        $docStatus->setSourceID($sourceID);
        $this->assertSame($sourceID, $docStatus->getSourceID());
        try {
            $docStatus->setSourceID("");
            $this->fail("SourceID should throw AuditFileException "
                ."when setted to an empty string");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(AuditFileException::class, $ex);
        }
        $docStatus->setSourceID(\str_pad($sourceID, 31, "9"));
        $this->assertSame(30, \strlen($docStatus->getSourceID()));
    }

    public function testSourceBilling()
    {
        $billing   = SourceBilling::P;
        $docStatus = new DocumentStatus();
        $docStatus->setSourceBilling(new SourceBilling($billing));
        $this->assertSame($billing, $docStatus->getSourceBilling()->get());
    }

    public function createDocumentStatus(): DocumentStatus
    {
        $docStatus = new DocumentStatus();
        $docStatus->setInvoiceStatus(
            new InvoiceStatus(InvoiceStatus::N)
        );
        $docStatus->setInvoiceStatusDate(new RDate());
        $docStatus->setReason("Reason test");
        $docStatus->setSourceBilling(
            new SourceBilling(SourceBilling::P)
        );
        $docStatus->setSourceID("Test source ID");
        return $docStatus;
    }

    public function testCreateXmlNode()
    {
        $docStatus  = $this->createDocumentStatus();
        $node       = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docStaNode = $docStatus->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docStaNode);
        $this->assertSame(
            DocumentStatus::N_DOCUMENTSTATUS, $docStaNode->getName()
        );
        $this->assertSame(
            $docStatus->getInvoiceStatus()->get(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_INVOICESTATUS}
        );
        $this->assertSame(
            $docStatus->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}
            ->{DocumentStatus::N_INVOICESTATUSDATE}
        );
        $this->assertSame(
            $docStatus->getReason(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_REASON}
        );
        $this->assertSame(
            $docStatus->getSourceBilling()->get(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_SOURCEBILLING}
        );
        $this->assertSame(
            $docStatus->getSourceID(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_SOURCEID}
        );
    }

    public function testCreateXmlNodeNullReason()
    {
        $docStatus  = $this->createDocumentStatus();
        $docStatus->setReason(null);
        $node       = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docStaNode = $docStatus->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docStaNode);
        $this->assertSame(
            DocumentStatus::N_DOCUMENTSTATUS, $docStaNode->getName()
        );
        $this->assertSame(
            $docStatus->getInvoiceStatus()->get(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_INVOICESTATUS}
        );
        $this->assertSame(
            $docStatus->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}
            ->{DocumentStatus::N_INVOICESTATUSDATE}
        );
        $this->assertSame(0,
            $node->{DocumentStatus::N_DOCUMENTSTATUS}
            ->{DocumentStatus::N_REASON}->count()
        );
        $this->assertSame(
            $docStatus->getSourceBilling()->get(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_SOURCEBILLING}
        );
        $this->assertSame(
            $docStatus->getSourceID(),
            (string) $node->{DocumentStatus::N_DOCUMENTSTATUS}->{DocumentStatus::N_SOURCEID}
        );
    }

    public function testeParseXml()
    {
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docSta = $this->createDocumentStatus();
        $xml    = $docSta->createXmlNode($node)->asXML();

        $parsed = new DocumentStatus();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($docSta->getInvoiceStatus()->get(),
            $parsed->getInvoiceStatus()->get());
        $this->assertSame($docSta->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME),
            $parsed->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME));
        $this->assertSame($docSta->getReason(), $parsed->getReason());
        $this->assertSame($docSta->getSourceBilling()->get(),
            $parsed->getSourceBilling()->get());
        $this->assertSame($docSta->getSourceID(), $parsed->getSourceID());
    }

    public function testeParseXmlReasonNull()
    {
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docSta = $this->createDocumentStatus();
        $docSta->setReason(null);
        $xml    = $docSta->createXmlNode($node)->asXML();

        $parsed = new DocumentStatus();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($docSta->getInvoiceStatus()->get(),
            $parsed->getInvoiceStatus()->get());
        $this->assertSame($docSta->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME),
            $parsed->getInvoiceStatusDate()
                ->format(RDate::DATE_T_TIME));
        $this->assertSame($docSta->getReason(), $parsed->getReason());
        $this->assertSame($docSta->getSourceBilling()->get(),
            $parsed->getSourceBilling()->get());
        $this->assertSame($docSta->getSourceID(), $parsed->getSourceID());
    }

    public function testCreateXmlNodeWrongName()
    {
        $docStatus = new DocumentStatus();
        $node      = new \SimpleXMLElement("<root></root>"
        );
        try {
            $docStatus->createXmlNode($node);
            $this->fail("Creat a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testParseXmlNodeWrongName()
    {
        $docStat = new DocumentStatus();
        $node    = new \SimpleXMLElement("<root></root>"
        );
        try {
            $docStat->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }
}