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
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\Date\Date as RDate;

/**
 * Settlement
 *  <!-- Estrutura de Acordos entre cliente e fornecedor-->
 * <pre>
 *   &lt;xs:complexType name="Settlement"&gt;
 *       &lt;xs:sequence&gt;
 *           &lt;xs:element name="SettlementDiscount" type="SAFPTtextTypeMandatoryMax30Car"
 *               minOccurs="0"/&gt;
 *           &lt;xs:element name="SettlementAmount" type="SAFmonetaryType" minOccurs="0"/&gt;
 *           &lt;xs:element name="SettlementDate" type="SAFdateType" minOccurs="0"/&gt;
 *           &lt;xs:element name="PaymentTerms" type="SAFPTtextTypeMandatoryMax100Car" minOccurs="0"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
 * <pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class Settlement
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const N_SETTLEMENT = "Settlement";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const N_SETTLEMENTDISCOUNT = "SettlementDiscount";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const N_SETTLEMENTAMOUNT = "SettlementAmount";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const N_SETTLEMENTDATE = "SettlementDate";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const N_PAYMENTTERMS = "PaymentTerms";

    /**
     * &lt;xs:element name="SettlementDiscount" type="SAFPTtextTypeMandatoryMax30Car" minOccurs="0"/&gt;
     * @var string|null $settlementDiscount
     * @since 1.0.0
     */
    private ?string $settlementDiscount = null;

    /**
     * &lt;xs:element name="SettlementAmount" type="SAFmonetaryType" minOccurs="0"/&gt;
     * @var float|null $settlementAmount
     * @since 1.0.0
     */
    private ?float $settlementAmount = null;

    /**
     * &lt;xs:element name="SettlementDate" type="SAFdateType" minOccurs="0"/&gt;
     * @var \Rebelo\Date\Date|null $settlementDate
     * @since 1.0.0
     */
    private ?RDate $settlementDate = null;

    /**
     * &lt;xs:element name="PaymentTerms" type="SAFPTtextTypeMandatoryMax100Car" minOccurs="0"/&gt;
     * @var string|null $paymentTerms
     * @since 1.0.0
     */
    private ?string $paymentTerms = null;

    /**
     *  <!-- Estrutura de Acordos entre cliente e fornecedor--><br>
     * <pre>
     *   &lt;xs:complexType name="Settlement"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element name="SettlementDiscount" type="SAFPTtextTypeMandatoryMax30Car"
     *               minOccurs="0"/&gt;
     *           &lt;xs:element name="SettlementAmount" type="SAFmonetaryType" minOccurs="0"/&gt;
     *           &lt;xs:element name="SettlementDate" type="SAFdateType" minOccurs="0"/&gt;
     *           &lt;xs:element name="PaymentTerms" type="SAFPTtextTypeMandatoryMax100Car" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as settlementDiscount<br>
     * &lt;xs:element name="SettlementDiscount" type="SAFPTtextTypeMandatoryMax30Car" minOccurs="0"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getSettlementDiscount(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->settlementDiscount === null
                        ? "null"
                        : $this->settlementDiscount));
        return $this->settlementDiscount;
    }

    /**
     * Sets a new settlementDiscount<br>
     * &lt;xs:element name="SettlementDiscount" type="SAFPTtextTypeMandatoryMax30Car" minOccurs="0"/&gt;
     *
     * @param string|null $settlementDiscount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSettlementDiscount(?string $settlementDiscount): void
    {
        $this->settlementDiscount = $settlementDiscount === null
            ?
            $settlementDiscount
            :
            $this->valTextMandMaxCar($settlementDiscount, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->settlementDiscount === null
                        ? "null"
                        : $this->settlementDiscount));
    }

    /**
     * Gets as settlementAmount<br>
     * &lt;xs:element name="SettlementAmount" type="SAFmonetaryType" minOccurs="0"/&gt;
     * @return float|null
     * @since 1.0.0
     */
    public function getSettlementAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->settlementAmount === null
                        ? "null"
                        : \strval($this->settlementAmount)));

        return $this->settlementAmount;
    }

    /**
     * Sets a new settlementAmount<br>
     * &lt;xs:element name="SettlementAmount" type="SAFmonetaryType" minOccurs="0"/&gt;
     * @param float|null $settlementAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSettlementAmount(?float $settlementAmount): void
    {
        if ($settlementAmount < 0.0)
        {
            $msg = "SettlementAmount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->settlementAmount = $settlementAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->settlementAmount === null
                        ? "null"
                        : \strval($this->settlementAmount)));
    }

    /**
     * Gets as settlementDate<br>
     * &lt;xs:element name="SettlementDate" type="SAFdateType" minOccurs="0"/&gt;
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getSettlementDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->settlementDate === null
                        ? "null"
                        : $this->settlementDate->format(RDate::SQL_DATE)));

        return $this->settlementDate;
    }

    /**
     * Sets a new settlementDate<br>
     * &lt;xs:element name="SettlementDate" type="SAFdateType" minOccurs="0"/&gt;
     * @param \Rebelo\Date\Date|null $settlementDate
     * @return void
     * @since 1.0.0
     */
    public function setSettlementDate(?RDate $settlementDate): void
    {
        $this->settlementDate = $settlementDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->settlementDate === null
                        ? "null"
                        : $this->settlementDate->format(RDate::SQL_DATE)));
    }

    /**
     * Gets as paymentTerms<br>
     * &lt;xs:element name="PaymentTerms" type="SAFPTtextTypeMandatoryMax100Car" minOccurs="0"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getPaymentTerms(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->paymentTerms === null
                        ? "null"
                        : $this->paymentTerms));

        return $this->paymentTerms;
    }

    /**
     * Sets a new paymentTerms<br>
     * &lt;xs:element name="PaymentTerms" type="SAFPTtextTypeMandatoryMax100Car" minOccurs="0"/&gt;
     * @param string|null $paymentTerms
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setPaymentTerms(?string $paymentTerms): void
    {
        $this->paymentTerms = $paymentTerms === null
            ?
            $paymentTerms
            :
            $this->valTextMandMaxCar($paymentTerms, 100, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->paymentTerms === null
                        ? "null"
                        : $this->paymentTerms));
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== ADocumentTotals::N_DOCUMENTTOTALS)
        {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                            ADocumentTotals::N_DOCUMENTTOTALS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $settlNode = $node->addChild(static::N_SETTLEMENT);
        if ($this->getSettlementDiscount() !== null)
        {
            $settlNode->addChild(static::N_SETTLEMENTDISCOUNT,
                                 $this->getSettlementDiscount());
        }
        if ($this->getSettlementAmount() !== null)
        {
            $settlNode->addChild(static::N_SETTLEMENTAMOUNT,
                                 $this->floatFormat($this->getSettlementAmount()));
        }
        if ($this->getSettlementDate() !== null)
        {
            $settlNode->addChild(static::N_SETTLEMENTDATE,
                                 $this->getSettlementDate()->format(RDate::SQL_DATE));
        }
        if ($this->getPaymentTerms() !== null)
        {
            $settlNode->addChild(static::N_PAYMENTTERMS,
                                 $this->getPaymentTerms());
        }
        return $settlNode;
    }

    /**
     * Parse the XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SETTLEMENT)
        {
            $msg = sprintf("Node name should be '%s' but is '%s",
                           static::N_SETTLEMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setSettlementDiscount(
            $node->{static::N_SETTLEMENTDISCOUNT}->count() > 0
                ?
                (string) $node->{static::N_SETTLEMENTDISCOUNT}
                :
                null
        );

        $this->setSettlementAmount(
            $node->{static::N_SETTLEMENTAMOUNT}->count() > 0
                ?
                (float) $node->{static::N_SETTLEMENTAMOUNT}
                :
                null
        );

        $this->setSettlementDate(
            $node->{static::N_SETTLEMENTDATE}->count() > 0
                ?
                RDate::parse(RDate::SQL_DATE,
                             (string) $node->{static::N_SETTLEMENTDATE})
                :
                null
        );

        $this->setPaymentTerms(
            $node->{static::N_PAYMENTTERMS}->count() > 0
                ?
                (string) $node->{static::N_PAYMENTTERMS}
                :
                null
        );
    }

}
