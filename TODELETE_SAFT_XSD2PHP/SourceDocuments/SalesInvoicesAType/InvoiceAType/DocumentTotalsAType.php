<?php

namespace Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType;

/**
 * Class representing DocumentTotalsAType
 */
class DocumentTotalsAType
{

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
