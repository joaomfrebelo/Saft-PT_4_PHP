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
use Rebelo\Decimal\UDecimal;
use Rebelo\Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments as SaftPayments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;

/**
 * Validate Payments table.<br>
 * This class will validate the values of Payments and dates
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Payments extends ADocuments
{

    /**
     * Validate Payments table.<br>
     * This class will validate the values of Payments and dates
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile The AuditFile to be validated
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        parent::__construct($auditFile);

        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $payments = $sourceDoc->getPayments(false);
            if ($payments !== null) {
                $payments->setDocTableTotalCalc(
                    new \Rebelo\SaftPt\Validate\DocTableTotalCalc()
                );
            }
        }
    }

    /**
     * Validate the payments
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $progreBar = null;
        try {
            $payments = $this->auditFile->getSourceDocuments()
                    ->getPayments(false);

            if ($payments === null) {
                \Logger::getLogger(\get_class($this))
                        ->debug(__METHOD__ . " no sales payments to be vaidated");
                return $this->isValid;
            }

            $payments->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($payments->getPayment()) === 0) {

                if ($payments->getTotalCredit() !== 0.0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "payments_total_credit_should_be_zero"
                        ), $payments->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payments->addError(
                        $msg, SaftPayments::N_TOTALCREDIT
                    );
                    $this->isValid = false;
                }

                if ($payments->getTotalDebit() !== 0.0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "payments_total_debit_should_be_zero"
                        ), $payments->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payments->addError(
                        $msg, SaftPayments::N_TOTALDEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $payments->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc = \count($payments->getPayment());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section = null;
                $progreBar = $this->getStyle()->addProgressBar($section);
                $section->writeln("");
                $section->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "Payments"
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

                        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
                        $payment = $order[$type][$serie][$no];
                        list(, $no) = \explode("/", $payment->getPaymentRefNo());
                        if ((string) $type !== $this->lastType || (string) $serie !== $this->lastSerie) {
                            $this->lastHash = "";
                            $this->lastDocDate = null;
                            $this->lastSystemEntryDate = null;
                        } else {
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
                        $payment->setDocTotalcal(new DocTotalCalc());
                        $this->payment($payment);
                        $this->lastType = (string) $type;
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
                            __METHOD__ . " validate error '%s'", $e->getMessage()
                        )
                    );
        }
        return $this->isValid;
    }

    /**
     * Validate Payment
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function payment(Payment $payment): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try {
            $this->docCredit = new UDecimal(0.0, static::CALC_PRECISION);
            $this->docDebit = new UDecimal(0.0, static::CALC_PRECISION);
            $this->netTotal = new UDecimal(0.0, static::CALC_PRECISION);
            $this->taxPayable = new UDecimal(0.0, static::CALC_PRECISION);
            $this->grossTotal = new UDecimal(0.0, static::CALC_PRECISION);

            if ($payment->issetPaymentRefNo() === false) {
                $msg = AAuditFile::getI18n()->get("invoicetno_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($payment->issetPaymentType() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "paymenttype_not_defined"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_PAYMENTTYPE);
                \Logger::getLogger(\get_class($this))->info($msg);
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
                $payment->addError($msg, Payment::N_TRANSACTIONDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payment->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_systementrydate_not_defined"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_SYSTEMENTRYDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
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
        } catch (\Exception | \Error $e) {
            $this->auditFile->getErrorRegistor()
                    ->addExceptionErrors($e->getMessage());
            \Logger::getLogger(\get_class($this))
                    ->debug(
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
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $payments = $this->auditFile->getSourceDocuments()->getPayments();
        $calculatedNumOfEntries = \count($payments->getPayment());
        $numberOfEntries = $payments->getNumberOfEntries();
        $test = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()->getPayments()
                ->getDocTableTotalCalc()->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_payments"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_NUMBEROFENTRIES);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate Payments TotalDebit
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $payments = $this->auditFile->getSourceDocuments()
                ->getPayments();

        $payments->getDocTableTotalCalc()
                ->setTotalDebit($this->debit->valueOf());

        $diff = $this->debit->signedSubtract(
            new Decimal(
                $payments->getTotalDebit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_payments"
                ), $payments->getTotalDebit(), $this->debit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_TOTALDEBIT);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate Payments TotalCredit
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $payments = $this->auditFile->getSourceDocuments()->getPayments();

        $payments->getDocTableTotalCalc()->setTotalCredit($this->credit->valueOf());

        $diff = $this->credit->signedSubtract(
            new Decimal(
                $payments->getTotalCredit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_payments"
                ), $payments->getTotalCredit(), $this->credit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payments->addError($msg, SaftPayments::N_TOTALCREDIT);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
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
            $payment->addError($msg, DocumentStatus::N_PAYMENTSTATUS);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        /* @var $status \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus */
        $status = $payment->getDocumentStatus();

        if ($status->getPaymentStatusDate()->isEarlier($payment->getTransactionDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, DocumentStatus::N_PAYMENTSTATUSDATE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getPaymentStatus()->isEqual(PaymentStatus::A) &&
                $status->getReason() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, DocumentStatus::N_REASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }
    }

    /**
     * validate if the customerID of the Payment if is set and if exits in
     * the customer table
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function customerId(Payment $payment): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($payment->issetCustomerID()) {
            $allCustomer = $this->auditFile->getMasterFiles()->getAllCustomerID();
            if (\in_array($payment->getCustomerID(), $allCustomer) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("customerID_not_exits"),
                    $payment->getCustomerID(), $payment->getPaymentRefNo()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, Payment::N_CUSTOMERID);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("customerID_not_defined_in_document"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, Payment::N_CUSTOMERID);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the Payment
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function lines(Payment $payment): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($payment->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg, Payment::N_PAYMENTREFNO);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $n = 0;
        /* @var $lineNoStack int[] */
        $lineNoStack = array();
        $lineNoError = false;
        //$hasDebit and $hasCredit is to check if the document as both debit and credit lines
        $hasDebit = false;
        $hasCredit = false;
        $totalSettlement = new UDecimal(0.0, static::CALC_PRECISION);

        $lineSumNetTotal = new Decimal(0.0, static::CALC_PRECISION);
        $lineSumTaxPayable = new Decimal(0.0, static::CALC_PRECISION);

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
                        $line->addError($msg, Line::N_LINENUMBER);
                        \Logger::getLogger(\get_class($this))->info($msg);
                        $this->isValid = false;
                        $lineNoError = true;
                    } elseif (\in_array($line->getLineNumber(), $lineNoStack)) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_duplicated"
                            ), $payment->getPaymentRefNo()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINENUMBER);
                        \Logger::getLogger(\get_class($this))->info($msg);
                        $this->isValid = false;
                        $lineNoError = true;
                    }
                    $lineNoStack[] = $line->getLineNumber();
                } else {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "document_line_no_number"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg, Line::N_LINENUMBER);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    $lineNoError = true;
                    continue;
                }
            }

            $lineValue = new Decimal(0.0, static::CALC_PRECISION);
            $lineTaxCal = new Decimal(0.0, static::CALC_PRECISION);

            if ($line->getCreditAmount() === null &&
                    $line->getDebitAmount() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_no_debit_or_credit"),
                    $payment->getPaymentRefNo(), $line->getLineNumber()
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
            $settlement = $line->getSettlementAmount() ?? 0.0;
            $totalSettlement->plusThis($settlement);

            $lineTax = $line->getTax(false);
            if ($lineTax !== null) {

                if ($lineTax->getTaxAmount() !== null) {
                    $lineTaxCal = new Decimal(
                        $lineTax->getTaxAmount(), static::CALC_PRECISION
                    );
                }

                if ($lineTax->getTaxPercentage() !== null &&
                        $lineTax->getTaxPercentage() !== 0.0) {

                    $lineFactor = $lineTax->getTaxPercentage() / 100;

                    $lineTaxCal = new Decimal(
                        $lineFactor * (\abs($lineAmount) + $settlement),
                        static::CALC_PRECISION
                    );

                    if ($lineAmount < 0.0) {
                        $lineTaxCal->multiplyThis(-1);
                    }
                }
            }

            $notForTotal = array(
                PaymentStatus::A
            );

            $docStat = $payment->getDocumentStatus()->getPaymentStatus()->get();

            if ($line->getCreditAmount() !== null) {
                $credit = new UDecimal(
                    $line->getCreditAmount(), static::CALC_PRECISION
                );
                $this->docCredit->plusThis($credit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->credit->plusThis($credit);
                }

                $hasCredit = true;
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
            }

            //The validation if is CashVatScheme is made in tax method
            $this->tax($line, $payment);

            $lineSumNetTotal->plusThis($lineValue);
            $lineSumTaxPayable->plusThis($lineTaxCal);

            if (\count($line->getSourceDocumentID()) > 0) {
                $this->sourceDocumentID($line, $payment);
            }
        }

        if ($lineSumNetTotal->isLess(0.0)) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("payment_must_be_credit_document"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        $this->netTotal->plusThis($lineSumNetTotal->abs());
        $this->taxPayable->plusThis($lineSumTaxPayable->abs());
        $this->grossTotal = $this->netTotal->plus($this->taxPayable->abs());

        $payment->getDocTotalcal()->setGrossTotal($this->grossTotal->valueOf());
        $payment->getDocTotalcal()->setNetTotal($this->netTotal->valueOf());
        $payment->getDocTotalcal()->setTaxPayable($this->taxPayable->valueOf());

        if ($totalSettlement->isGreater(0.0)) {
            if ($payment->issetDocumentTotals()) {
                $paySett = $payment->getDocumentTotals()->getSettlementAmount() ?? 0.0;
            } else {
                $paySett = 0.0;
            }

            $diff = $totalSettlement->signedSubtract($paySett)->abs();

            if ($diff->isGreater(0.0)) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("payment_settlement_sum_diff"),
                    $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate the SourceDocumentID
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
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
            $line->addError($msg, Line::N_SOURCEDOCUMENTID);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $originStack = array();
        foreach ($line->getSourceDocumentID() as $source) {
            /* @var $source \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID */
            if ($source->issetOriginatingON() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "originatingon_document_not_defined"
                    ), $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORIGINATINGON);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            } else {
                $val = AAuditFile::validateDocNumber($source->getOriginatingON());
                if ($val === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "originatingon_document_number_not_valid"
                        ), $payment->getPaymentRefNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $source->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                }
                if (\in_array($source->getOriginatingON(), $originStack)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "originatingon_document_repeated"
                        ), $payment->getPaymentRefNo(), $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $source->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                } else {
                    $originStack[] = $source->getOriginatingON();
                }
            }

            if ($source->issetInvoiceDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_date_not_incicated"
                    ), $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORDERDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            } elseif ($source->getInvoiceDate()->isLater($payment->getTransactionDate())) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $payment->getPaymentRefNo(), $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $source->addError($msg, OrderReferences::N_ORDERDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * Validate the line Tax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, Payment $payment): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        $lineTax = $line->getTax(false);

        if ($lineTax === null && $payment->getPaymentType()->isEqual(PaymentType::RC)) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("payment_cash_vat_without_tax"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Tax::N_TAXTYPE);
            \Logger::getLogger(\get_class($this))->info($msg);
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
            $lineTax->addError($msg, Tax::N_TAXTYPE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCode() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_code"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxCountryRegion() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_region"),
                $payment->getPaymentRefNo(), $line->getLineNumber()
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
                $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXPERCENTAGE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->getTaxAmount() === 0.0 ||
                $lineTax->getTaxPercentage() === 0.0) {
            if ($line->getTaxExemptionCode() === null ||
                    $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $payment->getPaymentRefNo()
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
                    $payment->getPaymentRefNo()
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

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_isent"),
                $payment->getPaymentRefNo()
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
            if ($taxEntry->getTaxExpirationDate()->isLater($payment->getTransactionDate())) {// is valid
                return;
            }
        }

        $this->isValid = false; // No table tax entry
        $msg = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $payment->getPaymentRefNo()
        );
        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        \Logger::getLogger(\get_class($this))->info($msg);
        $line->addError($msg);
        $this->isValid = false;
    }

    /**
     * Validate the document total, only can be invoked after
     * validate lines (Because total controls are getted from that validation)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function totals(Payment $payment): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($payment->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $payment->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $totals = $payment->getDocumentTotals();
        $gross = new UDecimal($totals->getGrossTotal(), 2);
        $net = new UDecimal($totals->getNetTotal(), static::CALC_PRECISION);
        $tax = new UDecimal($totals->getTaxPayable(), static::CALC_PRECISION);

        if ($gross->equals($net->plus($tax)) === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $payment->getPaymentRefNo()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($gross->signedSubtract($this->grossTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal, $payment->getPaymentRefNo(), $gross->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSSTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($net->signedSubtract($this->netTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_nettotal_not_equal_calc_nettotal"),
                $this->netTotal, $payment->getPaymentRefNo(), $net->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NETTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($tax->signedSubtract($this->taxPayable)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_taxpayable_not_equal_calc_taxpayable"),
                $this->taxPayable, $payment->getPaymentRefNo(), $tax->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAXPAYABLE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($payment->getDocumentTotals()->getCurrency(false) === null) {
            \Logger::getLogger(\get_class($this))->info(
                \sprintf(
                    "Invoice '%s' without currency node",
                    $payment->getPaymentRefNo()
                )
            );
            return;
        }

        $currency = $payment->getDocumentTotals()->getCurrency();
        $currAmou = new UDecimal(
            $currency->getCurrencyAmount(), static::CALC_PRECISION
        );
        $rate = new UDecimal(
            $currency->getExchangeRate(), static::CALC_PRECISION
        );
        $grossExchange = $currAmou->multiply($rate);
        $payment->getDocTotalcal()->setGrossTotalFromCurrency($grossExchange->valueOf());
        $calcCambio = $gross->signedSubtract($grossExchange, 2)->abs()->valueOf();

        if ($calcCambio > $this->deltaCurrency) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $payment->getPaymentRefNo()
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
     * Validate the Payment date nad SystemEntrydate
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function paymentDateAndSystemEntryDate(Payment $payment): void
    {
        $docDate = $payment->getTransactionDate();
        $systemDate = $payment->getSystemEntryDate();
        $msgStack = [];
        $headerDateChecked = false;
        if ($this->auditFile->issetHeader()) {
            $header = $this->auditFile->getHeader();
            if ($header->issetStartDate() && $header->issetEndDate()) {
                if ($header->getStartDate()->isLater($docDate) ||
                        $header->getEndDate()->isEarlier($docDate)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()
                                    ->get("doc_date_out_of_range_start_end_header_date"),
                        $payment->getPaymentRefNo()
                    );
                    $msgStack[] = $msg;
                    $payment->addError($msg, Payment::N_SYSTEMENTRYDATE);
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_date_not_cheked_start_end_header_date"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_TRANSACTIONDATE);
        }

        if ($this->lastDocDate !== null &&
                $this->lastDocDate->isLater($docDate)) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_date_eaarlier_previous_doc"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_TRANSACTIONDATE);
        }

        if ($this->lastSystemEntryDate !== null &&
                $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_systementrydate_earlier_previous_doc"),
                $payment->getPaymentRefNo()
            );
            $msgStack[] = $msg;
            $payment->addError($msg, Payment::N_SYSTEMENTRYDATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the PaymentMethods
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function paymentMethod(Payment $payment): void
    {
        if (\count($payment->getPaymentMethod()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "payment_withou_payment_method"
                ), $payment->getPaymentRefNo()
            );
            $this->auditFile->getErrorRegistor()->addWarning($msg);
            $payment->addWarning($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            return;
        }

        $totalPayMeth = new UDecimal(0.0, static::CALC_PRECISION);

        /* @var $payMet \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
        foreach ($payment->getPaymentMethod() as $payMet) {
            if ($payMet->issetPaymentAmount()) {
                $totalPayMeth->plusThis($payMet->getPaymentAmount());
            } else {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "paymentmethod_withou_payment_amout"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($payMet->issetPaymentDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "paymentmethod_withou_payment_date"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payMet->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
        }

        if ($payment->issetDocumentTotals()) {
            if ($payment->getDocumentTotals()->issetGrossTotal()) {
                $gross = $payment->getDocumentTotals()->getGrossTotal();
                $diff = $totalPayMeth->signedSubtract($gross);

                foreach ($payment->getWithholdingTax() as $withholding) {
                    /* @var $withholding \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax */
                    if ($withholding->issetWithholdingTaxAmount()) {
                        $diff->plusThis($withholding->getWithholdingTaxAmount());
                    }
                }

                if ($diff->abs()->isGreater($this->getDeltaTotalDoc())) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "paymentmethod_sum_not_equal_to_gross_less_tax"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payMet->addError($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    return;
                }
            }
        }
    }

    /**
     * Validate the withholdingTax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment $payment
     * @return void
     * @since 1.0.0
     */
    protected function withholdingTax(Payment $payment): void
    {
        $totalTax = new UDecimal(0.0, static::CALC_PRECISION);
        foreach ($payment->getWithholdingTax() as $withholding) {
            /* @var $withholding \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax */
            if ($withholding->issetWithholdingTaxAmount() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "withholding_without_amout"
                    ), $payment->getPaymentRefNo()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $payment->addError($msg, WithholdingTax::N_WITHHOLDINGTAX);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
            $totalTax->plusThis($withholding->getWithholdingTaxAmount());
        }

        if ($totalTax->isEquals(0.0)) {
            return;
        }

        if ($payment->issetDocumentTotals()) {
            if ($payment->getDocumentTotals()->issetGrossTotal()) {
                $gross = $payment->getDocumentTotals()->getGrossTotal();
                if ($totalTax->isGreater($gross) || $totalTax->isEquals($gross)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholdingtax_greater_than_gross"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $payment->addError($msg, WithholdingTax::N_WITHHOLDINGTAX);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    return;
                }
                if ($totalTax->isGreater($gross / 2)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "withholdingtax_greater_than_half_gross"
                        ), $payment->getPaymentRefNo()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $payment->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    return;
                }
            }
        }
    }

}
