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

namespace Rebelo\SaftPt\AuditFile;

use PHPUnit\Framework\TestCase;
use Rebelo\Enum\EnumException;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class SupplierAddress
 *
 * @author João Rebelo
 */
class SupplierAddressTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(SupplierAddress::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $address = new SupplierAddress(new ErrorRegister());
        $this->assertInstanceOf(SupplierAddress::class, $address);
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
        $address  = new SupplierAddress(new ErrorRegister());
        $buildNum = "Lote 999";
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber($buildNum));
        $this->assertEquals($buildNum, $address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(null));
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(\str_pad("_", 11, "_")));
        $this->assertEquals(10, \strlen($address->getBuildingNumber()));

        $address->getErrorRegistor()->clearAllErrors();
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
        $address = new SupplierAddress(new ErrorRegister());
        $strName = "the street name";
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName($strName));
        $this->assertEquals($strName, $address->getStreetName());
        $this->assertTrue($address->setStreetName(null));
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName(\str_pad("_", 209, "_")));
        $this->assertEquals(200, \strlen($address->getStreetName()));

        $address->getErrorRegistor()->clearAllErrors();
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
        $address    = new SupplierAddress(new ErrorRegister());
        $addrDetail = "the address detail";
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail($addrDetail));
        $this->assertEquals($addrDetail, $address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(null));
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(\str_pad("_", 212, "_")));
        $this->assertEquals(210, \strlen($address->getAddressDetail()));

        $address->getErrorRegistor()->clearAllErrors();
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
        $address = new SupplierAddress(new ErrorRegister());
        try {
            $address->getCity();
            $this->fail("getCity should throw Error when not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $city = "the city";
        $this->assertTrue($address->setCity($city));
        $this->assertEquals($city, $address->getCity());
        $this->assertTrue($address->issetCity());
        $this->assertTrue($address->setCity(\str_pad("_", 59, "_")));
        $this->assertEquals(50, \strlen($address->getCity()));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setCity(""));
        $this->assertSame("", $address->getCity());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetRegion(): void
    {
        $address = new SupplierAddress(new ErrorRegister());
        $region  = "the region";
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion($region));
        $this->assertEquals($region, $address->getRegion());
        $this->assertTrue($address->setRegion(null));
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion(\str_pad("_", 212, "_")));
        $this->assertEquals(50, \strlen($address->getRegion()));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setRegion(""));
        $this->assertSame("", $address->getRegion());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws EnumException
     * @author João Rebelo
     * @test
     */
    public function testSetGetCountry(): void
    {
        $address = new SupplierAddress(new ErrorRegister());
        try {
            $address->getCountry();
            $this->fail("getCountry should throw Error when not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $coIso = SupplierCountry::ISO_BR;
        $address->setCountry(new SupplierCountry($coIso));
        $this->assertEquals($coIso, $address->getCountry()->get());
        $this->assertTrue($address->issetCountry());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPostalCode(): void
    {
        $address = new SupplierAddress(new ErrorRegister());
        try {
            $address->getPostalCode();
            $this->fail("getPostalCode should throw Error when not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $posCode = "12548447-999";
        $this->assertTrue($address->setPostalCode($posCode));
        $this->assertTrue($address->issetPostalCode());
        $this->assertEquals($posCode, $address->getPostalCode());
        $this->assertTrue($address->setPostalCode(\str_pad("_", 212, "_")));
        $this->assertEquals(20, \strlen($address->getPostalCode()));

        $address->getErrorRegistor()->clearAllErrors();
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
        $address  = new SupplierAddress(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($addrNode)
        );

        $this->assertEquals(
            $address->getBuildingNumber(),
            $addrNode->{SupplierAddress::N_BUILDINGNUMBER}
        );

        $this->assertEquals(
            $address->getStreetName(),
            $addrNode->{SupplierAddress::N_STREETNAME}
        );

        $this->assertEquals(
            $address->getStreetName()." ".$address->getBuildingNumber(),
            $addrNode->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $this->assertEquals(
            $address->getCity(), $addrNode->{SupplierAddress::N_CITY}
        );

        $this->assertEquals(
            $address->getCountry()->get(),
            $addrNode->{SupplierAddress::N_COUNTRY}
        );

        $this->assertEquals(
            $address->getPostalCode(),
            $addrNode->{SupplierAddress::N_POSTALCODE}
        );

        $this->assertEquals(
            $address->getRegion(), $addrNode->{SupplierAddress::N_REGION}
        );

        $address->setBuildingNumber(null);
        $address->setStreetName(null);
        $address->setAddressDetail("Address detail test");
        $address->setRegion(null);

        $node = new \SimpleXMLElement("<Address></Address>");
        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($node)
        );

        $this->assertEquals(
            0, $node->{SupplierAddress::N_BUILDINGNUMBER}->count()
        );

        $this->assertEquals(0, $node->{SupplierAddress::N_STREETNAME}->count());

        $this->assertEquals(0, $node->{SupplierAddress::N_REGION}->count());

        $this->assertEquals(
            $address->getAddressDetail(),
            $node->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $this->assertEquals(
            $address->getCity(), $node->{SupplierAddress::N_CITY}
        );

        $this->assertEquals(
            $address->getCountry()->get(), $node->{SupplierAddress::N_COUNTRY}
        );

        $this->assertEquals(
            $address->getPostalCode(), $node->{SupplierAddress::N_POSTALCODE}
        );
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new SupplierAddress(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $parsed = new SupplierAddress(new ErrorRegister());
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
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testClone(): void
    {
        $addr  = new SupplierAddress(new ErrorRegister());
        $addr->setCountry(new SupplierCountry(SupplierCountry::ISO_PT));
        $clone = clone $addr;
        $clone->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));
        $this->assertEquals(SupplierCountry::ISO_PT, $addr->getCountry()->get());
        $this->assertEquals(SupplierCountry::ISO_BR, $clone->getCountry()->get());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new SupplierAddress(new ErrorRegister());
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
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new SupplierAddress(new ErrorRegister());
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
