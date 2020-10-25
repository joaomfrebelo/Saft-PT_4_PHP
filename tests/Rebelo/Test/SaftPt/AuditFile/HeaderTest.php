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

namespace Rebelo\Test\SaftPt\AuditFile;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\Header;
use Rebelo\SaftPt\AuditFile\AddressPT;
use Rebelo\SaftPt\AuditFile\TaxAccountingBasis;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\Date\Date as RDate;

/**
 * Class HeaderTest
 *
 * @author João Rebelo
 */
class HeaderTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\Header::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $header = new Header(new ErrorRegister());
        $this->assertInstanceOf(Header::class, $header);
        $this->assertEquals("EUR", $header->getCurrencyCode());

        $this->assertFalse($header->issetCompanyAddress());
        $this->assertFalse($header->issetCompanyID());
        $this->assertFalse($header->issetCompanyName());
        $this->assertTrue($header->issetCurrencyCode());
        $this->assertTrue($header->issetDateCreated());
        $this->assertFalse($header->issetEndDate());
        $this->assertFalse($header->issetFiscalYear());
        $this->assertFalse($header->issetProductCompanyTaxID());
        $this->assertFalse($header->issetProductVersion());
        $this->assertFalse($header->issetSoftwareCertificateNumber());
        $this->assertFalse($header->issetStartDate());
        $this->assertFalse($header->issetTaxAccountingBasis());
        $this->assertFalse($header->issetTaxEntity());
        $this->assertFalse($header->issetTaxRegistrationNumber());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetAuditFileVersion(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertEquals("1.04_01", $header->getAuditFileVersion());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetCompanyID(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getCompanyID();
            $this->fail("Get company id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $companyId = "Conser 1209";
        $this->assertTrue($header->setCompanyID($companyId));
        $this->assertEquals($companyId, $header->getCompanyID());
        $this->assertTrue($header->issetCompanyID());

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setCompanyID(""));
        $this->assertEmpty($header->getCompanyID());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = "aaaaa";
        $this->assertFalse($header->setCompanyID($wrong));
        $this->assertSame($wrong, $header->getCompanyID());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setCompanyID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetTaxRegistrationNumber(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getTaxRegistrationNumber();
            $this->fail("Get tax registration number without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxRegNum = 999999990;
        $this->assertTrue(
            $header->setTaxRegistrationNumber($taxRegNum)
        );
        $this->assertEquals($taxRegNum, $header->getTaxRegistrationNumber());
        $this->assertTrue($header->issetTaxRegistrationNumber());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = 111222333;
        $this->assertFalse(
            $header->setTaxRegistrationNumber($wrong)
        );
        $this->assertSame($wrong, $header->getTaxRegistrationNumber());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setTaxRegistrationNumber(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTaxAccountingBasis(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getTaxAccountingBasis();
            $this->fail("Get TaxAccountingBasis without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $basis = TaxAccountingBasis::FACTURACAO;
        $header->setTaxAccountingBasis(new TaxAccountingBasis($basis));
        $this->assertEquals($basis, $header->getTaxAccountingBasis()->get());
        $this->assertTrue($header->issetTaxAccountingBasis());

        try {
            $header->setTaxAccountingBasis("F");/** @phpstan-ignore-line */
            $this->fail("set tax account basis id with string should throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
        try {
            $header->setTaxAccountingBasis(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCompanyName(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $name = "CompanyName FACTURACAO";
        $this->assertTrue($header->setCompanyName($name));
        $this->assertEquals($name, $header->getCompanyName());
        $this->assertTrue($header->issetCompanyName());
        $this->assertTrue($header->setCompanyName(\str_pad("_", 109, "_")));
        $this->assertEquals(100, \strlen($header->getCompanyName()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setCompanyName(""));
        $this->assertSame("", $header->getCompanyName());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setCompanyName(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testBusinessName(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getBusinessName());

        $name = "business name test";
        $this->assertTrue($header->setBusinessName($name));
        $this->assertEquals($name, $header->getBusinessName());
        $this->assertTrue($header->setBusinessName(\str_pad("_", 109, "_")));
        $this->assertEquals(60, \strlen($header->getBusinessName()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setBusinessName(""));
        $this->assertSame("", $header->getBusinessName());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setBusinessName(null);
        $this->assertNull($header->getBusinessName());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCompanyAddress(): void
    {
        $header = new Header(new ErrorRegister());
        $this->assertInstanceOf(AddressPT::class, $header->getCompanyAddress());
        $this->assertTrue($header->issetCompanyAddress());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testFiscalYear(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getFiscalYear();
            $this->fail("Get FiscalYear without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $year = 2020;
        $this->assertTrue($header->setFiscalYear($year));
        $this->assertEquals($year, $header->getFiscalYear());
        $this->assertTrue($header->issetFiscalYear());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = 1999;
        $this->assertFalse($header->setFiscalYear($wrong));
        $this->assertSame($wrong, $header->getFiscalYear());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong2 = \intval(\Date("Y")) + 2;
        $this->assertFalse($header->setFiscalYear($wrong2));
        $this->assertSame($wrong2, $header->getFiscalYear());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setFiscalYear(null);/** @phpstan-ignore-line */
            $this->fail("Set FiscalYear to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testStartDate(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getStartDate();
            $this->fail("Get StartDate without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $this->assertTrue($header->setStartDate($date));
        $this->assertTrue($header->issetStartDate());
        $this->assertEquals(
            $date->getTimestamp(), $header->getStartDate()->getTimestamp()
        );

        $header->getErrorRegistor()->cleaeAllErrors();
        $date->setDate(1999, 10, 05);
        $this->assertFalse($header->setStartDate($date));
        $this->assertSame("1999-10-05", $date->format(RDate::SQL_DATE));
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $date->setDate(\intval(\Date("Y") + 2), 10, 5);
        $this->assertFalse($header->setStartDate($date));
        $this->assertSame($date, $header->getStartDate());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setStartDate(null);/** @phpstan-ignore-line */
            $this->fail("Set StartDate to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEndDate(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getEndDate();
            $this->fail("Get EndDate without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $this->assertTrue($header->setEndDate($date));
        $this->assertTrue($header->issetEndDate());
        $this->assertEquals(
            $date->getTimestamp(), $header->getEndDate()->getTimestamp()
        );

        $header->getErrorRegistor()->cleaeAllErrors();
        $date->setDate(1999, 10, 05);
        $this->assertFalse($header->setEndDate($date));
        $this->assertSame($date, $header->getEndDate());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $date->setDate(\intval(\Date("Y") + 2), 10, 5);
        $this->assertFalse($header->setEndDate($date));
        $this->assertSame($date, $header->getEndDate());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setEndDate(null);/** @phpstan-ignore-line */
            $this->fail("Set EndDate to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDateCreated(): void
    {
        $header = new Header(new ErrorRegister());

        $now = new RDate();
        $this->assertEquals(
            $now->getTimestamp(), $header->getDateCreated()->getTimestamp()
        );

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $header->setDateCreated($date);
        $this->assertEquals(
            $date->getTimestamp(), $header->getDateCreated()->getTimestamp()
        );
        $this->assertTrue($header->issetDateCreated());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTaxEntity(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getTaxEntity();
            $this->fail("Get TaxEntity without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $taxEntity = "tax entity test";
        $this->assertTrue($header->setTaxEntity($taxEntity));
        $this->assertTrue($header->issetTaxEntity());
        $this->assertEquals($taxEntity, $header->getTaxEntity());

        $this->assertTrue($header->setTaxEntity(\str_pad("_", 109, "_")));
        $this->assertEquals(20, \strlen($header->getTaxEntity()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setTaxEntity(""));
        $this->assertSame("", $header->getTaxEntity());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setTaxEntity(null);/** @phpstan-ignore-line */
            $this->fail("Set TaxEntity to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testProductCompanyTaxID(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getProductCompanyTaxID();
            $this->fail("Get ProductCompanyTaxID without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $taxEntity = "ProductCompanyTaxID test";
        $this->assertTrue($header->setProductCompanyTaxID($taxEntity));
        $this->assertTrue($header->issetProductCompanyTaxID());
        $this->assertEquals($taxEntity, $header->getProductCompanyTaxID());

        $this->assertTrue($header->setProductCompanyTaxID(\str_pad("_", 300, "_")));
        $this->assertEquals(30, \strlen($header->getProductCompanyTaxID()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setProductCompanyTaxID(""));
        $this->assertSame("", $header->getProductCompanyTaxID());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setProductCompanyTaxID(null);/** @phpstan-ignore-line */
            $this->fail("Set ProductCompanyTaxID to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSoftwareCertificateNumber(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getSoftwareCertificateNumber();
            $this->fail("Get SoftwareCertificateNumber without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $number = 1999;
        $this->assertTrue($header->setSoftwareCertificateNumber($number));
        $this->assertEquals($number, $header->getSoftwareCertificateNumber());
        $this->assertTrue($header->issetSoftwareCertificateNumber());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = -1;
        $this->assertFalse($header->setSoftwareCertificateNumber($wrong));
        $this->assertSame($wrong, $header->getSoftwareCertificateNumber());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setSoftwareCertificateNumber(null);/** @phpstan-ignore-line */
            $this->fail("Set SoftwareCertificateNumber to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testProductID(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getProductID();
            $this->fail("Get ProductID without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $productId = "1999/1";
        $this->assertTrue($header->setProductID($productId));
        $this->assertEquals($productId, $header->getProductID());
        $this->assertTrue($header->issetProductID());

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setProductID(""));
        $this->assertSame("", $header->getProductID());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setProductID(null);/** @phpstan-ignore-line */
            $this->fail("Set ProductID to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testProductVersion(): void
    {
        $header = new Header(new ErrorRegister());

        try {
            $header->getProductVersion();
            $this->fail("Get ProductVersion without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $productVersion = "ProductVersion test";
        $this->assertTrue($header->setProductVersion($productVersion));
        $this->assertEquals($productVersion, $header->getProductVersion());
        $this->assertTrue($header->issetProductVersion());

        $this->assertTrue($header->setProductVersion(\str_pad("_", 300, "_")));
        $this->assertEquals(30, \strlen($header->getProductVersion()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setProductVersion(""));
        $this->assertSame("", $header->getProductVersion());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        try {
            $header->setProductVersion(null);/** @phpstan-ignore-line */
            $this->fail("Set ProductVersion to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testHeaderComment(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getHeaderComment());

        $headerComment = "HeaderComment test";
        $this->assertTrue($header->setHeaderComment($headerComment));
        $this->assertEquals($headerComment, $header->getHeaderComment());

        $this->assertTrue($header->setHeaderComment(\str_pad("_", 300, "_")));
        $this->assertEquals(255, \strlen($header->getHeaderComment()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setHeaderComment(""));
        $this->assertSame("", $header->getHeaderComment());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setHeaderComment(null);
        $this->assertNull($header->getHeaderComment());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTelephone(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getTelephone());

        $telephone = "Telephone test";
        $this->assertTrue($header->setTelephone($telephone));
        $this->assertEquals($telephone, $header->getTelephone());

        $this->assertTrue($header->setTelephone(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($header->getTelephone()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setTelephone(""));
        $this->assertSame("", $header->getTelephone());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setTelephone(null);
        $this->assertNull($header->getTelephone());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testFax(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getFax());

        $fax = "Fax test";
        $this->assertTrue($header->setFax($fax));
        $this->assertEquals($fax, $header->getFax());

        $this->assertTrue($header->setFax(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($header->getFax()));

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setFax(""));
        $this->assertSame("", $header->getFax());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setFax(null);
        $this->assertNull($header->getFax());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEmail(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getEmail());

        $email = "email@email.pt";
        $this->assertTrue($header->setEmail($email));
        $this->assertEquals($email, $header->getEmail());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = \str_pad($email, 255, "a", STR_PAD_LEFT);
        $this->assertFalse($header->setEmail($wrong));
        $this->assertSame($wrong, $header->getEmail());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong2 = "isNotEmail";
        $this->assertFalse($header->setEmail($wrong2));
        $this->assertSame($wrong2, $header->getEmail());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setEmail(""));
        $this->assertSame("", $header->getEmail());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setEmail(null);
        $this->assertNull($header->getEmail());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWebsite(): void
    {
        $header = new Header(new ErrorRegister());

        $this->assertNull($header->getWebsite());

        $website = "http://saft.pt";
        $this->assertTrue($header->setWebsite($website));
        $this->assertEquals($website, $header->getWebsite());

        $header->getErrorRegistor()->cleaeAllErrors();
        $wrong = \str_pad($website, 61, "a", STR_PAD_RIGHT);
        $this->assertFalse($header->setWebsite($wrong));
        $this->assertSame($wrong, $header->getWebsite());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($header->setWebsite(""));
        $this->assertSame("", $header->getWebsite());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());

        $header->setWebsite(null);
        $this->assertNull($header->getWebsite());
    }

    /**
     * Create and populate a instance of Header to be used in tests
     * @return Header
     */
    public function createHeader(): Header
    {
        $header = new Header(new ErrorRegister());

        $address = $header->getCompanyAddress();
        $address->setAddressDetail("Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");

        $now   = new RDate();
        $start = (clone $now)->setDay(1);
        $end   = (clone $now)->setDay(28);

        $header->setBusinessName("Business Name test");
        $header->setCompanyID("999999990");
        $header->setCompanyName("SAFT-PT 4 PHP");
        $header->setDateCreated($now);
        $header->setEmail("email@emil.pt");
        $header->setEndDate($end);
        $header->setFax("123456789");
        $header->setTelephone("+351 987654321");
        $header->setFiscalYear(\intval($now->format("Y")));
        $header->setHeaderComment("Header Comment");
        $header->setProductCompanyTaxID("599999999");
        $header->setProductID("0000/1");
        $header->setProductVersion("1.0.0");
        $header->setSoftwareCertificateNumber(0);
        $header->setStartDate($start);
        $header->setTaxAccountingBasis(new TaxAccountingBasis(TaxAccountingBasis::FACTURACAO));
        $header->setTaxEntity("999999990");
        $header->setTaxRegistrationNumber(999999990);
        $header->setWebsite("http://saft.pt");
        return $header;
    }

    /**
     * Set the properties that can have nulll to null
     * @param Header $header
     */
    public function setNullsHeader(Header $header): void
    {
        $header->setBusinessName(null);
        $header->setHeaderComment(null);
        $header->setTelephone(null);
        $header->setFax(null);
        $header->setEmail(null);
        $header->setWebsite(null);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $auditFile = new AuditFile();
        $node      = $auditFile->createRootElement();

        $header = $this->createHeader();

        $this->assertInstanceOf(
            \SimpleXMLElement::class, $header->createXmlNode($node)
        );

        $headerNode = $node->{Header::N_HEADER};

        $this->assertEquals(
            $header->getAuditFileVersion(),
            (string) $headerNode->{Header::N_AUDITFILEVERSION}
        );

        $this->assertEquals(
            $header->getCompanyID(),
            (string) $headerNode->{Header::N_COMPANYID}
        );

        $this->assertEquals(
            $header->getTaxRegistrationNumber(),
            (int) $headerNode->{Header::N_TAXREGISTRATIONNUMBER}
        );

        $this->assertEquals(
            $header->getTaxAccountingBasis()->get(),
            (string) $headerNode->{Header::N_TAXACCOUNTINGBASIS}
        );

        $this->assertEquals(
            $header->getCompanyName(),
            (string) $headerNode->{Header::N_COMPANYNAME}
        );

        $this->assertEquals(
            $header->getBusinessName(),
            (string) $headerNode->{Header::N_BUSINESSNAME}
        );

        $this->assertEquals(
            $header->getCompanyAddress()->getAddressDetail(),
            (string) $headerNode->{Header::N_COMPANYADDRESS}->{AddressPT::N_ADDRESSDETAIL}
        );

        $this->assertEquals(
            $header->getFiscalYear(), (int) $headerNode->{Header::N_FISCALYEAR}
        );

        $this->assertEquals(
            $header->getStartDate()->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_STARTDATE}
        );

        $this->assertEquals(
            $header->getEndDate()
                ->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_ENDDATE}
        );

        $this->assertEquals(
            $header->getDateCreated()
                ->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_DATECREATED}
        );

        $this->assertEquals(
            $header->getCurrencyCode(),
            (string) $headerNode->{Header::N_CURRENCYCODE}
        );

        $this->assertEquals(
            $header->getTaxEntity(),
            (string) $headerNode->{Header::N_TAXENTITY}
        );

        $this->assertEquals(
            $header->getProductCompanyTaxID(),
            (string) $headerNode->{Header::N_PRODUCTCOMPANYTAXID}
        );

        $this->assertEquals(
            $header->getSoftwareCertificateNumber(),
            (int) $headerNode->{Header::N_SOFTWARECERTIFICATENUMBER}
        );

        $this->assertEquals(
            $header->getProductID(),
            (string) $headerNode->{Header::N_PRODUCTID}
        );

        $this->assertEquals(
            $header->getProductVersion(),
            (string) $headerNode->{Header::N_PRODUCTVERSION}
        );
        $this->assertEquals(
            $header->getHeaderComment(),
            (string) $headerNode->{Header::N_HEADERCOMMENT}
        );

        $this->assertEquals(
            $header->getTelephone(),
            (string) $headerNode->{Header::N_TELEPHONE}
        );

        $this->assertEquals(
            $header->getFax(),
            (string) $headerNode->{Header::N_FAX}
        );

        $this->assertEquals(
            $header->getEmail(),
            (string) $headerNode->{Header::N_EMAIL}
        );

        $this->assertEquals(
            $header->getWebsite(),
            (string) $headerNode->{Header::N_WEBSITE}
        );

        $this->assertEmpty($header->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($header->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($header->getErrorRegistor()->getOnSetValue());

        $this->setNullsHeader($header);

        unset($node);

        $nodeNull       = $auditFile->createRootElement();
        $header->createXmlNode($nodeNull);
        $headerNodeNull = $nodeNull->{Header::N_HEADER};
        $this->assertEquals(
            0, $headerNodeNull->{Header::N_BUSINESSNAME}->count()
        );
        $this->assertEquals(
            0, $headerNodeNull->{Header::N_HEADERCOMMENT}->count()
        );
        $this->assertEquals(0, $headerNodeNull->{Header::N_TELEPHONE}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_FAX}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_EMAIL}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_WEBSITE}->count());

        $this->assertEmpty($header->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($header->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($header->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $auditFile = new AuditFile();
        $node      = $auditFile->createRootElement();
        $header = $this->createHeader();
        $xml    = $header->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
            return;
        }

        $parsed = new Header(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals(
            $header->getAuditFileVersion(), $parsed->getAuditFileVersion()
        );

        $this->assertEquals($header->getCompanyID(), $parsed->getCompanyID());

        $this->assertEquals(
            $header->getTaxRegistrationNumber(),
            $parsed->getTaxRegistrationNumber()
        );

        $this->assertEquals(
            $header->getTaxAccountingBasis()->get(),
            $parsed->getTaxAccountingBasis()->get()
        );

        $this->assertEquals($header->getCompanyName(), $parsed->getCompanyName());

        $this->assertEquals(
            $header->getBusinessName(), $parsed->getBusinessName()
        );
        $this->assertEquals(
            $header->getCompanyAddress()->getAddressDetail(),
            $parsed->getCompanyAddress()->getAddressDetail()
        );

        $this->assertEquals($header->getFiscalYear(), $parsed->getFiscalYear());

        $this->assertEquals(
            $header->getStartDate()->format(RDate::SQL_DATE),
            $parsed->getStartDate()->format(RDate::SQL_DATE)
        );

        $this->assertEquals(
            $header->getEndDate()->format(RDate::SQL_DATE),
            $parsed->getEndDate()->format(RDate::SQL_DATE)
        );

        $this->assertEquals(
            $header->getDateCreated()->format(RDate::SQL_DATE),
            $parsed->getDateCreated()->format(RDate::SQL_DATE)
        );

        $this->assertEquals(
            $header->getCurrencyCode(), $parsed->getCurrencyCode()
        );

        $this->assertEquals($header->getTaxEntity(), $parsed->getTaxEntity());

        $this->assertEquals(
            $header->getProductCompanyTaxID(), $parsed->getProductCompanyTaxID()
        );

        $this->assertEquals(
            $header->getSoftwareCertificateNumber(),
            $parsed->getSoftwareCertificateNumber()
        );

        $this->assertEquals($header->getProductID(), $parsed->getProductID());

        $this->assertEquals(
            $header->getProductVersion(), $parsed->getProductVersion()
        );

        $this->assertEquals(
            $header->getHeaderComment(), $parsed->getHeaderComment()
        );

        $this->assertEquals($header->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($header->getFax(), $parsed->getFax());
        $this->assertEquals($header->getEmail(), $parsed->getEmail());
        $this->assertEquals($header->getWebsite(), $parsed->getWebsite());

        $this->assertEmpty($header->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($header->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($header->getErrorRegistor()->getOnSetValue());

        unset($parsed);
        $this->setNullsHeader($header);
        $parsedNull = new Header(new ErrorRegister());

        $xmlToParse = $header->createXmlNode($node)->asXML();
        if ($xmlToParse === false) {
            $this->fail("Fail to get as xml string");
            return;
        }

        $parsedNull->parseXmlNode(
            new \SimpleXMLElement($xmlToParse)
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testClone(): void
    {
        $header = $this->createHeader();
        $clone  = clone $header;
        $clone->getCompanyAddress()->setAddressDetail("Clone street");
        $this->assertNotEquals(
            $clone->getCompanyAddress()->getAddressDetail(),
            $header->getCompanyAddress()->getAddressDetail()
        );
        $clone->getStartDate()->setYaer(1999);
        $this->assertNotEquals(
            $clone->getStartDate()->getTimestamp(),
            $header->getStartDate()->getTimestamp()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $auditFile = new AuditFile();
        $node      = $auditFile->createRootElement();
        $header = new Header(new ErrorRegister());
        $xml    = $header->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($header->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($header->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($header->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $auditFile = new AuditFile();
        $node      = $auditFile->createRootElement();
        $header = new Header(new ErrorRegister());
        $header->setBusinessName("");
        $header->setCompanyID("");
        $header->setCompanyName("");
        $header->setEmail("aaaa");
        $header->setEndDate((new RDate())->addYears(-9));
        $header->setStartDate((new RDate())->addYears(-9));
        $header->setFax("");
        $header->setFiscalYear(99);
        $header->setHeaderComment("");
        $header->setProductCompanyTaxID("----");
        $header->setProductID("---");
        $header->setProductVersion("****");
        $header->setSoftwareCertificateNumber(-99);
        $header->setTaxEntity("");
        $header->setTaxRegistrationNumber(-999);
        $header->setTelephone("");
        $header->setWebsite("");

        $xml = $header->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($header->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($header->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($header->getErrorRegistor()->getLibXmlError());
    }
}