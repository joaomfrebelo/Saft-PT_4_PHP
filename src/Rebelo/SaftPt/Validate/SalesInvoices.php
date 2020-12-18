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

namespace Rebelo\SaftPt\Validate;

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\Sign\Sign;
use Rebelo\Decimal\UDecimal;
use Rebelo\Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices as SaftSalesInvoices;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\References;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;
use Rebelo\Date\Date as RDate;

/**
 * Validate SalesInvoices table.<br>
 * This class will validate the values of SalesInvoices, the
 * signtuare hash and dates
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SalesInvoices extends ADocuments
{

    /**
     * Validate SalesInvoices table.<br>
     * This class will validate the values of SalesInvoices, the
     * signtuare hash and dates
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile The AuditFile to be validated
     * @param \Rebelo\SaftPt\Sign\Sign $sign The sign class to be used to validate the hash, must have the public key defined
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile, Sign $sign)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        parent::__construct($auditFile, $sign);

        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $salesInvoices = $sourceDoc->getSalesInvoices(false);
            if ($salesInvoices !== null) {
                $salesInvoices->setDocTableTotalCalc(
                    new \Rebelo\SaftPt\Validate\DocTableTotalCalc()
                );
            }
        }
    }

    /**
     * Validate the invoices
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $progreBar = null;
        try {
            $salesInvoices = $this->auditFile->getSourceDocuments()
                ->getSalesInvoices(false);

            if ($salesInvoices === null) {
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." no sales invoices to be vaidated");
                return $this->isValid;
            }

            $salesInvoices->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($salesInvoices->getInvoice()) === 0) {

                if ($salesInvoices->getTotalCredit() !== 0.0) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "salesinvoice_total_credit_should_be_zero"
                        ), $salesInvoices->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $salesInvoices->addError(
                        $msg, SaftSalesInvoices::N_TOTALCREDIT
                    );
                    $this->isValid = false;
                }

                if ($salesInvoices->getTotalDebit() !== 0.0) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "salesinvoice_total_debit_should_be_zero"
                        ), $salesInvoices->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $salesInvoices->addError(
                        $msg, SaftSalesInvoices::N_TOTALDEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $salesInvoices->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc      = \count($salesInvoices->getInvoice());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section   = null;
                $progreBar = $this->getStyle()->addProgressBar($section);
                $section->writeln("");
                $section->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "Invoice"
                    )
                );
                $progreBar->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serie) {
                    foreach (\array_keys($order[$type][$serie]) as $no) {

                        if ($progreBar !== null) {
                            $progreBar->advance();
                        }

                        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
                        $invoice = $order[$type][$serie][$no];
                        list(, $no) = \explode("/", $invoice->getInvoiceNo());
                        if ((string) $type !== $this->lastType || (string) $serie
                            !== $this->lastSerie) {
                            $this->lastHash            = "";
                            $this->lastDocDate         = null;
                            $this->lastSystemEntryDate = null;
                        }else {
                            $noExpected = $this->lastDocNumber + 1;
                            if (\intval($no) !== $noExpected) {
                                do{
                                $msg = \sprintf(
                                    AuditFile::getI18n()->get("the_document_n_is_missing"),
                                    $type, $serie, $noExpected
                                );
                                \Logger::getLogger(\get_class($this))->debug($msg);
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $this->isValid = false;
                                $this->lastDocNumber = $noExpected;
                                $noExpected++;
                                }while ($no !== \strval($noExpected));
                            }
                        }
                        $this->lastDocNumber = (int) $no;
                        $invoice->setDocTotalcal(new DocTotalCalc());
                        $this->invoice($invoice);
                        $this->lastType  = (string) $type;
                        $this->lastSerie = (string) $serie;
                    }
                }
            }

            if ($progreBar !== null) {
                $progreBar->finish();
            }

            $this->totalCredit();
            $this->totalDebit();
        } catch (\Exception | \Error $e) {
            $this->isValid = false;

            if ($progreBar !== null) {
                $progreBar->finish();
            }

            $this->auditFile->getErrorRegistor()
                ->addExceptionErrors($e->getMessage());

            \Logger::getLogger(\get_class($this))
                ->debug(
                    \sprintf(
                        __METHOD__." validate error '%s'", $e->getMessage()
                    )
                );
        }
        return $this->isValid;
    }

    /**
     * Validate Invoice
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function invoice(Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try {
            $this->docCredit  = new UDecimal(0.0, static::CALC_PRECISION);
            $this->docDebit   = new UDecimal(0.0, static::CALC_PRECISION);
            $this->netTotal   = new UDecimal(0.0, static::CALC_PRECISION);
            $this->taxPayable = new UDecimal(0.0, static::CALC_PRECISION);
            $this->grossTotal = new UDecimal(0.0, static::CALC_PRECISION);

            if ($invoice->issetInvoiceNo() === false) {
                $msg           = AAuditFile::getI18n()->get("invoicetno_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetInvoiceType() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "invoicetype_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_INVOICETYPE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetInvoiceDate() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_INVOICEDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($invoice->issetSystemEntryDate() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_systementrydate_not_defined"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, Invoice::N_SYSTEMENTRYDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            $this->sign($invoice);
            $this->invoiceDateAndSystemEntryDate($invoice);
            $this->customerId($invoice);
            $this->documentStatus($invoice);
            $this->lines($invoice);
            $this->totals($invoice);
            $this->shipement($invoice);
            $this->payment($invoice);
            $this->withholdingTax($invoice);
            $this->outOfDateInvoiceTypes($invoice);
        } catch (\Exception | \Error $e) {
            $this->auditFile->getErrorRegistor()
                ->addExceptionErrors($e->getMessage());
            \Logger::getLogger(\get_class($this))
                ->debug(
                    \sprintf(
                        __METHOD__." validate error '%s'", $e->getMessage()
                    )
                );
            $invoice->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfEntries is equal to the number of invoices
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $salesInvoices          = $this->auditFile->getSourceDocuments()->getSalesInvoices();
        $calculatedNumOfEntries = \count($salesInvoices->getInvoice());
        $numberOfEntries        = $salesInvoices->getNumberOfEntries();
        $test                   = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()->getSalesInvoices()
            ->getDocTableTotalCalc()->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_invoices"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_NUMBEROFENTRIES);
            \Logger::getLogger(\get_class($this))->info($msg);
        }
        if ($test === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate SalesInvoices TotalDebit
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $salesInvoices = $this->auditFile->getSourceDocuments()
            ->getSalesInvoices();

        $salesInvoices->getDocTableTotalCalc()
            ->setTotalDebit($this->debit->valueOf());

        $diff = $this->debit->signedSubtract(
            new Decimal(
                $salesInvoices->getTotalDebit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTable) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_invoices"
                ), $salesInvoices->getTotalDebit(), $this->debit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_TOTALDEBIT);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate SalesInvoices TotalCredit
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $salesInvoices = $this->auditFile->getSourceDocuments()->getSalesInvoices();

        $salesInvoices->getDocTableTotalCalc()->setTotalDebit($this->credit->valueOf());

        $diff = $this->credit->signedSubtract(
            new Decimal(
                $salesInvoices->getTotalCredit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTable) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_invoices"
                ), $salesInvoices->getTotalCredit(), $this->credit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $salesInvoices->addError($msg, SaftSalesInvoices::N_TOTALCREDIT);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(Invoice $invoice): void
    {
        if ($invoice->issetDocumentStatus() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_DOCUMENTSTATUS);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $invoice->getDocumentStatus();

        if ($status->getInvoiceStatusDate() < $invoice->getSystemEntryDate()) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, DocumentStatus::N_INVOICESTATUSDATE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getInvoiceStatus()->isEqual(InvoiceStatus::A) &&
            $status->getReason() === null) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, DocumentStatus::N_REASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }
    }

    /**
     * validate if the customerID of the Invoice if is set and if exits in
     * the customer table
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function customerId(Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
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
                $invoice->addError($msg, Invoice::N_CUSTOMERID);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "customerID_not_defined_in_document"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_CUSTOMERID);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the Invoice
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function lines(Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($invoice->getLine()) === 0) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_INVOICENO);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $n           = 0;
        /* @var $lineNoStack int[] */
        $lineNoStack = array();
        $lineNoError = false;
        //$hasDebit and $hasCredit is to check if the document as both debit and credit lines
        $hasDebit    = false;
        $hasCredit   = false;

        // For the case that line anulation are use,
        // validate if the anulation is bigger or not

        /* @var $anulaDebitValue \Rebelo\Decimal\UDecimal[] */
        $anulaDebitValue  = array();
        /* @var $anulaCreditValue \Rebelo\Decimal\UDecimal[] */
        $anulaCreditValue = array();
        /* @var $anulaDebitQt \Rebelo\Decimal\UDecimal[] */
        $anulaDebitQt     = array();
        /* @var $anulaCreditQt \Rebelo\Decimal\UDecimal[] */
        $anulaCreditQt    = array();

        foreach ($invoice->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg           = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_no_continues"
                            ), $invoice->getInvoiceNo()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINENUMBER);
                        \Logger::getLogger(\get_class($this))->info($msg);
                        $this->isValid = false;
                        $lineNoError   = true;
                    } elseif (\in_array($line->getLineNumber(), $lineNoStack)) {
                        $msg           = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_duplicated"
                            ), $invoice->getInvoiceNo()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINENUMBER);
                        \Logger::getLogger(\get_class($this))->info($msg);
                        $this->isValid = false;
                        $lineNoError   = true;
                    }
                    $lineNoStack[] = $line->getLineNumber();
                } else {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "document_line_no_number"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg, Line::N_LINENUMBER);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    $lineNoError   = true;
                    continue;
                }
            }

            if ($line->issetQuantity() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_no_quantity"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_QUANTITY);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                continue;
            }

            if ($line->issetUnitPrice() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_no_unit_price"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_UNITPRICE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                continue;
            }

            $lineValue  = new Decimal(0.0, static::CALC_PRECISION);
            $lineTaxCal = new UDecimal(0.0, static::CALC_PRECISION);

            if ($line->getCreditAmount() === null &&
                $line->getDebitAmount() === null) {

                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_no_debit_or_credit"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                continue;
            }

            $lineAmount = $line->getCreditAmount() === null ?
                $line->getDebitAmount() * -1.0 :
                $line->getCreditAmount();

            // Get value for total validation
            $lineValue->plusThis($lineAmount);

            if ($line->issetTax()) {
                $lineTax = $line->getTax();
                if ($lineTax->getTaxAmount() !== null) {
                    $lineTaxCal = new UDecimal(
                        $lineTax->getTaxAmount(), static::CALC_PRECISION
                    );
                }

                if ($lineTax->getTaxPercentage() !== null &&
                    $lineTax->getTaxPercentage() !== 0.0) {

                    $lineFactor = $lineTax->getTaxPercentage() / 100;

                    if ($line->getTaxBase() !== null) {
                        $lineTaxCal = new UDecimal(
                            $lineFactor * $line->getTaxBase(),
                            static::CALC_PRECISION
                        );
                    } else {
                        $lineTaxCal = new UDecimal(
                            $lineFactor * \abs($lineAmount),
                            static::CALC_PRECISION
                        );
                    }
                }
            }

            // validate unit price and quantuty
            $unitPrice = new UDecimal(
                $line->getUnitPrice(), static::CALC_PRECISION
            );

            $uniQt = $unitPrice->multiply(
                new UDecimal($line->getQuantity(), static::CALC_PRECISION)
            );

            $invoice->getDocTotalcal()->addLineTotal(
                $line->getLineNumber(), $uniQt->valueOf()
            );

            if ($line->getTaxBase() !== null &&
                ($unitPrice->valueOf() > 0.0 || $lineValue->valueOf() !== 0.0)
            ) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_have_tax_base_with_unit_price_credit_debit"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAXBASE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($uniQt->signedSubtract($lineValue->abs())->abs()->valueOf() > $this->getDeltaLine()) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_value_not_quantity_price"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                        Line::N_DEBITAMOUNT : Line::N_CREDITAMOUNT
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }

            $notForTotal = array(
                InvoiceStatus::A,
                InvoiceStatus::F
            );

            $docStat = $invoice->getDocumentStatus()->getInvoiceStatus()->get();

            if ($line->getCreditAmount() !== null) {
                $credit = new UDecimal(
                    $line->getCreditAmount(), static::CALC_PRECISION
                );
                $this->docCredit->plusThis($credit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->credit->plusThis($credit);
                }

                $hasCredit = true;

                if (\array_key_exists($line->getProductCode(), $anulaCreditQt)) {
                    $anulaCreditQt[$line->getProductCode()]->plusThis(
                        new UDecimal(
                            $line->getQuantity(), static::CALC_PRECISION
                        )
                    );
                    $anulaCreditValue[$line->getProductCode()]->plusThis($uniQt);
                } else {
                    $anulaCreditQt[$line->getProductCode()]    = new UDecimal(
                        $line->getQuantity(), static::CALC_PRECISION
                    );
                    $anulaCreditValue[$line->getProductCode()] = clone $uniQt;
                }
            }

            if ($line->getDebitAmount() !== null) {
                $debit = new UDecimal(
                    $line->getDebitAmount(), static::CALC_PRECISION
                );
                $this->docDebit->plusThis($debit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->debit->plusThis($debit);
                }

                $hasDebit = true;

                if (\array_key_exists($line->getProductCode(), $anulaDebitQt)) {
                    $anulaDebitQt[$line->getProductCode()]->plusThis(
                        new UDecimal(
                            $line->getQuantity(), static::CALC_PRECISION
                        )
                    );
                    $anulaDebitValue[$line->getProductCode()]->plusThis($uniQt);
                } else {
                    $anulaDebitQt[$line->getProductCode()]    = new UDecimal(
                        $line->getQuantity(), static::CALC_PRECISION
                    );
                    $anulaDebitValue[$line->getProductCode()] = clone $uniQt;
                }
            }

            $this->producCode($line, $invoice);

            if ($line->issetTax()) {
                $this->tax($line, $invoice);
            } elseif ($line->getTaxBase() === null) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get("tax_must_be_defined"),
                    $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
            $this->netTotal->plusThis($lineValue->abs());

            $this->taxPayable->plusThis($lineTaxCal);

            if (\count($line->getReferences()) > 0) {
                $this->refernces($line, $invoice);
            }
            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $invoice);
            }
        }

        $this->grossTotal = $this->netTotal->plus($this->taxPayable);

        if ($invoice->getInvoiceType()->isEqual("NC") &&
            $this->docDebit->isLess($this->docCredit)) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_must_be_debit_but_credit"),
                $invoice->getInvoiceNo(), $invoice->getInvoiceType()->get()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($invoice->getInvoiceType()->isNotEqual("NC") &&
            $this->docCredit->isLess($this->docDebit)) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_must_be_credit_but_debit"
                ), $invoice->getInvoiceNo(), $invoice->getInvoiceType()->get()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        $invoice->getDocTotalcal()->setGrossTotal($this->grossTotal->valueOf());
        $invoice->getDocTotalcal()->setNetTotal($this->netTotal->valueOf());
        $invoice->getDocTotalcal()->setTaxPayable($this->taxPayable->valueOf());

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_has_credit_and_debit_lines"
                ), $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit) {
            foreach ($invoice->getLine() as $line) {
                /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
                if ($invoice->getInvoiceType()->isEqual("NC")) {
                    foreach ($anulaCreditQt as $code => $qt) {
                        if (\array_key_exists($code, $anulaDebitQt)) {
                            if ($qt->isGreater($anulaDebitQt[$code])) {
                                $msg           = \sprintf(
                                    AAuditFile::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_qt"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                \Logger::getLogger(\get_class($this))->info($msg);
                                $this->isValid = false;
                                return;
                            }

                            if ($anulaCreditValue[$code]->isGreater($anulaDebitValue[$code])) {
                                $msg           = \sprintf(
                                    AAuditFile
                                    ::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_value"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                \Logger::getLogger(\get_class($this))->info($msg);
                                $this->isValid = false;

                                return;
                            }
                        } else {
                            $msg           = \sprintf(
                                AAuditFile::getI18n()->get(
                                    "document_has_cancel_lines_that_not_exist"
                                ), $invoice->getInvoiceNo()
                            );
                            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                            $invoice->addError($msg);
                            \Logger::getLogger(\get_class($this))->info($msg);
                            $this->isValid = false;
                            return;
                        }
                    }
                } else {
                    foreach ($anulaDebitQt as $code => $qt) {
                        if (\array_key_exists($code, $anulaCreditQt)) {
                            if ($qt->isGreater($anulaCreditQt[$code])) {
                                $msg           = \sprintf(
                                    AAuditFile::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_qt"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                \Logger::getLogger(\get_class($this))->info($msg);
                                $this->isValid = false;
                                return;
                            }

                            if ($anulaDebitValue[$code]->isGreater($anulaCreditValue[$code])) {
                                $msg           = \sprintf(
                                    AAuditFile
                                    ::getI18n()->get(
                                        "document_has_cancel_lines_with_greater_value"
                                    ), $invoice->getInvoiceNo()
                                );
                                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                $invoice->addError($msg);
                                \Logger::getLogger(\get_class($this))->info($msg);
                                $this->isValid = false;
                                return;
                            }
                        } else {
                            $msg           = \sprintf(
                                AAuditFile::getI18n()->get(
                                    "document_has_cancel_lines_that_not_exist"
                                ), $invoice->getInvoiceNo()
                            );
                            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                            $invoice->addError($msg);
                            \Logger::getLogger(\get_class($this))->info($msg);
                            $this->isValid = false;
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * Validate refernces of NC (Credit note)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    public function refernces(Line $line, Invoice $invoice): void
    {
        if (\in_array($invoice->getInvoiceType()->get(), ["NC", "ND"]) === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "only_NC_and_ND_can_have_references"
                ), $invoice->getInvoiceNo(), $invoice->getInvoiceType()->get()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($line->getReferences()) === 0) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_refernces"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }
        $hasRef    = false;
        $hasReason = false;
        foreach ($line->getReferences() as $reference) {
            /* @var $reference \Rebelo\SaftPt\AuditFile\SourceDocuments\References */
            if ($reference->getReference() !== null) {
                $hasRef = true;
                if (AAuditFile::validateDocNumber($reference->getReference()) === false) {
                    $warning = \sprintf(
                        AAuditFile::getI18n()->get("reference_is_not_doc_valid"),
                        $invoice->getInvoiceNo(), $line->getLineNumber(),
                        $reference->getReference()
                    );
                    $reference->addWarning($warning);
                    \Logger::getLogger(\get_class($this))->info($warning);
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
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_refernces"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($hasReason === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_reason"
                ), $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, References::N_REASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }
    }

    /**
     * Validate the Order References
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    public function orderReferences(Line $line, Invoice $invoice): void
    {
        if (\count($line->getOrderReferences()) > 0 &&
            \in_array($invoice->getInvoiceType()->get(), ["NC", "ND"])) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "order_reference_not_for_NC_ND"
                ), $invoice->getInvoiceNo(), $invoice->getInvoiceType()->get(),
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, OrderReferences::N_ORDERREFERENCES);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        foreach ($line->getOrderReferences() as $orderRef) {
            /* @var $orderRef \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences */
            if ($orderRef->getOriginatingON() === null) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_incicated"
                    ), $invoice->getInvoiceNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $orderRef->addError($msg, OrderReferences::N_ORIGINATINGON);
                \Logger::getLogger(\get_class($this))->info($msg);
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
                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $invoice->getDocumentStatus()->getInvoiceStatus();
                if ($docStatus->isNotEqual(InvoiceStatus::R)) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_incicated"
                        ), $invoice->getInvoiceNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDERDATE);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($invoice->getInvoiceDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $invoice->getInvoiceNo(), $line->getLineNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $orderRef->addError($msg, OrderReferences::N_ORDERDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate if Product CodeExist
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function producCode(Line $line, Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
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
                $line->addError($msg, Line::N_PRODUCTCODE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        } else {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_line_product_code_not_defined"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCTCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the line Tax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($line->issetTax() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAX);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $lineTax = $line->getTax();

        if ($lineTax->issetTaxType() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_type"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXTYPE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCode() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_code"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCountryRegion() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_region"),
                $invoice->getInvoiceNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXCOUNTRYREGION);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }


        if ($lineTax->getTaxType()->isEqual(TaxType::IVA) &&
            $lineTax->getTaxPercentage() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_must_have_percentage"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXPERCENTAGE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->getTaxAmount() === 0.0 || $lineTax->getTaxPercentage() === 0.0) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $invoice->getInvoiceNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAXEXEMPTIONCODE);
                $line->addError($msg, Line::N_TAXEXEMPTIONREASON);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }

        if ($lineTax->getTaxCode()->isEqual(TaxCode::ISE)) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_iva_code_ise_must_have_code_and_reason"),
                    $invoice->getInvoiceNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAXEXEMPTIONCODE);
                $line->addError($msg, Line::N_TAXEXEMPTIONREASON);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }


        if ($lineTax->getTaxCode() !== TaxCode::ISE &&
            $lineTax->getTaxPercentage() !== 0.0 &&
            ($line->getTaxExemptionCode() !== null ||
            $line->getTaxExemptionReason() !== null)
        ) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_isent"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAXEXEMPTIONCODE);
            $line->addError($msg, Line::N_TAXEXEMPTIONREASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        // valiedate if exists in tax table
        foreach ($this->auditFile->getMasterFiles()->getTaxTableEntry() as $taxEntry) {
            /* @var $taxEntry \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry */
            if ($taxEntry->issetTaxType() === false ||
                $taxEntry->issetTaxCode() === false ||
                $taxEntry->issetTaxCountryRegion() === false
            ) {
                continue;
            }

            if ($taxEntry->getTaxType()->isNotEqual($lineTax->getTaxType()) ||
                ($taxEntry->getTaxAmount() !== $lineTax->getTaxAmount() &&
                $taxEntry->getTaxPercentage() !== $lineTax->getTaxPercentage()) ||
                $taxEntry->getTaxCountryRegion()->isNotEqual($lineTax->getTaxCountryRegion())) {
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
        \Logger::getLogger(\get_class($this))->info($msg);
        $line->addError($msg);
        $this->isValid = false;
    }

    /**
     * Validate the document total, only can be invoked after
     * validate lines (Because total controls are getted from that validation)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function totals(Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($invoice->issetDocumentTotals() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $invoice->getDocumentTotals();
        $gross  = new UDecimal($totals->getGrossTotal(), 2);
        $net    = new UDecimal($totals->getNetTotal(), static::CALC_PRECISION);
        $tax    = new UDecimal($totals->getTaxPayable(), static::CALC_PRECISION);

        if ($gross->equals($net->plus($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($gross->signedSubtract($this->grossTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal, $invoice->getInvoiceNo(), $gross->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSSTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($net->signedSubtract($this->netTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_nettotal_not_equal_calc_nettotal"),
                $this->netTotal, $invoice->getInvoiceNo(), $net->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NETTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($tax->signedSubtract($this->taxPayable)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_taxpayable_not_equal_calc_taxpayable"),
                $this->taxPayable, $invoice->getInvoiceNo(), $tax->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAXPAYABLE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($invoice->getDocumentTotals()->getCurrency(false) === null) {
            \Logger::getLogger(\get_class($this))->info(
                \sprintf(
                    "Invoice '%s' without currency node",
                    $invoice->getInvoiceNo()
                )
            );
            return;
        }

        $currency      = $invoice->getDocumentTotals()->getCurrency();
        $currAmou      = new UDecimal(
            $currency->getCurrencyAmount(), static::CALC_PRECISION
        );
        $rate          = new UDecimal(
            $currency->getExchangeRate(), static::CALC_PRECISION
        );
        $grossExchange = $currAmou->multiply($rate);
        $invoice->getDocTotalcal()->setGrossTotalFromCurrency($grossExchange->valueOf());
        $calcCambio    = $gross->signedSubtract($grossExchange, 2)->abs()->valueOf();

        if ($calcCambio > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $invoice->getInvoiceNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError(
                $msg,
                \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency::N_EXCHANGERATE
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Test if the signature is valide or not
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function sign(Invoice $invoice): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($invoice->issetHash() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_HASH);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($this->getSignValidation() === false) {
            \Logger::getLogger(\get_class($this))->info("Skip sign test as ValidationConfig");
            return;
        }

        if ($invoice->getDocumentStatus()->getSourceBilling()->isEqual(SourceBilling::I)) {
            $validate = true;
        } else {
            $validate = $this->sign->verifySignature(
                $invoice->getHash(), $invoice->getInvoiceDate(),
                $invoice->getSystemEntryDate(), $invoice->getInvoiceNo(),
                $invoice->getDocumentTotals()->getGrossTotal(), $this->lastHash
            );
        }

        if ($validate === false && $this->lastHash === "") {

            list(,, $no) = \explode(
                " ", \str_replace("/", " ", $invoice->getInvoiceNo())
            );

            if ($no !== "1") {
                $msg      = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serie"),
                    $invoice->getInvoiceNo()
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $invoice->addWarning($msg);
                $validate = true;
            }
        }

        if ($validate === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("signature_not_valid"),
                $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg, Invoice::N_HASH);
            \Logger::getLogger(\get_class($this))->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $invoice->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate shipement data
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function shipement(Invoice $invoice): void
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

        $invoiceType = $invoice->getInvoiceType()->get();

        if ($shipFrom !== null && $shipTo !== null) {
            if ($shipFrom->getDeliveryDate() !== null &&
                $shipTo->getDeliveryDate() !== null) {
                if ($shipFrom->getDeliveryDate()->isLater($shipTo->getDeliveryDate())) {
                    $msg        = \sprintf(
                        AAuditFile::getI18n()
                            ->get("shipfrom_delivery_date_later_shipto_delivery_date"),
                        $invoice->getInvoiceNo()
                    );
                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_SHIPFROM);
                } else {
                    if ($movStartTime === null && $movEndTime === null &&
                        $shipFrom->getAddress(false) === null &&
                        $shipTo->getAddress(false) === null) {
                        return;
                    }
                }
            }
        }

        if (\in_array($invoiceType, ['FT', 'FR']) === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("only_FR_FT_can_be_stockMovement"),
                $invoice->getInvoiceNo(), $invoice->getInvoiceType()->get()
            );
            $invoice->addError($msg);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($movStartTime === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("document_to_be_stockMovement_must_heve_start_time"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_MOVEMENTSTARTTIME);
        } else {
            if ($movStartTime->isEarlier($invoice->getInvoiceDate())) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("start_movement_can_not_be earliar_doc_date"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_MOVEMENTSTARTTIME);
            }

            if ($invoice->getDocumentStatus()->getSourceBilling()->isEqual(SourceBilling::P)) {
                if ($invoice->issetSystemEntryDate() &&
                    $movStartTime->isEarlier($invoice->getSystemEntryDate())) {

                    $msg = \sprintf(
                        AAuditFile::getI18n()
                            ->get("start_movement_can_not_be earliar_system_entry_date"),
                        $invoice->getInvoiceNo()
                    );

                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_MOVEMENTSTARTTIME);
                }
            }

            if ($movEndTime !== null && $movEndTime->isEarlier($movStartTime)) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("end_movement_can_not_be earliar_start_movement"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_MOVEMENTENDTIME);
            }
        }

        if ($shipFrom === null || $shipFrom->getAddress(false) === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("document_to_be_stockMovement_must_have_shipfrom"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SHIPFROM);
        } else {
            $shipFromAddr = $shipFrom->getAddress();
            if (($shipFromAddr->getStreetName() === null ||
                $shipFromAddr->getStreetName() === "") && (
                $shipFromAddr->getAddressDetail() === null ||
                $shipFromAddr->getAddressDetail() === "")) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("document_to_be_stockMovement_must_have_shipfrom"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPFROM);
            }

            if ($shipFromAddr->issetCity() === false ||
                $shipFromAddr->getCity() === "") {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("shipement_address_from_must_heve_city"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPFROM);
            }

            if ($shipFromAddr->issetCountry() === false) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("shipement_address_from_must_heve_country"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPFROM);
            }
        }

        if ($shipTo === null || $shipTo->getAddress(false) === null) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("document_to_be_stockMovement_must_have_shipto"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SHIPTO);
        } else {
            $shipToAddr = $shipTo->getAddress();
            if (($shipToAddr->getStreetName() === null ||
                $shipToAddr->getStreetName() === "") &&
                ($shipToAddr->getAddressDetail() === null ||
                $shipToAddr->getAddressDetail() === "")) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("document_to_be_stockMovement_must_have_shipto"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPTO);
            }

            if ($shipToAddr->issetCity() === false ||
                $shipTo->getAddress()->getCity() === "") {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("shipement_address_to_must_heve_city"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPTO);
            }

            if ($shipToAddr->issetCountry() === false) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                        ->get("shipement_address_to_must_heve_country"),
                    $invoice->getInvoiceNo()
                );
                $msgStack[] = $msg;
                $invoice->addError($msg, Invoice::N_SHIPTO);
            }
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Invoice date nad SystemEntrydate
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
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
                        AAuditFile::getI18n()
                            ->get("doc_date_out_of_range_start_end_header_date"),
                        $invoice->getInvoiceNo()
                    );
                    $msgStack[] = $msg;
                    $invoice->addError($msg, Invoice::N_SYSTEMENTRYDATE);
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_date_not_cheked_start_end_header_date"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_INVOICEDATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_date_eaarlier_previous_doc"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_INVOICEDATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_systementrydate_earlier_previous_doc"),
                $invoice->getInvoiceNo()
            );
            $msgStack[] = $msg;
            $invoice->addError($msg, Invoice::N_SYSTEMENTRYDATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Payment
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function payment(Invoice $invoice): void
    {
        if (\count($invoice->getDocumentTotals()->getPayment()) === 0) {
            if ($invoice->getInvoiceType()->isEqual(InvoiceType::FR)) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("fr_withou_payment_method"),
                    $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $invoice->addWarning($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
            }
            return;
        }

        $totalPayMeth = new UDecimal(0.0, static::CALC_PRECISION);

        /* @var $payMet \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
        foreach ($invoice->getDocumentTotals()->getPayment() as $payMet) {
            if ($payMet->issetPaymentAmount()) {
                $totalPayMeth->plusThis($payMet->getPaymentAmount());
            } else {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "paymentmethod_withou_payment_amout"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payMet->issetPaymentDate() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "paymentmethod_withou_payment_date"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
        }

        if ($invoice->issetDocumentTotals()) {
            if ($invoice->getDocumentTotals()->issetGrossTotal()) {
                $gross          = $invoice->getDocumentTotals()->getGrossTotal();
                $diff           = $totalPayMeth->signedSubtract($gross);
                $sumWithholding = new UDecimal(0.0, static::CALC_PRECISION);
                foreach ($invoice->getWithholdingTax() as $withholding) {
                    /* @var $withholding \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax */
                    if ($withholding->issetWithholdingTaxAmount()) {
                        $sumWithholding->plusThis($withholding->getWithholdingTaxAmount());
                    }
                }

                $diff->plusThis($sumWithholding);

                if ($invoice->getInvoiceType()->isEqual(InvoiceType::FR) &&
                    $diff->abs()->isGreater($this->getDeltaTotalDoc())) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "paymentmethod_sum_not_equal_to_gross_less_tax"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    return;
                }

                if ($totalPayMeth->isGreater($gross - $sumWithholding->valueOf())) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "paymentmethod_sum_greater_than_gross_lass_withholtax"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    return;
                }
            }
        }
    }

    /**
     * Validate the withholdingTax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function withholdingTax(Invoice $invoice): void
    {
        $totalTax = new UDecimal(0.0, static::CALC_PRECISION);
        foreach ($invoice->getWithholdingTax() as $withholding) {
            /* @var $withholding \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax */
            if ($withholding->issetWithholdingTaxAmount() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "withholding_without_amout"
                    ), $invoice->getInvoiceNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $invoice->addError($msg, WithholdingTax::N_WITHHOLDINGTAX);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
            $totalTax->plusThis($withholding->getWithholdingTaxAmount());
        }

        if($totalTax->isEquals(0.0)){
            return;
        }
        
        if ($invoice->issetDocumentTotals()) {
            if ($invoice->getDocumentTotals()->issetGrossTotal()) {
                $gross = $invoice->getDocumentTotals()->getGrossTotal();
                if ($totalTax->isGreater($gross) || $totalTax->isEquals($gross)) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholdingtax_greater_than_gross"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $invoice->addError($msg, WithholdingTax::N_WITHHOLDINGTAX);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    return;
                }
                if ($totalTax->isGreater($gross / 2)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholdingtax_greater_than_half_gross"
                        ), $invoice->getInvoiceNo()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $invoice->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    return;
                }
            }
        }
    }

    /**
     * Validate if exists invoice types out of date
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return void
     * @since 1.0.0
     */
    protected function outOfDateInvoiceTypes(Invoice $invoice) : void
    {
        if ($invoice->issetInvoiceType() === false || $invoice->issetInvoiceDate() === false) {
            return;
        }

        $type     = $invoice->getInvoiceType()->get();
        $lastDay  = RDate::parse(RDate::SQL_DATE, "2012-12-31");
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
                $type, "2012-12-31", $invoice->getInvoiceNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $invoice->addError($msg);
            \Logger::getLogger(\get_class($this))->error($msg);
            $this->isValid = false;
        }
    }
}
