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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\Country;

/**
 * Class CustomerTest
 *
 * @author João Rebelo
 */
class CustomerTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\Customer::class
        );
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $customer = new Customer();
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertNull($customer->getContact());
        $this->assertNull($customer->getEmail());
        $this->assertNull($customer->getFax());
        $this->assertNull($customer->getTelephone());
        $this->assertNull($customer->getWebsite());
        $this->assertEquals(array(), $customer->getShipToAddress());
    }

    /**
     *
     */
    public function testSestGetCustomerID()
    {
        $customer = new Customer();

        try {
            $customer->getCustomerID();
            $this->fail("Get customer id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $customerId = "Contumer 1209";
        $customer->setCustomerID($customerId);
        $this->assertEquals($customerId, $customer->getCustomerID());
        try {
            $customer->setCustomerID("");
            $this->fail("set customer id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setCustomerID(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetAccountID()
    {
        $customer = new Customer();

        try {
            $customer->getAccountID();
            $this->fail("Get Account id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $accountId = "AccountID999";
        $customer->setAccountID($accountId);
        $this->assertEquals($accountId, $customer->getAccountID());
        $customer->setAccountID("Desconhecido");
        $this->assertEquals("Desconhecido", $customer->getAccountID());
        try {
            $customer->setAccountID("");
            $this->fail("set account id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setAccountID(str_pad("A", 32, "A"));
            $this->fail("set account id with length greater then 30 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setAccountID(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetCustomerTaxID()
    {
        $customer = new Customer();

        try {
            $customer->getCustomerTaxID();
            $this->fail("Get Costomer tax id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $customerTaxId = "CustomerTaxID999";
        $customer->setCustomerTaxID($customerTaxId);
        $this->assertEquals($customerTaxId, $customer->getCustomerTaxID());
        try {
            $customer->setCustomerTaxID("");
            $this->fail("set customer tax id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setCustomerTaxID(str_pad("A", 32, "A"));
            $this->fail("set customer tax id with length greater then 30 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setCustomerTaxID(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testCompanyName()
    {
        $customer = new Customer();

        try {
            $customer->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $name = "CompanyName FACTURACAO";
        $customer->setCompanyName($name);
        $this->assertEquals($name, $customer->getCompanyName());
        $customer->setCompanyName(\str_pad("_", 109, "_"));
        $this->assertEquals(100, \strlen($customer->getCompanyName()));
        try {
            $customer->setCompanyName("");
            $this->fail("set company name id with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $customer->setCompanyName(null);
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testContact()
    {
        $customer = new Customer();

        $this->assertNull($customer->getContact());

        $name = "Contact name test";
        $customer->setContact($name);
        $this->assertEquals($name, $customer->getContact());
        $customer->setContact(\str_pad("_", 51, "_"));
        $this->assertEquals(50, \strlen($customer->getContact()));
        try {
            $customer->setContact("");
            $this->fail("set contact name with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $customer->setContact(null);
        $this->assertNull($customer->getContact());
    }

    /**
     *
     */
    public function testBillingAddress()
    {
        $customer = new Customer();

        try {
            $customer->getBillingAddress();
            $this->fail("Get BillingAddress without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $address = new Address();
        $customer->setBillingAddress($address);
        $this->assertInstanceOf(Address::class, $customer->getBillingAddress());

        try {
            $customer->setBillingAddress(null);
            $this->fail("Set BillingAddress to null should throw TypeError");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testShipToAddress()
    {
        $customer = new Customer();

        $this->assertEquals(array(), $customer->getShipToAddress());

        $address = new Address();
        $this->assertEquals(0, $customer->addToShipToAddress($address));
        $this->assertEquals(1, $customer->addToShipToAddress($address));
        $this->assertEquals(2, $customer->addToShipToAddress($address));
        $this->assertTrue($customer->issetShipToAddress(0));
        $this->assertTrue($customer->issetShipToAddress(1));
        $this->assertTrue($customer->issetShipToAddress(2));
        $customer->unsetShipToAddress(1);
        $this->assertEquals(3, $customer->addToShipToAddress($address));
        $this->assertFalse($customer->issetShipToAddress(1));
        $this->assertTrue($customer->issetShipToAddress(0));
        $this->assertTrue($customer->issetShipToAddress(2));
        $this->assertTrue($customer->issetShipToAddress(3));
    }

    /**
     *
     */
    public function testTelephone()
    {
        $customer = new Customer();

        $this->assertNull($customer->getTelephone());

        $telephone = "Telephone test";
        $customer->setTelephone($telephone);
        $this->assertEquals($telephone, $customer->getTelephone());

        $customer->setTelephone(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($customer->getTelephone()));

        try {
            $customer->setTelephone("");
            $this->fail("set Telephone with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $customer->setTelephone(null);
        $this->assertNull($customer->getTelephone());
    }

    /**
     *
     */
    public function testFax()
    {
        $customer = new Customer();

        $this->assertNull($customer->getFax());

        $fax = "Fax test";
        $customer->setFax($fax);
        $this->assertEquals($fax, $customer->getFax());

        $customer->setFax(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($customer->getFax()));

        try {
            $customer->setFax("");
            $this->fail("set fax with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $customer->setFax(null);
        $this->assertNull($customer->getFax());
    }

    /**
     *
     */
    public function testEmail()
    {
        $customer = new Customer();

        $this->assertNull($customer->getEmail());

        $email = "email@email.pt";
        $customer->setEmail($email);
        $this->assertEquals($email, $customer->getEmail());

        try {
            $customer->setEmail(\str_pad($email, 255, "a", STR_PAD_LEFT));
            $this->fail("set Email with length > 254 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $customer->setEmail("isNotEmail");
            $this->fail("set Email with wrong string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $customer->setEmail("");
            $this->fail("set Email with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $customer->setEmail(null);
        $this->assertNull($customer->getEmail());
    }

    /**
     *
     */
    public function testWebsite()
    {
        $customer = new Customer();

        $this->assertNull($customer->getWebsite());

        $website = "http://saft.pt";
        $customer->setWebsite($website);
        $this->assertEquals($website, $customer->getWebsite());

        try {
            $customer->setWebsite(\str_pad($website, 61, "a", STR_PAD_RIGHT));
            $this->fail("set Website with length > 60 should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $customer->setWebsite("");
            $this->fail("set Website with empty string should throw AuditFileException");
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $customer->setWebsite(null);
        $this->assertNull($customer->getWebsite());
    }

    /**
     * Create and populate a instance of Customer to be used in tests
     * @return Customer
     */
    public function createCustomer(): Customer
    {
        $address = new Address();
        $address->setAddressDetail("Billing Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");
        $address->setCountry(new Country(Country::ISO_BR));

        $shToAdd = clone $address;
        $shToAdd->setAddressDetail("Ship to address test");

        $customer = new Customer();
        $customer->setCustomerID("ID999999990");
        $customer->setAccountID("Account id test");
        $customer->setCustomerTaxID("599999990");
        $customer->setCompanyName("Customer name test");
        $customer->setContact("Customer contact neme");
        $customer->setBillingAddress($address);
        $customer->addToShipToAddress($shToAdd);
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
    public function setNullsCustomer(Customer $customer)
    {
        $customer->setContact(null);
        $customer->unsetShipToAddress(0);
        $customer->setTelephone(null);
        $customer->setFax(null);
        $customer->setEmail(null);
        $customer->setWebsite(null);
    }

    public function testCreateXmlNode()
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $customer = $this->createCustomer();

        $this->assertInstanceOf(\SimpleXMLElement::class,
            $customer->createXmlNode($node));
        $customerNode = $node->{Customer::N_CUSTOMER};
        $this->assertEquals($customer->getCustomerID(),
            (string) $customerNode->{Customer::N_CUSTOMERID}
        );
        $this->assertEquals($customer->getAccountID(),
            (string) $customerNode->{Customer::N_ACCOUNTID}
        );
        $this->assertEquals($customer->getCustomerTaxID(),
            (int) $customerNode->{Customer::N_CUSTOMERTAXID}
        );
        $this->assertEquals($customer->getCompanyName(),
            (string) $customerNode->{Customer::N_COMPANYNAME}
        );
        $this->assertEquals($customer->getContact(),
            (string) $customerNode->{Customer::N_CONTACT}
        );
        $this->assertEquals($customer->getBillingAddress()->getAddressDetail(),
            (string) $customerNode
            ->{Customer::N_BILLINGADDRESS}
            ->{Address::N_ADDRESSDETAIL}
        );

        $shToAddr = $customer->getShipToAddress();
        $this->assertEquals($shToAddr[0]->getAddressDetail(),
            (string) $customerNode
            ->{Customer::N_SHIPTOADDRESS}
            ->{Address::N_ADDRESSDETAIL}
        );

        $this->assertEquals($customer->getTelephone(),
            (string) $customerNode->{Customer::N_TELEPHONE}
        );
        $this->assertEquals($customer->getFax(),
            (string) $customerNode->{Customer::N_FAX}
        );
        $this->assertEquals($customer->getEmail(),
            (string) $customerNode->{Customer::N_EMAIL}
        );
        $this->assertEquals($customer->getWebsite(),
            (string) $customerNode->{Customer::N_WEBSITE}
        );

        $this->setNullsCustomer($customer);

        unset($node);
        $nodeNull         = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $customer->createXmlNode($nodeNull);
        $customerNodeNull = $nodeNull->{Customer::N_CUSTOMER};
        $this->assertEquals(0, $customerNodeNull->{Customer::N_CONTACT}->count());
        $this->assertEquals(0,
            $customerNodeNull->{Customer::N_SHIPTOADDRESS}->count());
        $this->assertEquals(0,
            $customerNodeNull->{Customer::N_TELEPHONE}->count());
        $this->assertEquals(0, $customerNodeNull->{Customer::N_FAX}->count());
        $this->assertEquals(0, $customerNodeNull->{Customer::N_EMAIL}->count());
        $this->assertEquals(0, $customerNodeNull->{Customer::N_WEBSITE}->count());
    }

    public function testParseXmlNode()
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $customer = $this->createCustomer();

        $xml = $customer->createXmlNode($node)->asXML();

        $parsed      = new Customer();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($customer->getCustomerID(), $parsed->getCustomerID());
        $this->assertEquals($customer->getAccountID(), $parsed->getAccountID());
        $this->assertEquals($customer->getCompanyName(),
            $parsed->getCompanyName());
        $this->assertEquals($customer->getContact(), $parsed->getContact());
        $this->assertEquals($customer->getBillingAddress()->getAddressDetail(),
            $parsed->getBillingAddress()->getAddressDetail());
        $cusShToAddr = $customer->getShipToAddress();
        $parShTAddr  = $parsed->getShipToAddress();
        $this->assertEquals($cusShToAddr[0]->getAddressDetail(),
            $parShTAddr[0]->getAddressDetail());
        $this->assertEquals($customer->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($customer->getFax(), $parsed->getFax());
        $this->assertEquals($customer->getEmail(), $parsed->getEmail());
        $this->assertEquals($customer->getWebsite(), $parsed->getWebsite());

        $shToAddrStack = $customer->getShipToAddress();
        $shToAddr      = clone $shToAddrStack[0];
        unset($parsed);
        $this->setNullsCustomer($customer);
        $parsedNull    = new Customer();
        $parsedNull->parseXmlNode(new \SimpleXMLElement(
                $customer->createXmlNode($node)->asXML()
        ));

        $customer->addToShipToAddress($shToAddr);
        $customer->addToShipToAddress(clone $shToAddr);
        $parseDobleShToAddr = new Customer();
        $parseDobleShToAddr->parseXmlNode(new \SimpleXMLElement(
                $customer->createXmlNode($node)->asXML()
        ));
        $this->assertEquals(2, \count($parseDobleShToAddr->getShipToAddress()));
    }

    public function testCreateXmlNodeWrongName()
    {
        $customer = new Customer();
        $node     = new \SimpleXMLElement("<root></root>"
        );
        try {
            $customer->createXmlNode($node);
            $this->fail("Creat a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testParseXmlNodeWrongName()
    {
        $customer = new Customer();
        $node     = new \SimpleXMLElement("<root></root>"
        );
        try {
            $customer->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }
}