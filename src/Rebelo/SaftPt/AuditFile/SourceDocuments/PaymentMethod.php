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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * PaymentMethod<br>
 * Indicate the payment method.
 * In case of mixed payments, the amounts should be mentioned by payment type and date.
 * If there is a need to make more than one reference,
 * this structure can be generated as many times as necessary.
 * <pre>
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
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTMETHOD = "PaymentMethod";

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
     * <br>
     * Indicate the payment method.
     * In case of mixed payments, the amounts should be mentioned by payment type and date.
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.
     * @param ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets PaymentMechanism<br>
     * The field shall be filled in with:<br>
     * “CC” - Credit card;<br>
     * “CD” - Debit card;<br>
     * “CH” - Bank cheque;<br>
     * “CI” – International Letter of Credit;<br>
     * “CO” - Gift cheque or gift card<br>
     * “CS” - Balance compensation in current account;<br>
     * “DE” - Electronic Money, for example, on fidelity or points cards;<br>
     * “LC” - Commercial Bill;<br>
     * “MB” - Payment references for ATM;<br>
     * “NU” – Cash;<br>
     * “OU” – Other means not mentioned;<br>
     * “PR” – Exchange of goods;<br>
     * “TB” – Banking transfer or authorized direct debit;<br>
     * “TR” - Non-wage compensation titles regardless of their support
     * [paper or digital format], for instance, meal or education vouchers, etc.<br>
     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism|null
     * @since 1.0.0
     */
    public function getPaymentMechanism(): ?PaymentMechanism
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->paymentMechanism === null ? "null" : $this->paymentMechanism->get()
                )
            );
        return $this->paymentMechanism;
    }

    /**
     * Sets PaymentMechanism<br>
     * PaymentMechanism<br>
     * The field shall be filled in with:<br>
     * “CC” - Credit card;<br>
     * “CD” - Debit card;<br>
     * “CH” - Bank cheque;<br>
     * “CI” – International Letter of Credit;<br>
     * “CO” - Gift cheque or gift card<br>
     * “CS” - Balance compensation in current account;<br>
     * “DE” - Electronic Money, for example, on fidelity or points cards;<br>
     * “LC” - Commercial Bill;<br>
     * “MB” - Payment references for ATM;<br>
     * “NU” – Cash;<br>
     * “OU” – Other means not mentioned;<br>
     * “PR” – Exchange of goods;<br>
     * “TB” – Banking transfer or authorized direct debit;<br>
     * “TR” - Non-wage compensation titles regardless of their support
     * [paper or digital format], for instance, meal or education vouchers, etc.<br>

     * &lt;xs:element ref="PaymentMechanism" minOccurs="0"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism|null $paymentMechanism
     * @return void
     * @since 1.0.0
     */
    public function setPaymentMechanism(?PaymentMechanism $paymentMechanism): void
    {
        $this->paymentMechanism = $paymentMechanism;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->paymentMechanism === null ? "null" : $this->paymentMechanism->get()
                )
            );
    }

    /**
     * Gets PaymentAmount<br>
     * Amount for each mean of payment.<br>
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentAmount(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->paymentAmount)
                )
            );
        return $this->paymentAmount;
    }

    /**
     * Sets a new paymentAmount<br>
     * Amount for each mean of payment.<br>
     * &lt;xs:element name="PaymentAmount" type="SAFmonetaryType"/&gt;
     * @param float $paymentAmount
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setPaymentAmount(float $paymentAmount): bool
    {
        if ($paymentAmount < 0.0) {
            $msg    = "PaymentAmount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("PaymentAmount_not_valid");
        } else {
            $return = true;
        }
        $this->paymentAmount = $paymentAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    \strval($this->paymentAmount)
                )
            );
        return $return;
    }

    /**
     * Gets PaymentDate<br>
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->paymentDate->format(RDate::SQL_DATE)
                )
            );
        return $this->paymentDate;
    }

    /**
     * Sets PaymentDate<br>
     * &lt;xs:element name="PaymentDate" type="SAFdateType"/&gt;
     * @param \Rebelo\Date\Date $paymentDate
     * @return void
     * @since 1.0.0
     */
    public function setPaymentDate(RDate $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->paymentDate->format(RDate::SQL_DATE)
                )
            );
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
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s",
                Payment::N_PAYMENT, ADocumentTotals::N_DOCUMENTTOTALS,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $nodePayMethod = $node->getName() === Payment::N_PAYMENT ?
            $node->addChild(static::N_PAYMENTMETHOD) :
            $nodePayMethod = $node->addChild(SalesInvoices\DocumentTotals::N_PAYMENT);


        if ($this->getPaymentMechanism() !== null) {
            $nodePayMethod->addChild(
                static::N_PAYMENTMECHANISM, $this->getPaymentMechanism()->get()
            );
        }

        if (isset($this->paymentAmount)) {
            $nodePayMethod->addChild(
                static::N_PAYMENTAMOUNT,
                $this->floatFormat($this->getPaymentAmount())
            );
        } else {
            $node->addChild(static::N_PAYMENTAMOUNT);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentAmount_not_valid");
        }

        if (isset($this->paymentDate)) {
            $nodePayMethod->addChild(
                static::N_PAYMENTDATE,
                $this->getPaymentDate()->format(RDate::SQL_DATE)
            );
        } else {
            $node->addChild(static::N_PAYMENTDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentDate_not_valid");
        }

        return $nodePayMethod;
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

        if (false === \in_array(
            $node->getName(),
            [static::N_PAYMENTMETHOD,
                    ADocumentTotals::N_DOCUMENTTOTALS,
            DocumentTotals::N_PAYMENT]
        )
        ) {
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s'",
                Payment::N_PAYMENTMETHOD, ADocumentTotals::N_DOCUMENTTOTALS,
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