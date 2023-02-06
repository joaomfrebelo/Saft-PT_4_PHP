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
 * Recalculated Doc table values for Movement of goods
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovOfGoodsTableTotalCalc
{
    /**
     * @var int|null $numberOfMovementLines
     * @since 1.0.0
     */
    protected ?int $numberOfMovementLines = null;

    /**
     * @var float|null $totalQuantityIssued
     * @since 1.0.0
     */
    protected ?float $totalQuantityIssued = null;

    /**
     * The Calculate values
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * get the calculated numberOfMovementLines
     * @return int|null
     * @since 1.0.0
     */
    public function getNumberOfMovementLines(): ?int
    {
        return $this->numberOfMovementLines;
    }

    /**
     * Get the Calculated TotalQuantityIssued
     * @return float|null
     * @since 1.0.0
     */
    public function getTotalQuantityIssued(): ?float
    {
        return $this->totalQuantityIssued;
    }

    /**
     * Set the calculated NumberOfEntries
     * @param int|null $numberOfMovementLines
     * @return void
     * @since 1.0.0
     */
    public function setNumberOfMovementLines(?int $numberOfMovementLines): void
    {
        $this->numberOfMovementLines = $numberOfMovementLines;
    }

    /**
     * Set the calculated TotalQuantityIssued
     * @param float|null $totalQuantityIssued
     * @return void
     * @since 1.0.0
     */
    public function setTotalQuantityIssued(?float $totalQuantityIssued): void
    {
        $this->totalQuantityIssued = $totalQuantityIssued;
    }
}
