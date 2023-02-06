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

use Rebelo\Decimal\DecimalException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Generic and commune validations
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class OtherValidations extends ADocuments
{

    /**
     * Generic and commune validations
     * @param AuditFile $auditFile The AuditFile to be validated
     * @throws DecimalException
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        parent::__construct($auditFile);
    }

    /**
     * Validate
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $progressBar = null;
        try {

            $invoices = $this->auditFile->getSourceDocuments()?->getSalesInvoices(false) === null ? [] :
                $this->auditFile->getSourceDocuments()->getSalesInvoices(false)->getInvoice();

            $stockMovements = $this->auditFile->getSourceDocuments()?->getMovementOfGoods(false) === null ? [] :
                $this->auditFile->getSourceDocuments()->getMovementOfGoods(false)->getStockMovement();

            $workDocs = $this->auditFile->getSourceDocuments()?->getWorkingDocuments(false) === null ? [] :
                $this->auditFile->getSourceDocuments()->getWorkingDocuments(false)->getWorkDocument();

            $payments = $this->auditFile->getSourceDocuments()?->getPayments(false) === null ? [] :
                $this->auditFile->getSourceDocuments()->getPayments(false)->getPayment();

            $nDoc = \count($invoices);
            $nDoc += \count($stockMovements);
            $nDoc += \count($workDocs);
            $nDoc += \count($payments);

            if ($this->getStyle() !== null) {
                $section     = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section->writeln("");
                $section->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("other_validation_n_doc"),
                        $nDoc
                    )
                );
                $progressBar?->start($nDoc);
            }

            $type = [];
            $this->checkInvoicesType($invoices, $type, $progressBar);
            $this->checkStockMovementType($stockMovements, $type, $progressBar);
            $this->checkWorkDocumentType($workDocs, $type, $progressBar);
            $this->checkPaymentType($payments, $type, $progressBar);

            $progressBar?->finish();

        } catch (\Exception|\Error $e) {
            $this->isValid = false;

            $progressBar?->finish();

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
     * Verify the single invoice internal code per all document type
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[] $invoices
     * @param array<string, string> $type The accumulated documents types and internal codes
     * @param \Symfony\Component\Console\Helper\ProgressBar|null $progressBar
     * @return void
     * @since 1.0.0
     */
    protected function checkInvoicesType(
        array       &$invoices,
        array       &$type,
        ProgressBar $progressBar = null
    ): void
    {
        foreach ($invoices as $inv) {
            $progressBar?->advance();

            list($code,) = \explode(" ", $inv->getInvoiceNo());

            if (\array_key_exists($code, $type)) {
                if ($type[$code] !== $inv->getInvoiceType()->get()) {
                    $this->isValid = false;

                    $msg = \sprintf(
                        AuditFile::getI18n()->get("doc_code_with_more_one_type"),
                        $code, $type[$code], $inv->getInvoiceType()->get()
                    );

                    $this->auditFile->getErrorRegistor()
                        ->addValidationErrors($msg);

                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            } else {
                $type[$code] = $inv->getInvoiceType()->get();
            }
        }
    }

    /**
     * Verify the single StockMovement internal code per all document type
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement[] $stockMovements
     * @param array<string, string> $type The accumulated documents types and internal codes
     * @param \Symfony\Component\Console\Helper\ProgressBar|null $progressBar
     * @return void
     * @since 1.0.0
     */
    protected function checkStockMovementType(
        array       &$stockMovements,
        array       &$type,
        ProgressBar $progressBar = null): void
    {
        foreach ($stockMovements as $stockMov) {
            $progressBar?->advance();

            list($code,) = \explode(" ", $stockMov->getDocumentNumber());

            if (\array_key_exists($code, $type)) {
                if ($type[$code] !== $stockMov->getMovementType()->get()) {
                    $this->isValid = false;

                    $msg = \sprintf(
                        AuditFile::getI18n()->get("doc_code_with_more_one_type"),
                        $code, $type[$code], $stockMov->getMovementType()->get()
                    );

                    $this->auditFile->getErrorRegistor()
                        ->addValidationErrors($msg);

                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            } else {
                $type[$code] = $stockMov->getMovementType()->get();
            }
        }
    }

    /**
     * Verify the single WorkDocument internal code per all document type
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[] $workDocs
     * @param array<string, string> $type The accumulated documents types and internal codes
     * @param \Symfony\Component\Console\Helper\ProgressBar|null $progressBar
     * @return void
     * @since 1.0.0
     */
    protected function checkWorkDocumentType(
        array       &$workDocs,
        array       &$type,
        ProgressBar $progressBar = null
    ): void
    {
        foreach ($workDocs as $work) {
            /* @var $work \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
            $progressBar?->advance();

            list($code,) = \explode(" ", $work->getDocumentNumber());

            if (\array_key_exists($code, $type)) {
                if ($type[$code] !== $work->getWorkType()->get()) {
                    $this->isValid = false;

                    $msg = \sprintf(
                        AuditFile::getI18n()->get("doc_code_with_more_one_type"),
                        $code, $type[$code], $work->getWorkType()->get()
                    );

                    $this->auditFile->getErrorRegistor()
                        ->addValidationErrors($msg);

                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            } else {
                $type[$code] = $work->getWorkType()->get();
            }
        }
    }

    /**
     * Verify the single Payment internal code per all document type
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment[] $payments
     * @param array<string, string> $type The accumulated documents types and internal codes
     * @param \Symfony\Component\Console\Helper\ProgressBar|null $progressBar
     * @return void
     * @since 1.0.0
     */
    protected function checkPaymentType(
        array       &$payments,
        array       &$type,
        ProgressBar $progressBar = null
    ): void
    {
        foreach ($payments as $pay) {
            $progressBar?->advance();

            list($code,) = \explode(" ", $pay->getPaymentRefNo());

            if (\array_key_exists($code, $type)) {
                if ($type[$code] !== $pay->getPaymentType()->get()) {
                    $this->isValid = false;

                    $msg = \sprintf(
                        AuditFile::getI18n()->get("doc_code_with_more_one_type"),
                        $code, $type[$code], $pay->getPaymentType()->get()
                    );

                    $this->auditFile->getErrorRegistor()
                        ->addValidationErrors($msg);

                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            } else {
                $type[$code] = $pay->getPaymentType()->get();
            }
        }
    }
}
