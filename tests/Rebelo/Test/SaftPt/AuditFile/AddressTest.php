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
use Rebelo\SaftPt\AuditFile\Country;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class AddressTest
 *
 * @author João Rebelo
 */
class AddressTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\Address::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $address = new Address(new ErrorRegister());
        $this->assertInstanceOf(Address::class, $address);
        $this->assertFalse($address->issetCity());
        $this->assertFalse($address->issetCountry());
        $this->assertFalse($address->issetPostalCode());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetBuildingNumber(): void
    {
        $address  = new Address(new ErrorRegister());
        $buildNum = "Lote 999";
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber($buildNum));
        $this->assertEquals($buildNum, $address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(null));
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(\str_pad("_", 11, "_")));
        $this->assertEquals(10, \strlen($address->getBuildingNumber()));

        $address->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($address->setBuildingNumber(""));
        $this->assertSame("", $address->getBuildingNumber());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetStreetName(): void
    {
        $address = new Address(new ErrorRegister());
        $strName = "the street name";
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName($strName));
        $this->assertEquals($strName, $address->getStreetName());
        $this->assertTrue($address->setStreetName(null));
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName(\str_pad("_", 209, "_")));
        $this->assertEquals(200, \strlen($address->getStreetName()));

        $address->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($address->setStreetName(""));
        $this->assertSame("", $address->getStreetName());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetAddressDetail(): void
    {
        $address    = new Address(new ErrorRegister());
        $addrDetail = "the address detail";
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail($addrDetail));
        $this->assertEquals($addrDetail, $address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(null));
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(\str_pad("_", 212, "_")));
        $this->assertEquals(210, \strlen($address->getAddressDetail()));

        $address->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($address->setAddressDetail(""));
        $this->assertSame("", $address->getAddressDetail());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetCity(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getCity();
            $this->fail("getCity should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $city = "the city";
        $this->assertTrue($address->setCity($city));
        $this->assertTrue($address->issetCity());
        $this->assertEquals($city, $address->getCity());
        $this->assertTrue($address->setCity(\str_pad("_", 59, "_")));
        $this->assertEquals(50, \strlen($address->getCity()));
        try {
            $address->setCity(null);/** @phpstan-ignore-line */
            $this->fail("setCity should throw Erro whene set to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetRegion(): void
    {
        $address = new Address(new ErrorRegister());
        $region  = "the region";
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion($region));
        $this->assertEquals($region, $address->getRegion());
        $this->assertTrue($address->setRegion(null));
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion(\str_pad("_", 212, "_")));
        $this->assertEquals(50, \strlen($address->getRegion()));

        $address->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($address->setRegion(""));
        $this->assertSame("", $address->getRegion());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetCountry(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getCountry();
            $this->fail("getCountry should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $coIso = \Rebelo\SaftPt\AuditFile\Country::ISO_BR;
        $address->setCountry(new \Rebelo\SaftPt\AuditFile\Country($coIso));
        $this->assertTrue($address->issetCountry());
        $this->assertEquals($coIso, $address->getCountry()->get());
        try {
            $address->setCountry(null);/** @phpstan-ignore-line */
            $this->fail("setCountry should throw Error whene set to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPostalCode(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getPostalCode();
            $this->fail("getPostalCode should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $posCode = "12548447-999";
        $this->assertTrue($address->setPostalCode($posCode));
        $this->assertTrue($address->issetPostalCode());
        $this->assertEquals($posCode, $address->getPostalCode());
        $this->assertTrue($address->setPostalCode(\str_pad("_", 212, "_")));
        $this->assertEquals(20, \strlen($address->getPostalCode()));

        $address->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($address->setPostalCode(""));
        $this->assertSame("", $address->getPostalCode());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new Country(Country::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($addrNode)
        );

        $this->assertEquals(
            $address->getBuildingNumber(),
            $addrNode->{Address::N_BUILDINGNUMBER}
        );

        $this->assertEquals(
            $address->getStreetName(), $addrNode->{Address::N_STREETNAME}
        );

        $this->assertEquals(
            $address->getStreetName()." ".$address->getBuildingNumber(),
            $addrNode->{Address::N_ADDRESSDETAIL}
        );

        $this->assertEquals($address->getCity(), $addrNode->{Address::N_CITY});

        $this->assertEquals(
            $address->getCountry()->get(), $addrNode->{Address::N_COUNTRY}
        );

        $this->assertEquals(
            $address->getPostalCode(), $addrNode->{Address::N_POSTALCODE}
        );

        $this->assertEquals(
            $address->getRegion(), $addrNode->{Address::N_REGION}
        );

        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());

        $address->setBuildingNumber(null);
        $address->setStreetName(null);
        $address->setAddressDetail("Address detail test");
        $address->setRegion(null);

        $node = new \SimpleXMLElement("<Address></Address>");
        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($node)
        );

        $this->assertEquals(0, $node->{Address::N_BUILDINGNUMBER}->count());
        $this->assertEquals(0, $node->{Address::N_STREETNAME}->count());
        $this->assertEquals(0, $node->{Address::N_REGION}->count());
        $this->assertEquals(
            $address->getAddressDetail(), $node->{Address::N_ADDRESSDETAIL}
        );
        $this->assertEquals($address->getCity(), $node->{Address::N_CITY});
        $this->assertEquals(
            $address->getCountry()->get(), $node->{Address::N_COUNTRY}
        );
        $this->assertEquals(
            $address->getPostalCode(), $node->{Address::N_POSTALCODE}
        );

        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new Country(Country::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $parsed = new Address(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals(
            $address->getBuildingNumber(), $parsed->getBuildingNumber()
        );
        $this->assertEquals($address->getStreetName(), $parsed->getStreetName());
        $this->assertEquals($address->getCity(), $parsed->getCity());
        $this->assertEquals(
            $address->getCountry()->get(), $parsed->getCountry()->get()
        );
        $this->assertEquals($address->getPostalCode(), $parsed->getPostalCode());
        $this->assertEquals($address->getRegion(), $parsed->getRegion());
        $this->assertEquals(
            $address->getStreetName()." ".$address->getBuildingNumber(),
            $parsed->getAddressDetail()
        );

        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());

        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testClone(): void
    {
        $addr  = new Address(new ErrorRegister());
        $addr->setCountry(new Country(Country::ISO_PT));
        $clone = clone $addr;
        $clone->setCountry(new Country(Country::ISO_BR));
        $this->assertEquals($addr->getCountry()->get(), Country::ISO_PT);
        $this->assertEquals($clone->getCountry()->get(), Country::ISO_BR);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $xml      = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $address->setAddressDetail("");
        $address->setBuildingNumber("");
        $address->setPostalCode("");
        $address->setRegion("");
        $address->setStreetName("");

        $xml = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
    }
}