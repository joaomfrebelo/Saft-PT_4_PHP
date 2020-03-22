<?php

namespace Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType;

/**
 * Class representing StockMovementAType
 */
class StockMovementAType
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
     * @var \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentStatusAType $documentStatus
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
     * @var \DateTime $movementDate
     */
    private $movementDate = null;

    /**
     * @var string $movementType
     */
    private $movementType = null;

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
     * @var string $supplierID
     */
    private $supplierID = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $eACCode
     */
    private $eACCode = null;

    /**
     * @var string $movementComments
     */
    private $movementComments = null;

    /**
     * @var \Rebelo\SaftPt\ShipTo $shipTo
     */
    private $shipTo = null;

    /**
     * @var \Rebelo\SaftPt\ShipFrom $shipFrom
     */
    private $shipFrom = null;

    /**
     * @var \DateTime $movementEndTime
     */
    private $movementEndTime = null;

    /**
     * @var \DateTime $movementStartTime
     */
    private $movementStartTime = null;

    /**
     * @var string $aTDocCodeID
     */
    private $aTDocCodeID = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\LineAType[] $line
     */
    private $line = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentTotalsAType $documentTotals
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
     * @return \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentStatusAType
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * Sets a new documentStatus
     *
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentStatusAType $documentStatus
     * @return self
     */
    public function setDocumentStatus(\Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentStatusAType $documentStatus)
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
     * Gets as movementDate
     *
     * @return \DateTime
     */
    public function getMovementDate()
    {
        return $this->movementDate;
    }

    /**
     * Sets a new movementDate
     *
     * @param \DateTime $movementDate
     * @return self
     */
    public function setMovementDate(\DateTime $movementDate)
    {
        $this->movementDate = $movementDate;
        return $this;
    }

    /**
     * Gets as movementType
     *
     * @return string
     */
    public function getMovementType()
    {
        return $this->movementType;
    }

    /**
     * Sets a new movementType
     *
     * @param string $movementType
     * @return self
     */
    public function setMovementType($movementType)
    {
        $this->movementType = $movementType;
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
     * Gets as movementComments
     *
     * @return string
     */
    public function getMovementComments()
    {
        return $this->movementComments;
    }

    /**
     * Sets a new movementComments
     *
     * @param string $movementComments
     * @return self
     */
    public function setMovementComments($movementComments)
    {
        $this->movementComments = $movementComments;
        return $this;
    }

    /**
     * Gets as shipTo
     *
     * @return \Rebelo\SaftPt\ShipTo
     */
    public function getShipTo()
    {
        return $this->shipTo;
    }

    /**
     * Sets a new shipTo
     *
     * @param \Rebelo\SaftPt\ShipTo $shipTo
     * @return self
     */
    public function setShipTo(\Rebelo\SaftPt\ShipTo $shipTo)
    {
        $this->shipTo = $shipTo;
        return $this;
    }

    /**
     * Gets as shipFrom
     *
     * @return \Rebelo\SaftPt\ShipFrom
     */
    public function getShipFrom()
    {
        return $this->shipFrom;
    }

    /**
     * Sets a new shipFrom
     *
     * @param \Rebelo\SaftPt\ShipFrom $shipFrom
     * @return self
     */
    public function setShipFrom(\Rebelo\SaftPt\ShipFrom $shipFrom)
    {
        $this->shipFrom = $shipFrom;
        return $this;
    }

    /**
     * Gets as movementEndTime
     *
     * @return \DateTime
     */
    public function getMovementEndTime()
    {
        return $this->movementEndTime;
    }

    /**
     * Sets a new movementEndTime
     *
     * @param \DateTime $movementEndTime
     * @return self
     */
    public function setMovementEndTime(\DateTime $movementEndTime)
    {
        $this->movementEndTime = $movementEndTime;
        return $this;
    }

    /**
     * Gets as movementStartTime
     *
     * @return \DateTime
     */
    public function getMovementStartTime()
    {
        return $this->movementStartTime;
    }

    /**
     * Sets a new movementStartTime
     *
     * @param \DateTime $movementStartTime
     * @return self
     */
    public function setMovementStartTime(\DateTime $movementStartTime)
    {
        $this->movementStartTime = $movementStartTime;
        return $this;
    }

    /**
     * Gets as aTDocCodeID
     *
     * @return string
     */
    public function getATDocCodeID()
    {
        return $this->aTDocCodeID;
    }

    /**
     * Sets a new aTDocCodeID
     *
     * @param string $aTDocCodeID
     * @return self
     */
    public function setATDocCodeID($aTDocCodeID)
    {
        $this->aTDocCodeID = $aTDocCodeID;
        return $this;
    }

    /**
     * Adds as line
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\LineAType $line
     */
    public function addToLine(\Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\LineAType $line)
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
     * @return \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\LineAType[]
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Sets a new line
     *
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\LineAType[] $line
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
     * @return \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentTotalsAType
     */
    public function getDocumentTotals()
    {
        return $this->documentTotals;
    }

    /**
     * Sets a new documentTotals
     *
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentTotalsAType $documentTotals
     * @return self
     */
    public function setDocumentTotals(\Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType\DocumentTotalsAType $documentTotals)
    {
        $this->documentTotals = $documentTotals;
        return $this;
    }


}

