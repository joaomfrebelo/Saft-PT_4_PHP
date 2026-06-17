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
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments as SaftPayments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;

/**
 * Validate Payments table.<br>
 * This class will validate the values of Payments and dates
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Payments extends ADocuments
{

    /**
     * Validate Payments table.<br>
     * This class will validate the values of Payments and dates
     *
     * @param AuditFile $auditFile The AuditFile to be validated
     *
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile)
    {
        parent::__construct($auditFile);

        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $payments = $sourceDoc->getPayments(false);
            $payments?->setDocTableTotalCalc(
                new DocTableTotalCalc()
            );
        }
    }

    /**
     * Validate the payments
     *
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        AAuditFile::$logger?->debug(__METHOD__);
        $progressBar = null;
        try {
            if (null === $payments = $this->auditFile->getSourceDocuments()?->getPayments(false)) {
                AAuditFile::$logger?->debug(__METHOD__ . " no sales payments to be validated");
                return $this->isValid;
            }

            $payments->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($payments->getPayment()) === 0) {

                if (!$payments->getTotalCredit()->equals(0)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "payments_total_credit_should_be_zero"
                        ), $payments->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payments->addError(
                        $msg, SaftPayments::N_TOTAL_CREDIT
                    );
                    $this->isValid = false;
                }

                if (!$payments->getTotalDebit()->equals(0)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "payments_total_debit_should_be_zero"
                        ), $payments->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payments->addError(
                        $msg, SaftPayments::N_TOTAL_DEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $payments->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc = \count($payments->getPayment());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section     = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section?->writeln("");
                $section?->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "Payments"
                    )
                );
                $progressBar?->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serial) {
                    foreach (\array_keys($order[$type][$serial]) as $no) {

                        $progressBar?->advance();

                        $payment = $order[$type][$serial][$no];
                        list(, $no) = \explode("/", $payment->getPaymentRefNo());
                        if ((string)$type !== $this->lastType || (string)$serial !== $this->lastSerial) {
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
                        $payment->setDocTotalCal(new DocTotalCalc());
                        $this->payment($payment);
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
     * Validate Payment
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function payment(Payment $payment): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        try {
            $this->docCredit  = new Decimal("0.0");
            $this->docDebit   = new Decimal("0.0");
            $this->netTotal   = new Decimal("0.0");
            $this->taxPayable = new Decimal("0.0");
            $this->grossTotal = new Decimal("0.0");

            if ($payment->issetPaymentRefNo() === false) {
                $msg = AAuditFile::getI18n()->get("invoice_no_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($payment->issetPaymentType() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "payment_type_not_defined"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_PAYMENT_TYPE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payment->issetTransactionDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_TRANSACTION_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payment->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_system_entry_date_not_defined"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_SYSTEM_ENTRY_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->paymentDateAndSystemEntryDate($payment);
            $this->customerId($payment);
            $this->documentStatus($payment);
            $this->lines($payment);
            $this->totals($payment);
            $this->paymentMethod($payment);
            $this->withholdingTax($payment);
        } catch (\Exception|\Error $e) {
            $this->auditFile->getErrorRegistor()
                            ->addExceptionErrors($e->getMessage());
            AAuditFile::$logger?->debug(
                \sprintf(
                    __METHOD__ . " validate error '%s'", $e->getMessage()
                )
            );
            $payment->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfEntries is equal to the number of payments
     *
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $payments = $this->auditFile->getSourceDocuments()?->getPayments()) {
            return;
        }
        $calculatedNumOfEntries = \count($payments->getPayment());
        $numberOfEntries        = $payments->getNumberOfEntries();
        $test                   = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()
                        ->getPayments()
                        ->getDocTableTotalCalc()
                        ?->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_payments"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_NUMBER_OF_ENTRIES);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate Payments TotalDebit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (null === $payments = $this->auditFile->getSourceDocuments()?->getPayments()) {
            return;
        }

        $payments->getDocTableTotalCalc()?->setTotalDebit($this->debit);

        $diff = $this->debit->sub(new Decimal((string)$payments->getTotalDebit()))->abs();

        if ($diff->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_payments"
                ), $payments->getTotalDebit(), $this->debit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_TOTAL_DEBIT);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate Payments TotalCredit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $payments = $this->auditFile->getSourceDocuments()?->getPayments()) {
            return;
        }

        $payments->getDocTableTotalCalc()?->setTotalCredit($this->credit);

        $diff = $this->credit->sub(
            new Decimal((string)$payments->getTotalCredit())
        )->abs();

        if ($diff->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_payments"
                ), $payments->getTotalCredit(), $this->credit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_TOTAL_CREDIT);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(Payment $payment): void
    {
        if ($payment->issetDocumentStatus() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, DocumentStatus::N_PAYMENT_STATUS);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $payment->getDocumentStatus();

        if ($status->getPaymentStatusDate()->isEarlier($payment->getTransactionDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, DocumentStatus::N_PAYMENT_STATUS_DATE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getPaymentStatus() === PaymentStatus::A && $status->getReason() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, DocumentStatus::N_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate if the customerID of the Payment if is set and if exits in
     * the customer table
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function customerId(Payment $payment): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if ($payment->issetCustomerID()) {
            $allCustomer = $this->auditFile->getMasterFiles()->getAllCustomerID();
            if (\in_array($payment->getCustomerID(), $allCustomer) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("customerID_not_exits"),
                    $payment->getCustomerID(), $payment->getPaymentRefNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_CUSTOMER_ID);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("customerID_not_defined_in_document"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, Payment::N_CUSTOMER_ID);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the Payment
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function lines(Payment $payment): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (\count($payment->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, Payment::N_PAYMENT_REF_NO);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $n = 0;
        /* @var $lineNoStack int[] */
        $lineNoStack = array();
        $lineNoError = false;
        //$hasDebit and $hasCredit is to check if the document as both debit and credit lines

        $totalSettlement   = new Decimal("0.0");
        $lineSumNetTotal   = new Decimal("0.0");
        $lineSumTaxPayable = new Decimal("0.0");

        /**
         * @link https://info.portaldasfinancas.gov.pt/pt/apoio_contribuinte/questoes_frequentes/Pages/faqs-00276.aspx
         * FAQs: 55-2791, 55-2792
         */
        foreach ($payment->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_no_continues"
                            ), $payment->getPaymentRefNo()
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
                            ), $payment->getPaymentRefNo()
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
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg, Line::N_LINE_NUMBER);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    $lineNoError   = true;
                    continue;
                }
            }

            $lineValue  = new Decimal("0.0");
            $lineTaxCal = new Decimal("0.0");

            if ($line->getCreditAmount() === null &&
                $line->getDebitAmount() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_no_debit_or_credit"),
                    $payment->getPaymentRefNo(), $line->getLineNumber()
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
            $lineValue       = $lineValue->add($lineAmount);
            $settlement      = $line->getSettlementAmount() ?? new Decimal("0.0");
            $totalSettlement = $totalSettlement->add($settlement);

            $lineTax = $line->getTax(false);

            if ($lineTax !== null) {

                if ($lineTax->getTaxAmount() !== null) {
                    $lineTaxCal = new Decimal($lineTax->getTaxAmount());
                }

                if ($lineTax->getTaxPercentage() !== null &&
                    $lineTax->getTaxPercentage()->compareTo("0.0") !== 0) {

                    $lineFactor = $lineTax->getTaxPercentage()->div("100.0");

                    $lineTaxCal = new Decimal(
                        $lineFactor->mul($lineAmount->abs()->add($settlement))
                    );

                    if ($lineAmount->compareTo("0.0") < 0) {
                        $lineTaxCal = $lineTaxCal->mul("-1");
                    }
                }
            }

            $notForTotal = array(
                PaymentStatus::A
            );

            $docStat = $payment->getDocumentStatus()->getPaymentStatus();

            if ($line->getCreditAmount() !== null) {
                $credit          = new Decimal($line->getCreditAmount());
                $this->docCredit = $this->docCredit->add($credit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->credit = $this->credit->add($credit);
                }
            }

            if ($line->getDebitAmount() !== null) {
                $this->docDebit = $this->docDebit->add(
                    $line->getDebitAmount()
                );

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->debit = $this->debit->add(
                        $line->getDebitAmount()
                    );
                }
            }

            //The validation if is CashVatScheme is made in tax method
            $this->tax($line, $payment);

            $lineSumNetTotal   = $lineSumNetTotal->add($lineValue);
            $lineSumTaxPayable = $lineSumTaxPayable->add($lineTaxCal);

            if (\count($line->getSourceDocumentID()) > 0) {
                $this->sourceDocumentID($line, $payment);
            }
        }

        if ($lineSumNetTotal->compareTo("0.0") < 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("payment_must_be_credit_document"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        $this->netTotal   = $this->netTotal->add($lineSumNetTotal->abs());
        $this->taxPayable = $this->taxPayable->add($lineSumTaxPayable->abs());
        $this->grossTotal = $this->netTotal->add($this->taxPayable->abs());

        $payment->getDocTotalCal()?->setGrossTotal($this->grossTotal);
        $payment->getDocTotalCal()?->setNetTotal($this->netTotal);
        $payment->getDocTotalCal()?->setTaxPayable($this->taxPayable);

        if ($totalSettlement->compareTo("0.0") > 0) {
            if ($payment->issetDocumentTotals()) {
                $paySett = $payment->getDocumentTotals()->getSettlementAmount() ?? new Decimal("0.0");
            } else {
                $paySett = new Decimal("0.0");
            }

            $diff = $totalSettlement->sub($paySett)->abs();

            if ($diff->compareTo("0.0") > 0) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("payment_settlement_sum_diff"),
                    $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate the SourceDocumentID
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    public function sourceDocumentID(Line $line, Payment $payment): void
    {

        if (\count($line->getSourceDocumentID()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("payment_without_any_source_doc_id"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_SOURCE_DOCUMENT_ID);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $originStack = array();
        foreach ($line->getSourceDocumentID() as $source) {
            if ($source->issetOriginatingON() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "originating_on_document_not_defined"
                    ), $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORIGINATING_ON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            } else {
                $val = AAuditFile::validateDocNumber($source->getOriginatingON());
                if ($val === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "originating_on_document_number_not_valid"
                        ), $payment->getPaymentRefNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $source->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
                if (\in_array($source->getOriginatingON(), $originStack)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "originating_on_document_repeated"
                        ), $payment->getPaymentRefNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $source->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                } else {
                    $originStack[] = $source->getOriginatingON();
                }
            }

            if ($source->issetInvoiceDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_date_not_indicated"
                    ), $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORDER_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            } elseif ($source->getInvoiceDate()->isLater($payment->getTransactionDate())) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORDER_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate the line Tax
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line    $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, Payment $payment): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        $lineTax = $line->getTax(false);

        if ($lineTax === null && $payment->getPaymentType() === PaymentType::RC) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("payment_cash_vat_without_tax"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Tax::N_TAX_TYPE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax === null) {
            return;
        }

        if ($lineTax->issetTaxType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_type"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
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
                $payment->getPaymentRefNo(), $line->getLineNumber()
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
                $payment->getPaymentRefNo(), $line->getLineNumber()
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
                $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_PERCENTAGE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if (
            $lineTax->getTaxAmount()?->compareTo("0.0") === 0 ||
            $lineTax->getTaxPercentage()?->compareTo("0.0") === 0
        ) {
            if ($line->getTaxExemptionCode() === null || $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $payment->getPaymentRefNo()
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
                    $payment->getPaymentRefNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
                $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }


        if (
            $lineTax->getTaxCode() !== TaxCode::ISE &&
            ($lineTax->getTaxPercentage()?->compareTo("0.0") ?? 0) !== 0 &&
            ($line->getTaxExemptionCode() !== null || $line->getTaxExemptionReason() !== null)
        ) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_for_tax_zero"),
                $payment->getPaymentRefNo()
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

            if (
                $taxEntry->getTaxType() !== $lineTax->getTaxType() ||
                ($taxEntry->getTaxAmount() !== $lineTax->getTaxAmount() &&
                    $taxEntry->getTaxPercentage() !== $lineTax->getTaxPercentage()) ||
                $taxEntry->getTaxCountryRegion() !== $lineTax->getTaxCountryRegion()
            ) {
                continue;
            }

            if ($taxEntry->getTaxExpirationDate() === null) {// is valid
                return;
            }
            if ($taxEntry->getTaxExpirationDate()->isLater($payment->getTransactionDate())) {// is valid
                return;
            }
        }

        $this->isValid = false; // No table tax entry
        $msg           = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $payment->getPaymentRefNo()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function totals(Payment $payment): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($payment->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $totals = $payment->getDocumentTotals();
        $gross  = new Decimal($totals->getGrossTotal());
        $net    = new Decimal($totals->getNetTotal());
        $tax    = new Decimal($totals->getTaxPayable());

        if ($gross->compareTo($net->add($tax)) !== 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($gross->sub($this->grossTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal, $payment->getPaymentRefNo(), $gross->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSS_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($net->sub($this->netTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_net_total_not_equal_calc_net_total"),
                $this->netTotal, $payment->getPaymentRefNo(), $net->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NET_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($tax->sub($this->taxPayable)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_tax_payable_not_equal_calc_tax_payable"),
                $this->taxPayable, $payment->getPaymentRefNo(), $tax->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAX_PAYABLE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($payment->getDocumentTotals()->getCurrency(false) === null) {
            AAuditFile::$logger?->info(
                \sprintf(
                    "Invoice '%s' without currency node",
                    $payment->getPaymentRefNo()
                )
            );
            return;
        }

        if (null === $currency = $payment->getDocumentTotals()->getCurrency()) {
            return;
        }

        $currentAmount = new Decimal($currency->getCurrencyAmount());
        $rate          = new Decimal($currency->getExchangeRate());
        $grossExchange = $currentAmount->mul($rate);
        $payment->getDocTotalCal()?->setGrossTotalFromCurrency($grossExchange);
        $calcExchange = $gross->sub($grossExchange)->abs();

        if ($calcExchange > $this->deltaCurrency) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError(
                $msg,
                Currency::N_EXCHANGE_RATE
            );
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Payment date nad SystemEntryDate
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function paymentDateAndSystemEntryDate(Payment $payment): void
    {
        $docDate           = $payment->getTransactionDate();
        $systemDate        = $payment->getSystemEntryDate();
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
                        $payment->getPaymentRefNo()
                    );
                    $msgStack[] = $msg;
                    $payment->addError($msg, Payment::N_SYSTEM_ENTRY_DATE);
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_not_checked_start_end_header_date"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_TRANSACTION_DATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_earlier_previous_doc"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_TRANSACTION_DATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_system_entry_date_earlier_previous_doc"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_SYSTEM_ENTRY_DATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the PaymentMethods
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function paymentMethod(Payment $payment): void
    {
        $payMet = null;

        if (\count($payment->getPaymentMethod()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "payment_without_payment_method"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addWarning($msg);
            $payment->addWarning($msg);
            AAuditFile::$logger?->info($msg);
            return;
        }

        $totalPayMeth = new Decimal("0.0");

        foreach ($payment->getPaymentMethod() as $payMet) {
            if ($payMet->issetPaymentAmount()) {
                $totalPayMeth = $totalPayMeth->add($payMet->getPaymentAmount());
            } else {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "payment_method_without_payment_amount"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payMet->issetPaymentDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "payment_method_without_payment_date"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }
        }

        if ($payment->issetDocumentTotals()) {
            if ($payment->getDocumentTotals()->issetGrossTotal()) {
                $gross = $payment->getDocumentTotals()->getGrossTotal();
                $diff  = $totalPayMeth->sub($gross);

                foreach ($payment->getWithholdingTax() as $withholding) {
                    if ($withholding->issetWithholdingTaxAmount()) {
                        $diff = $diff->add($withholding->getWithholdingTaxAmount());
                    }
                }

                if ($diff->abs()->compareTo($this->getDeltaTotalDoc()) > 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "payment_method_sum_not_equal_to_gross_less_tax"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payMet->addError($msg);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                }
            }
        }
    }

    /**
     * Validate the withholdingTax
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     *
     * @return void
     * @since 1.0.0
     */
    protected function withholdingTax(Payment $payment): void
    {
        $totalTax = new Decimal("0.0");
        foreach ($payment->getWithholdingTax() as $withholding) {
            if ($withholding->issetWithholdingTaxAmount() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "withholding_without_amount"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, WithholdingTax::N_WITHHOLDING_TAX);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }
            $totalTax = $totalTax->add($withholding->getWithholdingTaxAmount());
        }

        if ($totalTax->compareTo("0.0") === 0) {
            return;
        }

        if ($payment->issetDocumentTotals()) {
            if ($payment->getDocumentTotals()->issetGrossTotal()) {
                $gross = $payment->getDocumentTotals()->getGrossTotal();
                if ($totalTax->compareTo($gross) >= 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholding_tax_greater_than_gross"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payment->addError($msg, WithholdingTax::N_WITHHOLDING_TAX);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    return;
                }
                if ($totalTax->compareTo($gross->div(2)) > 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholding_tax_greater_than_half_gross"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $payment->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
            }
        }
    }

}
