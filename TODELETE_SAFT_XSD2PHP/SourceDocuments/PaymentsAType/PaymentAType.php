<?php

namespace Rebelo\SaftPt\SourceDocuments\PaymentsAType;

/**
 * Class representing PaymentAType
 */
class PaymentAType
{

    /**
     * @var string $paymentRefNo
     */
    private $paymentRefNo = null;

    /**
     * @var string $aTCUD
     */
    private $aTCUD = null;

    /**
     * @var int $period
     */
    private $period = null;

    /**
     * @var string $transactionID
     */
    private $transactionID = null;

    /**
     * @var \DateTime $transactionDate
     */
    private $transactionDate = null;

    /**
     * @var string $paymentType
     */
    private $paymentType = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var string $systemID
     */
    private $systemID = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentStatusAType $documentStatus
     */
    private $documentStatus = null;

    /**
     * @var \Rebelo\SaftPt\PaymentMethodType[] $paymentMethod
     */
    private $paymentMethod = [
        
    ];

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var \DateTime $systemEntryDate
     */
    private $systemEntryDate = null;

    /**
     * @var string $customerID
     */
    private $customerID = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType[] $line
     */
    private $line = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType $documentTotals
     */
    private $documentTotals = null;

    /**
     * @var \Rebelo\SaftPt\WithholdingTaxType[] $withholdingTax
     */
    private $withholdingTax = [
        
    ];

    /**
     * Gets as paymentRefNo
     *
     * @return string
     */
    public function getPaymentRefNo()
    {
        return $this->paymentRefNo;
    }

    /**
     * Sets a new paymentRefNo
     *
     * @param string $paymentRefNo
     * @return self
     */
    public function setPaymentRefNo($paymentRefNo)
    {
        $this->paymentRefNo = $paymentRefNo;
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
     * Gets as paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Sets a new paymentType
     *
     * @param string $paymentType
     * @return self
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
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
     * Gets as systemID
     *
     * @return string
     */
    public function getSystemID()
    {
        return $this->systemID;
    }

    /**
     * Sets a new systemID
     *
     * @param string $systemID
     * @return self
     */
    public function setSystemID($systemID)
    {
        $this->systemID = $systemID;
        return $this;
    }

    /**
     * Gets as documentStatus
     *
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentStatusAType
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * Sets a new documentStatus
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentStatusAType $documentStatus
     * @return self
     */
    public function setDocumentStatus(\Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentStatusAType $documentStatus)
    {
        $this->documentStatus = $documentStatus;
        return $this;
    }

    /**
     * Adds as paymentMethod
     *
     * @return self
     * @param \Rebelo\SaftPt\PaymentMethodType $paymentMethod
     */
    public function addToPaymentMethod(\Rebelo\SaftPt\PaymentMethodType $paymentMethod)
    {
        $this->paymentMethod[] = $paymentMethod;
        return $this;
    }

    /**
     * isset paymentMethod
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPaymentMethod($index)
    {
        return isset($this->paymentMethod[$index]);
    }

    /**
     * unset paymentMethod
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPaymentMethod($index)
    {
        unset($this->paymentMethod[$index]);
    }

    /**
     * Gets as paymentMethod
     *
     * @return \Rebelo\SaftPt\PaymentMethodType[]
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Sets a new paymentMethod
     *
     * @param \Rebelo\SaftPt\PaymentMethodType[] $paymentMethod
     * @return self
     */
    public function setPaymentMethod(array $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
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
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType $line
     */
    public function addToLine(\Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType $line)
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
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType[]
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Sets a new line
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType[] $line
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
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType
     */
    public function getDocumentTotals()
    {
        return $this->documentTotals;
    }

    /**
     * Sets a new documentTotals
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType $documentTotals
     * @return self
     */
    public function setDocumentTotals(\Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType $documentTotals)
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


}

