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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 *
 * <!-- Estrutura de Regimes especiais de faturacao-->
 *  &lt;xs:complexType name="SpecialRegimes"&gt;
 *      &lt;xs:sequence&gt;
 *          &lt;xs:element ref="SelfBillingIndicator"/&gt;
 *          &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
 *          &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
 *      &lt;/xs:sequence&gt;
 *  &lt;/xs:complexType&gt;
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SpecialRegimes extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SPECIALREGIMES = "SpecialRegimes";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SELFBILLINGINDICATOR = "SelfBillingIndicator";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_CASHVATSCHEMEINDICATOR = "CashVATSchemeIndicator";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_THIRDPARTIESBILLINGINDICATOR = "ThirdPartiesBillingIndicator";

    /**
     * <xs:element ref="SelfBillingIndicator"/>
     * @var bool
     * @since 1.0.0
     */
    private bool $selfBillingIndicator = false;

    /**
     *  <xs:element ref="CashVATSchemeIndicator"/>
     * @var bool
     * @since 1.0.0
     */
    private bool $cashVATSchemeIndicator = false;

    /**
     * <xs:element ref="ThirdPartiesBillingIndicator"/>
     * @var bool
     * @since 1.0.0
     */
    private bool $thirdPartiesBillingIndicator = false;

    /**
     * <!-- Estrutura de Regimes especiais de faturacao-->
     *  &lt;xs:complexType name="SpecialRegimes"&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="SelfBillingIndicator"/&gt;
     *          &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
     *          &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  <xs:element ref="SelfBillingIndicator"/>
     * @return bool
     * @since 1.0.0
     */
    public function getSelfBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->selfBillingIndicator ? "true" : "false"));
        return $this->selfBillingIndicator;
    }

    /**
     *  <xs:element ref="CashVATSchemeIndicator"/>
     * @return bool
     * @since 1.0.0
     */
    public function getCashVATSchemeIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->cashVATSchemeIndicator ? "true" : "false"));
        return $this->cashVATSchemeIndicator;
    }

    /**
     * <xs:element ref="ThirdPartiesBillingIndicator"/>
     * @return bool
     * @since 1.0.0
     */
    public function getThirdPartiesBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->thirdPartiesBillingIndicator ? "true" : "false"));
        return $this->thirdPartiesBillingIndicator;
    }

    /**
     *  <xs:element ref="SelfBillingIndicator"/>
     * @param bool $SelfBillingIndicator
     * @return void
     * @since 1.0.0
     */
    public function setSelfBillingIndicator(bool $SelfBillingIndicator): void
    {
        $this->selfBillingIndicator = $SelfBillingIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->selfBillingIndicator ? "true" : "false"));
    }

    /**
     * <xs:element ref="CashVATSchemeIndicator"/>
     * @param bool $CashVATSchemeIndicator
     * @return void
     * @since 1.0.0
     */
    public function setCashVATSchemeIndicator(bool $CashVATSchemeIndicator): void
    {
        $this->cashVATSchemeIndicator = $CashVATSchemeIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->cashVATSchemeIndicator ? "true" : "false"));
    }

    /**
     * <xs:element ref="ThirdPartiesBillingIndicator"/>
     * @param bool $ThirdPartiesBillingIndicator
     * @return void
     * @since 1.0.0
     */
    public function setThirdPartiesBillingIndicator(bool $ThirdPartiesBillingIndicator): void
    {
        $this->thirdPartiesBillingIndicator = $ThirdPartiesBillingIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->thirdPartiesBillingIndicator ? "true" : "false"));
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

        if ($node->getName() !== Invoice::N_INVOICE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                Invoice::N_INVOICE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $nodeSpeReg = $node->addChild(static::N_SPECIALREGIMES);

        $nodeSpeReg->addChild(
            static::N_SELFBILLINGINDICATOR,
            $this->getSelfBillingIndicator() ? "1" : "0"
        );
        $nodeSpeReg->addChild(
            static::N_CASHVATSCHEMEINDICATOR,
            $this->getCashVATSchemeIndicator() ? "1" : "0"
        );
        $nodeSpeReg->addChild(
            static::N_THIRDPARTIESBILLINGINDICATOR,
            $this->getThirdPartiesBillingIndicator() ? "1" : "0"
        );
        return $nodeSpeReg;
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

        if ($node->getName() !== static::N_SPECIALREGIMES) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_SPECIALREGIMES, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setSelfBillingIndicator(
            (string) $node->{static::N_SELFBILLINGINDICATOR} === "1"
        );

        $this->setCashVATSchemeIndicator(
            (string) $node->{static::N_CASHVATSCHEMEINDICATOR} === "1"
        );

        $this->setThirdPartiesBillingIndicator(
            (string) $node->{static::N_THIRDPARTIESBILLINGINDICATOR} === "1"
        );
    }
}