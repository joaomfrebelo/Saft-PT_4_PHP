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

/**
 * Recalculated Doc table values for SalesInvoices, WorkingDocuments and Payments
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocTableTotalCalc
{
    /**
     * @var int|null $numberOfEntries
     * @since 1.0.0
     */
    protected ?int $numberOfEntries = null;

    /**
     * @var Decimal|null $totalDebit
     * @since 1.0.0
     */
    protected Decimal|null $totalDebit = null;

    /**
     * @var Decimal|null $totalCredit
     * @since 1.0.0
     */
    protected Decimal|null $totalCredit = null;

    /**
     * The Calculate values
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * get the calculated NumberOfEntries
     * @return int|null
     * @since 1.0.0
     */
    public function getNumberOfEntries(): ?int
    {
        return $this->numberOfEntries;
    }

    /**
     * Get the Calculated TotalDebit
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getTotalDebit(): Decimal|null
    {
        return $this->totalDebit;
    }

    /**
     * Get the TotalCredit calculated
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getTotalCredit(): Decimal|null
    {
        return $this->totalCredit;
    }

    /**
     * Set the calculated NumberOfEntries
     * @param int|null $numberOfEntries
     * @return void
     * @since 1.0.0
     */
    public function setNumberOfEntries(?int $numberOfEntries): void
    {
        $this->numberOfEntries = $numberOfEntries;
    }

    /**
     * Set the calculated TotalDebit
     * @param Decimal|null $totalDebit
     * @return void
     * @since 1.0.0
     */
    public function setTotalDebit(Decimal|null $totalDebit): void
    {
        $this->totalDebit = $totalDebit;
    }

    /**
     * Set the calculated TotalCredit
     * @param Decimal|null $totalCredit
     * @return void
     * @since 1.0.0
     */
    public function setTotalCredit(Decimal|null $totalCredit): void
    {
        $this->totalCredit = $totalCredit;
    }
}
