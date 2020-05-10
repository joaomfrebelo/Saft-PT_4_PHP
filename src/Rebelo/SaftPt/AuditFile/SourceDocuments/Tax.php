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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;

/**
 * Tax
 * <!-- Estrutura de Taxa -->
 * &lt;xs:complexType name="Tax"&gt;
 *     &lt;xs:sequence&gt;
 *         &lt;xs:element ref="TaxType"/&gt;
 *         &lt;xs:element ref="TaxCountryRegion"/&gt;
 *         &lt;xs:element ref="TaxCode"/&gt;
 *         &lt;xs:choice&gt;
 *             &lt;xs:element ref="TaxPercentage"/&gt;
 *             &lt;xs:element ref="TaxAmount"/&gt;
 *         &lt;/xs:choice&gt;
 *     &lt;/xs:sequence&gt;
 * &lt;/xs:complexType&gt;
 * @author João Rebelo
 * @since 1.0.0
 */
class Tax extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXTYPE = "TaxType";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXCOUNTRYREGION = "TaxCountryRegion";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXCODE = "TaxCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXPERCENTAGE = "TaxPercentage";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXAMOUNT = "TaxAmount";

    /**
     * <xs:element ref="TaxType"/>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType
     * @since 1.0.0
     */
    private TaxType $taxType;

    /**
     * <xs:element ref="TaxCountryRegion"/>
     * @var \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    private TaxCountryRegion $taxCountryRegion;

    /**
     * <xs:element ref="TaxCode"/>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode
     * @since 1.0.0
     */
    private TaxCode $taxCode;

    /**
     * <xs:element ref="TaxPercentage"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $taxPercentage = null;

    /**
     * <xs:element ref="TaxAmount"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $taxAmount = null;

    /**
     * <!-- Estrutura de Taxa -->
     * <pre>
     * &lt;xs:complexType name="Tax"&gt;
     *     &lt;xs:sequence&gt;
     *         &lt;xs:element ref="TaxType"/&gt;
     *         &lt;xs:element ref="TaxCountryRegion"/&gt;
     *         &lt;xs:element ref="TaxCode"/&gt;
     *         &lt;xs:choice&gt;
     *             &lt;xs:element ref="TaxPercentage"/&gt;
     *             &lt;xs:element ref="TaxAmount"/&gt;
     *         &lt;/xs:choice&gt;
     *     &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get TaxType
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType
     * @since 1.0.0
     */
    public function getTaxType(): TaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxType->get()));
        return $this->taxType;
    }

    /**
     * Set Tax Type
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType $taxType
     * @return void
     * @since 1.0.0
     */
    public function setTaxType(TaxType $taxType): void
    {
        $this->taxType = $taxType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxType->get()));
    }

    /**
     * Get TaxCountryRegion
     * @return \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    public function getTaxCountryRegion(): TaxCountryRegion
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxCountryRegion->get()));
        return $this->taxCountryRegion;
    }

    /**
     * Set TaxCountryRegion
     * @param Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion
     * @return void
     * @since 1.0.0
     */
    public function setTaxCountryRegion(TaxCountryRegion $taxCountryRegion): void
    {
        $this->taxCountryRegion = $taxCountryRegion;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxCountryRegion->get()));
    }

    /**
     * Get Tax Code
     * @return Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode
     * @since 1.0.0
     */
    public function getTaxCode(): TaxCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxCode->get()));
        return $this->taxCode;
    }

    /**
     * Set Tax Code
     * @param Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(TaxCode $taxCode): void
    {
        $this->taxCode = $taxCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxCode->get()));
    }

    /**
     * Get Tax Percentage
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxPercentage(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxPercentage === null ?
                        "null" : \strval($this->taxPercentage)));
        return $this->taxPercentage;
    }

    /**
     * Set Tax Percentage
     * @param float|null $taxPercentage
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxPercentage(?float $taxPercentage): void
    {
        if ($this->getTaxAmount() !== null && $taxPercentage !== null) {
            $msg = "Tax Percentage and Tax Amount can not be setted at the same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxPercentage = $taxPercentage;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxPercentage === null ?
                        "null" : \strval($this->taxPercentage)));
    }

    /**
     * Get Tax Amount
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxAmount === null ?
                        "null" : \strval($this->taxAmount)));
        return $this->taxAmount;
    }

    /**
     * Set Tax Amout
     * @param float|null $taxAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxAmount(?float $taxAmount): void
    {
        if ($this->getTaxPercentage() !== null && $taxAmount !== null) {
            $msg = "Tax Percentage and Tax Amount can not be setted at the same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxAmount = $taxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxAmount === null ?
                        "null" : \strval($this->taxAmount)));
    }

    /**
     * Create xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== A2Line::N_LINE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                A2Line::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $taxNode = $node->addChild(static::N_TAX);
        $taxNode->addChild(static::N_TAXTYPE, $this->getTaxType()->get());
        $taxNode->addChild(static::N_TAXCOUNTRYREGION,
            $this->getTaxCountryRegion()->get());
        $taxNode->addChild(static::N_TAXCODE, $this->getTaxCode()->get());
        if ($this->getTaxPercentage() !== null) {
            $taxNode->addChild(static::N_TAXPERCENTAGE,
                $this->floatFormat($this->getTaxPercentage()));
        }
        if ($this->getTaxAmount() !== null) {
            $taxNode->addChild(static::N_TAXAMOUNT,
                $this->floatFormat($this->getTaxAmount()));
        }
        return $taxNode;
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_TAX) {
            $msg = sprintf("Node name should be '%s' but is '%s", static::N_TAX,
                $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxCode(new TaxCode((string) $node->{static::N_TAXCODE}));
        $this->setTaxType(new TaxType((string) $node->{static::N_TAXTYPE}));
        $this->setTaxCountryRegion(new TaxCountryRegion((string) $node->{static::N_TAXCOUNTRYREGION}));
        if ($node->{static::N_TAXPERCENTAGE}->count() > 0) {
            $this->setTaxPercentage((float) $node->{static::N_TAXPERCENTAGE});
        }
        if ($node->{static::N_TAXAMOUNT}->count() > 0) {
            $this->setTaxAmount((float) $node->{static::N_TAXAMOUNT});
        }
    }
}