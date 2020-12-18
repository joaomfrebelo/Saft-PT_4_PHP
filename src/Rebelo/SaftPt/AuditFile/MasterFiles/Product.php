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
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Product<br>
 * This table shall present the catalogue of products and types of services
 * used in the invoicing system, which have been operated, and also the records,
 * which are implicit in the operations and do not exist in the table of
 * products/services of the application.
 * If, for instance, there is an invoice with a line of freights that does
 * not exist in the articles’ file of the application, this file shall be
 * exported and represented as a product in the SAF-T (PT).
 * This table shall also show taxes, tax rates, eco taxes, parafiscal charges
 * mentioned in the invoice and contributing or not to the taxable basis
 * for VAT or Stamp Duty - except VAT and Stamp duty, which shall be showed
 * in 2.5. – TaxTable (Table of taxes).
 * <pre>
 *  &lt;xs:element name="Product"&gt;
 *      &lt;xs:complexType&gt;
 *          &lt;xs:sequence&gt;
 *              &lt;xs:element ref="ProductType"/&gt;
 *              &lt;xs:element ref="ProductCode"/&gt;
 *              &lt;xs:element ref="ProductGroup" minOccurs="0"/&gt;
 *              &lt;xs:element ref="ProductDescription"/&gt;
 *              &lt;xs:element ref="ProductNumberCode"/&gt;
 *              &lt;xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/&gt;
 *          &lt;/xs:sequence&gt;
 *      &lt;/xs:complexType&gt;
 *  &lt;/xs:element&gt;
 * </pre>
 * @since 1.0.0
 */
