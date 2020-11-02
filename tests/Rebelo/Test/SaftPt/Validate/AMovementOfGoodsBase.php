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

namespace Rebelo\Test\SaftPt\Validate;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\MovementOfGoods;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\Decimal\UDecimal;
use Rebelo\Date\Date as RDate;

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
                    return;
                }

                $public = \file_get_contents(PUBLIC_KEY_PATH);
                if ($public === false) {
                    TestCase::fail(
                        \sprintf(
                            "Public key '%s' not not loaded", PUBLIC_KEY_PATH
                        )
                    );
                    return;
                }

                parent::__construct(
                    new \Rebelo\SaftPt\AuditFile\AuditFile(),
                    new \Rebelo\SaftPt\Sign\Sign(
                        $private, $public
                    )
                );
                $this->auditFile->getSourceDocuments()->getMovementOfGoods()
                    ->setMovOfGoodsTableTotalCalc(
                        new \Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc()
                    );
            }

            public function getAuditFile(): \Rebelo\SaftPt\AuditFile\AuditFile
            {
                return $this->auditFile;
            }

            public function setAuditFile(\Rebelo\SaftPt\AuditFile\AuditFile $auditFile): void
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
             * @var string
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

            public function documentStatus(StockMovement $stockMovement): void
            {
                parent::documentStatus($stockMovement);
            }

            public function customerIdOrSupplierId(StockMovement $stockMovement): void
            {
                parent::customerIdOrSupplierId($stockMovement);
            }

            public function lines(StockMovement $stockMovement): void
            {
                parent::lines($stockMovement);
            }

            public function producCode(Line $line, StockMovement $stockMovement): void
            {
                parent::producCode($line, $stockMovement);
            }

            public function tax(Line $line, StockMovement $stockMovement): void
            {
                parent::tax($line, $stockMovement);
            }

            public function totals(StockMovement $stockMovement): void
            {
                parent::totals($stockMovement);
            }

            public function sign(StockMovement $stockMovement): void
            {
                parent::sign($stockMovement);
            }

            public function stockMovementDateAndSystemEntryDate(StockMovement $stockMovement): void
            {
                parent::stockMovementDateAndSystemEntryDate($stockMovement);
            }

            public function stockMovement(StockMovement $stockMovement): void
            {
                parent::stockMovement($stockMovement);
            }
            
            public function shipement(StockMovement $stockMov) : void
            {
                parent::shipement($stockMov);
            }
            
        };
    }
}
