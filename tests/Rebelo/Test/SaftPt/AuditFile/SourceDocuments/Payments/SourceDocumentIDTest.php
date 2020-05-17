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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\Payments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line;

/**
 * SourceDocumentIDTest
 *
 * @author João Rebelo
 */
class SourceDocumentIDTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(SourceDocumentID::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $source = new SourceDocumentID();
        $this->assertNull($source->getDescription());

        try {
            $source->getOriginatingON();
            $this->fail("Get OriginatingON before setting should throw \Error");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try {
            $source->getInvoiceDate();
            $this->fail("Get InvoiceDate before setting should throw \Error");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        $invoice = "FT FT/1";
        $source->setOriginatingON($invoice);
        $this->assertSame($invoice, $source->getOriginatingON());

        $date = new RDate();
        $source->setInvoiceDate($date);
        $this->assertSame($date, $source->getInvoiceDate());

        $des = "Description";
        $source->setDescription($des);
        $this->assertSame($des, $source->getDescription());
        $source->setDescription(null);
        $this->assertNull($source->getDescription());

        $source->setDescription(\str_pad("A", 299, "A"));
        $this->assertSame(200, \strlen($source->getDescription()));

        try {
            $source->setDescription("");
            $this->fail("Set description to an empty string should throw"
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(AuditFileException::class, $ex);
        }
    }

    /**
     *
     * @return SourceDocumentID
     */
    public function createSourceDocumentID(): SourceDocumentID
    {
        $source = new SourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        $source->setInvoiceDate(new RDate());
        $source->setDescription("Source Document description");
        return $source;
    }

    public function testCreateXmlNodeWrongName()
    {
        $source = new SourceDocumentID();
        $node   = new \SimpleXMLElement("<root></root>"
        );
        try {
            $source->createXmlNode($node);
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
        $source = new SourceDocumentID();
        $node   = new \SimpleXMLElement("<root></root>"
        );
        try {
            $source->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testCreateXmlNode()
    {
        $source     = $this->createSourceDocumentID();
        $node       = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $sourceNode = $source->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $sourceNode);
        $this->assertSame(
            SourceDocumentID::N_SOURCEDOCUMENTID, $sourceNode->getName()
        );

        $this->assertSame(
            $source->getOriginatingON(),
            (string) $node->{SourceDocumentID::N_SOURCEDOCUMENTID}
            ->{SourceDocumentID::N_ORIGINATINGON}
        );

        $this->assertSame(
            $source->getInvoiceDate()->format(RDate::SQL_DATE),
            (string) $node->{SourceDocumentID::N_SOURCEDOCUMENTID}
            ->{SourceDocumentID::N_INVOICEDATE}
        );
        $this->assertSame(
            $source->getDescription(),
            (string) $node->{SourceDocumentID::N_SOURCEDOCUMENTID}
            ->{SourceDocumentID::N_DESCRIPTION}
        );
    }

    /**
     *
     */
    public function testCreateXmlNodeNull()
    {
        $source     = $this->createSourceDocumentID();
        $source->setDescription(null);
        $node       = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $sourceNode = $source->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $sourceNode);
        $this->assertSame(
            SourceDocumentID::N_SOURCEDOCUMENTID, $sourceNode->getName()
        );

        $this->assertSame(
            $source->getOriginatingON(),
            (string) $node->{SourceDocumentID::N_SOURCEDOCUMENTID}
            ->{SourceDocumentID::N_ORIGINATINGON}
        );

        $this->assertSame(
            $source->getInvoiceDate()->format(RDate::SQL_DATE),
            (string) $node->{SourceDocumentID::N_SOURCEDOCUMENTID}
            ->{SourceDocumentID::N_INVOICEDATE}
        );
        $this->assertNull(
            $source->getDescription()
        );
    }

    public function testeParseXml()
    {
        $node   = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $source = $this->createSourceDocumentID();
        $xml    = $source->createXmlNode($node)->asXML();

        $parsed = new SourceDocumentID();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($source->getOriginatingON(),
            $parsed->getOriginatingON());
        $this->assertSame(
            $source->getInvoiceDate()->format(RDate::SQL_DATE),
            $parsed->getInvoiceDate()->format(RDate::SQL_DATE)
        );
        $this->assertSame($source->getDescription(), $parsed->getDescription());
    }

    /**
     * 
     */
    public function testeParseXmlNull()
    {
        $node   = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $source = $this->createSourceDocumentID();
        $source->setDescription(null);
        $xml    = $source->createXmlNode($node)->asXML();

        $parsed = new SourceDocumentID();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($source->getOriginatingON(),
            $parsed->getOriginatingON());
        $this->assertSame(
            $source->getInvoiceDate()->format(RDate::SQL_DATE),
            $parsed->getInvoiceDate()->format(RDate::SQL_DATE)
        );
        $this->assertNull($parsed->getDescription());
    }
}