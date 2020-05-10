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

/**
 * StockMovement's Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Line extends \Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTax|null
     * @since 1.0.0
     */
    private ?MovementTax $tax = null;

    /**
     * <pre>
     * &lt;xs:element name="Line" maxOccurs="unbounded"&gt;
     * &lt;xs:complexType&gt;
     *     &lt;xs:sequence&gt;
     *         &lt;xs:element ref="LineNumber"/&gt;
     *         &lt;xs:element name="OrderReferences"
     *                     type="OrderReferences" minOccurs="0"
     *                     maxOccurs="unbounded"/&gt;
     *         &lt;xs:element ref="ProductCode"/&gt;
     *         &lt;xs:element ref="ProductDescription"/&gt;
     *         &lt;xs:element ref="Quantity"/&gt;
     *         &lt;xs:element ref="UnitOfMeasure"/&gt;
     *         &lt;xs:element ref="UnitPrice"/&gt;
     *         &lt;xs:element ref="Description"/&gt;
     *         &lt;xs:element name="ProductSerialNumber"
     *                     type="ProductSerialNumber" minOccurs="0"/&gt;
     *         &lt;xs:choice&gt;
     *             &lt;xs:element ref="DebitAmount"/&gt;
     *             &lt;xs:element ref="CreditAmount"/&gt;
     *         &lt;/xs:choice&gt;
     *         &lt;xs:element name="Tax" type="MovementTax" minOccurs="0"/&gt;
     *         &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;
     *         &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     *         &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     *         &lt;xs:element name="CustomsInformation"
     *                     type="CustomsInformation" minOccurs="0"/&gt;
     *     &lt;/xs:sequence&gt;
     *     &lt;xs:assert
     *         test="
     *             if (not(ns:Tax/ns:TaxPercentage) or ((ns:Tax/ns:TaxPercentage !== 0 and not(ns:TaxExemptionReason)) or (ns:Tax/ns:TaxPercentage eq 0 and ns:TaxExemptionReason))) then
     *                 true()
     *             else
     *                 false()"/&gt;
     *     &lt;xs:assert
     *         test="
     *             if ((ns:TaxExemptionReason and not(ns:TaxExemptionCode)) or (ns:TaxExemptionCode and not(ns:TaxExemptionReason))) then
     *                 false()
     *             else
     *                 true()"
     *     /&gt;
     *    &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * &lt;xs:element name="Tax" type="MovementTax" minOccurs="0"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTax|null
     * @since 1.0.0
     */
    public function getTax(): ?MovementTax
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->tax;
    }

    /**
     * &lt;xs:element name="Tax" type="MovementTax" minOccurs="0"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTax|null $tax
     * @return void
     * @since 1.0.0
     */
    public function setTax(?MovementTax $tax): void
    {
        $this->tax = $tax;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $lineNode = parent::createXmlNode($node);


        $lineNode->addChild(static::N_DESCRIPTION, $this->getDescription());

        if ($this->getProductSerialNumber() !== null) {
            $this->getProductSerialNumber()->createXmlNode($lineNode);
        }
        parent::createXmlNodeDebitCreditNode($lineNode);

        if ($this->getTax() !== null) {
            $this->getTax()->createXmlNode($lineNode);
        }

        parent::createXmlNodeTaxExcSettAndCustoms($lineNode);

        return $lineNode;
    }

    /**
     * Parse XML node
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        parent::parseXmlNode($node);

        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->{static::N_TAX}->count() > 0) {
            $movTax = new MovementTax();
            $movTax->parseXmlNode($node->{static::N_TAX});
            $this->setTax($movTax);
        }
    }
}