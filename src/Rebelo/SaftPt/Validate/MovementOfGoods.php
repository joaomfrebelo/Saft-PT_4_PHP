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
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods as SaftMovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Validate MovementOfGoods table.<br>
 * This class will validate the values of MovementOfGoods, the
 * signature hash and dates
 *
 * @author João Rebelo
 * @since  1.0.0
 *
 */
class MovementOfGoods extends ADocuments
{

    /**
     * The calculated number of movement lines
     *
     * @var int
     */
    protected int $numberOfMovementLines = 0;

    /**
     * The calculated total quantity issued
     *
     * @var \Decimal\Decimal
     */
    protected Decimal $totalQuantityIssued;

    /**
     * Validate MovementOfGoods table.<br>
     * This class will validate the values of MovementOfGoods, the
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
        $this->totalQuantityIssued = new Decimal("0.00");
        $sourceDoc                 = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $movementOfGoods = $sourceDoc->getMovementOfGoods(false);
            $movementOfGoods?->setMovOfGoodsTableTotalCalc(
                new MovOfGoodsTableTotalCalc()
            );
        }
    }

    /**
     * Validate the StockMovement
     *
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        AAuditFile::$logger?->debug(__METHOD__);
        $progressBar = null;
        try {
            if (null === $movementOfGoods = $this->auditFile->getSourceDocuments()?->getMovementOfGoods(false)) {
                AAuditFile::$logger?->debug(__METHOD__ . " no movement of goods documents to be validated");
                return $this->isValid;
            }

            $movementOfGoods->setMovOfGoodsTableTotalCalc(
                new MovOfGoodsTableTotalCalc()
            );

            $order = $movementOfGoods->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc = \count($movementOfGoods->getStockMovement());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section     = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section?->writeln("");
                $section?->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "StockMovement"
                    )
                );
                $progressBar?->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serial) {
                    foreach (\array_keys($order[$type][$serial]) as $no) {

                        $progressBar?->advance();

                        $stockMovDocument = $order[$type][$serial][$no];
                        list(, $no) = \explode("/", $stockMovDocument->getDocumentNumber());
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
                                    $this->isValid = false;
                                    $noExpected++;
                                } while ($no !== \strval($noExpected));
                            }
                        }

                        $this->lastDocNumber = (int)$no;
                        $stockMovDocument->setDocTotalCalc(new DocTotalCalc());
                        $this->stockMovement($stockMovDocument);
                        $this->lastType   = (string)$type;
                        $this->lastSerial = (string)$serial;
                    }
                }
            }

            $progressBar?->finish();

            $this->numberOfLinesAndTotalQuantity();

            if ($movementOfGoods->getMovOfGoodsTableTotalCalc()?->getNumberOfMovementLines() === 0) {

                if ($movementOfGoods->getNumberOfMovementLines() !== 0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("mv_goods_num_lines_should_be_zero"),
                        $movementOfGoods->getNumberOfMovementLines()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $movementOfGoods->addError(
                        $msg, SaftMovementOfGoods::N_NUMBER_OF_MOVEMENT_LINES
                    );
                    $this->isValid = false;
                }

                if (!$movementOfGoods->getTotalQuantityIssued()->equals(0.0)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "mv_goods_total_qt_should_be_zero"
                        ), $movementOfGoods->getTotalQuantityIssued()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $movementOfGoods->addError(
                        $msg, SaftMovementOfGoods::N_TOTAL_QUANTITY_ISSUED
                    );
                    $this->isValid = false;
                }
            }
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
     * Validate StockMovement
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function stockMovement(StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        try {
            $this->docCredit  = new Decimal("0.0");
            $this->docDebit   = new Decimal("0.0");
            $this->netTotal   = new Decimal("0.0");
            $this->taxPayable = new Decimal("0.0");
            $this->grossTotal = new Decimal("0.0");

            if ($stockMovDocument->issetDocumentNumber() === false) {
                $msg = AAuditFile::getI18n()->get("stock_mov_number_not_defined");
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError($msg);
                $this->isValid = false;
                return;
            }

            if ($stockMovDocument->issetMovementType() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "stock_mov_number_not_defined"
                    ), $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError($msg, StockMovement::N_MOVEMENT_TYPE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($stockMovDocument->issetMovementDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_date_not_defined"
                    ), $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError($msg, StockMovement::N_MOVEMENT_DATE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            if ($stockMovDocument->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_system_entry_date_not_defined"
                    ), $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError(
                    $msg, StockMovement::N_SYSTEM_ENTRY_DATE
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->sign($stockMovDocument);
            $this->stockMovementDateAndSystemEntryDate($stockMovDocument);
            $this->movementStartAndEndTime($stockMovDocument);
            $this->customerIdOrSupplierId($stockMovDocument);
            $this->documentStatus($stockMovDocument);
            $this->lines($stockMovDocument);
            $this->totals($stockMovDocument);
            $this->shipment($stockMovDocument);
        } catch (\Exception|\Error $e) {
            $this->auditFile->getErrorRegistor()
                            ->addExceptionErrors($e->getMessage());
            AAuditFile::$logger?->debug(
                \sprintf(
                    __METHOD__ . " validate error '%s'", $e->getMessage()
                )
            );
            $stockMovDocument->addError($e->getMessage());
            $this->isValid = false;
        }
    }

    /**
     * Validate if the NumberOfLines and TotalQuantity is equal to the number of StockMovements
     *
     * @return void
     * @since 1.0.0
     */
    protected function numberOfLinesAndTotalQuantity(): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if (null === $movementOfGoods = $this->auditFile->getSourceDocuments()?->getMovementOfGoods()) {
            return;
        }

