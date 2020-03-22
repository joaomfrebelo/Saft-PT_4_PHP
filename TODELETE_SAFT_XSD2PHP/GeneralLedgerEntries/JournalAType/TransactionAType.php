<?php

namespace Rebelo\SaftPt\GeneralLedgerEntries\JournalAType;

/**
 * Class representing TransactionAType
 */
class TransactionAType
{

    /**
     * @var string $transactionID
     */
    private $transactionID = null;

    /**
     * @var int $period
     */
    private $period = null;

    /**
     * @var \DateTime $transactionDate
     */
    private $transactionDate = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var string $docArchivalNumber
     */
    private $docArchivalNumber = null;

    /**
     * @var string $transactionType
     */
    private $transactionType = null;

    /**
     * @var \DateTime $gLPostingDate
     */
    private $gLPostingDate = null;

    /**
     * @var string $customerID
     */
    private $customerID = null;

    /**
     * @var string $supplierID
     */
    private $supplierID = null;

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType $lines
     */
    private $lines = null;

    /**
     * Gets as transactionID
     *
     * @return string
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * Sets a new transactionID
     *
     * @param string $transactionID
     * @return self
     */
    public function setTransactionID($transactionID)
    {
        $this->transactionID = $transactionID;
        return $this;
    }

    /**
     * Gets as period
     *
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Sets a new period
     *
     * @param int $period
     * @return self
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * Gets as transactionDate
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Sets a new transactionDate
     *
     * @param \DateTime $transactionDate
     * @return self
     */
    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;
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
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as docArchivalNumber
     *
     * @return string
     */
    public function getDocArchivalNumber()
    {
        return $this->docArchivalNumber;
    }

    /**
     * Sets a new docArchivalNumber
     *
     * @param string $docArchivalNumber
     * @return self
     */
    public function setDocArchivalNumber($docArchivalNumber)
    {
        $this->docArchivalNumber = $docArchivalNumber;
        return $this;
    }

    /**
     * Gets as transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Sets a new transactionType
     *
     * @param string $transactionType
     * @return self
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * Gets as gLPostingDate
     *
     * @return \DateTime
     */
    public function getGLPostingDate()
    {
        return $this->gLPostingDate;
    }

    /**
     * Sets a new gLPostingDate
     *
     * @param \DateTime $gLPostingDate
     * @return self
     */
    public function setGLPostingDate(\DateTime $gLPostingDate)
    {
        $this->gLPostingDate = $gLPostingDate;
        return $this;
    }

    /**
     * Gets as customerID
     *
     * @return string
     */
    public function getCustomerID()
    {
        return $this->customerID;
    }

    /**
     * Sets a new customerID
     *
     * @param string $customerID
     * @return self
     */
    public function setCustomerID($customerID)
    {
        $this->customerID = $customerID;
        return $this;
    }

    /**
     * Gets as supplierID
     *
     * @return string
     */
    public function getSupplierID()
    {
        return $this->supplierID;
    }

    /**
     * Sets a new supplierID
     *
     * @param string $supplierID
     * @return self
     */
    public function setSupplierID($supplierID)
    {
        $this->supplierID = $supplierID;
        return $this;
    }

    /**
     * Gets as lines
     *
     * @return \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Sets a new lines
     *
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType $lines
     * @return self
     */
    public function setLines(\Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType $lines)
    {
        $this->lines = $lines;
        return $this;
    }


}

