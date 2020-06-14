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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * MovementTax
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementTax extends \Rebelo\SaftPt\AuditFile\AAuditFile
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
     * <xs:element name="TaxType" type="SAFTPTMovementTaxType"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType
     * @since 1.0.0
     */
    private MovementTaxType $taxType;

    /**
     * <xs:element ref="TaxCountryRegion"/>
     * @var \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    private TaxCountryRegion $taxCountryRegion;

    /**
     * <xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/>
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode
     * @since 1.0.0
     */
    private MovementTaxCode $taxCode;

    /**
     * <xs:element ref="TaxPercentage"/>
     * @var type
     * @since 1.0.0
     */
    private float $taxPercentage;

    /**
     * MovementTax
     * <pre>
     * &lt;xs:complexType name="MovementTax"&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element name="TaxType" type="SAFTPTMovementTaxType"/&gt;
     *        &lt;xs:element ref="TaxCountryRegion"/&gt;
     *        &lt;xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/&gt;
     *        &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * <xs:element name="TaxType" type="SAFTPTMovementTaxType"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType
     * @since 1.0.0
     */
    public function getTaxType(): MovementTaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxType->get()));
        return $this->taxType;
    }

    /**
     * <xs:element name="TaxType" type="SAFTPTMovementTaxType"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType $taxType
     * @return void
     * @since 1.0.0
     */
    public function setTaxType(MovementTaxType $taxType): void
    {
        $this->taxType = $taxType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxType->get()));
    }

    /**
     * <xs:element ref="TaxCountryRegion"/>
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
     * <xs:element ref="TaxCountryRegion"/>
     * @param \Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion
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
     * <xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode
     * @since 1.0.0
     */
    public function getTaxCode(): MovementTaxCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxCode->get()));
        return $this->taxCode;
    }

    /**
     * <xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(MovementTaxCode $taxCode): void
    {
        $this->taxCode = $taxCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxCode->get()));
    }

    /**
     * <xs:element ref="TaxPercentage"/>
     * @return float
     * @since 1.0.0
     */
    public function getTaxPercentage(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->taxPercentage)));
        return $this->taxPercentage;
    }

    /**
     * <xs:element ref="TaxPercentage"/>
     * @param float $taxPercentage
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxPercentage(float $taxPercentage): void
    {
        if ($taxPercentage < 0.0) {
            $msg = "TaxPercentage can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxPercentage = $taxPercentage;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->taxPercentage)));
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== Line::N_LINE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", Line::N_LINE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $movTaxNode = $node->addChild(static::N_TAX);

        $movTaxNode->addChild(
            static::N_TAXTYPE, $this->getTaxType()->get()
        );

        $movTaxNode->addChild(
            static::N_TAXCOUNTRYREGION, $this->getTaxCountryRegion()->get()
        );

        $movTaxNode->addChild(
            static::N_TAXCODE, $this->getTaxCode()->get()
        );

        $movTaxNode->addChild(
            static::N_TAXPERCENTAGE,
            $this->floatFormat($this->getTaxPercentage())
        );

        return $movTaxNode;
    }

    /**
     * Parse the XML node
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_TAX) {
            $msg = \sprintf("Node name should be '%s' but is '%s'",
                static::N_TAX, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $type = new MovementTaxType((string) $node->{static::N_TAXTYPE});
        $this->setTaxType($type);

        $country = new TaxCountryRegion((string) $node->{static::N_TAXCOUNTRYREGION});
        $this->setTaxCountryRegion($country);

        $code = new MovementTaxCode((string) $node->{static::N_TAXCODE});
        $this->setTaxCode($code);

        $this->setTaxPercentage((float) $node->{static::N_TAXPERCENTAGE});
    }
}