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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\{AAuditFile,
    AuditFileException,
    ErrorRegister,
    SourceDocuments\ASourceDocuments,
    SourceDocuments\SourceDocuments};

/**
 * SalesInvoices<br>
 * This table shall present all sales documents and correcting documents
 * issued by the company, including cancelled documents,
 * duly marked, enabling a verification of the documents’
 * numbering sequence within each documental series,
 * which should have an annual numbering at least.<br>
 * Type of documents to be exported: all documents mentioned in field 4.1.4.8. – InvoiceType
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SalesInvoices extends ASourceDocuments
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SALESINVOICES = "SalesInvoices";

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
     * &lt;xs:element ref="NumberOfEntries"/&gt;
     * @var int
     * @since 1.0.0
     */
    protected int $numberOfEntries;

    /**
     * &lt;xs:element ref="TotalDebit"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $totalDebit;

    /**
     * &lt;xs:element ref="TotalCredit"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $totalCredit;

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     * @since 1.0.0
     */
    protected array $invoice = array();

    /**
     * $array[type][serie][number] = $invoice
     * \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     * @var array
     */
    protected array $order = array();

    /**
     * <br>
     * This table shall present all sales documents and correcting documents
     * issued by the company, including cancelled documents,
     * duly marked, enabling a verification of the documents’
     * numbering sequence within each documental series,
     * which should have an annual numbering at least.<br>
     * Type of documents to be exported: all documents mentioned in field 4.1.4.8. – InvoiceType
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get NumberOfEntries<br>
     * The field shall contain the total number of documents,
     * including the documents which content in field 4.1.4.3.1. -
     * InvoiceStatus is “A” or “F”.
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->numberOfEntries));
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
     * Set NumberOfEntries<br>
     * The field shall contain the total number of documents,
     * including the documents which content in field 4.1.4.3.1. -
     * InvoiceStatus is “A” or “F”.
     * @param int $numberOfEntries
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): bool
    {
        if ($numberOfEntries < 0) {
            $msg    = "NumberOdEntries can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NumberOdEntries_not_valid");
        } else {
            $return = true;
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->numberOfEntries
                )
            );
        return $return;
    }

    /**
     * Get TotalDebit<br>
     * The field shall contain the control sum of field 4.1.4.19.13. -
     * DebitAmount, excluding the documents which content in field 4.1.4.3.1. -
     * InvoiceStatus is “A” or “F”.
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->totalDebit));
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
     * Set TotalDebit<br>
     * The field shall contain the control sum of field 4.1.4.19.13. -
     * DebitAmount, excluding the documents which content in field 4.1.4.3.1. -
     * InvoiceStatus is “A” or “F”.
     * @param float $totalDebit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): bool
    {
        if ($totalDebit < 0) {
            $msg    = "TotalDebit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalDebit_not_valid");
        } else {
            $return = true;
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->totalDebit));
        return $return;
    }

    /**
     * Get TotalCredit<br>
     * The field shall contain the control sum of field
     * 4.1.4.19.14. – CreditAmount, excluding the documents which
     * content in field 4.1.4.3.1. - InvoiceStatus is “A” or “F”.
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->totalCredit));
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
     * Set TotalCredit<br>
     * The field shall contain the control sum of field
     * 4.1.4.19.14. – CreditAmount, excluding the documents which
     * content in field 4.1.4.3.1. - InvoiceStatus is “A” or “F”.
     * @param float $totalCredit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): bool
    {
        if ($totalCredit < 0) {
            $msg    = "TotalCredit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalCredit_not_valid");
        } else {
            $return = true;
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->totalCredit));
        return $return;
    }

    /**
     * Get Invoice Stack<br>
     * &lt;xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     * @since 1.0.0
     */
    public function getInvoice(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get '%s'");
        return $this->invoice;
    }

    /**
     * Get Invoice<br>
     * When this method is invoked a new Invoice instance is created, add to the
     * stack and returned to be populated<br>
     * &lt;xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice
     * @since 1.0.0
     */
    public function addInvoice(): Invoice
    {
        // Every time that an invoice is added the order is reset and is
        // contructed when called
        $this->order     = array();
        $invoice         = new Invoice($this->getErrorRegistor());
        $this->invoice[] = $invoice;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__."Invoice add to index "
        );
        return $invoice;
    }

    /**
     * Get invoices order by type/serie/number<br>
     * Ex: $stack[type][serie][InvoiceNo] = Invoice<br>
     * If a error exist, th error is added to ValidationErrors stack
     * @return array<string, array<string , array<int, \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice>>>
     * @since 1.0.0
     */
    public function getOrder(): array
    {
        if (\count($this->order) > 0) {
            return $this->order;
        }

        foreach ($this->getInvoice() as $k => $invoice) {
            if ($invoice->issetInvoiceNo() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("invoice_at_index_no_number"), $k
                );
                $this->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_INVOICENO);
                \Logger::getLogger(\get_class($this))->error($msg);
                continue;
            }

            list($type, $serie, $no) = \explode(
                " ",
                \str_replace("/", " ", $invoice->getInvoiceNo())
            );

            if (\array_key_exists($type, $this->order)) {
                if (\array_key_exists($serie, $this->order[$type])) {
                    if (\array_key_exists(
                        \intval($no),
                        $this->order[$type][$serie]
                    )
                    ) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("duplicated_invoice"),
                            $invoice->getInvoiceNo()
                        );
                        $this->getErrorRegistor()->addValidationErrors($msg);
                        $invoice->addError($msg, Invoice::N_INVOICE);
                        \Logger::getLogger(\get_class($this))->error($msg);
                    }
                }
            }
            $this->order[$type][$serie][\intval($no)] = $invoice;
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
     * Create Xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== SourceDocuments::N_SOURCEDOCUMENTS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $salesNode = $node->addChild(static::N_SALESINVOICES);

        if (isset($this->numberOfEntries)) {
            $salesNode->addChild(
                static::N_NUMBEROFENTRIES, \strval($this->getNumberOfEntries())
            );
        } else {
            $salesNode->addChild(static::N_NUMBEROFENTRIES);
            $this->getErrorRegistor()->addOnCreateXmlNode("NumberOfEntries_not_valid");
        }

        if (isset($this->totalDebit)) {
            $salesNode->addChild(
                static::N_TOTALDEBIT, $this->floatFormat($this->getTotalDebit())
            );
        } else {
            $salesNode->addChild(static::N_TOTALDEBIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalDebit_not_valid");
        }

        if (isset($this->totalCredit)) {
            $salesNode->addChild(
                static::N_TOTALCREDIT,
                $this->floatFormat($this->getTotalCredit())
            );
        } else {
            $salesNode->addChild(static::N_TOTALCREDIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalCredit_not_valid");
        }

        foreach ($this->getInvoice() as $invoice) {
            $invoice->createXmlNode($salesNode);
        }

        return $salesNode;
    }

    /**
     * Parse Xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SALESINVOICES) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SALESINVOICES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $nMax = $node->{Invoice::N_INVOICE}->count();
        for ($n = 0; $n < $nMax; $n++) {
            $this->addInvoice()->parseXmlNode(
                $node->{Invoice::N_INVOICE}[$n]
            );
        }
    }
}
