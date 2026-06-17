<?php /** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
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

use Decimal\Decimal;
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
 * @since  1.0.0
 */
class SalesInvoices extends ASourceDocuments
{
    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_SALES_INVOICES = "SalesInvoices";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_NUMBER_OF_ENTRIES = "NumberOfEntries";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_TOTAL_DEBIT = "TotalDebit";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_TOTAL_CREDIT = "TotalCredit";

    /**
     * &lt;xs:element ref="NumberOfEntries"/&gt;
     *
     * @var int
     * @since 1.0.0
     */
    protected int $numberOfEntries;

    /**
     * &lt;xs:element ref="TotalDebit"/&gt;
     *
     * @var \Decimal\Decimal
     * @since 1.0.0
     */
    protected Decimal $totalDebit;

    /**
     * &lt;xs:element ref="TotalCredit"/&gt;
     *
     * @var Decimal
     * @since 1.0.0
     */
    protected Decimal $totalCredit;

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     * @since 1.0.0
     */
    protected array $invoice = array();

    /**
     * $array[type][serial][number] = $invoice
     * \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     *
     * @var mixed[]
     */
    protected array $order = [];

    /**
     * <br>
     * This table shall present all sales documents and correcting documents
     * issued by the company, including cancelled documents,
     * duly marked, enabling a verification of the documents’
     * numbering sequence within each documental series,
     * which should have an annual numbering at least.<br>
     * Type of documents to be exported: all documents mentioned in field 4.1.4.8. – InvoiceType
     *
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     *
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
     *
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        AAuditFile::$logger?->info(\sprintf(__METHOD__ . " get '%s'", $this->numberOfEntries));
        return $this->numberOfEntries;
    }

    /**
     * Get if is set NumberOfEntries
     *
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
     *
     * @param int $numberOfEntries
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): bool
    {
        if ($numberOfEntries < 0) {
            $msg = "NumberOdEntries can not be less than zero";
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NumberOdEntries_not_valid");
        } else {
            $return = true;
        }
        $this->numberOfEntries = $numberOfEntries;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
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
     *
     * @return Decimal
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalDebit(): Decimal
    {
        AAuditFile::$logger?->info(\sprintf(__METHOD__ . " get '%s'", $this->totalDebit));
        return $this->totalDebit;
    }

    /**
     * Get if is set TotalDebit
     *
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
     *
     * @param Decimal $totalDebit
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalDebit(Decimal $totalDebit): bool
    {
        if ($totalDebit->compareTo("0.0") < 0) {
            $msg = "TotalDebit can not be less than zero";
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalDebit_not_valid");
        } else {
            $return = true;
        }
        $this->totalDebit = $totalDebit;
        AAuditFile::$logger?->debug(\sprintf(__METHOD__ . " set to '%s'", $this->totalDebit));
        return $return;
    }

    /**
     * Get TotalCredit<br>
     * The field shall contain the control sum of field
     * 4.1.4.19.14. – CreditAmount, excluding the documents which
     * content in field 4.1.4.3.1. - InvoiceStatus is “A” or “F”.
     *
     * @return Decimal
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalCredit(): Decimal
    {
        AAuditFile::$logger?->info(\sprintf(__METHOD__ . " get '%s'", $this->totalCredit));
        return $this->totalCredit;
    }

    /**
     * Get if is set TotalCredit
     *
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
     *
     * @param Decimal $totalCredit
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalCredit(Decimal $totalCredit): bool
    {
        if ($totalCredit->compareTo("0.0") < 0) {
            $msg = "TotalCredit can not be less than zero";
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalCredit_not_valid");
        } else {
            $return = true;
        }
        $this->totalCredit = $totalCredit;
        AAuditFile::$logger?->debug(\sprintf(__METHOD__ . " set to '%s'", $this->totalCredit));
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
        AAuditFile::$logger?->info(__METHOD__ . " get '%s'");
        return $this->invoice;
    }

    /**
     * Get Invoice<br>
     * When this method is invoked a new Invoice instance is created, add to the
     * stack and returned to be populated<br>
     * &lt;xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice
     * @since 1.0.0
     */
    public function addInvoice(): Invoice
    {
        // Every time that an invoice is added the order is reset and is
        // constructed when called
        $this->order     = array();
        $invoice         = new Invoice($this->getErrorRegistor());
        $this->invoice[] = $invoice;
        AAuditFile::$logger?->debug(__METHOD__ . "Invoice add to index");
        return $invoice;
    }

