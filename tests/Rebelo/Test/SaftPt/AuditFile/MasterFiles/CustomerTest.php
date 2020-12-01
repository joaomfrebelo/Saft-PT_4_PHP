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
use Rebelo\SaftPt\AuditFile\MasterFiles\Customer;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\SaftPt\AuditFile\Address;
use Rebelo\SaftPt\AuditFile\Country;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class CustomerTest
 *
 * @author João Rebelo
 */
class CustomerTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\Customer::class
            );
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $customer = new Customer(new ErrorRegister());
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertNull($customer->getContact());
        $this->assertNull($customer->getEmail());
        $this->assertNull($customer->getFax());
        $this->assertNull($customer->getTelephone());
        $this->assertNull($customer->getWebsite());
        $this->assertEquals(array(), $customer->getShipToAddress());

        $this->assertFalse($customer->issetAccountID());
        $this->assertFalse($customer->issetBillingAddress());
        $this->assertFalse($customer->issetCompanyName());
        $this->assertFalse($customer->issetCustomerID());
        $this->assertFalse($customer->issetCustomerTaxID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetCustomerID(): void
    {
        $customer = new Customer(new ErrorRegister());

        try {
            $customer->getCustomerID();
            $this->fail("Get customer id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $customerId = "Contumer 1209";
        $this->assertTrue($customer->setCustomerID($customerId));
        $this->assertEquals($customerId, $customer->getCustomerID());

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setCustomerID(""));
        $this->assertSame("", $customer->getCustomerID());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        try {
            $customer->setCustomerID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $this->assertTrue($customer->issetCustomerID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetAccountID(): void
    {
        $customer = new Customer(new ErrorRegister());

        try {
            $customer->getAccountID();
            $this->fail("Get Account id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $accountId = "AccountID999";
        $this->assertTrue($customer->setAccountID($accountId));
        $this->assertEquals($accountId, $customer->getAccountID());
        $this->assertTrue($customer->setAccountID(Customer::DESCONHECIDO));
        $this->assertEquals(Customer::DESCONHECIDO, $customer->getAccountID());

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setAccountID(""));
        $this->assertSame("", $customer->getAccountID());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $wrong = str_pad("A", 32, "A");
        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setAccountID($wrong));
        $this->assertSame($wrong, $customer->getAccountID());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        try {
            $customer->setAccountID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $this->assertTrue($customer->issetAccountID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetCustomerTaxID(): void
    {
        $customer = new Customer(new ErrorRegister());

        try {
            $customer->getCustomerTaxID();
            $this->fail("Get Costomer tax id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $customerTaxId = "CustomerTaxID999";
        $this->assertTrue($customer->setCustomerTaxID($customerTaxId));
        $this->assertEquals($customerTaxId, $customer->getCustomerTaxID());

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setCustomerTaxID(""));
        $this->assertSame("", $customer->getCustomerTaxID());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $wrong = str_pad("A", 32, "A");
        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setCustomerTaxID($wrong));
        $this->assertSame($wrong, $customer->getCustomerTaxID());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        try {
            $customer->setCustomerTaxID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $this->assertTrue($customer->issetCustomerTaxID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCompanyName(): void
    {
        $customer = new Customer(new ErrorRegister());

        try {
            $customer->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $name = "CompanyName FACTURACAO";
        $this->assertTrue($customer->setCompanyName($name));
        $this->assertEquals($name, $customer->getCompanyName());
        $this->assertTrue($customer->setCompanyName(\str_pad("_", 109, "_")));
        $this->assertEquals(100, \strlen($customer->getCompanyName()));

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setCompanyName(""));
        $this->assertSame("", $customer->getCompanyName());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        try {
            $customer->setCompanyName(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $this->assertTrue($customer->issetCompanyName());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testContact(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertNull($customer->getContact());

        $name = "Contact name test";
        $this->assertTrue($customer->setContact($name));
        $this->assertEquals($name, $customer->getContact());
        $this->assertTrue($customer->setContact(\str_pad("_", 51, "_")));
        $this->assertEquals(50, \strlen($customer->getContact()));

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setContact(""));
        $this->assertSame("", $customer->getContact());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->setContact(null);
        $this->assertNull($customer->getContact());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testBillingAddress(): void
    {
        $customer = new Customer(new ErrorRegister());
        $this->assertInstanceOf(Address::class, $customer->getBillingAddress());

        $this->assertTrue($customer->issetBillingAddress());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipToAddress(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertEquals(array(), $customer->getShipToAddress());

        $this->assertInstanceOf(
            Address::class, $customer->addShipToAddress()
        );
        $this->assertInstanceOf(
            Address::class, $customer->addShipToAddress()
        );
        $this->assertInstanceOf(
            Address::class, $customer->addShipToAddress()
        );

        $this->assertCount(3, $customer->getShipToAddress());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTelephone(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertNull($customer->getTelephone());

        $telephone = "Telephone test";
        $this->assertTrue($customer->setTelephone($telephone));
        $this->assertEquals($telephone, $customer->getTelephone());

        $this->assertTrue($customer->setTelephone(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($customer->getTelephone()));

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setTelephone(""));
        $this->assertSame("", $customer->getTelephone());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->setTelephone(null);
        $this->assertNull($customer->getTelephone());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testFax(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertNull($customer->getFax());

        $fax = "Fax test";
        $this->assertTrue($customer->setFax($fax));
        $this->assertEquals($fax, $customer->getFax());

        $this->assertTrue($customer->setFax(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($customer->getFax()));

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setFax(""));
        $this->assertSame("", $customer->getFax());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->setFax(null);
        $this->assertNull($customer->getFax());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEmail(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertNull($customer->getEmail());

        $email = "email@email.pt";
        $customer->setEmail($email);
        $this->assertEquals($email, $customer->getEmail());

        $wrong = \str_pad($email, 255, "a", STR_PAD_LEFT);
        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setEmail($wrong));
        $this->assertSame($wrong, $customer->getEmail());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $wrong2 = "isNotEmail";
        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setEmail($wrong2));
        $this->assertSame($wrong2, $customer->getEmail());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setEmail(""));
        $this->assertSame("", $customer->getEmail());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->setEmail(null);
        $this->assertNull($customer->getEmail());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWebsite(): void
    {
        $customer = new Customer(new ErrorRegister());

        $this->assertNull($customer->getWebsite());

        $website = "http://saft.pt";
        $customer->setWebsite($website);
        $this->assertEquals($website, $customer->getWebsite());

        $wrong = \str_pad($website, 61, "a", STR_PAD_RIGHT);
        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setWebsite($wrong));
        $this->assertSame($wrong, $customer->getWebsite());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($customer->setWebsite(""));
        $this->assertSame("", $customer->getWebsite());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());

        $customer->setWebsite(null);
        $this->assertNull($customer->getWebsite());
    }

    /**
     * Create and populate a instance of Customer to be used in tests
     * @return Customer
     */
    public function createCustomer(): Customer
    {
        $customer = new Customer(new ErrorRegister());
        $address  = $customer->getBillingAddress();
        $address->setAddressDetail("Billing Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");
        $address->setCountry(new Country(Country::ISO_BR));

        $shToAdd = $customer->addShipToAddress();
        $shToAdd->setAddressDetail("Ship to address test");
        $shToAdd->setCity("Sintra");
        $shToAdd->setPostalCode("1999-999");
        $shToAdd->setRegion("Lisbon");
        $shToAdd->setCountry(new Country(Country::ISO_BR));

        $customer->setCustomerID("ID999999990");
        $customer->setAccountID("Account id test");
        $customer->setCustomerTaxID("599999990");
        $customer->setCompanyName("Customer name test");
        $customer->setContact("Customer contact neme");
        $customer->setTelephone("+351 987654321");
        $customer->setFax("123456789");
        $customer->setEmail("email@emil.pt");
        $customer->setWebsite("http://saft.pt");
        $customer->setSelfBillingIndicator(false);
        return $customer;
    }

    /**
     * Set the properties that can have nulll to null
     * @param Customer $customer
     */
    public function setNullsCustomer(Customer $customer): void
    {
        $customer->setContact(null);
        $customer->setTelephone(null);
        $customer->setFax(null);
        $customer->setEmail(null);
        $customer->setWebsite(null);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $customer = $this->createCustomer();

        $this->assertInstanceOf(
            \SimpleXMLElement::class, $customer->createXmlNode($node)
        );

        $customerNode = $node->{Customer::N_CUSTOMER};

        $this->assertEquals(
            $customer->getCustomerID(),
            (string) $customerNode->{Customer::N_CUSTOMERID}
        );

        $this->assertEquals(
            $customer->getAccountID(),
            (string) $customerNode->{Customer::N_ACCOUNTID}
        );

        $this->assertEquals(
            $customer->getCustomerTaxID(),
            (int) $customerNode->{Customer::N_CUSTOMERTAXID}
        );

        $this->assertEquals(
            $customer->getCompanyName(),
            (string) $customerNode->{Customer::N_COMPANYNAME}
        );

        $this->assertEquals(
            $customer->getContact(),
            (string) $customerNode->{Customer::N_CONTACT}
        );

        $this->assertEquals(
            $customer->getBillingAddress()->getAddressDetail(),
            (string) $customerNode
            ->{Customer::N_BILLINGADDRESS}->{Address::N_ADDRESSDETAIL}
        );

        $shToAddr = $customer->getShipToAddress();
        $this->assertEquals(
            $shToAddr[0]->getAddressDetail(),
            (string) $customerNode
            ->{Customer::N_SHIPTOADDRESS}->{Address::N_ADDRESSDETAIL}
        );

        $this->assertEquals(
            $customer->getTelephone(),
            (string) $customerNode->{Customer::N_TELEPHONE}
        );

        $this->assertEquals(
            $customer->getFax(), (string) $customerNode->{Customer::N_FAX}
        );

        $this->assertEquals(
            $customer->getEmail(), (string) $customerNode->{Customer::N_EMAIL}
        );

        $this->assertEquals(
            $customer->getWebsite(),
            (string) $customerNode->{Customer::N_WEBSITE}
        );

        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());

        $this->setNullsCustomer($customer);

        unset($node);
        $nodeNull = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $customer->createXmlNode($nodeNull);

        $customerNodeNull = $nodeNull->{Customer::N_CUSTOMER};

        $this->assertEquals(0, $customerNodeNull->{Customer::N_CONTACT}->count());
        $this->assertEquals(
            1, $customerNodeNull->{Customer::N_SHIPTOADDRESS}->count()
        );
        $this->assertEquals(
            0, $customerNodeNull->{Customer::N_TELEPHONE}->count()
        );
        $this->assertEquals(0, $customerNodeNull->{Customer::N_FAX}->count());
        $this->assertEquals(0, $customerNodeNull->{Customer::N_EMAIL}->count());
        $this->assertEquals(0, $customerNodeNull->{Customer::N_WEBSITE}->count());

        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $customer = $this->createCustomer();

        $xml = $customer->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed      = new Customer(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($customer->getCustomerID(), $parsed->getCustomerID());
        $this->assertEquals($customer->getAccountID(), $parsed->getAccountID());
        $this->assertEquals(
            $customer->getCompanyName(), $parsed->getCompanyName()
        );
        $this->assertEquals($customer->getContact(), $parsed->getContact());
        $this->assertEquals(
            $customer->getBillingAddress()->getAddressDetail(),
            $parsed->getBillingAddress()->getAddressDetail()
        );
        $cusShToAddr = $customer->getShipToAddress();
        $parShTAddr  = $parsed->getShipToAddress();
        $this->assertEquals(
            $cusShToAddr[0]->getAddressDetail(),
            $parShTAddr[0]->getAddressDetail()
        );
        $this->assertEquals($customer->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($customer->getFax(), $parsed->getFax());
        $this->assertEquals($customer->getEmail(), $parsed->getEmail());
        $this->assertEquals($customer->getWebsite(), $parsed->getWebsite());

        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());

        $shToAddrStack = $customer->getShipToAddress();
        
        unset($parsed);

        $this->setNullsCustomer($customer);
        $parsedNull = new Customer(new ErrorRegister());

        $xmlNull = $customer->createXmlNode($node)->asXML();
        if ($xmlNull === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));

        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());
    }

    /**
     * Reads all Customers from the Demo SAFT in Test\Ressources
     * and parse then to Customer class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }
        
        $customerStack = $saftDemoXml
            ->{MasterFiles::N_MASTERFILES}
            ->{Customer::N_CUSTOMER};

        if ($customerStack->count() === 0) {
            $this->fail("No Customers in XML");
        }

        for ($i = 0; $i < $customerStack->count(); $i++) {
            /* @var $customerXml \SimpleXMLElement */
            $customerXml = $customerStack[$i];

            $customer = new Customer(new ErrorRegister());
            $customer->parseXmlNode($customerXml);


            $xmlRootNode     = (new \Rebelo\SaftPt\AuditFile\AuditFile)->createRootElement();
            $masterFilesNode = $xmlRootNode->addChild(MasterFiles::N_MASTERFILES);

            $xml = $customer->createXmlNode($masterFilesNode);

            try {
                $assertXml = $this->xmlIsEqual($customerXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Customer '%s' with error '%s'",
                        $customerXml->{Customer::N_CUSTOMERID}, $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $customerXml->{Customer::N_CUSTOMERID}, $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $customer = new Customer(new ErrorRegister());
        $node     = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $customer->createXmlNode($node);
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
        $customer = new Customer(new ErrorRegister());
        $node     = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $customer->parseXmlNode($node);
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
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $customer     = new Customer(new ErrorRegister());
        $xml          = $customer->createXmlNode($customerNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customer->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $custNode = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $customer = new Customer(new ErrorRegister());
        $customer->setAccountID("");
        $customer->setCompanyName("");
        $customer->setContact("");
        $customer->setCustomerID("");
        $customer->setCustomerTaxID("");
        $customer->setEmail("----");
        $customer->setTelephone("");
        $customer->setFax("");
        $customer->setWebsite("");

        $xml = $customer->createXmlNode($custNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($customer->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($customer->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($customer->getErrorRegistor()->getLibXmlError());
    }
}