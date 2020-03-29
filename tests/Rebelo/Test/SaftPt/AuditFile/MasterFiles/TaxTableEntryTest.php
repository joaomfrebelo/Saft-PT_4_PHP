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

}
