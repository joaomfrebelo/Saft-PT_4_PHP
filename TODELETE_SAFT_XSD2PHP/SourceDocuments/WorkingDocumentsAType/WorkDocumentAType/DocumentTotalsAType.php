<?php

namespace Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType;

/**
 * Class representing DocumentTotalsAType
 */
class DocumentTotalsAType
{

    /**
     * @var float $taxPayable
     */
    private $taxPayable = null;

    /**
     * @var float $netTotal
     */
    private $netTotal = null;

    /**
     * @var float $grossTotal
     */
    private $grossTotal = null;

    /**
     * @var \Rebelo\SaftPt\CurrencyType $currency
     */
    private $currency = null;

    /**
     * Gets as taxPayable
     *
     * @return float
     */
    public function getTaxPayable()
    {
        return $this->taxPayable;
    }

    /**
     * Sets a new taxPayable
     *
     * @param float $taxPayable
     * @return self
     */
    public function setTaxPayable($taxPayable)
    {
        $this->taxPayable = $taxPayable;
        return $this;
    }

    /**
     * Gets as netTotal
     *
     * @return float
     */
    public function getNetTotal()
    {
        return $this->netTotal;
    }

    /**
     * Sets a new netTotal
     *
     * @param float $netTotal
     * @return self
     */
    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;
        return $this;
    }

    /**
     * Gets as grossTotal
     *
     * @return float
     */
    public function getGrossTotal()
    {
        return $this->grossTotal;
    }

    /**
     * Sets a new grossTotal
     *
     * @param float $grossTotal
     * @return self
     */
    public function setGrossTotal($grossTotal)
    {
        $this->grossTotal = $grossTotal;
        return $this;
    }

    /**
     * Gets as currency
     *
     * @return \Rebelo\SaftPt\CurrencyType
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets a new currency
     *
     * @param \Rebelo\SaftPt\CurrencyType $currency
     * @return self
     */
    public function setCurrency(\Rebelo\SaftPt\CurrencyType $currency)
    {
        $this->currency = $currency;
        return $this;
    }


}

