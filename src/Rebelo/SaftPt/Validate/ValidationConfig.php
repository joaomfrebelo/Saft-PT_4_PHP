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

use Rebelo\SaftPt\Bin\Style;

/**
 * The validation generic configuration
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class ValidationConfig
{
    /**
     * If allow Debit an Credit line in the same document
     * @var bool
     * @since 1.0.0
     */
    protected bool $allowDebitAndCredit = false;

    /**
     * If allow only continues lines
     * @var bool
     * @since 1.0.0
     */
    protected bool $continuesLines = true;

    /**
     * The maximum delta in the currency calculation to be consider valid
     * @var float
     * @since 1.0.0
     */
    protected float $deltaCurrency = 0.01;

    /**
     * The maximum delta in the lines to be conider valid
     * @var float
     * @since 1.0.0
     */
    protected float $deltaLine = 0.01;

    /**
     * The maximum delta in the table's sums calculation to be consider valid
     * @var float
     * @since 1.0.0
     */
    protected float $deltaTable = 0.01;

    /**
     * The maximum delta in the document totals calculation to be consider value
     * @var float
     * @since 1.0.0
     */
    protected float $deltaTotalDoc = 0.01;

    /**
     * Defifine if performes the signatures validation
     * @var bool
     * @since 1.0.0
     */
    protected bool $signValidation = true;

    /**
     * The output writer, to be use in concolde application
     * @var \Rebelo\SaftPt\Bin\Style|null
     * @since 1.0.0
     */
    protected ?Style $style = null;

    /**
     * Define if validates schema against XSD
     * @var bool
     * @since 1.0.0
     */
    protected bool $schema = true;

    /** The validation generic configuration
     *
     * @author João Rebelo
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * If allow Debit an Credit line in the same document
     * @return bool
     * @since 1.0.0
     */
    public function getAllowDebitAndCredit(): bool
    {
        return $this->allowDebitAndCredit;
    }

    /**
     * If allow only continues lines
     * @return bool
     * @since 1.0.0
     */
    public function getContinuesLines(): bool
    {
        return $this->continuesLines;
    }

    /**
     * The maximum delta in the currency calculation to be consider valid
     * @return float
     * @since 1.0.0
     */
    public function getDeltaCurrency(): float
    {
        return $this->deltaCurrency;
    }

    /**
     * The maximum delta in the line calculation to be consider valid
     * @return float
     * @since 1.0.0
     */
    public function getDeltaLine(): float
    {
        return $this->deltaLine;
    }

    /**
     * The maximum delta in the table's sums calculation to be consider valid
     * @return float
     * @since 1.0.0
     */
    public function getDeltaTable(): float
    {
        return $this->deltaTable;
    }

    /**
     * The maximum delta in the document totals calculation to be consider value
     * @return float
     * @since 1.0.0
     */
    public function getDeltaTotalDoc(): float
    {
        return $this->deltaTotalDoc;
    }

    /**
     * If performes signature validation
     * @return bool
     * @since 1.0.0
     */
    public function getSignValidation(): bool
    {
        return $this->signValidation;
    }

    /**
     * The output writer for console applications
     * @return \Rebelo\SaftPt\Bin\Style|null
     * @since 1.0.0
     */
    public function getStyle(): ?Style
    {
        return $this->style;
    }

    /**
     * Get if is to validate schema against XSD
     * @return bool
     * @since 1.0.0
     */
    public function getSchemaValidate(): bool
    {
        return $this->schema;
    }

    /**
     * If allow Debit an Credit line in the same document
     * @param bool $allowDebitAndCredit
     * @return void
     * @since 1.0.0
     */
    public function setAllowDebitAndCredit(bool $allowDebitAndCredit): void
    {
        $this->allowDebitAndCredit = $allowDebitAndCredit;
    }

    /**
     * If allow only continues lines
     * @param bool $continuesLines
     * @return void
     * @since 1.0.0
     */
    public function setContinuesLines(bool $continuesLines): void
    {
        $this->continuesLines = $continuesLines;
    }

    /**
     * The maximum delta in the currency calculation to be consider valid
     * @param float $deltaCurrency
     * @return void
     * @since 1.0.0
     */
    public function setDeltaCurrency(float $deltaCurrency): void
    {
        $this->deltaCurrency = \abs($deltaCurrency);
    }

    /**
     * The maximum delta in the line calculation to be consider valid
     * @param float $deltaLine
     * @return void
     * @since 1.0.0
     */
    public function setDeltaLine(float $deltaLine): void
    {
        $this->deltaLine = \abs($deltaLine);
    }

    /**
     * The maximum delta in the table's sums calculation to be consider valid
     * @param float $deltaTable
     * @return void
     * @since 1.0.0
     */
    public function setDeltaTable(float $deltaTable): void
    {
        $this->deltaTable = \abs($deltaTable);
    }

    /**
     * The maximum delta in the document totals calculation to be consider value
     * @param float $deltaTotalDoc
     * @return void
     * @since 1.0.0
     */
    public function setDeltaTotalDoc(float $deltaTotalDoc): void
    {
        $this->deltaTotalDoc = \abs($deltaTotalDoc);
    }

    /**
     * If performes signature validation
     * @param bool $signValidation
     * @return void
     * @since 1.0.0
     */
    public function setSignValidation(bool $signValidation): void
    {
        \Logger::configure();
        $this->signValidation = $signValidation;
    }

    /**
     * The output writer for console applications
     * @param \Rebelo\SaftPt\Bin\Style|null $style
     * @return void
     * @since 1.0.0
     */
    public function setStyle(?Style $style = null): void
    {
        $this->style = $style;
    }

    /**
     * Set if is to validate schema against XSD
     * @param bool $schema
     * @return void
     * @since 1.0.0
     */
    public function setSchemaValidate(bool $schema): void
    {
        $this->schema = $schema;
    }
}
