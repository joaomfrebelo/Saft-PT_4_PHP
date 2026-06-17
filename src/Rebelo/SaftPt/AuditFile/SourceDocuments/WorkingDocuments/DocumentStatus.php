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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;

/**
 * DocumentStatus
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class DocumentStatus extends AAuditFile
{
    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_DOCUMENT_STATUS = "DocumentStatus";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_WORK_STATUS = "WorkStatus";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_WORK_STATUS_DATE = "WorkStatusDate";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_REASON = "Reason";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_SOURCE_ID = "SourceID";

    /**
     * Node name
     *
     * @since 1.0.0
     */
    const string N_SOURCE_BILLING = "SourceBilling";

    /**
     * &lt;xs:element ref="WorkStatus"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @since 1.0.0
     */
    private WorkStatus $workStatus;

    /**
     * &lt;xs:element ref="WorkStatusDate"/&gt;<br>
     * &lt;xs:element name="WorkStatusDate" type="SAFdateTimeType"/&gt;
     *
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $workStatusDate;

    /**
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     *
     * @var string|null
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @var string
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @since 1.0.0
     */
    private SourceBilling $sourceBilling;

    /**
     * <pre>
     * &lt;xs:element name="DocumentStatus"&gt;
     *  &lt;xs:complexType&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="WorkStatus"/&gt;
     *          &lt;xs:element ref="WorkStatusDate"/&gt;
     *          &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *          &lt;xs:element ref="SourceID"/&gt;
     *          &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     *
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     *
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get work status<br>
     * &lt;xs:element ref="WorkStatus"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkStatus(): WorkStatus
    {
        AAuditFile::$logger?->info(\sprintf(__METHOD__ . " get '%s'", $this->workStatus->value));
        return $this->workStatus;
    }

    /**
     * Get if is set WorkStatus
     *
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkStatus(): bool
    {
        return isset($this->workStatus);
    }

    /**
     * Set the work status<br>
     * &lt;xs:element ref="WorkStatus"/&gt;
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus $workStatus
     *
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatus(WorkStatus $workStatus): void
    {
        $this->workStatus = $workStatus;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                $this->workStatus->value
            )
        );
    }

    /**
     * Get WorkStatusDate (DateTime)<br>
     * Date of the last storage of the document status to the second.<br>
     * &lt;xs:element ref="WorkStatusDate"/&gt;<br>
     * &lt;xs:element name="WorkStatusDate" type="SAFdateTimeType"/&gt;
     *
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getWorkStatusDate(): RDate
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__ . " get '%s'",
                $this->workStatusDate->format(Pattern::DATE_T_TIME)
            )
        );
        return $this->workStatusDate;
    }

    /**
     * Get if is set WorkStatusDate
     *
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkStatusDate(): bool
    {
        return isset($this->workStatusDate);
    }

    /**
     * Set WorkStatusDate (DateTime)<br>
     * Date of the last storage of the document status to the second.<br>
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.
     * &lt;xs:element ref="WorkStatusDate"/&gt;<br>
     * &lt;xs:element name="WorkStatusDate" type="SAFdateTimeType"/&gt;
     *
     * @param \Rebelo\Date\Date $workStatusDate
     *
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatusDate(RDate $workStatusDate): void
    {
        $this->workStatusDate = $workStatusDate;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                $this->workStatusDate->format(Pattern::DATE_T_TIME)
            )
        );
    }

    /**
     * Get Reason<br>
     * The reason leading to the change in the document status shall be presented.<br>
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__ . " get '%s'",
                $this->reason === null ? "null" : $this->reason
            )
        );
        return $this->reason;
    }

    /**
     * Set Reason<br>
     * The reason leading to the change in the document status shall be presented.<br>
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     *
     * @param string|null $reason
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setReason(?string $reason): bool
    {
        try {
            $this->reason = $reason === null ? null :
                $this->valTextMandatoryMaxCar($reason, 50, __METHOD__);
            $return       = true;
        } catch (AuditFileException $e) {
            $this->reason = $reason;
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . "  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Reason_not_valid");
            $return = false;
        }
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                $this->reason === null ? "null" : $this->reason
            )
        );
        return $return;
    }

    /**
     * Set SourceID<br>
     * User responsible for the current document status.<br>
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        AAuditFile::$logger?->info(
            \sprintf(__METHOD__ . " get '%s'", $this->sourceID)
        );
        return $this->sourceID;
    }

    /**
     * Get if is set SourceID
     *
     * @return bool
     * @since 1.0.0
     */
    public function issetSourceID(): bool
    {
        return isset($this->sourceID);
    }

    /**
     * Get SourceID<br>
     * User responsible for the current document status.<br>
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @param string $sourceID
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): bool
    {
        try {
            $this->sourceID = $this->valTextMandatoryMaxCar($sourceID, 30, __METHOD__);
            $return         = true;
        } catch (AuditFileException $e) {
            $this->sourceID = $sourceID;
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . "  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SourceID_not_valid");
            $return = false;
        }
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'", $this->sourceID
            )
        );
        return $return;
    }

    /**
     * Get SourceBilling<br>
     * To fill in with:<br>
     * “P” – Document created in the invoicing program;<br>
     * “I” – Document integrated and produced in a different invoicing program;<br>
     * “M” – Recovered or manually issued document.<br>
     * &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceBilling(): SourceBilling
    {
        AAuditFile::$logger?->info(
            \sprintf(__METHOD__ . " get '%s'", $this->sourceBilling->value)
        );
        return $this->sourceBilling;
    }

    /**
     * Get if is set SourceBilling
     *
     * @return bool
     * @since 1.0.0
     */
    public function issetSourceBilling(): bool
    {
        return isset($this->sourceBilling);
    }

    /**
     * Set SourceBilling<br>
     * To fill in with:<br>
     * “P” – Document created in the invoicing program;<br>
     * “I” – Document integrated and produced in a different invoicing program;<br>
     * “M” – Recovered or manually issued document.<br>
     * &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling $sourceBilling
     *
     * @return void
     * @since 1.0.0
     */
    public function setSourceBilling(SourceBilling $sourceBilling): void
    {
        $this->sourceBilling = $sourceBilling;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__ . " set to '%s'",
                $this->sourceBilling->value
            )
        );
    }

    /**
     * Create Xml node
     *
     * @param \SimpleXMLElement $node
     *
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== WorkDocument::N_WORK_DOCUMENT) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                WorkDocument::N_WORK_DOCUMENT, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $statusNode = $node->addChild(static::N_DOCUMENT_STATUS);

        if (isset($this->workStatus)) {
            $statusNode->addChild(
                static::N_WORK_STATUS, $this->getWorkStatus()->value
            );
        } else {
            $statusNode->addChild(static::N_WORK_STATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkStatus_not_valid");
        }

        if (isset($this->workStatusDate)) {
            $statusNode->addChild(
                static::N_WORK_STATUS_DATE,
                $this->getWorkStatusDate()->format(Pattern::DATE_T_TIME)
            );
        } else {
            $statusNode->addChild(static::N_WORK_STATUS_DATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkStatusDate_not_valid");
        }

        if ($this->getReason() !== null) {
            $statusNode->addChild(static::N_REASON, $this->getReason());
        }

        if (isset($this->sourceID)) {
            $statusNode->addChild(static::N_SOURCE_ID, $this->getSourceID());
        } else {
            $statusNode->addChild(static::N_SOURCE_ID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if (isset($this->sourceBilling)) {
            $statusNode->addChild(
                static::N_SOURCE_BILLING, $this->getSourceBilling()->value
            );
        } else {
            $statusNode->addChild(static::N_SOURCE_BILLING);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceBilling_not_valid");
        }

        return $statusNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== static::N_DOCUMENT_STATUS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static:: N_DOCUMENT_STATUS, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setWorkStatus(
            WorkStatus::from((string)$node->{static::N_WORK_STATUS})
        );

        $this->setWorkStatusDate(
            RDate::parse(
                Pattern:: DATE_T_TIME, (string)$node->{static::N_WORK_STATUS_DATE}
            )
        );

        if ($node->{static::N_REASON}->count() !== 0) {
            $this->setReason(
                (string)$node->{static::N_REASON
                }
            );
        }
        $this->setSourceID((string)$node->{static::N_SOURCE_ID});

        $this->setSourceBilling(
            SourceBilling::from((string)$node->{static::N_SOURCE_BILLING})
        );
    }
}
