<?php

namespace Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType;

/**
 * Class representing DebitLineAType
 */
class DebitLineAType
{

    /**
     * @var string $recordID
     */
    private $recordID = null;

    /**
     * @var string $accountID
     */
    private $accountID = null;

    /**
     * @var string $sourceDocumentID
     */
    private $sourceDocumentID = null;

    /**
     * @var \DateTime $systemEntryDate
     */
    private $systemEntryDate = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var float $debitAmount
     */
    private $debitAmount = null;

    /**
     * Gets as recordID
     *
     * @return string
     */
    public function getRecordID()
    {
        return $this->recordID;
    }

    /**
     * Sets a new recordID
     *
     * @param string $recordID
     * @return self
     */
    public function setRecordID($recordID)
    {
        $this->recordID = $recordID;
        return $this;
    }

    /**
     * Gets as accountID
     *
     * @return string
     */
    public function getAccountID()
    {
        return $this->accountID;
    }

    /**
     * Sets a new accountID
     *
     * @param string $accountID
     * @return self
     */
    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;
        return $this;
    }

    /**
     * Gets as sourceDocumentID
     *
     * @return string
     */
    public function getSourceDocumentID()
    {
        return $this->sourceDocumentID;
    }

    /**
     * Sets a new sourceDocumentID
     *
     * @param string $sourceDocumentID
     * @return self
     */
    public function setSourceDocumentID($sourceDocumentID)
    {
        $this->sourceDocumentID = $sourceDocumentID;
        return $this;
    }

    /**
     * Gets as systemEntryDate
     *
     * @return \DateTime
     */
    public function getSystemEntryDate()
    {
        return $this->systemEntryDate;
    }

    /**
     * Sets a new systemEntryDate
     *
     * @param \DateTime $systemEntryDate
     * @return self
     */
    public function setSystemEntryDate(\DateTime $systemEntryDate)
    {
        $this->systemEntryDate = $systemEntryDate;
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
     * Gets as debitAmount
     *
     * @return float
     */
    public function getDebitAmount()
    {
        return $this->debitAmount;
    }

    /**
     * Sets a new debitAmount
     *
     * @param float $debitAmount
     * @return self
     */
    public function setDebitAmount($debitAmount)
    {
        $this->debitAmount = $debitAmount;
        return $this;
    }


}

