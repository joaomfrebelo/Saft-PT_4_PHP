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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Tax::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $tax = new Tax();
        $this->assertInstanceOf(Tax::class, $tax);
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
     *
     */
    public function testSetGet()
    {
        $tax  = new Tax();
        $type = TaxType::IVA;
        $tax->setTaxType(new TaxType($type));
        $this->assertSame($type, $tax->getTaxType()->get());

        $code = TaxCode::NOR;
        $tax->setTaxCode(new TaxCode($code));
        $this->assertSame($code, $tax->getTaxCode()->get());

        $country = TaxCountryRegion::ISO_PT;
        $tax->setTaxCountryRegion(new TaxCountryRegion($country));
        $this->assertSame($country, $tax->getTaxCountryRegion()->get());

        $percent = 23.00;
        $tax->setTaxPercentage($percent);
        $this->assertSame($percent, $tax->getTaxPercentage());
        $tax->setTaxPercentage(null);
        $this->assertNull($tax->getTaxPercentage());

        $amount = 4.59;
        $tax->setTaxAmount($amount);
        $this->assertSame($amount, $tax->getTaxAmount());
        $tax->setTaxAmount(null);
        $this->assertNull($tax->getTaxAmount());

        $tax->setTaxPercentage($percent);
        try {
            $tax->setTaxAmount($amount);
            $this->fail("Setting TaxAmount with TaxPercent setted should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $tax->setTaxPercentage(null);
        $tax->setTaxAmount($amount);
        try {
            $tax->setTaxPercentage($percent);
            $this->fail("Setting TaxPercentage with TaxAmount setted should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     * @return Tax
     */
    public function createTax(): Tax
    {
        $tax = new Tax();
        $tax->setTaxCode(new TaxCode(TaxCode::NOR));
        $tax->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::ISO_PT));
        $tax->setTaxType(new TaxType(TaxType::IVA));
        $tax->setTaxPercentage(23.00);
        return $tax;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $tax  = new Tax();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $tax->createXmlNode($node);
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
        $psn  = new Tax();
        $node = new \SimpleXMLElement("<root></root>");
        try {
            $psn->parseXmlNode($node);
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
     *
     */
    public function testCreateXmlNodeAmount()
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

        $this->assertSame(0,
            $node->{Tax::N_TAX}->{Tax::N_TAXPERCENTAGE}->count());
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $tax  = $this->createTax();
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $tax->createXmlNode($node)->asXML();

        $parsed = new Tax();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($tax->getTaxAmount(), $parsed->getTaxAmount());
        $this->assertSame($tax->getTaxPercentage(), $parsed->getTaxPercentage());
        $this->assertSame($tax->getTaxCode()->get(),
            $parsed->getTaxCode()->get());
        $this->assertSame($tax->getTaxCountryRegion()->get(),
            $parsed->getTaxCountryRegion()->get());
        $this->assertSame($tax->getTaxType()->get(),
            $parsed->getTaxType()->get());
    }

    /**
     *
     */
    public function testeParseXmlAmount()
    {
        $tax  = $this->createTax();
        $tax->setTaxPercentage(null);
        $tax->setTaxAmount(4.59);
        $node = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml  = $tax->createXmlNode($node)->asXML();

        $parsed = new Tax();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($tax->getTaxAmount(), $parsed->getTaxAmount());
        $this->assertSame($tax->getTaxPercentage(), $parsed->getTaxPercentage());
        $this->assertSame($tax->getTaxCode()->get(),
            $parsed->getTaxCode()->get());
        $this->assertSame($tax->getTaxCountryRegion()->get(),
            $parsed->getTaxCountryRegion()->get());
        $this->assertSame($tax->getTaxType()->get(),
            $parsed->getTaxType()->get());
    }
}