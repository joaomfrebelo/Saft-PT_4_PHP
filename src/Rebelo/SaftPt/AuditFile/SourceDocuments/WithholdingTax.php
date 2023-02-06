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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;

/**
 * WithholdingTax
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WithholdingTax extends AAuditFile
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
     * &lt;xs:element ref="WithholdingTaxType" minOccurs="0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType|null
     * @since 1.0.0
     */
    private ?WithholdingTaxType $withholdingTaxType = null;

    /**
     * &lt;xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $withholdingTaxDescription = null;

    /**
     * &lt;xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/&gt;
     * @var float
     * @since 1.0.0
     */
    private float $withholdingTaxAmount;

    /**
     * WithholdingTax
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
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get the tax type<br>
     * Indicate the type of withholding tax in this field, filling in:<br>
     * “IRS” – Personal income tax;<br>
     * “IRC” – Corporate income tax;<br>
     * “IS” – Stamp Duty.<br>
     * &lt;xs:element ref="WithholdingTaxType" minOccurs="0"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType|null
     * @since 1.0.0
     */
    public function getWithholdingTaxType(): ?WithholdingTaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->withholdingTaxType === null ?
                    "null" : $this->withholdingTaxType->get()
                )
            );

        return $this->withholdingTaxType;
    }

    /**
     * Set the tax type<br><br>
     * Indicate the type of withholding tax in this field, filling in:<br>
     * “IRS” – Personal income tax;<br>
     * “IRC” – Corporate income tax;<br>
     * “IS” – Stamp Duty.<br>
     * &lt;xs:element ref="WithholdingTaxType" minOccurs="0"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType|null $withholdingTaxType
     * @return void
     * @since 1.0.0
     */
    public function setWithholdingTaxType(?WithholdingTaxType $withholdingTaxType): void
    {
        $this->withholdingTaxType = $withholdingTaxType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->withholdingTaxType === null ?
                    "null" : $this->withholdingTaxType->get()
                )
            );
    }

    /**
     * Get the Tax description<br>
     * Indicate the applicable legal framework.<br>
     * In case WithholdingTaxType = IS, fill in with the corresponding table code.<br>
     * &lt;xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getWithholdingTaxDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->withholdingTaxDescription === null ?
                    "null" : $this->withholdingTaxDescription
                )
            );
        return $this->withholdingTaxDescription;
    }

    /**
     * Set the Tax description<br>
     * Indicate the applicable legal framework.<br>
     * In case WithholdingTaxType = IS, fill in with the corresponding table code.<br>
     * &lt;xs:element name="WithholdingTaxDescription" type="SAFPTtextTypeMandatoryMax60Car" minOccurs="0"/&gt;
     * @param string|null $withholdingTaxDescription
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setWithholdingTaxDescription(?string $withholdingTaxDescription): bool
    {
        try {
            $this->withholdingTaxDescription = $withholdingTaxDescription === null
                    ? null : $this->valTextMandMaxCar(
                        $withholdingTaxDescription, 60, __METHOD__
                    );
            $return                          = true;
        } catch (AuditFileException $e) {
            $this->withholdingTaxDescription = $withholdingTaxDescription;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("WithholdingTaxDescription_not_valid");
            $return                          = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->withholdingTaxDescription === null ?
                    "null" : $this->withholdingTaxDescription
                )
            );
        return $return;
    }

    /**
     * Get Tax amount<br>
     * Fill in withheld tax amount.
     * &lt;xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getWithholdingTaxAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    \strval($this->withholdingTaxAmount)
                )
            );
        return $this->withholdingTaxAmount;
    }

    /**
     * Get if is set WithholdingTaxAmount
     * @return bool
     * @since 1.0.0
     */
    public function issetWithholdingTaxAmount(): bool
    {
        return isset($this->withholdingTaxAmount);
    }

    /**
     * Get Tax amount<br>
     * Fill in withheld tax amount.
     * &lt;xs:element name="WithholdingTaxAmount" type="SAFmonetaryType"/&gt;
     * @param float $withholdingTaxAmount
     * @return bool true if the value is valid
	 * @since 1.0.0
     */
    public function setWithholdingTaxAmount(float $withholdingTaxAmount): bool
    {
        if ($withholdingTaxAmount < 0.0) {
            $msg    = "Withholding tax amount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("WithholdingTaxAmount_not_valid");
        } else {
            $return = true;
        }
        $this->withholdingTaxAmount = $withholdingTaxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->withholdingTaxAmount)
                )
            );
        return $return;
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
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s",
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

        if (isset($this->withholdingTaxAmount)) {
            $withholTaxNode->addChild(
                static::N_WITHHOLDINGTAXAMOUNT,
                $this->floatFormat($this->getWithholdingTaxAmount())
            );
        } else {
            $withholTaxNode->addChild(static::N_WITHHOLDINGTAXAMOUNT);
            $this->getErrorRegistor()->addOnCreateXmlNode("WithholdingTaxAmount_not_valid");
        }

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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_WITHHOLDINGTAX, $node->getName()
            );
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
