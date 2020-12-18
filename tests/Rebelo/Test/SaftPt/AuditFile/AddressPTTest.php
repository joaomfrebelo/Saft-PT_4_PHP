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
use Rebelo\SaftPt\AuditFile\AddressPT;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class AddressPT
 *
 * @author João Rebelo
 */
class AddressPTTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\AddressPT::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $addrPT = new AddressPT(new ErrorRegister());
        $this->assertInstanceOf(AddressPT::class, $addrPT);

        $this->assertFalse($addrPT->issetCity());
        $this->assertFalse($addrPT->issetPostalCode());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetBuildingNumber(): void
    {
        $addrPT = new AddressPT(new ErrorRegister());
        $this->assertInstanceOf(AddressPT::class, $addrPT);

        $buildNum = "Lote 999";
        $this->assertNull($addrPT->getBuildingNumber());

        $this->assertTrue($addrPT->setBuildingNumber($buildNum));
        $this->assertEquals($buildNum, $addrPT->getBuildingNumber());

        $this->assertTrue($addrPT->setBuildingNumber(null));
        $this->assertNull($addrPT->getBuildingNumber());

        $this->assertTrue($addrPT->setBuildingNumber(\str_pad("_", 11, "_")));
        $this->assertEquals(10, \strlen($addrPT->getBuildingNumber()));

        $addrPT->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($addrPT->setBuildingNumber(""));
        $this->assertSame("", $addrPT->getBuildingNumber());
        $this->assertNotEmpty($addrPT->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetStreetName(): void
    {
        $addrPT  = new AddressPT(new ErrorRegister());
        $strName = "the street name";
        $this->assertNull($addrPT->getStreetName());
        $this->assertTrue($addrPT->setStreetName($strName));
        $this->assertEquals($strName, $addrPT->getStreetName());
        $this->assertTrue($addrPT->setStreetName(null));
        $this->assertNull($addrPT->getStreetName());
        $addrPT->setStreetName(\str_pad("_", 209, "_"));
        $this->assertEquals(200, \strlen($addrPT->getStreetName()));

        $addrPT->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($addrPT->setStreetName(""));
        $this->assertSame("", $addrPT->getStreetName());
        $this->assertNotEmpty($addrPT->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetAddressDetail(): void
    {
        $addrPT     = new AddressPT(new ErrorRegister());
        $addrDetail = "the address detail";
        $this->assertNull($addrPT->getAddressDetail());
        $this->assertTrue($addrPT->setAddressDetail($addrDetail));
        $this->assertEquals($addrDetail, $addrPT->getAddressDetail());
        $this->assertTrue($addrPT->setAddressDetail(null));
        $this->assertNull($addrPT->getAddressDetail());
        $addrPT->setAddressDetail(\str_pad("_", 212, "_"));
        $this->assertEquals(210, \strlen($addrPT->getAddressDetail()));

        $addrPT->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($addrPT->setAddressDetail(""));
        $this->assertSame("", $addrPT->getAddressDetail());
        $this->assertNotEmpty($addrPT->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetCity(): void
    {
        $addrPT = new AddressPT(new ErrorRegister());
        try {
            $addrPT->getCity();
            $this->fail("getCity should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $city = "the city";
        $this->assertTrue($addrPT->setCity($city));
        $this->assertEquals($city, $addrPT->getCity());
        $this->assertTrue($addrPT->issetCity());
        $addrPT->setCity(\str_pad("_", 59, "_"));
        $this->assertEquals(50, \strlen($addrPT->getCity()));

        $addrPT->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($addrPT->setCity(""));
        $this->assertSame("", $addrPT->getCity());
        $this->assertNotEmpty($addrPT->getErrorRegistor()->getOnSetValue());

        try {
            $addrPT->setCity(null);/** @phpstan-ignore-line */
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
        $addrPT = new AddressPT(new ErrorRegister());
        $region = "the region";
        $this->assertNull($addrPT->getRegion());
        $addrPT->setRegion($region);
        $this->assertEquals($region, $addrPT->getRegion());
        $addrPT->setRegion(null);
        $this->assertNull($addrPT->getRegion());
        $addrPT->setRegion(\str_pad("_", 212, "_"));
        $this->assertEquals(50, \strlen($addrPT->getRegion()));
        $addrPT->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($addrPT->setRegion(""));
        $this->assertSame("", $addrPT->getRegion());
        $this->assertNotEmpty($addrPT->getErrorRegistor()->getOnSetValue());

        $this->assertEquals(
            \Rebelo\SaftPt\AuditFile\Country::ISO_PT,
            $addrPT->getCountry()->get()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetPostalCode(): void
    {
        $addrPT = new AddressPT(new ErrorRegister());

        try {
            $addrPT->getPostalCode();
            $this->fail("GetPostalCode should throw Error when set to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $postalCode = "1999-999";
        $this->assertTrue($addrPT->setPostalCode($postalCode));
        $this->assertTrue($addrPT->issetPostalCode());
        $this->assertEquals($postalCode, $addrPT->getPostalCode());
        try {
            $addrPT->setPostalCode(null);/** @phpstan-ignore-line */
            $this->fail("setPostalCode should throw TypeError when set to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($addrNode)
        );

        $this->assertEquals(
            $address->getBuildingNumber(),
            $addrNode->{AddressPT::N_BUILDINGNUMBER}
        );

        $this->assertEquals(
            $address->getStreetName(), $addrNode->{AddressPT::N_STREETNAME}
        );

        $this->assertEquals(
            $address->getStreetName()." ".$address->getBuildingNumber(),
            $addrNode->{AddressPT::N_ADDRESSDETAIL}
        );

        $this->assertEquals($address->getCity(), $addrNode->{AddressPT::N_CITY});

        $this->assertEquals(
            $address->getCountry()->get(), $addrNode->{AddressPT::N_COUNTRY}
        );

        $this->assertEquals(
            $address->getPostalCode(), $addrNode->{AddressPT::N_POSTALCODE}
        );

        $this->assertEquals(
            $address->getRegion(), $addrNode->{AddressPT::N_REGION}
        );

        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());

        $address->setBuildingNumber(null);
        $address->setStreetName(null);
        $address->setAddressDetail("Address detail test");
        $address->setRegion(null);

        $node = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($node)
        );

        $this->assertEquals(0, $node->{AddressPT::N_BUILDINGNUMBER}->count());

        $this->assertEquals(0, $node->{AddressPT::N_STREETNAME}->count());

        $this->assertEquals(0, $node->{AddressPT::N_REGION}->count());

        $this->assertEquals(
            $address->getAddressDetail(), $node->{AddressPT::N_ADDRESSDETAIL}
        );

        $this->assertEquals($address->getCity(), $node->{AddressPT::N_CITY});

        $this->assertEquals(
            $address->getCountry()->get(), $node->{AddressPT::N_COUNTRY}
        );

        $this->assertEquals(
            $address->getPostalCode(), $node->{AddressPT::N_POSTALCODE}
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
    public function testParseXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $parsed = new AddressPT(new ErrorRegister());

        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals(
            $address->getBuildingNumber(),
            $parsed->getBuildingNumber()
        );
        $this->assertEquals($address->getStreetName(), $parsed->getStreetName());
        $this->assertEquals($address->getCity(), $parsed->getCity());
        $this->assertEquals(
            $address->getCountry()->get(),
            $parsed->getCountry()->get()
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
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testClone(): void
    {
        $addr    = new AddressPT(new ErrorRegister());
        $addrPc  = "1999-000";
        $addr->setPostalCode($addrPc);
        $clone   = clone $addr;
        $clonePc = "1100-999";
        $clone->setPostalCode($clonePc);
        $this->assertEquals($addr->getPostalCode(), $addrPc);
        $this->assertEquals($clone->getPostalCode(), $clonePc);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT(new ErrorRegister());
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
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT(new ErrorRegister());
        $address->setAddressDetail("");
        $address->setBuildingNumber("");
        $address->setPostalCode("1999");
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
