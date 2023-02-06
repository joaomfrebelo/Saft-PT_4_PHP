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

/**
 * Recalculated document values
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocTotalCalc
{
    /**
     * @var float|null $taxPayable
     * @since 1.0.0
     */
    protected ?float $taxPayable = null;

    /**
     * @var float|null $netTotal
     * @since 1.0.0
     */
    protected ?float $netTotal = null;

    /**
     * @var float|null $grossTotal
     * @since 1.0.0
     */
    protected ?float $grossTotal = null;

    /**
     * @var float|null $grossTotal
     * @since 1.0.0
     */
    protected ?float $grossTotalFromCurrency = null;

    /**
     * The calculated total line (credit/debit),
     * ini format [lineNumber=>totalLine]
     * @var float[]
     */
    protected array $lineTotal = array();

    /**
     * The Calculate values
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * get the calculated TaxPayable
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxPayable(): ?float
    {
        return $this->taxPayable;
    }

    /**
     * Get the Calculated NetTotal
     * @return float|null
     * @since 1.0.0
     */
    public function getNetTotal(): ?float
    {
        return $this->netTotal;
    }

    /**
     * Get the GrossTotal calculated
     * @return float|null
     * @since 1.0.0
     */
    public function getGrossTotal(): ?float
    {
        return $this->grossTotal;
    }

    /**
     * Get the calculated GrossTotal from the currency
     * @return float|null
     * @since 1.0.0
     */
    public function getGrossTotalFromCurrency(): ?float
    {
        return $this->grossTotalFromCurrency;
    }

    /**
     * Set the calculated TaxPayable
     * @param float|null $taxPayable
     * @return void
     * @since 1.0.0
     */
    public function setTaxPayable(?float $taxPayable): void
    {
        $this->taxPayable = $taxPayable;
    }

    /**
     * Set the calculated NetTotal
     * @param float|null $netTotal
     * @return void
     * @since 1.0.0
     */
    public function setNetTotal(?float $netTotal): void
    {
        $this->netTotal = $netTotal;
    }

    /**
     * Set the calculated Gross total
     * @param float|null $grossTotal
     * @return void
     * @since 1.0.0
     */
    public function setGrossTotal(?float $grossTotal): void
    {
        $this->grossTotal = $grossTotal;
    }

    /**
     * Set the calculated Gross total from Currency
     * @param float|null $grossFromCurrency
     * @return void
     * @since 1.0.0
     */
    public function setGrossTotalFromCurrency(?float $grossFromCurrency): void
    {
        $this->grossTotalFromCurrency = $grossFromCurrency;
    }

    /**
     * To calculate total of each line, the array keys are the
     * line number
     * @return float[]
     * @since 1.0.0
     */
    public function getLineTotal(): array
    {
        return $this->lineTotal;
    }

    /**
     * Add a total for a line
     * @param int $lineNumber
     * @param float $value
     * @return void
     * @since 1.0.0
     */
    public function addLineTotal(int $lineNumber, float $value): void
    {
        $this->lineTotal[$lineNumber] = $value;
    }
}
