<?php

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * DocumentStatus
 * <pre>
 * &lt;xs:element name="DocumentStatus"&gt;
 *     &lt;xs:complexType&gt;
 *         &lt;xs:sequence&gt;
 *           &lt;xs:element ref="InvoiceStatus"/&gt;
 *           &lt;xs:element ref="InvoiceStatusDate"/&gt;
 *           &lt;xs:element ref="Reason" minOccurs="0"/&gt;
 *           &lt;xs:element ref="SourceID"/&gt;
 *           &lt;xs:element name="SourceBilling"
 *           type="SAFTPTSourceBilling"/&gt;
 *         &lt;/xs:sequence&gt;
 *     &lt;/xs:complexType&gt;
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
    const N_INVOICESTATUS = "InvoiceStatus";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_INVOICESTATUSDATE = "InvoiceStatusDate";

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
    const N_SOURCEBILLING = "SourceBilling";

    /**
     * &lt;xs:element ref="InvoiceStatus"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus $invoiceStatus
     * @since 1.0.0
     */
    private InvoiceStatus $invoiceStatus;

    /**
     * &lt;xs:element ref="InvoiceStatusDate"/&gt;
     * @var \Rebelo\Date\Date DateTime $invoiceStatusDate
     * @since 1.0.0
     */
    private RDate $invoiceStatusDate;

    /**
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     * Max length 50
     * @var string|null $reason
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * &lt;xs:element ref="SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string $sourceID
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling $sourceBilling
     * @since 1.0.0
     */
    private SourceBilling $sourceBilling;

    /**
     * DocumentStatus
     * <pre>
     * &lt;xs:element name="DocumentStatus"&gt;
     *     &lt;xs:complexType&gt;
     *         &lt;xs:sequence&gt;
     *           &lt;xs:element ref="InvoiceStatus"/&gt;
     *           &lt;xs:element ref="InvoiceStatusDate"/&gt;
     *           &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *           &lt;xs:element ref="SourceID"/&gt;
     *           &lt;xs:element name="SourceBilling"
     *           type="SAFTPTSourceBilling"/&gt;
     *         &lt;/xs:sequence&gt;
     *     &lt;/xs:complexType&gt;
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
     * Gets InvoiceStatus<br><br>
     * The field must be filled in with:<br>
     * “N” - Normal;<br>
     * “A” - Cancelled document;<br>
     * “R” - Summary document for other documents created in other applications
     * and generated in this application;
     * “F” - Invoiced document.<br>
     * &lt;xs:element ref="InvoiceStatus"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceStatus(): InvoiceStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->invoiceStatus->get())
                )
            );
        return $this->invoiceStatus;
    }

    /**
     * Get if is set InvoiceStatus
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoiceStatus(): bool
    {
        return isset($this->invoiceStatus);
    }

    /**
     * Sets InvoiceStatus<br><br>
     * The field must be filled in with:<br>
     * “N” - Normal;<br>
     * “A” - Cancelled document;<br>
     * “R” - Summary document for other documents created in other applications
     * and generated in this application;
     * “F” - Invoiced document.<br>
     * &lt;xs:element ref="InvoiceStatus"/&gt;
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus $invoiceStatus
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceStatus(InvoiceStatus $invoiceStatus): void
    {
        $this->invoiceStatus = $invoiceStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->invoiceStatus->get()
                )
            );
    }

    /**
     * Gets InvoiceStatusDate
     * &lt;xs:element ref="InvoiceStatusDate"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->invoiceStatusDate->format(
                        RDate::DATE_T_TIME
                    )
                )
            );
        return $this->invoiceStatusDate;
    }

    /**
     * Get if is set InvoiceStatusDate
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoiceStatusDate(): bool
    {
        return isset($this->invoiceStatusDate);
    }

    /**
     * Sets InvoiceStatusDate
     * &lt;xs:element ref="InvoiceStatusDate"/&gt;
     * @param \Rebelo\Date\Date $invoiceStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceStatusDate(RDate $invoiceStatusDate): void
    {
        $this->invoiceStatusDate = $invoiceStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->invoiceStatusDate->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Gets Reason
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * Max length 50
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
     * Sets Reason
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;<br>
     * Max length 50
     * @param string|null $reason
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setReason(?string $reason): bool
    {
        try {
            $this->reason = $reason === null ? null :
                static::valTextMandMaxCar($reason, 50, __METHOD__);
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
                    __METHOD__." set to '%s'",
                    $this->reason === null ? "null" : $this->reason
                )
            );
        return $return;
    }

    /**
     * Gets SourceID
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * Max length 30
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
     * Sets SourceID
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * Max length 30
     * @param string $sourceID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): bool
    {
        try {
            $this->sourceID = static::valTextMandMaxCar(
                $sourceID, 30,
                __METHOD__
            );
            $return         = true;
        } catch (AuditFileException $e) {
            $this->sourceID = $sourceID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SourceID_not_valid");
            $return         = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->sourceID));
        return $return;
    }

    /**
     * Gets SourceBilling<br>
     * &lt;xs:element name="SourceBilling" type="SAFTPTSourceBilling"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceBilling(): SourceBilling
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->sourceBilling->get()
                )
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
     * Sets SourceBilling<br>
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
                    __METHOD__." set to '%s'",
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

        if ($node->getName() !== Invoice::N_INVOICE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                Invoice::N_INVOICE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $nodeDocStatus = $node->addChild(static::N_DOCUMENTSTATUS);

        if (isset($this->invoiceStatus)) {
            $nodeDocStatus->addChild(
                static::N_INVOICESTATUS, $this->getInvoiceStatus()->get()
            );
        } else {
            $nodeDocStatus->addChild(static::N_INVOICESTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceStatus_not_valid");
        }

        if (isset($this->invoiceStatusDate)) {
            $nodeDocStatus->addChild(
                static::N_INVOICESTATUSDATE,
                $this->getInvoiceStatusDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $nodeDocStatus->addChild(static::N_INVOICESTATUSDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceStatusDate_not_valid");
        }

        if ($this->getReason() !== null) {
            $nodeDocStatus->addChild(
                static::N_REASON, $this->getReason()
            );
        }

        if (isset($this->sourceID)) {
            $nodeDocStatus->addChild(
                static::N_SOURCEID, $this->getSourceID()
            );
        } else {
            $nodeDocStatus->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if (isset($this->sourceBilling)) {
            $nodeDocStatus->addChild(
                static::N_SOURCEBILLING, $this->getSourceBilling()->get()
            );
        } else {
            $nodeDocStatus->addChild(static::N_SOURCEBILLING);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceBilling_not_valid");
        }

        return $nodeDocStatus;
    }

    /**
     * Parse Xml node
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

        $this->setInvoiceStatus(
            new InvoiceStatus(
                (string) $node->{static::N_INVOICESTATUS}
            )
        );
        $this->setInvoiceStatusDate(
            RDate::parse(
                RDate::DATE_T_TIME,
                (string) $node->{static::N_INVOICESTATUSDATE}
            )
        );

        $this->setSourceBilling(
            new SourceBilling(
                (string) $node->{static::N_SOURCEBILLING}
            )
        );

        $this->setSourceID((string) $node->{static::N_SOURCEID});

        if ($node->{static::N_REASON}->count() > 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }
    }
}
