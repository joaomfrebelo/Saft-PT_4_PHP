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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
     * &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID[]
     * @since 1.0.0
     */
    private array $sourceDocumentID = array();

    /**
     * &lt;xs:element name="Tax" type="PaymentTax" minOccurs="0"/&gt;
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get SourceDocumentID stack<br>
     * If there is a need to make more than one reference, this structure can
     * be generated as many times as necessary. In case of integrated accounting and invoicing program,
     * the numbering structure of the source field shall be used.<br>
     * &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded"&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID[]
     * @since 1.0.0
     */
    public function getSourceDocumentID(): array
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->sourceDocumentID;
    }

    /**
     * Add SourceDocumentID to the stack<br
     * If there is a need to make more than one reference, this structure can
     * be generated as many times as necessary. In case of integrated accounting and invoicing program,
     * the numbering structure of the source field shall be used.<br>
     * When this method is invoked a new instance of SourceDocumentID is
     * created, add to the stack then returned to be populated
     * &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded"&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID
     * @since 1.0.0
     */
    public function addSourceDocumentID(): SourceDocumentID
    {
        $sourceDocumentID         = new SourceDocumentID($this->getErrorRegistor());
        $this->sourceDocumentID[] = $sourceDocumentID;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." SourceDocumentID add to index "
        );
        return $sourceDocumentID;
    }

    /**
     * Get Tax<br>
     * On the receipts of the VAT cash regime a line should be mentioned for
     * each different VAT rate on the correspondent invoice.
     * This complex type element shall also be generated for any other type
     * of receipts containing taxes described in field 4.4.4.14.6.1. - TaxType<br>
     * &lt;xs:element name="Tax" type="PaymentTax" minOccurs="0"/&gt;     *
     * @param bool $create If true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax|null
     * @since 1.0.0
     */
    public function getTax(bool $create = true): ?Tax
    {
        if ($create && $this->tax === null) {
            $this->tax = new Tax($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->tax;
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

        $lineNode = parent::createXmlNode($node);

        if (\count($this->getSourceDocumentID()) === 0) {
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceDocumentID_without_elements");
        }

        foreach ($this->getSourceDocumentID() as $sourceDocumentID) {
            /* @var $sourceDocumentID SourceDocumentID */
            $sourceDocumentID->createXmlNode($lineNode);
        }

        if ($this->getSettlementAmount() !== null) {
            $lineNode->addChild(
                static::N_SETTLEMENTAMOUNT,
                \strval($this->getSettlementAmount())
            );
        }

        $this->createXmlNodeDebitCreditNode($lineNode);

        if ($this->getTax(false) !== null) {
            $this->getTax()->createXmlNode($lineNode);
        }

        if ($this->getTaxExemptionReason() !== null) {
            $lineNode->addChild(
                static::N_TAXEXEMPTIONREASON,
                $this->getTaxExemptionReason()
            );
        }

        if ($this->getTaxExemptionCode() !== null) {
            $lineNode->addChild(
                static::N_TAXEXEMPTIONCODE,
                $this->getTaxExemptionCode()->get()
            );
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
            $this->addSourceDocumentID()->parseXmlNode(
                $node->{static::N_SOURCEDOCUMENTID}[$n]
            );
        }

        if ($node->{static::N_SETTLEMENTAMOUNT}->count() > 0) {
            $this->setSettlementAmount((float) $node->{static::N_SETTLEMENTAMOUNT});
        }

        if ($node->{static::N_TAX}->count() > 0) {
            $this->getTax()->parseXmlNode($node->{static::N_TAX});
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
