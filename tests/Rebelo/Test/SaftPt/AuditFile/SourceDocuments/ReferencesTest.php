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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(References::class);
        $this->assertTrue(true);
    }

    public function testInstanceSetGet(): void
    {
        $ref = new References(new ErrorRegister());
        $this->assertInstanceOf(References::class, $ref);
        $this->assertNull($ref->getReason());
        $this->assertNull($ref->getReference());

        $refer = "Reference document";
        $this->assertTrue($ref->setReference($refer));
        $this->assertSame($refer, $ref->getReference());
        $this->assertTrue($ref->setReference(null));
        $this->assertNull($ref->getReference());

        $reason = "Reason of ref";
        $this->assertTrue($ref->setReason($reason));
        $this->assertSame($reason, $ref->getReason());
        $this->assertTrue($ref->setReason(null));
        $this->assertNull($ref->getReason());

        $this->assertTrue($ref->setReference(\str_pad($refer, 70, "A")));
        $this->assertSame(60, \strlen($ref->getReference()));

        $this->assertTrue($ref->setReason(\str_pad($reason, 70, "A")));
        $this->assertSame(50, \strlen($ref->getReason()));

        $ref->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($ref->setReference(""));
        $this->assertSame("", $ref->getReference());
        $this->assertNotEmpty($ref->getErrorRegistor()->getOnSetValue());

        $ref->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($ref->setReason(""));
        $this->assertSame("", $ref->getReason());
        $this->assertNotEmpty($ref->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     */
    public function createReferences(): References
    {
        $ref = new References(new ErrorRegister());
        $ref->setReference("Reference");
        $ref->setReason("Reason");

        return $ref;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $ref  = new References(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ref->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testParseXmlNodeWrongName(): void
    {
        $ref  = new References(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ref->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testCreateXmlNode(): void
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

        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ref->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     */
    public function testCreateXmlNodeNull(): void
    {
        $ref  = new References(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $refNode = $ref->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $refNode);

        $this->assertSame(
            References::N_REFERENCES, $refNode->getName()
        );

        $this->assertSame(
            0,
            $node->{References::N_REFERENCES}->{References::N_REFERENCE}->count()
        );

        $this->assertSame(
            0,
            $node->{References::N_REFERENCES}->{References::N_REASON}->count()
        );

        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ref->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     */
    public function testeParseXml(): void
    {
        $ref  = $this->createReferences();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ref->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new References(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ref->getReference(), $parsed->getReference()
        );

        $this->assertSame(
            $ref->getReason(), $parsed->getReason()
        );

        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ref->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     */
    public function testeParseXmlNull(): void
    {
        $ref  = new References(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ref->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new References(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ref->getReference(), $parsed->getReference()
        );

        $this->assertSame(
            $ref->getReason(), $parsed->getReason()
        );

        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ref->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $refNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $ref     = new References(new ErrorRegister());
        $xml     = $ref->createXmlNode($refNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ref->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $refNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $ref     = new References(new ErrorRegister());
        $ref->setReference("");
        $ref->setReason("");

        $xml = $ref->createXmlNode($refNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ref->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($ref->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ref->getErrorRegistor()->getLibXmlError());
    }
}
