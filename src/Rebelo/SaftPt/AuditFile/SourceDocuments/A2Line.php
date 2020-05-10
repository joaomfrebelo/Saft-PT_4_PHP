<?php
/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Function and properties
 * comumn to Invoice, Movement of Goods
 * and Working Documents
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class A2Line extends ALine
{
    /**
     * <xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/>
     * Node name
     * @since 1.0.0
     */
    const N_ORDERREFERENCES = "OrderReferences";

    /**
     * <xs:element ref="ProductCode"/>
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTCODE = "ProductCode";

    /**
     * <xs:element ref="ProductDescription"/>
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTDESCRIPTION = "ProductDescription";

    /**
     * <xs:element ref="Quantity"/>
     * Node name
     * @since 1.0.0
     */
    const N_QUANTITY = "Quantity";

    /**
     * <xs:element ref="UnitOfMeasure"/>
     * Node name
     * @since 1.0.0
     */
    const N_UNITOFMEASURE = "UnitOfMeasure";

    /**
     * <xs:element ref="UnitPrice"/>
     * Node name
     * @since 1.0.0
     */
    const N_UNITPRICE = "UnitPrice";

    /**
     * <xs:element ref="Description"/>
     * Node name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * <xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTSERIALNUMBER = "ProductSerialNumber";

    /**
     * <xs:element ref="DebitAmount"/>
     * Node name
     * @since 1.0.0
     */
    const N_DEBITAMOUNT = "DebitAmount";

    /**
     * <xs:element ref="CreditAmount"/>
     * Node name
     * @since 1.0.0
     */
    const N_CREDITAMOUNT = "CreditAmount";

    /**
     *
     * Node name
     * @since 1.0.0
     */
    const N_TAXEXEMPTIONREASON = "TaxExemptionReason";

    /**
     * <xs:element ref="DebitAmount"/>
     * Node name
     * @since 1.0.0
     */
    const N_TAXEXEMPTIONCODE = "TaxExemptionCode";

    /**
     * <xs:element ref="SettlementAmount" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_SETTLEMENTAMOUNT = "SettlementAmount";

    /**
     * <xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMSINFORMATION = "CustomsInformation";

    /**
     * <xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences[]
     * @since 1.0.0
     */
    private array $orderReferences = array();

    /**
     * <xs:element ref="ProductCode"/>
     * @var string
     * @since 1.0.0
     */
    private string $productCode;

    /**
     * <xs:element ref="ProductDescription"/>
     * @var string
     * @since 1.0.0
     */
    private string $productDescription;

    /**
     * <xs:element ref="Quantity"/>
     * @var float
     * @since 1.0.0
     */
    private float $quantity;

    /**
     * <xs:element ref="UnitOfMeasure"/>
     * @var string
     * @since 1.0.0
     */
    private string $unitOfMeasure;

    /**
     * <xs:element ref="UnitPrice"/>
     * @var float
     * @since 1.0.0
     */
    private float $unitPrice;

    /**
     * <xs:element ref="Description"/>
     * @var string
     * @since 1.0.0
     */
    private string $description;

    /**
     * <xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber|null
     * @since 1.0.0
     */
    private ?ProductSerialNumber $productSerialNumber = null;

    /**
     * <xs:element ref="TaxExemptionReason" minOccurs="0"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $taxExemptionReason = null;

    /**
     * <xs:element ref="TaxExemptionCode" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null
     * @since 1.0.0
     */
    private ?TaxExemptionCode $taxExemptionCode = null;

    /**
     * <xs:element ref="SettlementAmount" minOccurs="0"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $settlementAmount = null;

    /**
     * <xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation
     * @since 1.0.0
     */
    private ?CustomsInformation $customsInformation = null;

    /**
     * <xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences[]
     * @since 1.0.0
     */
    public function getOrderReferences(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted '%s'");
        return $this->orderReferences;
    }

    /**
     * <xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences $orderReferences
     * @return int
     * @since 1.0.0
     */
    public function addToOrderReferences(OrderReferences $orderReferences): int
    {
        if (\count($this->orderReferences) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->orderReferences);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->orderReferences[$index] = $orderReferences;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, "OrderReferences add to index ".\strval($index));
        return $index;
    }

    /**
     * isset OrderReferences
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetOrderReferences(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->orderReferences[$index]);
    }

    /**
     * unset OrderReferences
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetOrderReferences(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->orderReferences[$index]);
    }

    /**
     * <xs:element ref="ProductCode"/><br>
     * <xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getProductCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productCode));
        return $this->productCode;
    }

    /**
     * <xs:element ref="ProductCode"/><br>
     * <xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @param string $productCode
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductCode(string $productCode): void
    {
        $this->productCode = $this->valTextMandMaxCar($productCode, 60,
            __METHOD__, false);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->productCode));
    }

    /**
     * Get ProductDescription
     * <pre>
     * &lt;xs:element ref="ProductDescription"/&gt;
     * &lt;xs:element name="ProductDescription" type="SAFPTProductDescription"/&gt;
     * &lt;xs:simpleType name="SAFPTProductDescription"&gt;
     *  &lt;xs:annotation/&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *      &lt;xs:minLength value="2"/&gt;
     *      &lt;xs:maxLength value="200"/&gt;
     *  &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getProductDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productDescription));
        return $this->productDescription;
    }

    /**
     * Set ProductDescription
     * <pre>
     * &lt;xs:element ref="ProductDescription"/&gt;
     * &lt;xs:element name="ProductDescription" type="SAFPTProductDescription"/&gt;
     * &lt;xs:simpleType name="SAFPTProductDescription"&gt;
     *  &lt;xs:annotation/&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *      &lt;xs:minLength value="2"/&gt;
     *      &lt;xs:maxLength value="200"/&gt;
     *  &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @param string $productDescription
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductDescription(string $productDescription): void
    {
        if (\strlen($productDescription) < 2) {
            $msg = "Product descriiptin can not have less than 2 caracters";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->productDescription = $this->valTextMandMaxCar($productDescription,
            200, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->productDescription));
    }

    /**
     * <xs:element ref="Quantity"/>
     * @return float
     * @since 1.0.0
     */
    public function getQuantity(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", \strval($this->quantity)));
        return $this->quantity;
    }

    /**
     * <xs:element ref="Quantity"/>
     * @param float $quantity
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setQuantity(float $quantity): void
    {
        if ($quantity < 0.0) {
            $msg = "Quantity can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->quantity = $quantity;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->quantity));
    }

    /**
     * <xs:element ref="UnitOfMeasure"/><br>
     * <xs:element name="UnitOfMeasure" type="SAFPTtextTypeMandatoryMax20Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getUnitOfMeasure(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->unitOfMeasure));
        return $this->unitOfMeasure;
    }

    /**
     * <xs:element ref="UnitOfMeasure"/><br>
     * <xs:element name="UnitOfMeasure" type="SAFPTtextTypeMandatoryMax20Car"/>
     * @param string $unitOfMeasure
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setUnitOfMeasure(string $unitOfMeasure): void
    {
        $this->unitOfMeasure = $this->valTextMandMaxCar($unitOfMeasure, 20,
            __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->unitOfMeasure));
    }

    /**
     * <xs:element ref="UnitPrice"/>
     * @return float
     * @since 1.0.0
     */
    public function getUnitPrice(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", \strval($this->unitPrice)));
        return $this->unitPrice;
    }

    /**
     * <xs:element ref="UnitPrice"/>
     * @param float $unitPrice
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setUnitPrice(float $unitPrice): void
    {

        if ($unitPrice < 0.0) {
            $msg = "Quantity can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->unitPrice = $unitPrice;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->unitPrice));
    }

    /**
     * <xs:element ref="Description"/><br>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->description));
        return $this->description;
    }

    /**
     * <xs:element ref="Description"/><br>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @param string $description
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDescription(string $description): void
    {
        $this->description = $this->valTextMandMaxCar($description, 200,
            __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->description));
    }

    /**
     * <xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber|null
     * @since 1.0.0
     */
    public function getProductSerialNumber(): ?ProductSerialNumber
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->productSerialNumber === null ? "null" : "ProductSerialNumber getted"));
        return $this->productSerialNumber;
    }

    /**
     * <xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber|null $productSerialNumber
     * @return void
     * @since 1.0.0
     */
    public function setProductSerialNumber(?ProductSerialNumber $productSerialNumber): void
    {
        $this->productSerialNumber = $productSerialNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->productSerialNumber === null ? "null" : "ProductSerialNumber"));
    }

    /**
     * Get Tax ExemptionReason
     * <pre>
     * &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;&lt;br&gt;
     * &lt;xs:element name="TaxExemptionReason" type="SAFPTPortugueseTaxExemptionReason"/&gt;
     * &lt;xs:simpleType name="SAFPTPortugueseTaxExemptionReason"&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:minLength value="6"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @return string|null
     * @since 1.0.0
     */
    public function getTaxExemptionReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxExemptionReason === null ? "null" :
                        $this->taxExemptionReason));
        return $this->taxExemptionReason;
    }

    /**
     * Set Tax ExemptionReason
     * <pre>
     * &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;&lt;br&gt;
     * &lt;xs:element name="TaxExemptionReason" type="SAFPTPortugueseTaxExemptionReason"/&gt;
     * &lt;xs:simpleType name="SAFPTPortugueseTaxExemptionReason"&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:minLength value="6"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @param string|null $taxExemptionReason
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxExemptionReason(?string $taxExemptionReason): void
    {
        if ($taxExemptionReason !== null && \strlen($taxExemptionReason) < 6) {
            $msg = "Tax Exemption Reason can not have less than 6 caracters";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->taxExemptionReason = $taxExemptionReason === null ? null :
            $this->valTextMandMaxCar($taxExemptionReason, 60, __METHOD__);
    }

    /**
     * <xs:element ref="TaxExemptionCode" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null
     * @since 1.0.0
     */
    public function getTaxExemptionCode(): ?TaxExemptionCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxExemptionCode === null ? "null" :
                        $this->taxExemptionCode->get()));
        return $this->taxExemptionCode;
    }

    /**
     * <xs:element ref="TaxExemptionCode" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null $taxExemptionCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxExemptionCode(?TaxExemptionCode $taxExemptionCode): void
    {
        $this->taxExemptionCode = $taxExemptionCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxExemptionCode === null ? "null" :
                        $this->taxExemptionCode->get()));
    }

    /**
     * <xs:element ref="SettlementAmount" minOccurs="0"/>
     * @return float|null
     * @since 1.0.0
     */
    public function getSettlementAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->settlementAmount === null ? "null" :
                        \strval($this->settlementAmount)));
        return $this->settlementAmount;
    }

    /**
     * <xs:element ref="SettlementAmount" minOccurs="0"/>
     * @param float|null $settlementAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSettlementAmount(?float $settlementAmount): void
    {
        if ($settlementAmount !== null && $settlementAmount < 0.0) {
            $msg = "Settlement Amout can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->settlementAmount = $settlementAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->settlementAmount === null ? "null" :
                        \strval($this->settlementAmount)));
    }

    /**
     * <xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation|null
     * @since 1.0.0
     */
    public function getCustomsInformation(): ?CustomsInformation
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->customsInformation === null ? "null" :
                        "CustomsInformation"));
        return $this->customsInformation;
    }

    /**
     * <xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation|null $customsInformation
     * @return void
     * @since 1.0.0
     */
    public function setCustomsInformation(?CustomsInformation $customsInformation): void
    {
        $this->customsInformation = $customsInformation;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->settlementAmount === null ? "null" :
                        "CustomsInformation"));
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $lineNode = parent::createXmlNode($node);

        if ($this->getOrderReferences() !== null) {
            foreach ($this->getOrderReferences() as $orderReferences) {
                /* @var $orderReferences \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences */
                $orderReferences->createXmlNode($lineNode);
            }
        }
        $lineNode->addChild(static::N_PRODUCTCODE, $this->getProductCode());

        $lineNode->addChild(static::N_PRODUCTDESCRIPTION,
            $this->getProductDescription());

        $lineNode->addChild(static::N_QUANTITY,
            $this->floatFormat($this->getQuantity()));

        $lineNode->addChild(static::N_UNITOFMEASURE, $this->getUnitOfMeasure());

        $lineNode->addChild(static::N_UNITPRICE,
            $this->floatFormat($this->getUnitPrice()));

        return $lineNode;
    }

    /**
     * Create xml nodefor Credit and Debit
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    protected function createXmlNodeDebitCreditNode(\SimpleXMLElement $node): void
    {
        parent::createXmlNodeDebitCreditNode($node);
    }

    /**
     * Create the TaxExemptionReason,  TaxExemptionCode,
     * SettlementAmount, CustomsInformation nodes
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    protected function createXmlNodeTaxExcSettAndCustoms(\SimpleXMLElement $node): void
    {
        if ($this->getTaxExemptionReason() !== null) {
            $node->addChild(static::N_TAXEXEMPTIONREASON,
                $this->getTaxExemptionReason());
        }
        if ($this->getTaxExemptionCode() !== null) {
            $node->addChild(static::N_TAXEXEMPTIONCODE,
                $this->getTaxExemptionCode()->get());
        }
        if ($this->getSettlementAmount() !== null) {
            $node->addChild(static::N_SETTLEMENTAMOUNT,
                $this->floatFormat($this->getSettlementAmount()));
        }
        if ($this->getCustomsInformation() !== null) {
            $this->getCustomsInformation()->createXmlNode($node);
        }
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        parent::parseXmlNode($node);

        for ($n = 0; $n < $node->{static::N_ORDERREFERENCES}->count(); $n++) {
            $orderRef = new OrderReferences();
            $orderRef->parseXmlNode($node->{static::N_ORDERREFERENCES}[$n]);
            $this->addToOrderReferences($orderRef);
        }

        $this->setProductCode((string) $node->{static::N_PRODUCTCODE});
        $this->setProductDescription((string) $node->{static::N_PRODUCTDESCRIPTION});
        $this->setQuantity((float) $node->{static::N_QUANTITY});
        $this->setUnitOfMeasure((string) $node->{static::N_UNITOFMEASURE});
        $this->setUnitPrice((float) $node->{static::N_UNITPRICE});

        if ($node->{static::N_PRODUCTSERIALNUMBER}->count() > 0) {
            $psn = new ProductSerialNumber();
            $psn->parseXmlNode($node->{static::N_PRODUCTSERIALNUMBER});
            $this->setProductSerialNumber($psn);
        }

        $this->setDescription((string) $node->{static::N_DESCRIPTION});
        if ($node->{static::N_TAXEXEMPTIONREASON}->count() > 0) {
            $this->setTaxExemptionReason((string) $node->{static::N_TAXEXEMPTIONREASON});
        }
        if ($node->{static::N_TAXEXEMPTIONCODE}->count() > 0) {
            $this->setTaxExemptionCode(
                new TaxExemptionCode(
                    (string) $node->{static::N_TAXEXEMPTIONCODE}
            ));
        }
        if ($node->{static::N_SETTLEMENTAMOUNT}->count() > 0) {
            $this->setSettlementAmount((float) $node->{static::N_SETTLEMENTAMOUNT});
        }
        if ($node->{static::N_CUSTOMSINFORMATION}->count() > 0) {
            $ci = new CustomsInformation();
            $ci->parseXmlNode($node->{static::N_CUSTOMSINFORMATION});
            $this->setCustomsInformation($ci);
        }
    }
}