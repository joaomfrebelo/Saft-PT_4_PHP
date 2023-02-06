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

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Decimal\UDecimal;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Class AMovementOfGoodsBase
 *
 * @author João Rebelo
 */
abstract class AMovementOfGoodsBase extends TestCase
{
    /**
     * The SalesStockMovement to be possible to access to protected methods
     * @var \Rebelo\SaftPt\Validate\MovementOfGoods
     */
    protected MovementOfGoods $movementOfGoods;

    public function movementOfGoodsFactory(): void
    {
        $this->movementOfGoods = new class extends MovementOfGoods {

            public function __construct()
            {
                $private = \file_get_contents(PRIVATE_KEY_PATH);
                if ($private === false) {
                    TestCase::fail(
                        \sprintf(
                            "Private key '%s' not not loaded", PRIVATE_KEY_PATH
                        )
                    );
                }

                $public = \file_get_contents(PUBLIC_KEY_PATH);
                if ($public === false) {
                    TestCase::fail(
                        \sprintf(
                            "Public key '%s' not not loaded", PUBLIC_KEY_PATH
                        )
                    );
                }

                parent::__construct(
                    new AuditFile(),
                    new Sign(
                        $private, $public
                    )
                );
                $this->auditFile->getSourceDocuments()->getMovementOfGoods()
                    ->setMovOfGoodsTableTotalCalc(
                        new MovOfGoodsTableTotalCalc()
                    );
            }

            public function getAuditFile(): AuditFile
            {
                return $this->auditFile;
            }

            public function setAuditFile(AuditFile $auditFile): void
            {
                $this->auditFile = $auditFile;
            }

            public function setNumberOfMovementLines(int $num) : void
            {
                $this->numberOfMovementLines = $num;
            }

            public function setTotalQuantityIssued(UDecimal $qt) : void
            {
                $this->totalQuantityIssued = $qt;
            }

            /**
             * The total debit calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $docDebit
             * @return void
             */
            public function setDocDebit(UDecimal $docDebit): void
            {
                $this->docDebit = $docDebit;
            }

            /**
             * The total credit calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $docCredit
             * @return void
             */
            public function setDocCredit(UDecimal $docCredit): void
            {
                $this->docCredit = $docCredit;
            }

            /**
             * The net total calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $netTotal
             * @return void
             */
            public function setNetTotal(UDecimal $netTotal): void
            {
                $this->netTotal = $netTotal;
            }

            /**
             *  The total tax calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $taxPayable
             * @return void
             */
            public function setTaxPayable(UDecimal $taxPayable): void
            {
                $this->taxPayable = $taxPayable;
            }

            /**
             * The Gross total calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $grossTotal
             * @return void
             */
            public function setGrossTotal(UDecimal $grossTotal): void
            {
                $this->grossTotal = $grossTotal;
            }

            /**
             * The last hash of document that has been the signature validated
             * in the same document serie
             * @param string $hash
             * @since 1.0.0
             */
            public function setLastHash(string $hash): void
            {
                $this->lastHash = $hash;
            }

            public function setLastDocDate(RDate $date): void
            {
                $this->lastDocDate = $date;
            }

            public function setLastSystemEntryDate(RDate $date): void
            {
                $this->lastSystemEntryDate = $date;
            }

            public function isValid(): bool
            {
                return $this->isValid;
            }

            public function numberOfLinesAndTotalQuantity(): void
            {
                parent::numberOfLinesAndTotalQuantity();
            }

            public function documentStatus(StockMovement $stockMovDocument): void
            {
                parent::documentStatus($stockMovDocument);
            }

            public function customerIdOrSupplierId(StockMovement $stockMovDocument): void
            {
                parent::customerIdOrSupplierId($stockMovDocument);
            }

            public function lines(StockMovement $stockMovDocument): void
            {
                parent::lines($stockMovDocument);
            }

            public function productCode(Line $line, StockMovement $stockMovDocument): void
            {
                parent::productCode($line, $stockMovDocument);
            }

            public function tax(Line $line, StockMovement $stockMovDocument): void
            {
                parent::tax($line, $stockMovDocument);
            }

            public function totals(StockMovement $stockMovDocument): void
            {
                parent::totals($stockMovDocument);
            }

            public function sign(StockMovement $stockMovDocument): void
            {
                parent::sign($stockMovDocument);
            }

            public function stockMovementDateAndSystemEntryDate(StockMovement $stockMovDocument): void
            {
                parent::stockMovementDateAndSystemEntryDate($stockMovDocument);
            }

            public function stockMovement(StockMovement $stockMovDocument): void
            {
                parent::stockMovement($stockMovDocument);
            }

            public function shipement(StockMovement $stockMov) : void
            {
                parent::shipement($stockMov);
            }

        };
    }
}
