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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Payments
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Payments extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTS = "Payments";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_NUMBEROFENTRIES = "NumberOfEntries";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALDEBIT = "TotalDebit";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALCREDIT = "TotalCredit";

    /**
     * <xs:element ref="NumberOfEntries"/>
     * @var int
     * @since 1.0.0
     */
    private int $numberOfEntries;

    /**
     * <xs:element ref="TotalDebit"/>
     * @var float
     * @since 1.0.0
     */
    private float $totalDebit;

    /**
     * <xs:element ref="TotalCredit"/>
     * @var float
     * @since 1.0.0
     */
    private float $totalCredit;

    /**
     * <xs:element name="Payment" minOccurs="0" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment[]     *
     * @since 1.0.0
     */
    private array $payment = array();

    /**
     * Payments
     * <pre>
     *  &lt;xs:element name="Payments" minOccurs="0"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="NumberOfEntries"/&gt;
     *           &lt;xs:element ref="TotalDebit"/&gt;
     *           &lt;xs:element ref="TotalCredit"/&gt;
     *           &lt;xs:element name="Payment" minOccurs="0" maxOccurs="unbounded" /&gt;
     *           &lt;/xs:element&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Number of entries
     * @return int
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->numberOfEntries)));
        return $this->numberOfEntries;
    }

    /**
     * Set Number of entries
     * @param int $numberOfEntries
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): void
    {
        if ($numberOfEntries < 0) {
            $msg = "Number of entries can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->numberOfEntries)));
    }

    /**
     * Get total debit
     * @return float
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->totalDebit)));
        return $this->totalDebit;
    }

    /**
     * Set total debit
     * @param float $totalDebit
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): void
    {
        if ($totalDebit < 0) {
            $msg = "Total debit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->totalDebit)));
    }

    /**
     * Get total dredit
     * @return float
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->totalCredit)));
        return $this->totalCredit;
    }

    /**
     * Set total dredit
     * @param float $totalCredit
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): void
    {
        if ($totalCredit < 0.0) {
            $msg = "Total credit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->totalCredit)));
    }

    /**
     * Add to payment stack
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return int
     * @since 1.0.0
     */
    public function addToPayment(Payment $payment): int
    {
        if (\count($this->payment) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->payment);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->payment[$index] = $payment;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " Payment add to index ".\strval($index));
        return $index;
    }

    /**
     * isset payment
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetPayment(int $index): bool
    {
        return isset($this->payment[$index]);
    }

    /**
     * unset payment
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetPayment(int $index): void
    {
        unset($this->payment[$index]);
    }

    /**
     * Gets as payment<br>
     * <xs:element name="Payment" minOccurs="0" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment[]
     * @since 1.0.0
     */
    public function getPayment(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "Payment"));
        return $this->payment;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== SourceDocuments::N_SOURCEDOCUMENTS) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $payNode = $node->addChild(static::N_PAYMENTS);
        $payNode->addChild(
            static::N_NUMBEROFENTRIES, strval($this->getNumberOfEntries())
        );
        $payNode->addChild(
            static::N_TOTALDEBIT, strval($this->getTotalDebit())
        );
        $payNode->addChild(
            static::N_TOTALCREDIT, strval($this->getTotalCredit())
        );

        foreach ($this->getPayment() as $payment) {
            /* @var $payment Payment */
            $payment->createXmlNode($payNode);
        }

        return $payNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_PAYMENTS) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                static::N_PAYMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $pCount = $node->{Payment::N_PAYMENT}->count();
        for ($n = 0; $n < $pCount; $n++) {
            $payment = new Payment();
            $payment->parseXmlNode($node->{Payment::N_PAYMENT}[$n]);
            $this->addToPayment($payment);
        }
    }
}