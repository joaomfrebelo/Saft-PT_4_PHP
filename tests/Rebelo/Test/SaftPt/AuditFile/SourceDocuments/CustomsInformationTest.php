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
use Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * Class CustomsInformationTest
 *
 * @author João Rebelo
 */
class CustomsInformationTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(CustomsInformation::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstanceSetGet()
    {
        $ci = new CustomsInformation();
        $this->assertInstanceOf(CustomsInformation::class, $ci);
        $this->assertTrue(\is_array($ci->getArcNo()));
        $this->assertSame(0, \count($ci->getArcNo()));
        $this->assertNull($ci->getIecAmount());

        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $index = $ci->addToARCNo(\strval($n));
            $this->assertSame(
                \strval($n), $ci->getArcNo()[$n]
            );
            $this->assertSame($n, $index);
            $this->assertTrue($ci->issetARCNo($n));
        }
        $amount = 49.59;
        $ci->setIecAmount($amount);
        $this->assertSame($amount, $ci->getIecAmount());

        $unset = 2;
        $ci->unsetARCNo($unset);
        $this->assertFalse($ci->issetARCNo($unset));

        $pad = $ci->addToARCNo(\str_pad("A", 99, "A"));
        $this->assertSame(21, \strlen($ci->getArcNo()[$pad]));

        try {
            $ci->addToARCNo("");
            $this->fail("Add a ARCNo empty string to the stack should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $ci->setIecAmount(-0.01);
            $this->fail("Set a negative IEC Amount should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    public function createCustomsInformation(): CustomsInformation
    {
        $ci   = new CustomsInformation();
        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $ci->addToARCNo(\strval($n));
        }
        $ci->setIecAmount(49.95);
        return $ci;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $ci   = new CustomsInformation();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ci->createXmlNode($node);
            $this->fail("Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testParseXmlNodeWrongName()
    {
        $ci   = new CustomsInformation();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ci->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $ci   = $this->createCustomsInformation();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $ciNode = $ci->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $ciNode);

        $this->assertSame(
            CustomsInformation::N_CUSTOMSINFORMATION, $ciNode->getName()
        );

        for ($n = 0; $n < \count($ci->getArcNo()); $n++) {
            $this->assertSame(
                $ci->getArcNo()[$n],
                (string) $node->{CustomsInformation::N_CUSTOMSINFORMATION}
                ->{CustomsInformation::N_ARCNO}[$n]
            );
        }

        $this->assertSame(
            $ci->getIecAmount(),
            (float) $node->{CustomsInformation::N_CUSTOMSINFORMATION}
            ->{CustomsInformation::N_IECAMOUNT}
        );
    }

    /**
     *
     */
    public function testCreateXmlNodeNull()
    {
        $ci   = new CustomsInformation();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $ciNode = $ci->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $ciNode);

        $this->assertSame(
            CustomsInformation::N_CUSTOMSINFORMATION, $ciNode->getName()
        );

        $this->assertSame(0,
            $node->{CustomsInformation::N_CUSTOMSINFORMATION}
            ->{CustomsInformation::N_ARCNO}->count()
        );

        $this->assertSame(0,
            $node->{CustomsInformation::N_CUSTOMSINFORMATION}
            ->{CustomsInformation::N_IECAMOUNT}->count()
        );
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $ci   = $this->createCustomsInformation();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ci->createXmlNode($node)->asXML();

        $parsed = new CustomsInformation();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        for ($n = 0; $n < \count($ci->getArcNo()); $n++) {
            $this->assertSame(
                $ci->getArcNo()[$n], $parsed->getArcNo()[$n]
            );
        }

        $this->assertSame($ci->getIecAmount(), $parsed->getIecAmount());
    }

    /**
     *
     */
    public function testeParseXmlEmpty()
    {
        $ci   = new CustomsInformation();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ci->createXmlNode($node)->asXML();

        $parsed = new CustomsInformation();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ci->getArcNo(), $parsed->getArcNo()
        );

        $this->assertSame(
            $ci->getIecAmount(), $parsed->getIecAmount()
        );
    }
}