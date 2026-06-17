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
use Rebelo\Date\DateParseException;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\References;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments as SaftWorkingDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Validate WorkingDocuments table.<br>
 * This class will validate the values of WorkingDocuments, the
 * signature hash and dates
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class WorkingDocuments extends ADocuments
{

    /**
     * Validate WorkingDocuments table.<br>
     * This class will validate the values of WorkingDocuments, the
     * signature hash and dates
     *
     * @param AuditFile $auditFile The AuditFile to be validated
     * @param Sign      $sign      The sign class to be used to validate the hash, must have the public key defined
     *
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile, Sign $sign)
    {
        AAuditFile::$logger?->debug(__METHOD__);
        parent::__construct($auditFile, $sign);

        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $workingDocuments = $sourceDoc->getWorkingDocuments(false);
            $workingDocuments?->setDocTableTotalCalc(
                new DocTableTotalCalc()
            );
        }
    }

    /**
     * Validate the working documents
     *
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        AAuditFile::$logger?->debug(__METHOD__);
        $progressBar = null;
        try {
            $workingDocuments = $this->auditFile->getSourceDocuments()?->getWorkingDocuments(false);

            if ($workingDocuments === null) {
                AAuditFile::$logger?->debug(__METHOD__ . " no work documents to be validated");
                return $this->isValid;
            }

            $workingDocuments->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($workingDocuments->getWorkDocument()) === 0) {

                if (!$workingDocuments->getTotalCredit()->equals("0.0")) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "working_documents_total_credit_should_be_zero"
                        ), $workingDocuments->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $workingDocuments->addError(
                        $msg, SaftWorkingDocuments::N_TOTAL_CREDIT
                    );
                    $this->isValid = false;
                }

                if (!$workingDocuments->getTotalDebit()->equals(0.0)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "working_documents_total_debit_should_be_zero"
                        ), $workingDocuments->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $workingDocuments->addError(
                        $msg, SaftWorkingDocuments::N_TOTAL_DEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $workingDocuments->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc        = \count($workingDocuments->getWorkDocument());
                $section     = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section?->writeln("");
                $section?->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "WorkDocument"
                    )
                );
                $progressBar?->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serial) {
                    foreach (\array_keys($order[$type][$serial]) as $no) {

                        $progressBar?->advance();

                        $workDocument = $order[$type][$serial][$no];
                        list(, $no) = \explode(
                            "/",
                            $workDocument->getDocumentNumber()
                        );
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

                        $workDocument->setDocTotalCalc(new DocTotalCalc());
                        $this->workDocument($workDocument);
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
     * Validate WorkDocument
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function workDocument(WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        try {
            $this->docCredit  = new Decimal("0.0");
            $this->docDebit   = new Decimal("0.0");
            $this->netTotal   = new Decimal("0.0");
            $this->taxPayable = new Decimal("0.0");
            $this->grossTotal = new Decimal("0.0");

            if ($workDocument->issetDocumentNumber() === false) {
                $msg = AAuditFile::getI18n()->get("work_document_number_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetWorkType() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "work_document_type_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_WORK_TYPE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetWorkDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_WORK_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_system_entry_date_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_SYSTEM_ENTRY_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->sign($workDocument);
            $this->workDocumentDateAndSystemEntryDate($workDocument);
            $this->customerId($workDocument);
            $this->documentStatus($workDocument);
            $this->lines($workDocument);
            $this->totals($workDocument);
            $this->outOfDateInvoiceTypes($workDocument);
        } catch (\Exception|\Error $e) {
            $this->auditFile->getErrorRegistor()
                            ->addExceptionErrors($e->getMessage());
            AAuditFile::$logger?->debug(
                \sprintf(
                    __METHOD__ . " validate error '%s'", $e->getMessage()
                )
            );
            $workDocument->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfEntries is equal to the number of WorkDocuments
     *
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $workingDocuments = $this->auditFile->getSourceDocuments()?->getWorkingDocuments(false)) {
            return;
        }

        $calculatedNumOfEntries = \count($workingDocuments->getWorkDocument());
        $numberOfEntries        = $workingDocuments->getNumberOfEntries();
        $test                   = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()->getWorkingDocuments()
                        ?->getDocTableTotalCalc()
                        ?->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_work_documents"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError(
                $msg, SaftWorkingDocuments::N_NUMBER_OF_ENTRIES
            );
            AAuditFile::$logger?->info($msg);
        }
        if ($test === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate WorkingDocuments TotalDebit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $workingDocuments = $this->auditFile->getSourceDocuments()?->getWorkingDocuments(false)) {
            return;
        }

        $workingDocuments->getDocTableTotalCalc()?->setTotalDebit($this->debit);

        $diff = $this->debit->sub($workingDocuments->getTotalDebit())->abs();

        if ($diff > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_working_documents"
                ), $workingDocuments->getTotalDebit(), $this->debit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError($msg, SaftWorkingDocuments::N_TOTAL_DEBIT);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate WorkingDocuments TotalCredit
     *
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (null === $workingDocuments = $this->auditFile->getSourceDocuments()?->getWorkingDocuments(false)) {
            return;
        }

        $workingDocuments->getDocTableTotalCalc()?->setTotalDebit($this->credit);
        $diff = $this->credit->sub($workingDocuments->getTotalCredit())->abs();

        if ($diff > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_working_documents"
                ), $workingDocuments->getTotalCredit(), $this->credit->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError(
                $msg, SaftWorkingDocuments::N_TOTAL_CREDIT
            );
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(WorkDocument $workDocument): void
    {
        if ($workDocument->issetDocumentStatus() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_DOCUMENT_STATUS);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $workDocument->getDocumentStatus();

        if ($status->getWorkStatusDate()->isEarlier($workDocument->getWorkDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_WORK_STATUS_DATE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getWorkStatus() === WorkStatus::A && $status->getReason() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate if the customerID of the WorkDocument if is set and if exits in
     * the customer table
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function customerId(WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if ($workDocument->issetCustomerID()) {
            $allCustomer = $this->auditFile->getMasterFiles()->getAllCustomerID();
            if (\in_array($workDocument->getCustomerID(), $allCustomer) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "customerID_not_exits"
                    ), $workDocument->
                    getCustomerID(), $workDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_CUSTOMER_ID);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "customerID_not_defined_in_document"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_CUSTOMER_ID);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the WorkDocument
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function lines(WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (\count($workDocument->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_DOCUMENT_NUMBER);
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

        // For the case that line annulation are use,
        // validate if the annulation is bigger or not

        /* @var $canceledDebitValue \Decimal\Decimal[] */
        $canceledDebitValue = array();
        /* @var $canceledCreditValue \Decimal\Decimal[] */
        $canceledCreditValue = array();
        /* @var $canceledDebitQt \Decimal\Decimal[] */
        $canceledDebitQt = array();
        /* @var $canceledCreditQt \Decimal\Decimal[] */
        $canceledCreditQt = array();

        foreach ($workDocument->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_no_continues"
                            ), $workDocument->getDocumentNumber()
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
                            ), $workDocument->getDocumentNumber()
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
                        ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            /** @var Decimal $lineAmount */
            $lineAmount = $line->getCreditAmount() === null
                ? $line->getDebitAmount()?->mul("-1.0")
                : $line->getCreditAmount();

            // Get value for total validation
            $lineValue = $lineValue->add($lineAmount);

            if ($line->issetTax()) {

                $lineTax = $line->getTax();

                if ($lineTax->getTaxAmount() !== null) {
                    $lineTaxCal = new Decimal($lineTax->getTaxAmount());
                }

                if ($lineTax->getTaxPercentage() !== null && $lineTax->getTaxPercentage()->compareTo("0.0") !== 0) {

                    $lineFactor = $lineTax->getTaxPercentage()->div("100.0");

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

            $workDocument->getDocTotalCalc()?->addLineTotal(
                $line->getLineNumber(), $uniQt
            );

            if ($line->getTaxBase() !== null &&
                ($unitPrice->compareTo("0.0") > 0 || $lineValue->compareTo("0.0") !== 0)
            ) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_have_tax_base_with_unit_price_credit_debit"
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                        Line::N_DEBIT_AMOUNT : Line::N_CREDIT_AMOUNT
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }

            $notForTotal = array(
                WorkStatus::A
            );

            $docStat = $workDocument->getDocumentStatus()->getWorkStatus();

            if ($line->getCreditAmount() !== null) {
                $credit          = new Decimal($line->getCreditAmount());
                $this->docCredit = $this->docCredit->add($credit);

                if (\in_array($docStat, $notForTotal) === false) {
                    $this->credit = $this->credit->add($credit);
                }

                $hasCredit = true;

                if (\array_key_exists($line->getProductCode(), $canceledCreditQt)) {

                    $canceledCreditQt[$line->getProductCode()] = $canceledCreditQt[$line->getProductCode()]
                        ->add($line->getQuantity());

                    $canceledCreditValue[$line->getProductCode()] = $canceledCreditValue[$line->getProductCode()]->add($uniQt);

                } else {

                    $canceledCreditQt[$line->getProductCode()] = $canceledCreditQt[$line->getProductCode()] = new Decimal(
                        $line->getQuantity()
                    );

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
                    $canceledDebitQt[$line->getProductCode()]    = $canceledDebitQt[$line->getProductCode()]->add(
                        $line->getQuantity()
                    );
                    $canceledDebitValue[$line->getProductCode()] = $canceledDebitValue[$line->getProductCode()]->add($uniQt);
                } else {
                    $canceledDebitQt[$line->getProductCode()]    = new Decimal(
                        $line->getQuantity()
                    );
                    $canceledDebitValue[$line->getProductCode()] = new Decimal($uniQt);
                }
            }

            $this->productCode($line, $workDocument);

            if ($line->issetTax()) {
                $this->tax($line, $workDocument);
            } elseif ($line->getTaxBase() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_must_be_defined"),
                    $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $this->references($line, $workDocument);
            }
            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $workDocument);
            }
        }

        $this->grossTotal = $this->netTotal->add($this->taxPayable);

        $workDocument->getDocTotalCalc()?->setGrossTotal($this->grossTotal);
        $workDocument->getDocTotalCalc()?->setNetTotal($this->netTotal);
        $workDocument->getDocTotalCalc()?->setTaxPayable($this->taxPayable);

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_has_credit_and_debit_lines"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate references of NC (Credit note)
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line         $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    public function references(Line $line, WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (\count($line->getReferences()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_references"
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                        $workDocument->getDocumentNumber(),
                        $line->getLineNumber(), $reference->getReference()
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
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line         $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    public function orderReferences(Line $line, WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        foreach ($line->getOrderReferences() as $orderRef) {
            if ($orderRef->getOriginatingON() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_indicated"
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
                        ), $workDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $orderRef->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $workDocument->getDocumentStatus()->getWorkStatus();
                if ($docStatus !== WorkStatus::A) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_indicated"
                        ), $workDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDER_DATE);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($workDocument->getWorkDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $workDocument->getDocumentNumber(), $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line         $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function productCode(Line $line, WorkDocument $workDocument): void
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
                    $workDocument->getDocumentNumber(), $line->getLineNumber(),
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line         $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($line->issetTax() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber()
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
                    $workDocument->getDocumentNumber()
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
                    $workDocument->getDocumentNumber()
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
            !$lineTax->getTaxPercentage()?->equals("0.0") &&
            ($line->getTaxExemptionCode() !== null || $line->getTaxExemptionReason() !== null)
        ) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_for_tax_zero"),
                $workDocument->getDocumentNumber()
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

            if ($taxEntry->getTaxType() !== $lineTax->getTaxType() ||
                ($taxEntry->getTaxAmount() !== $lineTax->getTaxAmount() &&
                    $taxEntry->getTaxPercentage() !== $lineTax->getTaxPercentage()) ||
                $taxEntry->getTaxCountryRegion() !== $lineTax->getTaxCountryRegion()) {
                continue;
            }

            if ($taxEntry->getTaxExpirationDate() === null) {// is valid
                return;
            }
            if ($taxEntry->getTaxExpirationDate()->isLater($workDocument->getWorkDate())) {// is valid
                return;
            }
        }

        $this->isValid = false; // No table tax entry
        $msg           = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $workDocument->getDocumentNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function totals(WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if ($workDocument->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $workDocument->getDocumentTotals();
        $gross  = new Decimal($totals->getGrossTotal());
        $net    = new Decimal($totals->getNetTotal());
        $tax    = new Decimal($totals->getTaxPayable());

        if ($gross->equals($net->add($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $workDocument->getDocumentNumber()
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
                $workDocument->getDocumentNumber(),
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
                $this->netTotal,
                $workDocument->getDocumentNumber(),
                $net->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NET_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($tax->sub($this->taxPayable)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_tax_payable_not_equal_calc_tax_payable"),
                $this->taxPayable, $workDocument->getDocumentNumber(),
                $tax->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAX_PAYABLE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if (null === $currency = $workDocument->getDocumentTotals()->getCurrency(false)) {
            AAuditFile::$logger?->info(
                \sprintf(
                    "WorkDocument '%s' without currency node",
                    $workDocument->getDocumentNumber()
                )
            );
            return;
        }

        $currAmount    = new Decimal($currency->getCurrencyAmount());
        $rate          = new Decimal($currency->getExchangeRate());
        $grossExchange = $currAmount->mul($rate);
        $workDocument->getDocTotalCalc()?->setGrossTotalFromCurrency($grossExchange);
        $calcExchange = $gross->sub($grossExchange)->abs();

        if ($calcExchange > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $workDocument->getDocumentNumber()
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
     * Test if the signature is valid or not
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    protected function sign(WorkDocument $workDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($workDocument->issetHash() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_HASH);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($this->getSignValidation() === false) {
            AAuditFile::$logger?->debug("Skip sing test as ValidationConfig");
            return;
        }

        if ($workDocument->getDocumentStatus()->getSourceBilling() === SourceBilling::I) {
            $validate = true;
        } else {
            $validate = $this->sign->verifySignature(
                $workDocument->getHash(), $workDocument->getWorkDate(),
                $workDocument->getSystemEntryDate(),
                $workDocument->getDocumentNumber(),
                $workDocument->getDocumentTotals()->getGrossTotal(),
                $this->lastHash
            );
        }

        if ($validate === false && $this->lastHash === "") {

            list(, , $no) = \explode(
                " ", \str_replace("/", " ", $workDocument->getDocumentNumber())
            );

            if ($no !== "1") {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serial"),
                    $workDocument->getDocumentNumber()
                );
                AAuditFile::$logger?->info($msg);
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $workDocument->addWarning($msg);
                $validate = true;
            }
        }

        if ($validate === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("signature_not_valid"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_HASH);
            AAuditFile::$logger?->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $workDocument->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate the WorkDocument date nad SystemEntryDate
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function workDocumentDateAndSystemEntryDate(WorkDocument $workDocument): void
    {
        $docDate           = $workDocument->getWorkDate();
        $systemDate        = $workDocument->getSystemEntryDate();
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
                        $workDocument->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $workDocument->addError(
                        $msg, WorkDocument::N_SYSTEM_ENTRY_DATE
                    );
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_not_checked_start_end_header_date"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_WORK_DATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_earlier_previous_doc"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_WORK_DATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_system_entry_date_earlier_previous_doc"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_SYSTEM_ENTRY_DATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate if exists work document types out of date
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     *
     * @return void
     * @throws DateParseException
     * @throws \Rebelo\Date\DateException
     * @since 1.0.0
     */
    protected function outOfDateInvoiceTypes(WorkDocument $workDocument): void
    {
        if ($workDocument->issetWorkType() === false || $workDocument->issetWorkDate()
            === false) {
            return;
        }

        $type         = $workDocument->getWorkType();
        $lastDay      = RDate::parse(Pattern::SQL_DATE, "2017-06-30");
        $outDateTypes = [
            WorkType::DC
        ];

        if (\in_array($type, $outDateTypes) === false) {
            return;
        }

        if ($workDocument->getWorkDate()->isLater($lastDay)) {
            $msg = \sprintf(
                AuditFile::getI18n()->get("document_type_last_date_later"),
                $type->value, "2017-06-30", $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            AAuditFile::$logger?->error($msg);
            $this->isValid = false;
        }
    }
}
