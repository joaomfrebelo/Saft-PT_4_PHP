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

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals;

/**
 * Description of ADocumentTotals
 *
 * @author João Rebelo
 */
abstract class ADocumentTotals extends AAuditFile
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
     * &lt;xs:element ref="TaxPayable"/&gt;<br>
     * @var float $taxPayable
     * @since 1.0.0
     */
    private float $taxPayable;

    /**
     * &lt;xs:element ref="NetTotal"/&gt;<br>
     * @var float $netTotal
     * @since 1.0.0
     */
    private float $netTotal;

    /**
     * &lt;xs:element ref="GrossTotal"/&gt;<br>
     * @var float $grossTotal
     * @since 1.0.0
     */
    private float $grossTotal;

    /**
     * &lt;xs:element name="Currency" type="Currency" minOccurs="0"/&gt;<br>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency|null $currency
     * @since 1.0.0
     */
    private ?Currency $currency = null;

    /**
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets as taxPayable<br>
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="TaxPayable"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxPayable(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->taxPayable));
        return $this->taxPayable;
    }

    /**
     * Get if is set TaxPayable
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxPayable(): bool
    {
        return isset($this->taxPayable);
    }

    /**
     * Sets a new taxPayable<br>
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="TaxPayable"/&gt;
     * @param float $taxPayable
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxPayable(float $taxPayable): bool
    {
        if ($taxPayable < 0.0) {
            $msg    = "Tax Payable can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxPayable_not_valid");
        } else {
            $return = true;
        }
        $this->taxPayable = $taxPayable;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->taxPayable));
        return $return;
    }

    /**
     * Gets as netTotal<br>
     * [Total of the document without taxes]<br>
     * This field shall not include the amounts regarding the taxes existing
     * in table 2.5. - TaxTable.
     * When not valued in the database, shall be filled in with "0.00".
     * &lt;xs:element ref="NetTotal"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getNetTotal(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->netTotal));
        return $this->netTotal;
    }

    /**
     * Get if is set NetTotal<br>
     * @return bool
     * @since 1.0.0
     */
    public function issetNetTotal(): bool
    {
        return isset($this->netTotal);
    }

    /**
     * Sets a new netTotal<br>
     * &lt;xs:element ref="NetTotal"/&gt;
     * @param float $netTotal
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNetTotal(float $netTotal): bool
    {
        if ($netTotal < 0.0) {
            $msg    = "Net Total can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NetTotal_not_valid");
        } else {
            $return = true;
        }
        $this->netTotal = $netTotal;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->netTotal));
        return $return;
    }

    /**
     * Gets as grossTotal<br>
     * [Total of the Documents with taxes]<br>
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="GrossTotal"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getGrossTotal(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->grossTotal));
        return $this->grossTotal;
    }

    /**
     * Get if is set GrossTotal<br>
     * @return bool
     * @since 1.0.0
     */
    public function issetGrossTotal(): bool
    {
        return isset($this->grossTotal);
    }

    /**
     * Sets a new grossTotal<br>
     * [Total of the Documents with taxes]<br>
     * When not valued in the database, shall be filled in with "0.00".<br>
     * &lt;xs:element ref="GrossTotal"/&gt;
     * @param float $grossTotal
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setGrossTotal(float $grossTotal): bool
    {
        if ($grossTotal < 0.0) {
            $msg    = "Gross Total can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("GrossTotal_not_valid");
        } else {
            $return = true;
        }
        $this->grossTotal = $grossTotal;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->grossTotal));
        return $return;
    }

    /**
     * Gets as currency<br>
     * It shall not be generated if the document is issued in euros.<br>
     * If $create is true and an instance wasn't created a new instance
     * will be created when you get this method.
     * &lt;xs:element name="Currency" type="Currency" minOccurs="0"/&gt;
     * @param bool $create If true an instance of Currency will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Currency|null
     * @since 1.0.0
     */
    public function getCurrency(bool $create = true): ?Currency
    {
        if ($create && $this->currency === null) {
            $this->currency = new Currency($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->info(__METHOD__." get");
        return $this->currency;
    }

    /**
     * Set Currency as null
     * @return void
     * @since 1.0.0
     */
    public function setCurrencyAsNull(): void
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set as null");
        $this->currency = null;
    }

    /**
     * Create the common XML nodes
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        $docTotalNode = $node->addChild(static::N_DOCUMENTTOTALS);

        if (isset($this->taxPayable)) {
            $docTotalNode->addChild(
                static::N_TAXPAYABLE, $this->floatFormat($this->getTaxPayable())
            );
        } else {
            $docTotalNode->addChild(static::N_TAXPAYABLE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxPayable_not_valid");
        }

        if (isset($this->netTotal)) {
            $docTotalNode->addChild(
                static::N_NETTOTAL, $this->floatFormat($this->getNetTotal())
            );
        } else {
            $docTotalNode->addChild(static::N_NETTOTAL);
            $this->getErrorRegistor()->addOnCreateXmlNode("NetTotal_not_valid");
        }


        if (isset($this->grossTotal)) {
            // GrossTotal is always with 2 decimals, and the GrossTotal value to the
            // digital signature hash must be with 2 decimals too
            $docTotalNode->addChild(
                static::N_GROSSTOTAL,
                $this->floatFormat($this->getGrossTotal(), 2)
            );
        } else {
            $docTotalNode->addChild(static::N_GROSSTOTAL);
            $this->getErrorRegistor()->addOnCreateXmlNode("GrossTotal_not_valid");
        }

        // In the Payment the Currency is in different order,
        // must be created in the Payments\DocumentTotals
        if (false === ($this instanceof DocumentTotals)) {
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

        if ($node->getName() !== static::N_DOCUMENTTOTALS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->getCurrency(false)?->createXmlNode($node);
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

        if ($node->getName() !== static::N_DOCUMENTTOTALS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxPayable((float) $node->{static::N_TAXPAYABLE});
        $this->setNetTotal((float) $node->{static::N_NETTOTAL});
        $this->setGrossTotal((float) $node->{static::N_GROSSTOTAL});
        if ($node->{static::N_CURRENCY}->count() > 0) {
            $currency = $this->getCurrency();
            $currency?->setCurrencyAmount(
                (float) $node->{static::N_CURRENCY}->{Currency::N_CURRENCYAMOUNT}
            );
            $currency?->setExchangeRate(
                (float) $node->{static::N_CURRENCY}->{Currency::N_EXCHANGERATE}
            );
            $currency?->setCurrencyCode(
                new CurrencyCode(
                    (string) $node->{static::N_CURRENCY}->{Currency::N_CURRENCYCODE}
                )
            );
        }
    }
}
