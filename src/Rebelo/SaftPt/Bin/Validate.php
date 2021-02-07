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

namespace Rebelo\SaftPt\Bin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Rebelo\SaftPt\Validate\ValidationConfig;

/**
 * The saft validation command
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Validate extends Command
{

    /**
     * The command name
     * @since 1.0.0
     */
    public const COMMAND_NAME = "validate";

    /**
     * The default laguage pack to be used
     * @since 1.0.0
     */
    public const DEFAULT_LANG = "en_GB";

    /**
     * Argument name of the saft file path
     * @since 1.0.0
     */
    public const ARG_SAFT_FILE_PATH = "SAFT_PATH";

    /**
     * Option name of the public key file path
     * @since 1.0.0
     */
    public const OPT_PUB_KEY_PATH = "pubkey";

    /**
     * Option name of the public key file path
     * @since 1.0.0
     */
    public const OPT_PUB_KEY_PATH_SHORT = "p";

    /**
     * Option name of the log configuration file path
     * @since 1.0.0
     */
    public const OPT_LOG4PHP_CONG = "log";

    /**
     * Option name of the log configuration file path
     * @since 1.0.0
     */
    public const OPT_LOG4PHP_CONG_SHORT = "l";

    /**
     * Option name of show warnings
     * @since 1.0.0
     */
    public const OPT_SHOW_WARNINGS = "warnings";

    /**
     * Option name of show warnings
     * @since 1.0.0
     */
    public const OPT_SHOW_WARNINGS_SHORT = "w";

    /**
     * Argument name of the idiome
     * @since 1.0.0
     */
    public const OPT_LANG = "lang";

    /**
     * Argument name of the idiome
     * @since 1.0.0
     */
    public const OPT_LANG_SHORT = "g";

    /**
     * Argument name of the configuration option if 
     * accepts Debit and Credit lines in the same document
     * @since 1.0.0
     */
    public const OPT_DEBIT_CREDIT = "debcre";

    /**
     * Argument name of the configuration option 
     * of the delta in currency calculation to be consider valid
     * @since 1.0.0
     */
    public const OPT_DELTA_CURRENCY = "dc";

    /**
     * Argument name of the configuration option 
     * of the delta in lines calculation to be consider valid
     * @since 1.0.0
     */
    public const OPT_DELTA_LINES = "dl";

    /**
     * Argument name of the configuration option 
     * of the delta in tables (sum of all documents) 
     * calculation to be consider valid
     * @since 1.0.0
     */
    public const OPT_DELTA_TABLE = "dt";

    /**
     * Argument name of the configuration option 
     * of the delta in document totals 
     * calculation to be consider valid
     * @since 1.0.0
     */
    public const OPT_DELTA_TOTAL_DOC = "ddt";

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * App start time
     * @since 1.0.0
     */
    public static int $start;

    /**
     * 
     * @param mixed $name
     * @since 1.0.0
     */
    public function __construct(mixed $name = null)
    {
        parent::__construct($name);
    }

    /**
     * The name of the command
     * @var string|null
     * @since 1.0.0
     */
    protected static $defaultName = self::COMMAND_NAME;

    /**
     * The command configuration
     * @return void
     * @since 1.0.0
     */
    protected function configure(): void
    {
        $this->setDescription('Validate a SAFT-PT audit file version 1.04');
        $this->setHelp(
            'This command will validate a SAFT-PT version 1.04, '
                . 'if no public key path option is set the document’s signatures ‘hash’ '
                . 'will no be validated. '
                . 'A calculation delta can be set that is accepted as no error.'
        );

        $this->addArgument(
            static::ARG_SAFT_FILE_PATH, InputArgument::REQUIRED,
            "The path of the SAFT-PT file"
        );

        $this->addOption(
            static::OPT_PUB_KEY_PATH, static::OPT_PUB_KEY_PATH_SHORT,
            InputOption::VALUE_OPTIONAL,
            "The path of the file with the public key to verify the document's signature",
            null
        );

        $this->addOption(
            static::OPT_LOG4PHP_CONG, static::OPT_LOG4PHP_CONG_SHORT,
            InputOption::VALUE_OPTIONAL,
            "The path of the file with the log4php configuration"
                . "(see: https://logging.apache.org/log4php/docs/configuration.html ),"
                . "default log4php.xml", null
        );

        $this->addOption(
            static::OPT_LANG, static::OPT_LANG_SHORT,
            InputOption::VALUE_OPTIONAL, "The validation output language",
            self::DEFAULT_LANG
        );

        $this->addOption(
            static::OPT_SHOW_WARNINGS, static::OPT_SHOW_WARNINGS_SHORT,
            InputOption::VALUE_OPTIONAL,
            "Define if show warnings list, the number of warning is always show, defualt true",
            null
        );

        $this->addOption(
            static::OPT_DEBIT_CREDIT, null, InputOption::VALUE_OPTIONAL,
            "Accepts Debit and Credit lines in the same document (yes|no|true|false|0|1).",
            false
        );

        $this->addOption(
            static::OPT_DELTA_CURRENCY, null, InputOption::VALUE_OPTIONAL,
            "Delta in currency calculation to be consider valid.", "0.0"
        );

        $this->addOption(
            static::OPT_DELTA_LINES, null, InputOption::VALUE_OPTIONAL,
            "Delta in document lines calculation to be consider valid.", "0.01"
        );

        $this->addOption(
            static::OPT_DELTA_TABLE, null, InputOption::VALUE_OPTIONAL,
            "Delta in document tables (sum of all documents) "
                . "calculation to be consider valid.", "0.01"
        );

        $this->addOption(
            static::OPT_DELTA_TOTAL_DOC, null, InputOption::VALUE_OPTIONAL,
            "Delta in document document's totals calculation to be consider valid.",
            "0.01"
        );
    }

    /**
     * Execute the command
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @since 1.0.0
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @phpstan-ignore-next-line */
        $io = new Style($input, $output);
        $io->title("SAFT-PT 4 PHP by João M F Rebelo");
        try {

            $this->setLog($io, $input);
            $this->setLang($io, $input);

            $showWarningsOpt = $input->getOption(self::OPT_SHOW_WARNINGS);
            $showWarnings = $showWarningsOpt === null ? true :
                    $this->parseBool(
                        $showWarningsOpt, self::OPT_SHOW_WARNINGS
                    );

            $saft = $input->getArgument(static::ARG_SAFT_FILE_PATH);
            if (\is_string($saft) === false) {
                throw new \Exception("Saft file path is not set");
            }

            $io->writeln(
                "<info>" . \sprintf(
                    AuditFile::getI18n()->get("validating_file"), $saft
                ) . "</info>"
            );

            $pubKey = $this->getPubKeyPath($input);
            $config = $this->getValidationConfig($input);
            $config->setSignValidation($pubKey !== null);
            $config->setStyle($io);
            // The AuditFile::loadFile always validate schema so
            // is not necessary to validate twice
            $config->setSchemaValidate(false);

            $audit = AuditFile::loadFile($saft);

            $audit->validate($pubKey, $config);

            $io->newLine();

            $nWar = \count($audit->getErrorRegistor()->getWarnings());
            if ($nWar > 0) {
                $io->warning(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_warnings"), $nWar
                    )
                );
                if($showWarnings){
                    $io->listing($audit->getErrorRegistor()->getWarnings());
                }
            }

            if ($audit->getErrorRegistor()->hasErrors() === false) {
                $io->success(AuditFile::getI18n()->get("validation_no_error"));
                $this->printStatistic($io, $saft);
                return Command::SUCCESS;
            }

            $nStructur = \count($audit->getErrorRegistor()->getLibXmlError());
            if ($nStructur > 0) {
                $io->error(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_xml_structure_erros"),
                        $nStructur
                    )
                );
                $io->listing($audit->getErrorRegistor()->getLibXmlError());
            }

            $nSetValues = \count($audit->getErrorRegistor()->getOnSetValue());
            if ($nSetValues > 0) {
                $io->error(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_errors_on_set_value"),
                        $nSetValues
                    )
                );
                $io->listing($audit->getErrorRegistor()->getOnSetValue());
            }

            $nCreateXml = \count($audit->getErrorRegistor()->getOnCreateXmlNode());
            if ($nCreateXml > 0) {
                $io->error(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_errors_on_create_xml_node"),
                        $nCreateXml
                    )
                );
                $io->listing($audit->getErrorRegistor()->getOnCreateXmlNode());
            }

            $nValidation = \count($audit->getErrorRegistor()->getValidationErrors());
            if ($nValidation > 0) {
                $io->error(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_errors_on_data_validation"),
                        $nValidation
                    )
                );
                $io->listing($audit->getErrorRegistor()->getValidationErrors());
            }

            $nException = \count($audit->getErrorRegistor()->getExceptionErrors());
            if ($nException > 0) {
                $io->error(
                    \sprintf(
                        AuditFile::getI18n()->get("has_n_exception"),
                        $nException
                    )
                );
                $io->listing($audit->getErrorRegistor()->getExceptionErrors());
            }
            
            
            $this->printStatistic($io, $saft);

            return Command::SUCCESS;
        } catch (\Exception | \Error $ex) {
            $io->error($ex->getMessage());
            $this->log->error($ex->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Set the language pack
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return void
     * @since 1.0.0
     */
    protected function setLang(SymfonyStyle $io, InputInterface $input): void
    {
        $lang = $input->getOption(self::OPT_LANG);
        if (\is_string($lang)) {

            if(null === $lang = \preg_replace("/^=/", "", $lang)){
                throw new \Exception("Failed apply preg_replace to '$lang'");
            }

            switch (\strtolower($lang)) {
                case null:
                case 'en':
                case 'en_gb':
                case 'engb':
                    AuditFile::setI18n(new \Rebelo\SaftPt\AuditFile\i18n\en_GB());
                    break;
                case 'pt':
                case 'pt_pt':
                case 'ptpt':
                    AuditFile::setI18n(new \Rebelo\SaftPt\AuditFile\i18n\pt_PT());
                    break;
                default :
                    $io->writeln(
                        \sprintf(
                            "<info>Note:</> Language pack '%s' does not exist, setting to '%s'.",
                            $lang, self::DEFAULT_LANG
                        )
                    );
                    AuditFile::setI18n(new \Rebelo\SaftPt\AuditFile\i18n\en_GB());
            }
        }
    }

    /**
     * Set the log
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return void
     * @since 1.0.0
     */
    protected function setLog(SymfonyStyle $io, InputInterface $input): void
    {

        $logFile = $input->getOption(self::OPT_LOG4PHP_CONG);

        if (\is_string($logFile)) {

            if(null === $logFile = \preg_replace("/^=/", "", $logFile)){
            throw new \Exception("Failed apply preg_replace to '$logFile'");
            }

            if (\file_exists($logFile)) {
                AuditFile::$log4phpConfigFilePath = $logFile;
                \Logger::configure($logFile);
                $this->log = \Logger::getLogger(\get_class($this));
                return;
            } else {
                $io->writeln(
                    \sprintf(
                        "<info>Note:</> Log4php configuration file '%s' does not exist, set the default.",
                        $logFile
                    )
                );
            }
        }

        $default = __DIR__;
        for ($n = 0; $n <= 3; $n++) {
            $default .= DIRECTORY_SEPARATOR . "..";
        }
        $logFileDef = $default . DIRECTORY_SEPARATOR . "log4php.xml";
        AuditFile::$log4phpConfigFilePath = $logFileDef;
        \Logger::configure($logFileDef);
        $this->log = \Logger::getLogger(\get_class($this));
        return;
    }

    /**
     * Get the Pub key file path option
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return string|null
     * @throws \Exception
     * @since 1.0.0
     */
    protected function getPubKeyPath(InputInterface $input): ?string
    {
        $pubKey = $input->getOption(self::OPT_PUB_KEY_PATH);
        if ($pubKey === null) {
            return null;
        }

        if (\is_string($pubKey) === false) {
            throw new \Exception(
                "Public key file path not set properly"
            );
        }

        if(null === $pubKeyClean = \preg_replace("/^=/", "", $pubKey)){
            throw new \Exception("Failed apply preg_replace to '$pubKey'");
        }

        if (\file_exists($pubKeyClean) === false) {
            throw new \Exception(
                \sprintf("Public key file path '%s' not exist", $pubKeyClean)
            );
        }

        return $pubKeyClean;
    }

    /**
     * Get the validation config set by the input options
     * @param InputInterface $input
     * @return ValidationConfig
     * @since 1.0.0
     */
    public function getValidationConfig(InputInterface $input): ValidationConfig
    {
        $config = new ValidationConfig();

        $debcred = $input->getOption(self::OPT_DEBIT_CREDIT);
        $config->setAllowDebitAndCredit(
            $debcred === null ? true :
                        $this->parseBool(
                            $debcred, self::OPT_DEBIT_CREDIT
                        )
        );

        $config->setDeltaCurrency(
            $this->parseFloat(
                $input->getOption(self::OPT_DELTA_CURRENCY),
                self::OPT_DELTA_CURRENCY
            )
        );

        $config->setDeltaLine(
            $this->parseFloat(
                $input->getOption(self::OPT_DELTA_LINES), self::OPT_DELTA_LINES
            )
        );

        $config->setDeltaTable(
            $this->parseFloat(
                $input->getOption(self::OPT_DELTA_TABLE), self::OPT_DELTA_TABLE
            )
        );

        $config->setDeltaTotalDoc(
            $this->parseFloat(
                $input->getOption(self::OPT_DELTA_TOTAL_DOC),
                self::OPT_DELTA_TOTAL_DOC
            )
        );

        return $config;
    }

    /**
     * 
     * @param mixed $value
     * @param string $name
     * @return bool
     * @throws \Exception
     * @since 1.0.0
     */
    public function parseBool($value, $name): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\in_array($value, [0, 1, "0", "1"], true)) {
            return $value === 1 || $value === "1";
        }

        if (\is_string($value)) {
            switch (\preg_replace("/^=/", "", \strtolower($value))) {
                case "true":
                case "on":
                case "yes":
                case "1":
                    return true;
                case "false":
                case "off":
                case "no":
                case "0":
                    return false;
            }
        }

        throw new \Exception(
            \sprintf(
                "The option '%s' must be a boolean and is not", $name
            )
        );
    }

    /**
     * 
     * @param mixed $value
     * @param string $name
     * @return float
     * @throws \Exception
     * @since 1.0.0
     */
    public function parseFloat($value, $name): float
    {
        if (\is_string($value)) {
            $valueClean = \preg_replace("/^=/", "", $value);
        } else {
            $valueClean = $value;
        }

        if (\is_numeric($valueClean)) {
            return (float) $valueClean;
        }

        throw new \Exception(
            \sprintf(
                "The option '%s' must be a float and is not", $name
            )
        );
    }

    /**
     * Prints the app running statistic
     * @param \Rebelo\SaftPt\Bin\Style $io
     * @param string $saft The saft-pt file path
     * @return void
     */
    protected function printStatistic(Style $io, string $saft): void
    {
        $exectime = \time() - self::$start;
        $exec = \sprintf(
            "%sm %ss", \floor($exectime / 60), $exectime % 60
        );
        $mem = \number_format(\memory_get_peak_usage(true), 0, "", " ");
        $size = \number_format((float) \filesize($saft), 0, "", " ");
        $io->definitionList(
            [AuditFile::getI18n()->get("memory") . ":" => $mem . " Bytes"],
            [AuditFile::getI18n()->get("saft_file") . ":" => $size . " Bytes"],
            [AuditFile::getI18n()->get("exec_time") . ":" => $exec]
        );
    }

}
