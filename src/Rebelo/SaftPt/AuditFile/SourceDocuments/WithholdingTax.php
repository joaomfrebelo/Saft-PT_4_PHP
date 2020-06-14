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
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;

/**
 * WithholdingTax
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WithholdingTax extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_WITHHOLDINGTAX = "WithholdingTax";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WITHHOLDINGTAXTYPE = "WithholdingTaxType";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WITHHOLDINGTAXDESCRIPTION = "WithholdingTaxDescription";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WITHHOLDINGTAXAMOUNT = "WithholdingTaxAmount";

    /**
     * <xs:element ref="WithholdingTaxType" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType
     * @since 1.0.0
     */
    private ?WithholdingTaxType $withholdingTaxType = null;

    /**
     * <xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $withholdingTaxDescription = null;

    /**
     * <xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/>
     * @var float
     * @since 1.0.0
     */
    private float $withholdingTaxAmount;

    /**
     * &lt;!-- Estrutura de Retencao na fonte--&gt;
     * &lt;xs:complexType name="WithholdingTax"&gt;
     *     &lt;xs:sequence&gt;
     *         &lt;xs:element ref="WithholdingTaxType" minOccurs="0"/&gt;
     *         &lt;xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car"
     *             minOccurs="0"/&gt;
     *         &lt;xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/&gt;
     *     &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the tax type<br>
     * <xs:element ref="WithholdingTaxType" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType|null
     * @since 1.0.0
     */
    public function getWithholdingTaxType(): ?WithholdingTaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->withholdingTaxType === null ?
                        "null" : $this->withholdingTaxType->get()));

        return $this->withholdingTaxType;
    }

    /**
     * Set the tax type<br>
     * <xs:element ref="WithholdingTaxType" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType|null $WithholdingTaxType
     * @return void
     * @since 1.0.0
     */
    public function setWithholdingTaxType(?WithholdingTaxType $WithholdingTaxType): void
    {
        $this->withholdingTaxType = $WithholdingTaxType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->withholdingTaxType === null ?
                        "null" : $this->withholdingTaxType->get()));
    }

    /**
     * Get the Tax description<br>
     * <xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getWithholdingTaxDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->withholdingTaxDescription === null ?
                        "null" : $this->withholdingTaxDescription));
        return $this->withholdingTaxDescription;
    }

    /**
     * Set the Tax description<br>
     * <xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/>
     * @param string|null $withholdingTaxDescription
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setWithholdingTaxDescription(?string $withholdingTaxDescription): void
    {
        $this->withholdingTaxDescription = $withholdingTaxDescription === null ?
            null : $this->valTextMandMaxCar($withholdingTaxDescription, 60,
                __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->withholdingTaxDescription === null ?
                        "null" : $this->withholdingTaxDescription));
    }

    /**
     * Get Tax amount
     * <xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/>
     * @return float
     * @since 1.0.0
     */
    public function getWithholdingTaxAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->withholdingTaxAmount)));
        return $this->withholdingTaxAmount;
    }

    /**     *
     * Get Tax amount
     * <xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/>
     * @param float $withholdingTaxAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setWithholdingTaxAmount(float $withholdingTaxAmount): void
    {
        if ($withholdingTaxAmount < 0.0) {
            $msg = "Withholding tax amount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->withholdingTaxAmount = $withholdingTaxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->withholdingTaxAmount)));
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== Payment::N_PAYMENT && $node->getName() !== Invoice::N_INVOICE) {
            $msg = \sprintf("Node name should be '%s' or '%s' but is '%s",
                Payment::N_PAYMENT, Invoice::N_INVOICE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $withholTaxNode = $node->addChild(static::N_WITHHOLDINGTAX);
        if ($this->getWithholdingTaxType() !== null) {
            $withholTaxNode->addChild(
                static::N_WITHHOLDINGTAXTYPE,
                $this->getWithholdingTaxType()->get()
            );
        }
        if ($this->getWithholdingTaxDescription() !== null) {
            $withholTaxNode->addChild(
                static::N_WITHHOLDINGTAXDESCRIPTION,
                $this->getWithholdingTaxDescription()
            );
        }
        $withholTaxNode->addChild(
            static::N_WITHHOLDINGTAXAMOUNT,
            $this->floatFormat($this->getWithholdingTaxAmount())
        );

        return $withholTaxNode;
    }

    /**
     * Parse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_WITHHOLDINGTAX) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_WITHHOLDINGTAX, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_WITHHOLDINGTAXTYPE}->count() > 0) {
            $taxType = new WithholdingTaxType(
                (string) $node->{static::N_WITHHOLDINGTAXTYPE}
            );
            $this->setWithholdingTaxType($taxType);
        } else {
            $this->setWithholdingTaxType(null);
        }

        if ($node->{static::N_WITHHOLDINGTAXDESCRIPTION}->count() > 0) {
            $this->setWithholdingTaxDescription(
                (string) $node->{static::N_WITHHOLDINGTAXDESCRIPTION}
            );
        } else {
            $this->setWithholdingTaxDescription(null);
        }

        $this->setWithholdingTaxAmount(
            (float) $node->{static::N_WITHHOLDINGTAXAMOUNT}
        );
    }
}