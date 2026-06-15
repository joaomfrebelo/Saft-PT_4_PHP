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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\Commune;

/**
 * Description of TaxTableEntryTest
 *
 * @author João Rebelo
 */
class TaxTableEntryTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(TaxTableEntry::class))->testReflection(TaxTableEntry::class);
    }

    /**
     *
     */
    #[Test]
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
     */
    #[Test]
    public function testSetGetDescription(): void
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
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxCode(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxCode();
            $this->fail("Get TaxCode without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCode = TaxCode::NOR;
        $taxEntTab->setTaxCode($taxCode);
        $this->assertEquals($taxCode, $taxEntTab->getTaxCode());
        $this->assertTrue($taxEntTab->issetTaxCode());

        $taxRegExp = "A999";
        $taxEntTab->setTaxCode($taxRegExp);
        $this->assertEquals($taxRegExp, $taxEntTab->getTaxCode());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxCountryRegion(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxCountryRegion();
            $this->fail("Get TaxCountryRegion without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCouReg = TaxCountryRegion::ISO_PT;
        $taxEntTab->setTaxCountryRegion($taxCouReg);
        $this->assertEquals($taxCouReg, $taxEntTab->getTaxCountryRegion());
        $this->assertTrue($taxEntTab->issetTaxCountryRegion());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxType(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        try {
            $taxEntTab->getTaxType();
            $this->fail("Get TaxType without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxType = TaxType::IVA;
        $taxEntTab->setTaxType($taxType);
        $this->assertEquals($taxType, $taxEntTab->getTaxType());
        $this->assertTrue($taxEntTab->issetTaxType());
    }

    /**
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxExpirationDate(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $date = RDate::parse(Pattern::SQL_DATE, "2019-10-05");

        $taxEntTab->setTaxExpirationDate($date);
        $this->assertEquals(
            $date->format(Pattern::SQL_DATE),
            $taxEntTab->getTaxExpirationDate()?->format(Pattern::SQL_DATE)
        );

        $taxEntTab->setTaxExpirationDate(null);
        $this->assertNull($taxEntTab->getTaxExpirationDate());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxPercentage(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $percent = new Decimal("23.00");

        $taxEntTab->setTaxPercentage($percent);
        $this->assertEquals($percent, $taxEntTab->getTaxPercentage());

        // false because Percentage was set
        $amount = new Decimal("999.00");
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxAmount($amount));
        $this->assertSame($amount, $taxEntTab->getTaxAmount());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $wrong = new Decimal("-23.00");
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxPercentage($wrong));
        $this->assertSame($wrong, $taxEntTab->getTaxPercentage());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $taxEntTab->setTaxPercentage(null);
        $this->assertNull($taxEntTab->getTaxPercentage());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetTaxAmount(): void
    {
        $taxEntTab = new TaxTableEntry(new ErrorRegister());

        $amount = new Decimal("999.09");

        $taxEntTab->setTaxAmount($amount);
        $this->assertEquals($amount, $taxEntTab->getTaxAmount());

        $percentage = new Decimal("23.00");
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxPercentage($percentage));
        $this->assertSame($percentage, $taxEntTab->getTaxPercentage());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $wrong = new Decimal("-230.99");
        $taxEntTab->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($taxEntTab->setTaxAmount($wrong));
        $this->assertSame($wrong, $taxEntTab->getTaxAmount());
        $this->assertNotEmpty($taxEntTab->getErrorRegistor()->getOnSetValue());

        $taxEntTab->setTaxAmount(null);
        $this->assertNull($taxEntTab->getTaxAmount());
    }

    /**
     * Create and populate a TaxTableEntry to perform test
     *
     * @return TaxTableEntry
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    public function createTaxTableEntry(): TaxTableEntry
    {
        $entry = new TaxTableEntry(new ErrorRegister());
        $entry->setDescription("IVA test");
        $entry->setTaxAmount(null);
        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $entry->setTaxExpirationDate(RDate::parse(Pattern::SQL_DATE, "2019-10-05"));
        $entry->setTaxPercentage(new Decimal("23.00"));
        $entry->setTaxType(TaxType::IVA);
        $entry->setTaxCode(TaxCode::NOR);
        return $entry;
    }

    /**
     * Change the Tax Table entry type of value from percentage to amount
     * @param TaxTableEntry $taxTableEntry
     */
    public function changeTaxPercentageToAmount(TaxTableEntry $taxTableEntry): void
    {
        $taxTableEntry->setTaxPercentage(null);
        $taxTableEntry->setTaxAmount(new Decimal("999.00"));
    }

    /**
     * Set the Tax Table entry Nullables to null
     *
     * @param TaxTableEntry $taxTableEntry
     */
    public function setNullTaxTableEntry(TaxTableEntry $taxTableEntry): void
    {
        $taxTableEntry->setTaxExpirationDate(null);
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
        );

        $entry = $this->createTaxTableEntry();

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $entry->createXmlNode($node)
        );

        $entryNode = $node->{TaxTableEntry::N_TAX_TABLE_ENTRY};

        $this->assertEquals(
            $entry->getDescription(),
            (string) $entryNode->{TaxTableEntry::N_DESCRIPTION}
        );

        $this->assertEquals(
            is_string($entry->getTaxCode()) ? $entry->getTaxCode() : $entry->getTaxCode()->value,
            (string) $entryNode->{TaxTableEntry::N_TAX_CODE}
        );

        $this->assertEquals(
            $entry->getTaxCountryRegion()->value,
            (string) $entryNode->{TaxTableEntry::N_TAX_COUNTRY_REGION}
        );

        $this->assertEquals(
            $entry->getTaxExpirationDate()?->format(Pattern::SQL_DATE),
            (string) $entryNode->{TaxTableEntry::N_TAX_EXPIRATION_DATE}
        );

        $this->assertEquals(
            $entry->getTaxPercentage(),
            new Decimal((string) $entryNode->{TaxTableEntry::N_TAX_PERCENTAGE})
        );

        $this->assertEquals(
            $entry->getTaxType()->value,
            (string) $entryNode->{TaxTableEntry::N_TAX_TYPE}
        );

        $this->assertEquals(0, $entryNode->{TaxTableEntry::N_TAX_AMOUNT}->count());

        unset($entryNode);

        $nodeAmount = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
        );

        $this->changeTaxPercentageToAmount($entry);

        $entry->createXmlNode($nodeAmount);
        $this->assertEquals(
            0,
            $nodeAmount->{TaxTableEntry::N_TAX_TABLE_ENTRY}->{TaxTableEntry::N_TAX_PERCENTAGE}->count()
        );

        $this->assertEquals(
            $entry->getTaxAmount(),
            new Decimal((string) $nodeAmount->{TaxTableEntry::N_TAX_TABLE_ENTRY}->{TaxTableEntry::N_TAX_AMOUNT})
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());

        unset($nodeAmount);

        $nodeNull = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
        );

        $this->setNullTaxTableEntry($entry);
        $entry->createXmlNode($nodeNull);
        $this->assertEquals(
            0,
            $nodeNull->{TaxTableEntry::N_TAX_TABLE_ENTRY}->{TaxTableEntry::N_TAX_EXPIRATION_DATE}->count()
        );

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
        );

        $entry = $this->createTaxTableEntry();

        $xml = $entry->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new TaxTableEntry(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals($entry->getDescription(), $parsed->getDescription());

        $this->assertEquals($entry->getTaxCode(), $parsed->getTaxCode());

        $this->assertEquals($entry->getTaxCountryRegion(), $parsed->getTaxCountryRegion());

        $this->assertEquals(
            $entry->getTaxExpirationDate()?->format(Pattern::SQL_DATE),
            $parsed->getTaxExpirationDate()?->format(Pattern::SQL_DATE)
        );

        $this->assertEquals($entry->getTaxPercentage(), $parsed->getTaxPercentage());

        $this->assertEquals($entry->getTaxType(), $parsed->getTaxType());

        $this->assertNull($parsed->getTaxAmount());

        $this->assertEmpty($entry->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($entry->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($entry->getErrorRegistor()->getOnSetValue());

        unset($parsed);

        $this->changeTaxPercentageToAmount($entry);
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
     */
    #[Test]
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
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testAutoGenerateDescription(): void
    {

        $entry = new TaxTableEntry(new ErrorRegister());
        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $entry->setTaxType(TaxType::IVA);

        $entry->setTaxCode(TaxCode::ISE);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::RED);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::INT);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::NOR);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT_MA);

        $entry->setTaxCode(TaxCode::ISE);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Região autónoma da Madeira", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::RED);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Região autónoma da Madeira", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::INT);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Região autónoma da Madeira", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::NOR);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Região autónoma da Madeira", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT_AC);

        $entry->setTaxCode(TaxCode::ISE);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Isento - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::RED);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Reduzido - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::INT);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Intermédio - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxCode(TaxCode::NOR);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Normal - Região autónoma dos Açores", $entry->getDescription()
        );

        $entry->setTaxType(TaxType::NS);

        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Não sujeição - Portugal continental", $entry->getDescription()
        );

        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT_MA);
        $entry->autoGenerateDescription();
        $this->assertSame(
            "Não sujeição - Região autónoma da Madeira",
            $entry->getDescription()
        );

        $entry->setTaxCountryRegion(TaxCountryRegion::ISO_PT_AC);
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<".MasterFiles::N_TAX_TABLE."></".MasterFiles::N_TAX_TABLE.">"
        );
        $entry        = new TaxTableEntry(new ErrorRegister());
        $entry->setDescription("");
        $entry->setTaxAmount(new Decimal("-0.99"));
        $entry->setTaxPercentage(new Decimal("120"));

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
