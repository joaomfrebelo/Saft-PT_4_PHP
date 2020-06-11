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
     * <xs:element ref="WorkStatus"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @since 1.0.0
     */
    private WorkStatus $workStatus;

    /**
     * <xs:element ref="WorkStatusDate"/><br>
     * <xs:element name="WorkStatusDate" type="SAFdateTimeType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $workStatusDate;

    /**
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @var string
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * <xs:element name="SourceBilling" type="SAFTPTSourceBilling"/>
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
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get workstatus<br>
     * <xs:element ref="WorkStatus"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus
     * @since 1.0.0
     */
    public function getWorkStatus(): WorkStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->workStatus->get()));
        return $this->workStatus;
    }

    /**
     * Set the workstatus<br>
     * <xs:element ref="WorkStatus"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus $workStatus
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatus(WorkStatus $workStatus): void
    {
        $this->workStatus = $workStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->workStatus->get()));
    }

    /**
     * Get WorkStatusDate (DateTime)<br>
     * <xs:element ref="WorkStatusDate"/><br>
     * <xs:element name="WorkStatusDate" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getWorkStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(__METHOD__." getted '%s'",
                    $this->workStatusDate->format(RDate::DATE_T_TIME))
        );
        return $this->workStatusDate;
    }

    /**
     * Set WorkStatusDate (DateTime)<br>
     * <xs:element ref="WorkStatusDate"/><br>
     * <xs:element name="WorkStatusDate" type="SAFdateTimeType"/>
     * @param \Rebelo\Date\Date $workStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setWorkStatusDate(RDate $workStatusDate): void
    {
        $this->workStatusDate = $workStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->workStatusDate->format(RDate::DATE_T_TIME)));
    }

    /**
     * Get Reason<br>
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(__METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason)
        );
        return $this->reason;
    }

    /**
     * Set Reason<br>
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
                    $this->reason === null ? "null" : $this->reason
        ));
    }

    /**
     * Set SourceID<br>
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @return string
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
     * Get SourceID<br>
     * <xs:element ref="SourceID"/><br>
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
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->sourceID
        ));
    }

    /**
     * Get SourceBilling<br>
     * <xs:element name="SourceBilling" type="SAFTPTSourceBilling"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @since 1.0.0
     */
    public function getSourceBilling(): SourceBilling
    {
        return $this->sourceBilling;
    }

    /**
     * Set SourceBilling<br>
     * <xs:element name="SourceBilling" type="SAFTPTSourceBilling"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling $sourceBilling
     * @return void
     * @since 1.0.0
     */
    public function setSourceBilling(SourceBilling $sourceBilling): void
    {
        $this->sourceBilling = $sourceBilling;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->sourceBilling->get()
        ));
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== WorkDocument::N_WORKDOCUMENT) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                WorkDocument::N_WORKDOCUMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $statusNode = $node->addChild(static::N_DOCUMENTSTATUS);

        $statusNode->addChild(
            static::N_WORKSTATUS, $this->getWorkStatus()->get()
        );
        $statusNode->addChild(
            static::N_WORKSTATUSDATE,
            $this->getWorkStatusDate()->format(RDate::DATE_T_TIME)
        );
        if ($this->getReason() !== null) {
            $statusNode->addChild(static::N_REASON, $this->getReason());
        }
        $statusNode->addChild(static::N_SOURCEID, $this->getSourceID());
        $statusNode->addChild(
            static::N_SOURCEBILLING, $this->getSourceBilling()->get()
        );
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
            $msg = \sprintf("Node name should be '%s' but is '%s",
                static::N_DOCUMENTSTATUS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setWorkStatus(
            new WorkStatus((string) $node->{static::N_WORKSTATUS})
        );
        $this->setWorkStatusDate(
            RDate::parse(
                RDate::DATE_T_TIME, (string) $node->{static::N_WORKSTATUSDATE}
            )
        );
        if ($node->{static::N_REASON}->count() !== 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }
        $this->setSourceID((string) $node->{static::N_SOURCEID});
        $this->setSourceBilling(
            new SourceBilling((string) $node->{static::N_SOURCEBILLING})
        );
    }
}