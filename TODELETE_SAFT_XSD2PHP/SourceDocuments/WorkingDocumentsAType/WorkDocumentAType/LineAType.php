<?php

namespace Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType;

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
     * @var \Rebelo\SaftPt\OrderReferencesType[] $orderReferences
     */
    private $orderReferences = [
        
    ];

    /**
     * @var string $productCode
     */
    private $productCode = null;

    /**
     * @var string $productDescription
     */
    private $productDescription = null;

    /**
     * @var float $quantity
     */
    private $quantity = null;

    /**
     * @var string $unitOfMeasure
     */
    private $unitOfMeasure = null;

    /**
     * @var float $unitPrice
     */
    private $unitPrice = null;

    /**
     * @var float $taxBase
     */
    private $taxBase = null;

    /**
     * @var \DateTime $taxPointDate
     */
    private $taxPointDate = null;

    /**
     * @var \Rebelo\SaftPt\ReferencesType[] $references
     */
    private $references = [
        
    ];

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var string[] $productSerialNumber
     */
    private $productSerialNumber = null;

    /**
     * @var float $debitAmount
     */
    private $debitAmount = null;

    /**
     * @var float $creditAmount
     */
    private $creditAmount = null;

    /**
     * @var \Rebelo\SaftPt\TaxType $tax
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
     * @var float $settlementAmount
     */
    private $settlementAmount = null;

    /**
     * @var \Rebelo\SaftPt\CustomsInformationType $customsInformation
     */
    private $customsInformation = null;

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
     * Adds as orderReferences
     *
     * @return self
     * @param \Rebelo\SaftPt\OrderReferencesType $orderReferences
     */
    public function addToOrderReferences(\Rebelo\SaftPt\OrderReferencesType $orderReferences)
    {
        $this->orderReferences[] = $orderReferences;
        return $this;
    }

    /**
     * isset orderReferences
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOrderReferences($index)
    {
        return isset($this->orderReferences[$index]);
    }

    /**
     * unset orderReferences
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOrderReferences($index)
    {
        unset($this->orderReferences[$index]);
    }

    /**
     * Gets as orderReferences
     *
     * @return \Rebelo\SaftPt\OrderReferencesType[]
     */
    public function getOrderReferences()
    {
        return $this->orderReferences;
    }

    /**
     * Sets a new orderReferences
     *
     * @param \Rebelo\SaftPt\OrderReferencesType[] $orderReferences
     * @return self
     */
    public function setOrderReferences(array $orderReferences)
    {
        $this->orderReferences = $orderReferences;
        return $this;
    }

    /**
     * Gets as productCode
     *
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * Sets a new productCode
     *
     * @param string $productCode
     * @return self
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * Gets as productDescription
     *
     * @return string
     */
    public function getProductDescription()
    {
        return $this->productDescription;
    }

    /**
     * Sets a new productDescription
     *
     * @param string $productDescription
     * @return self
     */
    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    /**
     * Gets as quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Sets a new quantity
     *
     * @param float $quantity
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Gets as unitOfMeasure
     *
     * @return string
     */
    public function getUnitOfMeasure()
    {
        return $this->unitOfMeasure;
    }

    /**
     * Sets a new unitOfMeasure
     *
     * @param string $unitOfMeasure
     * @return self
     */
    public function setUnitOfMeasure($unitOfMeasure)
    {
        $this->unitOfMeasure = $unitOfMeasure;
        return $this;
    }

    /**
     * Gets as unitPrice
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Sets a new unitPrice
     *
     * @param float $unitPrice
     * @return self
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * Gets as taxBase
     *
     * @return float
     */
    public function getTaxBase()
    {
        return $this->taxBase;
    }

    /**
     * Sets a new taxBase
     *
     * @param float $taxBase
     * @return self
     */
    public function setTaxBase($taxBase)
    {
        $this->taxBase = $taxBase;
        return $this;
    }

    /**
     * Gets as taxPointDate
     *
     * @return \DateTime
     */
    public function getTaxPointDate()
    {
        return $this->taxPointDate;
    }

    /**
     * Sets a new taxPointDate
     *
     * @param \DateTime $taxPointDate
     * @return self
     */
    public function setTaxPointDate(\DateTime $taxPointDate)
    {
        $this->taxPointDate = $taxPointDate;
        return $this;
    }

    /**
     * Adds as references
     *
     * @return self
     * @param \Rebelo\SaftPt\ReferencesType $references
     */
    public function addToReferences(\Rebelo\SaftPt\ReferencesType $references)
    {
        $this->references[] = $references;
        return $this;
    }

    /**
     * isset references
     *
     * @param int|string $index
     * @return bool
     */
    public function issetReferences($index)
    {
        return isset($this->references[$index]);
    }

    /**
     * unset references
     *
     * @param int|string $index
     * @return void
     */
    public function unsetReferences($index)
    {
        unset($this->references[$index]);
    }

    /**
     * Gets as references
     *
     * @return \Rebelo\SaftPt\ReferencesType[]
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Sets a new references
     *
     * @param \Rebelo\SaftPt\ReferencesType[] $references
     * @return self
     */
    public function setReferences(array $references)
    {
        $this->references = $references;
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
     * Adds as serialNumber
     *
     * @return self
     * @param string $serialNumber
     */
    public function addToProductSerialNumber($serialNumber)
    {
        $this->productSerialNumber[] = $serialNumber;
        return $this;
    }

    /**
     * isset productSerialNumber
     *
     * @param int|string $index
     * @return bool
     */
    public function issetProductSerialNumber($index)
    {
        return isset($this->productSerialNumber[$index]);
    }

    /**
     * unset productSerialNumber
     *
     * @param int|string $index
     * @return void
     */
    public function unsetProductSerialNumber($index)
    {
        unset($this->productSerialNumber[$index]);
    }

    /**
     * Gets as productSerialNumber
     *
     * @return string[]
     */
    public function getProductSerialNumber()
    {
        return $this->productSerialNumber;
    }

    /**
     * Sets a new productSerialNumber
     *
     * @param string $productSerialNumber
     * @return self
     */
    public function setProductSerialNumber(array $productSerialNumber)
    {
        $this->productSerialNumber = $productSerialNumber;
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
     * @return \Rebelo\SaftPt\TaxType
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Sets a new tax
     *
     * @param \Rebelo\SaftPt\TaxType $tax
     * @return self
     */
    public function setTax(\Rebelo\SaftPt\TaxType $tax)
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
     * Gets as customsInformation
     *
     * @return \Rebelo\SaftPt\CustomsInformationType
     */
    public function getCustomsInformation()
    {
        return $this->customsInformation;
    }

    /**
     * Sets a new customsInformation
     *
     * @param \Rebelo\SaftPt\CustomsInformationType $customsInformation
     * @return self
     */
    public function setCustomsInformation(\Rebelo\SaftPt\CustomsInformationType $customsInformation)
    {
        $this->customsInformation = $customsInformation;
        return $this;
    }


}

