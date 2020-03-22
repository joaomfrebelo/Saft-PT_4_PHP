<?php

namespace Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType;

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
     * @var \Rebelo\SaftPt\SettlementType[] $settlement
     */
    private $settlement = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\PaymentMethodType[] $payment
     */
    private $payment = [
        
    ];

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

    /**
     * Adds as settlement
     *
     * @return self
     * @param \Rebelo\SaftPt\SettlementType $settlement
     */
    public function addToSettlement(\Rebelo\SaftPt\SettlementType $settlement)
    {
        $this->settlement[] = $settlement;
        return $this;
    }

    /**
     * isset settlement
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSettlement($index)
    {
        return isset($this->settlement[$index]);
    }

    /**
     * unset settlement
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSettlement($index)
    {
        unset($this->settlement[$index]);
    }

    /**
     * Gets as settlement
     *
     * @return \Rebelo\SaftPt\SettlementType[]
     */
    public function getSettlement()
    {
        return $this->settlement;
    }

    /**
     * Sets a new settlement
     *
     * @param \Rebelo\SaftPt\SettlementType[] $settlement
     * @return self
     */
    public function setSettlement(array $settlement)
    {
        $this->settlement = $settlement;
        return $this;
    }

    /**
     * Adds as payment
     *
     * @return self
     * @param \Rebelo\SaftPt\PaymentMethodType $payment
     */
    public function addToPayment(\Rebelo\SaftPt\PaymentMethodType $payment)
    {
        $this->payment[] = $payment;
        return $this;
    }

    /**
     * isset payment
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPayment($index)
    {
        return isset($this->payment[$index]);
    }

    /**
     * unset payment
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPayment($index)
    {
        unset($this->payment[$index]);
    }

    /**
     * Gets as payment
     *
     * @return \Rebelo\SaftPt\PaymentMethodType[]
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Sets a new payment
     *
     * @param \Rebelo\SaftPt\PaymentMethodType[] $payment
     * @return self
     */
    public function setPayment(array $payment)
    {
        $this->payment = $payment;
        return $this;
    }


}

