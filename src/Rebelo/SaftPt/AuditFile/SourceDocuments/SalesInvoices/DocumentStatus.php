<?php

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * DocumentStatus
 * <pre>
 * <!-- Estrutura da situacao atual do documento -->
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
     * <xs:element ref="InvoiceStatus"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus $invoiceStatus
     * @since 1.0.0
     */
    private InvoiceStatus $invoiceStatus;

    /**
     * <xs:element ref="InvoiceStatusDate"/>
     * @var \Rebelo\Date\Date DateTime $invoiceStatusDate
     * @since 1.0.0
     */
    private RDate $invoiceStatusDate;

    /**
     * <xs:element ref="Reason" minOccurs="0"/>
     * Max length 50
     * @var string|null $reason
     * @since 1.0.0
     */
    private ?string $reason = null;

    /**
     * <xs:element ref="SourceID"/>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
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
     * <!-- Estrutura da situacao atual do documento -->
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
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as invoiceStatus
     * <xs:element ref="InvoiceStatus"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus
     * @since 1.0.0
     */
    public function getInvoiceStatus(): InvoiceStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->invoiceStatus->get())));
        return $this->invoiceStatus;
    }

    /**
     * Sets a InvoiceStatus
     * <xs:element ref="InvoiceStatus"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus $invoiceStatus
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceStatus(InvoiceStatus $invoiceStatus): void
    {
        $this->invoiceStatus = $invoiceStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->invoiceStatus->get()));
    }

    /**
     * Gets as invoiceStatusDate
     * <xs:element ref="InvoiceStatusDate"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getInvoiceStatusDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->invoiceStatusDate->format(
                        RDate::DATE_T_TIME
        )));
        return $this->invoiceStatusDate;
    }

    /**
     * Sets a new invoiceStatusDate
     * <xs:element ref="InvoiceStatusDate"/>
     * @param \Rebelo\Date\Date $invoiceStatusDate
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceStatusDate(RDate $invoiceStatusDate): void
    {
        $this->invoiceStatusDate = $invoiceStatusDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->invoiceStatusDate->format(RDate::DATE_T_TIME)));
    }

    /**
     * Gets as reason
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * Max length 50
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason ));
        return $this->reason;
    }

    /**
     * Sets a new reason
     * <xs:element ref="Reason" minOccurs="0"/><br>
     * Max length 50
     * @param string|null $reason
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason === null ? null :
            static::valTextMandMaxCar($reason, 50, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->reason === null ? "null" : $this->reason));
    }

    /**
     * Gets as sourceID
     * <xs:element ref="SourceID"/><br>
     * Max length 30
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
     * Sets a new sourceID
     * <xs:element ref="SourceID"/><br>
     * Max length 30
     * @param string $sourceID
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): void
    {
        $this->sourceID = static::valTextMandMaxCar($sourceID, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->sourceID));
    }

    /**
     * Gets as sourceBilling
     * <xs:element name="SourceBilling" type="SAFTPTSourceBilling"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
     * @since 1.0.0
     */
    public function getSourceBilling(): SourceBilling
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->sourceBilling->get()));
        return $this->sourceBilling;
    }

    /**
     * Sets a new sourceBilling
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
                    $this->sourceBilling->get()));
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

        if ($node->getName() !== Invoice::N_INVOICE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                Invoice::N_INVOICE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $nodeDocStatus = $node->addChild(static::N_DOCUMENTSTATUS);

        //<xs:element ref = "InvoiceStatus"/>
        $nodeDocStatus->addChild(
            static::N_INVOICESTATUS, $this->getInvoiceStatus()->get()
        );
        //<xs:element ref = "InvoiceStatusDate"/>
        $nodeDocStatus->addChild(
            static::N_INVOICESTATUSDATE,
            $this->getInvoiceStatusDate()->format(RDate::DATE_T_TIME)
        );
        //<xs:element ref = "Reason" minOccurs = "0"/>
        if ($this->getReason() !== null) {
            $nodeDocStatus->addChild(
                static::N_REASON, $this->getReason()
            );
        }
        //<xs:element ref = "SourceID"/>
        $nodeDocStatus->addChild(
            static::N_SOURCEID, $this->getSourceID()
        );
        //<xs:element name = "SourceBilling" type = "SAFTPTSourceBilling"/>
        $nodeDocStatus->addChild(
            static::N_SOURCEBILLING, $this->getSourceBilling()->get()
        );


        return $nodeDocStatus;
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
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_DOCUMENTSTATUS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setInvoiceStatus(new InvoiceStatus(
                (string) $node->{static::N_INVOICESTATUS}
        ));
        $this->setInvoiceStatusDate(RDate::parse(
                RDate::DATE_T_TIME,
                (string) $node->{static::N_INVOICESTATUSDATE}
        ));
        $this->setSourceBilling(new SourceBilling(
                (string) $node->{static::N_SOURCEBILLING}
        ));
        $this->setSourceID((string) $node->{static::N_SOURCEID});
        if ($node->{static::N_REASON}->count() > 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }
    }
}