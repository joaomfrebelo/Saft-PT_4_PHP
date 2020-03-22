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

/**
 * <pre>
 * <!--    Estrutura de produto (AuditFile.MasterFiles.Product)-->
 *  <xs:element name="Product">
 *      <xs:complexType>
 *          <xs:sequence>
 *              <xs:element ref="ProductType"/>
 *              <xs:element ref="ProductCode"/>
 *              <xs:element ref="ProductGroup" minOccurs="0"/>
 *              <xs:element ref="ProductDescription"/>
 *              <xs:element ref="ProductNumberCode"/>
 *              <xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/>
 *          </xs:sequence>
 *      </xs:complexType>
 *  </xs:element>
 * </pre>
 * Class Product
 * @since 1.0.0
 */
class Product
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
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
     *     <xs:element name="ProductType">
     *      <xs:annotation>
     *          <xs:documentation>Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
     *              debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
     *              Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
     *              parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
     *              impostos (TaxTable)e Impostos Especiais de Consumo </xs:documentation>
     *      </xs:annotation>
     *      <xs:simpleType>
     *          <xs:restriction base="xs:string">
     *              <xs:enumeration value="P"/>
     *              <xs:enumeration value="S"/>
     *              <xs:enumeration value="O"/>
     *              <xs:enumeration value="E"/>
     *              <xs:enumeration value="I"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     *
     * Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
     * debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos  debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
     * Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
     * parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
     * impostos (TaxTable)e Impostos Especiais de Consumo
     * @var string $productType
     * @since 1.0.0
     */
    private ProductType $productType;

    /**
     * <pre>
     * <xs:element ref="ProductCode"/>
     * <xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @var string $productCode
     * @since 1.0.0
     */
    private string $productCode;

    /**
     * <pre>
     * <xs:element ref="ProductGroup" minOccurs="0"/>
     * <xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/>
     * </pre>
     * @var string|null $productGroup
     * @since 1.0.0
     */
    private ?string $productGroup = null;

    /**
     * <pre>
     * <xs:element ref="ProductDescription"/>     *
     *  <!-- Descrição do produto ou servico -->
     *  <xs:simpleType name="SAFPTProductDescription">
     *      <xs:annotation/>
     *      <xs:restriction base="xs:string">
     *          <xs:minLength value="2"/>
     *          <xs:maxLength value="200"/>
     *      </xs:restriction>
     *  </xs:simpleType>
     * </pre>
     * @var string $productDescription
     * @since 1.0.0
     */
    private string $productDescription;

    /**
     * <pre>
     * <xs:element ref="ProductNumberCode"/>
     * <xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @var string $productNumberCode
     * @since 1.0.0
     */
    private string $productNumberCode;

    /**
     * <pre>
     * <xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/>
     * </pre>
     *
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails|null $customsDetails
     * @since 1.0.0
     */
    private ?CustomsDetails $customsDetails = null;

    /**
     * <code>
     * <pre>
     * <!--    Estrutura de produto (AuditFile.MasterFiles.Product)-->
     *  <xs:element name="Product">
     *      <xs:complexType>
     *          <xs:sequence>
     *              <xs:element ref="ProductType"/>
     *              <xs:element ref="ProductCode"/>
     *              <xs:element ref="ProductGroup" minOccurs="0"/>
     *              <xs:element ref="ProductDescription"/>
     *              <xs:element ref="ProductNumberCode"/>
     *              <xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/>
     *          </xs:sequence>
     *      </xs:complexType>
     *  </xs:element>
     * </pre>
     * </code>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as productType
     * <br>
     * Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
     * debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos  debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
     * Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
     * parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
     * impostos (TaxTable)e Impostos Especiais de Consumo
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType
     * @since 1.0.0
     */
    public function getProductType(): ProductType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->productType->get()));
        return $this->productType;
    }

    /**
     * Sets a new productType
     * <br>
     * Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
     * debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos  debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
     * Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
     * parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
     * impostos (TaxTable)e Impostos Especiais de Consumo
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType $productType
     * @return void
     * @since 1.0.0
     */
    public function setProductType(ProductType $productType): void
    {
        $this->productType = $productType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->productType->get()));
    }

    /**
     * Gets as productCode
     *
     * <pre>
     * <xs:element ref="ProductCode"/>
     * <xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getProductCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->productCode));
        return $this->productCode;
    }

    /**
     * Sets productCode
     *
     * <pre>
     * <xs:element ref="ProductCode"/>
     * <xs:element name="ProductCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     *
     * @param string $productCode
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductCode(string $productCode): void
    {
        $this->productCode = static::valTextMandMaxCar($productCode, 60,
                                                       __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->productCode));
    }

    /**
     * Gets productGroup
     * <pre>
     * <xs:element ref="ProductGroup" minOccurs="0"/>
     * <xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/>
     * </pre>
     * @return string|null
     * @since 1.0.0
     */
    public function getProductGroup(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->productGroup));
        return $this->productGroup;
    }

    /**
     * Sets a new productGroup
     * <pre>
     * <xs:element ref="ProductGroup" minOccurs="0"/>
     * <xs:element name="ProductGroup" type="SAFPTtextTypeMandatoryMax50Car"/>
     * </pre>
     * @param string|null $productGroup
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductGroup(?string $productGroup): void
    {
        if ($productGroup === null)
        {
            $this->productGroup = null;
        }
        else
        {
            $this->productGroup = static::valTextMandMaxCar($productGroup, 50,
                                                            __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->productGroup === null
                        ? "null"
                        : $this->productGroup));
    }

    /**
     * Gets as productDescription
     * <pre>
     * <xs:minLength value="2"/>
     * <xs:maxLength value="200"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getProductDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->productDescription));
        return $this->productDescription;
    }

    /**
     * Sets a new productDescription     *
     * <pre>
     * <xs:minLength value="2"/>
     * <xs:maxLength value="200"/>
     * </pre>
     * @param string $productDescription
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductDescription(string $productDescription): void
    {
        if (\strlen($productDescription) < 2)
        {
            $msg = "Product description must have at leats 2 chars";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->productDescription = static::valTextMandMaxCar($productDescription,
                                                              200, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->productDescription));
    }

    /**
     * Gets as productNumberCode
     * <pre>
     * <xs:element ref="ProductNumberCode"/>
     * <xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getProductNumberCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->productNumberCode));
        return $this->productNumberCode;
    }

    /**
     * Sets a new productNumberCode
     * <pre>
     * <xs:element ref="ProductNumberCode"/>
     * <xs:element name="ProductNumberCode" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @param string $productNumberCode
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setProductNumberCode(string $productNumberCode): void
    {
        $this->productNumberCode = static::valTextMandMaxCar($productNumberCode,
                                                             60, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->productDescription));
    }

    /**
     * Gets as customsDetails
     * <pre>
     * <xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/>
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails|null
     * @since 1.0.0
     */
    public function getCustomsDetails(): ?CustomsDetails
    {
        \Logger::getLogger(\get_class($this))->info(\sprintf(__METHOD__));
        return $this->customsDetails;
    }

    /**
     * Sets a new customsDetails
     * <pre>
     * <xs:element name="CustomsDetails" type="CustomsDetails" minOccurs="0"/>
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails|null $customsDetails
     * @return void
     * @since 1.0.0
     */
    public function setCustomsDetails(?CustomsDetails $customsDetails): void
    {
        $this->customsDetails = $customsDetails;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__ . " setted ");
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        $prodNode = $node->addChild(static::N_PRODUCT);
        $prodNode->addChild(static::N_PRODUCTTYPE,
                            $this->getProductType()->get());
        $prodNode->addChild(static::N_PRODUCTCODE, $this->getProductCode());
        if ($this->getProductGroup() !== null)
        {
            $prodNode->addChild(static::N_PRODUCTGROUP, $this->getProductGroup());
        }
        $prodNode->addChild(static::N_PRODUCTDESCRIPTION,
                            $this->getProductDescription());
        $prodNode->addChild(static::N_PRODUCTNUMBERCODE,
                            $this->getProductNumberCode());
        if ($this->getCustomsDetails() !== null)
        {
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
        if ($node->getName() !== static::N_PRODUCT)
        {
            $msg = sprinf("Node name should be '%s' but is '%s",
                          static::N_PRODUCT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->setProductType(new ProductType((string) $node->{static::N_PRODUCTTYPE}));
        $this->setProductCode((string) $node->{static::N_PRODUCTCODE});
        if ($node->{static::N_PRODUCTGROUP}->count() > 0)
        {
            $this->setProductGroup((string) $node->{static::N_PRODUCTGROUP});
        }
        else
        {
            $this->setProductGroup(null);
        }
        $this->setProductDescription((string) $node->{static::N_PRODUCTDESCRIPTION});
        $this->setProductNumberCode((string) $node->{static::N_PRODUCTNUMBERCODE});
        if ($node->{static::N_CUSTOMSDETAILS}->count() > 0)
        {
            $cusDet = new CustomsDetails();
            $cusDet->parseXmlNode($node->{static::N_CUSTOMSDETAILS});
            $this->setCustomsDetails($cusDet);
        }
        else
        {
            $this->setCustomsDetails(null);
        }
    }

}
