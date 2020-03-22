<?php

namespace Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType;

/**
 * Class representing LineAType
 */
class LineAType
{

    /**
     * @var int $lineNumber
     */
    private $lineNumber = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType\SourceDocumentIDAType[] $sourceDocumentID
     */
    private $sourceDocumentID = [
        
    ];

    /**
     * @var float $settlementAmount
     */
    private $settlementAmount = null;

    /**
     * @var float $debitAmount
     */
    private $debitAmount = null;

    /**
     * @var float $creditAmount
     */
    private $creditAmount = null;

    /**
     * @var \Rebelo\SaftPt\PaymentTaxType $tax
     */
    private $tax = null;

    /**
     * @var string $taxExemptionReason
     */
    private $taxExemptionReason = null;

    /**
     * @var string $taxExemptionCode
     */
    private $taxExemptionCode = null;

    /**
     * Gets as lineNumber
     *
     * @return int
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * @param int $lineNumber
     * @return self
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Adds as sourceDocumentID
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType\SourceDocumentIDAType $sourceDocumentID
     */
    public function addToSourceDocumentID(\Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType\SourceDocumentIDAType $sourceDocumentID)
    {
        $this->sourceDocumentID[] = $sourceDocumentID;
        return $this;
    }

    /**
     * isset sourceDocumentID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSourceDocumentID($index)
    {
        return isset($this->sourceDocumentID[$index]);
    }

    /**
     * unset sourceDocumentID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSourceDocumentID($index)
    {
        unset($this->sourceDocumentID[$index]);
    }

    /**
     * Gets as sourceDocumentID
     *
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType\SourceDocumentIDAType[]
     */
    public function getSourceDocumentID()
    {
        return $this->sourceDocumentID;
    }

    /**
     * Sets a new sourceDocumentID
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType\SourceDocumentIDAType[] $sourceDocumentID
     * @return self
     */
    public function setSourceDocumentID(array $sourceDocumentID)
    {
        $this->sourceDocumentID = $sourceDocumentID;
        return $this;
    }

    /**
     * Gets as settlementAmount
     *
     * @return float
     */
    public function getSettlementAmount()
    {
        return $this->settlementAmount;
    }

    /**
     * Sets a new settlementAmount
     *
     * @param float $settlementAmount
     * @return self
     */
    public function setSettlementAmount($settlementAmount)
    {
        $this->settlementAmount = $settlementAmount;
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

    /**
     * Gets as creditAmount
     *
     * @return float
     */
    public function getCreditAmount()
    {
        return $this->creditAmount;
    }

    /**
     * Sets a new creditAmount
     *
     * @param float $creditAmount
     * @return self
     */
    public function setCreditAmount($creditAmount)
    {
        $this->creditAmount = $creditAmount;
        return $this;
    }

    /**
     * Gets as tax
     *
     * @return \Rebelo\SaftPt\PaymentTaxType
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Sets a new tax
     *
     * @param \Rebelo\SaftPt\PaymentTaxType $tax
     * @return self
     */
    public function setTax(\Rebelo\SaftPt\PaymentTaxType $tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * Gets as taxExemptionReason
     *
     * @return string
     */
    public function getTaxExemptionReason()
    {
        return $this->taxExemptionReason;
    }

    /**
     * Sets a new taxExemptionReason
     *
     * @param string $taxExemptionReason
     * @return self
     */
    public function setTaxExemptionReason($taxExemptionReason)
    {
        $this->taxExemptionReason = $taxExemptionReason;
        return $this;
    }

    /**
     * Gets as taxExemptionCode
     *
     * @return string
     */
    public function getTaxExemptionCode()
    {
        return $this->taxExemptionCode;
    }

    /**
     * Sets a new taxExemptionCode
     *
     * @param string $taxExemptionCode
     * @return self
     */
    public function setTaxExemptionCode($taxExemptionCode)
    {
        $this->taxExemptionCode = $taxExemptionCode;
        return $this;
    }


}

