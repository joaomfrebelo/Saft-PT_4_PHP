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
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\SupplierAddress;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SupplierCountry;

/**
 * Class SupplierTest
 *
 * @author João Rebelo
 */
class SupplierTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier::class
        );
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertNull($supplier->getContact());
        $this->assertNull($supplier->getEmail());
        $this->assertNull($supplier->getFax());
        $this->assertNull($supplier->getTelephone());
        $this->assertNull($supplier->getWebsite());
        $this->assertEquals(array(), $supplier->getShipFromAddress());
    }

    /**
     *
     */
    public function testSestGetSupplierID()
    {
        $supplier = new Supplier();

        try
        {
            $supplier->getSupplierID();
            $this->fail("Get supplier id without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $supplierId = "Contumer 1209";
        $supplier->setSupplierID($supplierId);
        $this->assertEquals($supplierId, $supplier->getSupplierID());
        try
        {
            $supplier->setSupplierID("");
            $this->fail("set supplier id with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setSupplierID(null);
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetAccountID()
    {
        $supplier = new Supplier();

        try
        {
            $supplier->getAccountID();
            $this->fail("Get Account id without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $accountId = "AccountID999";
        $supplier->setAccountID($accountId);
        $this->assertEquals($accountId, $supplier->getAccountID());
        $supplier->setAccountID("Desconhecido");
        $this->assertEquals("Desconhecido", $supplier->getAccountID());
        try
        {
            $supplier->setAccountID("");
            $this->fail("set account id with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setAccountID(str_pad("A", 32, "A"));
            $this->fail("set account id with length greater then 30 should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setAccountID(null);
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testSestGetSupplierTaxID()
    {
        $supplier = new Supplier();

        try
        {
            $supplier->getSupplierTaxID();
            $this->fail("Get Costomer tax id without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $supplierTaxId = "SupplierTaxID999";
        $supplier->setSupplierTaxID($supplierTaxId);
        $this->assertEquals($supplierTaxId, $supplier->getSupplierTaxID());
        try
        {
            $supplier->setSupplierTaxID("");
            $this->fail("set supplier tax id with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setSupplierTaxID(str_pad("A", 32, "A"));
            $this->fail("set supplier tax id with length greater then 30 should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setSupplierTaxID(null);
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testCompanyName()
    {
        $supplier = new Supplier();

        try
        {
            $supplier->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $name = "CompanyName FACTURACAO";
        $supplier->setCompanyName($name);
        $this->assertEquals($name, $supplier->getCompanyName());
        $supplier->setCompanyName(\str_pad("_", 109, "_"));
        $this->assertEquals(100, \strlen($supplier->getCompanyName()));
        try
        {
            $supplier->setCompanyName("");
            $this->fail("set company name id with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $supplier->setCompanyName(null);
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testContact()
    {
        $supplier = new Supplier();

        $this->assertNull($supplier->getContact());

        $name = "Contact name test";
        $supplier->setContact($name);
        $this->assertEquals($name, $supplier->getContact());
        $supplier->setContact(\str_pad("_", 51, "_"));
        $this->assertEquals(50, \strlen($supplier->getContact()));
        try
        {
            $supplier->setContact("");
            $this->fail("set contact name with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        $supplier->setContact(null);
        $this->assertNull($supplier->getContact());
    }

    /**
     *
     */
    public function testBillingSupplierAddress()
    {
        $supplier = new Supplier();

        try
        {
            $supplier->getBillingAddress();
            $this->fail("Get BillingSupplierAddress without initialize should throw error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $address = new SupplierAddress();
        $supplier->setBillingAddress($address);
        $this->assertInstanceOf(SupplierAddress::class,
                                $supplier->getBillingAddress());

        try
        {
            $supplier->setBillingAddress(null);
            $this->fail("Set BillingSupplierAddress to null should throw TypeError");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     *
     */
    public function testShipFromAddress()
    {
        $supplier = new Supplier();

        $this->assertEquals(array(), $supplier->getShipFromAddress());

        $address = new SupplierAddress();
        $this->assertEquals(0, $supplier->addToShipFromAddress($address));
        $this->assertEquals(1, $supplier->addToShipFromAddress($address));
        $this->assertEquals(2, $supplier->addToShipFromAddress($address));
        $this->assertTrue($supplier->issetShipFromAddress(0));
        $this->assertTrue($supplier->issetShipFromAddress(1));
        $this->assertTrue($supplier->issetShipFromAddress(2));
        $supplier->unsetShipFromAddress(1);
        $this->assertEquals(3, $supplier->addToShipFromAddress($address));
        $this->assertFalse($supplier->issetShipFromAddress(1));
        $this->assertTrue($supplier->issetShipFromAddress(0));
        $this->assertTrue($supplier->issetShipFromAddress(2));
        $this->assertTrue($supplier->issetShipFromAddress(3));
    }

    /**
     *
     */
    public function testTelephone()
    {
        $supplier = new Supplier();

        $this->assertNull($supplier->getTelephone());

        $telephone = "Telephone test";
        $supplier->setTelephone($telephone);
        $this->assertEquals($telephone, $supplier->getTelephone());

        $supplier->setTelephone(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($supplier->getTelephone()));

        try
        {
            $supplier->setTelephone("");
            $this->fail("set Telephone with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $supplier->setTelephone(null);
        $this->assertNull($supplier->getTelephone());
    }

    /**
     *
     */
    public function testFax()
    {
        $supplier = new Supplier();

        $this->assertNull($supplier->getFax());

        $fax = "Fax test";
        $supplier->setFax($fax);
        $this->assertEquals($fax, $supplier->getFax());

        $supplier->setFax(\str_pad("_", 300, "_"));
        $this->assertEquals(20, \strlen($supplier->getFax()));

        try
        {
            $supplier->setFax("");
            $this->fail("set fax with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $supplier->setFax(null);
        $this->assertNull($supplier->getFax());
    }

    /**
     *
     */
    public function testEmail()
    {
        $supplier = new Supplier();

        $this->assertNull($supplier->getEmail());

        $email = "email@email.pt";
        $supplier->setEmail($email);
        $this->assertEquals($email, $supplier->getEmail());

        try
        {
            $supplier->setEmail(\str_pad($email, 255, "a", STR_PAD_LEFT));
            $this->fail("set Email with length > 254 should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $supplier->setEmail("isNotEmail");
            $this->fail("set Email with wrong string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $supplier->setEmail("");
            $this->fail("set Email with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $supplier->setEmail(null);
        $this->assertNull($supplier->getEmail());
    }

    /**
     *
     */
    public function testWebsite()
    {
        $supplier = new Supplier();

        $this->assertNull($supplier->getWebsite());

        $website = "http://saft.pt";
        $supplier->setWebsite($website);
        $this->assertEquals($website, $supplier->getWebsite());

        try
        {
            $supplier->setWebsite(\str_pad($website, 61, "a", STR_PAD_RIGHT));
            $this->fail("set Website with length > 60 should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $supplier->setWebsite("isNotWebsite");
            $this->fail("set Website with wrong string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $supplier->setWebsite("");
            $this->fail("set Website with empty string should throw AuditFileException");
        }
        catch (\Exception | \TypeError $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $supplier->setWebsite(null);
        $this->assertNull($supplier->getWebsite());
    }

    /**
     * Create and populate a instance of Supplier to be used in tests
     * @return Supplier
     */
    public function createSupplier(): Supplier
    {
        $address = new SupplierAddress();
        $address->setAddressDetail("Billing Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");
        $address->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));

        $shToAdd = clone $address;
        $shToAdd->setAddressDetail("Ship to address test");

        $supplier = new Supplier();
        $supplier->setSupplierID("ID999999990");
        $supplier->setAccountID("Account id test");
        $supplier->setSupplierTaxID("599999990");
        $supplier->setCompanyName("Supplier name test");
        $supplier->setContact("Supplier contact neme");
        $supplier->setBillingAddress($address);
        $supplier->addToShipFromAddress($shToAdd);
        $supplier->setTelephone("+351 987654321");
        $supplier->setFax("123456789");
        $supplier->setEmail("email@emil.pt");
        $supplier->setWebsite("http://saft.pt");
        $supplier->setSelfBillingIndicator(false);
        return $supplier;
    }

    /**
     * Set the properties that can have nulll to null
     * @param Supplier $supplier
     */
    public function setNullsSupplier(Supplier $supplier)
    {
        $supplier->setContact(null);
        $supplier->unsetShipFromAddress(0);
        $supplier->setTelephone(null);
        $supplier->setFax(null);
        $supplier->setEmail(null);
        $supplier->setWebsite(null);
    }

    public function testCreateXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $supplier = $this->createSupplier();

        $this->assertInstanceOf(\SimpleXMLElement::class,
                                $supplier->createXmlNode($node));
        $supplierNode = $node->{Supplier::N_CUSTOMER};
        $this->assertEquals($supplier->getSupplierID(),
                            (string) $supplierNode->{Supplier::N_CUSTOMERID}
        );
        $this->assertEquals($supplier->getAccountID(),
                            (string) $supplierNode->{Supplier::N_ACCOUNTID}
        );
        $this->assertEquals($supplier->getSupplierTaxID(),
                            (int) $supplierNode->{Supplier::N_CUSTOMERTAXID}
        );
        $this->assertEquals($supplier->getCompanyName(),
                            (string) $supplierNode->{Supplier::N_COMPANYNAME}
        );
        $this->assertEquals($supplier->getContact(),
                            (string) $supplierNode->{Supplier::N_CONTACT}
        );
        $this->assertEquals($supplier->getBillingAddress()->getAddressDetail(),
                            (string) $supplierNode
            ->{Supplier::N_BILLINGADDRESS}
            ->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $shToAddr = $supplier->getShipFromAddress();
        $this->assertEquals($shToAddr[0]->getAddressDetail(),
                            (string) $supplierNode
            ->{Supplier::N_SHIPTOADDRESS}
            ->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $this->assertEquals($supplier->getTelephone(),
                            (string) $supplierNode->{Supplier::N_TELEPHONE}
        );
        $this->assertEquals($supplier->getFax(),
                            (string) $supplierNode->{Supplier::N_FAX}
        );
        $this->assertEquals($supplier->getEmail(),
                            (string) $supplierNode->{Supplier::N_EMAIL}
        );
        $this->assertEquals($supplier->getWebsite(),
                            (string) $supplierNode->{Supplier::N_WEBSITE}
        );

        $this->setNullsSupplier($supplier);

        unset($node);
        $nodeNull         = new \SimpleXMLElement("<root></root>");
        $supplier->createXmlNode($nodeNull);
        $supplierNodeNull = $nodeNull->{Supplier::N_CUSTOMER};
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_CONTACT}->count());
        $this->assertEquals(0,
                            $supplierNodeNull->{Supplier::N_SHIPTOADDRESS}->count());
        $this->assertEquals(0,
                            $supplierNodeNull->{Supplier::N_TELEPHONE}->count());
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_FAX}->count());
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_EMAIL}->count());
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_WEBSITE}->count());
    }

    public function testParseXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $supplier = $this->createSupplier();

        $xml = $supplier->createXmlNode($node)->asXML();

        $parsed      = new Supplier();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($supplier->getSupplierID(), $parsed->getSupplierID());
        $this->assertEquals($supplier->getAccountID(), $parsed->getAccountID());
        $this->assertEquals($supplier->getCompanyName(),
                            $parsed->getCompanyName());
        $this->assertEquals($supplier->getContact(), $parsed->getContact());
        $this->assertEquals($supplier->getBillingAddress()->getAddressDetail(),
                            $parsed->getBillingAddress()->getAddressDetail());
        $cusShToAddr = $supplier->getShipFromAddress();
        $parShTAddr  = $parsed->getShipFromAddress();
        $this->assertEquals($cusShToAddr[0]->getAddressDetail(),
                            $parShTAddr[0]->getAddressDetail());
        $this->assertEquals($supplier->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($supplier->getFax(), $parsed->getFax());
        $this->assertEquals($supplier->getEmail(), $parsed->getEmail());
        $this->assertEquals($supplier->getWebsite(), $parsed->getWebsite());

        $shToAddrStack = $supplier->getShipFromAddress();
        $shToAddr      = clone $shToAddrStack[0];
        unset($parsed);
        $this->setNullsSupplier($supplier);
        $parsedNull    = new Supplier();
        $parsedNull->parseXmlNode(new \SimpleXMLElement(
                $supplier->createXmlNode($node)->asXML()
        ));

        $supplier->addToShipFromAddress($shToAddr);
        $supplier->addToShipFromAddress(clone $shToAddr);
        $parseDobleShToAddr = new Supplier();
        $parseDobleShToAddr->parseXmlNode(new \SimpleXMLElement(
                $supplier->createXmlNode($node)->asXML()
        ));
        $this->assertEquals(2, \count($parseDobleShToAddr->getShipFromAddress()));
    }

}
