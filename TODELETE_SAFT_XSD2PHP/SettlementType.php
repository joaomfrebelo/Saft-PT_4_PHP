<?php

namespace Rebelo\SaftPt;

/**
 * Class representing SettlementType
 *
 * 
 * XSD Type: Settlement
 */
class SettlementType
{

    /**
     * @var string $settlementDiscount
     */
    private $settlementDiscount = null;

    /**
     * @var float $settlementAmount
     */
    private $settlementAmount = null;

    /**
     * @var \DateTime $settlementDate
     */
    private $settlementDate = null;

    /**
     * @var string $paymentTerms
     */
    private $paymentTerms = null;

    /**
     * Gets as settlementDiscount
     *
     * @return string
     */
    public function getSettlementDiscount()
    {
        return $this->settlementDiscount;
    }

    /**
     * Sets a new settlementDiscount
     *
     * @param string $settlementDiscount
     * @return self
     */
    public function setSettlementDiscount($settlementDiscount)
    {
        $this->settlementDiscount = $settlementDiscount;
        return $this;
    }

    /**
     * Gets as settlementAmount
     *
     * @return float
     */
    public function getSettlementAmount()
    {
        return $this->settlementAmount;
    }

    /**
     * Sets a new settlementAmount
     *
     * @param float $settlementAmount
     * @return self
     */
    public function setSettlementAmount($settlementAmount)
    {
        $this->settlementAmount = $settlementAmount;
        return $this;
    }

    /**
     * Gets as settlementDate
     *
     * @return \DateTime
     */
    public function getSettlementDate()
    {
        return $this->settlementDate;
    }

    /**
     * Sets a new settlementDate
     *
     * @param \DateTime $settlementDate
     * @return self
     */
    public function setSettlementDate(\DateTime $settlementDate)
    {
        $this->settlementDate = $settlementDate;
        return $this;
    }

    /**
     * Gets as paymentTerms
     *
     * @return string
     */
    public function getPaymentTerms()
    {
        return $this->paymentTerms;
    }

    /**
     * Sets a new paymentTerms
     *
     * @param string $paymentTerms
     * @return self
     */
    public function setPaymentTerms($paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }


}

