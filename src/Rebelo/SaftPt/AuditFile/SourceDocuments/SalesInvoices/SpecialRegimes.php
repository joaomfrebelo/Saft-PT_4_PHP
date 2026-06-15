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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * SpecialRegimes<br>
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
class SpecialRegimes extends AAuditFile
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_SPECIAL_REGIMES = "SpecialRegimes";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_SELF_BILLING_INDICATOR = "SelfBillingIndicator";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_CASH_VAT_SCHEME_INDICATOR = "CashVATSchemeIndicator";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_THIRD_PARTIES_BILLING_INDICATOR = "ThirdPartiesBillingIndicator";

    /**
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * @var bool
     * @since 1.0.0
     */
    private bool $selfBillingIndicator = false;

    /**
     *  &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
     * @var bool
     * @since 1.0.0
     */
    private bool $cashVATSchemeIndicator = false;

    /**
     * &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
     * @var bool
     * @since 1.0.0
     */
    private bool $thirdPartiesBillingIndicator = false;

    /**
     * SpecialRegimes<br>
     * &lt;xs:complexType name="SpecialRegimes"&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="SelfBillingIndicator"/&gt;
     *          &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
     *          &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * GetSelfBillingIndicator<br>
     * The field shall be filled in with “1” if it concerns
     * self-billing and otherwise with “0” (Zero).<br>
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * @return bool
     * @since 1.0.0
     */
    public function getSelfBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->selfBillingIndicator ? "true" : "false"
                )
            );
        return $this->selfBillingIndicator;
    }

    /**
     * Get CashVATSchemeIndicator<br>
     * Accession indicator to the VAT cash method.
     * Should be filled in with “1” in case the method has
     * been accessed and with “0” (zero) if not.<br>
     *  &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
     * @return bool
     * @since 1.0.0
     */
    public function getCashVATSchemeIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->cashVATSchemeIndicator ? "true" : "false"
                )
            );
        return $this->cashVATSchemeIndicator;
    }

    /**
     * GetThirdPartiesBillingIndicator<br>
     * Should be filled in with “1” for invoices issued on behalf of
     * third persons and with “0” (zero) if not.<br>
     * &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
     * @return bool
     * @since 1.0.0
     */
    public function getThirdPartiesBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->thirdPartiesBillingIndicator ? "true" : "false"
                )
            );
        return $this->thirdPartiesBillingIndicator;
    }

    /**
     * SetSelfBillingIndicator<br>
     * The field shall be filled in with “1” if it concerns
     * self-billing and otherwise with “0” (Zero).<br>
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * @param bool $selfBillingIndicator
     * @return void
     * @since 1.0.0
     */
    public function setSelfBillingIndicator(bool $selfBillingIndicator): void
    {
        $this->selfBillingIndicator = $selfBillingIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->selfBillingIndicator ? "true" : "false"
                )
            );
    }

    /**
     * Set CashVATSchemeIndicator<br>
     * Accession indicator to the VAT cash method.
     * Should be filled in with “1” in case the method has
     * been accessed and with “0” (zero) if not.<br>
     * &lt;xs:element ref="CashVATSchemeIndicator"/&gt;
     * @param bool $cashVATSchemeIndicator
     * @return void
     * @since 1.0.0
     */
    public function setCashVATSchemeIndicator(bool $cashVATSchemeIndicator): void
    {
        $this->cashVATSchemeIndicator = $cashVATSchemeIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->cashVATSchemeIndicator ? "true" : "false"
                )
            );
    }

    /**
     * <br>
     * Should be filled in with “1” for invoices issued on behalf of
     * third persons and with “0” (zero) if not.<br>
     * &lt;xs:element ref="ThirdPartiesBillingIndicator"/&gt;
     * @param bool $thirdPartiesBillingIndicator
     * @return void
     * @since 1.0.0
     */
    public function setThirdPartiesBillingIndicator(bool $thirdPartiesBillingIndicator): void
    {
        $this->thirdPartiesBillingIndicator = $thirdPartiesBillingIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->thirdPartiesBillingIndicator ? "true" : "false"
                )
            );
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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                Invoice::N_INVOICE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $nodeSpeReg = $node->addChild(static::N_SPECIAL_REGIMES);

        $nodeSpeReg->addChild(
            static::N_SELF_BILLING_INDICATOR,
            $this->getSelfBillingIndicator() ? "1" : "0"
        );
        $nodeSpeReg->addChild(
            static::N_CASH_VAT_SCHEME_INDICATOR,
            $this->getCashVATSchemeIndicator() ? "1" : "0"
        );
        $nodeSpeReg->addChild(
            static::N_THIRD_PARTIES_BILLING_INDICATOR,
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

        if ($node->getName() !== static::N_SPECIAL_REGIMES) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SPECIAL_REGIMES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setSelfBillingIndicator(
            (string) $node->{static::N_SELF_BILLING_INDICATOR} === "1"
        );

        $this->setCashVATSchemeIndicator(
            (string) $node->{static::N_CASH_VAT_SCHEME_INDICATOR} === "1"
        );

        $this->setThirdPartiesBillingIndicator(
            (string) $node->{static::N_THIRD_PARTIES_BILLING_INDICATOR} === "1"
        );
    }
}
