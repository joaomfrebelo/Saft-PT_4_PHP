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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Currency<br>
 * It shall not be generated if the document is issued in euros.<br>
 * <pre>
 * &lt;xs:complexType name="Currency"&gt;
 *       &lt;xs:sequence&gt;
 *           &lt;xs:element ref="CurrencyCode"/&gt;
 *           &lt;xs:element ref="CurrencyAmount"/&gt;
 *           &lt;xs:element ref="ExchangeRate"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
 * <pre>
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Currency extends AAuditFile
{
    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_CURRENCY = "Currency";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_CURRENCY_CODE = "CurrencyCode";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_CURRENCY_AMOUNT = "CurrencyAmount";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_EXCHANGE_RATE = "ExchangeRate";

    /**
     * &lt;xs:element ref="CurrencyCode"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     * @since 1.0.0
     */
    private CurrencyCode $currencyCode;

    /**
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     *
     * @var \Decimal\Decimal $currencyAmount
     * @since 1.0.0
     */
    private Decimal $currencyAmount;

    /**
     * &lt;xs:element ref="ExchangeRate"/&gt;
     *
     * @var Decimal $exchangeRate
     * @since 1.0.0
     */
    private Decimal $exchangeRate;

    /**
     * Currency<br>
     * It shall not be generated if the document is issued in euros.<br>
     * <pre>
     * &lt;xs:complexType name="Currency"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="CurrencyCode"/&gt;
     *           &lt;xs:element ref="CurrencyAmount"/&gt;
     *           &lt;xs:element ref="ExchangeRate"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     *
     * @param ErrorRegister $errorRegister
     *
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets CurrencyCode<br>
     * In the case of foreign currency, the field shall be
     * filled in according to norm ISO 4217.<br>
     * &lt;xs:element ref="CurrencyCode"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode
     * @throws \Error
     * @since 1.0.0
     */
    public function getCurrencyCode(): CurrencyCode
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__ . " get '%s'",
                $this->currencyCode->value
            )
        );
        return $this->currencyCode;
    }

    /**
     * Sets CurrencyCode<br>
     * In the case of foreign currency, the field shall be
     * filled in according to norm ISO 4217.<br>
     * &lt;xs:element ref="CurrencyCode"/&gt;
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     *
     * @return void
     * @since 1.0.0
     */
    public function setCurrencyCode(CurrencyCode $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                $this->currencyCode->value
            )
        );
    }

    /**
     * Gets CurrencyAmount<br>
     * Value of field 4.1.4.20.3. – GrossTotal in the original currency of the document.<br>
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     *
     * @return Decimal
     * @throws \Error
     * @since 1.0.0
     */
    public function getCurrencyAmount(): Decimal
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__ . " get '%s'",
                \strval($this->currencyAmount)
            )
        );
        return $this->currencyAmount;
    }

    /**
     * Sets CurrencyAmount<br>
     * Value of field 4.1.4.20.3. – GrossTotal in the original currency of the document.<br>
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     *
     * @param Decimal $currencyAmount
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCurrencyAmount(Decimal $currencyAmount): bool
    {
        if ($currencyAmount->compareTo(new Decimal("0.0")) < 0) {
            $msg = "CurrencyAmount can not be negative";
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("CurrencyAmount_not_valid");
        } else {
            $return = true;
        }
        $this->currencyAmount = $currencyAmount;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                \strval($this->currencyAmount)
            )
        );
        return $return;
    }

    /**
     * Gets ExchangeRate<br>
     * The exchange rate used in the conversion into EUR shall be mentioned.<br>
     * &lt;xs:element ref="ExchangeRate"/&gt;
     *
     * @return Decimal
     * @throws \Error
     * @since 1.0.0
     */
    public function getExchangeRate(): Decimal
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__ . " get '%s'",
                \strval($this->exchangeRate)
            )
        );
        return $this->exchangeRate;
    }

    /**
     * Sets a new exchangeRate<br>
     * The exchange rate used in the conversion into EUR shall be mentioned.<br>
     * &lt;xs:element ref="ExchangeRate"/&gt;
     *
     * @param Decimal $exchangeRate
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setExchangeRate(Decimal $exchangeRate): bool
    {
        if ($exchangeRate->compareTo(new Decimal("0.0")) < 0) {
            $msg = "ExchangeRate can not be negative";
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("ExchangeRate_not_valid");
        } else {
            $return = true;
        }
        $this->exchangeRate = $exchangeRate;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                \strval($this->exchangeRate)
            )
        );
        return $return;
    }

    /**
     * Create Xml node
     *
     * @param \SimpleXMLElement $node
     *
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== ADocumentTotals::N_DOCUMENT_TOTALS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                ADocumentTotals::N_DOCUMENT_TOTALS, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $currencyNode = $node->addChild(static::N_CURRENCY);

        if (isset($this->currencyCode)) {
            $currencyNode->addChild(
                static::N_CURRENCY_CODE, $this->getCurrencyCode()->value
            );
        } else {
            $currencyNode->addChild(static::N_CURRENCY_CODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("CurrencyCode_not_valid");
        }

        if (isset($this->currencyAmount)) {
            $currencyNode->addChild(
                static::N_CURRENCY_AMOUNT,
                $this->floatFormat($this->getCurrencyAmount())
            );
        } else {
            $currencyNode->addChild(static::N_CURRENCY_AMOUNT);
            $this->getErrorRegistor()->addOnCreateXmlNode("CurrencyAmount_not_valid");
        }

        if (isset($this->exchangeRate)) {
            $currencyNode->addChild(
                static::N_EXCHANGE_RATE,
                $this->floatFormat($this->getExchangeRate())
            );
        } else {
            $currencyNode->addChild(static::N_EXCHANGE_RATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ExchangeRate_not_valid");
        }

        return $currencyNode;
    }

    /**
     * Parse Xml code
     *
     * @param \SimpleXMLElement $node
     *
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== static::N_CURRENCY) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_CURRENCY, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setCurrencyCode(
            CurrencyCode::from((string)$node->{static::N_CURRENCY_CODE})
        );
        $this->setCurrencyAmount(new Decimal((string)$node->{static::N_CURRENCY_AMOUNT}));
        $this->setExchangeRate(new Decimal((string)$node->{static::N_EXCHANGE_RATE}));
    }
}
