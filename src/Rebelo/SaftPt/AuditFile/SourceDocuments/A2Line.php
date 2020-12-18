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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

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
     * &lt;xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_ORDERREFERENCES = "OrderReferences";

    /**
     * &lt;xs:element ref="ProductCode"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTCODE = "ProductCode";

    /**
     * &lt;xs:element ref="ProductDescription"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTDESCRIPTION = "ProductDescription";

    /**
     * &lt;xs:element ref="Quantity"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_QUANTITY = "Quantity";

    /**
     * &lt;xs:element ref="UnitOfMeasure"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_UNITOFMEASURE = "UnitOfMeasure";

    /**
     * &lt;xs:element ref="UnitPrice"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_UNITPRICE = "UnitPrice";

    /**
     * &lt;xs:element ref="Description"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * &lt;xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTSERIALNUMBER = "ProductSerialNumber";

    /**
     * &lt;xs:element ref="DebitAmount"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_DEBITAMOUNT = "DebitAmount";

    /**
     * &lt;xs:element ref="CreditAmount"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_CREDITAMOUNT = "CreditAmount";

    /**
     * &lt;xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMSINFORMATION = "CustomsInformation";

    /**
     * &lt;xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences[]
     * @since 1.0.0
     */
    protected array $orderReferences = array();

    /**
     * &lt;xs:element ref="ProductCode"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $productCode;

    /**
     * &lt;xs:element ref="ProductDescription"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $productDescription;

    /**
     * &lt;xs:element ref="Quantity"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $quantity;

    /**
     * &lt;xs:element ref="UnitOfMeasure"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $unitOfMeasure;

    /**
     * &lt;xs:element ref="UnitPrice"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $unitPrice;

    /**
     * &lt;xs:element ref="Description"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $description;

    /**
     * &lt;xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber|null
     * @since 1.0.0
     */
    protected ?ProductSerialNumber $productSerialNumber = null;

    /**
     * &lt;xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation
     * @since 1.0.0
     */
    protected ?CustomsInformation $customsInformation = null;

    /**
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * GetOrderReferences<br>
     * References to invoices on the correspondent correcting documents.
     * If there is a need to make more than one reference, this structure
     * can be generated as many times as necessary.<br>     *
     * &lt;xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/&gt;
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
     * AddOrderReferences<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary<br>
     * Every time that this method is invoked a new instance of OrderReferences
     * is created, add to the stack and returned to be populaed<br>
     * &lt;xs:element name="OrderReferences" type="OrderReferences" minOccurs="0" maxOccurs="unbounded"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences $orderReferences The new instance created
     * @since 1.0.0
     */
    public function addOrderReferences(): OrderReferences
    {
        $orderReferences         = new OrderReferences($this->getErrorRegistor());
        $this->orderReferences[] = $orderReferences;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__."OrderReferences add to index "
        );
        return $orderReferences;
    }

    /**
     * Get ProductCode<br>
     * Record Key related to table 2.4. – Product in field 2.4.2. - ProductCode.<br>
     * &lt;xs:element ref="ProductCode"/&gt;<br>
     * &lt;xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productCode));
        return $this->productCode;
    }

    /**
     * Get if is set ProductCode
     * @return bool
     * @since 1.0.0
     */
    public function issetProductCode(): bool
    {
        return isset($this->productCode);
    }

    /**
     * Set ProductCode<br>
     * Record Key related to table 2.4. – Product in field 2.4.2. - ProductCode.<br>
     * &lt;xs:element ref="ProductCode"/&gt;<br>
     * &lt;xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @param string $productCode
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setProductCode(string $productCode): bool
    {
        try {
            $this->productCode = $this->valTextMandMaxCar(
                $productCode, 60, __METHOD__, false
            );
            $return            = true;
        } catch (AuditFileException $e) {
            $this->productCode = $productCode;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductCode_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->productCode));
        return $return;
    }

    /**
     * Get ProductDescription<br>
     * Description of the invoice line, related to table 2.4. –
     * Product in field 2.4.4. – ProductDescription.
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
     * Get if is set ProductDescription
     * @return bool
     * @since 1.0.0
     */
    public function issetProductDescription(): bool
    {
        return isset($this->productDescription);
    }

    /**
     * Set ProductDescriptionet ProductDescription<br>
     * Description of the invoice line, related to table 2.4. –
     * Product in field 2.4.4. – ProductDescription.
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
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setProductDescription(string $productDescription): bool
    {
        try {
            if (\strlen($productDescription) < 2) {
                $msg = "Product descriiptin can not have less than 2 caracters";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->productDescription = $this->valTextMandMaxCar(
                $productDescription,
                200, __METHOD__
            );
            $return                   = true;
        } catch (AuditFileException $e) {
            $this->productDescription = $productDescription;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductCode_not_valid");
            $return                   = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->productDescription
                )
            );
        return $return;
    }

    /**
     * Get Quantity<br>
     * &lt;xs:element ref="Quantity"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getQuantity(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", \strval($this->quantity)));
        return $this->quantity;
    }

    /**
     * Get if is set Quantity
     * @return bool
     * @since 1.0.0
     */
    public function issetQuantity(): bool
    {
        return isset($this->quantity);
    }

    /**
     * Set Quantity<br>
     * &lt;xs:element ref="Quantity"/&gt;
     * @param float $quantity
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setQuantity(float $quantity): bool
    {
        if ($quantity < 0.0) {
            $msg    = "Quantity can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("Quantity_not_valid");
        } else {
            $return = true;
        }
        $this->quantity = $quantity;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->quantity));
        return $return;
    }

    /**
     * Get UnitOfMeasure<br>
     * &lt;xs:element ref="UnitOfMeasure"/&gt;<br>
     * &lt;xs:element name="UnitOfMeasure" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
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
     * Get if is set UnitOfMeasure
     * @return bool
     * @since 1.0.0
     */
    public function issetUnitOfMeasure(): bool
    {
        return isset($this->unitOfMeasure);
    }

    /**
     * Set UnitOfMeasure<br>
     * &lt;xs:element ref="UnitOfMeasure"/&gt;<br>
     * &lt;xs:element name="UnitOfMeasure" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     * @param string $unitOfMeasure
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setUnitOfMeasure(string $unitOfMeasure): bool
    {
        try {
            $this->unitOfMeasure = $this->valTextMandMaxCar(
                $unitOfMeasure, 20, __METHOD__
            );
            $return              = true;
        } catch (AuditFileException $e) {
            $this->unitOfMeasure = $unitOfMeasure;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("UnitOfMeasure_not_valid");
            $return              = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->unitOfMeasure));
        return $return;
    }

    /**
     * Get UnitPrice<br>
     * Price per Unit without tax, and after the deduction of the line and header discounts.
     * It shall be filled in with "0.00" if there is any requirement to
     * fill in the field 4.1.4.19.8. - TaxBase.<br>
     * &lt;xs:element ref="UnitPrice"/&gt;
     * @return float
     * @throws \error
     * @since 1.0.0
     */
    public function getUnitPrice(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", \strval($this->unitPrice)));
        return $this->unitPrice;
    }

    /**
     * Get if is set UnitPrice
     * @return bool
     * @since 1.0.0
     */
    public function issetUnitPrice(): bool
    {
        return isset($this->unitPrice);
    }

    /**
     * Set UnitPrice<br>
     * Price per Unit without tax, and after the deduction of the line and header discounts.
     * It shall be filled in with "0.00" if there is any requirement to
     * fill in the field 4.1.4.19.8. - TaxBase.<br>
     * &lt;xs:element ref="UnitPrice"/&gt;
     * @param float $unitPrice
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setUnitPrice(float $unitPrice): bool
    {
        if ($unitPrice < 0.0) {
            $msg    = "Quantity can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("UnitPrice_not_valid");
        } else {
            $return = true;
        }
        $this->unitPrice = $unitPrice;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->unitPrice));
        return $return;
    }

    /**
     * Get Description<br>
     * Description of the document line.
     * Line description of the document<br>
     * &lt;xs:element ref="Description"/&gt;<br>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->description));
        return $this->description;
    }

    /**
     * Get if is set Description
     * @return bool
     * @since 1.0.0
     */
    public function issetDescription(): bool
    {
        return isset($this->description);
    }

    /**
     * Set Description<br>
     * Description of the document line.<br>
     * &lt;xs:element ref="Description"/&gt;<br>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @param string $description
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDescription(string $description): bool
    {
        try {
            $this->description = $this->valTextMandMaxCar(
                $description, 200, __METHOD__
            );
            $return            = true;
        } catch (AuditFileException $e) {
            $this->description = $description;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductCode_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->description));
        return $return;
    }

    /**
     * GetProductSerialNumber<br>
     * When this method is invoked and $create is true a new instance
     * of ProductSerialNumber will be created, if not previousm, add to the stack and
     * returned to be populaed<br>
     * &lt;xs:element name="ProductSerialNumber" type="ProductSerialNumber" minOccurs="0"/&gt;
     * @param bool $create If true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ProductSerialNumber|null
     * @since 1.0.0
     */
    public function getProductSerialNumber(bool $create = true): ?ProductSerialNumber
    {
        if ($create && $this->productSerialNumber === null) {
            $this->productSerialNumber = new ProductSerialNumber(
                $this->getErrorRegistor()
            );
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->productSerialNumber === null ? "null" : "ProductSerialNumber getted"
                )
            );
        return $this->productSerialNumber;
    }

    /**
     * Set ProductSerialNumberAsNull
     * @return void
     * @since 1.0.0
     */
    public function setProductSerialNumberAsNull(): void
    {
        $this->productSerialNumber = null;
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." ProductSerialNumberAsNull set to null");
    }

    /**
     * Get CustomsInformation<br>
     * When this method is invoked and $create is true a new instance
     * of CustomsInformation will be created, if not previousm, add to the stack and
     * returned to be populaed<br>
     * &lt;xs:element name="CustomsInformation" type="CustomsInformation" minOccurs="0"/&gt;
     * @param bool $create If true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\CustomsInformation|null
     * @since 1.0.0
     */
    public function getCustomsInformation(bool $create = true): ?CustomsInformation
    {
        if ($create && $this->customsInformation === null) {
            $this->customsInformation = new CustomsInformation(
                $this->getErrorRegistor()
            );
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->customsInformation === null ? "null" :
                    "CustomsInformation"
                )
            );
        return $this->customsInformation;
    }

    /**
     * Set CustomsInformation As Null
     * @return void
     * @since 1.0.0
     */
    public function setCustomsInformationAsNull(): void
    {
        $this->customsInformation = null;
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set to null");
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

        if (isset($this->productCode)) {
            $lineNode->addChild(static::N_PRODUCTCODE, $this->getProductCode());
        } else {
            $lineNode->addChild(static::N_PRODUCTCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductCode_not_valid");
        }

        if (isset($this->productDescription)) {
            $lineNode->addChild(
                static::N_PRODUCTDESCRIPTION, $this->getProductDescription()
            );
        } else {
            $lineNode->addChild(static::N_PRODUCTDESCRIPTION);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductDescription_not_valid");
        }

        if (isset($this->quantity)) {
            $lineNode->addChild(
                static::N_QUANTITY, $this->floatFormat($this->getQuantity())
            );
        } else {
            $lineNode->addChild(static::N_QUANTITY);
            $this->getErrorRegistor()->addOnCreateXmlNode("Quantity_not_valid");
        }

        if (isset($this->unitOfMeasure)) {
            $lineNode->addChild(
                static:: N_UNITOFMEASURE, $this->getUnitOfMeasure()
            );
        } else {
            $lineNode->addChild(static:: N_UNITOFMEASURE);
            $this->getErrorRegistor()->addOnCreateXmlNode("UnitOfMeasure_not_valid");
        }

        if (isset($this->unitPrice)) {
            $lineNode->addChild(
                static::N_UNITPRICE, $this->floatFormat($this->getUnitPrice())
            );
        } else {
            $lineNode->addChild(static::N_UNITPRICE);
            $this->getErrorRegistor()->addOnCreateXmlNode("UnitPrice_not_valid");
        }

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
            $node->addChild(
                static::N_TAXEXEMPTIONREASON, $this->getTaxExemptionReason()
            );
        }

        if ($this->getTaxExemptionCode() !== null) {
            $node->addChild(
                static::N_TAXEXEMPTIONCODE, $this->getTaxExemptionCode()->get()
            );
        }

        if ($this->getSettlementAmount() !== null) {
            $node->addChild(
                static::N_SETTLEMENTAMOUNT,
                $this->floatFormat($this->getSettlementAmount())
            );
        }

        if ($this->getCustomsInformation(false) !== null) {
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
        for ($n = 0; $n < $node->{static::N_ORDERREFERENCES }->count(); $n++) {
            $this->addOrderReferences()->parseXmlNode($node->{static::N_ORDERREFERENCES}[$n]);
        }

        $this->setProductCode((string) $node->{static::N_PRODUCTCODE});
        $this->setProductDescription((string) $node->{static::N_PRODUCTDESCRIPTION});
        $this->setQuantity((float) $node->{static::N_QUANTITY});
        $this->setUnitOfMeasure((string) $node->{static::N_UNITOFMEASURE});
        $this->setUnitPrice((float) $node->{static::N_UNITPRICE});

        if ($node->{static::N_PRODUCTSERIALNUMBER}->count() > 0) {
            $this->getProductSerialNumber()->parseXmlNode(
                $node->{static::N_PRODUCTSERIALNUMBER}
            );
        }

        $this->setDescription((string) $node->{static::N_DESCRIPTION});

        if ($node->{static::N_TAXEXEMPTIONREASON}->count() > 0) {
            $this->setTaxExemptionReason(
                (string) $node->{static::N_TAXEXEMPTIONREASON}
            );
        }

        if ($node->{static::N_TAXEXEMPTIONCODE}->count() > 0) {
            $this->setTaxExemptionCode(
                new TaxExemptionCode(
                    (string) $node->{static::N_TAXEXEMPTIONCODE}
                )
            );
        }

        if ($node->{static::N_SETTLEMENTAMOUNT}->count() > 0) {
            $this->setSettlementAmount(
                (float) $node->{static::N_SETTLEMENTAMOUNT}
            );
        }

        if ($node->{static::N_CUSTOMSINFORMATION}->count() > 0) {
            $this->getCustomsInformation()->parseXmlNode(
                $node->{static::N_CUSTOMSINFORMATION}
            );
        }
    }
}
