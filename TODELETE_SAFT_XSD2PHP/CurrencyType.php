<?php

namespace Rebelo\SaftPt;

/**
 * Class representing CurrencyType
 *
 * 
 * XSD Type: Currency
 */
class CurrencyType
{

    /**
     * @var string $currencyCode
     */
    private $currencyCode = null;

    /**
     * @var float $currencyAmount
     */
    private $currencyAmount = null;

    /**
     * @var float $exchangeRate
     */
    private $exchangeRate = null;

    /**
     * Gets as currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Sets a new currencyCode
     *
     * @param string $currencyCode
     * @return self
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * Gets as currencyAmount
     *
     * @return float
     */
    public function getCurrencyAmount()
    {
        return $this->currencyAmount;
    }

    /**
     * Sets a new currencyAmount
     *
     * @param float $currencyAmount
     * @return self
     */
    public function setCurrencyAmount($currencyAmount)
    {
        $this->currencyAmount = $currencyAmount;
        return $this;
    }

    /**
     * Gets as exchangeRate
     *
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Sets a new exchangeRate
     *
     * @param float $exchangeRate
     * @return self
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }


}

