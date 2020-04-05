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
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\Date\Date as RDate;

/**
 * Description of TaxTableEntryTest
 *
 * @author João Rebelo
 */
class TaxTableEntryTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
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
    public function testInstance()
    {
        $taxTabEnt = new TaxTableEntry();
        $this->assertInstanceOf(TaxTableEntry::class, $taxTabEnt);
        $this->assertNull($taxTabEnt->getTaxPercentage());
        $this->assertNull($taxTabEnt->getTaxAmount());
        $this->assertNull($taxTabEnt->getTaxExpirationDate());
    }

    /**
     *
     */
    public function testSestGetDescription()
    {
        $taxEntTab = new TaxTableEntry();

        try
        {
            $taxEntTab->getDescription();
            $this->fail("Get description without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $description = "description";
        $taxEntTab->setDescription($description);
        $this->assertEquals($description, $taxEntTab->getDescription());
        try
        {
            $taxEntTab->setDescription("");
            $this->fail("set description with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $taxEntTab->setDescription(null);
            $this->fail("Set Description shoul to null shuld throw TypeError");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetTaxCode()
    {
        $taxEntTab = new TaxTableEntry();

        try
        {
            $taxEntTab->getTaxCode();
            $this->fail("Get TaxCode without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCode = TaxCode::NOR;
        $taxEntTab->setTaxCode(new TaxCode($taxCode));
        $this->assertEquals($taxCode, $taxEntTab->getTaxCode()->get());

        $taxRegExp = "A999";
        $taxEntTab->setTaxCode(new TaxCode($taxRegExp));
        $this->assertEquals($taxRegExp, $taxEntTab->getTaxCode()->get());

        try
        {
            $taxEntTab->setTaxCode(null);
            $this->fail("Set TaxCode shoul to null shuld throw TypeError");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetTaxCountryRegion()
    {
        $taxEntTab = new TaxTableEntry();

        try
        {
            $taxEntTab->getTaxCountryRegion();
            $this->fail("Get TaxCountryRegion without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxCouReg = TaxCountryRegion::ISO_PT;
        $taxEntTab->setTaxCountryRegion(new TaxCountryRegion($taxCouReg));
        $this->assertEquals($taxCouReg, $taxEntTab->getTaxCountryRegion()->get());

        try
        {
            $taxEntTab->setTaxCountryRegion(null);
            $this->fail("Set TaxCountryRegion shoul to null shuld throw TypeError");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetTaxType()
    {
        $taxEntTab = new TaxTableEntry();

        try
        {
            $taxEntTab->getTaxType();
            $this->fail("Get TaxType without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxType = TaxType::IVA;
        $taxEntTab->setTaxType(new TaxType($taxType));
        $this->assertEquals($taxType, $taxEntTab->getTaxType()->get());

        try
        {
            $taxEntTab->setTaxType(null);
            $this->fail("Set TaxType shoul to null shuld throw TypeError");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetTaxExpirationDate()
    {
        $taxEntTab = new TaxTableEntry();

        $date = RDate::parse(RDate::SQL_DATE, "2019-10-05");

        $taxEntTab->setTaxExpirationDate($date);
        $this->assertEquals($date->format(RDate::SQL_DATE),
                                          $taxEntTab->getTaxExpirationDate()->format(RDate::SQL_DATE));

        $taxEntTab->setTaxExpirationDate(null);
        $this->assertNull($taxEntTab->getTaxExpirationDate());
    }

    /**
     *
     */
    public function testSestGetTaxPercentage()
    {
        $taxEntTab = new TaxTableEntry();

        $percent = 23.00;

        $taxEntTab->setTaxPercentage($percent);
        $this->assertEquals($percent, $taxEntTab->getTaxPercentage());

        try
        {
            $taxEntTab->setTaxAmount(999.00);
            $this->fail("Set Tax amount whene Tax percentage is setted should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $taxEntTab->setTaxPercentage(-23.00);
            $this->fail("Set Tax percentage to a negative number should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $taxEntTab->setTaxPercentage(null);
        $this->assertNull($taxEntTab->getTaxPercentage());
    }

    /**
     *
     */
    public function testSestGetTaxAmount()
    {
        $taxEntTab = new TaxTableEntry();

        $amount = 999.09;

        $taxEntTab->setTaxAmount($amount);
        $this->assertEquals($amount, $taxEntTab->getTaxAmount());

        try
        {
            $taxEntTab->setTaxPercentage(23.00);
            $this->fail("Set Tax percentage whene Tax amount is setted should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $taxEntTab->setTaxAmount(-230.99);
            $this->fail("Set a negative amount should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $taxEntTab->setTaxAmount(null);
        $this->assertNull($taxEntTab->getTaxAmount());
    }

    /**
     * Create and populate a TaxTableEntry to perform test
     * @return TaxTableEntry
     */
    public function createTaxTableEntry(): TaxTableEntry
    {
        $entry = new TaxTableEntry();
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
    public function changeTaxPercToAmount(TaxTableEntry $taxTableEntry)
    {
        $taxTableEntry->setTaxPercentage(null);
        $taxTableEntry->setTaxAmount(999.00);
    }

    /**
     * Set the Tax Table entry Nulables to null
     * @param TaxTableEntry $taxTableEntry
     */
    public function setNullTaxTableEntry(TaxTableEntry $taxTableEntry)
    {
        $taxTableEntry->setTaxExpirationDate(null);
    }

    public function testCreateXmlNode()
    {
        $node = new \SimpleXMLElement(
            "<" . MasterFiles::N_TAXTABLE . "></" . MasterFiles::N_TAXTABLE . ">"
        );

        $entry = $this->createTaxTableEntry();

        $this->assertInstanceOf(\SimpleXMLElement::class,
                                $entry->createXmlNode($node));

        $entryNode = $node->{TaxTableEntry::N_TAXTABLEENTRY};
        $this->assertEquals($entry->getDescription(),
                            (string) $entryNode->{TaxTableEntry::N_DESCRIPTION});
        $this->assertEquals($entry->getTaxCode()->get(),
                            (string) $entryNode->{TaxTableEntry::N_TAXCODE});
        $this->assertEquals($entry->getTaxCountryRegion()->get(),
                            (string) $entryNode->{TaxTableEntry::N_TAXCOUNTRYREGION});
        $this->assertEquals(
            $entry->getTaxExpirationDate()
                ->format(RDate::SQL_DATE),
                         (string) $entryNode->{TaxTableEntry::N_TAXEXPIRATIONDATE}
        );
        $this->assertEquals($entry->getTaxPercentage(),
                            (float) $entryNode->{TaxTableEntry::N_TAXPERCENTAGE});
        $this->assertEquals($entry->getTaxType()->get(),
                            (string) $entryNode->{TaxTableEntry::N_TAXTYPE});

        $this->assertEquals(0, $entryNode->{TaxTableEntry::N_TAXAMOUNT}->count());

        unset($entryNode);

        $nodeAmount = new \SimpleXMLElement(
            "<" . MasterFiles::N_TAXTABLE . "></" . MasterFiles::N_TAXTABLE . ">"
        );

        $this->changeTaxPercToAmount($entry);
        $entry->createXmlNode($nodeAmount);
        $this->assertEquals(0,
                            $nodeAmount->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXPERCENTAGE}->count());

        $this->assertEquals($entry->getTaxAmount(),
                            (float) $nodeAmount->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXAMOUNT});


        unset($nodeAmount);

        $nodeNull = new \SimpleXMLElement(
            "<" . MasterFiles::N_TAXTABLE . "></" . MasterFiles::N_TAXTABLE . ">"
        );

        $this->setNullTaxTableEntry($entry);
        $entry->createXmlNode($nodeNull);
        $this->assertEquals(0,
                            $nodeNull->{TaxTableEntry::N_TAXTABLEENTRY}->{TaxTableEntry::N_TAXEXPIRATIONDATE}->count());
    }

    public function testParseXmlNode()
    {
        $node = new \SimpleXMLElement(
            "<" . MasterFiles::N_TAXTABLE . "></" . MasterFiles::N_TAXTABLE . ">"
        );

        $entry = $this->createTaxTableEntry();

        $xml = $entry->createXmlNode($node)->asXML();

        $parsed = new TaxTableEntry();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($entry->getDescription(), $parsed->getDescription());
        $this->assertEquals($entry->getTaxCode()->get(),
                            $parsed->getTaxCode()->get());
        $this->assertEquals($entry->getTaxCountryRegion()->get(),
                            $parsed->getTaxCountryRegion()->get());
        $this->assertEquals($entry->getTaxExpirationDate()->format(RDate::SQL_DATE),
                                                                   $parsed->getTaxExpirationDate()->format(RDate::SQL_DATE));
        $this->assertEquals($entry->getTaxPercentage(),
                            $parsed->getTaxPercentage());
        $this->assertEquals($entry->getTaxType()->get(),
                            $parsed->getTaxType()->get());
        $this->assertNull($parsed->getTaxAmount());

        unset($parsed);

        $this->changeTaxPercToAmount($entry);
        $parsedAmount = new TaxTableEntry();
        $xmlAmount    = $entry->createXmlNode($node)->asXML();
        $parsedAmount->parseXmlNode(new \SimpleXMLElement($xmlAmount));
        $this->assertNull($parsedAmount->getTaxPercentage());
        $this->assertEquals($entry->getTaxAmount(),
                            $parsedAmount->getTaxAmount());

        unset($parsedAmount);

        $this->setNullTaxTableEntry($entry);
        $parsedNull = new TaxTableEntry();
        $xmlNull    = $entry->createXmlNode($node)->asXML();
        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));
        $this->assertNull($parsedNull->getTaxExpirationDate());
    }

}
