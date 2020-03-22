<?php

namespace Rebelo\SaftPt;

/**
 * Class representing TaxTableEntry
 */
class TaxTableEntry
{

    /**
     * @var string $taxType
     */
    private $taxType = null;

    /**
     * @var string $taxCountryRegion
     */
    private $taxCountryRegion = null;

    /**
     * @var string $taxCode
     */
    private $taxCode = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var \DateTime $taxExpirationDate
     */
    private $taxExpirationDate = null;

    /**
     * @var float $taxPercentage
     */
    private $taxPercentage = null;

    /**
     * @var float $taxAmount
     */
    private $taxAmount = null;

    /**
     * Gets as taxType
     *
     * @return string
     */
    public function getTaxType()
    {
        return $this->taxType;
    }

    /**
     * Sets a new taxType
     *
     * @param string $taxType
     * @return self
     */
    public function setTaxType($taxType)
    {
        $this->taxType = $taxType;
        return $this;
    }

    /**
     * Gets as taxCountryRegion
     *
     * @return string
     */
    public function getTaxCountryRegion()
    {
        return $this->taxCountryRegion;
    }

    /**
     * Sets a new taxCountryRegion
     *
     * @param string $taxCountryRegion
     * @return self
     */
    public function setTaxCountryRegion($taxCountryRegion)
    {
        $this->taxCountryRegion = $taxCountryRegion;
        return $this;
    }

    /**
     * Gets as taxCode
     *
     * @return string
     */
    public function getTaxCode()
    {
        return $this->taxCode;
    }

    /**
     * Sets a new taxCode
     *
     * @param string $taxCode
     * @return self
     */
    public function setTaxCode($taxCode)
    {
        $this->taxCode = $taxCode;
        return $this;
    }

    /**
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as taxExpirationDate
     *
     * @return \DateTime
     */
    public function getTaxExpirationDate()
    {
        return $this->taxExpirationDate;
    }

    /**
     * Sets a new taxExpirationDate
     *
     * @param \DateTime $taxExpirationDate
     * @return self
     */
    public function setTaxExpirationDate(\DateTime $taxExpirationDate)
    {
        $this->taxExpirationDate = $taxExpirationDate;
        return $this;
    }

    /**
     * Gets as taxPercentage
     *
     * @return float
     */
    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    /**
     * Sets a new taxPercentage
     *
     * @param float $taxPercentage
     * @return self
     */
    public function setTaxPercentage($taxPercentage)
    {
        $this->taxPercentage = $taxPercentage;
        return $this;
    }

    /**
     * Gets as taxAmount
     *
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * Sets a new taxAmount
     *
     * @param float $taxAmount
     * @return self
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }


}

