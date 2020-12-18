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

namespace Rebelo\Test\SaftPt\AuditFile\MasterFile;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\Date\Date as RDate;

/**
 * Description of TaxTableEntryTest
 *
 * @author João Rebelo
 */
class TaxTableEntryTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry::class
            );
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance(): void
    {
        $taxTabEnt = new TaxTableEntry(new ErrorRegister());

        $this->assertFalse($taxTabEnt->issetDescription());
        $this->assertFalse($taxTabEnt->issetTaxCode());
        $this->assertFalse($taxTabEnt->issetTaxCountryRegion());
        $this->assertFalse($taxTabEnt->issetTaxType());

        $this->assertInstanceOf(TaxTableEntry::class, $taxTabEnt);
        $this->assertNull($taxTabEnt->getTaxPercentage());
        $this->assertNull($taxTabEnt->getTaxAmount());
        $this->assertNull($taxTabEnt->getTaxExpirationDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetDescription(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getDescription();
            $this->fail("Get description without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $description = "description";
        $this->assertTrue($taxEntTab->setDescription($description));
        $this->assertEquals($description, $taxEntTab->getDescription());
        $this->assertTrue($taxEntTab->issetDescription());

        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setDescription(""));
        $this->assertSame("", $taxEntTab->getDescription());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        try {
            $taxEntTab->setDescription(null);/** @phpstan-ignore-line */
            $this->fail("Set Description shoul to null shuld throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxCode(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxCode();
            $this->fail("Get TaxCode without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCode = TaxCode::NOR;
        $taxEntTab->setTaxCode(new TaxCode($taxCode));
        $this->assertEquals($taxCode, $taxEntTab->getTaxCode()->get());
        $this->assertTrue($taxEntTab->issetTaxCode());

        $taxRegExp = "A999";
        $taxEntTab->setTaxCode(new TaxCode($taxRegExp));
        $this->assertEquals($taxRegExp, $taxEntTab->getTaxCode()->get());

        try {
            $taxEntTab->setTaxCode(null);/** @phpstan-ignore-line */
            $this->fail("Set TaxCode shoul to null shuld throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxCountryRegion(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxCountryRegion();
            $this->fail("Get TaxCountryRegion without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCouReg = TaxCountryRegion::ISO_PT;
        $taxEntTab->setTaxCountryRegion(new TaxCountryRegion($taxCouReg));
        $this->assertEquals($taxCouReg, $taxEntTab->getTaxCountryRegion()->get());
        $this->assertTrue($taxEntTab->issetTaxCountryRegion());

        try {
            $taxEntTab->setTaxCountryRegion(null);/** @phpstan-ignore-line */
            $this->fail("Set TaxCountryRegion shoul to null shuld throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxType(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxType();
            $this->fail("Get TaxType without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxType = TaxType::IVA;
        $taxEntTab->setTaxType(new TaxType($taxType));
        $this->assertEquals($taxType, $taxEntTab->getTaxType()->get());
        $this->assertTrue($taxEntTab->issetTaxType());

        try {
            $taxEntTab->setTaxType(null);/** @phpstan-ignore-line */
            $this->fail("Set TaxType shoul to null shuld throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxExpirationDate(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $date = RDate::parse(RDate::SQL_DATE, "2019-10-05");

        $taxEntTab->setTaxExpirationDate($date);
        $this->assertEquals(
            $date->format(RDate::SQL_DATE),
            $taxEntTab->getTaxExpirationDate()->format(RDate::SQL_DATE)
        );

        $taxEntTab->setTaxExpirationDate(null);
        $this->assertNull($taxEntTab->getTaxExpirationDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxPercentage(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $percent = 23.00;

        $taxEntTab->setTaxPercentage($percent);
        $this->assertEquals($percent, $taxEntTab->getTaxPercentage());

        // false because Percentage was setted
        $amount = 999.00;
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxAmount($amount));
        $this->assertSame($amount, $taxEntTab->getTaxAmount());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $wrong = -23.00;
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxPercentage($wrong));
        $this->assertSame($wrong, $taxEntTab->getTaxPercentage());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $taxEntTab->setTaxPercentage(null);
        $this->assertNull($taxEntTab->getTaxPercentage());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetTaxAmount(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $amount = 999.09;

        $taxEntTab->setTaxAmount($amount);
        $this->assertEquals($amount, $taxEntTab->getTaxAmount());

        $percentage = 23.00;
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxPercentage($percentage));
        $this->assertSame($percentage, $taxEntTab->getTaxPercentage());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $wrong = -230.99;
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxAmount($wrong));
        $this->assertSame($wrong, $taxEntTab->getTaxAmount());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $taxEntTab->setTaxAmount(null);
        $this->assertNull($taxEntTab->getTaxAmount());
    }

    /**
     * Create and populate a TaxTableEntry to perform test
     * @return TaxTableEntry
     */
    public function createTaxTableEntry(): TaxTableEntry
    {
        $entry = new TaxTableEntry(new ErrorRegister());
        $entry->setDescription("IVA test");
        $entry->setTaxAmount(null);
        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::ISO_PT));
        $entry->setTaxExpirationDate(RDate::parse(RDate::SQL_DATE, "2019-10-05"));
        $entry->setTaxPercentage(23.00);
        $entry->setTaxType(new TaxType(TaxType::IVA));
        $entry->setTaxCode(new TaxCode(TaxCode::NOR));
        return $entry;
    }

    /**
     * Change the Tax Table entry type of value from percentage to amount
     * @param TaxTableEntry $taxTableEntry
     */
    public function changeTaxPercToAmount(TaxTableEntry $taxTableEntry): void
    {
        $taxTableEntry->setTaxPercentage(null);
        $taxTableEntry->setTaxAmount(999.00);
    }

    /**
     * Set the Tax Table entry Nulables to null
     * @param TaxTableEntry $taxTableEntry
     */
    public function setNullTaxTableEntry(TaxTableEntry $taxTableEntry): void
    {
        $taxTableEntry->setTaxExpirationDate(null);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );

        $entry = $this->createTaxTableEntry();

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $entry->createXmlNode($node)
        );

        $entryNode = $node->{TaxTableEntry::N_TAXTABLEENTRY};

        $this->assertEquals(
            $entry->getDescription(),
            (string) $entryNode->{TaxTableEntry::N_DESCRIPTION}
        );

        $this->assertEquals(
            $entry->getTaxCode()->get(),
            (string) $entryNode->{TaxTableEntry::N_TAXCODE}
        );

        $this->assertEquals(
            $entry->getTaxCountryRegion()->get(),
            (string) $entryNode->{TaxTableEntry::N_TAXCOUNTRYREGION}
        );

        $this->assertEquals(
            $entry->getTaxExpirationDate()
                ->format(RDate::SQL_DATE),
            (string) $entryNode->{TaxTableEntry::N_TAXEXPIRATIONDATE}
        );

        $this->assertEquals(
            $entry->getTaxPercentage(),
            (float) $entryNode->{TaxTableEntry::N_TAXPERCENTAGE}
        );

        $this->assertEquals(
            $entry->getTaxType()->get(),
            (string) $entryNode->{TaxTableEntry::N_TAXTYPE}
        );

        $this->assertEquals(0, $entryNode->{TaxTableEntry::N_TAXAMOUNT}->count());

        unset($entryNode);

        $nodeAmount = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );

        $this->changeTaxPercToAmount($entry);

        $entry->createXmlNode($nodeAmount);
        $this->assertEquals(
            0,
            $nodeAmount->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXPERCENTAGE}->count()
        );

        $this->assertEquals(
            $entry->getTaxAmount(),
            (float) $nodeAmount->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXAMOUNT}
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());

        unset($nodeAmount);

        $nodeNull = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );

        $this->setNullTaxTableEntry($entry);
        $entry->createXmlNode($nodeNull);
        $this->assertEquals(
            0,
            $nodeNull->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXEXPIRATIONDATE}->count()
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );

        $entry = $this->createTaxTableEntry();

        $xml = $entry->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new TaxTableEntry(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals($entry->getDescription(), $parsed->getDescription());

        $this->assertEquals(
            $entry->getTaxCode()->get(), $parsed->getTaxCode()->get()
        );

        $this->assertEquals(
            $entry->getTaxCountryRegion()->get(),
            $parsed->getTaxCountryRegion()->get()
        );

        $this->assertEquals(
            $entry->getTaxExpirationDate()->format(RDate::SQL_DATE),
            $parsed->getTaxExpirationDate()->format(RDate::SQL_DATE)
        );

        $this->assertEquals(
            $entry->getTaxPercentage(), $parsed->getTaxPercentage()
        );

        $this->assertEquals(
            $entry->getTaxType()->get(), $parsed->getTaxType()->get()
        );

        $this->assertNull($parsed->getTaxAmount());

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());

        unset($parsed);

        $this->changeTaxPercToAmount($entry);
        $parsedAmount = new TaxTableEntry(new ErrorRegister());
        $xmlAmount    = $entry->createXmlNode($node)->asXML();
        if ($xmlAmount === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsedAmount->parseXmlNode(new \SimpleXMLElement($xmlAmount));
        $this->assertNull($parsedAmount->getTaxPercentage());
        $this->assertEquals(
            $entry->getTaxAmount(),
            $parsedAmount->getTaxAmount()
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());

        unset($parsedAmount);

        $this->setNullTaxTableEntry($entry);
        $parsedNull = new TaxTableEntry(new ErrorRegister());
        $xmlNull    = $entry->createXmlNode($node)->asXML();
        if ($xmlNull === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));
        $this->assertNull($parsedNull->getTaxExpirationDate());

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $taxTableEntry = new TaxTableEntry(new ErrorRegister());
        $node          = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $taxTableEntry->createXmlNode($node);
            $this->fail(
                "Creat a xml node on a wrong node should throw "
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
        $taxTableEntry = new TaxTableEntry(new ErrorRegister());
        $node          = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $taxTableEntry->parseXmlNode($node);
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
     * @test
     */
    public function testAutoGenerateDescription(): void
    {

        $entry = new TaxTableEntry(new ErrorRegister());
        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::ISO_PT));
        $entry->setTaxType(new TaxType(TaxType::IVA));

        $entry->setTaxCode(new TaxCode(TaxCode::ISE));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::RED));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::INT));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::NOR));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::PT_MA));

        $entry->setTaxCode(new TaxCode(TaxCode::ISE));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Região autónoma da Madaeira", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::RED));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Região autónoma da Madaeira", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::INT));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Região autónoma da Madaeira", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::NOR));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Região autónoma da Madaeira", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::PT_AC));

        $entry->setTaxCode(new TaxCode(TaxCode::ISE));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::RED));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::INT));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(new TaxCode(TaxCode::NOR));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxType(new TaxType(TaxType::NS));

        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::ISO_PT));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Não sujeição - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::PT_MA));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Não sujeição - Região autónoma da Madaeira",
            $entry->getDescription()
        );

        $entry->setTaxCountryRegion(new TaxCountryRegion(TaxCountryRegion::PT_AC));
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Não sujeição - Região autónoma dos Açores",
            $entry->getDescription()
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );
        $entry        = new TaxTableEntry(new ErrorRegister());
        $xml          = $entry->createXmlNode($customerNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($entry->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<".MasterFiles::N_TAXTABLE."></".MasterFiles::N_TAXTABLE.">"
        );
        $entry        = new TaxTableEntry(new ErrorRegister());
        $entry->setDescription("");
        $entry->setTaxAmount(-0.99);
        $entry->setTaxPercentage(120);

        $xml = $entry->createXmlNode($supplierNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($entry->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
    }
}
