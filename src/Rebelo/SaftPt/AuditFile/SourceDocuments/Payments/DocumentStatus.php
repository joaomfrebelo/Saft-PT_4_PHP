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

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;

/**
 * DocumentStatus
 * <pre>
 * &lt;xs:element name="DocumentStatus"&gt;
 *  &lt;xs:complexType&gt;
 *      &lt;xs:sequence&gt;
 *          &lt;xs:element ref="PaymentStatus"/&gt;
 *          &lt;xs:element ref="PaymentStatusDate"/&gt;
 *          &lt;xs:element ref="Reason" minOccurs="0"/&gt;
 *          &lt;xs:element ref="SourceID"/&gt;
 *          &lt;xs:element name="SourcePayment" type="SAFTPTSourcePayment"/&gt;
 *      &lt;/xs:sequence&gt;
 *  &lt;/xs:complexType&gt;
 * &lt;/xs:element&gt;
 * </pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentStatus extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const N_DOCUMENTSTATUS = "DocumentStatus";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_PAYMENTSTATUS = "PaymentStatus";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_PAYMENTSTATUSDATE = "PaymentStatusDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_REASON = "Reason";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SOURCEID = "SourceID";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SOURCEPAYMENT = "SourcePayment";

    /**
     * <xs:element ref="PaymentStatus"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus
     * @since 1.0.0
     */
    private PaymentStatus $paymentStatus;

    /**
     * <pre>
     * <xs:element ref="PaymentStatusDate"/>
     * <xs:simpleType name="SAFdateTimeType">
     *   <xs:restriction base="xs:dateTime"/>
     *  </xs:simpleType>
     * </pre>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $paymentStatusDate;

    /**
     * Reason<br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * SourceID
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @var string
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * SourcePayment
     * <pre>
     * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt;P para documento produzido na aplicacao, I para documento integrado e
     *           produzido noutra aplicacao, M para documento proveniente de recuperacao ou de
     *           emissao manual &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="P"/&gt;
     *       &lt;xs:enumeration value="I"/&gt;
     *       &lt;xs:enumeration value="M"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment
     * @since 1.0.0
     */
    private SourcePayment $sourcePayment;

    /**
     * DocumentStatus
     * <pre>
     * &lt;xs:element name="DocumentStatus"&gt;
     *  &lt;xs:complexType&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="PaymentStatus"/&gt;
     *          &lt;xs:element ref="PaymentStatusDate"/&gt;
     *          &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *          &lt;xs:element ref="SourceID"/&gt;
     *          &lt;xs:element name="SourcePayment" type="SAFTPTSourcePayment"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get PaymentStatus<br>
     * <xs:element ref="PaymentStatus"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus
     * @since 1.0.0
     */
    public function getPaymentStatus(): PaymentStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->paymentStatus->get()));
        return $this->paymentStatus;
    }

    /**
     * Set PaymentStatus<br>
     * <xs:element ref="PaymentStatus"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus $paymentStatus
     * @return void
     * @since 1.0.0
     */
    public function setPaymentStatus(PaymentStatus $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->paymentStatus->get()));
    }

    /**
     * Get PaymentStatusDate
     * <pre>
     * <xs:element ref="PaymentStatusDate"/>
     * <xs:simpleType name="SAFdateTimeType">
     *   <xs:restriction base="xs:dateTime"/>
     *  </xs:simpleType>
     * </pre>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getPaymentStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->paymentStatusDate->format(RDate::DATE_T_TIME)));
        return $this->paymentStatusDate;
    }

    /**
     * Set PaymentStatusDate
     * <pre>
     * <xs:element ref="PaymentStatusDate"/>
     * <xs:simpleType name="SAFdateTimeType">
     *   <xs:restriction base="xs:dateTime"/>
     *  </xs:simpleType>
     * </pre>
     * @param \Rebelo\Date\Date $paymentStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setPaymentStatusDate(RDate $paymentStatusDate): void
    {
        $this->paymentStatusDate = $paymentStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->paymentStatusDate->format(RDate::DATE_T_TIME)));
    }

    /**
     * Get reason
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason));
        return $this->reason;
    }

    /**
     * Set reason<br>
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @param string|null $reason
     * @return void
     * @since 1.0.0
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason === null ? null :
            $this->valTextMandMaxCar($reason, 50, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->reason === null ? "null" : $this->reason));
    }

    /**
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->sourceID));
        return $this->sourceID;
    }

    /**
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @param string $sourceID
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): void
    {
        $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->sourceID));
    }

    /**
     * Get SourcePayment
     * <pre>
     * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt;P para documento produzido na aplicacao, I para documento integrado e
     *           produzido noutra aplicacao, M para documento proveniente de recuperacao ou de
     *           emissao manual &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="P"/&gt;
     *       &lt;xs:enumeration value="I"/&gt;
     *       &lt;xs:enumeration value="M"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment
     * @since 1.0.0
     */
    public function getSourcePayment(): SourcePayment
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->sourcePayment->get()));
        return $this->sourcePayment;
    }

    /**
     * Set SourcePayment
     * <pre>
     * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt;P para documento produzido na aplicacao, I para documento integrado e
     *           produzido noutra aplicacao, M para documento proveniente de recuperacao ou de
     *           emissao manual &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="P"/&gt;
     *       &lt;xs:enumeration value="I"/&gt;
     *       &lt;xs:enumeration value="M"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment $sourcePayment
     * @return void
     * @since 1.0.0
     */
    public function setSourcePayment(SourcePayment $sourcePayment): void
    {
        $this->sourcePayment = $sourcePayment;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->sourcePayment->get()));
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
                Payment::N_PAYMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $docStatusNode = $node->addChild(static::N_DOCUMENTSTATUS);

        $docStatusNode->addChild(
            static::N_PAYMENTSTATUS, $this->getPaymentStatus()->get()
        );

        $docStatusNode->addChild(
            static::N_PAYMENTSTATUSDATE,
            $this->getPaymentStatusDate()->format(RDate::DATE_T_TIME)
        );

        if ($this->getReason() !== null) {
            $docStatusNode->addChild(
                static::N_REASON, $this->getReason()
            );
        }

        $docStatusNode->addChild(
            static::N_SOURCEID, $this->getSourceID()
        );

        $docStatusNode->addChild(
            static::N_SOURCEPAYMENT, $this->getSourcePayment()->get()
        );

        return $docStatusNode;
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

        if ($node->getName() !== static::N_DOCUMENTSTATUS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_DOCUMENTSTATUS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $payStatus = new PaymentStatus((string) $node->{static::N_PAYMENTSTATUS});
        $this->setPaymentStatus($payStatus);

        $this->setPaymentStatusDate(
            RDate::parse(
                RDate::DATE_T_TIME,
                (string) $node->{static::N_PAYMENTSTATUSDATE}
            )
        );

        if ($node->{static::N_REASON}->count() > 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }

        $this->setSourceID((string) $node->{static::N_SOURCEID});

        $sourcePay = new SourcePayment((string) $node->{static::N_SOURCEPAYMENT});
        $this->setSourcePayment($sourcePay);
    }
}