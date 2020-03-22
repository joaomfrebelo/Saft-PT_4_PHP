<?php

namespace Rebelo\SaftPt;

/**
 * Class representing AddressStructurePTType
 *
 * 
 * XSD Type: AddressStructurePT
 */
class AddressStructurePTType
{

    /**
     * @var string $buildingNumber
     */
    private $buildingNumber = null;

    /**
     * @var string $streetName
     */
    private $streetName = null;

    /**
     * @var string $addressDetail
     */
    private $addressDetail = null;

    /**
     * @var string $city
     */
    private $city = null;

    /**
     * @var string $postalCode
     */
    private $postalCode = null;

    /**
     * @var string $region
     */
    private $region = null;

    /**
     * @var mixed $country
     */
    private $country = null;

    /**
     * Gets as buildingNumber
     *
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }

    /**
     * Sets a new buildingNumber
     *
     * @param string $buildingNumber
     * @return self
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * Gets as streetName
     *
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Sets a new streetName
     *
     * @param string $streetName
     * @return self
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;
        return $this;
    }

    /**
     * Gets as addressDetail
     *
     * @return string
     */
    public function getAddressDetail()
    {
        return $this->addressDetail;
    }

    /**
     * Sets a new addressDetail
     *
     * @param string $addressDetail
     * @return self
     */
    public function setAddressDetail($addressDetail)
    {
        $this->addressDetail = $addressDetail;
        return $this;
    }

    /**
     * Gets as city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets a new city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Gets as postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Sets a new postalCode
     *
     * @param string $postalCode
     * @return self
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * Gets as region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets a new region
     *
     * @param string $region
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Gets as country
     *
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * @param mixed $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }


}

