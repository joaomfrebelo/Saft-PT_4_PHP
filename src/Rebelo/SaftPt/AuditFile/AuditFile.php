<?php
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Class representing AuditFile
 * &lt;xs:element name="AuditFile">
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
     * &lt;xs:element ref="Header" minOccurs="1"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\Header $header
     * @since 1.0.0
     */
    protected Header $header;

    /**
     *  &lt;xs:element name="MasterFiles">
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles $masterFiles
     * @since 1.0.0
     */
    protected MasterFiles $masterFiles;

    /**
     * &lt;xs:element ref="GeneralLedgerEntries" minOccurs="0"/&gt;

     * @var \Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries $generalLedgerEntries
     * @since 1.0.0
     */
    protected ?GeneralLedgerEntries $generalLedgerEntries = null;

    /**
     * &lt;xs:element ref="SourceDocuments" minOccurs="0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments|null $sourceDocuments
     * @since 1.0.0
     */
    protected ?SourceDocuments $sourceDocuments = null;

    /**
     * &lt;xs:element name="AuditFile">
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister|null $errorRegister
     * @since 1.0.0
     */
    public function __construct(?ErrorRegister $errorRegister = null)
    {
        if ($errorRegister === null) {
            $errorRegister = new ErrorRegister();
        }
        parent::__construct($errorRegister);
    }

    /**
     * Gets as header <br>
     * The item Header contains the general information regarding the taxpayer,
     * whom the SAF-T (PT) refers to.<br>
     * This get will create the Header instance<br>
     * &lt;xs:element ref="Header" minOccurs="1"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\Header
     * @since 1.0.0
     */
    public function getHeader(): Header
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if (isset($this->header) === false) {
            $this->header = new Header($this->errorRegister);
        }
        return $this->header;
    }

    /**
     * Get if is set Header
     * @return bool
     * @since 1.0.0
     */
    public function issetHeader(): bool
    {
        return isset($this->header);
    }

    /**
     * Gets as masterFiles <br>
     * This get will create the MasterFiles instance<br>
     * &lt;xs:element name="MasterFiles">
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles
     * @since 1.0.0
     */
    public function getMasterFiles(): MasterFiles
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if (isset($this->masterFiles) === false) {
            $this->masterFiles = new MasterFiles($this->getErrorRegistor());
        }
        return $this->masterFiles;
    }

    /**
     * Get if is set MasterFiles
     * @return bool
     * @since 1.0.0
     */
    public function issetMasterFiles(): bool
    {
        return isset($this->masterFiles);
    }

    /**
     * Gets as generalLedgerEntries <br>
     * &lt;xs:element ref="GeneralLedgerEntries" minOccurs="0"/&gt;
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
     * Gets as sourceDocuments <br>
     * SourceDocuments<br>
     * Lines in documents without fiscal relevance must not be exported,
     * in particular technical descriptions, installation instructions and guarantee
     * conditions. The internal code of the document type cannot be used in
     * different document types, regardless of the table in which it is to be exported.<br>
     * &lt;xs:element ref="SourceDocuments" minOccurs="0"/&gt;<br>
     * This get will create the SourceDocuments instance if $generate is true
     * @param bool $generate if true and SourceDocuments is null a new Instance will be created
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments|null
     * @since 1.0.0
     */
    public function getSourceDocuments(bool $generate = true): ?SourceDocuments
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($generate === true && $this->sourceDocuments === null) {
            $this->sourceDocuments = new SourceDocuments($this->errorRegister);
        }
        return $this->sourceDocuments;
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== static::N_AUDITFILE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_AUDITFILE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if (isset($this->header) === true) {
            $label = "SAF-T PT created by joaomfrebelo/saft-pt_4_php "
                .\ComposerRevisions\Revisions::$byName["joaomfrebelo/saft-pt_4_php"];

            if (defined("IS_UNIT_TEST") === false) {
                $this->getHeader()->setHeaderComment(
                    $this->getHeader()->getHeaderComment() === null ?
                        $label : $this->getHeader()->getHeaderComment()." - ".$label
                );
            }

            $this->getHeader()->createXmlNode($node);
        } else {
            $this->errorRegister->addOnCreateXmlNode("no_header_table");
            \Logger::getLogger(\get_class($this))
                ->error("No 'Header' on create xml node");
        }

        if (isset($this->masterFiles)) {
            $this->getMasterFiles()->createXmlNode($node);
        } else {
            $node->addChild(MasterFiles::N_MASTERFILES);
            $this->errorRegister->addOnCreateXmlNode("no_master_files_table");
            \Logger::getLogger(\get_class($this))
                ->error("No 'MasterFiles' on create xml node");
        }
        if ($this->sourceDocuments !== null) {
            $this->getSourceDocuments()->createXmlNode($node);
        }
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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_AUDITFILE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $header = $this->getHeader();
        $header->parseXmlNode($node->{Header::N_HEADER});

        $master = $this->getMasterFiles();
        $master->parseXmlNode($node->{MasterFiles::N_MASTERFILES});

        if ($node->{SourceDocuments::N_SOURCEDOCUMENTS}->count() > 0) {
            $sourceDocs = $this->getSourceDocuments(true);
            $sourceDocs->parseXmlNode($node->{SourceDocuments::N_SOURCEDOCUMENTS});
        }
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
                'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>',
            LIBXML_PARSEHUGE | LIBXML_BIGLINES
        );
    }

    /**
     * Get the saft as a xml string.<br>
     * By the Portuguese Tax law, the ERP must generate the saft even if
     * has errors, because of that rule when there are errors instead of
     * throws an exception the error is registed in the ErrorRegister
     * instance of the AuditFile instance, only in severe condition where is not
     * possible to catch the exception or error that a \Exception or \Error will
     * be throw. To know if there are errors access to the ErrorRegister instance
     * of the AuditFile instance. Some validation to check for errors are done
     * on the setter methods, when get the AuditFile as a xml string, other validation
     * is done, the xml string structure is validated, but other validations
     * can be done using the validation classes, however that validations
     * in very big AuditFiles could have a time consume very hight, is
     * recomended to use in test envoirment, in producion envoirment
     * should be evaluated if is necessary.
     * @return string
     * @since 1.0.0
     */
    public function toXmlString(): string
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $xml      = $this->createXmlNode($this->createRootElement())->asXML();
        $this->replaceHexUtf($xml);
        $validate = new \Rebelo\SaftPt\Validate\XmlStructure($this);
        $validate->validate($xml);
        return $xml;
    }

    /**
     * Write the XML to a file<br>
     * By the Portuguese Tax law, the ERP must generate the saft even if
     * has errors, because of that rule when there are errors instead of
     * throws an exception the error is registed in the ErrorRegister
     * instance of the AuditFile instance, only in severe condition where is not
     * possible to catch the exception or error that a \Exception or \Error will
     * be throw. To know if there are errors access to the ErrorRegister instance
     * of the AuditFile instance. Some validation to check for errors are done
     * on the setter methods, when write the AuditFile a file, other validation
     * is done, the xml string structure is validated, but other validations
     * can be done using the validation classes, however that validations
     * in very big AuditFiles could have a time consume very hight, is
     * recomended to use in test envoirment, in producion envoirment
     * should be evaluated if is necessary.
     * @param string $path File path to write, if exists will be  overwritten
     * @return int The number of bytes that were written to the file
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function toFile(string $path): int
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $bytes = file_put_contents(
            $path, $this->toXmlString(), LOCK_EX
        );
        if ($bytes === false) {
            throw new AuditFileException(
                \sprintf("failing write to file '%s'", $path)
            );
        }
        return $bytes;
    }
}