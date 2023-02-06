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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;

/**
 * DocumentTotals
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotals extends ADocumentTotals
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SETTLEMENT = "Settlement";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SETTLEMENTAMOUNT = "SettlementAmount";

    /**
     * Settlement Amount
     * @var float|null
     * @since 1.0.0
     */
    private ?float $settlementAmount = null;

    /**
     * DocumentTotals
     * <pre>
     * &lt;xs:element name="DocumentTotals"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="TaxPayable"/&gt;
     *           &lt;xs:element ref="NetTotal"/&gt;
     *           &lt;xs:element ref="GrossTotal"/&gt;
     *           &lt;!-- O conteudo desta estrutura Settlement representa o somatorio dos descontos reflectidos no elemento SettlementAmount das linhas do recibo. Trata-se de um raciocinio diverso da tabela 4.1 SalesInvoices --&gt;
     *           &lt;xs:element name="Settlement" minOccurs="0"&gt;
     *               &lt;xs:complexType&gt;
     *                   &lt;xs:sequence&gt;
     *                       &lt;xs:element ref="SettlementAmount"/&gt;
     *                   &lt;/xs:sequence&gt;
     *               &lt;/xs:complexType&gt;
     *           &lt;/xs:element&gt;
     *           &lt;xs:element name="Currency" type="Currency"
     *                       minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $eErrorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $eErrorRegister)
    {
        parent::__construct($eErrorRegister);
    }

    /**
     * Get settlement<br>
     * Agreements regarding payment discounts.
     * <pre>
     *  &lt;xs:element name="Settlement" minOccurs="0"&gt;
     *      &lt;xs:complexType&gt;
     *          &lt;xs:sequence&gt;
     *              &lt;xs:element ref="SettlementAmount"/&gt;
     *          &lt;/xs:sequence&gt;
     *      &lt;/xs:complexType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @return float|null
     * @since 1.0.0
     */
    public function getSettlementAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->settlementAmount === null ? "null" :
                    \strval($this->settlementAmount)
                )
            );
        return $this->settlementAmount;
    }

    /**
     * Set settlement<br>
     * Agreements regarding payment discounts.
     * <pre>
     *  &lt;xs:element name="Settlement" minOccurs="0"&gt;
     *      &lt;xs:complexType&gt;
     *          &lt;xs:sequence&gt;
     *              &lt;xs:element ref="SettlementAmount"/&gt;
     *          &lt;/xs:sequence&gt;
     *      &lt;/xs:complexType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param float|null $settlementAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSettlementAmount(?float $settlementAmount): bool
    {
        if ($settlementAmount !== null && $settlementAmount < 0) {
            $msg    = "Settlement Amount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("SettlementAmount_not_valid");
        } else {
            $return = true;
        }
        $this->settlementAmount = $settlementAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->settlementAmount === null ? "null" :
                    \strval($this->settlementAmount)
                )
            );
        return $return;
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== Payment::N_PAYMENT) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                Payment::N_PAYMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $docTotallsNode = parent::createXmlNode($node);

        if ($this->getSettlementAmount() !== null) {
            $docTotallsNode->addChild(static::N_SETTLEMENT)
                ->addChild(
                    static::N_SETTLEMENTAMOUNT,
                    $this->floatFormat($this->getSettlementAmount())
                );
        }

        $this->createCurrencyNode($docTotallsNode);

        return $docTotallsNode;
    }

    /**
     * Parse XML node
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        parent::parseXmlNode($node);

        if ($node->{static::N_SETTLEMENT}->count() > 0) {
            $settl = (float) $node->{static::N_SETTLEMENT}
                ->{static::N_SETTLEMENTAMOUNT};
            $this->setSettlementAmount($settl);
        }
    }
}
