<?php

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

/**
 * Class representing Invoice
 */
class Invoice extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * <xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     * Node Name
     */
    const N_INVOICE = "Invoice";

    /**
     * @var string $invoiceNo
     */
    private $invoiceNo = null;

    /**
     * @var string $aTCUD
     */
    private $aTCUD = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentStatusAType $documentStatus
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
     * @var \DateTime $invoiceDate
     */
    private $invoiceDate = null;

    /**
     * @var string $invoiceType
     */
    private $invoiceType = null;

    /**
     * @var \Rebelo\SaftPt\SpecialRegimesType $specialRegimes
     */
    private $specialRegimes = null;

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
     * @var \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\LineAType[] $line
     */
    private $line = [
    ];

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentTotalsAType $documentTotals
     */
    private $documentTotals = null;

    /**
     * @var \Rebelo\SaftPt\WithholdingTaxType[] $withholdingTax
     */
    private $withholdingTax = [
    ];

    /**
     * Gets as invoiceNo
     *
     * @return string
     */
    public function getInvoiceNo()
    {
        return $this->invoiceNo;
    }

    /**
     * Sets a new invoiceNo
     *
     * @param string $invoiceNo
     * @return self
     */
    public function setInvoiceNo($invoiceNo)
    {
        $this->invoiceNo = $invoiceNo;
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
     * @return \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentStatusAType
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * Sets a new documentStatus
     *
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentStatusAType $documentStatus
     * @return self
     */
    public function setDocumentStatus(\Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentStatusAType $documentStatus)
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
     * Gets as invoiceDate
     *
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Sets a new invoiceDate
     *
     * @param \DateTime $invoiceDate
     * @return self
     */
    public function setInvoiceDate(\DateTime $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * Gets as invoiceType
     *
     * @return string
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    /**
     * Sets a new invoiceType
     *
     * @param string $invoiceType
     * @return self
     */
    public function setInvoiceType($invoiceType)
    {
        $this->invoiceType = $invoiceType;
        return $this;
    }

    /**
     * Gets as specialRegimes
     *
     * @return \Rebelo\SaftPt\SpecialRegimesType
     */
    public function getSpecialRegimes()
    {
        return $this->specialRegimes;
    }

    /**
     * Sets a new specialRegimes
     *
     * @param \Rebelo\SaftPt\SpecialRegimesType $specialRegimes
     * @return self
     */
    public function setSpecialRegimes(\Rebelo\SaftPt\SpecialRegimesType $specialRegimes)
    {
        $this->specialRegimes = $specialRegimes;
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
     * Adds as line
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\LineAType $line
     */
    public function addToLine(\Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\LineAType $line)
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
     * @return \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\LineAType[]
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Sets a new line
     *
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\LineAType[] $line
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
     * @return \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentTotalsAType
     */
    public function getDocumentTotals()
    {
        return $this->documentTotals;
    }

    /**
     * Sets a new documentTotals
     *
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentTotalsAType $documentTotals
     * @return self
     */
    public function setDocumentTotals(\Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType\DocumentTotalsAType $documentTotals)
    {
        $this->documentTotals = $documentTotals;
        return $this;
    }

    /**
     * Adds as withholdingTax
     *
     * @return self
     * @param \Rebelo\SaftPt\WithholdingTaxType $withholdingTax
     */
    public function addToWithholdingTax(\Rebelo\SaftPt\WithholdingTaxType $withholdingTax)
    {
        $this->withholdingTax[] = $withholdingTax;
        return $this;
    }

    /**
     * isset withholdingTax
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWithholdingTax($index)
    {
        return isset($this->withholdingTax[$index]);
    }

    /**
     * unset withholdingTax
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWithholdingTax($index)
    {
        unset($this->withholdingTax[$index]);
    }

    /**
     * Gets as withholdingTax
     *
     * @return \Rebelo\SaftPt\WithholdingTaxType[]
     */
    public function getWithholdingTax()
    {
        return $this->withholdingTax;
    }

    /**
     * Sets a new withholdingTax
     *
     * @param \Rebelo\SaftPt\WithholdingTaxType[] $withholdingTax
     * @return self
     */
    public function setWithholdingTax(array $withholdingTax)
    {
        $this->withholdingTax = $withholdingTax;
        return $this;
    }

    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

    }

    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        
    }
}