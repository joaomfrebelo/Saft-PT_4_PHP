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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use PHPUnit\Framework\TestCase;
use Rebelo\Enum\EnumException;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class MovementTaxTest
 *
 * @author João Rebelo
 */
class MovementTaxTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(MovementTax::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $movTax = new MovementTax(new ErrorRegister());
        $this->assertInstanceOf(MovementTax::class, $movTax);
        $this->assertFalse($movTax->issetTaxCode());
        $this->assertFalse($movTax->issetTaxCountryRegion());
        $this->assertFalse($movTax->issetTaxPercentage());
        $this->assertFalse($movTax->issetTaxType());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxType(): void
    {
        $movTax = new MovementTax(new ErrorRegister());
        $type   = MovementTaxType::IVA;
        $movTax->setTaxType(new MovementTaxType($type));
        $this->assertSame($type, $movTax->getTaxType()->get());
        $this->assertTrue($movTax->issetTaxType());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxCode(): void
    {
        $movTax = new MovementTax(new ErrorRegister());
        $code   = MovementTaxCode::NOR;
        $movTax->setTaxCode(new MovementTaxCode($code));
        $this->assertSame($code, $movTax->getTaxCode()->get());
        $this->assertTrue($movTax->issetTaxCode());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxCountryRegion(): void
    {
        $movTax  = new MovementTax(new ErrorRegister());
        $country = TaxCountryRegion::ISO_PT;
        $movTax->setTaxCountryRegion(new TaxCountryRegion($country));
        $this->assertSame($country, $movTax->getTaxCountryRegion()->get());
        $this->assertTrue($movTax->issetTaxCountryRegion());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxPercentage(): void
    {
        $movTax     = new MovementTax(new ErrorRegister());
        $percentage = 23.00;
        $movTax->setTaxPercentage($percentage);
        $this->assertSame($percentage, $movTax->getTaxPercentage());
        $this->assertTrue($movTax->issetTaxPercentage());

        $wrong = -0.04;
        $this->assertFalse($movTax->setTaxPercentage(-0.04));
        $this->assertSame($wrong, $movTax->getTaxPercentage());
        $this->assertNotEmpty($movTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $ci   = new MovementTax(new ErrorRegister());
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
        $ci   = new MovementTax(new ErrorRegister());
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
     *
     * @return MovementTax
     * @throws EnumException
     */
    public function createMovementTax(): MovementTax
    {
        $movTax = new MovementTax(new ErrorRegister());
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
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
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

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $movTaxNode = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $movTax     = new MovementTax(new ErrorRegister());
        $xml        = $movTax->createXmlNode($movTaxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($movTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($movTax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($movTax->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $movTaxNode = new \SimpleXMLElement(
            "<".Line::N_LINE."></".Line::N_LINE.">"
        );
        $movTax     = new MovementTax(new ErrorRegister());
        $movTax->setTaxPercentage(-0.01);

        $xml = $movTax->createXmlNode($movTaxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($movTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($movTax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($movTax->getErrorRegistor()->getLibXmlError());
    }
}
