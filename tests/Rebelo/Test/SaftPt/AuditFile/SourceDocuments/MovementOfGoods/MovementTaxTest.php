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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use PHPUnit\Framework\TestCase;

/**
 * Class MovementTaxTest
 *
 * @author João Rebelo
 */
class MovementTaxTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(MovementTax::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $movTax = new MovementTax();
        $this->assertInstanceOf(MovementTax::class, $movTax);

        $type = MovementTaxType::IVA;
        $movTax->setTaxType(new MovementTaxType($type));
        $this->assertSame($type, $movTax->getTaxType()->get());

        $code = MovementTaxCode::NOR;
        $movTax->setTaxCode(new MovementTaxCode($code));
        $this->assertSame($code, $movTax->getTaxCode()->get());

        $country = TaxCountryRegion::ISO_PT;
        $movTax->setTaxCountryRegion(new TaxCountryRegion($country));
        $this->assertSame($country, $movTax->getTaxCountryRegion()->get());

        $percentage = 23.00;
        $movTax->setTaxPercentage($percentage);
        $this->assertSame($percentage, $movTax->getTaxPercentage());

        try {
            $movTax->setTaxPercentage(-0.04);
            $this->fail("Set TaxPercentage to a negative number must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $ci   = new MovementTax();
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
        $ci   = new MovementTax();
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
     * @return MovementTax
     */
    public function createMovementTax(): MovementTax
    {
        $movTax = new MovementTax();
        $type   = MovementTaxType::IVA;
        $movTax->setTaxType(new MovementTaxType($type));

        $code = MovementTaxCode::NOR;
        $movTax->setTaxCode(new MovementTaxCode($code));

        $country = TaxCountryRegion::ISO_PT;
        $movTax->setTaxCountryRegion(new TaxCountryRegion($country));

        $percentage = 23.00;
        $movTax->setTaxPercentage($percentage);

        return $movTax;
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $movTax = $this->createMovementTax();
        $node   = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );

        $moveNode = $movTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $moveNode);

        $this->assertSame(
            MovementTax::N_TAX, $moveNode->getName()
        );

        $this->assertSame(
            $movTax->getTaxPercentage(),
            (float) $node->{MovementTax::N_TAX}
            ->{MovementTax::N_TAXPERCENTAGE}
        );

        $this->assertSame(
            $movTax->getTaxCode()->get(),
            (string) $node->{MovementTax::N_TAX}
            ->{MovementTax::N_TAXCODE}
        );

        $this->assertSame(
            $movTax->getTaxCountryRegion()->get(),
            (string) $node->{MovementTax::N_TAX}
            ->{MovementTax::N_TAXCOUNTRYREGION}
        );

        $this->assertSame(
            $movTax->getTaxType()->get(),
            (string) $node->{MovementTax::N_TAX}
            ->{MovementTax::N_TAXTYPE}
        );
    }
}