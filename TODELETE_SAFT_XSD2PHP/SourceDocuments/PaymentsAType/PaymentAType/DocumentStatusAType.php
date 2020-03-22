<?php

namespace Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType;

/**
 * Class representing DocumentStatusAType
 */
class DocumentStatusAType
{

    /**
     * @var string $paymentStatus
     */
    private $paymentStatus = null;

    /**
     * @var \DateTime $paymentStatusDate
     */
    private $paymentStatusDate = null;

    /**
     * @var string $reason
     */
    private $reason = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $sourcePayment
     */
    private $sourcePayment = null;

    /**
     * Gets as paymentStatus
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Sets a new paymentStatus
     *
     * @param string $paymentStatus
     * @return self
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * Gets as paymentStatusDate
     *
     * @return \DateTime
     */
    public function getPaymentStatusDate()
    {
        return $this->paymentStatusDate;
    }

    /**
     * Sets a new paymentStatusDate
     *
     * @param \DateTime $paymentStatusDate
     * @return self
     */
    public function setPaymentStatusDate(\DateTime $paymentStatusDate)
    {
        $this->paymentStatusDate = $paymentStatusDate;
        return $this;
    }

    /**
     * Gets as reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * @param string $reason
     * @return self
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Gets as sourceID
     *
     * @return string
     */
    public function getSourceID()
    {
        return $this->sourceID;
    }

    /**
     * Sets a new sourceID
     *
     * @param string $sourceID
     * @return self
     */
    public function setSourceID($sourceID)
    {
        $this->sourceID = $sourceID;
        return $this;
    }

    /**
     * Gets as sourcePayment
     *
     * @return string
     */
    public function getSourcePayment()
    {
        return $this->sourcePayment;
    }

    /**
     * Sets a new sourcePayment
     *
     * @param string $sourcePayment
     * @return self
     */
    public function setSourcePayment($sourcePayment)
    {
        $this->sourcePayment = $sourcePayment;
        return $this;
    }


}