        $testNLines = $this->numberOfMovementLines === $movementOfGoods->getNumberOfMovementLines();
        $testQt     = $this->totalQuantityIssued->sub(
            (string)$movementOfGoods->getTotalQuantityIssued()
        )->abs()->compareTo($this->getDeltaTable()) <= 0;

        $this->auditFile->getSourceDocuments()
                        ->getMovementOfGoods()
                        ->getMovOfGoodsTableTotalCalc()
                        ?->setNumberOfMovementLines($this->numberOfMovementLines);

        $this->auditFile->getSourceDocuments()
                        ->getMovementOfGoods()
                        ->getMovOfGoodsTableTotalCalc()
                        ?->setTotalQuantityIssued($this->totalQuantityIssued);

        if ($testNLines === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_movement_lines"
                ), $movementOfGoods->getNumberOfMovementLines(),
                $this->numberOfMovementLines
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $movementOfGoods->addError(
                $msg, SaftMovementOfGoods::N_NUMBER_OF_MOVEMENT_LINES
            );
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($testQt === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_total_qt_issued"
                ), $movementOfGoods->getTotalQuantityIssued(),
                $this->totalQuantityIssued
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $movementOfGoods->addError(
                $msg, SaftMovementOfGoods::N_TOTAL_QUANTITY_ISSUED
            );
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function documentStatus(StockMovement $stockMovDocument): void
    {
        if ($stockMovDocument->issetDocumentStatus() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_not_defined"
                ), $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, DocumentStatus::N_DOCUMENT_STATUS);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $status = $stockMovDocument->getDocumentStatus();