    /**
     * Get invoices order by type/serial/number<br>
     * Ex: $stack[type][serial][InvoiceNo] = Invoice<br>
     * If an error exist, th error is added to ValidationErrors stack
     *
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
                $invoice->addError($msg, Invoice::N_INVOICE_NO);
                AAuditFile::$logger?->error($msg);
                continue;
            }

            list($type, $serial, $no) = \explode(
                " ",
                \str_replace("/", " ", $invoice->getInvoiceNo())
            );

            if (\array_key_exists($type, $this->order)) {
                if (\array_key_exists($serial, $this->order[$type])) {
                    if (\array_key_exists(
                        \intval($no),
                        $this->order[$type][$serial]
                    )
                    ) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("duplicated_invoice"),
                            $invoice->getInvoiceNo()
                        );
                        $this->getErrorRegistor()->addValidationErrors($msg);
                        $invoice->addError($msg, Invoice::N_INVOICE);
                        AAuditFile::$logger?->error($msg);
                    }
                }
            }
            $this->order[$type][$serial][\intval($no)] = $invoice;
        }

        $cloneOrder = $this->order;

        foreach (\array_keys($cloneOrder) as $type) {
            foreach (\array_keys($cloneOrder[$type]) as $serial) {
                ksort($this->order[$type][$serial], SORT_NUMERIC);
            }
            ksort($this->order[$type], SORT_STRING);
        }
        ksort($this->order, SORT_STRING);

        return $this->order;
    }

    /**
     * Create Xml node
     *
     * @param \SimpleXMLElement $node
     *
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== SourceDocuments::N_SOURCE_DOCUMENTS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCE_DOCUMENTS, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $salesNode = $node->addChild(static::N_SALES_INVOICES);

        if (isset($this->numberOfEntries)) {
            $salesNode->addChild(
                static::N_NUMBER_OF_ENTRIES, \strval($this->getNumberOfEntries())
            );
        } else {
            $salesNode->addChild(static::N_NUMBER_OF_ENTRIES);
            $this->getErrorRegistor()->addOnCreateXmlNode("NumberOfEntries_not_valid");
        }

        if (isset($this->totalDebit)) {
            $salesNode->addChild(
                static::N_TOTAL_DEBIT, $this->floatFormat($this->getTotalDebit())
            );
        } else {
            $salesNode->addChild(static::N_TOTAL_DEBIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalDebit_not_valid");
        }

        if (isset($this->totalCredit)) {
            $salesNode->addChild(
                static::N_TOTAL_CREDIT,
                $this->floatFormat($this->getTotalCredit())
            );
        } else {
            $salesNode->addChild(static::N_TOTAL_CREDIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalCredit_not_valid");
        }

        foreach ($this->getInvoice() as $invoice) {
            $invoice->createXmlNode($salesNode);
        }

        return $salesNode;
    }

    /**
     * Parse Xml node
     *
     * @param \SimpleXMLElement $node
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== static::N_SALES_INVOICES) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SALES_INVOICES, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int)$node->{static::N_NUMBER_OF_ENTRIES});
        $this->setTotalDebit(new Decimal((string)$node->{static::N_TOTAL_DEBIT}));
        $this->setTotalCredit(new Decimal((string)$node->{static::N_TOTAL_CREDIT}));

        $nMax = $node->{Invoice::N_INVOICE}->count();
        for ($n = 0; $n < $nMax; $n++) {
            $this->addInvoice()->parseXmlNode(
                $node->{Invoice::N_INVOICE}[$n]
            );
        }
    }
}
