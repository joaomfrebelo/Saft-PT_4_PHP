<?php

namespace Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType;

/**
 * Class representing WorkDocumentAType
 */
class WorkDocumentAType
{

    /**
     * @var string $documentNumber
     */
    private $documentNumber = null;

    /**
     * @var string $aTCUD
     */
    private $aTCUD = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentStatusAType $documentStatus
     */
    private $documentStatus = null;

    /**
     * @var string $hash
     */
    private $hash = null;

    /**
     * @var string $hashControl
     */
    private $hashControl = null;

    /**
     * @var int $period
     */
    private $period = null;

    /**
     * @var \DateTime $workDate
     */
    private $workDate = null;

    /**
     * @var string $workType
     */
    private $workType = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $eACCode
     */
    private $eACCode = null;

    /**
     * @var \DateTime $systemEntryDate
     */
    private $systemEntryDate = null;

    /**
     * @var string $transactionID
     */
    private $transactionID = null;

    /**
     * @var string $customerID
     */
    private $customerID = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\LineAType[] $line
     */
    private $line = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentTotalsAType $documentTotals
     */
    private $documentTotals = null;

    /**
     * Gets as documentNumber
     *
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Sets a new documentNumber
     *
     * @param string $documentNumber
     * @return self
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * Gets as aTCUD
     *
     * @return string
     */
    public function getATCUD()
    {
        return $this->aTCUD;
    }

    /**
     * Sets a new aTCUD
     *
     * @param string $aTCUD
     * @return self
     */
    public function setATCUD($aTCUD)
    {
        $this->aTCUD = $aTCUD;
        return $this;
    }

    /**
     * Gets as documentStatus
     *
     * @return \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentStatusAType
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * Sets a new documentStatus
     *
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentStatusAType $documentStatus
     * @return self
     */
    public function setDocumentStatus(\Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentStatusAType $documentStatus)
    {
        $this->documentStatus = $documentStatus;
        return $this;
    }

    /**
     * Gets as hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Sets a new hash
     *
     * @param string $hash
     * @return self
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * Gets as hashControl
     *
     * @return string
     */
    public function getHashControl()
    {
        return $this->hashControl;
    }

    /**
     * Sets a new hashControl
     *
     * @param string $hashControl
     * @return self
     */
    public function setHashControl($hashControl)
    {
        $this->hashControl = $hashControl;
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
     * Gets as workDate
     *
     * @return \DateTime
     */
    public function getWorkDate()
    {
        return $this->workDate;
    }

    /**
     * Sets a new workDate
     *
     * @param \DateTime $workDate
     * @return self
     */
    public function setWorkDate(\DateTime $workDate)
    {
        $this->workDate = $workDate;
        return $this;
    }

    /**
     * Gets as workType
     *
     * @return string
     */
    public function getWorkType()
    {
        return $this->workType;
    }

    /**
     * Sets a new workType
     *
     * @param string $workType
     * @return self
     */
    public function setWorkType($workType)
    {
        $this->workType = $workType;
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
     * Gets as eACCode
     *
     * @return string
     */
    public function getEACCode()
    {
        return $this->eACCode;
    }

    /**
     * Sets a new eACCode
     *
     * @param string $eACCode
     * @return self
     */
    public function setEACCode($eACCode)
    {
        $this->eACCode = $eACCode;
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
     * Adds as line
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\LineAType $line
     */
    public function addToLine(\Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\LineAType $line)
    {
        $this->line[] = $line;
        return $this;
    }

    /**
     * isset line
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLine($index)
    {
        return isset($this->line[$index]);
    }

    /**
     * unset line
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLine($index)
    {
        unset($this->line[$index]);
    }

    /**
     * Gets as line
     *
     * @return \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\LineAType[]
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Sets a new line
     *
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\LineAType[] $line
     * @return self
     */
    public function setLine(array $line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * Gets as documentTotals
     *
     * @return \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentTotalsAType
     */
    public function getDocumentTotals()
    {
        return $this->documentTotals;
    }

    /**
     * Sets a new documentTotals
     *
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentTotalsAType $documentTotals
     * @return self
     */
    public function setDocumentTotals(\Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType\DocumentTotalsAType $documentTotals)
    {
        $this->documentTotals = $documentTotals;
        return $this;
    }


}

