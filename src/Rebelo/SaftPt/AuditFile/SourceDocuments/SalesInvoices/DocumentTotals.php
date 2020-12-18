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

use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;

/**
 * Invoice DocumentTotals
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotals extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals
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
    const N_PAYMENT = "Payment";

    /**
     * &lt;xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement[]
     * @since 1.0.0
     */
    private array $settlement = array();

    /**
     * &lt;xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[]
     * @since 1.0.0
     */
    private array $payment = array();

    /**
     * DocumentTotals
     * <pre>
     * &lt;xs:element name="DocumentTotals"&gt;
     *    &lt;xs:complexType&gt;
     *        &lt;xs:sequence&gt;
     *          &lt;xs:element ref="TaxPayable"/&gt;
     *          &lt;xs:element ref="NetTotal"/&gt;
     *          &lt;xs:element ref="GrossTotal"/&gt;
     *          &lt;xs:element name="Currency" type="Currency" minOccurs="0"/&gt;
     *          &lt;!-- A estrutura Settlement representa acordos ou formas de pagamento futuros. Nao constitui em caso algum o somatorio dos descontos concedidos e reflectidos nas linhas dos documentos e a informacao aqui constante nao influi o montante total do documento (GrossTotal) --&gt;
     *          &lt;xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/&gt;
     *          &lt;xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     *        &lt;/xs:sequence&gt;
     *    &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get Settlement stack<br>
     * Agreements or payment methods.<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.     *
     * &lt;xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement[]
     * @since 1.0.0
     */
    public function getSettlement(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->settlement;
    }

    /**
     * Add Settlement to the stack<br>
     * When this method is invoked a new Settlement is created
     * add to the stack and returned to e populate<br>
     * &lt;xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement
     * @since 1.0.0
     */
    public function addSettlement(): Settlement
    {
        $settlement         = new Settlement($this->getErrorRegistor());
        $this->settlement[] = $settlement;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." add to stack");
        return $settlement;
    }

    /**
     * Get Pyment stack<br>
     * Payment method used. In case of mixed payments,
     * the amounts shall be indicated by type of mean of payment and date of payment.
     * If there is a need to make more than one reference, this structure can
     * be generated as many times as necessary.<br>
     * &lt;xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[]
     * @since 1.0.0
     */
    public function getPayment(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->payment;
    }

    /**
     * Add a PaymentMethod to the Payment stack<br>
     * When this method is invoked a new Instance of PaymentMethod is created,
     * add to the Payment stack and returned to be populated<br>
     * Payment method used. In case of mixed payments,
     * the amounts shall be indicated by type of mean of payment and date of payment.
     * If there is a need to make more than one reference, this structure can
     * be generated as many times as necessary.<br>
     * &lt;xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod
     * @since 1.0.0
     */
    public function addPayment(): PaymentMethod
    {
        $paymentMethod   = new PaymentMethod($this->getErrorRegistor());
        $this->payment[] = $paymentMethod;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." add to stack");
        return $paymentMethod;
    }

    /**
     * Create the XML node
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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                Invoice::N_INVOICE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $docTotNode = parent::createXmlNode($node);

        foreach ($this->getSettlement() as $settlement) {
            /* @var $settlement Settlement */
            $settlement->createXmlNode($docTotNode);
        }

        foreach ($this->getPayment() as $paymentMethod) {
            /* @var $paymentMethod PaymentMethod */
            $paymentMethod->createXmlNode($docTotNode);
        }

        return $docTotNode;
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

        parent::parseXmlNode($node);

        if ($node->{static::N_PAYMENT}->count() > 0) {
            for ($n = 0; $n < $node->{static::N_PAYMENT}->count(); $n++) {
                $this->addPayment()->parseXmlNode(
                    $node->{static::N_PAYMENT}[$n]
                );
            }
        }

        if ($node->{static::N_SETTLEMENT}->count() > 0) {
            for ($n = 0; $n < $node->{static::N_SETTLEMENT}->count(); $n++) {
                $this->addSettlement()->parseXmlNode(
                    $node->{static::N_SETTLEMENT}[$n]
                );
            }
        }
    }
}
