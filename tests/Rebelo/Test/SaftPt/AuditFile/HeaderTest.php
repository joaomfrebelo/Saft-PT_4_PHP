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
use Rebelo\SaftPt\AuditFile\Header;
use Rebelo\SaftPt\AuditFile\AddressPT;
use Rebelo\SaftPt\AuditFile\PostalCodePT;
use Rebelo\SaftPt\AuditFile\TaxAccountingBasis;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;

/**
 * Class HeaderTest
 *
 * @author João Rebelo
 */
class HeaderTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\Header::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $header = new Header();
        $this->assertInstanceOf(Header::class, $header);
        $this->assertEquals("EUR", $header->getCurrencyCode());
    }

    /**
     *
     */
    public function testSetGetAuditFileVersion()
    {
        $header = new Header();

        $this->assertEquals("1.04_01", $header->getAuditFileVersion());
    }

    /**
     *
     */
    public function testSestGetCompanyID()
    {
        $header = new Header();

        try {
            $header->getCompanyID();
            $this->fail("Get company id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $companyId = "Conser 1209";
        $header->setCompanyID($companyId);
        $this->assertEquals($companyId, $header->getCompanyID());
        try {
            $header->setCompanyID("");
            $this->fail("set company id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $header->setCompanyID("aaaaa");
            $this->fail("set company id with wrong regexp string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $header->setCompanyID(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGetTaxRegistrationNumber()
    {
        $header = new Header();

        try {
            $header->getTaxRegistrationNumber();
            $this->fail("Get tax registration number without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $taxRegNum = 999999990;
        $header->setTaxRegistrationNumber($taxRegNum);
        $this->assertEquals($taxRegNum, $header->getTaxRegistrationNumber());
        try {
            $header->setTaxRegistrationNumber(111222333);
            $this->fail("set tax registration number with wrong number should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $header->setTaxRegistrationNumber(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testTaxAccountingBasis()
    {
        $header = new Header();

        try {
            $header->getTaxAccountingBasis();
            $this->fail("Get TaxAccountingBasis without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $basis = TaxAccountingBasis::FACTURACAO;
        $header->setTaxAccountingBasis(new TaxAccountingBasis($basis));
        $this->assertEquals($basis, $header->getTaxAccountingBasis()->get());
        try {
            $header->setTaxAccountingBasis("F");
            $this->fail("set tax account basis id with string should throw TypeError");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
        try {
            $header->setTaxAccountingBasis(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testCompanyName()
    {
        $header = new Header();

        try {
            $header->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $name = "CompanyName FACTURACAO";
        $header->setCompanyName($name);
        $this->assertEquals($name, $header->getCompanyName());
        $header->setCompanyName(\str_pad("_", 109, "_"));
        $this->assertEquals(100, \strlen($header->getCompanyName()));
        try {
            $header->setCompanyName("");
            $this->fail("set company name id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $header->setCompanyName(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testBusinessName()
    {
        $header = new Header();

        $this->assertNull($header->getBusinessName());

        $name = "business name test";
        $header->setBusinessName($name);
        $this->assertEquals($name, $header->getBusinessName());
        $header->setBusinessName(\str_pad("_", 109, "_"));
        $this->assertEquals(60, \strlen($header->getBusinessName()));
        try {
            $header->setBusinessName("");
            $this->fail("set business name with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $header->setBusinessName(null);
        $this->assertNull($header->getBusinessName());
    }

    /**
     *
     */
    public function testCompanyAddress()
    {
        $header = new Header();

        try {
            $header->getCompanyAddress();
            $this->fail("Get CompanyAddress without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $address = new AddressPT();
        $header->setCompanyAddress($address);
        $this->assertInstanceOf(AddressPT::class, $header->getCompanyAddress());

        try {
            $header->setCompanyAddress(null);
            $this->fail("Set CompanyAddress to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testFiscalYear()
    {
        $header = new Header();

        try {
            $header->getFiscalYear();
            $this->fail("Get FiscalYear without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $year = 2020;
        $header->setFiscalYear($year);
        $this->assertEquals($year, $header->getFiscalYear());

        try {
            $header->setFiscalYear(1999);
            $this->fail("set a fiscal year earlier than 2000 should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setFiscalYear(\intval(\Date("Y")) + 2);
            $this->fail("set a fiscal year older than now + 2Y should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setFiscalYear(null);
            $this->fail("Set FiscalYear to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testStartDate()
    {
        $header = new Header();

        try {
            $header->getStartDate();
            $this->fail("Get StartDate without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $header->setStartDate($date);
        $this->assertEquals($date->getTimestamp(),
            $header->getStartDate()->getTimestamp());

        try {
            $date->setDate(1999, 10, 05);
            $header->setStartDate($date);
            $this->fail("set a start date earlier than 2000 should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $date->setDate(\intval(\Date("Y") + 2), 10, 5);
            $header->setStartDate($date);
            $this->fail("set a start date older than now + 2Y should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setStartDate(null);
            $this->fail("Set StartDate to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testEndDate()
    {
        $header = new Header();

        try {
            $header->getEndDate();
            $this->fail("Get EndDate without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $header->setEndDate($date);
        $this->assertEquals($date->getTimestamp(),
            $header->getEndDate()->getTimestamp());

        try {
            $date->setDate(1999, 10, 05);
            $header->setEndDate($date);
            $this->fail("set a end date earlier than 2000 should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $date->setDate(\intval(\Date("Y") + 2), 10, 5);
            $header->setEndDate($date);
            $this->fail("set a end date older than now + 2Y should throw AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setEndDate(null);
            $this->fail("Set EndDate to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testDateCreated()
    {
        $header = new Header();

        $now = new RDate();
        $this->assertEquals($now->getTimestamp(),
            $header->getDateCreated()->getTimestamp());

        $date = new RDate();
        $date->setDate(2020, 10, 20);
        $header->setDateCreated($date);
        $this->assertEquals($date->getTimestamp(),
            $header->getDateCreated()->getTimestamp());
    }

    /**
     *
     */
    public function testTaxEntity()
    {
        $header = new Header();

        try {
            $header->getTaxEntity();
            $this->fail("Get TaxEntity without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $taxEntity = "tax entity test";
        $header->setTaxEntity($taxEntity);
        $this->assertEquals($taxEntity, $header->getTaxEntity());

        $header->setTaxEntity(\str_pad("_", 109, "_"));
        $this->assertEquals(20, \strlen($header->getTaxEntity()));

        try {
            $header->setTaxEntity("");
            $this->fail("set tax entity with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setTaxEntity(null);
            $this->fail("Set TaxEntity to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testProductCompanyTaxID()
    {
        $header = new Header();

        try {
            $header->getProductCompanyTaxID();
            $this->fail("Get ProductCompanyTaxID without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $taxEntity = "ProductCompanyTaxID test";
        $header->setProductCompanyTaxID($taxEntity);
        $this->assertEquals($taxEntity, $header->getProductCompanyTaxID());

        $header->setProductCompanyTaxID(\str_pad("_", 300, "_"));
        $this->assertEquals(30, \strlen($header->getProductCompanyTaxID()));

        try {
            $header->setProductCompanyTaxID("");
            $this->fail("set ProductCompanyTaxID with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setProductCompanyTaxID(null);
            $this->fail("Set ProductCompanyTaxID to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSoftwareCertificateNumber()
    {
        $header = new Header();

        try {
            $header->getSoftwareCertificateNumber();
            $this->fail("Get SoftwareCertificateNumber without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $number = 1999;
        $header->setSoftwareCertificateNumber($number);
        $this->assertEquals($number, $header->getSoftwareCertificateNumber());

        try {
            $header->setSoftwareCertificateNumber(-1);
            $this->fail("set SoftwareCertificateNumber less than zero should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setSoftwareCertificateNumber(null);
            $this->fail("Set SoftwareCertificateNumber to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testProductID()
    {
        $header = new Header();

        try {
            $header->getProductID();
            $this->fail("Get ProductID without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $productId = "1999/1";
        $header->setProductID($productId);
        $this->assertEquals($productId, $header->getProductID());

        try {
            $header->setProductID("");
            $this->fail("set ProductID with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setProductID(null);
            $this->fail("Set ProductID to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testProductVersion()
    {
        $header = new Header();

        try {
            $header->getProductVersion();
            $this->fail("Get ProductVersion without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $productVersion = "ProductVersion test";
        $header->setProductVersion($productVersion);
        $this->assertEquals($productVersion, $header->getProductVersion());

        $header->setProductVersion(\str_pad("_", 300, "_"));
        $this->assertEquals(30, \strlen($header->getProductVersion()));

        try {
            $header->setProductVersion("");
            $this->fail("set ProductVersion with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setProductVersion(null);
            $this->fail("Set ProductVersion to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testHeaderComment()
    {
        $header = new Header();

        $this->assertNull($header->getHeaderComment());

        $headerComment = "HeaderComment test";
        $header->setHeaderComment($headerComment);
        $this->assertEquals($headerComment, $header->getHeaderComment());

        $header->setHeaderComment(\str_pad("_", 300, "_"));
        $this->assertEquals(255, \strlen($header->getHeaderComment()));

        try {
            $header->setHeaderComment("");
            $this->fail("set HeaderComment with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $header->setHeaderComment(null);
        $this->assertNull($header->getHeaderComment());
    }

    /**
     *
     */
    public function testTelephone()
    {
        $header = new Header();

        $this->assertNull($header->getTelephone());

        $telephone = "Telephone test";
        $header->setTelephone($telephone);
        $this->assertEquals($telephone, $header->getTelephone());

        $header->setTelephone(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($header->getTelephone()));

        try {
            $header->setTelephone("");
            $this->fail("set Telephone with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $header->setTelephone(null);
        $this->assertNull($header->getTelephone());
    }

    /**
     *
     */
    public function testFax()
    {
        $header = new Header();

        $this->assertNull($header->getFax());

        $fax = "Fax test";
        $header->setFax($fax);
        $this->assertEquals($fax, $header->getFax());

        $header->setFax(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($header->getFax()));

        try {
            $header->setFax("");
            $this->fail("set fax with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $header->setFax(null);
        $this->assertNull($header->getFax());
    }

    /**
     *
     */
    public function testEmail()
    {
        $header = new Header();

        $this->assertNull($header->getEmail());

        $email = "email@email.pt";
        $header->setEmail($email);
        $this->assertEquals($email, $header->getEmail());

        try {
            $header->setEmail(\str_pad($email, 255, "a", STR_PAD_LEFT));
            $this->fail("set Email with length > 254 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setEmail("isNotEmail");
            $this->fail("set Email with wrong string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setEmail("");
            $this->fail("set Email with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $header->setEmail(null);
        $this->assertNull($header->getEmail());
    }

    /**
     *
     */
    public function testWebsite()
    {
        $header = new Header();

        $this->assertNull($header->getWebsite());

        $website = "http://saft.pt";
        $header->setWebsite($website);
        $this->assertEquals($website, $header->getWebsite());

        try {
            $header->setWebsite(\str_pad($website, 61, "a", STR_PAD_RIGHT));
            $this->fail("set Website with length > 60 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $header->setWebsite("");
            $this->fail("set Website with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $header->setWebsite(null);
        $this->assertNull($header->getWebsite());
    }

    /**
     * Create and populate a instance of Header to be used in tests
     * @return Header
     */
    public function createHeader(): Header
    {
        $address = new AddressPT();
        $address->setAddressDetail("Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode(new PostalCodePT("1999-999"));
        $address->setRegion("Lisbon");

        $now    = new RDate();
        $start  = (clone $now)->setDay(1);
        $end    = (clone $now)->setDay(28);
        $header = new Header();
        $header->setBusinessName("Business Name test");
        $header->setCompanyAddress($address);
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
    public function setNullsHeader(Header $header)
    {
        $header->setBusinessName(null);
        $header->setHeaderComment(null);
        $header->setTelephone(null);
        $header->setFax(null);
        $header->setEmail(null);
        $header->setWebsite(null);
    }

    public function testCreateXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $header = $this->createHeader();

        $this->assertInstanceOf(\SimpleXMLElement::class,
            $header->createXmlNode($node));
        $headerNode = $node->{Header::N_HEADER};
        $this->assertEquals($header->getAuditFileVersion(),
            (string) $headerNode->{Header::N_AUDITFILEVERSION}
        );
        $this->assertEquals($header->getCompanyID(),
            (string) $headerNode->{Header::N_COMPANYID}
        );
        $this->assertEquals($header->getTaxRegistrationNumber(),
            (int) $headerNode->{Header::N_TAXREGISTRATIONNUMBER}
        );
        $this->assertEquals($header->getTaxAccountingBasis()->get(),
            (string) $headerNode->{Header::N_TAXACCOUNTINGBASIS}
        );
        $this->assertEquals($header->getCompanyName(),
            (string) $headerNode->{Header::N_COMPANYNAME}
        );
        $this->assertEquals($header->getBusinessName(),
            (string) $headerNode->{Header::N_BUSINESSNAME}
        );
        $this->assertEquals($header->getCompanyAddress()->getAddressDetail(),
            (string) $headerNode
            ->{Header::N_COMPANYADDRESS}
            ->{AddressPT::N_ADDRESSDETAIL}
        );
        $this->assertEquals($header->getFiscalYear(),
            (int) $headerNode->{Header::N_FISCALYEAR});
        $this->assertEquals($header->getStartDate()
                ->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_STARTDATE}
        );
        $this->assertEquals($header->getEndDate()
                ->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_ENDDATE}
        );
        $this->assertEquals($header->getDateCreated()
                ->format(RDate::SQL_DATE),
            (string) $headerNode->{Header::N_DATECREATED}
        );
        $this->assertEquals($header->getCurrencyCode(),
            (string) $headerNode->{Header::N_CURRENCYCODE}
        );
        $this->assertEquals($header->getTaxEntity(),
            (string) $headerNode->{Header::N_TAXENTITY}
        );
        $this->assertEquals($header->getProductCompanyTaxID(),
            (string) $headerNode->{Header::N_PRODUCTCOMPANYTAXID}
        );
        $this->assertEquals($header->getSoftwareCertificateNumber(),
            (int) $headerNode->{Header::N_SOFTWARECERTIFICATENUMBER}
        );
        $this->assertEquals($header->getProductID(),
            (string) $headerNode->{Header::N_PRODUCTID}
        );
        $this->assertEquals($header->getProductVersion(),
            (string) $headerNode->{Header::N_PRODUCTVERSION}
        );
        $this->assertEquals($header->getHeaderComment(),
            (string) $headerNode->{Header::N_HEADERCOMMENT}
        );
        $this->assertEquals($header->getTelephone(),
            (string) $headerNode->{Header::N_TELEPHONE}
        );
        $this->assertEquals($header->getFax(),
            (string) $headerNode->{Header::N_FAX}
        );
        $this->assertEquals($header->getEmail(),
            (string) $headerNode->{Header::N_EMAIL}
        );
        $this->assertEquals($header->getWebsite(),
            (string) $headerNode->{Header::N_WEBSITE}
        );

        $this->setNullsHeader($header);

        unset($node);
        $nodeNull       = new \SimpleXMLElement("<root></root>");
        $header->createXmlNode($nodeNull);
        $headerNodeNull = $nodeNull->{Header::N_HEADER};
        $this->assertEquals(0,
            $headerNodeNull->{Header::N_BUSINESSNAME}->count());
        $this->assertEquals(0,
            $headerNodeNull->{Header::N_HEADERCOMMENT}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_TELEPHONE}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_FAX}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_EMAIL}->count());
        $this->assertEquals(0, $headerNodeNull->{Header::N_WEBSITE}->count());
    }

    public function testParseXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $header = $this->createHeader();
        $xml    = $header->createXmlNode($node)->asXML();

        $parsed = new Header();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($header->getAuditFileVersion(),
            $parsed->getAuditFileVersion());
        $this->assertEquals($header->getCompanyID(), $parsed->getCompanyID());
        $this->assertEquals($header->getTaxRegistrationNumber(),
            $parsed->getTaxRegistrationNumber());
        $this->assertEquals($header->getTaxAccountingBasis()->get(),
            $parsed->getTaxAccountingBasis()->get());
        $this->assertEquals($header->getCompanyName(), $parsed->getCompanyName());
        $this->assertEquals($header->getBusinessName(),
            $parsed->getBusinessName());
        $this->assertEquals($header->getCompanyAddress()->getAddressDetail(),
            $parsed->getCompanyAddress()->getAddressDetail());
        $this->assertEquals($header->getFiscalYear(), $parsed->getFiscalYear());
        $this->assertEquals($header->getStartDate()
                ->format(RDate::SQL_DATE),
            $parsed->getStartDate()->format(RDate::SQL_DATE));
        $this->assertEquals($header->getEndDate()
                ->format(RDate::SQL_DATE),
            $parsed->getEndDate()->format(RDate::SQL_DATE));
        $this->assertEquals($header->getDateCreated()
                ->format(RDate::SQL_DATE),
            $parsed->getDateCreated()->format(RDate::SQL_DATE));
        $this->assertEquals($header->getCurrencyCode(),
            $parsed->getCurrencyCode());
        $this->assertEquals($header->getTaxEntity(), $parsed->getTaxEntity());
        $this->assertEquals($header->getProductCompanyTaxID(),
            $parsed->getProductCompanyTaxID());
        $this->assertEquals($header->getSoftwareCertificateNumber(),
            $parsed->getSoftwareCertificateNumber());
        $this->assertEquals($header->getProductID(), $parsed->getProductID());
        $this->assertEquals($header->getProductVersion(),
            $parsed->getProductVersion());
        $this->assertEquals($header->getHeaderComment(),
            $parsed->getHeaderComment());
        $this->assertEquals($header->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($header->getFax(), $parsed->getFax());
        $this->assertEquals($header->getEmail(), $parsed->getEmail());
        $this->assertEquals($header->getWebsite(), $parsed->getWebsite());

        unset($parsed);
        $this->setNullsHeader($header);
        $parsedNull = new Header();
        $parsedNull->parseXmlNode(new \SimpleXMLElement(
                $header->createXmlNode($node)->asXML()
        ));
    }

    public function testClone()
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
}