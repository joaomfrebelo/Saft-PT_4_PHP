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

namespace Rebelo\SaftPt\Validate;

use Decimal\Decimal;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\References;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices as SaftSalesInvoices;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Validate SalesInvoices table.<br>
 * This class will validate the values of SalesInvoices, the
 * signature hash and dates
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class SalesInvoices extends ADocuments
{

    /**
     * Validate SalesInvoices table.<br>
     * This class will validate the values of SalesInvoices, the
     * signature hash and dates
     *
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile The AuditFile to be validated
     * @param \Rebelo\SaftPt\Sign\Sign           $sign      The sign class to be used to validate the hash, must have the public key defined
     *
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile, Sign $sign)
    {
        AAuditFile::$logger?->debug(__METHOD__);
        parent::__construct($auditFile, $sign);

        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $salesInvoices = $sourceDoc->getSalesInvoices(false);
            $salesInvoices?->setDocTableTotalCalc(
                new DocTableTotalCalc()
            );
        }
    }

    /**
     * Validate the invoices
     *
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        AAuditFile::$logger?->debug(__METHOD__);
        $progressBar = null;
        try {
            if (null === $salesInvoices = $this->auditFile->getSourceDocuments()?->getSalesInvoices(false)) {
                AAuditFile::$logger?->debug(__METHOD__ . " no sales invoices to be validated");
                return $this->isValid;
            }

            $salesInvoices->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($salesInvoices->getInvoice()) === 0) {

                if (!$salesInvoices->getTotalCredit()->equals("0.0")) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "sales_invoice_total_credit_should_be_zero"
                        ), $salesInvoices->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $salesInvoices->addError(
                        $msg, SaftSalesInvoices::N_TOTAL_CREDIT
                    );
                    $this->isValid = false;
                }

                if (!$salesInvoices->getTotalDebit()->equals("0.0")) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "sales_invoice_total_debit_should_be_zero"
                        ), $salesInvoices->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $salesInvoices->addError(
                        $msg, SaftSalesInvoices::N_TOTAL_DEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $salesInvoices->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc        = \count($salesInvoices->getInvoice());
                $section     = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section?->writeln("");
                $section?->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "Invoice"
                    )
                );
                $progressBar?->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serial) {
                    foreach (\array_keys($order[$type][$serial]) as $no) {

                        $progressBar?->advance();

                        $invoice = $order[$type][$serial][$no];
                        list(, $no) = \explode("/", $invoice->getInvoiceNo());
                        if ((string)$type !== $this->lastType || (string)$serial
                            !== $this->lastSerial) {
                            $this->lastHash            = "";
                            $this->lastDocDate         = null;
                            $this->lastSystemEntryDate = null;
                        } else {
                            $noExpected = $this->lastDocNumber + 1;
                            if (\intval($no) !== $noExpected) {
                                do {
                                    $msg = \sprintf(
                                        AuditFile::getI18n()->get("the_document_n_is_missing"),
                                        $type, $serial, $noExpected
                                    );
                                    AAuditFile::$logger?->debug($msg);
                                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                    $this->isValid       = false;
                                    $this->lastDocNumber = $noExpected;
                                    $noExpected++;
                                } while ($no !== \strval($noExpected));
                            }
                        }
                        $this->lastDocNumber = (int)$no;
                        $invoice->setDocTotalCalc(new DocTotalCalc());
                        $this->invoice($invoice);
                        $this->lastType   = (string)$type;
                        $this->lastSerial = (string)$serial;
                    }
                }
            }

            $progressBar?->finish();

            $this->totalCredit();
            $this->totalDebit();
        } catch (\Exception|\Error $e) {
            $this->isValid = false;

            $progressBar?->finish();

            $this->auditFile->getErrorRegistor()
                            ->addExceptionErrors($e->getMessage());

            AAuditFile::$logger?->debug(
                \sprintf(
                    __METHOD__ . " validate error '%s'", $e->getMessage()
                )
            );
        }
        return $this->isValid;
    }

    /**
     * Validate Invoice
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function invoice(Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        try {
            $this->docCredit  = new Decimal("0.0");
            $this->docDebit   = new Decimal("0.0");
            $this->netTotal   = new Decimal("0.0");
            $this->taxPayable = new Decimal("0.0");
            $this->grossTotal = new Decimal("0.0");

            if ($invoice->issetInvoiceNo() === false) {
                $msg = AAuditFile::getI18n()->get("invoice_no_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetInvoiceType() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "invoice_type_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_INVOICE_TYPE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetInvoiceDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_INVOICE_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_system_entry_date_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_SYSTEM_ENTRY_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->sign($invoice);
            $this->invoiceDateAndSystemEntryDate($invoice);
            $this->customerId($invoice);
            $this->documentStatus($invoice);
            $this->lines($invoice);
            $this->totals($invoice);
            $this->shipment($invoice);
            $this->payment($invoice);
            $this->withholdingTax($invoice);
            $this->outOfDateInvoiceTypes($invoice);
        } catch (\Exception|\Error $e) {
            $this->auditFile->getErrorRegistor()
                            ->addExceptionErrors($e->getMessage());
            AAuditFile::$logger?->debug(
                \sprintf(
                    __METHOD__ . " validate error '%s'", $e->getMessage()
                )
            );
            $invoice->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfEntries is equal to the number of invoices
     *
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $salesInvoices = $this->auditFile->getSourceDocuments()?->getSalesInvoices()) {
            return;
        }

        $calculatedNumOfEntries = \count($salesInvoices->getInvoice());
        $numberOfEntries        = $salesInvoices->getNumberOfEntries();
        $test                   = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()
                        ->getSalesInvoices()
                        ->getDocTableTotalCalc()
                        ?->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_invoices"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_NUMBER_OF_ENTRIES);
            AAuditFile::$logger?->info($msg);
        }
        if ($test === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate SalesInvoices TotalDebit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $salesInvoices = $this->auditFile->getSourceDocuments()?->getSalesInvoices()) {
            return;
        }

        $salesInvoices->getDocTableTotalCalc()?->setTotalDebit($this->debit);

        $diff = $this->debit->sub($salesInvoices->getTotalDebit())->abs();

        if ($diff > $this->deltaTable) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_invoices"
                ), $salesInvoices->getTotalDebit(), $this->debit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_TOTAL_DEBIT);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate SalesInvoices TotalCredit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $salesInvoices = $this->auditFile->getSourceDocuments()?->getSalesInvoices()) {
            return;
        }

        $salesInvoices->getDocTableTotalCalc()?->setTotalDebit($this->credit);

        $diff = $this->credit->sub($salesInvoices->getTotalCredit())->abs();

        if ($diff > $this->deltaTable) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_invoices"
                ), $salesInvoices->getTotalCredit(), $this->credit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_TOTAL_CREDIT);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(Invoice $invoice): void
    {
        if ($invoice->issetDocumentStatus() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_DOCUMENT_STATUS);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $invoice->getDocumentStatus();

        if ($status->getInvoiceStatusDate() < $invoice->getSystemEntryDate()) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, DocumentStatus::N_INVOICE_STATUS_DATE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getInvoiceStatus() === InvoiceStatus::A && $status->getReason() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, DocumentStatus::N_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate if the customerID of the Invoice if is set and if exits in
     * the customer table
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function customerId(Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if ($invoice->issetCustomerID()) {
            $allCustomer = $this->auditFile->getMasterFiles()->getAllCustomerID();
            if (\in_array($invoice->getCustomerID(), $allCustomer) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "customerID_not_exits"
                    ), $invoice->
                    getCustomerID(), $invoice->getInvoiceNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_CUSTOMER_ID);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "customerID_not_defined_in_document"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_CUSTOMER_ID);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the Invoice
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function lines(Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (\count($invoice->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_INVOICE_NO);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $n = 0;
        /* @var $lineNoStack int[] */
        $lineNoStack = array();
        $lineNoError = false;
        //$hasDebit and $hasCredit is to check if the document as both debit and credit lines
        $hasDebit  = false;
        $hasCredit = false;

        // For the case that line cancellation are use,
        // validate if the cancellation is bigger or not

        /* @var $canceledDebitValue \Decimal\Decimal[] */
        $canceledDebitValue = array();
        /* @var $canceledCreditValue \Decimal\Decimal[] */
        $canceledCreditValue = array();
        /* @var $canceledDebitQt \Decimal\Decimal[] */
        $canceledDebitQt = array();
        /* @var $canceledCreditQt \Decimal\Decimal[] */
        $canceledCreditQt = array();

        foreach ($invoice->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_no_continues"
                            ), $invoice->getInvoiceNo()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINE_NUMBER);
                        AAuditFile::$logger?->info($msg);
                        $this->isValid = false;
                        $lineNoError   = true;
                    } elseif (\in_array($line->getLineNumber(), $lineNoStack)) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_duplicated"
                            ), $invoice->getInvoiceNo()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINE_NUMBER);
                        AAuditFile::$logger?->info($msg);
                        $this->isValid = false;
                        $lineNoError   = true;
                    }
                    $lineNoStack[] = $line->getLineNumber();
                } else {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "document_line_no_number"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg, Line::N_LINE_NUMBER);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    $lineNoError   = true;
                    continue;
                }
            }

            if ($line->issetQuantity() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_no_quantity"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_QUANTITY);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            if ($line->issetUnitPrice() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_no_unit_price"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_UNIT_PRICE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            $lineValue  = new Decimal("0.0");
            $lineTaxCal = new Decimal("0.0");

            if ($line->getCreditAmount() === null && $line->getDebitAmount() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_no_debit_or_credit"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            /** @var Decimal $lineAmount */
            $lineAmount = $line->getCreditAmount() === null ?
                $line->getDebitAmount()?->mul("-1.0") :
                $line->getCreditAmount();

            // Get value for total validation
            $lineValue = $lineValue->add($lineAmount);

            if ($line->issetTax()) {
                $lineTax = $line->getTax();
                if ($lineTax->getTaxAmount() !== null) {
                    $lineTaxCal = new Decimal($lineTax->getTaxAmount());
                }

                if (
                    $lineTax->getTaxPercentage() !== null &&
                    $lineTax->getTaxPercentage()->compareTo("0.0") !== 0
                ) {
                    $lineFactor = $lineTax->getTaxPercentage()->div(100);

                    if ($line->getTaxBase() !== null) {
                        $lineTaxCal = $lineFactor->mul($line->getTaxBase());
                    } else {
                        $lineTaxCal = $lineFactor->mul($lineAmount->abs());
                    }
                }
            }

            // validate unit price and quantity
            $unitPrice = new Decimal($line->getUnitPrice());

            $uniQt = $unitPrice->mul($line->getQuantity());

            $invoice->getDocTotalCalc()?->addLineTotal($line->getLineNumber(), $uniQt);

            if ($line->getTaxBase() !== null &&
                ($unitPrice->compareTo("0.0") > 0 || !$lineValue->equals("0.0"))
            ) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_have_tax_base_with_unit_price_credit_debit"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_BASE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($uniQt->sub($lineValue->abs())->abs()->compareTo($this->getDeltaLine()) > 0) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_value_not_quantity_price"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                        Line::N_DEBIT_AMOUNT : Line::N_CREDIT_AMOUNT
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }

            $notForTotal = array(
                InvoiceStatus::A,
                InvoiceStatus::F
            );

            $docStat = $invoice->getDocumentStatus()->getInvoiceStatus();

            if ($line->getCreditAmount() !== null) {

                $credit = new Decimal($line->getCreditAmount());

                $this->docCredit = $this->docCredit->add($credit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->credit = $this->credit->add($credit);
                }

                $hasCredit = true;

                if (\array_key_exists($line->getProductCode(), $canceledCreditQt)) {
                    $canceledCreditQt[$line->getProductCode()] = $canceledCreditQt[$line->getProductCode()]
                        ->add($line->getQuantity());

                    $canceledCreditValue[$line->getProductCode()] = $canceledCreditValue[$line->getProductCode()]
                        ->add($uniQt);

                } else {
                    $canceledCreditQt[$line->getProductCode()]    = new Decimal($line->getQuantity());
                    $canceledCreditValue[$line->getProductCode()] = new Decimal($uniQt);
                }
            }

            if ($line->getDebitAmount() !== null) {
                $debit          = new Decimal($line->getDebitAmount());
                $this->docDebit = $this->docDebit->add($debit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->debit = $this->debit->add($debit);
                }

                $hasDebit = true;

                if (\array_key_exists($line->getProductCode(), $canceledDebitQt)) {
                    $canceledDebitQt[$line->getProductCode()] = $canceledDebitQt[$line->getProductCode()]
                        ->add($line->getQuantity());

                    $canceledDebitValue[$line->getProductCode()] = $canceledDebitValue[$line->getProductCode()]
                        ->add($uniQt);
                } else {
                    $canceledDebitQt[$line->getProductCode()]    = new Decimal($line->getQuantity());
                    $canceledDebitValue[$line->getProductCode()] = new Decimal($uniQt);
                }
            }

            $this->productCode($line, $invoice);

            if ($line->issetTax()) {
                $this->tax($line, $invoice);
            } elseif ($line->getTaxBase() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_must_be_defined"),
                    $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->netTotal   = $this->netTotal->add($lineValue->abs());
            $this->taxPayable = $this->taxPayable->add($lineTaxCal);

            if (\count($line->getReferences()) > 0) {
                $this->references($line, $invoice);
            }

            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $invoice);
            }
        }

        $this->grossTotal = $this->netTotal->add($this->taxPayable);

        if ($invoice->getInvoiceType()->value === "NC" &&
            $this->docDebit->compareTo($this->docCredit) < 0) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_must_be_debit_but_credit"),
                $invoice->getInvoiceNo(), $invoice->getInvoiceType()->value
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($invoice->getInvoiceType()->value !== "NC" && $this->docCredit->compareTo($this->docDebit) < 0) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_must_be_credit_but_debit"),
                $invoice->getInvoiceNo(),
                $invoice->getInvoiceType()->value
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        $invoice->getDocTotalCalc()?->setGrossTotal($this->grossTotal);
        $invoice->getDocTotalCalc()?->setNetTotal($this->netTotal);
        $invoice->getDocTotalCalc()?->setTaxPayable($this->taxPayable);

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_has_credit_and_debit_lines"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($invoice->getLine() as $line) {
                if ($invoice->getInvoiceType()->value === "NC") {
                    foreach ($canceledCreditQt as $code => $qt) {
                        if (\array_key_exists($code, $canceledDebitQt)) {
                            if ($qt->compareTo($canceledDebitQt[$code]) > 0) {
                                $msg = \sprintf(
                                    AAuditFile::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_qt"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                AAuditFile::$logger?->info($msg);
                                $this->isValid = false;
                                return;
                            }

                            if ($canceledCreditValue[$code]->compareTo($canceledDebitValue[$code]) > 0) {
                                $msg = \sprintf(
                                    AAuditFile
                                        ::getI18n()->get(
                                            "document_has_cancel_lines_with_greater_value"
                                        ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                AAuditFile::$logger?->info($msg);
                                $this->isValid = false;

                                return;
                            }
                        } else {
                            $msg = \sprintf(
                                AAuditFile::getI18n()->get(
                                    "document_has_cancel_lines_that_not_exist"
                                ), $invoice->getInvoiceNo()
                            );
                            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                            $invoice->addError($msg);
                            AAuditFile::$logger?->info($msg);
                            $this->isValid = false;
                            return;
                        }
                    }
                } else {
                    foreach ($canceledDebitQt as $code => $qt) {
                        if (\array_key_exists($code, $canceledCreditQt)) {
                            if ($qt->compareTo($canceledCreditQt[$code]) > 0) {
                                $msg = \sprintf(
                                    AAuditFile::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_qt"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                AAuditFile::$logger?->info($msg);
                                $this->isValid = false;
                                return;
                            }

                            if ($canceledDebitValue[$code]->compareTo($canceledCreditValue[$code]) > 0) {
                                $msg = \sprintf(
                                    AAuditFile
                                        ::getI18n()->get(
                                            "document_has_cancel_lines_with_greater_value"
                                        ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                AAuditFile::$logger?->info($msg);
                                $this->isValid = false;
                                return;
                            }
                        } else {
                            $msg = \sprintf(
                                AAuditFile::getI18n()->get(
                                    "document_has_cancel_lines_that_not_exist"
                                ), $invoice->getInvoiceNo()
                            );
                            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                            $invoice->addError($msg);
                            AAuditFile::$logger?->info($msg);
                            $this->isValid = false;
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * Validate references of NC (Credit note)
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    public function references(Line $line, Invoice $invoice): void
    {
        if (\in_array($invoice->getInvoiceType()->value, ["NC", "ND"]) === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "only_NC_and_ND_can_have_references"
                ), $invoice->getInvoiceNo(), $invoice->getInvoiceType()->value
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        AAuditFile::$logger?->debug(__METHOD__);
        if (\count($line->getReferences()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_references"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }
        $hasRef    = false;
        $hasReason = false;
        foreach ($line->getReferences() as $reference) {
            if ($reference->getReference() !== null) {
                $hasRef = true;
                if (AAuditFile::validateDocNumber($reference->getReference()) === false) {
                    $warning = \sprintf(
                        AAuditFile::getI18n()->get("reference_is_not_doc_valid"),
                        $invoice->getInvoiceNo(), $line->getLineNumber(),
                        $reference->getReference()
                    );
                    $reference->addWarning($warning);
                    AAuditFile::$logger?->info($warning);
                    $this->auditFile->getErrorRegistor()->addWarning($warning);
                }
            }
            if ($reference->getReason() !== null) {
                $hasReason = true;
            }
        }
        if ($hasRef === false || $hasReason === false) {
            $this->isValid = false;
        }

        if ($hasRef === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_references"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($hasReason === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_reason"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, References::N_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Order References
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    public function orderReferences(Line $line, Invoice $invoice): void
    {
        if (\count($line->getOrderReferences()) > 0 &&
            \in_array($invoice->getInvoiceType()->value, ["NC", "ND"])) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "order_reference_not_for_NC_ND"
                ), $invoice->getInvoiceNo(), $invoice->getInvoiceType()->value,
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, OrderReferences::N_ORDER_REFERENCES);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        foreach ($line->getOrderReferences() as $orderRef) {
            if ($orderRef->getOriginatingON() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_indicated"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $orderRef->addError($msg, OrderReferences::N_ORIGINATING_ON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            } else {
                $val = AAuditFile::validateDocNumber($orderRef->getOriginatingON());
                if ($val === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_document_number_not_valid"
                        ), $invoice->getInvoiceNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $orderRef->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $invoice->getDocumentStatus()->getInvoiceStatus();
                if ($docStatus !== InvoiceStatus::R) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_indicated"
                        ), $invoice->getInvoiceNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDER_DATE);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($invoice->getInvoiceDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $invoice->getInvoiceNo(), $line->getLineNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $orderRef->addError($msg, OrderReferences::N_ORDER_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate if Product CodeExist
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function productCode(Line $line, Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($line->issetProductCode()) {
            if (\in_array(
                $line->getProductCode(),
                $this->auditFile->getMasterFiles()->getAllProductCode()
            ) === false
            ) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_line_product_code_not_exist"),
                    $invoice->getInvoiceNo(), $line->getLineNumber(),
                    $line->getProductCode()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_PRODUCT_CODE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        } else {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_line_product_code_not_defined"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCT_CODE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the line Tax
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($line->issetTax() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAX);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $lineTax = $line->getTax();

        if ($lineTax->issetTaxType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_type"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_TYPE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCode() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_code"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_CODE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCountryRegion() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_region"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_COUNTRY_REGION);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }


        if ($lineTax->getTaxType() === TaxType::IVA && $lineTax->getTaxPercentage() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_must_have_percentage"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_PERCENTAGE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->getTaxAmount()?->equals("0.0") || $lineTax->getTaxPercentage()?->equals("0.0")) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $invoice->getInvoiceNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
                $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }

        if ($lineTax->getTaxCode() === TaxCode::ISE) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_iva_code_ise_must_have_code_and_reason"),
                    $invoice->getInvoiceNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
                $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }


        if ($lineTax->getTaxCode() !== TaxCode::ISE &&
            !$lineTax->getTaxPercentage()?->equals("0.0") &&
            ($line->getTaxExemptionCode() !== null ||
                $line->getTaxExemptionReason() !== null)
        ) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_for_tax_zero"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
            $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        // validate if exists in tax table
        foreach ($this->auditFile->getMasterFiles()->getTaxTableEntry() as $taxEntry) {
            /* @var $taxEntry \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry */
            if ($taxEntry->issetTaxType() === false ||
                $taxEntry->issetTaxCode() === false ||
                $taxEntry->issetTaxCountryRegion() === false
            ) {
                continue;
            }

            if ($taxEntry->getTaxType()->value !== $lineTax->getTaxType()->value ||
                ($taxEntry->getTaxAmount() !== $lineTax->getTaxAmount() &&
                    $taxEntry->getTaxPercentage() !== $lineTax->getTaxPercentage()) ||
                $taxEntry->getTaxCountryRegion() !== $lineTax->getTaxCountryRegion()) {
                continue;
            }

            if ($taxEntry->getTaxExpirationDate() === null) {// is valid
                return;
            }
            if ($taxEntry->getTaxExpirationDate()->isLater($invoice->getInvoiceDate())) {// is valid
                return;
            }
        }

        $this->isValid = false; // No table tax entry
        $msg           = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $invoice->getInvoiceNo()
        );
        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        AAuditFile::$logger?->info($msg);
        $line->addError($msg);
        $this->isValid = false;
    }

    /**
     * Validate the document total, only can be invoked after
     * validate lines (Because total controls are get from that validation)
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function totals(Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($invoice->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $invoice->getDocumentTotals();
        $gross  = new Decimal($totals->getGrossTotal());
        $net    = new Decimal($totals->getNetTotal());
        $tax    = new Decimal($totals->getTaxPayable());

        if ($gross->equals($net->add($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($gross->sub($this->grossTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal,
                $invoice->getInvoiceNo(),
                $gross->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSS_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($net->sub($this->netTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_net_total_not_equal_calc_net_total"),
                $this->netTotal, $invoice->getInvoiceNo(), $net->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NET_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($tax->sub($this->taxPayable)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_tax_payable_not_equal_calc_tax_payable"),
                $this->taxPayable, $invoice->getInvoiceNo(), $tax->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAX_PAYABLE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if (null === $currency = $invoice->getDocumentTotals()->getCurrency(false)) {
            AAuditFile::$logger?->info(
                \sprintf(
                    "Invoice '%s' without currency node",
                    $invoice->getInvoiceNo()
                )
            );
            return;
        }

        $currAmount    = new Decimal($currency->getCurrencyAmount());
        $rate          = new Decimal($currency->getExchangeRate());
        $grossExchange = $currAmount->mul($rate);
        $invoice->getDocTotalCalc()?->setGrossTotalFromCurrency($grossExchange);
        $calcExchange = $gross->sub($grossExchange)->abs();

        if ($calcExchange > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, Currency::N_EXCHANGE_RATE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Test if the signature is validated or not
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    protected function sign(Invoice $invoice): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($invoice->issetHash() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_HASH);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($this->getSignValidation() === false) {
            AAuditFile::$logger?->info("Skip sign test as ValidationConfig");
            return;
        }

        if ($invoice->getDocumentStatus()->getSourceBilling() === SourceBilling::I) {
            $validate = true;
        } else {
            $validate = $this->sign->verifySignature(
                $invoice->getHash(), $invoice->getInvoiceDate(),
                $invoice->getSystemEntryDate(), $invoice->getInvoiceNo(),
                $invoice->getDocumentTotals()->getGrossTotal(), $this->lastHash
            );
        }

        if ($validate === false && $this->lastHash === "") {

            list(, , $no) = \explode(
                " ", \str_replace("/", " ", $invoice->getInvoiceNo())
            );

            if ($no !== "1") {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serial"),
                    $invoice->getInvoiceNo()
                );
                AAuditFile::$logger?->info($msg);
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $invoice->addWarning($msg);
                $validate = true;
            }
        }

        if ($validate === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("signature_not_valid"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_HASH);
            AAuditFile::$logger?->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $invoice->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate shipment data
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function shipment(Invoice $invoice): void
    {
        $shipFrom     = $invoice->getShipFrom(false);
        $shipTo       = $invoice->getShipTo(false);
        $movEndTime   = $invoice->getMovementEndTime();
        $movStartTime = $invoice->getMovementStartTime();
        $msgStack     = [];

        if ($shipFrom === null && $shipTo === null &&
            $movEndTime === null && $movStartTime === null) {
            return;
        }

        $invoiceType = $invoice->getInvoiceType();

        if ($shipFrom !== null && $shipTo !== null) {
            if ($shipFrom->getDeliveryDate() !== null &&
                $shipTo->getDeliveryDate() !== null) {
                if ($shipFrom->getDeliveryDate()->isLater($shipTo->getDeliveryDate())) {
                    $msg        = \sprintf(
                        AAuditFile::getI18n()->get("ship_from_delivery_date_later_ship_to_delivery_date"),
                        $invoice->getInvoiceNo()
                    );
                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_SHIP_FROM);
                } else {
                    /** @phpstan-ignore-next-line */
                    if ($movStartTime === null && $movEndTime === null &&
                        $shipFrom->getAddress(false) === null &&
                        $shipTo->getAddress(false) === null) {
                        return;
                    }
                }
            }
        }

        if (\in_array($invoiceType->value, ['FT', 'FR']) === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("only_FR_FT_can_be_stockMovement"),
                $invoice->getInvoiceNo(), $invoice->getInvoiceType()->value
            );
            $invoice->addError($msg);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($movStartTime === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("document_to_be_stockMovement_must_have_start_time"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_MOVEMENT_START_TIME);
        } else {
            if ($movStartTime->isEarlier($invoice->getInvoiceDate())) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("start_movement_can_not_be earlier_doc_date"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_MOVEMENT_START_TIME);
            }

            if ($invoice->getDocumentStatus()->getSourceBilling() === SourceBilling::P) {
                if ($invoice->issetSystemEntryDate() &&
                    $movStartTime->isEarlier($invoice->getSystemEntryDate())) {

                    $msg = \sprintf(
                        AAuditFile::getI18n()
                                  ->get("start_movement_can_not_be earlier_system_entry_date"),
                        $invoice->getInvoiceNo()
                    );

                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_MOVEMENT_START_TIME);
                }
            }

            if ($movEndTime !== null && $movEndTime->isEarlier($movStartTime)) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("end_movement_can_not_be earlier_start_movement"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_MOVEMENT_END_TIME);
            }
        }

        if ($shipFrom === null || $shipFrom->getAddress(false) === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("document_to_be_stockMovement_must_have_ship_from"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SHIP_FROM);
        } elseif (null !== $shipFromAddr = $shipFrom->getAddress()) {

            if (($shipFromAddr->getStreetName() === null ||
                    $shipFromAddr->getStreetName() === "") && (
                    $shipFromAddr->getAddressDetail() === null ||
                    $shipFromAddr->getAddressDetail() === "")) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("document_to_be_stockMovement_must_have_ship_from"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_FROM);
            }

            if ($shipFromAddr->issetCity() === false ||
                $shipFromAddr->getCity() === "") {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("shipment_address_from_must_have_city"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_FROM);
            }

            if ($shipFromAddr->issetCountry() === false) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("shipment_address_from_must_have_country"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_FROM);
            }
        }

        if ($shipTo === null || $shipTo->getAddress(false) === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("document_to_be_stockMovement_must_have_ship_to"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SHIP_TO);
        } elseif (null !== $shipToAddr = $shipTo->getAddress()) {
            if (($shipToAddr->getStreetName() === null ||
                    $shipToAddr->getStreetName() === "") &&
                ($shipToAddr->getAddressDetail() === null ||
                    $shipToAddr->getAddressDetail() === "")) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()->get("document_to_be_stockMovement_must_have_ship_to"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_TO);
            }

            if ($shipToAddr->issetCity() === false || $shipToAddr->getCity() === "") {

                $msg        = \sprintf(
                    AAuditFile::getI18n()->get("shipment_address_to_must_have_city"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_TO);
            }

            if ($shipToAddr->issetCountry() === false) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()->get("shipment_address_to_must_have_country"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIP_TO);
            }
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Invoice date nad SystemEntryDate
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function invoiceDateAndSystemEntryDate(Invoice $invoice): void
    {
        $docDate           = $invoice->getInvoiceDate();
        $systemDate        = $invoice->getSystemEntryDate();
        $msgStack          = [];
        $headerDateChecked = false;
        if ($this->auditFile->issetHeader()) {
            $header = $this->auditFile->getHeader();
            if ($header->issetStartDate() && $header->issetEndDate()) {
                if ($header->getStartDate()->isLater($docDate) ||
                    $header->getEndDate()->isEarlier($docDate)) {
                    $msg        = \sprintf(
                        AAuditFile::getI18n()->get("doc_date_out_of_range_start_end_header_date"),
                        $invoice->getInvoiceNo()
                    );
                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_SYSTEM_ENTRY_DATE);
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()->get("doc_date_not_checked_start_end_header_date"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_INVOICE_DATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()->get("doc_date_earlier_previous_doc"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_INVOICE_DATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()->get("doc_system_entry_date_earlier_previous_doc"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SYSTEM_ENTRY_DATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Payment
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function payment(Invoice $invoice): void
    {
        if (\count($invoice->getDocumentTotals()->getPayment()) === 0) {
            if ($invoice->getInvoiceType() === InvoiceType::FR) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("fr_without_payment_method"),
                    $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $invoice->addWarning($msg);
                AAuditFile::$logger?->info($msg);
            }
            return;
        }

        $totalPayMeth = new Decimal("0.0");

        foreach ($invoice->getDocumentTotals()->getPayment() as $payMet) {
            if ($payMet->issetPaymentAmount()) {
                $totalPayMeth = $totalPayMeth->add($payMet->getPaymentAmount());
            } else {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("payment_method_without_payment_amount"),
                    $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payMet->issetPaymentDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("payment_method_without_payment_date"),
                    $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }
        }

        if ($invoice->issetDocumentTotals()) {
            if ($invoice->getDocumentTotals()->issetGrossTotal()) {
                $gross          = $invoice->getDocumentTotals()->getGrossTotal();
                $diff           = $totalPayMeth->sub($gross);
                $sumWithholding = new Decimal("0.0");
                foreach ($invoice->getWithholdingTax() as $withholding) {
                    if ($withholding->issetWithholdingTaxAmount()) {
                        $sumWithholding = $sumWithholding->add($withholding->getWithholdingTaxAmount());
                    }
                }

                $diff = $diff->add($sumWithholding);

                if ($invoice->getInvoiceType() === InvoiceType::FR &&
                    $diff->abs()->compareTo($this->getDeltaTotalDoc()) > 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("payment_method_sum_not_equal_to_gross_less_tax"),
                        $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    return;
                }

                if ($totalPayMeth->compareTo($gross->sub($sumWithholding)) > 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("payment_method_sum_greater_than_gross_lass_withhold_tax"),
                        $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                }
            }
        }
    }

    /**
     * Validate the withholdingTax
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @since 1.0.0
     */
    protected function withholdingTax(Invoice $invoice): void
    {
        $totalTax = new Decimal("0.0");
        foreach ($invoice->getWithholdingTax() as $withholding) {
            if ($withholding->issetWithholdingTaxAmount() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("withholding_without_amount"),
                    $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, WithholdingTax::N_WITHHOLDING_TAX);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }
            $totalTax = $totalTax->add($withholding->getWithholdingTaxAmount());
        }

        if ($totalTax->equals("0.0")) {
            return;
        }

        if ($invoice->issetDocumentTotals()) {
            if ($invoice->getDocumentTotals()->issetGrossTotal()) {
                $gross = $invoice->getDocumentTotals()->getGrossTotal();
                if ($totalTax->compareTo($gross) > 0 || $totalTax->equals($gross)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("withholding_tax_greater_than_gross"),
                        $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg, WithholdingTax::N_WITHHOLDING_TAX);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    return;
                }
                if ($totalTax->compareTo($gross->div("2")) > 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("withholding_tax_greater_than_half_gross"),
                        $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $invoice->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
            }
        }
    }

    /**
     * Validate if exists invoice types out of date
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    protected function outOfDateInvoiceTypes(Invoice $invoice): void
    {
        if ($invoice->issetInvoiceType() === false || $invoice->issetInvoiceDate() === false) {
            return;
        }

        $type         = $invoice->getInvoiceType();
        $lastDay      = RDate::parse(Pattern::SQL_DATE, "2012-12-31");
        $outDateTypes = [
            InvoiceType::VD,
            InvoiceType::TV,
            InvoiceType::TD,
            InvoiceType::AA,
            InvoiceType::DA
        ];

        if (\in_array($type, $outDateTypes) === false) {
            return;
        }

        if ($invoice->getInvoiceDate()->isLater($lastDay)) {
            $msg = \sprintf(
                AuditFile::getI18n()->get("document_type_last_date_later"),
                $type->value, "2012-12-31", $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            AAuditFile::$logger?->error($msg);
            $this->isValid = false;
        }
    }
}
