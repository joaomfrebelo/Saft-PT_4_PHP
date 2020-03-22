<?php

namespace Rebelo\SaftPt\SourceDocuments;

/**
 * Class representing PaymentsAType
 */
class PaymentsAType
{

    /**
     * @var int $numberOfEntries
     */
    private $numberOfEntries = null;

    /**
     * @var float $totalDebit
     */
    private $totalDebit = null;

    /**
     * @var float $totalCredit
     */
    private $totalCredit = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType[] $payment
     */
    private $payment = [
        
    ];

    /**
     * Gets as numberOfEntries
     *
     * @return int
     */
    public function getNumberOfEntries()
    {
        return $this->numberOfEntries;
    }

    /**
     * Sets a new numberOfEntries
     *
     * @param int $numberOfEntries
     * @return self
     */
    public function setNumberOfEntries($numberOfEntries)
    {
        $this->numberOfEntries = $numberOfEntries;
        return $this;
    }

    /**
     * Gets as totalDebit
     *
     * @return float
     */
    public function getTotalDebit()
    {
        return $this->totalDebit;
    }

    /**
     * Sets a new totalDebit
     *
     * @param float $totalDebit
     * @return self
     */
    public function setTotalDebit($totalDebit)
    {
        $this->totalDebit = $totalDebit;
        return $this;
    }

    /**
     * Gets as totalCredit
     *
     * @return float
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * Sets a new totalCredit
     *
     * @param float $totalCredit
     * @return self
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

    /**
     * Adds as payment
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType $payment
     */
    public function addToPayment(\Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType $payment)
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
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType[]
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Sets a new payment
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType[] $payment
     * @return self
     */
    public function setPayment(array $payment)
    {
        $this->payment = $payment;
        return $this;
    }


}

