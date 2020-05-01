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

/**
 * Currency
 * <!-- Estrutura de valor monetario -->
 * <!-- Este elemento apenas deve ser gerado quando a moeda original do documento for diferente de euro -->
 *   &lt;xs:complexType name="Currency"&gt;
 *       &lt;xs:sequence&gt;
 *           &lt;xs:element ref="CurrencyCode"/&gt;
 *           &lt;xs:element ref="CurrencyAmount"/&gt;
 *           &lt;xs:element ref="ExchangeRate"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
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
     * <xs:element ref="CurrencyCode"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     * @since 1.0.0
     */
    private CurrencyCode $currencyCode;

    /**
     * <xs:element ref="CurrencyAmount"/>
     * @var float $currencyAmount
     * @since 1.0.0
     */
    private float $currencyAmount;

    /**
     * <xs:element ref="ExchangeRate"/>
     * @var float $exchangeRate
     * @since 1.0.0
     */
    private float $exchangeRate;

    /**
     * <!-- Estrutura de valor monetario -->
     * <!-- Este elemento apenas deve ser gerado quando a moeda original do documento for diferente de euro -->
     *   &lt;xs:complexType name="Currency"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="CurrencyCode"/&gt;
     *           &lt;xs:element ref="CurrencyAmount"/&gt;
     *           &lt;xs:element ref="ExchangeRate"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as currencyCode<br>
     * <xs:element ref="CurrencyCode"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode
     * @since 1.0.0
     */
    public function getCurrencyCode(): CurrencyCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->currencyCode->get()));
        return $this->currencyCode;
    }

    /**
     * Sets a new currencyCode<br>
     * <xs:element ref="CurrencyCode"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode $currencyCode
     * @return void
     * @since 1.0.0
     */
    public function setCurrencyCode(CurrencyCode $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->currencyCode->get()));
    }

    /**
     * Gets as currencyAmount<br>
     * <xs:element ref="CurrencyAmount"/>
     * @return float
     * @since 1.0.0
     */
    public function getCurrencyAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->currencyAmount)));
        return $this->currencyAmount;
    }

    /**
     * Sets a new currencyAmount<br>
     * <xs:element ref="CurrencyAmount"/>
     * @param float $currencyAmount
     * @return void
     * @since 1.0.0
     */
    public function setCurrencyAmount(float $currencyAmount): void
    {
        if ($currencyAmount < 0.0) {
            $msg = "CurrencyAmout can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->currencyAmount = $currencyAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->currencyAmount)));
    }

    /**
     * Gets as exchangeRate<br>
     * <xs:element ref="ExchangeRate"/>
     * @return float
     * @since 1.0.0
     */
    public function getExchangeRate(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->exchangeRate)));
        return $this->exchangeRate;
    }

    /**
     * Sets a new exchangeRate
     * <xs:element ref="ExchangeRate"/>
     * @param float $exchangeRate
     * @return void
     * @since 1.0.0
     */
    public function setExchangeRate(float $exchangeRate): void
    {
        if ($exchangeRate < 0.0) {
            $msg = "ExchangeRate can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->exchangeRate = $exchangeRate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->exchangeRate)));
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== ADocumentTotals::N_DOCUMENTTOTALS) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                ADocumentTotals::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $currencyNode = $node->addChild(static::N_CURRENCY);
        $currencyNode->addChild(
            static::N_CURRENCYCODE, $this->getCurrencyCode()->get()
        );
        $currencyNode->addChild(
            static::N_CURRENCYAMOUNT,
            $this->floatFormat($this->getCurrencyAmount())
        );
        $currencyNode->addChild(
            static::N_EXCHANGERATE, $this->floatFormat($this->getExchangeRate())
        );
        return $currencyNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_CURRENCY) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_CURRENCY, $node->getName());
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