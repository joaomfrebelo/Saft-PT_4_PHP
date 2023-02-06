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

/**
 * AAddress<br>
 * Abstract class for:<br>
 * &lt;xs:complexType name="AddressStructure"><br>
 * &lt;xs:complexType name="AddressStructurePT"><br>
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AAddress extends AAuditFile
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
     * &lt;xs:element ref="BuildingNumber" minOccurs="0"/&gt;
     * &lt;xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $buildingNumber = null;

    /**
     * &lt;xs:element ref="StreetName" minOccurs="0"/&gt;
     * &lt;xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $streetName = null;

    /**
     * &lt;xs:element ref="AddressDetail"/&gt;
     * &lt;xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $addressDetail = null;

    /**
     * &lt;xs:element ref="City"/&gt;
     * &lt;xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $city;

    /**
     * &lt;xs:element ref="Region" minOccurs="0"/&gt;
     * &lt;xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $region = null;

    /**
     * &lt;xs:element ref="Country"/&gt;
     * @var Country
     * @since 1.0.0
     */
    protected Country $country;

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get BuildingNumber<br>     *
     * &lt;xs:element ref="BuildingNumber" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/&gt;
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBuildingNumber(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->buildingNumber === null ? "null" : $this->buildingNumber
                )
            );
        return $this->buildingNumber;
    }

    /**
     * Get StreetName<br>
     * &lt;xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getStreetName(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->streetName === null ? "null" : $this->streetName
                )
            );
        return $this->streetName;
    }

    /**
     * Get AddressDetail<br>
     * Shall include street name, building number and floor, if applicable.<br>
     * &lt;xs:element ref="AddressDetail"/&gt;<br>
     * &lt;xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getAddressDetail(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->addressDetail === null ? "null" : $this->addressDetail
                )
            );
        return $this->addressDetail;
    }

    /**
     * GetCity<br>
     * &lt;xs:element ref="City"/&gt;<br>
     * &lt;xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCity(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->city));
        return $this->city;
    }

    /**
     * Get if is set City
     * @return bool
     * @since 1.0.0
     */
    public function issetCity(): bool
    {
        return isset($this->city);
    }

    /**
     * Get Region<br>
     * &lt;xs:element ref="Region" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getRegion(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->region === null ? "null" : $this->region
                )
            );
        return $this->region;
    }

    /**
     * Set BuildingNumber
     * &lt;xs:element ref="BuildingNumber" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="BuildingNumber" type="SAFPTtextTypeMandatoryMax10Car"/&gt;
     *
     * @param string|null $buildingNumber
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setBuildingNumber(?string $buildingNumber): bool
    {
        try {
            $this->buildingNumber = $buildingNumber === null ?
                null : static::valTextMandMaxCar($buildingNumber, 10, __METHOD__);
            $return               = true;
        } catch (AuditFileException $e) {
            $this->buildingNumber = $buildingNumber;
            $this->getErrorRegistor()->addOnSetValue("BuildingNumber_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return               = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->buildingNumber === null ? "null" : $this->buildingNumber
                )
            );
        return $return;
    }

    /**
     * Set StreetName<br>
     * &lt;xs:element name="StreetName" type="SAFPTtextTypeMandatoryMax200Car"/&gt;<br>
     * @param string|null $streetName
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setStreetName(?string $streetName): bool
    {
        try {
            $this->streetName = $streetName === null ?
                null : static::valTextMandMaxCar($streetName, 200, __METHOD__);
            $return           = true;
        } catch (AuditFileException $e) {
            $this->streetName = $streetName;
            $this->getErrorRegistor()->addOnSetValue("StreetName_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->streetName === null ? "null" : $this->streetName
                )
            );
        return $return;
    }

    /**
     * Set AddressDetail<br>
     * &lt;xs:element ref="AddressDetail"/&gt;<br>
     * &lt;xs:element name="AddressDetail" type="SAFPTtextTypeMandatoryMax210Car"/&gt;<br>
     * @param string|null $addressDetail
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setAddressDetail(?string $addressDetail): bool
    {
        try {
            $this->addressDetail = $addressDetail === null ?
                null : static::valTextMandMaxCar($addressDetail, 210, __METHOD__);
            $return              = true;
        } catch (AuditFileException $e) {
            $this->addressDetail = $addressDetail;
            $this->getErrorRegistor()->addOnSetValue("AddressDetail_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return              = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->addressDetail === null ? "null" : $this->addressDetail
                )
            );
        return $return;
    }

    /**
     * Set City<br>
     * &lt;xs:element ref="City"/&gt;<br>
     * &lt;xs:element name="City" type="SAFPTtextTypeMandatoryMax50Car"/&gt;<br>
     * @param string $city
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCity(string $city): bool
    {
        try {
            $this->city = static::valTextMandMaxCar($city, 50, __METHOD__);
            $return     = true;
        } catch (AuditFileException $e) {
            $this->city = $city;
            $this->getErrorRegistor()->addOnSetValue("City_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return     = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->city));
        return $return;
    }

    /**
     * Set Region
     * &lt;xs:element ref="Region" minOccurs="0"/&gt;
     * &lt;xs:element name="Region" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @param string|null $region
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setRegion(?string $region): bool
    {
        try {
            $this->region = $region === null ?
                null : static::valTextMandMaxCar($region, 50, __METHOD__);
            $return       = true;
        } catch (AuditFileException $e) {
            $this->region = $region;
            $this->getErrorRegistor()->addOnSetValue("Region_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return       = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->region === null ? "null" : $this->region
                )
            );
        return $return;
    }

    /**
     * Create the child nodes to the address<br>
     * In the case of this nodeXml, address only will create the address
     * child node, the address root node will be created by the invoker
     * because cane be CompanyAddress or SupplierAddress or CustomerAddress, etc
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($this->getAddressDetail() !== null) {
            $node->addChild(static::N_ADDRESSDETAIL, $this->getAddressDetail());
        } elseif ($this->getStreetName() !== null) {
            $node->addChild(static::N_STREETNAME, $this->getStreetName());
            $addr = $this->getStreetName();
            if ($this->getBuildingNumber() !== null) {
                $node->addChild(
                    static::N_BUILDINGNUMBER,
                    $this->getBuildingNumber()
                );
                $addr .= " ".$this->getBuildingNumber();
            }
            $node->addChild(static::N_ADDRESSDETAIL, $addr);
        } else {
            $node->addChild(static::N_ADDRESSDETAIL);
            $this->getErrorRegistor()->addOnCreateXmlNode("AddressDetail_not_valid");
        }

        if (isset($this->city)) {
            $node->addChild(static::N_CITY, $this->getCity());
        } else {
            $node->addChild(static::N_CITY);
            $this->getErrorRegistor()->addOnCreateXmlNode("City_not_valid");
        }

        try {
            if ($this instanceof Address || $this instanceof SupplierAddress) {
                $node->addChild(static::N_POSTALCODE, $this->getPostalCode());
            } elseif ($this instanceof AddressPT) {
                $node->addChild(static::N_POSTALCODE, $this->getPostalCode());
            } else {
                $msg = "unknown address class instance to get the postal code";
                throw new AuditFileException($msg);
            }
        } catch (\Exception | \Error $e) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $node->addChild(static::N_POSTALCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("PostalCode_not_valid");
        }

        if ($this->getRegion() !== null) {
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
        if ($node->{static::N_STREETNAME}->count() > 0) {
            $this->setStreetName((string) $node->{static::N_STREETNAME});
        }
        if ($node->{static::N_BUILDINGNUMBER}->count() > 0) {
            $this->setBuildingNumber((string) $node->{static::N_BUILDINGNUMBER});
        }
        $this->setAddressDetail((string) $node->{static::N_ADDRESSDETAIL});
        $this->setCity((string) $node->{static::N_CITY});
        if ($node->{static::N_REGION}->count() > 0) {
            $this->setRegion((string) $node->{static::N_REGION});
        }
    }
}
