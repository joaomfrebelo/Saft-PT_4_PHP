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

/**
 * Recalculated document values
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class DocTotalCalc
{
    /**
     * @var Decimal|null $taxPayable
     * @since 1.0.0
     */
    protected Decimal|null $taxPayable = null;

    /**
     * @var Decimal|null $netTotal
     * @since 1.0.0
     */
    protected Decimal|null $netTotal = null;

    /**
     * @var Decimal|null $grossTotal
     * @since 1.0.0
     */
    protected Decimal|null $grossTotal = null;

    /**
     * @var Decimal|null $grossTotal
     * @since 1.0.0
     */
    protected Decimal|null $grossTotalFromCurrency = null;

    /**
     * The calculated total line (credit/debit),
     * ini format [lineNumber=>totalLine]
     *
     * @var array<int ,Decimal>
     */
    protected array $lineTotal = [];

    /**
     * The Calculate values
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        AAuditFile::$logger?->debug(__METHOD__);
    }

    /**
     * get the calculated TaxPayable
     *
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getTaxPayable(): Decimal|null
    {
        return $this->taxPayable;
    }

    /**
     * Get the Calculated NetTotal
     *
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getNetTotal(): Decimal|null
    {
        return $this->netTotal;
    }

    /**
     * Get the GrossTotal calculated
     *
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getGrossTotal(): Decimal|null
    {
        return $this->grossTotal;
    }

    /**
     * Get the calculated GrossTotal from the currency
     *
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getGrossTotalFromCurrency(): Decimal|null
    {
        return $this->grossTotalFromCurrency;
    }

    /**
     * Set the calculated TaxPayable
     *
     * @param Decimal|null $taxPayable
     *
     * @return void
     * @since 1.0.0
     */
    public function setTaxPayable(Decimal|null $taxPayable): void
    {
        $this->taxPayable = $taxPayable;
    }

    /**
     * Set the calculated NetTotal
     *
     * @param Decimal|null $netTotal
     *
     * @return void
     * @since 1.0.0
     */
    public function setNetTotal(Decimal|null $netTotal): void
    {
        $this->netTotal = $netTotal;
    }

    /**
     * Set the calculated Gross total
     *
     * @param Decimal|null $grossTotal
     *
     * @return void
     * @since 1.0.0
     */
    public function setGrossTotal(Decimal|null $grossTotal): void
    {
        $this->grossTotal = $grossTotal;
    }

    /**
     * Set the calculated Gross total from Currency
     *
     * @param Decimal|null $grossFromCurrency
     *
     * @return void
     * @since 1.0.0
     */
    public function setGrossTotalFromCurrency(Decimal|null $grossFromCurrency): void
    {
        $this->grossTotalFromCurrency = $grossFromCurrency;
    }

    /**
     * To calculate total of each line, the array keys are the
     * line number
     *
     * @return array<int, Decimal>
     * @since 1.0.0
     */
    public function getLineTotal(): array
    {
        return $this->lineTotal;
    }

    /**
     * Add a total for a line
     *
     * @param int     $lineNumber
     * @param Decimal $value
     *
     * @return void
     * @since 1.0.0
     */
    public function addLineTotal(int $lineNumber, Decimal $value): void
    {
        $this->lineTotal[$lineNumber] = $value;
    }
}
