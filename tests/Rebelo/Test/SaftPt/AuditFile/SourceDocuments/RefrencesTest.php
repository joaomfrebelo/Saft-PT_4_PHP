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
use Rebelo\SaftPt\AuditFile\SourceDocuments\References;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * Class newPHPClassTest
 *
 * @author João Rebelo
 */
class ReferencesTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(References::class);
        $this->assertTrue(true);
    }

    public function testInstanceSetGet()
    {
        $ref = new References();
        $this->assertInstanceOf(References::class, $ref);
        $this->assertNull($ref->getReason());
        $this->assertNull($ref->getReference());

        $refer = "Reference document";
        $ref->setReference($refer);
        $this->assertSame($refer, $ref->getReference());
        $ref->setReference(null);
        $this->assertNull($ref->getReference());

        $reason = "Reason of ref";
        $ref->setReason($reason);
        $this->assertSame($reason, $ref->getReason());
        $ref->setReason(null);
        $this->assertNull($ref->getReason());

        $ref->setReference(\str_pad($refer, 70, "A"));
        $this->assertSame(60, \strlen($ref->getReference()));

        $ref->setReason(\str_pad($reason, 70, "A"));
        $this->assertSame(50, \strlen($ref->getReason()));

        try {
            $ref->setReference("");
            $this->fail("Set Reference to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $ref->setReason("");
            $this->fail("Set Reason to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function createReferences(): References
    {
        $ref = new References();
        $ref->setReference("Reference");
        $ref->setReason("Reason");

        return $ref;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $ref  = new References();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ref->createXmlNode($node);
            $this->fail("Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testParseXmlNodeWrongName()
    {
        $ref  = new References();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ref->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $ref  = $this->createReferences();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $refNode = $ref->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $refNode);

        $this->assertSame(
            References::N_REFERENCES, $refNode->getName()
        );

        $this->assertSame(
            $ref->getReference(),
            (string) $node->{References::N_REFERENCES}->{References::N_REFERENCE}
        );

        $this->assertSame(
            $ref->getReason(),
            (string) $node->{References::N_REFERENCES}->{References::N_REASON}
        );
    }

    /**
     *
     */
    public function testCreateXmlNodeNull()
    {
        $ref  = new References();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $refNode = $ref->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $refNode);

        $this->assertSame(
            References::N_REFERENCES, $refNode->getName()
        );

        $this->assertSame(0,
            $node->{References::N_REFERENCES}->{References::N_REFERENCE}->count()
        );

        $this->assertSame(0,
            $node->{References::N_REFERENCES}->{References::N_REASON}->count()
        );
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $ref  = $this->createReferences();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ref->createXmlNode($node)->asXML();

        $parsed = new References();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ref->getReference(), $parsed->getReference()
        );

        $this->assertSame(
            $ref->getReason(), $parsed->getReason()
        );
    }

    /**
     *
     */
    public function testeParseXmlNull()
    {
        $ref  = new References();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ref->createXmlNode($node)->asXML();

        $parsed = new References();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ref->getReference(), $parsed->getReference()
        );

        $this->assertSame(
            $ref->getReason(), $parsed->getReason()
        );
    }
}