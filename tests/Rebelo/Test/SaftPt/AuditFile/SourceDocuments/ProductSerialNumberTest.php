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
use Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * Class ProductSerialNumberTest
 *
 * @author João Rebelo
 */
class ProductSerialNumberTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(ProductSerialNumber::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceGetSet(): void
    {
        $psn = new ProductSerialNumber(new ErrorRegister());
        $this->assertInstanceOf(ProductSerialNumber::class, $psn);

        $this->assertTrue(\is_array($psn->getSerialNumber()));
        $this->assertSame(0, \count($psn->getSerialNumber()));

        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $this->assertTrue($psn->addSerialNumber(\strval($n)));
        }

        $this->assertSame($nMax, \count($psn->getSerialNumber()));

        $stack = $psn->getSerialNumber();
        for ($n = 0; $n < $nMax; $n++) {
            $this->assertSame(\strval($n), $stack[$n]);
        }

        $this->assertTrue($psn->addSerialNumber(\str_pad("A", 120, "9")));
        $pad = $psn->getSerialNumber()[$n];
        $this->assertSame(100, \strlen($pad));

        $psn->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($psn->addSerialNumber(""));
        $this->assertSame("", $psn->getSerialNumber()[++$n]);
        $this->assertNotEmpty($psn->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber
     */
    public function createProductSerialNumber(): ProductSerialNumber
    {
        $psn  = new ProductSerialNumber(new ErrorRegister());
        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $psn->addSerialNumber(\strval($n));
        }
        return $psn;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $psn  = new ProductSerialNumber(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $psn->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $psn  = new ProductSerialNumber(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $psn->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $psr  = $this->createProductSerialNumber();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $psrNode = $psr->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $psrNode);

        $this->assertSame(
            ProductSerialNumber::N_PRODUCTSERIALNUMBER, $psrNode->getName()
        );

        for ($n = 0; $n < \count($psr->getSerialNumber()); $n++) {
            $this->assertSame(
                $psr->getSerialNumber()[$n],
                (string) $node->{ProductSerialNumber::N_PRODUCTSERIALNUMBER}
                ->{ProductSerialNumber::N_SERIALNUMBER}[$n]
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeEmpty(): void
    {
        $psr  = new ProductSerialNumber(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $psrlNode = $psr->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $psrlNode);

        $this->assertSame(
            1,
            $node->{ProductSerialNumber::N_PRODUCTSERIALNUMBER}->count()
        );

        $this->assertSame(
            0,
            $node->{ProductSerialNumber::N_PRODUCTSERIALNUMBER}
                ->{ProductSerialNumber::N_SERIALNUMBER}
                ->count()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $psr  = $this->createProductSerialNumber();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $psr->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new ProductSerialNumber(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        for ($n = 0; $n < \count($psr->getSerialNumber()); $n++) {
            $this->assertSame(
                $psr->getSerialNumber()[$n], $parsed->getSerialNumber()[$n]
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlEmpty(): void
    {
        $psr  = new ProductSerialNumber(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $psr->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new ProductSerialNumber(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $psr->getSerialNumber(), $parsed->getSerialNumber()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $psnNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $psn     = new ProductSerialNumber(new ErrorRegister());
        $xml     = $psn->createXmlNode($psnNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($psn->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($psn->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($psn->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $psnNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $psn     = new ProductSerialNumber(new ErrorRegister());
        $psn->addSerialNumber("");

        $xml = $psn->createXmlNode($psnNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($psn->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($psn->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($psn->getErrorRegistor()->getLibXmlError());
    }
}