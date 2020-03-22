<?php

namespace Rebelo\SaftPt;

/**
 * Class representing PaymentMethodType
 *
 * 
 * XSD Type: PaymentMethod
 */
class PaymentMethodType
{

    /**
     * @var string $paymentMechanism
     */
    private $paymentMechanism = null;

    /**
     * @var float $paymentAmount
     */
    private $paymentAmount = null;

    /**
     * @var \DateTime $paymentDate
     */
    private $paymentDate = null;

    /**
     * Gets as paymentMechanism
     *
     * @return string
     */
    public function getPaymentMechanism()
    {
        return $this->paymentMechanism;
    }

    /**
     * Sets a new paymentMechanism
     *
     * @param string $paymentMechanism
     * @return self
     */
    public function setPaymentMechanism($paymentMechanism)
    {
        $this->paymentMechanism = $paymentMechanism;
        return $this;
    }

    /**
     * Gets as paymentAmount
     *
     * @return float
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Sets a new paymentAmount
     *
     * @param float $paymentAmount
     * @return self
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

    /**
     * Gets as paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Sets a new paymentDate
     *
     * @param \DateTime $paymentDate
     * @return self
     */
    public function setPaymentDate(\DateTime $paymentDate)
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }


}

