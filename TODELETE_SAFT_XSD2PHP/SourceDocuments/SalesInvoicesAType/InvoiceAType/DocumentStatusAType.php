<?php

namespace Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType;

/**
 * Class representing DocumentStatusAType
 */
class DocumentStatusAType
{

    /**
     * @var string $invoiceStatus
     */
    private $invoiceStatus = null;

    /**
     * @var \DateTime $invoiceStatusDate
     */
    private $invoiceStatusDate = null;

    /**
     * @var string $reason
     */
    private $reason = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $sourceBilling
     */
    private $sourceBilling = null;

    /**
     * Gets as invoiceStatus
     *
     * @return string
     */
    public function getInvoiceStatus()
    {
        return $this->invoiceStatus;
    }

    /**
     * Sets a new invoiceStatus
     *
     * @param string $invoiceStatus
     * @return self
     */
    public function setInvoiceStatus($invoiceStatus)
    {
        $this->invoiceStatus = $invoiceStatus;
        return $this;
    }

    /**
     * Gets as invoiceStatusDate
     *
     * @return \DateTime
     */
    public function getInvoiceStatusDate()
    {
        return $this->invoiceStatusDate;
    }

    /**
     * Sets a new invoiceStatusDate
     *
     * @param \DateTime $invoiceStatusDate
     * @return self
     */
    public function setInvoiceStatusDate(\DateTime $invoiceStatusDate)
    {
        $this->invoiceStatusDate = $invoiceStatusDate;
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
     * Gets as sourceBilling
     *
     * @return string
     */
    public function getSourceBilling()
    {
        return $this->sourceBilling;
    }

    /**
     * Sets a new sourceBilling
     *
     * @param string $sourceBilling
     * @return self
     */
    public function setSourceBilling($sourceBilling)
    {
        $this->sourceBilling = $sourceBilling;
        return $this;
    }


}

