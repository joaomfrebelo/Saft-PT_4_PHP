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

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\Sign\Sign;
use Rebelo\Decimal\UDecimal;
use Rebelo\Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods as SaftMovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;

/**
 * Validate MovementOfGoods table.<br>
 * This class will validate the values of MovementOfGoods, the
 * signature hash and dates
 *
 * @author João Rebelo
 * @since 1.0.0
 *
 */
class MovementOfGoods extends ADocuments
{

    /**
     * The calculated number of movement lines
     * @var int
     */
    protected int $numberOfMovementLines = 0;

    /**
     * The calculated total quantity issued
     * @var \Rebelo\Decimal\UDecimal
     */
    protected UDecimal $totalQuantityIssued;

    /**
     * Validate MovementOfGoods table.<br>
     * This class will validate the values of MovementOfGoods, the
     * signature hash and dates
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile The AuditFile to be validated
     * @param \Rebelo\SaftPt\Sign\Sign $sign The sign class to be used to validate the hash, must have the public key defined
     * @throws \Rebelo\Decimal\DecimalException
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile, Sign $sign)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        parent::__construct($auditFile, $sign);
        $this->totalQuantityIssued = new UDecimal(
            0.0, ADocuments::CALC_PRECISION
        );
        $sourceDoc = $auditFile->getSourceDocuments(false);
        if ($sourceDoc !== null) {
            $movementOfGoods = $sourceDoc->getMovementOfGoods(false);
            $movementOfGoods?->setMovOfGoodsTableTotalCalc(
                new MovOfGoodsTableTotalCalc()
            );
        }
    }

    /**
     * Validate the StockMovement
     * @return bool
     * @since 1.0.0
     */
    public function validate(): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $progressBar = null;
        try {
            if(null === $movementOfGoods = $this->auditFile->getSourceDocuments()?->getMovementOfGoods(false)){
                \Logger::getLogger(\get_class($this))
                        ->debug(__METHOD__ . " no movement of goods documents to be validated");
                return $this->isValid;
            }

            $movementOfGoods->setMovOfGoodsTableTotalCalc(
                new MovOfGoodsTableTotalCalc()
            );

            $order = $movementOfGoods->getOrder();

            if ($this->getStyle() !== null) {
                $nDoc = \count($movementOfGoods->getStockMovement());
                /* @var $section \Symfony\Component\Console\Output\ConsoleSectionOutput */
                $section = null;
                $progressBar = $this->getStyle()->addProgressBar($section);
                $section->writeln("");
                $section->writeln(
                    \sprintf(
                        AuditFile::getI18n()->get("validating_n_doc_of"), $nDoc,
                        "StockMovement"
                    )
                );
                $progressBar?->start($nDoc);
            }

            foreach (\array_keys($order) as $type) {
                foreach (\array_keys($order[$type]) as $serie) {
                    foreach (\array_keys($order[$type][$serie]) as $no) {

                        $progressBar?->advance();

                        $stockMovDocument = $order[$type][$serie][$no];
                        list(, $no) = \explode("/", $stockMovDocument->getDocumentNumber());
                        if ((string) $type !== $this->lastType || (string) $serie !== $this->lastSerie) {
                            $this->lastHash = "";
                            $this->lastDocDate = null;
                            $this->lastSystemEntryDate = null;
                        } else {
                            $noExpected = $this->lastDocNumber + 1;
                            if (\intval($no) !== $noExpected) {
                                do {
                                    $msg = \sprintf(
                                        AuditFile::getI18n()->get("the_document_n_is_missing"),
                                        $type, $serie, $noExpected
                                    );
                                    \Logger::getLogger(\get_class($this))->debug($msg);
                                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                                    $this->isValid = false;
                                    $noExpected++;
                                } while ($no !== \strval($noExpected));
                            }
                        }

                        $this->lastDocNumber = (int) $no;
                        $stockMovDocument->setDocTotalcal(new DocTotalCalc());
                        $this->stockMovement($stockMovDocument);
                        $this->lastType = (string) $type;
                        $this->lastSerie = (string) $serie;
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
                        $msg, SaftMovementOfGoods::N_NUMBEROFMOVEMENTLINES
                    );
                    $this->isValid = false;
                }

                if ($movementOfGoods->getTotalQuantityIssued() !== 0.0) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "mv_goods_total_qt_should_be_zero"
                        ), $movementOfGoods->getTotalQuantityIssued()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $movementOfGoods->addError(
                        $msg, SaftMovementOfGoods::N_TOTALQUANTITYISSUED
                    );
                    $this->isValid = false;
                }
            }
        } catch (\Exception | \Error $e) {
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
     * Validate StockMovement
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @since 1.0.0
     */
    protected function stockMovement(StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try {
            $this->docCredit = new UDecimal(0.0, static::CALC_PRECISION);
            $this->docDebit = new UDecimal(0.0, static::CALC_PRECISION);
            $this->netTotal = new UDecimal(0.0, static::CALC_PRECISION);
            $this->taxPayable = new UDecimal(0.0, static::CALC_PRECISION);
            $this->grossTotal = new UDecimal(0.0, static::CALC_PRECISION);

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
                $stockMovDocument->addError($msg, StockMovement::N_MOVEMENTTYPE);
                \Logger::getLogger(\get_class($this))->info($msg);
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
                $stockMovDocument->addError($msg, StockMovement::N_MOVEMENTDATE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }

            if ($stockMovDocument->issetSystemEntryDate() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "document_systementrydate_not_defined"
                    ), $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError(
                    $msg, StockMovement::N_SYSTEMENTRYDATE
                );
                \Logger::getLogger(\get_class($this))->info($msg);
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
            $this->shipement($stockMovDocument);
        } catch (\Exception | \Error $e) {
            $this->auditFile->getErrorRegistor()
                    ->addExceptionErrors($e->getMessage());
            \Logger::getLogger(\get_class($this))
                    ->debug(
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
     * @return void
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    protected function numberOfLinesAndTotalQuantity(): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if(null === $movementOfGoods = $this->auditFile->getSourceDocuments()?->getMovementOfGoods()){
            return;
        }

        $testNlines = $this->numberOfMovementLines === $movementOfGoods->getNumberOfMovementLines();
        $testQt = $this->totalQuantityIssued->signedSubtract(
            $movementOfGoods->getTotalQuantityIssued()
        )->abs()->valueOf() <= $this->getDeltaTable();

        $this->auditFile->getSourceDocuments()?->getMovementOfGoods()
                ?->getMovOfGoodsTableTotalCalc()?->setNumberOfMovementLines(
                    $this->numberOfMovementLines
                );

        $this->auditFile->getSourceDocuments()?->getMovementOfGoods()
                ?->getMovOfGoodsTableTotalCalc()?->setTotalQuantityIssued(
                    $this->totalQuantityIssued->valueOf()
                );

        if ($testNlines === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "wrong_number_of_movement_lines"
                ), $movementOfGoods->getNumberOfMovementLines(),
                $this->numberOfMovementLines
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $movementOfGoods->addError(
                $msg, SaftMovementOfGoods::N_NUMBEROFMOVEMENTLINES
            );
            \Logger::getLogger(\get_class($this))->info($msg);
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
                $msg, SaftMovementOfGoods::N_TOTALQUANTITYISSUED
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate the Document Status
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
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
            $stockMovDocument->addError($msg, DocumentStatus::N_DOCUMENTSTATUS);
            \Logger::getLogger(\get_class($this))->info($msg);
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
                $msg, DocumentStatus::N_MOVEMENTSTATUSDATE
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($status->getMovementStatus()->isEqual(MovementStatus::A) &&
                $status->getReason() === null) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get(
                    "document_status_cancel_no_reason"
                ), $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, DocumentStatus::N_REASON);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * validate if the customerID or SupplierID of the StockMovement if is set and if exits in
     * the customer table
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @since 1.0.0
     */
    protected function customerIdOrSupplierId(StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($stockMovDocument->issetCustomerID() && $stockMovDocument->issetSupplierID()) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("customerID_and_supplierID_defined_in_document"),
                $stockMovDocument->getDocumentNumber()
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_CUSTOMERID);
            $this->isValid = false;
            return;
        }

        switch ($stockMovDocument->getMovementType()->get()) {
            case MovementType::GC:
            case MovementType::GR:
                if ($stockMovDocument->issetCustomerID() === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("customerID_not_defined_in_document"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_CUSTOMERID
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
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_SUPPLIERID
                    );
                    $this->isValid = false;
                    return;
                }
            default :
                if ($stockMovDocument->issetCustomerID() === false &&
                        $stockMovDocument->issetSupplierID() === false) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("customerID_SupplierID_not_defined_in_document"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_CUSTOMERID
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
                    $msg, StockMovement::N_CUSTOMERID
                );
                \Logger::getLogger(\get_class($this))->info($msg);
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
                    $msg, StockMovement::N_CUSTOMERID
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }
    }

    /**
     * validate each line of the StockMovement
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    protected function lines(StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (\count($stockMovDocument->getLine()) === 0) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_without_lines"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_DOCUMENTNUMBER);
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
                        $line->addError($msg, Line::N_LINENUMBER);
                        \Logger::getLogger(\get_class($this))->info($msg);
                        $this->isValid = false;
                        $lineNoError = true;
                    } elseif (\in_array($line->getLineNumber(), $lineNoStack)) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("document_line_duplicated"),
                            $stockMovDocument->getDocumentNumber()
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
                        AAuditFile::getI18n()->get("document_line_no_number"),
                        $stockMovDocument->getDocumentNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg, Line::N_LINENUMBER);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    $lineNoError = true;
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
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                continue;
            }

            if ($line->issetUnitPrice() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_line_no_unit_price"),
                    $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_UNITPRICE);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                continue;
            }

            $lineValue = new Decimal(0.0, static::CALC_PRECISION);
            $lineTaxCal = new UDecimal(0.0, static::CALC_PRECISION);

            if ($line->getCreditAmount() === null &&
                    $line->getDebitAmount() === null) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_no_debit_or_credit"),
                    $stockMovDocument->getDocumentNumber(),
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

            if (null !== $lineTax = $line->getTax(false)) {

                if ($lineTax->issetTaxPercentage()) {

                    $lineFactor = $lineTax->getTaxPercentage() / 100;

                    $lineTaxCal = new UDecimal(
                        $lineFactor * \abs($lineAmount), static::CALC_PRECISION
                    );
                } else {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("document_line_no_tax_defined"),
                        $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $line->addError($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                    continue;
                }
            }

            // validate unit price and quantuty
            $unitPrice = new UDecimal(
                $line->getUnitPrice(), static::CALC_PRECISION
            );

            $uniQt = $unitPrice->multiply(
                new UDecimal($line->getQuantity(), static::CALC_PRECISION)
            );

            $stockMovDocument->getDocTotalcal()?->addLineTotal(
                $line->getLineNumber(), $uniQt->valueOf()
            );

            if ($uniQt->signedSubtract($lineValue->abs())->abs()->valueOf() > $this->getDeltaLine()) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_line_value_not_quantity_price"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError(
                    $msg,
                    $line->getCreditAmount() === null ?
                                Line::N_DEBITAMOUNT : Line::N_CREDITAMOUNT
                );
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }

            $docStat = $stockMovDocument->getDocumentStatus()->getMovementStatus();

            if ($docStat->isNotEqual(MovementStatus::A)) {
                $this->totalQuantityIssued->plusThis($line->getQuantity());
            }


            if ($line->getCreditAmount() !== null) {
                $credit = new UDecimal(
                    $line->getCreditAmount(), static::CALC_PRECISION
                );
                $this->docCredit->plusThis($credit);

                $hasCredit = true;
            }

            if ($line->getDebitAmount() !== null) {
                $debit = new UDecimal(
                    $line->getDebitAmount(), static::CALC_PRECISION
                );
                $this->docDebit->plusThis($debit);
                $hasDebit = true;
            }

            if ($hasCredit && $hasDebit) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("document_has_credit_and_debit_lines"),
                    $stockMovDocument->getDocumentNumber()
                );
                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $stockMovDocument->addError($msg);
                \Logger::getLogger(\get_class($this))->info($msg);
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
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
                return;
            }
            $this->netTotal->plusThis($lineValue->abs());

            $this->taxPayable->plusThis($lineTaxCal);

            if (\count($line->getOrderReferences()) > 0) {
                $this->orderReferences($line, $stockMovDocument);
            }
        }

        $this->grossTotal = $this->netTotal->plus($this->taxPayable);
    }

    /**
     * Validate the Order References
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function orderReferences(Line $line, StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        foreach ($line->getOrderReferences() as $orderRef) {
            if ($orderRef->getOriginatingON() === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get(
                        "order_reference_document_not_incicated"
                    ), $stockMovDocument->getDocumentNumber(),
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
                        ), $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addWarning($msg);
                    $orderRef->addWarning($msg);
                    \Logger::getLogger(\get_class($this))->info($msg);
                }
            }

            if ($orderRef->getOrderDate() === null) {
                $docStatus = $stockMovDocument->getDocumentStatus()->getMovementStatus();
                if ($docStatus->isNotEqual(MovementStatus::A)) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get(
                            "order_reference_date_not_incicated"
                        ), $stockMovDocument->getDocumentNumber(),
                        $line->getLineNumber()
                    );
                    $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                    $orderRef->addError($msg, OrderReferences::N_ORDERDATE);
                    \Logger::getLogger(\get_class($this))->info($msg);
                    $this->isValid = false;
                }
            } elseif ($orderRef->getOrderDate()->isLater($stockMovDocument->getMovementDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()->get("order_reference_date_later"),
                    $stockMovDocument->getDocumentNumber(),
                    $line->getLineNumber()
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @since 1.0.0
     */
    protected function productCode(Line $line, StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        $master = $this->auditFile->getMasterFiles();

        if ($line->issetProductCode() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_line_product_code_not_defined"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_PRODUCTCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
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
            $line->addError($msg, Line::N_PRODUCTCODE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        // If productCode is set and exists
        $product = $master->getProduct()[
                \array_search($line->getProductCode(), $master->getAllProductCode())
        ];
        if ($product->issetProductType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("mov_of_goods_product_do_not_have_type"),
                $line->getLineNumber(), $stockMovDocument->getDocumentNumber(),
                $line->getProductCode()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        $type = $product->getProductType()->get();
        $warningTypes = [
            ProductType::S,
            ProductType::O
        ];
        if (\in_array($type, $warningTypes)) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("mov_of_goods_product_is_of_type"),
                $line->getLineNumber(), $stockMovDocument->getDocumentNumber(),
                $line->getProductCode(), $type
            );
            $this->auditFile->getErrorRegistor()->addWarning($msg);
            $line->addWarning($msg);
            \Logger::getLogger(\get_class($this))->warn($msg);
        }
    }

    /**
     * Validate the line Tax
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function tax(Line $line, StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        $lineTax = $line->getTax(false);

        if ($lineTax === null) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_be_defined"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAX);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->issetTaxType() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_must_have_type"),
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
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
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
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
                $stockMovDocument->getDocumentNumber(), $line->getLineNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXCOUNTRYREGION);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }


        if ($lineTax->issetTaxPercentage() === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_must_have_percentage"),
                $stockMovDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $lineTax->addError($msg, Tax::N_TAXPERCENTAGE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($lineTax->getTaxPercentage() === 0.0) {
            if ($line->getTaxExemptionCode() === null ||
                    $line->getTaxExemptionReason() === null) {

                $msg = \sprintf(
                    AAuditFile:: getI18n()->get("tax_zero_must_have_code_and_reason"),
                    $stockMovDocument->getDocumentNumber()
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
                    $stockMovDocument->getDocumentNumber()
                );

                $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
                $line->addError($msg, Line::N_TAXEXEMPTIONCODE);
                $line->addError($msg, Line::N_TAXEXEMPTIONREASON);
                \Logger::getLogger(\get_class($this))->info($msg);
                $this->isValid = false;
            }
        }


        if ($lineTax->getTaxCode()->get() !== TaxCode::ISE &&
                $lineTax->getTaxPercentage() !== 0.0 &&
                ($line->getTaxExemptionCode() !== null ||
                $line->getTaxExemptionReason() !== null)
        ) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("tax_iva_exception_code_or_reason_only_isent"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $line->addError($msg, Line::N_TAXEXEMPTIONCODE);
            $line->addError($msg, Line::N_TAXEXEMPTIONREASON);
            \Logger::getLogger(\get_class($this))->info($msg);
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

            if ($taxEntry->getTaxType()->isNotEqual($lineTax->getTaxType()) ||
                    $taxEntry->getTaxPercentage() !== $lineTax->getTaxPercentage() ||
                    $taxEntry->getTaxCountryRegion()->isNotEqual($lineTax->getTaxCountryRegion())) {
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
        $msg = \sprintf(
            AAuditFile::getI18n()->get("no_tax_entry_for_line_document"),
            $line->getLineNumber(), $stockMovDocument->getDocumentNumber()
        );
        $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        \Logger::getLogger(\get_class($this))->info($msg);
        $line->addError($msg);
        $this->isValid = false;
    }

    /**
     * Validate the document total, only can be invoked after
     * validate lines (Because total controls are get from that validation)
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    protected function totals(StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($stockMovDocument->issetDocumentTotals() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_document_totals"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;

            return;
        }

        $totals = $stockMovDocument->getDocumentTotals();
        $gross = new UDecimal($totals->getGrossTotal(), 2);
        $net = new UDecimal($totals->getNetTotal(), static::CALC_PRECISION);
        $tax = new UDecimal($totals->getTaxPayable(), static::CALC_PRECISION);

        if ($gross->equals($net->plus($tax)) === false) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_tax_plus_net"),
                $stockMovDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($gross->signedSubtract($this->grossTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_gross_not_equal_calc_gross"),
                $this->grossTotal, $stockMovDocument->getDocumentNumber(), $gross->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_GROSSTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($net->signedSubtract($this->netTotal)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_nettotal_not_equal_calc_nettotal"),
                $this->netTotal, $stockMovDocument->getDocumentNumber(), $net->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_NETTOTAL);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($tax->signedSubtract($this->taxPayable)->abs()->valueOf() > $this->deltaTotalDoc) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_taxpayable_not_equal_calc_taxpayable"),
                $this->taxPayable, $stockMovDocument->getDocumentNumber(), $tax->valueOf()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError($msg, DocumentTotals::N_TAXPAYABLE);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }

        if ($stockMovDocument->getDocumentTotals()->getCurrency(false) === null) {
            \Logger::getLogger(\get_class($this))->info(
                \sprintf(
                    "StockMovement '%s' without currency node",
                    $stockMovDocument->getDocumentNumber()
                )
            );
            return;
        }

        if(null === $currency = $stockMovDocument->getDocumentTotals()->getCurrency()){
            return;
        }

        $currAmou = new UDecimal(
            $currency->getCurrencyAmount(), static::CALC_PRECISION
        );
        $rate = new UDecimal(
            $currency->getExchangeRate(), static::CALC_PRECISION
        );
        $grossExchange = $currAmou->multiply($rate);
        $stockMovDocument->getDocTotalcal()?->setGrossTotalFromCurrency($grossExchange->valueOf());
        $calcCambio = $gross->signedSubtract($grossExchange, 2)->abs()->valueOf();

        if ($calcCambio > $this->deltaCurrency) {

            $msg = \sprintf(
                AAuditFile::getI18n()->get("document_currency_rate"),
                $stockMovDocument->getDocumentNumber()
            );

            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $totals->addError(
                $msg,
                Currency::N_EXCHANGERATE
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Test if the signature is valide or not
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    protected function sign(StockMovement $stockMovDocument): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($stockMovDocument->issetHash() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("does_not_have_hash"),
                $stockMovDocument->getDocumentNumber()
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $stockMovDocument->addError($msg, StockMovement::N_HASH);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
            return;
        }

        if ($this->getSignValidation() === false) {
            \Logger::getLogger(\get_class($this))->debug("Skiping test sign as ValidationConfig");
            return;
        }

        if ($stockMovDocument->getDocumentStatus()->getSourceBilling()->isEqual(SourceBilling::I)) {
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

            list(,, $no) = \explode(
                " ",
                \str_replace("/", " ", $stockMovDocument->getDocumentNumber())
            );

            if ($no !== "1") {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("is_valid_only_if_is_not_first_of_serie"),
                    $stockMovDocument->getDocumentNumber()
                );
                \Logger::getLogger(\get_class($this))->info($msg);
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
            \Logger::getLogger(\get_class($this))->debug($msg);
            $this->isValid = false;
        }

        $this->lastHash = $stockMovDocument->getHash();
        if ($validate === false) {
            $this->isValid = false;
        }
    }

    /**
     * Verify the Start and en time of movement
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function movementStartAndEndTime(StockMovement $stockMovDocument): void
    {
        if ($stockMovDocument->issetMovementStartTime() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stockmov_must_have_mov_start_time"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENTSTARTTIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        $startTime = $stockMovDocument->getMovementStartTime();
        if ($startTime->isEarlier($stockMovDocument->getMovementDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stockmov_mov_start_time_earlier_doc_date"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENTSTARTTIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        if ($startTime->isEarlier($stockMovDocument->getSystemEntryDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stockmov_mov_start_time_earlier_systementrydate"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENTSTARTTIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }

        $endTime = $stockMovDocument->getMovementEndTime();
        if ($endTime === null) {
            return;
        }

        if ($endTime->isEarlier($stockMovDocument->getMovementStartTime())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("stockmov_end_time_earlier_start_time"),
                $stockMovDocument->getDocumentNumber()
            );
            $stockMovDocument->addError(
                $msg, StockMovement::N_MOVEMENTENDTIME
            );
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
        }
    }

    /**
     * Validate the StockMovement date nad SystemEntrydate
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovDocument
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function stockMovementDateAndSystemEntryDate(StockMovement $stockMovDocument): void
    {
        $docDate = $stockMovDocument->getMovementDate();
        $systemDate = $stockMovDocument->getSystemEntryDate();
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
                        $stockMovDocument->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMovDocument->addError(
                        $msg, StockMovement::N_SYSTEMENTRYDATE
                    );
                }
                $headerDateChecked = true;
            }
        }

        if ($headerDateChecked === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_date_not_cheked_start_end_header_date"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_MOVEMENTDATE);
        }

        if ($this->lastDocDate !== null &&
                $this->lastDocDate->isLater($docDate)) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_date_eaarlier_previous_doc"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_MOVEMENTDATE);
        }

        if ($this->lastSystemEntryDate !== null &&
                $this->lastSystemEntryDate->isLater($systemDate)) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("doc_systementrydate_earlier_previous_doc"),
                $stockMovDocument->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMovDocument->addError($msg, StockMovement::N_SYSTEMENTRYDATE);
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

    /**
     * Validate shipement data
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function shipement(StockMovement $stockMov): void
    {
        $shipFrom = $stockMov->getShipFrom(false);
        $shipTo = $stockMov->getShipTo(false);
        $movEndTime = $stockMov->getMovementEndTime();
        $msgStack = [];

        if ($shipFrom === null &&
                $stockMov->getDocumentStatus()->getMovementStatus()->isEqual(MovementStatus::R)) {
            return;
        }

        if ($shipFrom === null) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("shipfrom_not_defined_in_stock_mov"),
                $stockMov->getDocumentNumber()
            );

            \Logger::getLogger(\get_class($this))->info($msg);
            $stockMov->addError($msg, StockMovement::N_SHIPFROM);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        if ($shipTo === null && $stockMov->getMovementType()->isNotEqual(MovementType::GT)) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("no_shipto_only_in_global_doc_and_must_be_GT"),
                $stockMov->getDocumentNumber(),
                $stockMov->getMovementType()->get()
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $stockMov->addError($msg, StockMovement::N_SHIPFROM);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        if ($shipTo !== null &&
                $stockMov->getDocumentStatus()->getMovementStatus()->isNotEqual(MovementStatus::R)) {
            if ($shipFrom->getDeliveryDate() !== null &&
                    $shipTo->getDeliveryDate() !== null) {
                if ($shipFrom->getDeliveryDate()->isLater($shipTo->getDeliveryDate())) {
                    $msg = \sprintf(
                        AAuditFile::getI18n()->get("shipfrom_delivery_date_later_shipto_delivery_date"),
                        $stockMov->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMov->addError($msg, StockMovement::N_SHIPFROM);
                }
            }
        }

        if ($stockMov->issetMovementStartTime() === false) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("document_to_be_stockMovement_must_heve_start_time"),
                $stockMov->getDocumentNumber()
            );
            \Logger::getLogger(\get_class($this))->info($msg);
            $stockMov->addError($msg, StockMovement::N_MOVEMENTSTARTTIME);
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            $this->isValid = false;
            return;
        }

        $movStartTime = $stockMov->getMovementStartTime();

        if ($movStartTime->isEarlier($stockMov->getMovementDate())) {
            $msg = \sprintf(
                AAuditFile::getI18n()->get("start_movement_can_not_be earliar_doc_date"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_MOVEMENTSTARTTIME);
        }

        if ($stockMov->getDocumentStatus()->getSourceBilling()->isEqual(SourceBilling::P)) {
            if ($stockMov->issetSystemEntryDate() &&
                    $movStartTime->isEarlier($stockMov->getSystemEntryDate())) {

                $msg = \sprintf(
                    AAuditFile::getI18n()
                                ->get("start_movement_can_not_be earliar_system_entry_date"),
                    $stockMov->getDocumentNumber()
                );

                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_MOVEMENTSTARTTIME);
            }
        }

        if ($movEndTime !== null && $movEndTime->isEarlier($movStartTime)) {
            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("end_movement_can_not_be earliar_start_movement"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_MOVEMENTENDTIME);
        }


        $shipFromAddr = $shipFrom->getAddress(false);
        if ($shipFromAddr === null ||
                ($shipFromAddr->getStreetName() === null ||
                $shipFromAddr->getStreetName() === "") && (
                $shipFromAddr->getAddressDetail() === null ||
                $shipFromAddr->getAddressDetail() === "")) {

            $msg = \sprintf(
                AAuditFile::getI18n()
                            ->get("document_to_be_stockMovement_must_have_shipfrom"),
                $stockMov->getDocumentNumber()
            );
            $msgStack[] = $msg;
            $stockMov->addError($msg, StockMovement::N_SHIPFROM);
        } else {

            if ($shipFromAddr->issetCity() === false || $shipFromAddr->getCity() === "") {

                $msg = \sprintf(
                    AAuditFile::getI18n()
                                ->get("shipement_address_from_must_heve_city"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIPFROM);
            }

            if ($shipFromAddr->issetCountry() === false) {

                $msg = \sprintf(
                    AAuditFile::getI18n()
                                ->get("shipement_address_from_must_heve_country"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIPFROM);
            }
        }

        if ($shipTo !== null) {
            if ($shipTo->getAddress(false) === null) {
                $msg = \sprintf(
                    AAuditFile::getI18n()
                                ->get("document_to_be_stockMovement_must_heve_shipto"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->addError($msg, StockMovement::N_SHIPTO);
            } else if(null !== $shipToAddr = $shipTo->getAddress(false)){

                if (($shipToAddr->getStreetName() === null ||
                        $shipToAddr->getStreetName() === "") &&
                        ($shipToAddr->getAddressDetail() === null ||
                        $shipToAddr->getAddressDetail() === "")) {

                    $msg = \sprintf(
                        AAuditFile::getI18n()
                                    ->get("document_to_be_stockMovement_must_heve_shipto"),
                        $stockMov->getDocumentNumber()
                    );
                    $msgStack[] = $msg;
                    $stockMov->addError($msg, StockMovement::N_SHIPTO);
                } else {

                    if ($shipToAddr->issetCity() === false || $shipToAddr->getCity() === "") {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("shipement_address_to_must_heve_city"),
                            $stockMov->getDocumentNumber()
                        );
                        $msgStack[] = $msg;
                        $stockMov->addError($msg, StockMovement::N_SHIPTO);
                    }

                    if ($shipToAddr->issetCountry() === false) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()
                                        ->get("shipement_address_to_must_heve_country"),
                            $stockMov->getDocumentNumber()
                        );
                        $msgStack[] = $msg;
                        $stockMov->addError($msg, StockMovement::N_SHIPTO);
                    }
                }
            }
        }

        if ($stockMov->getDocumentStatus()->getMovementStatus()->isEqual(MovementStatus::A)) {
            $cancelDate = clone $stockMov->getDocumentStatus()->getMovementStatusDate();
            $startMov = clone $stockMov->getMovementStartTime();

            $cancelDate->setSeconds(0);
            $startMov->setSeconds(0);

            if ($cancelDate->isLater($startMov)) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("stockmov_can_not_be_cancel_after_movement_start"),
                    $stockMov->getDocumentNumber()
                );
                $msgStack[] = $msg;
                $stockMov->getDocumentStatus()->addError(
                    $msg, DocumentStatus::N_MOVEMENTSTATUSDATE
                );
            }
        }

        foreach ($msgStack as $msg) {
            $this->auditFile->getErrorRegistor()->addValidationErrors($msg);
            \Logger::getLogger(\get_class($this))->info($msg);
            $this->isValid = false;
        }
    }

}
