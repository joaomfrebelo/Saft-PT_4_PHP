<?php

namespace Rebelo\SaftPt;

/**
 * Class representing WithholdingTaxType
 *
 * 
 * XSD Type: WithholdingTax
 */
class WithholdingTaxType
{

    /**
     * @var string $withholdingTaxType
     */
    private $withholdingTaxType = null;

    /**
     * @var string $withholdingTaxDescription
     */
    private $withholdingTaxDescription = null;

    /**
     * @var float $withholdingTaxAmount
     */
    private $withholdingTaxAmount = null;

    /**
     * Gets as withholdingTaxType
     *
     * @return string
     */
    public function getWithholdingTaxType()
    {
        return $this->withholdingTaxType;
    }

    /**
     * Sets a new withholdingTaxType
     *
     * @param string $withholdingTaxType
     * @return self
     */
    public function setWithholdingTaxType($withholdingTaxType)
    {
        $this->withholdingTaxType = $withholdingTaxType;
        return $this;
    }

    /**
     * Gets as withholdingTaxDescription
     *
     * @return string
     */
    public function getWithholdingTaxDescription()
    {
        return $this->withholdingTaxDescription;
    }

    /**
     * Sets a new withholdingTaxDescription
     *
     * @param string $withholdingTaxDescription
     * @return self
     */
    public function setWithholdingTaxDescription($withholdingTaxDescription)
    {
        $this->withholdingTaxDescription = $withholdingTaxDescription;
        return $this;
    }

    /**
     * Gets as withholdingTaxAmount
     *
     * @return float
     */
    public function getWithholdingTaxAmount()
    {
        return $this->withholdingTaxAmount;
    }

    /**
     * Sets a new withholdingTaxAmount
     *
     * @param float $withholdingTaxAmount
     * @return self
     */
    public function setWithholdingTaxAmount($withholdingTaxAmount)
    {
        $this->withholdingTaxAmount = $withholdingTaxAmount;
        return $this;
    }


}

