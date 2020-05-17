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
     * <xs:element ref="LineNumber"/>
     * Node Name
     * @since 1.0.0
     */
    const N_LINENUMBER = "LineNumber";

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
     * <xs:element ref="SettlementAmount" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_SETTLEMENTAMOUNT = "SettlementAmount";

    /**
     * <xs:element ref="DebitAmount"/>
     * Node name
     * @since 1.0.0
     */
    const N_TAXEXEMPTIONCODE = "TaxExemptionCode";

    /**
     * <xs:element ref="LineNumber"/>
     *
     * @var int
     * @since 1.0.0
     */
    private int $lineNumber;

    /**
     * <xs:element ref="DebitAmount"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $debitAmount = null;

    /**
     * <xs:element ref="CreditAmount"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $creditAmount = null;

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
     * <xs:element ref="LineNumber"/>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get LineNumber<br>
     * <xs:element ref="LineNumber"/>
     * @return int
     * @since 1.0.0
     */
    public function getLineNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->lineNumber)));
        return $this->lineNumber;
    }

    /**
     * Set LineNumber<br>
     * <xs:element ref="LineNumber"/>
     * @param int $lineNumber
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setLineNumber(int $lineNumber): void
    {
        if ($lineNumber < 1) {
            $msg = "Line Number can not be less than 1";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new \Rebelo\SaftPt\AuditFile\AuditFileException($msg);
        }
        $this->lineNumber = $lineNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->lineNumber));
    }

    /**
     * <xs:element ref="DebitAmount"/>
     * @return float|null
     * @since 1.0.0
     */
    public function getDebitAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->debitAmount === null ? "null" : \strval($this->debitAmount)));
        return $this->debitAmount;
    }

    /**
     * <xs:element ref="DebitAmount"/>
     * @param float|null $debitAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDebitAmount(?float $debitAmount): void
    {
        if ($debitAmount < 0.0) {
            $msg = "Debit Amout can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if ($this->getCreditAmount() !== null && $debitAmount !== null) {
            $msg = "Debit Amout onlu can be setted if Credit Amount is null";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->debitAmount = $debitAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->debitAmount === null ? "null" : \strval($this->debitAmount)));
    }

    /**
     * <xs:element ref="CreditAmount"/>
     * @return float|null
     * @since 1.0.0
     */
    public function getCreditAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->creditAmount === null ? "null" : \strval($this->creditAmount)));
        return $this->creditAmount;
    }

    /**
     * <xs:element ref="CreditAmount"/>
     * @param float|null $creditAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setCreditAmount(?float $creditAmount): void
    {
        if ($creditAmount < 0.0) {
            $msg = "Credit Amout can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if ($this->getDebitAmount() !== null && $creditAmount !== null) {
            $msg = "Credit Amout onlu can be setted if Debit Amount is null";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->creditAmount = $creditAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->creditAmount === null ? "null" : \strval($this->creditAmount)));
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
     * Create common xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $lineNode = $node->addChild(ALine::N_LINE);
        $lineNode->addChild(ALine::N_LINENUMBER, \strval($this->getLineNumber()));
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
            $msg = \sprintf("Node name should be '%s' but is '%s",
                ALine::N_LINE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if ($this->getDebitAmount() !== null && $this->getCreditAmount() !== null) {
            $msg = "Debit and Credit amount can not be setted at same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if ($this->getDebitAmount() !== null) {
            $node->addChild(static::N_DEBITAMOUNT,
                $this->floatFormat($this->getDebitAmount()));
            return;
        }
        if ($this->getCreditAmount() !== null) {
            $node->addChild(static::N_CREDITAMOUNT,
                $this->floatFormat($this->getCreditAmount()));
            return;
        }
        $msg = "No Debit or Credit amount setted";
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__." '%s'", $msg));
        throw new AuditFileException($msg);
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
            $msg = \sprintf("Node name should be '%s' but is '%s",
                ALine::N_LINE, $node->getName());
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