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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;

/**
 * Class TaxTest
 *
 * @author João Rebelo
 */
class TaxTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Tax::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $tax = new Tax(new ErrorRegister());
        $this->assertInstanceOf(Tax::class, $tax);
        $this->assertFalse($tax->issetTaxCode());
        $this->assertFalse($tax->issetTaxCountryRegion());
        $this->assertFalse($tax->issetTaxType());
        $this->assertNull($tax->getTaxAmount());
        $this->assertNull($tax->getTaxPercentage());

        try {
            $tax->getTaxCode();
            $this->fail("Get TaxCode not initialized should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try {
            $tax->getTaxCountryRegion();
            $this->fail("Get TaxCountryRegion not initialized should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try {
            $tax->getTaxType();
            $this->fail("Get TaxType not initialized should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxType(): void
    {
        $tax  = new Tax(new ErrorRegister());
        $type = TaxType::IVA;
        $tax->setTaxType(new TaxType($type));
        $this->assertSame($type, $tax->getTaxType()->get());
        $this->assertTrue($tax->issetTaxType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxCode(): void
    {
        $tax  = new Tax(new ErrorRegister());
        $code = TaxCode::NOR;
        $tax->setTaxCode(new TaxCode($code));
        $this->assertSame($code, $tax->getTaxCode()->get());
        $this->assertTrue($tax->issetTaxCode());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxCountryRegion(): void
    {
        $tax     = new Tax(new ErrorRegister());
        $country = TaxCountryRegion::ISO_PT;
        $tax->setTaxCountryRegion(new TaxCountryRegion($country));
        $this->assertSame($country, $tax->getTaxCountryRegion()->get());
        $this->assertTrue($tax->issetTaxCountryRegion());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxPercentageAndAmount(): void
    {
        $tax     = new Tax(new ErrorRegister());
        $percent = 23.00;
        $this->assertTrue($tax->setTaxPercentage($percent));
        $this->assertSame($percent, $tax->getTaxPercentage());
        $this->assertTrue($tax->setTaxPercentage(null));
        $this->assertNull($tax->getTaxPercentage());

        $amount = 4.59;
        $this->assertTrue($tax->setTaxAmount($amount));
        $this->assertSame($amount, $tax->getTaxAmount());
        $this->assertTrue($tax->setTaxAmount(null));
        $this->assertNull($tax->getTaxAmount());

        $tax->setTaxPercentage($percent);

        $tax->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($tax->setTaxAmount($amount));
        $this->assertSame($amount, $tax->getTaxAmount());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());


        $tax->setTaxPercentage(null);
        $tax->setTaxAmount($amount);
        $tax->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($tax->setTaxPercentage($percent));
        $this->assertSame($percent, $tax->getTaxPercentage());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());

        $tax->setTaxPercentage(null);
        $tax->setTaxAmount(null);
        $negPer = -1.0;
        $tax->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($tax->setTaxPercentage($negPer));
        $this->assertSame($negPer, $tax->getTaxPercentage());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());
        $larPer = 110.0;
        $tax->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($tax->setTaxPercentage($larPer));
        $this->assertSame($larPer, $tax->getTaxPercentage());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());

        $tax->setTaxPercentage(null);
        $tax->setTaxAmount(null);
        $negAm = -1.0;
        $tax->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($tax->setTaxAmount($negAm));
        $this->assertSame($negPer, $tax->getTaxAmount());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return Tax
     */
    public function createTax(): Tax
    {
        $tax = new Tax(new ErrorRegister());
        $tax->setTaxCode(new TaxCode(TaxCode::NOR));
        $tax->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::ISO_PT));
        $tax->setTaxType(new TaxType(TaxType::IVA));
        $tax->setTaxPercentage(23.00);
        return $tax;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $tax  = new Tax(new ErrorRegister());
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $tax->createXmlNode($node);
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
        $psn  = new Tax(new ErrorRegister());
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
        $tax  = $this->createTax();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $taxNode = $tax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $taxNode);

        $this->assertSame(
            Tax::N_TAX, $taxNode->getName()
        );

        $this->assertSame(
            $tax->getTaxCode()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXCODE}
        );

        $this->assertSame(
            $tax->getTaxCountryRegion()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXCOUNTRYREGION}
        );

        $this->assertSame(
            $tax->getTaxType()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXTYPE}
        );

        $this->assertSame(
            $tax->getTaxPercentage(),
            (float) $node->{Tax::N_TAX}->{Tax::N_TAXPERCENTAGE}
        );

        $this->assertSame(0, $node->{Tax::N_TAX}->{Tax::N_TAXAMOUNT}->count());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeAmount(): void
    {
        $tax     = $this->createTax();
        $node    = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $tax->setTaxPercentage(null);
        $tax->setTaxAmount(4.59);
        $taxNode = $tax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $taxNode);

        $this->assertSame(
            Tax::N_TAX, $taxNode->getName()
        );

        $this->assertSame(
            $tax->getTaxCode()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXCODE}
        );

        $this->assertSame(
            $tax->getTaxCountryRegion()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXCOUNTRYREGION}
        );

        $this->assertSame(
            $tax->getTaxType()->get(),
            (string) $node->{Tax::N_TAX}->{Tax::N_TAXTYPE}
        );

        $this->assertSame(
            $tax->getTaxAmount(),
            (float) $node->{Tax::N_TAX}->{Tax::N_TAXAMOUNT}
        );

        $this->assertSame(
            0, $node->{Tax::N_TAX}->{Tax::N_TAXPERCENTAGE}->count()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $tax  = $this->createTax();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $tax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Tax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($tax->getTaxAmount(), $parsed->getTaxAmount());
        $this->assertSame($tax->getTaxPercentage(), $parsed->getTaxPercentage());

        $this->assertSame(
            $tax->getTaxCode()->get(), $parsed->getTaxCode()->get()
        );

        $this->assertSame(
            $tax->getTaxCountryRegion()->get(),
            $parsed->getTaxCountryRegion()->get()
        );

        $this->assertSame(
            $tax->getTaxType()->get(), $parsed->getTaxType()->get()
        );

        $this->assertEmpty($tax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($tax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($tax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlAmount(): void
    {
        $tax  = $this->createTax();
        $tax->setTaxPercentage(null);
        $tax->setTaxAmount(4.59);
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $tax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Tax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($tax->getTaxAmount(), $parsed->getTaxAmount());
        $this->assertSame($tax->getTaxPercentage(), $parsed->getTaxPercentage());

        $this->assertSame(
            $tax->getTaxCode()->get(), $parsed->getTaxCode()->get()
        );

        $this->assertSame(
            $tax->getTaxCountryRegion()->get(),
            $parsed->getTaxCountryRegion()->get()
        );

        $this->assertSame(
            $tax->getTaxType()->get(), $parsed->getTaxType()->get()
        );

        $this->assertEmpty($tax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($tax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($tax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $taxNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $tax     = new Tax(new ErrorRegister());
        $xml     = $tax->createXmlNode($taxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($tax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($tax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($tax->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $taxNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $tax     = new Tax(new ErrorRegister());
        $tax->setTaxAmount(-1.0);
        $tax->setTaxPercentage(-1.0);

        $xml = $tax->createXmlNode($taxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($tax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($tax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($tax->getErrorRegistor()->getLibXmlError());
    }
}
