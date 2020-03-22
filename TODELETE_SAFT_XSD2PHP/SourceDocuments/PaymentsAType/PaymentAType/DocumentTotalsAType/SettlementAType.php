<?php

namespace Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType;

/**
 * Class representing SettlementAType
 */
class SettlementAType
{

    /**
     * @var float $settlementAmount
     */
    private $settlementAmount = null;

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


}

