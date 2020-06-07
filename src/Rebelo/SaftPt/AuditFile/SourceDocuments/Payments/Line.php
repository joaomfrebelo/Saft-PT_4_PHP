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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;

/**
 * Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Line extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ALine
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEDOCUMENTID = "SourceDocumentID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     * SourceDocumentID, must have at least one element
     * <xs:element name="SourceDocumentID" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID[]
     * @since 1.0.0
     */
    private array $sourceDocumentID = array();

    /**
     * <xs:element name="Tax" type="PaymentTax" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax|null
     * @since 1.0.0
     */
    private ?Tax $tax = null;

    /**
     * <pre>
     * &lt;xs:element name="Line" maxOccurs="unbounded"&gt;
     *           &lt;xs:complexType&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element ref="LineNumber"/&gt;
     *        &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded"&gt;
     *            &lt;xs:complexType&gt;
     *                &lt;xs:sequence&gt;
     *                    &lt;xs:element ref="OriginatingON"/&gt;
     *                    &lt;xs:element ref="InvoiceDate"/&gt;
     *                    &lt;xs:element ref="Description" minOccurs="0"/&gt;
     *                &lt;/xs:sequence&gt;
     *            &lt;/xs:complexType&gt;
     *        &lt;/xs:element&gt;
     *        &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     *        &lt;xs:choice&gt;
     *            &lt;xs:element ref="DebitAmount"/&gt;
     *            &lt;xs:element ref="CreditAmount"/&gt;
     *        &lt;/xs:choice&gt;
     *        &lt;xs:element name="Tax" type="PaymentTax" minOccurs="0"/&gt;
     *        &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;
     *        &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     *    &lt;/xs:sequence&gt;
     *           &lt;/xs:complexType&gt;
     *   &lt;/xs:element&gt;
     *  </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get SourceDocumentID stack<br>
     * <xs:element name="SourceDocumentID" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID[]
     * @since 1.0.0
     */
    public function getSourceDocumentID(): array
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->sourceDocumentID;
    }

    /**
     * Add SourceDocumentID to the stack<br>
     * <xs:element name="SourceDocumentID" maxOccurs="unbounded">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID $sourceDocumentID
     * @return int
     * @since 1.0.0
     */
    public function addToSourceDocumentID(SourceDocumentID $sourceDocumentID): int
    {
        if (\count($this->sourceDocumentID) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->sourceDocumentID);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->sourceDocumentID[$index] = $sourceDocumentID;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " SourceDocumentID add to index ".\strval($index));
        return $index;
    }

    /**
     * isset sourceDocumentID<br>
     * <xs:element name="SourceDocumentID" maxOccurs="unbounded">
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetSourceDocumentID(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->sourceDocumentID[$index]);
    }

    /**
     * unset sourceDocumentID<br>
     * <xs:element name="SourceDocumentID" maxOccurs="unbounded">
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetSourceDocumentID(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->sourceDocumentID[$index]);
    }

    /**
     * Get Tax<br>
     * <xs:element name="Tax" type="PaymentTax" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax|null
     * @since 1.0.0
     */
    public function getTax(): ?Tax
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->tax;
    }

    /**
     * Set Tax<br>
     * <xs:element name="Tax" type="PaymentTax" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax|null $tax
     * @return void
     * @since 1.0.0
     */
    public function setTax(?Tax $tax): void
    {
        $this->tax = $tax;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->tax === null ? "null" : "Tax"));
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
            $msg = \sprintf("Node name should be '%s' but is '%s",
                Payment::N_PAYMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $lineNode = parent::createXmlNode($node);

        if (\count($this->getSourceDocumentID()) === 0) {
            throw new AuditFileException("SourceDocumentID stack can not be empty, "
                ."must have at least one element");
        }

        foreach ($this->getSourceDocumentID() as $sourceDocumentID) {
            /* @var $sourceDocumentID SourceDocumentID */
            $sourceDocumentID->createXmlNode($lineNode);
        }

        if ($this->getSettlementAmount() !== null) {
            $lineNode->addChild(static::N_SETTLEMENTAMOUNT,
                \strval($this->getSettlementAmount()));
        }

        $this->createXmlNodeDebitCreditNode($lineNode);

        if ($this->getTax() !== null) {
            $this->getTax()->createXmlNode($lineNode);
        }

        if ($this->getTaxExemptionReason() !== null) {
            $lineNode->addChild(static::N_TAXEXEMPTIONREASON,
                $this->getTaxExemptionReason());
        }

        if ($this->getTaxExemptionCode() !== null) {
            $lineNode->addChild(static::N_TAXEXEMPTIONCODE,
                $this->getTaxExemptionCode()->get());
        }
        return $lineNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        parent::parseXmlNode($node);

        $sourceCount = $node->{static::N_SOURCEDOCUMENTID}->count();
        if ($sourceCount === 0) {
            throw new AuditFileException("SourceDocumentID must have at least one node");
        }

        for ($n = 0; $n < $sourceCount; $n++) {
            $source = new SourceDocumentID();
            $source->parseXmlNode($node->{static::N_SOURCEDOCUMENTID}[$n]);
            $this->addToSourceDocumentID($source);
        }

        if ($node->{static::N_SETTLEMENTAMOUNT}->count() > 0) {
            $this->setSettlementAmount((float) $node->{static::N_SETTLEMENTAMOUNT});
        }

        if ($node->{static::N_TAX}->count() > 0) {
            $tax = new Tax();
            $tax->parseXmlNode($node->{static::N_TAX});
            $this->setTax($tax);
        }

        if ($node->{static::N_TAXEXEMPTIONREASON}->count() > 0) {
            $this->setTaxExemptionReason((string) $node->{static::N_TAXEXEMPTIONREASON});
        }

        if ($node->{static::N_TAXEXEMPTIONCODE}->count() > 0) {
            $taxCode = new TaxExemptionCode((string) $node->{static::N_TAXEXEMPTIONCODE});
            $this->setTaxExemptionCode($taxCode);
        }
    }
}