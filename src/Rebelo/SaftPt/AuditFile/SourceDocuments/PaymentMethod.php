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

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * PaymentMethod
 * <pre>
 * <!-- Estrutura de pagamentos-->
 *  &lt;xs:complexType name="PaymentMethod"&gt;
 *      &lt;xs:sequence&gt;
 *          &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;
 *          &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;
 *          &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;
 *      &lt;/xs:sequence&gt;
 *  &lt;/xs:complexType&gt;
 * </pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class PaymentMethod extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;<br>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTMECHANISM = "PaymentMechanism";

    /**
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;<br>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTAMOUNT = "PaymentAmount";

    /**
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;<br>
     * Node Name
     * @since 1.0.0
     */
    const N_PAYMENTDATE = "PaymentDate";

    /**
     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;<br>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism|null $paymentMechanism
     * @since 1.0.0
     */
    private ?PaymentMechanism $paymentMechanism = null;

    /**
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;<br>
     * @var float $paymentAmount
     * @since 1.0.0
     */
    private float $paymentAmount;

    /**
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;<br>
     * @var \Rebelo\Date\Date $paymentDate
     * @since 1.0.0
     */
    private RDate $paymentDate;

    /**
     * Gets as paymentMechanism<br>
     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism|null
     * @since 1.0.0
     */
    public function getPaymentMechanism(): ?PaymentMechanism
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->paymentMechanism === null ? "null" : $this->paymentMechanism->get()));
        return $this->paymentMechanism;
    }

    /**
     * Sets a new paymentMechanism<br>
     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism|null $paymentMechanism
     * @return void
     * @since 1.0.0
     */
    public function setPaymentMechanism(?PaymentMechanism $paymentMechanism): void
    {
        $this->paymentMechanism = $paymentMechanism;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->paymentMechanism === null ? "null" : $this->paymentMechanism->get()));
    }

    /**
     * Gets as paymentAmount<br>
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;
     * @return float
     * @since 1.0.0
     */
    public function getPaymentAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->paymentAmount)));
        return $this->paymentAmount;
    }

    /**
     * Sets a new paymentAmount<br>
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;
     * @param float $paymentAmount
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setPaymentAmount(float $paymentAmount): void
    {
        if ($paymentAmount < 0.0) {
            $msg = "PaymentAmount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->paymentAmount = $paymentAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->paymentAmount)));
    }

    /**
     * Gets as paymentDate<br>
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getPaymentDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->paymentDate->format(RDate::SQL_DATE)));
        return $this->paymentDate;
    }

    /**
     * Sets a new paymentDate<br>
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;
     * @param \Rebelo\Date\Date $paymentDate
     * @return void
     * @since 1.0.0
     */
    public function setPaymentDate(RDate $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->paymentDate->format(RDate::SQL_DATE)));
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

        if ($node->getName() !== Payment::N_PAYMENT && $node->getName() !== ADocumentTotals::N_DOCUMENTTOTALS) {
            $msg = \sprintf("Node name should be '%s' or '%s' but is '%s",
                Payment::N_PAYMENT, ADocumentTotals::N_DOCUMENTTOTALS,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($this->getPaymentMechanism() !== null) {
            $node->addChild(
                static::N_PAYMENTMECHANISM, $this->getPaymentMechanism()->get());
        }
        $node->addChild(
            static::N_PAYMENTAMOUNT,
            $this->floatFormat($this->getPaymentAmount())
        );
        $node->addChild(
            static::N_PAYMENTDATE,
            $this->getPaymentDate()->format(RDate::SQL_DATE)
        );

        return $node;
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

        if ($node->getName() !== Payment::N_PAYMENT && $node->getName() !== ADocumentTotals::N_DOCUMENTTOTALS) {
            $msg = \sprintf("Node name should be '%s' or '%s' but is '%s'",
                Payment::N_PAYMENT, ADocumentTotals::N_DOCUMENTTOTALS,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_PAYMENTMECHANISM}->count() > 0) {
            $this->setPaymentMechanism(
                new PaymentMechanism((string) $node->{static::N_PAYMENTMECHANISM})
            );
        } else {
            $this->setPaymentMechanism(null);
        }

        $this->setPaymentAmount(
            (float) $node->{static::N_PAYMENTAMOUNT}
        );
        $this->setPaymentDate(
            RDate::parse(
                RDate::SQL_DATE, (string) $node->{static::N_PAYMENTDATE}
            )
        );
    }
}