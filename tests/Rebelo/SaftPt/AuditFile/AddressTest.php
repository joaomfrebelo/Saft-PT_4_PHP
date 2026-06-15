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

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Commune;

/**
 * Class AddressTest
 *
 * @author João Rebelo
 */
class AddressTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(Address::class))->testReflection(Address::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testSetGetBuildingNumber(): void
    {
        $address  = new Address(new ErrorRegister());
        $buildNum = "Lote 999";
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber($buildNum));
        $this->assertSame($buildNum, $address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(null));
        $this->assertNull($address->getBuildingNumber());
        $this->assertTrue($address->setBuildingNumber(\str_pad("_", 11, "_")));
        /** @phpstan-ignore-next-line */
        $this->assertSame(10, \strlen($address->getBuildingNumber() ?? ""));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setBuildingNumber(""));
        $this->assertSame("", $address->getBuildingNumber());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetStreetName(): void
    {
        $address = new Address(new ErrorRegister());
        $strName = "the street name";
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName($strName));
        $this->assertSame($strName, $address->getStreetName());
        $this->assertTrue($address->setStreetName(null));
        $this->assertNull($address->getStreetName());
        $this->assertTrue($address->setStreetName(\str_pad("_", 209, "_")));
        /** @phpstan-ignore-next-line */
        $this->assertSame(200, \strlen($address->getStreetName() ?? ""));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setStreetName(""));
        $this->assertSame("", $address->getStreetName());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetAddressDetail(): void
    {
        $address    = new Address(new ErrorRegister());
        $addrDetail = "the address detail";
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail($addrDetail));
        $this->assertSame($addrDetail, $address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(null));
        $this->assertNull($address->getAddressDetail());
        $this->assertTrue($address->setAddressDetail(\str_pad("_", 212, "_")));
        /** @phpstan-ignore-next-line */
        $this->assertSame(210, \strlen($address->getAddressDetail() ?? ""));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setAddressDetail(""));
        $this->assertSame("", $address->getAddressDetail());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetCity(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getCity();
            $this->fail("getCity should throw Error when not initialized");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $city = "the city";
        $this->assertTrue($address->setCity($city));
        $this->assertTrue($address->issetCity());
        $this->assertSame($city, $address->getCity());
        $this->assertTrue($address->setCity(\str_pad("_", 59, "_")));
        $this->assertSame(50, \strlen($address->getCity()));
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetRegion(): void
    {
        $address = new Address(new ErrorRegister());
        $region  = "the region";
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion($region));
        $this->assertSame($region, $address->getRegion());
        $this->assertTrue($address->setRegion(null));
        $this->assertNull($address->getRegion());
        $this->assertTrue($address->setRegion(\str_pad("_", 212, "_")));
        /** @phpstan-ignore-next-line */
        $this->assertSame(50, \strlen($address->getRegion() ?? ""));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setRegion(""));
        $this->assertSame("", $address->getRegion());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetCountry(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getCountry();
            $this->fail("getCountry should throw Error when not initialized");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $countryIso = Country::ISO_BR;
        $address->setCountry($countryIso);
        $this->assertTrue($address->issetCountry());
        $this->assertSame($countryIso, $address->getCountry());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetPostalCode(): void
    {
        $address = new Address(new ErrorRegister());
        try {
            $address->getPostalCode();
            $this->fail("getPostalCode should throw Error when not initialized");
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $posCode = "12548447-999";
        $this->assertTrue($address->setPostalCode($posCode));
        $this->assertTrue($address->issetPostalCode());
        $this->assertSame($posCode, $address->getPostalCode());
        $this->assertTrue($address->setPostalCode(\str_pad("_", 212, "_")));
        $this->assertSame(20, \strlen($address->getPostalCode()));

        $address->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($address->setPostalCode(""));
        $this->assertSame("", $address->getPostalCode());
        $this->assertNotEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(Country::ISO_BR);
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $this->assertInstanceOf(
            \SimpleXMLElement::class,
            $address->createXmlNode($addrNode)
        );

        $this->assertSame(
            $address->getBuildingNumber(),
            (string)$addrNode->{Address::N_BUILDING_NUMBER}
        );

        $this->assertSame(
            $address->getStreetName(), (string)$addrNode->{Address::N_STREET_NAME}
        );

        $this->assertSame(
            $address->getStreetName() . " " . $address->getBuildingNumber(),
            (string)$addrNode->{Address::N_ADDRESS_DETAIL}
        );

        $this->assertSame($address->getCity(), (string)$addrNode->{Address::N_CITY});

        $this->assertSame($address->getCountry()->value, (string)$addrNode->{Address::N_COUNTRY});

        $this->assertSame($address->getPostalCode(), (string)$addrNode->{Address::N_POSTAL_CODE});

        $this->assertSame($address->getRegion(), (string)$addrNode->{Address::N_REGION});

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

        $this->assertSame(0, $node->{Address::N_BUILDING_NUMBER}->count());
        $this->assertSame(0, $node->{Address::N_STREET_NAME}->count());
        $this->assertSame(0, $node->{Address::N_REGION}->count());
        $this->assertSame($address->getAddressDetail(), (string)$node->{Address::N_ADDRESS_DETAIL});
        $this->assertSame($address->getCity(), (string)$node->{Address::N_CITY});
        $this->assertSame(
            $address->getCountry()->value, (string)$node->{Address::N_COUNTRY}
        );
        $this->assertSame(
            $address->getPostalCode(), (string)$node->{Address::N_POSTAL_CODE}
        );

        $this->assertEmpty($address->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($address->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($address->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNode(): void
    {
        $addrNode = new \SimpleXMLElement("<Address></Address>");
        $address  = new Address(new ErrorRegister());
        $address->setBuildingNumber("999");
        $address->setStreetName("Street test");
        $address->setCity("Sintra");
        $address->setCountry(Country::ISO_BR);
        $address->setPostalCode("9542979");
        $address->setRegion("Lisbon");

        $xml = $address->createXmlNode($addrNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $parsed = new Address(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertSame(
            $address->getBuildingNumber(), $parsed->getBuildingNumber()
        );
        $this->assertSame($address->getStreetName(), $parsed->getStreetName());
        $this->assertSame($address->getCity(), $parsed->getCity());
        $this->assertSame($address->getCountry(), $parsed->getCountry());
        $this->assertSame($address->getPostalCode(), $parsed->getPostalCode());
        $this->assertSame($address->getRegion(), $parsed->getRegion());
        $this->assertSame(
            $address->getStreetName() . " " . $address->getBuildingNumber(),
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
     */
    #[Test]
    public function testClone(): void
    {
        $addr = new Address(new ErrorRegister());
        $addr->setCountry(Country::ISO_PT);
        $clone = clone $addr;
        $clone->setCountry(Country::ISO_BR);
        $this->assertSame(Country::ISO_PT, $addr->getCountry());
        $this->assertSame(Country::ISO_BR, $clone->getCountry());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
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
