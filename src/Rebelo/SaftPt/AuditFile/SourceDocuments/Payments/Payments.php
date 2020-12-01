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

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Payments<br>
 * 4.4 – Payments
 * Receipts issued after the entry into force of this structure
 * should be exported on this table.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Payments extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ASourceDocuments
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
     * $array[type][serie][number] = $payment
     * \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment[]
     * @var array
     */
    protected array $order = array();

    /**
     * Payments<br>
     * 4.4 – Payments
     * Receipts issued after the entry into force of this structure
     * should be exported on this table.
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get Number of entries<br>
     * The field shall contain the total number of issued receipts,
     * including the documents which content in field
     * 4.4.4.9.1. – PaymentStatus is type “A”.
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'", \strval($this->numberOfEntries)
                )
            );
        return $this->numberOfEntries;
    }

    /**
     * Get if is set NumberOfEntries
     * @return bool
     * @since 1.0.0
     */
    public function issetNumberOfEntries(): bool
    {
        return isset($this->numberOfEntries);
    }

    /**
     * Set Number of entries<br>
     * The field shall contain the total number of issued receipts,
     * including the documents which content in field
     * 4.4.4.9.1. – PaymentStatus is type “A”.
     * @param int $numberOfEntries
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): bool
    {
        if ($numberOfEntries < 0) {
            $msg    = "Number of entries can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NumberOfEntries_not_valid");
        } else {
            $return = true;
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->numberOfEntries)
                )
            );
        return $return;
    }

    /**
     * Get total debit<br>
     * The field shall contain the control sum of field 4.4.4.14.4. – DebitAmount,
     * excluding the documents which content in field.4.4.9.1. – PaymentStatus is “A”.
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'", \strval($this->totalDebit)
                )
            );
        return $this->totalDebit;
    }

    /**
     * Get if is set TotalDebit
     * @return bool
     * @since 1.0.0
     */
    public function issetTotalDebit(): bool
    {
        return isset($this->totalDebit);
    }

    /**
     * Set total debit<br>     *
     * The field shall contain the control sum of field 4.4.4.14.4. – DebitAmount,
     * excluding the documents which content in field.4.4.9.1. – PaymentStatus is “A”.
     * @param float $totalDebit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): bool
    {
        if ($totalDebit < 0) {
            $msg    = "Total debit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalDebit_not_valid");
        } else {
            $return = true;
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", \strval($this->totalDebit)
                )
            );
        return $return;
    }

    /**
     * Get total Credit<br>
     * The field shall contain the control sum of field 4.4.4.14.5. – CreditAmount,
     * excluding the documents which content in field 4.4.4.9.1. – PaymentStatus is “A”.
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'", \strval($this->totalCredit)
                )
            );
        return $this->totalCredit;
    }

    /**
     * Get if is set TotalCredit
     * @return bool
     * @since 1.0.0
     */
    public function issetTotalCredit(): bool
    {
        return isset($this->totalCredit);
    }

    /**
     * Set total dredit
     * @param float $totalCredit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): bool
    {
        if ($totalCredit < 0.0) {
            $msg    = "Total credit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalDebit_not_valid");
        } else {
            $return = true;
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", \strval($this->totalCredit)
                )
            );
        return $return;
    }

    /**
     * Add Payment to stack<br>
     * When this method is invoked a new instance of Payment is created,
     * add to the stack then returned to be populated
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment
     * @since 1.0.0
     */
    public function addPayment(): Payment
    {
        // Every time that a payment is add the order is reseted and is
        // contructed when called
        $this->order     = array();
        $payment         = new Payment($this->getErrorRegistor());
        $this->payment[] = $payment;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." Payment add to index "
        );
        return $payment;
    }

    /**
     * Gets as payment<br>
     * &lt;xs:element name="Payment" minOccurs="0" maxOccurs="unbounded"&gt;
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
     * Get payment order by type/serie/number<br>
     * Ex: $stack[type][serie][InvoiceNo] = Payment<br>
     * If a error exist, th error is add to ValidationErrors stack
     * @return array<string, array<string , array<int, \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment>>>
     * @since 1.0.0
     */
    public function getOrder(): array
    {
        if (\count($this->order) > 0) {
            return $this->order;
        }

        foreach ($this->getPayment() as $k => $payment) {
            /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
            if ($payment->issetPaymentRefNo() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("payment_at_index_no_number"), $k
                );
                $this->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_PAYMENTREFNO);
                \Logger::getLogger(\get_class($this))->error($msg);
                continue;
            }

            list($type, $serie, $no) = \explode(
                " ",
                \str_replace("/", " ", $payment->getPaymentRefNo())
            );

            $type = \strval($type);
            $serie = \strval($serie);
            
            if (\array_key_exists($type, $this->order)) {
                if (\array_key_exists($serie, $this->order[$type])) {
                    if (\array_key_exists(\intval($no), $this->order[$type][$serie])) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("duplicated_payment"),
                            $payment->getPaymentRefNo()
                        );
                        $this->getErrorRegistor()->addValidationErrors($msg);
                        $payment->addError($msg, Payment::N_PAYMENT);
                        \Logger::getLogger(\get_class($this))->error($msg);
                    }
                }
            }
            $this->order[$type][$serie][\intval($no)] = $payment;
        }

        $cloneOrder = $this->order;

        foreach (\array_keys($cloneOrder) as $type) {
            foreach (\array_keys($cloneOrder[$type]) as $serie) {
                ksort($this->order[$type][$serie], SORT_NUMERIC);
            }
            ksort($this->order[$type], SORT_STRING);
        }
        ksort($this->order, SORT_STRING);

        return $this->order;
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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $payNode = $node->addChild(static::N_PAYMENTS);

        if (isset($this->numberOfEntries)) {
            $payNode->addChild(
                static::N_NUMBEROFENTRIES, strval($this->getNumberOfEntries())
            );
        } else {
            $payNode->addChild(static::N_NUMBEROFENTRIES);
            $this->getErrorRegistor()->addOnCreateXmlNode("NumberOfEntries_not_valid");
        }

        if (isset($this->totalDebit)) {
            $payNode->addChild(
                static::N_TOTALDEBIT, strval($this->getTotalDebit())
            );
        } else {
            $payNode->addChild(static::N_TOTALDEBIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalDebit_not_valid");
        }

        if (isset($this->totalCredit)) {
            $payNode->addChild(
                static::N_TOTALCREDIT, strval($this->getTotalCredit())
            );
        } else {
            $payNode->addChild(static::N_TOTALCREDIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalCredit_not_valid");
        }

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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", static::N_PAYMENTS,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $pCount = $node->{Payment::N_PAYMENT}->count();
        for ($n = 0; $n < $pCount; $n++) {
            $this->addPayment()->parseXmlNode($node->{Payment::N_PAYMENT}[$n]);
        }
    }
}