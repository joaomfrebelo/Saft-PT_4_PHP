<?php
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile;

require_once 'RSimpleXmlElement.php'; // In console app does't load from composer autoloader

use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\SaftPt\AuditFile\GeneralLedgerEntries\GeneralLedgerEntries;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Validate\ValidationConfig;

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
    protected ?SourceDocuments $sourceDocuments           = null;
    
    /**
     * The Log file configuration file
     * @var string|null
     */
    public static ?string $log4phpConfigFilePath = null;

    /**
     * &lt;xs:element name="AuditFile">
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister|null $errorRegister
     * @since 1.0.0
     */
    public function __construct(?ErrorRegister $errorRegister = null)
    {
        if (static::$log4phpConfigFilePath !== null) {
            \Logger::configure(static::$log4phpConfigFilePath);
        }

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
                "Node name should be '%s' but is '%s", static::N_AUDITFILE,
                $node->getName()
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
                "Node name should be '%s' but is '%s", static::N_AUDITFILE,
                $node->getName()
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
     * on the setter methods, when get the AuditFile as a xml string, no validation
     * is done, validations can be done using the validation classes, however that validations
     * in very big AuditFiles could have a time consume very height, is
     * recommended to use in test environment, in production environment
     * should be evaluated if is necessary.
     * The string is returned in the encoding "UTF-8" (the encoding of simplexml), but the 
     * file must be export with encoding Windows-1252
     * 
     * @return string
     * @since 1.0.0
     */
    public function toXmlString(): string
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $xml = $this->createXmlNode($this->createRootElement())->asXML();
        $this->replaceHexUtf($xml);
        return $xml;
    }

    /**
     * Return the toXmlString method but the xml string converted to "Windows-1252"
     * @return string
     * @since 1.0.0
     */
    public function toXmlStringWindows1252(): string
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $xml = \mb_convert_encoding(
            $this->toXmlString(), "Windows-1252", "UTF-8"
        );
        return \str_replace(
            '<?xml version="1.0"?>',
            '<?xml version="1.0" encoding="Windows-1252"?>', $xml
        );
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
        $bytes = \file_put_contents(
            $path, $this->toXmlStringWindows1252(), LOCK_EX
        );
        if ($bytes === false) {
            throw new AuditFileException(
                \sprintf("failing write to file '%s'", $path)
            );
        }
        return $bytes;
    }

    /**
     * Load and parse a SAFT-PT file, afeter load and before parsing the will 
     * be done a validation against the XSD, you can check if has any error 
     * in the ErrorRegistor of the AuditFile instance, and you can make 
     * the data validation using the validateData method
     * @param string $path
     * @return \Rebelo\SaftPt\AuditFile\AuditFile
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public static function loadFile(string $path): AuditFile
    {
        if (static::$log4phpConfigFilePath !== null) {
            \Logger::configure(static::$log4phpConfigFilePath);
        }
        \Logger::getLogger(AuditFile::class)->debug(__METHOD__);
        $audit  = new AuditFile();
        $xmlStr = \file_get_contents($path);
        if ($xmlStr === false) {
            $msg = \sprintf("failing open file '%s'", $path);
            \Logger::getLogger(AuditFile::class)->error($msg);
            throw new AuditFileException($msg);
        }

        $detOrder  = \array_merge(
            ["UTF-8", "Windows-1252"], \mb_list_encodings()
        );
        $encodeing = \mb_detect_encoding($xmlStr, $detOrder);
        $xmlEnc    = ($encodeing === false || $encodeing === "UTF-8") ?
            $xmlStr : \mb_convert_encoding($xmlStr, "UTF-8", $encodeing);
        unset($xmlStr);

        $xmlEncClean = \preg_replace(
            '/<\?xml.+version.+encoding.+\?>/i',
            '<?xml version="1.0"?>', $xmlEnc
        );
        unset($xmlEnc);

        $valXmlXsd = new \Rebelo\SaftPt\Validate\XmlStructure($audit);
        $valXmlXsd->validate($xmlEncClean);
        $xml       = \simplexml_load_string($xmlEncClean);
        unset($xmlEncClean);
        if ($xml === false) {
            $msg = \sprintf(
                "Simplexml_load_string failing read string loaded from file '%s'",
                $path
            );
            \Logger::getLogger(AuditFile::class)->error($msg);
            throw new AuditFileException($msg);
        }
        $audit->parseXmlNode($xml);
        return $audit;
    }

    /**
     * Validate the SAFT-PT audit file. 
     * @param string|null $pubKeyPath
     * @param \Rebelo\SaftPt\Validate\ValidationConfig|null $config
     * @return bool True if no errors (can have warnings)
     * @since 1.0.0
     */
    public function validate(?string $pubKeyPath = null,
                             ?ValidationConfig $config = null): bool
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $sign = new \Rebelo\SaftPt\Sign\Sign();

        if ($pubKeyPath !== null) {
            $sign->setPublicKeyFilePath($pubKeyPath);
        }

        if ($config === null) {
            $config = new ValidationConfig();
            $config->setSignValidation($pubKeyPath !== null);
        }

        if ($config->getSignValidation() && $pubKeyPath === null) {
            $msg = "To validate signatures must indicate public key file path";
            \Logger::getLogger(\get_class($this))->error($msg);
            throw new AuditFileException($msg);
        }

        $throw  = [];
        $logger = \Logger::getLogger(\get_class($this));
        $pool   = \Spatie\Async\Pool::create();
        
        // TODO test async in a machine with pcntl and posix, special if in 
        // console app the progress bar over each other or not

        if ($config->getSchemaValidate()) {
            $pool->add(
                function()
                {
                                \Logger::getLogger(\get_class($this))->info("Start validating Scheme");
                                $validate = new \Rebelo\SaftPt\Validate\XmlStructure($this);
                                $xml      = $this->toXmlString();
                                $validate->validate($xml);
                }
            )->catch(
                function($exception) use (&$throw, $logger)
                {
                                /* @var $exception \Exception */
                                $logger->debug($exception);
                                $throw[] = $exception->getMessage();
                }
            );
        }
        
        // Validate Invoices
        $pool->add(
            function() use ($sign, $config)
            {
                        \Logger::getLogger(\get_class($this))->info("Start validating SalesInvoices");
                        $siVal = new \Rebelo\SaftPt\Validate\SalesInvoices($this, $sign);
                        $siVal->setConfiguration($config);
                        $siVal->validate();
            }
        )->catch(
            function($exception) use (&$throw, $logger)
            {
                        /* @var $exception \Exception */
                        $logger->debug($exception);
                        $throw[] = $exception->getMessage();
            }
        );

        // Validate MovementOfGoods
        $pool->add(
            function() use ($sign, $config)
            {
                        \Logger::getLogger(\get_class($this))->info("Start validating MovementOfGoods");
                        $mvVal = new \Rebelo\SaftPt\Validate\MovementOfGoods($this, $sign);
                        $mvVal->setConfiguration($config);
                        $mvVal->validate();
            }
        )->catch(
            function ($exception) use (&$throw, $logger)
            {
                        /* @var $exception \Exception */
                        $logger->debug($exception);
                        $throw[] = $exception->getMessage();
            }
        );

        // Validate WorkingDocuments
        $pool->add(
            function() use ($sign, $config)
            {
                        \Logger::getLogger(\get_class($this))->info("Start validating WorkingDocument");
                        $worVal = new \Rebelo\SaftPt\Validate\WorkingDocuments($this, $sign);
                        $worVal->setConfiguration($config);
                        $worVal->validate();
            }
        )->catch(
            function ($exception) use (&$throw, $logger)
            {
                        /* @var $exception \Exception */
                        $logger->debug($exception);
                        $throw[] = $exception->getMessage();
            }
        );

        // Validate Payments
        $pool->add(
            function() use ($config)
            {
                        \Logger::getLogger(\get_class($this))->info("Start validating Payments");
                        $payVal = new \Rebelo\SaftPt\Validate\Payments($this);
                        $payVal->setConfiguration($config);
                        $payVal->validate();
            }
        )->catch(
            function ($exception) use (&$throw, $logger)
            {
                        /* @var $exception \Exception */
                        $logger->debug($exception);
                        $throw[] = $exception->getMessage();
            }
        );

        // Validate OtherValidations
        $pool->add(
            function() use ($config)
            {
                        \Logger::getLogger(\get_class($this))->info("Start other validations");
                        $other = new \Rebelo\SaftPt\Validate\OtherValidations($this);
                        $other->setConfiguration($config);
                        $other->validate();
            }
        )->catch(
            function ($exception) use (&$throw, $logger)
            {
                        /* @var $exception \Exception */
                        $logger->debug($exception);
                        $throw[] = $exception->getMessage();
            }
        );
        
        \Logger::getLogger(\get_class($this))->debug("End of data validation");

        $pool->wait();

        if (\count($throw) > 0) {
            throw new AuditFileException(\join("; ", $throw));
        }

        return !$this->getErrorRegistor()->hasErrors();
    }
}
