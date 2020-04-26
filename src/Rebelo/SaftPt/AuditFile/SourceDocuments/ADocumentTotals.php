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

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Description of ADocumentTotals
 *
 * @author João Rebelo
 */
abstract class ADocumentTotals
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DOCUMENTTOTALS = "DocumentTotals";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXPAYABLE = "TaxPayable";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_NETTOTAL = "NetTotal";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_GROSSTOTAL = "GrossTotal";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CURRENCY = "Currency";

    /**
     * <xs:element ref="TaxPayable"/><br>
     * @var float $taxPayable
     * @since 1.0.0
     */
    private float $taxPayable;

    /**
     * <xs:element ref="NetTotal"/><br>
     * @var float $netTotal
     * @since 1.0.0
     */
    private float $netTotal;

    /**
     * <xs:element ref="GrossTotal"/><br>
     * @var float $grossTotal
     * @since 1.0.0
     */
    private float $grossTotal;

    /**
     * <xs:element name="Currency" type="Currency" minOccurs="0"/><br>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency|null $currency
     * @since 1.0.0
     */
    private ?Currency $currency = null;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as taxPayable<br>
     * <xs:element ref="TaxPayable"/>
     * @return float
     * @since 1.0.0
     */
    public function getTaxPayable(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->taxPayable));
        return $this->taxPayable;
    }

    /**
     * Sets a new taxPayable<br>
     * <xs:element ref="TaxPayable"/>
     * @param float $taxPayable
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxPayable(float $taxPayable): void
    {
        if ($taxPayable < 0.0)
        {
            $msg = "Tax Payable can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxPayable = $taxPayable;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->taxPayable));
    }

    /**
     * Gets as netTotal<br>
     * <xs:element ref="NetTotal"/>
     * @return float
     * @since 1.0.0
     */
    public function getNetTotal(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->netTotal));
        return $this->netTotal;
    }

    /**
     * Sets a new netTotal<br>
     * <xs:element ref="NetTotal"/>
     * @param float $netTotal
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setNetTotal(float $netTotal): void
    {
        if ($netTotal < 0.0)
        {
            $msg = "Net Total can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->netTotal = $netTotal;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->netTotal));
    }

    /**
     * Gets as grossTotal<br>
     * <xs:element ref="GrossTotal"/>
     * @return float
     * @since 1.0.0
     */
    public function getGrossTotal(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->grossTotal));
        return $this->grossTotal;
    }

    /**
     * Sets a new grossTotal<br>
     * <xs:element ref="GrossTotal"/>
     * @param float $grossTotal
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setGrossTotal(float $grossTotal): void
    {
        if ($grossTotal < 0.0)
        {
            $msg = "Gross Total can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->grossTotal = $grossTotal;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->grossTotal));
    }

    /**
     * Gets as currency<br>
     * <xs:element name="Currency" type="Currency" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency|null
     * @since 1.0.0
     */
    public function getCurrency(): ?Currency
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->currency === null
                        ? "null"
                        : $this->currency->getCurrencyCode()->get()));
        return $this->currency;
    }

    /**
     * Sets a new currency<br>
     * <xs:element name="Currency" type="Currency" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency|null $currency
     * @return void
     * @since 1.0.0
     */
    public function setCurrency(?Currency $currency): void
    {
        $this->currency = $currency;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->currency === null
                        ? "null"
                        : $this->currency->getCurrencyCode()->get()));
    }

    /**
     * Create the commun XML nodes
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        $docTotalNode = $node->addChild(static::N_DOCUMENTTOTALS);

        $docTotalNode->addChild(
            static::N_TAXPAYABLE, $this->floatFormat($this->getTaxPayable())
        );
        $docTotalNode->addChild(
            static::N_NETTOTAL, $this->floatFormat($this->getNetTotal())
        );
        // GrossTotal is allways with 2 decimals, and the GrossTota value to the
        // digital sign hash must be with 2 decimals too
        $docTotalNode->addChild(
            static::N_GROSSTOTAL, $this->floatFormat($this->getGrossTotal(), 2)
        );

        // In the Payment the Currency is in diferent order,
        // must be created in the Payments\DocumentTotals
        if (false === ($this instanceof \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals))
        {
            $this->createCurrencyNode($docTotalNode);
        }
        return $docTotalNode;
    }

    /**
     * Create the Currency XMl node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    protected function createCurrencyNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_DOCUMENTTOTALS)
        {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                            static::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($this->getCurrency() !== null)
        {

            $this->getCurrency()->createXmlNode($node);
        }
        else
        {
            \Logger::getLogger(\get_class($this))->trace(__METHOD__
                . " No Currency defined to create XMML node");
        }
    }

    /**
     * Parse the XML node
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_DOCUMENTTOTALS)
        {
            $msg = sprintf("Node name should be '%s' but is '%s",
                           static::N_DOCUMENTTOTALS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxPayable((float) $node->{static::N_TAXPAYABLE});
        $this->setNetTotal((float) $node->{static::N_NETTOTAL});
        $this->setGrossTotal((float) $node->{static::N_GROSSTOTAL});
        if ($node->{static::N_CURRENCY}->count() > 0)
        {
            $currency = new Currency();
            $currency->setCurrencyAmount(
                (float) $node->{static::N_CURRENCY}->{Currency::N_CURRENCYAMOUNT}
            );
            $currency->setExchangeRate(
                (float) $node->{static::N_CURRENCY}->{Currency::N_EXCHANGERATE}
            );
            $currency->setCurrencyCode(
                new \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode(
                    (string) $node->{static::N_CURRENCY}->{Currency::N_CURRENCYCODE}
                )
            );
            $this->setCurrency($currency);
        }
    }

}
