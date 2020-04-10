<?php

declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile;

use \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries;
use \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Class representing AuditFile
 * <xs:element name="AuditFile">
 * @since 1.0.0
 */
class AuditFile
    extends AAuditFile
{

    const N_AUDITFILE = "AuditFile";

    /**
     * <xs:element ref="Header" minOccurs="1"/>
     * @var \Rebelo\SaftPt\AuditFile\Header $header
     * @since 1.0.0
     */
    private Header $header;

    /**
     *  <xs:element name="MasterFiles">
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles $masterFiles
     * @since 1.0.0
     */
    private MasterFiles $masterFiles;

    /**
     * <xs:element ref="GeneralLedgerEntries" minOccurs="0"/>

     * @var \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries $generalLedgerEntries
     * @since 1.0.0
     */
    private ?GeneralLedgerEntries $generalLedgerEntries = null;

    /**
     * <xs:element ref="SourceDocuments" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments $sourceDocuments
     * @since 1.0.0
     */
    private SourceDocuments $sourceDocuments;

    /**
     * The type of saft xml exported<br>
     * Simplified or Complete
     * @since 1.0.0
     */
    public static ExportType $exportType;

    /**
     * <xs:element name="AuditFile">
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        if (isset(static::$exportType) === false)
        {
            static::$exportType = new ExportType(ExportType::C);
        }
    }

    /**
     *
     * Gets as header <br>
     * <xs:element ref="Header" minOccurs="1"/>
     * @return \Rebelo\SaftPt\Header
     * @since 1.0.0
     */
    public function getHeader(): Header
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->header;
    }

    /**
     * Sets a new header <br>
     * <xs:element ref="Header" minOccurs="1"/>
     * @param \Rebelo\SaftPt\Header $header
     * @return self
     * @since 1.0.0
     */
    public function setHeader(Header $header): AuditFile
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->header = $header;
        return $this;
    }

    /**
     * Gets as masterFiles <br>
     * <xs:element name="MasterFiles">
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles
     * @since 1.0.0
     */
    public function getMasterFiles(): MasterFiles
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->masterFiles;
    }

    /**
     * Sets a new masterFiles <br>
     * <xs:element name="MasterFiles">
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles $masterFiles
     * @return self
     * @since 1.0.0
     */
    public function setMasterFiles(MasterFiles $masterFiles): AuditFile
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->masterFiles = $masterFiles;
        return $this;
    }

    /**
     * Gets as generalLedgerEntries <br>
     * <xs:element ref="GeneralLedgerEntries" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries
     */
    public function getGeneralLedgerEntries(): GeneralLedgerEntries
    {
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__ . " '%s'", "Not implemented"));
        throw new NotImplemented("Not implemented");
    }

    /**
     * Sets a new generalLedgerEntries <br>
     * <xs:element ref="GeneralLedgerEntries" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries $generalLedgerEntries
     * @return self
     */
    public function setGeneralLedgerEntries(GeneralLedgerEntries $generalLedgerEntries): AuditFile
    {
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__ . " '%s'", "Not implemented"));
        throw new NotImplemented("Not implemented");
    }

    /**
     * Gets as sourceDocuments <br>
     * <xs:element ref="SourceDocuments" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments
     * @since 1.0.0
     */
    public function getSourceDocuments(): SourceDocuments
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->sourceDocuments;
    }

    /**
     * Sets a new sourceDocuments <br>
     * <xs:element ref="SourceDocuments" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments $sourceDocuments
     * @return self
     * @since 1.0.0
     */
    public function setSourceDocuments(SourceDocuments $sourceDocuments): SourceDocuments
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->sourceDocuments = $sourceDocuments;
        return $this;
    }

    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

    }

    public function parseXmlNode(\SimpleXMLElement $node): void
    {

    }

}