        if ($status->getMovementStatusDate()->isEarlier($stockMovDocument->getMovementDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_date_earlier"
                ), $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError(
                $msg, DocumentStatus::N_MOVEMENT_STATUS_DATE
            );
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getMovementStatus() === MovementStatus::A && $status->getReason() === null) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, DocumentStatus::N_REASON);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate if the customerID or SupplierID of the StockMovement if is set and if exits in
     * the customer table
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function customerIdOrSupplierId(StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($stockMovDocument->issetCustomerID() && $stockMovDocument->issetSupplierID()) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("customerID_and_supplierID_defined_in_document"),
                $stockMovDocument->getDocumentNumber()
            );
            AAuditFile::$logger?->info($msg);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_CUSTOMER_ID);
            $this->isValid = false;
            return;
        }

        switch ($stockMovDocument->getMovementType()) {
            case MovementType::GC:
            case MovementType::GR:
                if ($stockMovDocument->issetCustomerID() === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("customerID_not_defined_in_document"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    AAuditFile::$logger?->info($msg);
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_CUSTOMER_ID
                    );
                    $this->isValid = false;
                    return;
                }
                break;
            case MovementType::GD:
                if ($stockMovDocument->issetSupplierID() === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("supplierID_not_defined_in_document"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    AAuditFile::$logger?->info($msg);
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_SUPPLIER_ID
                    );
                    $this->isValid = false;
                    return;
                }
                break;
            default :
                if ($stockMovDocument->issetCustomerID() === false &&
                    $stockMovDocument->issetSupplierID() === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("customerID_SupplierID_not_defined_in_document"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    AAuditFile::$logger?->info($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_CUSTOMER_ID
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $this->isValid = false;
                    return;
                }
        }

        if ($stockMovDocument->issetSupplierID()) {
            $allSupplier = $this->auditFile->getMasterFiles()->getAllSupplierID();
            if (\in_array($stockMovDocument->getSupplierID(), $allSupplier) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("supplierID_not_exits"),
                    $stockMovDocument->getSupplierID(),
                    $stockMovDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError(
                    $msg, StockMovement::N_CUSTOMER_ID
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        } else {
            $allCustomer = $this->auditFile->getMasterFiles()->getAllCustomerID();
            if (\in_array($stockMovDocument->getCustomerID(), $allCustomer) === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("customerID_not_exits"),
                    $stockMovDocument->getCustomerID(),
                    $stockMovDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError(
                    $msg, StockMovement::N_CUSTOMER_ID
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * validate each line of the StockMovement
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function lines(StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if (\count($stockMovDocument->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_DOCUMENT_NUMBER);
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

        foreach ($stockMovDocument->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line */
            if ($lineNoError === false) {
                if ($line->issetLineNumber()) {
                    if ($this->getContinuesLines() && $line->getLineNumber() !== ++$n) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("document_line_no_continues"),
                            $stockMovDocument->getDocumentNumber()
                        );
                        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                        $line->addError($msg, Line::N_LINE_NUMBER);
                        AAuditFile::$logger?->info($msg);
                        $this->isValid = false;
                        $lineNoError   = true;
                    } elseif (\in_array($line->getLineNumber(), $lineNoStack)) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("document_line_duplicated"),
                            $stockMovDocument->getDocumentNumber()
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
                        AAuditFile::getI18n()->get("document_line_no_number"),
                        $stockMovDocument->getDocumentNumber()
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
                    AAuditFile::getI18n()->get("document_line_no_quantity"),
                    $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_QUANTITY);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            if ($line->issetUnitPrice() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_line_no_unit_price"),
                    $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_UNIT_PRICE);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                continue;
            }

            $lineValue  = new Decimal("0.0");
            $lineTaxCal = new Decimal("0.0");

            if ($line->getCreditAmount() === null &&
                $line->getDebitAmount() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_no_debit_or_credit"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
            $lineValue = $lineValue->add((string)$lineAmount);

            if (null !== $lineTax = $line->getTax(false)) {

                if ($lineTax->issetTaxPercentage()) {

                    $lineFactor = $lineTax->getTaxPercentage()->div("100.0");
                    $lineTaxCal = $lineFactor->mul($lineAmount->abs());

                } else {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("document_line_no_tax_defined"),
                        $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                    continue;
                }
            }

            // validate unit price and quantity
            $unitPrice = new Decimal((string)$line->getUnitPrice());

            $uniQt = $unitPrice->mul((string)$line->getQuantity());

            $stockMovDocument->getDocTotalCalc()?->addLineTotal(
                $line->getLineNumber(), $uniQt
            );

            if ($uniQt->sub($lineValue->abs())->abs()->compareTo((string)$this->getDeltaLine()) > 0) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_line_value_not_quantity_price"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                        Line::N_DEBIT_AMOUNT : Line::N_CREDIT_AMOUNT
                );
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }

            $docStat = $stockMovDocument->getDocumentStatus()->getMovementStatus();

            if ($docStat !== MovementStatus::A) {
                $this->totalQuantityIssued = $this->totalQuantityIssued->add((string)$line->getQuantity());
            }


            if ($line->getCreditAmount() !== null) {
                $credit          = new Decimal((string)$line->getCreditAmount());
                $this->docCredit = $this->docCredit->add($credit);
                $hasCredit       = true;
            }

            if ($line->getDebitAmount() !== null) {
                $debit          = new Decimal((string)$line->getDebitAmount());
                $this->docDebit = $this->docDebit->add($debit);
                $hasDebit       = true;
            }

            if ($hasCredit && $hasDebit) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_has_credit_and_debit_lines"),
                    $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError($msg);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->productCode($line, $stockMovDocument);

            $this->numberOfMovementLines++;

            if ($line->getTax(false) !== null) {
                $this->tax($line, $stockMovDocument);
            } elseif ($uniQt->equals(0.0) === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_must_be_defined"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
                return;
            }

            $this->netTotal   = $this->netTotal->add($lineValue->abs());
            $this->taxPayable = $this->taxPayable->add($lineTaxCal);

            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $stockMovDocument);
            }
        }

        $this->grossTotal = $this->netTotal->add($this->taxPayable);
    }

    /**
     * Validate the Order References
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line          $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    public function orderReferences(Line $line, StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        foreach ($line->getOrderReferences() as $orderRef) {
            if ($orderRef->getOriginatingON() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_indicated"
                    ), $stockMovDocument->getDocumentNumber(),
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
                        ), $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $orderRef->addWarning($msg);
                    AAuditFile::$logger?->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $stockMovDocument->getDocumentStatus()->getMovementStatus();
                if ($docStatus !== MovementStatus::A) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_initiated"
                        ), $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDER_DATE);
                    AAuditFile::$logger?->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($stockMovDocument->getMovementDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line          $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function productCode(Line $line, StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        $master = $this->auditFile->getMasterFiles();

        if ($line->issetProductCode() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_line_product_code_not_defined"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCT_CODE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        // If productCode is set
        if (\in_array($line->getProductCode(), $master->getAllProductCode()) === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_line_product_code_not_exist"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber(),
                $line->getProductCode()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCT_CODE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        // If productCode is set and exists
        $product = $master->getProduct()[\array_search($line->getProductCode(), $master->getAllProductCode())];
        if ($product->issetProductType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("mov_of_goods_product_do_not_have_type"),
                $line->getLineNumber(), $stockMovDocument->getDocumentNumber(),
                $line->getProductCode()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        $type         = $product->getProductType();
        $warningTypes = [
            ProductType::S,
            ProductType::O
        ];

        if (\in_array($type, $warningTypes)) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("mov_of_goods_product_is_of_type"),
                $line->getLineNumber(),
                $stockMovDocument->getDocumentNumber(),
                $line->getProductCode(),
                $type->value
            );
            $this->auditFile->getErrorRegistor()->addWarning($msg);
            $line->addWarning($msg);
            AAuditFile::$logger?->warning($msg);
        }
    }

    /**
     * Validate the line Tax
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line          $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function tax(Line $line, StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        $lineTax = $line->getTax(false);

        if ($lineTax === null) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAX);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_type"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
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
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
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
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_COUNTRY_REGION);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }


        if ($lineTax->issetTaxPercentage() === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_must_have_percentage"),
                $stockMovDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAX_PERCENTAGE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->getTaxPercentage()->equals("0.0")) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $stockMovDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
                $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }

        if ($lineTax->getTaxCode()->value === (TaxCode::ISE)->value) {
            if ($line->getTaxExemptionCode() === null ||
                $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("tax_iva_code_ise_must_have_code_and_reason"),
                    $stockMovDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAX_EXEMPTION_CODE);
                $line->addError($msg, Line::N_TAX_EXEMPTION_REASON);
                AAuditFile::$logger?->info($msg);
                $this->isValid = false;
            }
        }


        if ($lineTax->getTaxCode()->value !== (TaxCode::ISE)->value &&
            !$lineTax->getTaxPercentage()->equals("0.0") &&
            ($line->getTaxExemptionCode() !== null ||
                $line->getTaxExemptionReason() !== null)
        ) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_for_tax_zero"),
                $stockMovDocument->getDocumentNumber()
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

            if ($taxEntry->getTaxPercentage() === null) continue;

            if ($taxEntry->getTaxType()->value !== $lineTax->getTaxType()->value ||
                $taxEntry->getTaxPercentage()->compareTo($lineTax->getTaxPercentage()) !== 0 ||
                $taxEntry->getTaxCountryRegion() !== $lineTax->getTaxCountryRegion()) {
                continue;
            }

            if ($taxEntry->getTaxExpirationDate() === null) {// is valid
                return;
            }
            if ($taxEntry->getTaxExpirationDate()->isLater($stockMovDocument->getMovementDate())) {// is valid
                return;
            }
        }

        $this->isValid = false; // No table tax entry
        $msg           = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $stockMovDocument->getDocumentNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function totals(StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);
        if ($stockMovDocument->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $stockMovDocument->getDocumentTotals();
        $gross  = $totals->getGrossTotal();
        $net    = $totals->getNetTotal();
        $tax    = $totals->getTaxPayable();

        if ($gross->equals($net->add($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $stockMovDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($gross->sub($this->grossTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal, $stockMovDocument->getDocumentNumber(), $gross
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSS_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($net->sub($this->netTotal)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_net_total_not_equal_calc_net_total"),
                $this->netTotal, $stockMovDocument->getDocumentNumber(), $net
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NET_TOTAL);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($tax->sub($this->taxPayable)->abs()->compareTo($this->deltaTotalDoc) > 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_tax_payable_not_equal_calc_tax_payable"),
                $this->taxPayable, $stockMovDocument->getDocumentNumber(), $tax->toFloat()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAX_PAYABLE);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }

        if ($stockMovDocument->getDocumentTotals()->getCurrency(false) === null) {
            AAuditFile::$logger?->info(
                \sprintf(
                    "StockMovement '%s' without currency node",
                    $stockMovDocument->getDocumentNumber()
                )
            );
            return;
        }

        if (null === $currency = $stockMovDocument->getDocumentTotals()->getCurrency()) {
            return;
        }

        $currAmount    = new Decimal($currency->getCurrencyAmount());
        $rate          = new Decimal($currency->getExchangeRate());
        $grossExchange = $currAmount->mul($rate);
        $stockMovDocument->getDocTotalCalc()?->setGrossTotalFromCurrency($grossExchange);
        $calcExchange = $gross->sub($grossExchange)->abs();

        if ($calcExchange > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $stockMovDocument->getDocumentNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    protected function sign(StockMovement $stockMovDocument): void
    {
        AAuditFile::$logger?->debug(__METHOD__);

        if ($stockMovDocument->issetHash() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_HASH);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
            return;
        }

        if ($this->getSignValidation() === false) {
            AAuditFile::$logger?->debug("Skipping test sign as ValidationConfig");
            return;
        }

        if ($stockMovDocument->getDocumentStatus()->getSourceBilling() === SourceBilling::I) {
            $validate = true;
        } else {
            $validate = $this->sign->verifySignature(
                $stockMovDocument->getHash(),
                $stockMovDocument->getMovementDate(),
                $stockMovDocument->getSystemEntryDate(),
                $stockMovDocument->getDocumentNumber(),
                $stockMovDocument->getDocumentTotals()->getGrossTotal(),
                $this->lastHash
            );
        }

        if ($validate === false && $this->lastHash === "") {

            list(, , $no) = \explode(
                " ",
                \str_replace("/", " ", $stockMovDocument->getDocumentNumber())
            );

            if ($no !== "1") {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serial"),
                    $stockMovDocument->getDocumentNumber()
                );
                AAuditFile::$logger?->info($msg);
                $this->auditFile->getErrorRegistor()->addWarning($msg);
                $stockMovDocument->addWarning($msg);
                $validate = true;
            }
        }

        if ($validate === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("signature_not_valid"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_HASH);
            AAuditFile::$logger?->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $stockMovDocument->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Verify the Start and en time of movement
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    public function movementStartAndEndTime(StockMovement $stockMovDocument): void
    {
        if ($stockMovDocument->issetMovementStartTime() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stock_mov_must_have_mov_start_time"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENT_START_TIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        $startTime = $stockMovDocument->getMovementStartTime();
        if ($startTime->isEarlier($stockMovDocument->getMovementDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stock_mov_mov_start_time_earlier_doc_date"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENT_START_TIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        if ($startTime->isEarlier($stockMovDocument->getSystemEntryDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stock_mov_mov_start_time_earlier_system_entry_date"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENT_START_TIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        $endTime = $stockMovDocument->getMovementEndTime();
        if ($endTime === null) {
            return;
        }

        if ($endTime->isEarlier($stockMovDocument->getMovementStartTime())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stock_mov_end_time_earlier_start_time"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENT_END_TIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }
    }

    /**
     * Validate the StockMovement date nad SystemEntryDate
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     *
     * @return void
     * @since 1.0.0
     */
    protected function stockMovementDateAndSystemEntryDate(StockMovement $stockMovDocument): void
    {
        $docDate           = $stockMovDocument->getMovementDate();
        $systemDate        = $stockMovDocument->getSystemEntryDate();
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
                        $stockMovDocument->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_SYSTEM_ENTRY_DATE
                    );
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_not_checked_start_end_header_date"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_MOVEMENT_DATE);
        }

        if ($this->lastDocDate !== null &&
            $this->lastDocDate->isLater($docDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_date_earlier_previous_doc"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_MOVEMENT_DATE);
        }

        if ($this->lastSystemEntryDate !== null &&
            $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("doc_system_entry_date_earlier_previous_doc"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_SYSTEM_ENTRY_DATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate shipment data
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @since 1.0.0
     */
    protected function shipment(StockMovement $stockMov): void
    {
        $shipFrom   = $stockMov->getShipFrom(false);
        $shipTo     = $stockMov->getShipTo(false);
        $movEndTime = $stockMov->getMovementEndTime();
        $msgStack   = [];

        if ($shipFrom === null &&
            $stockMov->getDocumentStatus()->getMovementStatus() === MovementStatus::R) {
            return;
        }

        if ($shipFrom === null) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("ship_from_not_defined_in_stock_mov"),
                $stockMov->getDocumentNumber()
            );

            AAuditFile::$logger?->info($msg);
            $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        if ($shipTo === null && $stockMov->getMovementType() !== MovementType::GT) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("no_ship_to_only_in_global_doc_and_must_be_GT"),
                $stockMov->getDocumentNumber(),
                $stockMov->getMovementType()->value
            );
            AAuditFile::$logger?->info($msg);
            $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        if ($shipTo !== null &&
            $stockMov->getDocumentStatus()->getMovementStatus() !== MovementStatus::R) {
            if ($shipFrom->getDeliveryDate() !== null &&
                $shipTo->getDeliveryDate() !== null) {
                if ($shipFrom->getDeliveryDate()->isLater($shipTo->getDeliveryDate())) {
                    $msg        = \sprintf(
                        AAuditFile::getI18n()->get("ship_from_delivery_date_later_ship_to_delivery_date"),
                        $stockMov->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
                }
            }
        }

        if ($stockMov->issetMovementStartTime() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                          ->get("document_to_be_stockMovement_must_have_start_time"),
                $stockMov->getDocumentNumber()
            );
            AAuditFile::$logger?->info($msg);
            $stockMov->addError($msg, StockMovement::N_MOVEMENT_START_TIME);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        $movStartTime = $stockMov->getMovementStartTime();

        if ($movStartTime->isEarlier($stockMov->getMovementDate())) {
            $msg        = \sprintf(
                AAuditFile::getI18n()->get("start_movement_can_not_be earlier_doc_date"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_MOVEMENT_START_TIME);
        }

        if ($stockMov->getDocumentStatus()->getSourceBilling() === SourceBilling::P) {
            if ($stockMov->issetSystemEntryDate() &&
                $movStartTime->isEarlier($stockMov->getSystemEntryDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()
                              ->get("start_movement_can_not_be earlier_system_entry_date"),
                    $stockMov->getDocumentNumber()
                );

                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_MOVEMENT_START_TIME);
            }
        }

        if ($movEndTime !== null && $movEndTime->isEarlier($movStartTime)) {
            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("end_movement_can_not_be earlier_start_movement"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_MOVEMENT_END_TIME);
        }


        $shipFromAddr = $shipFrom->getAddress(false);
        if ($shipFromAddr === null ||
            ($shipFromAddr->getStreetName() === null ||
                $shipFromAddr->getStreetName() === "") && (
                $shipFromAddr->getAddressDetail() === null ||
                $shipFromAddr->getAddressDetail() === "")) {

            $msg        = \sprintf(
                AAuditFile::getI18n()
                          ->get("document_to_be_stockMovement_must_have_ship_from"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
        } else {

            if ($shipFromAddr->issetCity() === false || $shipFromAddr->getCity() === "") {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("shipment_address_from_must_have_city"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
            }

            if ($shipFromAddr->issetCountry() === false) {

                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("shipment_address_from_must_have_country"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIP_FROM);
            }
        }

        if ($shipTo !== null) {
            if ($shipTo->getAddress(false) === null) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()
                              ->get("document_to_be_stockMovement_must_have_ship_to"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIP_TO);
                /** @phpstan-ignore-next-line */
            } else if (null !== $shipToAddr = $shipTo->getAddress(false)) {

                if (($shipToAddr->getStreetName() === null ||
                        $shipToAddr->getStreetName() === "") &&
                    ($shipToAddr->getAddressDetail() === null ||
                        $shipToAddr->getAddressDetail() === "")) {

                    $msg        = \sprintf(
                        AAuditFile::getI18n()
                                  ->get("document_to_be_stockMovement_must_have_ship_to"),
                        $stockMov->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMov->addError($msg, StockMovement::N_SHIP_TO);
                } else {

                    if ($shipToAddr->issetCity() === false || $shipToAddr->getCity() === "") {
                        $msg        = \sprintf(
                            AAuditFile::getI18n()->get("shipment_address_to_must_have_city"),
                            $stockMov->getDocumentNumber()
                        );
                        $msgStack[] = $msg;
                        $stockMov->addError($msg, StockMovement::N_SHIP_TO);
                    }

                    if ($shipToAddr->issetCountry() === false) {
                        $msg        = \sprintf(
                            AAuditFile::getI18n()
                                      ->get("shipment_address_to_must_have_country"),
                            $stockMov->getDocumentNumber()
                        );
                        $msgStack[] = $msg;
                        $stockMov->addError($msg, StockMovement::N_SHIP_TO);
                    }
                }
            }
        }

        if ($stockMov->getDocumentStatus()->getMovementStatus() === MovementStatus::A) {
            $cancelDate = clone $stockMov->getDocumentStatus()->getMovementStatusDate();
            $startMov   = clone $stockMov->getMovementStartTime();

            $cancelDate->setSeconds(0);
            $startMov->setSeconds(0);

            if ($cancelDate->isLater($startMov)) {
                $msg        = \sprintf(
                    AAuditFile::getI18n()->get("stock_mov_can_not_be_cancel_after_movement_start"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->getDocumentStatus()->addError(
                    $msg, DocumentStatus::N_MOVEMENT_STATUS_DATE
                );
            }
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            AAuditFile::$logger?->info($msg);
            $this->isValid = false;
        }
    }

}
