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
     * <xs:element ref="LineNumber"/>
     *
     * @var int
     * @since 1.0.0
     */
    private int $lineNumber;

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