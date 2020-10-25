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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;

/**
 * Class SpecialRegimesTest
 *
 * @author João Rebelo
 */
class SpecialRegimesTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(SpecialRegimes::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceAndSetGet(): void
    {
        $speReg = new SpecialRegimes(new ErrorRegister());
        $this->assertFalse($speReg->getCashVATSchemeIndicator());
        $this->assertFalse($speReg->getSelfBillingIndicator());
        $this->assertFalse($speReg->getThirdPartiesBillingIndicator());

        $speReg->setCashVATSchemeIndicator(true);
        $this->assertTrue($speReg->getCashVATSchemeIndicator());
        $this->assertFalse($speReg->getSelfBillingIndicator());
        $this->assertFalse($speReg->getThirdPartiesBillingIndicator());

        $speReg->setSelfBillingIndicator(true);
        $this->assertTrue($speReg->getSelfBillingIndicator());
        $this->assertFalse($speReg->getThirdPartiesBillingIndicator());

        $speReg->setThirdPartiesBillingIndicator(true);
        $this->assertTrue($speReg->getThirdPartiesBillingIndicator());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $sepReg = new SpecialRegimes(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $sepReg->createXmlNode($node);
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
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $speReg = new SpecialRegimes(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $speReg->parseXmlNode($node);
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
     * @author João Rebelo
     */
    public function getTruesTable(): array
    {
        return array(
            array(
                true,
                true,
                true),
            array(
                true,
                true,
                false),
            array(
                true,
                false,
                true),
            array(
                true,
                false,
                false),
            array(
                false,
                true,
                true),
            array(
                false,
                true,
                false),
            array(
                false,
                false,
                true),
            array(
                false,
                false,
                false)
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        foreach ($this->getTruesTable() as $bool) {
            $specReg = new SpecialRegimes(new ErrorRegister());
            $specReg->setCashVATSchemeIndicator($bool[0]);
            $specReg->setSelfBillingIndicator($bool[1]);
            $specReg->setThirdPartiesBillingIndicator($bool[2]);

            $node       = new \SimpleXMLElement(
                "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
            );
            $speRegNode = $specReg->createXmlNode($node);
            $this->assertInstanceOf(\SimpleXMLElement::class, $speRegNode);
            $this->assertSame(
                SpecialRegimes::N_SPECIALREGIMES, $speRegNode->getName()
            );
            $this->assertSame(
                $specReg->getCashVATSchemeIndicator() ? "1" : "0",
                (string) $node->{SpecialRegimes::N_SPECIALREGIMES}->{SpecialRegimes::N_CASHVATSCHEMEINDICATOR}
            );
            $this->assertSame(
                $specReg->getSelfBillingIndicator() ? "1" : "0",
                (string) $node->{SpecialRegimes::N_SPECIALREGIMES}->{SpecialRegimes::N_SELFBILLINGINDICATOR}
            );
            $this->assertSame(
                $specReg->getThirdPartiesBillingIndicator() ? "1" : "0",
                (string) $node->{SpecialRegimes::N_SPECIALREGIMES}->{SpecialRegimes::N_THIRDPARTIESBILLINGINDICATOR}
            );
        }

        /* @phpstan-ignore-next-line */
        $this->assertEmpty($specReg->getErrorRegistor()->getLibXmlError());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($specReg->getErrorRegistor()->getOnCreateXmlNode());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($specReg->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        foreach ($this->getTruesTable() as $bool) {
            $node   = new \SimpleXMLElement(
                "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
            );
            $speReg = new SpecialRegimes(new ErrorRegister());
            $xml    = $speReg->createXmlNode($node)->asXML();
            if ($xml === false) {
                $this->fail("Fail to generate xml string");
                return;
            }

            $parsed = new SpecialRegimes(new ErrorRegister());
            $parsed->parseXmlNode(new \SimpleXMLElement($xml));

            $parsed->setCashVATSchemeIndicator($bool[0]);
            $parsed->setSelfBillingIndicator($bool[1]);
            $parsed->setThirdPartiesBillingIndicator($bool[2]);

            $this->assertSame($bool[0], $parsed->getCashVATSchemeIndicator());
            $this->assertSame($bool[1], $parsed->getSelfBillingIndicator());
            $this->assertSame(
                $bool[2],
                $parsed->getThirdPartiesBillingIndicator()
            );
        }

        /* @phpstan-ignore-next-line */
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        /* @phpstan-ignore-next-line */
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
    }
}