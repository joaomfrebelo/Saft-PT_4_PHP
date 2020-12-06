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
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments as SaftWorkingDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\References;
use Rebelo\Date\Date as RDate;

/**
 * Validate WorkingDocuments table.<br>
 * This class will validate the values of WorkingDocuments, the
 * signtuare hash and dates
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkingDocuments extends ADocuments
{

    /**
     * Validate WorkingDocuments table.<br>
     * This class will validate the values of WorkingDocuments, the
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
            $workingDocuments = $sourceDoc->getWorkingDocuments(false);
            if ($workingDocuments !== null) {
                $workingDocuments->setDocTableTotalCalc(
                    new \Rebelo\SaftPt\Validate\DocTableTotalCalc()
                );
            }
        }
    }

    /**
     * Validate the workingdocuments
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $progreBar = null;
        try {
            $workingDocuments = $this->auditFile->getSourceDocuments()
                ->getWorkingDocuments(false);

            if ($workingDocuments === null) {
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." no work documents to be vaidated");
                return $this->isValid;
            }

            $workingDocuments->setDocTableTotalCalc(new DocTableTotalCalc());

            $this->numberOfEntries();

            if (\count($workingDocuments->getWorkDocument()) === 0) {

                if ($workingDocuments->getTotalCredit() !== 0.0) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "workingdocuments_total_credit_should_be_zero"
                        ), $workingDocuments->getTotalCredit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $workingDocuments->addError(
                        $msg, SaftWorkingDocuments::N_TOTALCREDIT
                    );
                    $this->isValid = false;
                }

                if ($workingDocuments->getTotalDebit() !== 0.0) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "workingdocuments_total_debit_should_be_zero"
                        ), $workingDocuments->getTotalDebit()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $workingDocuments->addError(
                        $msg, SaftWorkingDocuments::N_TOTALDEBIT
                    );
                    $this->isValid = false;
                }

                return $this->isValid;
            }

            $order = $workingDocuments->getOrder();

             if ($this->getStyle() !== null) {
                $nDoc = \count($workingDocuments->getWorkDocument());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section = null;
                $progreBar  = $this->getStyle()->addProgressBar($section);
                $section->writeln("");
                $section->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "WorkDocument"
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
                        
                        /* @var $workDocument \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
                        $workDocument = $order[$type][$serie][$no];
                        if ((string)$type !== $this->lastType || (string)$serie !== $this->lastSerie) {
                            $this->lastHash            = "";
                            $this->lastDocDate         = null;
                            $this->lastSystemEntryDate = null;
                        }
                        $workDocument->setDocTotalcal(new DocTotalCalc());
                        $this->workDocument($workDocument);
                        $this->lastType = (string)$type;
                        $this->lastSerie = (string)$serie;
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
     * Validate WorkDocument
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function workDocument(WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try {
            $this->docCredit  = new UDecimal(0.0, static::CALC_PRECISION);
            $this->docDebit   = new UDecimal(0.0, static::CALC_PRECISION);
            $this->netTotal   = new UDecimal(0.0, static::CALC_PRECISION);
            $this->taxPayable = new UDecimal(0.0, static::CALC_PRECISION);
            $this->grossTotal = new UDecimal(0.0, static::CALC_PRECISION);

            if ($workDocument->issetDocumentNumber() === false) {
                $msg           = AAuditFile::getI18n()->get("workdoc_number_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetWorkType() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "workdoctype_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_WORKTYPE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetWorkDate() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_WORKDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($workDocument->issetSystemEntryDate() === false) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_systementrydate_not_defined"
                    ), $workDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError($msg, WorkDocument::N_SYSTEMENTRYDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
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
        } catch (\Exception | \Error $e) {
            $this->auditFile->getErrorRegistor()
                ->addExceptionErrors($e->getMessage());
            \Logger::getLogger(\get_class($this))
                ->debug(
                    \sprintf(
                        __METHOD__." validate error '%s'", $e->getMessage()
                    )
                );
            $workDocument->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfEntries is equal to the number of WorkDocuments
     * @return void
     * @since 1.0.0
     */
    protected function numberOfEntries(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $workingDocuments       = $this->auditFile->getSourceDocuments()->getWorkingDocuments();
        $calculatedNumOfEntries = \count($workingDocuments->getWorkDocument());
        $numberOfEntries        = $workingDocuments->getNumberOfEntries();
        $test                   = $numberOfEntries === $calculatedNumOfEntries;

        $this->auditFile->getSourceDocuments()->getWorkingDocuments()
            ->getDocTableTotalCalc()->setNumberOfEntries($calculatedNumOfEntries);

        if ($test === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_workdocuments"
                ), $numberOfEntries, $calculatedNumOfEntries
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError(
                $msg, SaftWorkingDocuments::N_NUMBEROFENTRIES
            );
            \Logger::getLogger(\get_class($this))->info($msg);
        }
        if ($test === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate WorkingDocuments TotalDebit
     * @return void
     * @since 1.0.0
     */
    protected function totalDebit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $workingDocuments = $this->auditFile->getSourceDocuments()
            ->getWorkingDocuments();

        $workingDocuments->getDocTableTotalCalc()
            ->setTotalDebit($this->debit->valueOf());

        $diff = $this->debit->signedSubtract(
            new Decimal(
                $workingDocuments->getTotalDebit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_debit_of_workingdocuments"
                ), $workingDocuments->getTotalDebit(), $this->debit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError($msg, SaftWorkingDocuments::N_TOTALDEBIT);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate WorkingDocuments TotalCredit
     * @return void
     * @since 1.0.0
     */
    protected function totalCredit(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $workingDocuments = $this->auditFile->getSourceDocuments()->getWorkingDocuments();

        $workingDocuments->getDocTableTotalCalc()->setTotalDebit($this->credit->valueOf());

        $diff = $this->credit->signedSubtract(
            new Decimal(
                $workingDocuments->getTotalCredit(), static::CALC_PRECISION
            )
        )->abs()->valueOf();

        if ($diff > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_total_credit_of_workingdocuments"
                ), $workingDocuments->getTotalCredit(), $this->credit->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workingDocuments->addError(
                $msg,
                SaftWorkingDocuments::N_TOTALCREDIT
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(WorkDocument $workDocument): void
    {
        if ($workDocument->issetDocumentStatus() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_DOCUMENTSTATUS);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $workDocument->getDocumentStatus();

        if ($status->getWorkStatusDate()->isEarlier($workDocument->getWorkDate())) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_WORKSTATUSDATE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getWorkStatus()->isEqual(WorkStatus::A) &&
            $status->getReason() === null) {

            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, DocumentStatus::N_REASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }
    }

    /**
     * validate if the customerID of the WorkDocument if is set and if exits in
     * the customer table
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function customerId(WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
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
                $workDocument->addError($msg, WorkDocument::N_CUSTOMERID);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        } else {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "customerID_not_defined_in_document"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_CUSTOMERID);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate each line of the WorkDocument
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function lines(WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($workDocument->getLine()) === 0) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_DOCUMENTNUMBER);
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

        foreach ($workDocument->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg           = \sprintf(
                            AAuditFile::getI18n()->get(
                                "document_line_no_continues"
                            ), $workDocument->getDocumentNumber()
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
                            ), $workDocument->getDocumentNumber()
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
                        ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber()
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
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
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

            $workDocument->getDocTotalcal()->addLineTotal(
                $line->getLineNumber(), $uniQt->valueOf()
            );

            if ($line->getTaxBase() !== null &&
                ($unitPrice->valueOf() > 0.0 || $lineValue->valueOf() !== 0.0)
            ) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_line_have_tax_base_with_unit_price_credit_debit"
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $workDocument->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                        Line::N_DEBITAMOUNT : Line::N_CREDITAMOUNT
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }

            $notForTotal = array(
                WorkStatus::A
            );

            $docStat = $workDocument->getDocumentStatus()->getWorkStatus()->get();

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

            $this->producCode($line, $workDocument);

            if ($line->issetTax()) {
                $this->tax($line, $workDocument);
            } elseif ($line->getTaxBase() === null) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get("tax_must_be_defined"),
                    $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $this->refernces($line, $workDocument);
            }
            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $workDocument);
            }
        }

        $this->grossTotal = $this->netTotal->plus($this->taxPayable);

        $workDocument->getDocTotalcal()->setGrossTotal($this->grossTotal->valueOf());
        $workDocument->getDocTotalcal()->setNetTotal($this->netTotal->valueOf());
        $workDocument->getDocTotalcal()->setTaxPayable($this->taxPayable->valueOf());

        if ($hasCredit && $hasDebit && $this->allowDebitAndCredit === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_has_credit_and_debit_lines"
                ), $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate refernces of NC (Credit note)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    public function refernces(Line $line, WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($line->getReferences()) === 0) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_correcting_line_without_refernces"
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                        $workDocument->getDocumentNumber(),
                        $line->getLineNumber(), $reference->getReference()
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
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                ), $workDocument->getDocumentNumber(), $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    public function orderReferences(Line $line, WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        foreach ($line->getOrderReferences() as $orderRef) {
            /* @var $orderRef \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences */
            if ($orderRef->getOriginatingON() === null) {
                $msg           = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_incicated"
                    ), $workDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
                        ), $workDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $orderRef->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $workDocument->getDocumentStatus()->getWorkStatus();
                if ($docStatus->isNotEqual(WorkStatus::A)) {
                    $msg           = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_incicated"
                        ), $workDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDERDATE);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($workDocument->getWorkDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $workDocument->getDocumentNumber(), $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function producCode(Line $line, WorkDocument $workDocument): void
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
                    $workDocument->getDocumentNumber(), $line->getLineNumber(),
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCTCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the line Tax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($line->issetTax() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber(), $line->getLineNumber()
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
                $workDocument->getDocumentNumber()
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
                    $workDocument->getDocumentNumber()
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
                    $workDocument->getDocumentNumber()
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
                $workDocument->getDocumentNumber()
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
        \Logger::getLogger(\get_class($this))->info($msg);
        $line->addError($msg);
        $this->isValid = false;
    }

    /**
     * Validate the document total, only can be invoked after
     * validate lines (Because total controls are getted from that validation)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function totals(WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($workDocument->issetDocumentTotals() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $workDocument->getDocumentTotals();
        $gross  = new UDecimal($totals->getGrossTotal(), 2);
        $net    = new UDecimal($totals->getNetTotal(), static::CALC_PRECISION);
        $tax    = new UDecimal($totals->getTaxPayable(), static::CALC_PRECISION);

        if ($gross->equals($net->plus($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $workDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($gross->signedSubtract($this->grossTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSSTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($net->signedSubtract($this->netTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_nettotal_not_equal_calc_nettotal"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NETTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($tax->signedSubtract($this->taxPayable)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("document_taxpayable_not_equal_calc_taxpayable"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAXPAYABLE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($workDocument->getDocumentTotals()->getCurrency(false) === null) {
            \Logger::getLogger(\get_class($this))->info(
                \sprintf(
                    "WorkDocument '%s' without currency node",
                    $workDocument->getDocumentNumber()
                )
            );
            return;
        }

        $currency      = $workDocument->getDocumentTotals()->getCurrency();
        $currAmou      = new UDecimal(
            $currency->getCurrencyAmount(), static::CALC_PRECISION
        );
        $rate          = new UDecimal(
            $currency->getExchangeRate(), static::CALC_PRECISION
        );
        $grossExchange = $currAmou->multiply($rate);
        $workDocument->getDocTotalcal()->setGrossTotalFromCurrency($grossExchange->valueOf());
        $calcCambio    = $gross->signedSubtract($grossExchange, 2)->abs()->valueOf();

        if ($calcCambio > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $workDocument->getDocumentNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function sign(WorkDocument $workDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        
        if ($workDocument->issetHash() === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_HASH);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if($this->getSignValidation() === false){
            \Logger::getLogger(\get_class($this))->debug("Skip sing test as ValidationConfig");
            return;
        }
        
        if ($workDocument->getDocumentStatus()->getSourceBilling()->isEqual(SourceBilling::I)) {
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

            list(,, $no) = \explode(
                " ", \str_replace("/", " ", $workDocument->getDocumentNumber())
            );

            if ($no !== "1") {
                $msg      = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serie"),
                    $workDocument->getDocumentNumber()
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $workDocument->addWarning($msg);
                $validate = true;
            }
        }

        if ($validate === false) {
            $msg           = \sprintf(
                AAuditFile::getI18n()->get("signature_not_valid"),
                $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg, WorkDocument::N_HASH);
            \Logger::getLogger(\get_class($this))->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $workDocument->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Validate the WorkDocument date nad SystemEntrydate
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
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
                        $msg,
                        WorkDocument::N_SYSTEMENTRYDATE
                    );
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_date_not_cheked_start_end_header_date"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_WORKDATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_date_eaarlier_previous_doc"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_WORKDATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                    ->get("doc_systementrydate_earlier_previous_doc"),
                $workDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $workDocument->addError($msg, WorkDocument::N_SYSTEMENTRYDATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }
    

    /**
     * Validate if exists workdoc types out of date
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return void
     * @since 1.0.0
     */
    protected function outOfDateInvoiceTypes(WorkDocument $workDocument) : void
    {
        if ($workDocument->issetWorkType() === false || $workDocument->issetWorkDate() === false) {
            return;
        }

        $type     = $workDocument->getWorkType()->get();
        $lastDay  = RDate::parse(RDate::SQL_DATE, "2017-06-30");
        $outDateTypes = [
            WorkType::DC
        ];

        if (\in_array($type, $outDateTypes) === false) {
            return;
        }

        if ($workDocument->getWorkDate()->isLater($lastDay)) {
            $msg = \sprintf(
                AuditFile::getI18n()->get("document_type_last_date_later"),
                $type, "2017-06-30", $workDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $workDocument->addError($msg);
            \Logger::getLogger(\get_class($this))->error($msg);
            $this->isValid = false;
        }
    }
}