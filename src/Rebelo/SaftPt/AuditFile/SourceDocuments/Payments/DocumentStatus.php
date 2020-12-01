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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
     * &lt;xs:element ref="PaymentStatus"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus
     * @since 1.0.0
     */
    private PaymentStatus $paymentStatus;

    /**
     * <pre>
     * &lt;xs:element ref="PaymentStatusDate"/&gt;
     * &lt;xs:simpleType name="SAFdateTimeType">
     *   &lt;xs:restriction base="xs:dateTime"/&gt;
     *  &lt;/xs:simpleType/&gt;
     * </pre>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $paymentStatusDate;

    /**
     * Reason<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * SourceID
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get PaymentStatus<br>
     * To fill in with:<br>
     * “N” – Normal receipt in force;<br>
     * “A” – Cancelled receipt.<br>
     * &lt;xs:element ref="PaymentStatus"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentStatus(): PaymentStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->paymentStatus->get()
                )
            );
        return $this->paymentStatus;
    }

    /**
     * Get if is set PaymentStatus
     * @return bool
     * @since 1.0.0
     */
    public function issetPaymentStatus(): bool
    {
        return isset($this->paymentStatus);
    }

    /**
     * Set PaymentStatus<br><br>
     * To fill in with:<br>
     * “N” – Normal receipt in force;<br>
     * “A” – Cancelled receipt.<br>
     * &lt;xs:element ref="PaymentStatus"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus $paymentStatus
     * @return void
     * @since 1.0.0
     */
    public function setPaymentStatus(PaymentStatus $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->paymentStatus->get()
                )
            );
    }

    /**
     * Get PaymentStatusDate<br>
     * Date of the last record of the receipt status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.
     * <pre>
     * &lt;xs:element ref="PaymentStatusDate"/&gt;
     * &lt;xs:simpleType name="SAFdateTimeType">
     *   &lt;xs:restriction base="xs:dateTime"/&gt;
     *  &lt;/xs:simpleType/&gt;
     * </pre>
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->paymentStatusDate->format(RDate::DATE_T_TIME)
                )
            );
        return $this->paymentStatusDate;
    }

    /**
     * Get if is set PaymentStatusDate
     * @return bool
     * @since 1.0.0
     */
    public function issetPaymentStatusDate(): bool
    {
        return isset($this->paymentStatusDate);
    }

    /**
     * Set PaymentStatusDate<br>
     * Date of the last record of the receipt status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.
     * <pre>
     * &lt;xs:element ref="PaymentStatusDate"/&gt;
     * &lt;xs:simpleType name="SAFdateTimeType">
     *   &lt;xs:restriction base="xs:dateTime"/&gt;
     *  &lt;/xs:simpleType/&gt;
     * </pre>
     * @param \Rebelo\Date\Date $paymentStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setPaymentStatusDate(RDate $paymentStatusDate): void
    {
        $this->paymentStatusDate = $paymentStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->paymentStatusDate->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get reason<br>
     * Reason for changing the receipt status.<br>
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason
                )
            );
        return $this->reason;
    }

    /**
     * Set reason<br>
     * Reason for changing the receipt status.<br>
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @param string|null $reason
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setReason(?string $reason): bool
    {
        try {
            $this->reason = $reason === null ? null :
                $this->valTextMandMaxCar($reason, 50, __METHOD__);
            $return       = true;
        } catch (AUditFileException $e) {
            $this->reason = $reason;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return       = false;
            $this->getErrorRegistor()->addOnSetValue("Reason_not_valid");
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->reason === null ? "null" : $this->reason
                )
            );
        return $return;
    }

    /**
     * Get SourceID<br>
     * User responsible for the current receipt status.<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->sourceID));
        return $this->sourceID;
    }

    /**
     * Get if is set SourceID
     * @return bool
     * @since 1.0.0
     */
    public function issetSourceID(): bool
    {
        return isset($this->sourceID);
    }

    /**
     * Set SourceID<br>
     * User responsible for the current receipt status.<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @param string $sourceID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): bool
    {
        try {
            $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
            $return         = true;
        } catch (AuditFileException $e) {
            $this->sourceID = $sourceID;
            $return         = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Reason_not_valid");
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->sourceID));
        return $return;
    }

    /**
     * Get SourcePayment<br>
     * To be filled in with:<br>
     * “P” – Receipt created in the application;<br>
     * “I” – Receipt integrated and produced in a different application;<br>
     * “M” – Recovered or manually issued receipt.<br>
     * <pre>
     * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;     *
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="P"/&gt;
     *       &lt;xs:enumeration value="I"/&gt;
     *       &lt;xs:enumeration value="M"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourcePayment(): SourcePayment
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->sourcePayment->get()
                )
            );
        return $this->sourcePayment;
    }

    /**
     * Get if is set SourcePayment
     * @return bool
     * @since 1.0.0
     */
    public function issetSourcePayment(): bool
    {
        return isset($this->sourcePayment);
    }

    /**
     * Set SourcePayment<br>
     * To be filled in with:<br>
     * “P” – Receipt created in the application;<br>
     * “I” – Receipt integrated and produced in a different application;<br>
     * “M” – Recovered or manually issued receipt.<br>
     * <pre>
     * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;
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
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->sourcePayment->get()
                )
            );
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

        $docStatusNode = $node->addChild(static::N_DOCUMENTSTATUS);

        if (isset($this->paymentStatus)) {
            $docStatusNode->addChild(
                static::N_PAYMENTSTATUS, $this->getPaymentStatus()->get()
            );
        } else {
            $docStatusNode->addChild(static::N_PAYMENTSTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentStatus_not_valid");
        }

        if (isset($this->paymentStatusDate)) {
            $docStatusNode->addChild(
                static::N_PAYMENTSTATUSDATE,
                $this->getPaymentStatusDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $docStatusNode->addChild(static::N_PAYMENTSTATUSDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentStatusDate_not_valid");
        }

        if ($this->getReason() !== null) {
            $docStatusNode->addChild(
                static::N_REASON, $this->getReason()
            );
        }

        if (isset($this->sourceID)) {
            $docStatusNode->addChild(
                static::N_SOURCEID, $this->getSourceID()
            );
        } else {
            $docStatusNode->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if (isset($this->sourcePayment)) {
            $docStatusNode->addChild(
                static::N_SOURCEPAYMENT, $this->getSourcePayment()->get()
            );
        } else {
            $docStatusNode->addChild(static::N_SOURCEPAYMENT);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourcePayment_not_valid");
        }

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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_DOCUMENTSTATUS, $node->getName()
            );
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

        $this->setSourcePayment(
            new SourcePayment((string) $node->{static::N_SOURCEPAYMENT})
        );
    }
}