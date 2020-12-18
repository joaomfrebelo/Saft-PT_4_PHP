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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * ALine, abstract class of Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class ALine extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_LINE = "Line";

    /**
     * &lt;xs:element ref="LineNumber"/&gt;
     * Node Name
     * @since 1.0.0
     */
    const N_LINENUMBER = "LineNumber";

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
     *
     * Node name
     * @since 1.0.0
     */
    const N_TAXEXEMPTIONREASON = "TaxExemptionReason";

    /**
     * &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_SETTLEMENTAMOUNT = "SettlementAmount";

    /**
     * &lt;xs:element ref="DebitAmount"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TAXEXEMPTIONCODE = "TaxExemptionCode";

    /**
     * &lt;xs:element ref="LineNumber"/&gt;
     *
     * @var int
     * @since 1.0.0
     */
    private int $lineNumber;

    /**
     * &lt;xs:element ref="DebitAmount"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    private ?float $debitAmount = null;

    /**
     * &lt;xs:element ref="CreditAmount"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    private ?float $creditAmount = null;

    /**
     * &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $taxExemptionReason = null;

    /**
     * &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null
     * @since 1.0.0
     */
    private ?TaxExemptionCode $taxExemptionCode = null;

    /**
     * &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    private ?float $settlementAmount = null;

    /**
     * &lt;xs:element ref="LineNumber"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get LineNumber<br>
     * Lines should be exported in the same order as on the original receipt.<br>
     * &lt;xs:element ref="LineNumber"/&gt;
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getLineNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->lineNumber)
                )
            );
        return $this->lineNumber;
    }

    /**
     * Get if is set LineNumber
     * @return bool
     * @since 1.0.0
     */
    public function issetLineNumber(): bool
    {
        return isset($this->lineNumber);
    }

    /**
     * Set LineNumber<br>
     * Lines should be exported in the same order as on the original receipt.<br>
     * &lt;xs:element ref="LineNumber"/&gt;
     * @param int $lineNumber
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setLineNumber(int $lineNumber): bool
    {
        if ($lineNumber < 1) {
            $msg    = "Line Number can not be less than 1";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("LineNumber_not_valid");
        } else {
            $return = true;
        }
        $this->lineNumber = $lineNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->lineNumber));
        return $return;
    }

    /**
     * Get DebitAmount<br>
     * Amount of the line of the debit documents.
     * This amount is without tax, after the deduction of the line and header discounts.
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="DebitAmount"/&gt;
     * @return float|null
     * @since 1.0.0
     */
    public function getDebitAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->debitAmount === null ? "null" : \strval($this->debitAmount)
                )
            );
        return $this->debitAmount;
    }

    /**
     * Set DebitAmount<br>
     * Amount of the line of the debit documents.
     * This amount is without tax, after the deduction of the line and header discounts.
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="DebitAmount"/&gt;
     * @param float|null $debitAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setDebitAmount(?float $debitAmount): bool
    {
        try {
            if ($debitAmount < 0.0) {
                $msg = "Debit Amout can not be less than 0.0";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            if ($this->getCreditAmount() !== null && $debitAmount !== null) {
                $msg = "Debit Amout onlu can be set if Credit Amount is null";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("DebitAmount_not_valid");
            $return = false;
        }
        $this->debitAmount = $debitAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->debitAmount === null ? "null" : \strval($this->debitAmount)
                )
            );
        return $return;
    }

    /**
     * Get CreditAmount<br>
     * Amount of the line of the credit documents.
     * This amount is without tax, after the deduction of the line and header discounts.
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="CreditAmount"/&gt;
     * @return float|null
     * @since 1.0.0
     */
    public function getCreditAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->creditAmount === null ? "null" : \strval($this->creditAmount)
                )
            );
        return $this->creditAmount;
    }

    /**
     * Set CreditAmount<br>
     * Amount of the line of the credit documents.
     * This amount is without tax, after the deduction of the line and header discounts.
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="CreditAmount"/&gt;
     * @param float|null $creditAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCreditAmount(?float $creditAmount): bool
    {
        try {
            if ($creditAmount < 0.0) {
                $msg = "Credit Amout can not be less than 0.0";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            if ($this->getDebitAmount() !== null && $creditAmount !== null) {
                $msg = "Credit Amout onlu can be set if Debit Amount is null";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("CreditAmount_not_valid");
            $return = false;
        }
        $this->creditAmount = $creditAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->creditAmount === null ? "null" : \strval($this->creditAmount)
                )
            );
        return $return;
    }

    /**
     * Get Tax ExemptionReason<br>
     * When fields 4.3.4.14.15.4. - TaxPercentage or 4.3.4.14.15.5. - TaxAmount
     * fields are equal to zero, it is required to fill in this field.
     * It must be referred to the applicable legal rule/procedure.
     * This field should also be filled in, for the cases not subject to
     * tax mentioned on table 2.5. – TaxTable
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
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxExemptionReason === null ? "null" :
                    $this->taxExemptionReason
                )
            );
        return $this->taxExemptionReason;
    }

    /**
     * Set Tax ExemptionReason<br>
     * When fields 4.3.4.14.15.4. - TaxPercentage or 4.3.4.14.15.5. - TaxAmount
     * fields are equal to zero, it is required to fill in this field.
     * It must be referred to the applicable legal rule/procedure.
     * This field should also be filled in, for the cases not subject to
     * tax mentioned on table 2.5. – TaxTable
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
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxExemptionReason(?string $taxExemptionReason): bool
    {
        try {
            if ($taxExemptionReason !== null && \strlen($taxExemptionReason) < 6) {
                $msg = "Tax Exemption Reason can not have less than 6 caracters";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->taxExemptionReason = $taxExemptionReason === null ? null :
                $this->valTextMandMaxCar($taxExemptionReason, 60, __METHOD__);

            $return = true;
        } catch (AuditFileException $e) {
            $this->taxExemptionReason = $taxExemptionReason;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxExemptionReason_not_valid");
            $return                   = false;
        }
        return $return;
    }

    /**
     * Get TaxExemptionCode<br>
     * It shall be filled in with the code of the reason for exemption or
     * non-settlement, which is included in the
     * "Manual de Integração de Software – Comunicação das Faturas à
     * AT" (Software Integration Manual - Communication of the Invoices to Tax and Customs Authority).
     * The filling is required when fields 4.1.4.19.15.4. -
     * TaxPercentage or 4.1.4.19.15.5. - Tax amount are equal to zero.
     * This field shall also be filled in, for the cases not to subject to the
     * taxes mentioned in table 2.5. - TaxTable.<br>
     * &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null
     * @since 1.0.0
     */
    public function getTaxExemptionCode(): ?TaxExemptionCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxExemptionCode === null ? "null" :
                    $this->taxExemptionCode->get()
                )
            );
        return $this->taxExemptionCode;
    }

    /**
     * Set TaxExemptionCode<br>
     * It shall be filled in with the code of the reason for exemption or
     * non-settlement, which is included in the
     * "Manual de Integração de Software – Comunicação das Faturas à
     * AT" (Software Integration Manual - Communication of the Invoices to Tax and Customs Authority).
     * The filling is required when fields 4.1.4.19.15.4. -
     * TaxPercentage or 4.1.4.19.15.5. - Tax amount are equal to zero.
     * This field shall also be filled in, for the cases not to subject to the
     * taxes mentioned in table 2.5. - TaxTable.<br>
     * &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode|null $taxExemptionCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxExemptionCode(?TaxExemptionCode $taxExemptionCode): void
    {
        $this->taxExemptionCode = $taxExemptionCode;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->taxExemptionCode === null ? "null" :
                    $this->taxExemptionCode->get()
                )
            );
    }

    /**
     * Get SettlementAmount<br>
     * Shall present all the discounts
     * (the proportion of global discounts for this line and the specific of the same line)
     * affecting the amount on field 4.1.4.20.3. – GrossTotal.<br>
     * &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     * @return float|null
     * @since 1.0.0
     */
    public function getSettlementAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->settlementAmount === null ? "null" :
                    \strval($this->settlementAmount)
                )
            );
        return $this->settlementAmount;
    }

    /**
     * set SettlementAmount<br>
     * Shall present all the discounts
     * (the proportion of global discounts for this line and the specific of the same line)
     * affecting the amount on field 4.1.4.20.3. – GrossTotal.<br>
     * &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     * @param float|null $settlementAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSettlementAmount(?float $settlementAmount): bool
    {
        if ($settlementAmount !== null && $settlementAmount < 0.0) {
            $msg    = "Settlement Amout can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("SettlementAmount_not_valid");
        } else {
            $return = true;
        }
        $this->settlementAmount = $settlementAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->settlementAmount === null ? "null" :
                    \strval($this->settlementAmount)
                )
            );
        return $return;
    }

    /**
     * Create common xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $lineNode = $node->addChild(ALine::N_LINE);

        if (isset($this->lineNumber)) {
            $lineNode->addChild(
                ALine::N_LINENUMBER,
                \strval($this->getLineNumber())
            );
        } else {
            $lineNode->addChild(ALine::N_LINENUMBER);
            $this->getErrorRegistor()->addOnCreateXmlNode("LineNumber_not_valid");
        }

        return $lineNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    protected function createXmlNodeDebitCreditNode(\SimpleXMLElement $node): void
    {
        if ($node->getName() !== static::N_LINE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                ALine::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("LineNumber_not_valid");
        }

        if ($this->getDebitAmount() !== null && $this->getCreditAmount() !== null) {
            $msg = "Debit and Credit amount can not be set at same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("Debit_and_Credit_setted_at_same_time");
        }

        if ($this->getDebitAmount() === null && $this->getCreditAmount() === null) {
            $msg = "No Debit or Credit amount setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("No_Debit_or_Credit_setted");
        }

        if ($this->getDebitAmount() !== null) {
            $node->addChild(
                static::N_DEBITAMOUNT,
                $this->floatFormat($this->getDebitAmount())
            );
        }

        if ($this->getCreditAmount() !== null) {
            $node->addChild(
                static::N_CREDITAMOUNT,
                $this->floatFormat($this->getCreditAmount())
            );
        }
    }

    /**
     * Parse common xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== ALine::N_LINE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                ALine::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setLineNumber((int) $node->{static::N_LINENUMBER});

        if ($node->{static::N_DEBITAMOUNT}->count() > 0) {
            $this->setDebitAmount((float) $node->{static::N_DEBITAMOUNT});
        }

        if ($node->{static::N_CREDITAMOUNT}->count() > 0) {
            $this->setCreditAmount((float) $node->{static::N_CREDITAMOUNT});
        }
    }
}