class Product extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCT = "Product";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTTYPE = "ProductType";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTCODE = "ProductCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTGROUP = "ProductGroup";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTDESCRIPTION = "ProductDescription";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTNUMBERCODE = "ProductNumberCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMSDETAILS = "CustomsDetails";

    /**
     * <pre>
     *     &lt;xs:element name="ProductType"&gt;     *
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:enumeration value="P"/&gt;
     *              &lt;xs:enumeration value="S"/&gt;
     *              &lt;xs:enumeration value="O"/&gt;
     *              &lt;xs:enumeration value="E"/&gt;
     *              &lt;xs:enumeration value="I"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     *
     * The field shall be filled in with:<br>
     * “P” - Products;<br>
     * “S” - Services;<br>
     * “O” - Others (e.g. charged freights, advance payments received or sale of assets);<br>
     * “E” - Excise duties - (e.g. IABA, ISP, IT);<br>
     * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
     * which shall appear in table 2.5. – TaxTable and Excise Duties which
     * shall be filled in with the "E" code.
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType $productType
     * @since 1.0.0
     */
    private ProductType $productType;

    /**
     * <pre>
     * &lt;xs:element ref="ProductCode"/&gt;
     * &lt;xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @var string $productCode
     * @since 1.0.0
     */
    private string $productCode;

    /**
     * <pre>
     * &lt;xs:element ref="ProductGroup" minOccurs="0"/&gt;
     * &lt;xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * </pre>
     * @var string|null $productGroup
     * @since 1.0.0
     */
    private ?string $productGroup = null;

    /**
     * <pre>
     * &lt;xs:element ref="ProductDescription"/&gt;     *
     *  &lt;!-- Descrição do produto ou servico --&gt;
     *  &lt;xs:simpleType name="SAFPTProductDescription"&gt;
     *      &lt;xs:annotation/&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:minLength value="2"/&gt;
     *          &lt;xs:maxLength value="200"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @var string $productDescription
     * @since 1.0.0
     */
    private string $productDescription;

    /**
     * <pre>
     * &lt;xs:element ref="ProductNumberCode"/&gt;
     * &lt;xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @var string $productNumberCode
     * @since 1.0.0
     */
    private string $productNumberCode;

    /**
     * <pre>
     * &lt;xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/&gt;
     * </pre>
     *
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails|null $customsDetails
     * @since 1.0.0
     */
    private ?CustomsDetails $customsDetails = null;

    /**
     * Product<br>
     * This table shall present the catalogue of products and types of services
     * used in the invoicing system, which have been operated, and also the records,
     * which are implicit in the operations and do not exist in the table of
     * products/services of the application.
     * If, for instance, there is an invoice with a line of freights that does
     * not exist in the articles’ file of the application, this file shall be
     * exported and represented as a product in the SAF-T (PT).
     * This table shall also show taxes, tax rates, eco taxes, parafiscal charges
     * mentioned in the invoice and contributing or not to the taxable basis
     * for VAT or Stamp Duty - except VAT and Stamp duty, which shall be showed
     * in 2.5. – TaxTable (Table of taxes).
     * <pre>
     *  &lt;xs:element name="Product"&gt;
     *      &lt;xs:complexType&gt;
     *          &lt;xs:sequence&gt;
     *              &lt;xs:element ref="ProductType"/&gt;
     *              &lt;xs:element ref="ProductCode"/&gt;
     *              &lt;xs:element ref="ProductGroup" minOccurs="0"/&gt;
     *              &lt;xs:element ref="ProductDescription"/&gt;
     *              &lt;xs:element ref="ProductNumberCode"/&gt;
     *              &lt;xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/&gt;
     *          &lt;/xs:sequence&gt;
     *      &lt;/xs:complexType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets as productType
     * <br>
     * The field shall be filled in with:<br>
     * “P” - Products;<br>
     * “S” - Services;<br>
     * “O” - Others (e.g. charged freights, advance payments received or sale of assets);<br>
     * “E” - Excise duties - (e.g. IABA, ISP, IT);<br>
     * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
     * which shall appear in table 2.5. – TaxTable and Excise Duties which
     * shall be filled in with the "E" code.
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductType(): ProductType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productType->get()));
        return $this->productType;
    }

    /**
     * Get if is set ProductType
     * @return bool
     * @since 1.0.0
     */
    public function issetProductType(): bool
    {
        return isset($this->productType);
    }

    /**
     * Sets a new productType
     * <br>
     * The field shall be filled in with:<br>
     * “P” - Products;<br>
     * “S” - Services;<br>
     * “O” - Others (e.g. charged freights, advance payments received or sale of assets);<br>
     * “E” - Excise duties - (e.g. IABA, ISP, IT);<br>
     * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
     * which shall appear in table 2.5. – TaxTable and Excise Duties which
     * shall be filled in with the "E" code.
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType $productType
     * @return void
     * @since 1.0.0
     */
    public function setProductType(ProductType $productType): void
    {
        $this->productType = $productType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->productType->get()
                )
            );
    }

    /**
     * Gets ProductCode<br>
     * The unique code in the list of products
     * <pre>
     * &lt;xs:element ref="ProductCode"/&gt;
     * &lt;xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
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
     * Sets Product Code<br>
     * The unique code in the list of products
     * <pre>
     * &lt;xs:element ref="ProductCode"/&gt;
     * &lt;xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @param string $productCode
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductCode(string $productCode): bool
    {
        try {
            $this->productCode = static::valTextMandMaxCar(
                $productCode, 60, __METHOD__
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
     * Gets productGroup
     * <pre>
     * &lt;xs:element ref="ProductGroup" minOccurs="0"/&gt;
     * &lt;xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * </pre>
     * @return string|null
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductGroup(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productGroup));
        return $this->productGroup;
    }

    /**
     * Sets a new productGroup
     * <pre>
     * &lt;xs:element ref="ProductGroup" minOccurs="0"/&gt;
     * &lt;xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * </pre>
     * @param string|null $productGroup
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductGroup(?string $productGroup): bool
    {
        try {
            $this->productGroup = $productGroup === null ?
                null : static::valTextMandMaxCar($productGroup, 50, __METHOD__);
            $return             = true;
        } catch (AuditFileException $e) {
            $this->productGroup = $productGroup;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductGroup_not_valid");
            $return             = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->productGroup === null ? "null" : $this->productGroup
                )
            );
        return $return;
    }

    /**
     * Gets as productDescription<br>
     * It shall correspond to the usual name of the goods or services provided,
     * specifying the elements necessary to determine the applicable tax rate.
     * <pre>
     * &lt;xs:minLength value="2"/&gt;
     * &lt;xs:maxLength value="200"/&gt;
     * </pre>
     * @return string
     * @throws \Error
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
     * Sets a new productDescription<br>     *
     * It shall correspond to the usual name of the goods or services provided,
     * specifying the elements necessary to determine the applicable tax rate.
     * <pre>
     * &lt;xs:minLength value="2"/&gt;
     * &lt;xs:maxLength value="200"/&gt;
     * </pre>
     * @param string $productDescription
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductDescription(string $productDescription): bool
    {
        try {
            if (\strlen($productDescription) < 2) {
                $msg = "Product description must have at leats 2 chars";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->productDescription = static::valTextMandMaxCar(
                $productDescription, 200, __METHOD__
            );
            $return                   = true;
        } catch (AuditFileException $e) {
            $this->productDescription = $productDescription;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductDescription_not_valid");
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
     * Gets as productNumberCode<br>
     * The product’s EAN Code (bar code) shall be used.
     * If the EAN Code does not exist, fill in with the content of field 2.4.2. – ProductCode.
     * <pre>
     * &lt;xs:element ref="ProductNumberCode"/&gt;
     * &lt;xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductNumberCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productNumberCode));
        return $this->productNumberCode;
    }

    /**
     * Get if is set ProductNumberCode
     * @return bool
     * @since 1.0.0
     */
    public function issetProductNumberCode(): bool
    {
        return isset($this->productNumberCode);
    }

    /**
     * Sets ProductNumberCode<br>
     * The product’s EAN Code (bar code) shall be used.
     * If the EAN Code does not exist, fill in with the content of field 2.4.2. – ProductCode.
     * <pre>
     * &lt;xs:element ref="ProductNumberCode"/&gt;
     * &lt;xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @param string $productNumberCode
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductNumberCode(string $productNumberCode): bool
    {
        try {
            $this->productNumberCode = static::valTextMandMaxCar(
                $productNumberCode, 60, __METHOD__
            );
            $return                  = true;
        } catch (AuditFileException $e) {
            $this->productNumberCode = $productNumberCode;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductNumberCode_not_valid");
            $return                  = false;
        }
        \Logger::getLogger(\get_class($this))->debug(
            \sprintf(__METHOD__." set to '%s'", $this->productNumberCode)
        );
        return $return;
    }

    /**
     * Gets CustomsDetails<br>
     * When you getCustomsDetails for the first time the instance will be created
     * <pre>
     * &lt;xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails
     * @since 1.0.0
     */
    public function getCustomsDetails(): CustomsDetails
    {
        if (isset($this->customsDetails) === false) {
            $this->customsDetails = new CustomsDetails($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->info(\sprintf(__METHOD__));
        return $this->customsDetails;
    }

    /**
     * Get if is set CustomsDetails
     * @return bool
     * @since 1.0.0
     */
    public function issetCustomsDetails(): bool
    {
        return isset($this->customsDetails);
    }

    /**
     * Create Xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== MasterFiles::N_MASTERFILES) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                MasterFiles::N_MASTERFILES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $prodNode = $node->addChild(static::N_PRODUCT);

        if (isset($this->productType)) {
            $prodNode->addChild(
                static::N_PRODUCTTYPE, $this->getProductType()->get()
            );
        } else {
            $node->addChild(static::N_PRODUCTTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductType_not_valid");
        }

        if (isset($this->productCode)) {
            $prodNode->addChild(static::N_PRODUCTCODE, $this->getProductCode());
        } else {
            $prodNode->addChild(static::N_PRODUCTCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductCode_not_valid");
        }

        if ($this->getProductGroup() !== null) {
            $prodNode->addChild(static::N_PRODUCTGROUP, $this->getProductGroup());
        }

        if (isset($this->productDescription)) {
            $prodNode->addChild(
                static::N_PRODUCTDESCRIPTION, $this->getProductDescription()
            );
        } else {
            $prodNode->addChild(static::N_PRODUCTDESCRIPTION);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductDescription_not_valid");
        }

        if (isset($this->productNumberCode)) {
            $prodNode->addChild(
                static::N_PRODUCTNUMBERCODE, $this->getProductNumberCode()
            );
        } else {
            $prodNode->addChild(static::N_PRODUCTNUMBERCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductNumberCode_not_valid");
        }

        if ($this->getCustomsDetails() !== null) {
            $this->getCustomsDetails()->createXmlNode($prodNode);
        }

        return $prodNode;
    }

    /**
     * Parse the xml node to the instance
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_PRODUCT) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_PRODUCT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setProductType(new ProductType((string) $node->{static::N_PRODUCTTYPE}));
        $this->setProductCode((string) $node->{static::N_PRODUCTCODE});

        if ($node->{static::N_PRODUCTGROUP}->count() > 0) {
            $this->setProductGroup((string) $node->{static::N_PRODUCTGROUP});
        } else {
            $this->setProductGroup(null);
        }

        $this->setProductDescription((string) $node->{static::N_PRODUCTDESCRIPTION});

        $this->setProductNumberCode((string) $node->{static::N_PRODUCTNUMBERCODE});

        if ($node->{static::N_CUSTOMSDETAILS}->count() > 0) {
            $this->getCustomsDetails()->parseXmlNode($node->{static::N_CUSTOMSDETAILS});
        }
    }
}
