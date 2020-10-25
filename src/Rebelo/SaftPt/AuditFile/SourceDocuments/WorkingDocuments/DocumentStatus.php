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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;

/**
 * DocumentStatus
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class DocumentStatus extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_DOCUMENTSTATUS = "DocumentStatus";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WORKSTATUS = "WorkStatus";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WORKSTATUSDATE = "WorkStatusDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_REASON = "Reason";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEID = "SourceID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEBILLING = "SourceBilling";

    /**
     * &lt;xs:element ref="WorkStatus"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @since 1.0.0
     */
    private WorkStatus $workStatus;

    /**
     * &lt;xs:element ref="WorkStatusDate"/&gt;<br>
     * &lt;xs:element name="WorkStatusDate" type="SAFdateTimeType"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $workStatusDate;

    /**
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
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
      &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get workstatus<br>
     * &lt;xs:element ref="WorkStatus"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkStatus(): WorkStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->workStatus->get()));
        return $this->workStatus;
    }

    /**
     * Get if is set WorkStatus
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkStatus(): bool
    {
        return isset($this->workStatus);
    }

    /**
     * Set the workstatus<br>
     * &lt;xs:element ref="WorkStatus"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus $workStatus
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatus(WorkStatus $workStatus): void
    {
        $this->workStatus = $workStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->workStatus->get()
                )
            );
    }

    /**
     * Get WorkStatusDate (DateTime)<br>
     * Date of the last storage of the document status to the second.<br>
     * &lt;xs:element ref="WorkStatusDate"/&gt;<br>
     * &lt;xs:element name="WorkStatusDate" type="SAFdateTimeType"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->workStatusDate->format(RDate::DATE_T_TIME)
                )
            );
        return $this->workStatusDate;
    }

    /**
     * Get if is set WorkStatusDate
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
     * @param \Rebelo\Date\Date $workStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatusDate(RDate $workStatusDate): void
    {
        $this->workStatusDate = $workStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->workStatusDate->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get Reason<br>
     * The reason leading to the change in the document status shall be presented.<br>
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
     * Set Reason<br>
     * The reason leading to the change in the document status shall be presented.<br>
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
        } catch (AuditFileException $e) {
            $this->reason = $reason;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Reason_not_valid");
            $return       = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
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
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(__METHOD__." getted '%s'", $this->sourceID)
            );
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
     * Get SourceID<br>
     * User responsible for the current document status.<br>
     * &lt;xs:element ref="SourceID"/&gt;<br>
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
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SourceID_not_valid");
            $return         = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'", $this->sourceID
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
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceBilling(): SourceBilling
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(__METHOD__." getted '%s'", $this->sourceBilling->get())
            );
        return $this->sourceBilling;
    }

    /**
     * Get if is set SourceBilling
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling $sourceBilling
     * @return void
     * @since 1.0.0
     */
    public function setSourceBilling(SourceBilling $sourceBilling): void
    {
        $this->sourceBilling = $sourceBilling;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->sourceBilling->get()
                )
            );
    }

    /**
     * Create Xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== WorkDocument::N_WORKDOCUMENT) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                WorkDocument::N_WORKDOCUMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $statusNode = $node->addChild(static::N_DOCUMENTSTATUS);

        if (isset($this->workStatus)) {
            $statusNode->addChild(
                static::N_WORKSTATUS, $this->getWorkStatus()->get()
            );
        } else {
            $statusNode->addChild(static::N_WORKSTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkStatus_not_valid");
        }

        if (isset($this->workStatusDate)) {
            $statusNode->addChild(
                static::N_WORKSTATUSDATE,
                $this->getWorkStatusDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $statusNode->addChild(static::N_WORKSTATUSDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkStatusDate_not_valid");
        }

        if ($this->getReason() !== null) {
            $statusNode->addChild(static::N_REASON, $this->getReason());
        }

        if (isset($this->sourceID)) {
            $statusNode->addChild(static::N_SOURCEID, $this->getSourceID());
        } else {
            $statusNode->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if (isset($this->sourceBilling)) {
            $statusNode->addChild(
                static::N_SOURCEBILLING, $this->getSourceBilling()->get()
            );
        } else {
            $statusNode->addChild(static::N_SOURCEBILLING);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceBilling_not_valid");
        }

        return $statusNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_DOCUMENTSTATUS) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static:: N_DOCUMENTSTATUS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setWorkStatus(
            new WorkStatus((string) $node->{static::N_WORKSTATUS})
        );

        $this->setWorkStatusDate(
            RDate::parse(
                RDate:: DATE_T_TIME, (string) $node->{static::N_WORKSTATUSDATE}
            )
        );

        if ($node->{static::N_REASON}->count() !== 0) {
            $this->setReason(
                (string) $node->{static::N_REASON
                }
            );
        }
        $this->setSourceID((string) $node->{static::N_SOURCEID});

        $this->setSourceBilling(
            new SourceBilling((string) $node->{static::N_SOURCEBILLING})
        );
    }
}