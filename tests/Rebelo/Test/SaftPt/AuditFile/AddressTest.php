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
use Rebelo\SaftPt\AuditFile\Address;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\Country;

/**
 * Class AddressTest
 *
 * @author João Rebelo
 */
class AddressTest
    extends TestCase
{

    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\Address::class);
        $this->assertTrue(true);
    }

    public function testSetGet()
    {
        $address = new Address();
        $this->assertInstanceOf(Address::class, $address);

        $buildNum = "Lote 999";
        $this->assertNull($address->getBuildingNumber());
        $address->setBuildingNumber($buildNum);
        $this->assertEquals($buildNum, $address->getBuildingNumber());
        $address->setBuildingNumber(null);
        $this->assertNull($address->getBuildingNumber());
        $address->setBuildingNumber(\str_pad("_", 11, "_"));
        $this->assertEquals(10, \strlen($address->getBuildingNumber()));
        try
        {
            $address->setBuildingNumber("");
            $this->fail("setBuildingNumber should throw AuditFileException whene empty");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $strName = "the street name";
        $this->assertNull($address->getStreetName());
        $address->setStreetName($strName);
        $this->assertEquals($strName, $address->getStreetName());
        $address->setStreetName(null);
        $this->assertNull($address->getStreetName());
        $address->setStreetName(\str_pad("_", 209, "_"));
        $this->assertEquals(200, \strlen($address->getStreetName()));
        try
        {
            $address->setStreetName("");
            $this->fail("setStreetName should throw AuditFileException whene empty");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $addrDetail = "the address detail";
        $this->assertNull($address->getAddressDetail());
        $address->setAddressDetail($addrDetail);
        $this->assertEquals($addrDetail, $address->getAddressDetail());
        $address->setAddressDetail(null);
        $this->assertNull($address->getAddressDetail());
        $address->setAddressDetail(\str_pad("_", 212, "_"));
        $this->assertEquals(210, \strlen($address->getAddressDetail()));
        try
        {
            $address->setAddressDetail("");
            $this->fail("setAddressDetail should throw AuditFileException whene empty");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $address->getCity();
            $this->fail("getCity should throw Error whene not initialized");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $city = "the city";
        $address->setCity($city);
        $this->assertEquals($city, $address->getCity());
        $address->setCity(\str_pad("_", 59, "_"));
        $this->assertEquals(50, \strlen($address->getCity()));
        try
        {
            $address->setCity(null);
            $this->fail("setCity should throw Erro whene setted to null");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $region = "the region";
        $this->assertNull($address->getRegion());
        $address->setRegion($region);
        $this->assertEquals($region, $address->getRegion());
        $address->setRegion(null);
        $this->assertNull($address->getRegion());
        $address->setRegion(\str_pad("_", 212, "_"));
        $this->assertEquals(50, \strlen($address->getRegion()));
        try
        {
            $address->setRegion("");
            $this->fail("setRegion should throw AuditFileException whene empty");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try
        {
            $address->getCountry();
            $this->fail("getCountry should throw Error whene not initialized");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $coIso = \Rebelo\SaftPt\AuditFile\Country::ISO_BR;
        $address->setCountry(new \Rebelo\SaftPt\AuditFile\Country($coIso));
        $this->assertEquals($coIso, $address->getCountry()->get());
        try
        {
            $address->setCountry(null);
            $this->fail("setCountry should throw Error whene setted to null");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        try
        {
            $address->getPostalCode();
            $this->fail("getPostalCode should throw Error whene not initialized");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $posCode = "12548447-999";
        $address->setPostalCode($posCode);
        $this->assertEquals($posCode, $address->getPostalCode());
        $address->setPostalCode(\str_pad("_", 212, "_"));
        $this->assertEquals(20, \strlen($address->getPostalCode()));
        try
        {
            $address->setPostalCode(null);
            $this->fail("setPostalCode should throw Erro whene setted to null");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    public function testCreateXmlNode()
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address();
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new Country(Country::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(\SimpleXMLElement::class,
                                $address->createXmlNode($addrNode));

        $this->assertEquals($address->getBuildingNumber(),
                            $addrNode->{Address::N_BUILDINGNUMBER});
        $this->assertEquals($address->getStreetName(),
                            $addrNode->{Address::N_STREETNAME});
        $this->assertEquals($address->getStreetName() . " " . $address->getBuildingNumber(),
                            $addrNode->{Address::N_ADDRESSDETAIL});
        $this->assertEquals($address->getCity(), $addrNode->{Address::N_CITY});
        $this->assertEquals($address->getCountry()->get(),
                            $addrNode->{Address::N_COUNTRY});
        $this->assertEquals($address->getPostalCode(),
                            $addrNode->{Address::N_POSTALCODE});
        $this->assertEquals($address->getRegion(),
                            $addrNode->{Address::N_REGION});

        $address->setBuildingNumber(null);
        $address->setStreetName(null);
        $address->setAddressDetail("Address detail test");
        $address->setRegion(null);

        $node = new \SimpleXMLElement("<Address></Address>");
        $this->assertInstanceOf(\SimpleXMLElement::class,
                                $address->createXmlNode($node));

        $this->assertEquals(0, $node->{Address::N_BUILDINGNUMBER}->count());
        $this->assertEquals(0, $node->{Address::N_STREETNAME}->count());
        $this->assertEquals(0, $node->{Address::N_REGION}->count());
        $this->assertEquals($address->getAddressDetail(),
                            $node->{Address::N_ADDRESSDETAIL});
        $this->assertEquals($address->getCity(), $node->{Address::N_CITY});
        $this->assertEquals($address->getCountry()->get(),
                            $node->{Address::N_COUNTRY});
        $this->assertEquals($address->getPostalCode(),
                            $node->{Address::N_POSTALCODE});
    }

    public function testParseXmlNode()
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address();
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new Country(Country::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();

        $parsed = new Address();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($address->getBuildingNumber(),
                            $parsed->getBuildingNumber());
        $this->assertEquals($address->getStreetName(), $parsed->getStreetName());
        $this->assertEquals($address->getCity(), $parsed->getCity());
        $this->assertEquals($address->getCountry()->get(),
                            $parsed->getCountry()->get());
        $this->assertEquals($address->getPostalCode(), $parsed->getPostalCode());
        $this->assertEquals($address->getRegion(), $parsed->getRegion());
        $this->assertEquals($address->getStreetName() . " " . $address->getBuildingNumber(),
                            $parsed->getAddressDetail());
    }

    public function testClone()
    {
        $addr  = new Address();
        $addr->setCountry(new Country(Country::ISO_PT));
        $clone = clone $addr;
        $clone->setCountry(new Country(Country::ISO_BR));
        $this->assertEquals($addr->getCountry()->get(), Country::ISO_PT);
        $this->assertEquals($clone->getCountry()->get(), Country::ISO_BR);
    }

}
