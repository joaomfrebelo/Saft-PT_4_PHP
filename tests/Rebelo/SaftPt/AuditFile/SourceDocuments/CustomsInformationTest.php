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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class CustomsInformationTest
 *
 * @author João Rebelo
 */
class CustomsInformationTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(CustomsInformation::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGet(): void
    {
        $ci = new CustomsInformation(new ErrorRegister());
        $this->assertInstanceOf(CustomsInformation::class, $ci);
        $this->assertTrue(\is_array($ci->getArcNo()));
        $this->assertSame(0, \count($ci->getArcNo()));
        $this->assertNull($ci->getIecAmount());

        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $this->assertTrue($ci->addARCNo(\strval($n)));
            $this->assertSame(
                \strval($n), $ci->getArcNo()[$n]
            );
        }

        $amount = 49.59;
        $this->assertTrue($ci->setIecAmount($amount));
        $this->assertSame($amount, $ci->getIecAmount());


        $this->assertTrue($ci->addARCNo(\str_pad("A", 99, "A")));
        $this->assertSame(21, \strlen($ci->getArcNo()[$n]));

        $ci->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($ci->addARCNo(""));
        $this->assertSame("", $ci->getArcNo()[++$n]);
        $this->assertNotEmpty($ci->getErrorRegistor()->getOnSetValue());

        $wrong = -0.01;
        $ci->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($ci->setIecAmount($wrong));
        $this->assertSame($wrong, $ci->getIecAmount());
        $this->assertNotEmpty($ci->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return CustomsInformation
     */
    public function createCustomsInformation(): CustomsInformation
    {
        $ci   = new CustomsInformation(new ErrorRegister());
        $nMax = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $ci->addARCNo(\strval($n));
        }
        $ci->setIecAmount(49.95);
        return $ci;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $ci   = new CustomsInformation(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ci->createXmlNode($node);
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
        $ci   = new CustomsInformation(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $ci->parseXmlNode($node);
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
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
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

        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ci->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeNull(): void
    {
        $ci   = new CustomsInformation(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $ciNode = $ci->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $ciNode);

        $this->assertSame(
            CustomsInformation::N_CUSTOMSINFORMATION, $ciNode->getName()
        );

        $this->assertSame(
            0,
            $node->{CustomsInformation::N_CUSTOMSINFORMATION}
            ->{CustomsInformation::N_ARCNO}->count()
        );

        $this->assertSame(
            0,
            $node->{CustomsInformation::N_CUSTOMSINFORMATION}
            ->{CustomsInformation::N_IECAMOUNT}->count()
        );

        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ci->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXml(): void
    {
        $ci   = $this->createCustomsInformation();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ci->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new CustomsInformation(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        for ($n = 0; $n < \count($ci->getArcNo()); $n++) {
            $this->assertSame(
                $ci->getArcNo()[$n], $parsed->getArcNo()[$n]
            );
        }

        $this->assertSame($ci->getIecAmount(), $parsed->getIecAmount());

        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ci->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXmlEmpty(): void
    {
        $ci   = new CustomsInformation(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $ci->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new CustomsInformation(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $ci->getArcNo(), $parsed->getArcNo()
        );

        $this->assertSame(
            $ci->getIecAmount(), $parsed->getIecAmount()
        );

        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ci->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $ciNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $ci     = new CustomsInformation(new ErrorRegister());
        $xml    = $ci->createXmlNode($ciNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ci->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $ciNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $ci     = new CustomsInformation(new ErrorRegister());
        $ci->addARCNo("");
        $ci->setIecAmount(-1.0);

        $xml = $ci->createXmlNode($ciNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ci->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($ci->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ci->getErrorRegistor()->getLibXmlError());
    }
}
