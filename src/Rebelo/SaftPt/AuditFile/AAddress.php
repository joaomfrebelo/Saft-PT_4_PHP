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

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\SaftPt\AuditFile\Address;
use Rebelo\SaftPt\AuditFile\AddressPT;

/**
 * AAddress<br>
 * Abstract class for:<br>
 * <xs:complexType name="AddressStructure"><br>
 * <xs:complexType name="AddressStructurePT"><br>
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AAddress
    extends AAuditFile
{

    /**
     * Node name
     * @since 1.0.0
     */
    const N_BUILDINGNUMBER = "BuildingNumber";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_STREETNAME = "StreetName";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ADDRESSDETAIL = "AddressDetail";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CITY = "City";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_POSTALCODE = "PostalCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_REGION = "Region";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_COUNTRY = "Country";

    /**
     * <xs:element ref="BuildingNumber" minOccurs="0"/>
     * <xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/>
     * @var string
     * @since 1.0.0
     */
    private ?string $buildingNumber = null;

    /**
     * <xs:element ref="StreetName" minOccurs="0"/>
     * <xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @var string
     * @since 1.0.0
     */
    private ?string $streetName = null;

    /**
     * <xs:element ref="AddressDetail"/>
     * <xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/>
     * @var string
     * @since 1.0.0
     */
    private ?string $addressDetail = null;

    /**
     * <xs:element ref="City"/>
     * <xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @var string
     * @since 1.0.0
     */
    private string $city;

    /**
     * <xs:element ref="Region" minOccurs="0"/>
     * <xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @var string
     * @since 1.0.0
     */
    private ?string $region = null;

    /**
     * <xs:element ref="Country"/>
     * @var Country
     * @since 1.0.0
     */
    protected Country $country;

    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * <xs:element ref="BuildingNumber" minOccurs="0"/>
     * <xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBuildingNumber(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->buildingNumber === null
                        ? "null"
                        : $this->buildingNumber));
        return $this->buildingNumber;
    }

    /**
     * <xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getStreetName(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->streetName === null
                        ? "null"
                        : $this->streetName));
        return $this->streetName;
    }

    /**
     *
     * <xs:element ref="AddressDetail"/>
     * <xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getAddressDetail(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->addressDetail === null
                        ? "null"
                        : $this->addressDetail));
        return $this->addressDetail;
    }

    /**
     * <xs:element ref="City"/>
     * <xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getCity(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->city));
        return $this->city;
    }

    /**
     *
     * <xs:element ref="Region" minOccurs="0"/>
     * <xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getRegion(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->region === null
                        ? "null"
                        : $this->region));
        return $this->region;
    }

    /**
     *
     * <xs:element ref="BuildingNumber" minOccurs="0"/>
     * <xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/>
     *
     * @param string|null $buildingNumber
     * @return void
     * @since 1.0.0
     */
    public function setBuildingNumber(?string $buildingNumber): void
    {
        if ($buildingNumber !== null)
        {
            $this->buildingNumber = static::valTextMandMaxCar($buildingNumber,
                                                              10, __METHOD__);
        }
        else
        {
            $this->buildingNumber = $buildingNumber;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->buildingNumber === null
                        ? "null"
                        : $this->buildingNumber));
    }

    /**
     * <xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @param string|null $streetName
     * @return void
     * @since 1.0.0
     */
    public function setStreetName(?string $streetName): void
    {
        if ($streetName === null)
        {
            $this->streetName = $streetName;
        }
        else
        {
            $this->streetName = static::valTextMandMaxCar($streetName, 200,
                                                          __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->streetName === null
                        ? "null"
                        : $this->streetName));
    }

    /**
     *
     * <xs:element ref="AddressDetail"/>
     * <xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/>
     * @param string|null $addressDetail
     * @return void
     * @since 1.0.0
     */
    public function setAddressDetail(?string $addressDetail): void
    {
        if ($addressDetail !== null)
        {
            $this->addressDetail = static::valTextMandMaxCar($addressDetail,
                                                             210, __METHOD__);
        }
        else
        {
            $this->addressDetail = $addressDetail;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->addressDetail === null
                        ? "null"
                        : $this->addressDetail));
    }

    /**
     * <xs:element ref="City"/>
     * <xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @param string $city
     * @return void
     * @since 1.0.0
     */
    public function setCity(string $city): void
    {
        $this->city = static::valTextMandMaxCar($city, 50, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->city));
    }

    /**
     *
     * <xs:element ref="Region" minOccurs="0"/>
     * <xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @param string|null $region
     * @return void
     * @since 1.0.0
     */
    public function setRegion(?string $region): void
    {
        if ($region !== null)
        {
            $this->region = static::valTextMandMaxCar($region, 50, __METHOD__);
        }
        else
        {
            $this->region = $region;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->region === null
                        ? "null"
                        : $this->region));
    }

    /**
     * Create the child nodes to the address<br>
     * In the case of this nodeXml, address only will create the address
     * child node, the address root node will be created by the invoker
     * because cane be CompanyAddress or SupplierAddres or CustomerAddress, etc
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($this->getAddressDetail() !== null)
        {
            $node->addChild(static::N_ADDRESSDETAIL, $this->getAddressDetail());
        }
        elseif ($this->getStreetName() !== null)
        {
            $node->addChild(static::N_STREETNAME, $this->getStreetName());
            $addr = $this->getStreetName();
            if ($this->getBuildingNumber() !== null)
            {
                $node->addChild(static::N_BUILDINGNUMBER,
                                $this->getBuildingNumber());
                $addr .= " " . $this->getBuildingNumber();
            }
            $node->addChild(static::N_ADDRESSDETAIL, $addr);
        }
        else
        {
            throw new AuditFileException("The address structot doesn't have Address Detail and street name");
        }
        $node->addChild(static::N_CITY, $this->getCity());
        if ($this instanceof Address || $this instanceof SupplierAddress)
        {
            $node->addChild(static::N_POSTALCODE, $this->getPostalCode());
        }
        elseif ($this instanceof AddressPT)
        {
            $node->addChild(static::N_POSTALCODE,
                            $this->getPostalCode()->getPostalCode());
        }
        else
        {
            $msg = "unknow address class instande to get the postal code";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if ($this->getRegion() !== null)
        {
            $node->addChild(static::N_REGION, $this->getRegion());
        }
        return $node;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($node->{static::N_STREETNAME}->count() > 0)
        {
            $this->setStreetName((string) $node->{static::N_STREETNAME});
        }
        if ($node->{static::N_BUILDINGNUMBER}->count() > 0)
        {
            $this->setBuildingNumber((string) $node->{static::N_BUILDINGNUMBER});
        }
        $this->setAddressDetail((string) $node->{static::N_ADDRESSDETAIL});
        $this->setCity((string) $node->{static::N_CITY});
        if ($node->{static::N_REGION}->count() > 0)
        {
            $this->setRegion((string) $node->{static::N_REGION});
        }
    }

}
