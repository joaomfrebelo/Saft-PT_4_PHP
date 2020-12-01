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

use Rebelo\Decimal\UDecimal;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\Sign\Sign;
use Rebelo\SaftPt\Bin\Style;

/**
 * Base class of documents Validation
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class ADocuments extends AValidate
{
    /**
     * The number of decimal to use in the UDecimal class for calculation
     * of documents values validation
     * @since 1.0.0
     */
    const CALC_PRECISION = 9;

    /**
     * The output writer, to be use in concolde application
     * @var \Rebelo\SaftPt\Bin\Style|null
     * @since 1.0.0
     */
    protected ?Style $style = null;

    /**
     * The total debit calculated from all docmunts of the table
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $debit;

    /**
     * The total credit calculated from all documents of the table
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $credit;

    /**
     * The total debit calculated of the current document
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $docDebit;

    /**
     * The total credit calculated of the current document
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $docCredit;

    /**
     * The total tax calculated of the current document
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $taxPayable;

    /**
     * The net total calculated of the current document
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $netTotal;

    /**
     * The gross total calculated of the current document
     * @var \Rebelo\Decimal\UDecimal
     * @since 1.0.0
     */
    protected UDecimal $grossTotal;

    /**
     * The Sign instance with the public key defined
     * @var \Rebelo\SaftPt\Sign\Sign
     * @since 1.0.0
     */
    protected Sign $sign;

    /**
     * The delta that can be consider valid for total documents calculation
     * @var float
     * @since 1.0.0
     */
    protected float $deltaTotalDoc = 0.0;

    /**
     * The delta that can be consider valid for the product of UnitPRice and Quantity
     * @var float
     * @since 1.0.0
     */
    protected float $deltaLine = 0.0;

    /**
     * The delta that can be consider valid for total documents currency
     * @var float
     * @since 1.0.0
     */
    protected float $deltaCurrency = 0.0;

    /**
     * The delta that can be consider valid for total tables
     * @var float
     * @since 1.0.0
     */
    protected float $deltaTable = 0.0;

    /**
     * Set if the lines number are to be verified continues or not. If you set to
     * true all lines numbers have to be continues and starting from 1, else only
     * check if ther are repeated line numbers.<br>
     * The AT ordinance is not very clear how should be done, only says that the lines
     * are to be exported in the same order as in the document and non fiscal lines
     * aren't to be exported. So there are two situation if you have some non fiscal
     * lines and you are exporting with the ordr and same number line or database document
     * will miss the numbers of non fiscal lines, if you export with the continues
     * numeration, the numeration in the saft will be not equal to the line numebr
     * in the database.
     * @var bool
     * @since 1.0.0
     */
    protected bool $continuesLines = true;

    /**
     * The normal situation is the documents only have credit or debit lines,
     * however there are two situation where is possible to have debit and
     * credit lines, by default this is set to not allow, if the saft
     * that are being test have that situations set this to true
     * @var bool
     * @since 1.0.0
     */
    protected bool $allowDebitAndCredit = false;

    /**
     * Defifine if performes the signatures validation
     * @var bool
     * @since 1.0.0
     */
    protected bool $signValidation = true;

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile
     * @param \Rebelo\SaftPt\Sign\Sign|null $sign
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile, Sign $sign = null)
    {
        parent::__construct($auditFile);
        $this->debit  = new UDecimal(0.0, static::CALC_PRECISION);
        $this->credit = new UDecimal(0.0, static::CALC_PRECISION);
        if ($sign !== null) {
            $this->sign = $sign;
        }
    }

    /**
     * The delta that can be consider valid for total documents calculation
     * @return float
     * @since 1.0.0
     */
    public function getDeltaTotalDoc(): float
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->deltaTotalDoc;
    }

    /**
     * The delta that can be consider valid for total documents currency
     * @return float
     * @since 1.0.0
     */
    public function getDeltaCurrency(): float
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->deltaCurrency;
    }

    /**
     * The delta that can be consider valid for total tables
     * @return float
     * @since 1.0.0
     */
    public function getDeltaTable(): float
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->deltaTable;
    }

    /**
     * The delta that can be consider valid for UnitPrice * Quantity
     * @return float
     * @since 1.0.0
     */
    public function getDeltaLine(): float
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->deltaLine;
    }

    /**
     * The delta that can be consider valid for total documents calculation
     * @param float $deltaTotalDoc
     * @return void
     * @since 1.0.0
     */
    public function setDeltaTotalDoc(float $deltaTotalDoc): void
    {
        $this->deltaTotalDoc = $deltaTotalDoc;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf("DeltaTotalDoc set to '%s'", $this->deltaTotalDoc)
            );
    }

    /**
     * The delta that can be consider valid for total documents currency
     * @param float $deltaCurrency
     * @return void
     * @since 1.0.0
     */
    public function setDeltaCurrency(float $deltaCurrency): void
    {
        $this->deltaCurrency = $deltaCurrency;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf("DeltaCurrency set to '%s'", $this->deltaCurrency)
            );
    }

    /**
     * The delta that can be consider valid for total tables
     * @param float $deltaTable
     * @return void
     * @since 1.0.0
     */
    public function setDeltaTable(float $deltaTable): void
    {
        $this->deltaTable = $deltaTable;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf("DeltaTable set to '%s'", $this->deltaTable)
            );
    }

    /**
     * The delta that can be consider valid for Quantity * UnitPrice
     * @param float $deltaLine
     * @return void
     * @since 1.0.0
     */
    public function setDeltaLine(float $deltaLine): void
    {
        $this->deltaLine = $deltaLine;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf("DeltaLine set to '%s'", $this->deltaLine)
            );
    }

    /**
     * Check if the line numbers are to be check as continues or only
     * if there are repeated line numbers
     * @return bool
     * @since 1.0.0
     */
    public function getContinuesLines(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                __METHOD__.
                \sprintf(
                    "ContinuesLine getted as '%s'",
                    $this->continuesLines ? "true" : "false"
                )
            );
        return $this->continuesLines;
    }

    /**
     * Check if the line numbers are to be check as continues or only
     * if there are repeated line numbers
     * @param bool $continuesLines If true the validation will check if the line number are continues, false will check only id there are repeated values
     * @return void
     * @since 1.0.0
     */
    public function setContinuesLines(bool $continuesLines): void
    {
        $this->continuesLines = $continuesLines;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf(
                    "ContinuesLine set as '%s'",
                    $this->continuesLines ? "true" : "false"
                )
            );
    }

    /**
     * The normal situation is the documents only have credit or debit lines,
     * however there are two situation where is possible to have debit and
     * credit lines, by default this is set to not allow, if the saft
     * that are being test have that situations set this to true.<br>
     * Point 2.2.6 of Ordinance 8632/2014, of 3th of July
     * @return bool
     * @since 1.0.0
     */
    public function getAllowDebitAndCredit(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                __METHOD__.
                \sprintf(
                    "AllowDebitAndCredit getted as '%s'",
                    $this->allowDebitAndCredit ? "true" : "false"
                )
            );
        return $this->allowDebitAndCredit;
    }

    /**
     * The normal situation is the documents only have credit or debit lines,
     * however there are two situation where is possible to have debit and
     * credit lines, by default this is set to not allow, if the saft
     * that are being test have that situations set this to true.<br>
     * Point 2.2.6 of Ordinance 8632/2014, of 3th of July
     * @param bool $allowDebitAndCredit
     * @return void
     * @since 1.0.0
     */
    public function setAllowDebitAndCredit(bool $allowDebitAndCredit): void
    {
        $this->allowDebitAndCredit = $allowDebitAndCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf(
                    "AllowDebitAndCredit set as '%s'",
                    $this->allowDebitAndCredit ? "true" : "false"
                )
            );
    }

    /**
     * If performes signature validation
     * @param bool $signValidation
     * @return void
     * @since 1.0.0
     */
    public function setSignValidation(bool $signValidation): void
    {
        $this->signValidation = $signValidation;
        \Logger::getLogger(\get_class($this))
            ->debug(
                __METHOD__.
                \sprintf(
                    "SignValidation set as '%s'",
                    $this->signValidation ? "true" : "false"
                )
            );
    }

    /**
     * If performes signature validation
     * @return bool
     * @since 1.0.0
     */
    public function getSignValidation(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                __METHOD__.
                \sprintf(
                    "SignValidation getted as '%s'",
                    $this->signValidation ? "true" : "false"
                )
            );
        return $this->signValidation;
    }

    /**
     * Set the configuration
     * @param \Rebelo\SaftPt\Validate\ValidationConfig $config
     * @return void
     * @since 1.0.0
     */
    public function setConfiguration(ValidationConfig $config): void
    {
        $this->setAllowDebitAndCredit($config->getAllowDebitAndCredit());
        $this->setContinuesLines($config->getContinuesLines());
        $this->setDeltaCurrency($config->getDeltaCurrency());
        $this->setDeltaLine($config->getDeltaLine());
        $this->setDeltaTable($config->getDeltaTable());
        $this->setDeltaTotalDoc($config->getDeltaTotalDoc());
        $this->setSignValidation($config->getSignValidation());
        $this->setStyle($config->getStyle());
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
     * The output writer for console applications
     * @param \Rebelo\SaftPt\Bin\Style|null $style
     * @return void
     * @since 1.0.0
     */
    public function setStyle(?Style $style = null): void
    {
        $this->style = $style;
    }
}