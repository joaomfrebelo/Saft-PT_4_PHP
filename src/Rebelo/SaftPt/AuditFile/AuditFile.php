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
class AuditFile extends AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
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
     * <xs:element name="AuditFile">
     * @param \Rebelo\SaftPt\AuditFile\ExportType|null $exportType
     * @since 1.0.0
     */
    public function __construct(?ExportType $exportType = null)
    {
        parent::__construct();
        $this->setExportType(
            $exportType === null ? new ExportType(ExportType::C) :
                $exportType
        );
    }

    /**
     *
     * Gets as header <br>
     * <xs:element ref="Header" minOccurs="1"/>
     * @return \Rebelo\SaftPt\AuditFile\Header
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
     * @param \Rebelo\SaftPt\AuditFile\Header $header
     * @return void
     * @since 1.0.0
     */
    public function setHeader(Header $header): void
    {
        $this->header = $header;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
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
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles $masterFiles
     * @return void
     * @since 1.0.0
     */
    public function setMasterFiles(MasterFiles $masterFiles): void
    {
        $this->masterFiles = $masterFiles;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Gets as generalLedgerEntries <br>
     * <xs:element ref="GeneralLedgerEntries" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries
     * @throws \Rebelo\SaftPt\AuditFile\NotImplemented
     * @since 1.0.0
     */
    public function getGeneralLedgerEntries(): GeneralLedgerEntries
    {
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__." '%s'", "Not implemented"));
        throw new NotImplemented("Not implemented");
    }

    /**
     * Sets a new generalLedgerEntries <br>
     * <xs:element ref="GeneralLedgerEntries" minOccurs="0"/>
     * @param \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries $generalLedgerEntries
     * @throws \Rebelo\SaftPt\AuditFile\NotImplemented
     * @return void
     * @since 1.0.0
     */
    public function setGeneralLedgerEntries(GeneralLedgerEntries $generalLedgerEntries): void
    {
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__." '%s'", "Not implemented"));
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
     * @return void
     * @since 1.0.0
     */
    public function setSourceDocuments(SourceDocuments $sourceDocuments): void
    {
        $this->sourceDocuments = $sourceDocuments;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Error
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== static::N_AUDITFILE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                static::N_AUDITFILE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->getMasterFiles()->setExportType($this->getExportType());
        $this->getSourceDocuments()->setExportType($this->getExportType());
        $this->getHeader()->createXmlNode($node);
        $this->getMasterFiles()->createXmlNode($node);
        $this->getSourceDocuments()->createXmlNode($node);
        return $node;
    }

    /**
     * Parse the complete XML saft file
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        if ($node->getName() !== static::N_AUDITFILE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                static::N_AUDITFILE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $header = new Header();
        $header->parseXmlNode($node->{Header::N_HEADER});
        $this->setHeader($header);

        $master = new MasterFiles();
        $master->parseXmlNode($node->{MasterFiles::N_MASTERFILES});
        $this->setMasterFiles($master);

        $sourceDocs = new SourceDocuments();
        $sourceDocs->parseXmlNode($node->{SourceDocuments::N_SOURCEDOCUMENTS});
        $this->setSourceDocuments($sourceDocs);
    }

    /**
     * Create the AuditFile Xml Root element
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createRootElement(): \SimpleXMLElement
    {
        return RSimpleXMLElement::getInstance(
                '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
    }
}