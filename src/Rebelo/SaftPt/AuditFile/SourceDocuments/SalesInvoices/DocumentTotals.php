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
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;

/**
 * Invoice DocumentTotals
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentTotals
    extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals
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
     * <xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement[]
     * @since 1.0.0
     */
    private array $settlement = array();

    /**
     * <xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[]
     * @since 1.0.0
     */
    private array $payment = array();

    /**
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
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * Get Settlement stack<br>     *
     * <xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement[]
     * @since 1.0.0
     */
    public function getSettlement(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->settlement;
    }

    /**
     * Add Settlement to the stack<br>
     * <xs:element name="Settlement" type="Settlement" minOccurs="0" maxOccurs="unbounded"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement $settlement
     * @return int
     * @since 1.0.0
     */
    public function addToSettlement(Settlement $settlement): int
    {
        if (\count($this->settlement) === 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->settlement);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->settlement[$index] = $settlement;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset Settlement index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetSettlement(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->settlement[$index]);
    }

    /**
     * unset Settlement
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetSettlement(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->settlement[$index]);
    }

    /**
     * Get Pyment stack<br>
     * <xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[]
     * @since 1.0.0
     */
    public function getPayment(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->payment;
    }

    /**
     * Add a PaymentMethod to the Payment stack<br>
     * <xs:element name="Payment" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod $paymentMethod
     * @return int
     * @since 1.0.0
     */
    public function addToPayment(PaymentMethod $paymentMethod): int
    {
        if (\count($this->payment) === 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->payment);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->payment[$index] = $paymentMethod;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset PaymentMethod index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetPayment(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->payment[$index]);
    }

    /**
     * unset PaymentMethod
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetPayment(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->payment[$index]);
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

        if ($node->getName() !== Invoice::N_INVOICE)
        {
            $msg = sprintf("Node name should be '%s' but is '%s",
                           Invoice::N_INVOICE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $docTotNode = parent::createXmlNode($node);
        foreach ($this->getSettlement() as $settlement)
        {
            /* @var $settlement Settlement */
            $settlement->createXmlNode($docTotNode);
        }
        foreach ($this->getPayment() as $paymentMethod)
        {
            /* @var $paymentMethod PaymentMethod */
            $payNode = $docTotNode->addChild(static::N_PAYMENT);
            $paymentMethod->createXmlNode($payNode);
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

        if ($node->getName() !== static::N_DOCUMENTTOTALS)
        {
            $msg = sprintf("Node name should be '%s' but is '%s",
                           static::N_DOCUMENTTOTALS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        parent::parseXmlNode($node);

        if ($node->{static::N_PAYMENT}->count() > 0)
        {
            for ($n = 0; $n < $node->{static::N_PAYMENT}->count(); $n++)
            {
                $paymentMethod = new PaymentMethod();
                $paymentMethod->parseXmlNode($node->{static::N_PAYMENT}[$n]);
                $this->addToPayment($paymentMethod);
            }
        }

        if ($node->{static::N_SETTLEMENT}->count() > 0)
        {
            for ($n = 0; $n < $node->{static::N_SETTLEMENT}->count(); $n++)
            {
                $settlement = new Settlement();
                $settlement->parseXmlNode($node->{static::N_SETTLEMENT}[$n]);
                $this->addToSettlement($settlement);
            }
        }
    }

}
