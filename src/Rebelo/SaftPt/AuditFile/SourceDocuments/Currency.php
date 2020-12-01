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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
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
 * @author João Rebelo
 * @since 1.0.0
 */
class Currency extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_CURRENCY = "Currency";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CURRENCYCODE = "CurrencyCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CURRENCYAMOUNT = "CurrencyAmount";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_EXCHANGERATE = "ExchangeRate";

    /**
     * &lt;xs:element ref="CurrencyCode"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     * @since 1.0.0
     */
    private CurrencyCode $currencyCode;

    /**
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     * @var float $currencyAmount
     * @since 1.0.0
     */
    private float $currencyAmount;

    /**
     * &lt;xs:element ref="ExchangeRate"/&gt;
     * @var float $exchangeRate
     * @since 1.0.0
     */
    private float $exchangeRate;

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
     * @param ErrorRegister $errorRegister
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
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode
     * @throws \Error
     * @since 1.0.0
     */
    public function getCurrencyCode(): CurrencyCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->currencyCode->get()
                )
            );
        return $this->currencyCode;
    }

    /**
     * Sets CurrencyCode<br>
     * In the case of foreign currency, the field shall be
     * filled in according to norm ISO 4217.<br>
     * &lt;xs:element ref="CurrencyCode"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     * @return void
     * @since 1.0.0
     */
    public function setCurrencyCode(CurrencyCode $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->currencyCode->get()
                )
            );
    }

    /**
     * Gets CurrencyAmount<br>
     * Value of field 4.1.4.20.3. – GrossTotal in the original currency of the document.<br>
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getCurrencyAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->currencyAmount)
                )
            );
        return $this->currencyAmount;
    }

    /**
     * Sets CurrencyAmount<br>
     * Value of field 4.1.4.20.3. – GrossTotal in the original currency of the document.<br>
     * &lt;xs:element ref="CurrencyAmount"/&gt;
     * @param float $currencyAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCurrencyAmount(float $currencyAmount): bool
    {
        if ($currencyAmount < 0.0) {
            $msg    = "CurrencyAmout can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("CurrencyAmount_not_valid");
        } else {
            $return = true;
        }
        $this->currencyAmount = $currencyAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->currencyAmount)
                )
            );
        return $return;
    }

    /**
     * Gets ExchangeRate<br>
     * The exchange rate used in the conversion into EUR shall be mentioned.<br>
     * &lt;xs:element ref="ExchangeRate"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getExchangeRate(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->exchangeRate)
                )
            );
        return $this->exchangeRate;
    }

    /**
     * Sets a new exchangeRate<br>
     * The exchange rate used in the conversion into EUR shall be mentioned.<br>
     * &lt;xs:element ref="ExchangeRate"/&gt;
     * @param float $exchangeRate
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setExchangeRate(float $exchangeRate): bool
    {
        if ($exchangeRate < 0.0) {
            $msg    = "ExchangeRate can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("ExchangeRate_not_valid");
        } else {
            $return = true;
        }
        $this->exchangeRate = $exchangeRate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->exchangeRate)
                )
            );
        return $return;
    }

    /**
     * Create Xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== ADocumentTotals::N_DOCUMENTTOTALS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                ADocumentTotals::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $currencyNode = $node->addChild(static::N_CURRENCY);

        if (isset($this->currencyCode)) {
            $currencyNode->addChild(
                static::N_CURRENCYCODE, $this->getCurrencyCode()->get()
            );
        } else {
            $currencyNode->addChild(static::N_CURRENCYCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("CurrencyCode_not_valid");
        }

        if (isset($this->currencyAmount)) {
            $currencyNode->addChild(
                static::N_CURRENCYAMOUNT,
                $this->floatFormat($this->getCurrencyAmount())
            );
        } else {
            $currencyNode->addChild(static::N_CURRENCYAMOUNT);
            $this->getErrorRegistor()->addOnCreateXmlNode("CurrencyAmount_not_valid");
        }

        if (isset($this->exchangeRate)) {
            $currencyNode->addChild(
                static::N_EXCHANGERATE,
                $this->floatFormat($this->getExchangeRate())
            );
        } else {
            $currencyNode->addChild(static::N_EXCHANGERATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("ExchangeRate_not_valid");
        }

        return $currencyNode;
    }

    /**
     * Parse Xml code
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_CURRENCY) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_CURRENCY, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setCurrencyCode(
            new CurrencyCode((string) $node->{static::N_CURRENCYCODE})
        );
        $this->setCurrencyAmount((float) $node->{static::N_CURRENCYAMOUNT});
        $this->setExchangeRate((float) $node->{static::N_EXCHANGERATE});
    }
}