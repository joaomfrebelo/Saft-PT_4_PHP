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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;

/**
 * DocumentStatus of StockMovement
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
    const N_MOVEMENTSTATUS = "MovementStatus";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTSTATUSDATE = "MovementStatusDate";

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
     * <xs:element ref="MovementStatus"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus
     * @since 1.0.0
     */
    private MovementStatus $movementStatus;

    /**
     * <xs:element ref="MovementStatusDate"/><br>
     * <xs:element name="MovementStatusDate" type="SAFdateTimeType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $movementStatusDate;

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
     * DocumentStatus
     * <pre>
     * &lt;xs:element name="DocumentStatus"&gt;
     *  &lt;xs:complexType&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="MovementStatus"/&gt;
     *          &lt;xs:element ref="MovementStatusDate"/&gt;
     *          &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *          &lt;xs:element ref="SourceID"/&gt;
     *          &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get MovementStatus<br>
     * <xs:element ref="MovementStatus"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus
     * @since 1.0.0
     */
    public function getMovementStatus(): MovementStatus
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->movementStatus;
    }

    /**
     * Set MovementStatus<br>
     * <xs:element ref="MovementStatus"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus $movementStatus
     * @return void
     * @since 1.0.0
     */
    public function setMovementStatus(MovementStatus $movementStatus): void
    {
        $this->movementStatus = $movementStatus;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
    }

    /**
     * Get MovementStatusDate<br>
     * <xs:element ref="MovementStatusDate"/><br>
     * <xs:element name="MovementStatusDate" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->movementStatusDate->format(RDate::DATE_T_TIME)
        ));
        return $this->movementStatusDate;
    }

    /**
     * Set MovementStatusDate<br>
     * <xs:element ref="MovementStatusDate"/><br>
     * <xs:element name="MovementStatusDate" type="SAFdateTimeType"/>
     * @param \Rebelo\Date\Date $movementStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setMovementStatusDate(RDate $movementStatusDate): void
    {
        $this->movementStatusDate = $movementStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->movementStatusDate->format(RDate::DATE_T_TIME)
        ));
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
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason
        ));
        return $this->reason;
    }

    /**
     * Set Reason<br>
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * <xs:element name="Reason" type="SAFPTtextTypeMandatoryMax50Car"/>
     * @param string|null $reason
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason === null ? null :
            $this->valTextMandMaxCar($reason, 50, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->reason === null ? "null" : $this->reason
        ));
    }

    /**
     * Get SourceID<br>
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->sourceID === null ? "null" : $this->sourceID
        ));
        return $this->sourceID;
    }

    /**
     * Set SourceID<br>
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @param string $sourceID
     * @return void
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): void
    {
        $this->sourceID = $sourceID === null ? null :
            $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->sourceID === null ? "null" : $this->sourceID
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
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", $this->sourceBilling->get()
        ));
        return $this->sourceBilling;
    }

    /**     *
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
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->sourceBilling->get()
        ));
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

        if ($node->getName() !== StockMovement::N_STOCKMOVEMENT) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                StockMovement::N_STOCKMOVEMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $statusNode = $node->addChild(static::N_DOCUMENTSTATUS);
        $statusNode->addChild(
            static::N_MOVEMENTSTATUS, $this->getMovementStatus()->get()
        );
        $statusNode->addChild(
            static::N_MOVEMENTSTATUSDATE,
            $this->getMovementStatusDate()->format(RDate::DATE_T_TIME)
        );
        if ($this->getReason() !== null) {
            $statusNode->addChild(
                static::N_REASON, $this->getReason()
            );
        }
        $statusNode->addChild(
            static::N_SOURCEID, $this->getSourceID()
        );
        $statusNode->addChild(
            static::N_SOURCEBILLING, $this->getSourceBilling()->get()
        );
        return $statusNode;
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
            $msg = \sprintf("Node name should be '%s' but is '%s'",
                static::N_DOCUMENTSTATUS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setMovementStatus(
            new MovementStatus((string) $node->{static::N_MOVEMENTSTATUS})
        );

        $this->setMovementStatusDate(
            RDate::parse(RDate::DATE_T_TIME,
                (string) $node->{static::N_MOVEMENTSTATUSDATE}
            )
        );

        if ($node->{static::N_REASON}->count() > 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }

        $this->setSourceID(
            (string) $node->{static::N_SOURCEID}
        );

        $this->setSourceBilling(
            new SourceBilling(
                (string) $node->{static::N_SOURCEBILLING}
            )
        );
    }
}