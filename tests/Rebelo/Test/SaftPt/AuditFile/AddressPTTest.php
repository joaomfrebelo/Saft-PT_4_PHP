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
use Rebelo\SaftPt\AuditFile\PostalCodePT;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class AddressPT
 *
 * @author João Rebelo
 */
class AddressPTTest extends TestCase
{

    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(\Rebelo\SaftPt\AuditFile\AddressPT::class);
        $this->assertTrue(true);
    }

    public function testSetGet()
    {
        $addrPT = new AddressPT();
        $this->assertInstanceOf(AddressPT::class, $addrPT);

        $buildNum = "Lote 999";
        $this->assertNull($addrPT->getBuildingNumber());
        $addrPT->setBuildingNumber($buildNum);
        $this->assertEquals($buildNum, $addrPT->getBuildingNumber());
        $addrPT->setBuildingNumber(null);
        $this->assertNull($addrPT->getBuildingNumber());
        $addrPT->setBuildingNumber(\str_pad("_", 11, "_"));
        $this->assertEquals(10, \strlen($addrPT->getBuildingNumber()));
        try {
            $addrPT->setBuildingNumber("");
            $this->fail("setBuildingNumber should throw AuditFileException whene empty");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $strName = "the street name";
        $this->assertNull($addrPT->getStreetName());
        $addrPT->setStreetName($strName);
        $this->assertEquals($strName, $addrPT->getStreetName());
        $addrPT->setStreetName(null);
        $this->assertNull($addrPT->getStreetName());
        $addrPT->setStreetName(\str_pad("_", 209, "_"));
        $this->assertEquals(200, \strlen($addrPT->getStreetName()));
        try {
            $addrPT->setStreetName("");
            $this->fail("setStreetName should throw AuditFileException whene empty");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $addrDetail = "the address detail";
        $this->assertNull($addrPT->getAddressDetail());
        $addrPT->setAddressDetail($addrDetail);
        $this->assertEquals($addrDetail, $addrPT->getAddressDetail());
        $addrPT->setAddressDetail(null);
        $this->assertNull($addrPT->getAddressDetail());
        $addrPT->setAddressDetail(\str_pad("_", 212, "_"));
        $this->assertEquals(210, \strlen($addrPT->getAddressDetail()));
        try {
            $addrPT->setAddressDetail("");
            $this->fail("setAddressDetail should throw AuditFileException whene empty");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $addrPT->getCity();
            $this->fail("getCity should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $city = "the city";
        $addrPT->setCity($city);
        $this->assertEquals($city, $addrPT->getCity());
        $addrPT->setCity(\str_pad("_", 59, "_"));
        $this->assertEquals(50, \strlen($addrPT->getCity()));
        try {
            $addrPT->setCity(null);
            $this->fail("setCity should throw Erro whene setted to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }

        $region = "the region";
        $this->assertNull($addrPT->getRegion());
        $addrPT->setRegion($region);
        $this->assertEquals($region, $addrPT->getRegion());
        $addrPT->setRegion(null);
        $this->assertNull($addrPT->getRegion());
        $addrPT->setRegion(\str_pad("_", 212, "_"));
        $this->assertEquals(50, \strlen($addrPT->getRegion()));
        try {
            $addrPT->setRegion("");
            $this->fail("setRegion should throw AuditFileException whene empty");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $this->assertEquals(\Rebelo\SaftPt\AuditFile\Country::ISO_PT,
            $addrPT->getCountry()->get());

        try {
            $addrPT->getPostalCode();
            $this->fail("getPostalCode should throw Error whene not initialized");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $posCode = new \Rebelo\SaftPt\AuditFile\PostalCodePT("1999-999");
        $addrPT->setPostalCode($posCode);
        $this->assertEquals($posCode->getPostalCode(),
            $addrPT->getPostalCode()->getPostalCode());
        try {
            $addrPT->setPostalCode(null);
            $this->fail("setPostalCode should throw Erro whene setted to null");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    public function testCreateXmlNode()
    {
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT();
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setPostalCode(new PostalCodePT("1999-999"));
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(\SimpleXMLElement::class,
            $address->createXmlNode($addrNode));

        $a = $address->getBuildingNumber();
        $b = $addrNode->{AddressPT::N_BUILDINGNUMBER};
        $this->assertEquals($address->getBuildingNumber(),
            $addrNode->{AddressPT::N_BUILDINGNUMBER});
        $this->assertEquals($address->getStreetName(),
            $addrNode->{AddressPT::N_STREETNAME});
        $this->assertEquals($address->getStreetName()." ".$address->getBuildingNumber(),
            $addrNode->{AddressPT::N_ADDRESSDETAIL});
        $this->assertEquals($address->getCity(), $addrNode->{AddressPT::N_CITY});
        $this->assertEquals($address->getCountry()->get(),
            $addrNode->{AddressPT::N_COUNTRY});
        $this->assertEquals($address->getPostalCode()->getPostalCode(),
            $addrNode->{AddressPT::N_POSTALCODE});
        $this->assertEquals($address->getRegion(),
            $addrNode->{AddressPT::N_REGION});

        $address->setBuildingNumber(null);
        $address->setStreetName(null);
        $address->setAddressDetail("Address detail test");
        $address->setRegion(null);

        $node = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $this->assertInstanceOf(\SimpleXMLElement::class,
            $address->createXmlNode($node));

        $this->assertEquals(0, $node->{AddressPT::N_BUILDINGNUMBER}->count());
        $this->assertEquals(0, $node->{AddressPT::N_STREETNAME}->count());
        $this->assertEquals(0, $node->{AddressPT::N_REGION}->count());
        $this->assertEquals($address->getAddressDetail(),
            $node->{AddressPT::N_ADDRESSDETAIL});
        $this->assertEquals($address->getCity(), $node->{AddressPT::N_CITY});
        $this->assertEquals($address->getCountry()->get(),
            $node->{AddressPT::N_COUNTRY});
        $this->assertEquals($address->getPostalCode()->getPostalCode(),
            $node->{AddressPT::N_POSTALCODE});
    }

    public function testParseXmlNode()
    {
        $addrNode = new \SimpleXMLElement("<AddressPT></AddressPT>");
        $address  = new AddressPT();
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setPostalCode(new PostalCodePT("1999-999"));
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();

        $parsed = new AddressPT();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($address->getBuildingNumber(),
            $parsed->getBuildingNumber());
        $this->assertEquals($address->getStreetName(), $parsed->getStreetName());
        $this->assertEquals($address->getCity(), $parsed->getCity());
        $this->assertEquals($address->getCountry()->get(),
            $parsed->getCountry()->get());
        $this->assertEquals($address->getPostalCode(), $parsed->getPostalCode());
        $this->assertEquals($address->getRegion(), $parsed->getRegion());
        $this->assertEquals($address->getStreetName()." ".$address->getBuildingNumber(),
            $parsed->getAddressDetail());
    }

    public function testClone()
    {
        $addr    = new AddressPT();
        $addrPc  = "1999-000";
        $addr->setPostalCode(new PostalCodePT($addrPc));
        $clone   = clone $addr;
        $clonePc = "1100-999";
        $clone->setPostalCode(new PostalCodePT($clonePc));
        $this->assertEquals($addr->getPostalCode()->getPostalCode(), $addrPc);
        $this->assertEquals($clone->getPostalCode()->getPostalCode(), $clonePc);
    }
}