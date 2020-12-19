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

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\SaftPt\AuditFile\i18n\AI18n;
use Rebelo\SaftPt\AuditFile\i18n\pt_PT;
use Rebelo\Date\Date as RDate;

/**
 * Abstract of AAuditFile
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AAuditFile
{
    /**
     * Unknown word
     * @since 1.0.0
     */
    const DESCONHECIDO = "Desconhecido";

    /**
     * Unknown word
     * @since 1.0.0
     */
    const CONSUMIDOR_FINAL_TAX_ID = "999999990";

    /**
     * Final Consumer, Consumidor final
     * @since 1.0.0
     */
    const CONSUMIDOR_FINAL = "Consumidor final";

    /**
     * The ID in consumer table of the final Consumer, Consumidor final
     * @since 1.0.0
     */
    const CONSUMIDOR_FINAL_ID = "CONSUMIDOR_FINAL";

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\i18n\AI18n
     * @since 1.0.0
     */
    protected static AI18n $i18n;

    /**
     * Error Register, to register global validation and errors
     * @var \Rebelo\SaftPt\AuditFile\ErrorRegister
     * @since 1.0.0
     */
    protected ErrorRegister $errorRegister;

    /**
     * To registe particular validation and errors of documents or tables,
     * the key must be the field name
     * @var string[]
     * @since 1.0.0
     */
    protected array $error = array();

    /**
     * To regist particular warnings of documents or tables
     * @var string[]
     * @since 1.0.0
     */
    protected array $warning = array();

    /**
     * Invoke the isset to the propertie name
     * @param string $name The propertie name to check
     * @return bool
     * @since 1.0.0
     */
    public function __isset(string $name) : bool
    {
        return isset($this->{$name});
    }

    /**
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->errorRegister = $errorRegister;
    }

    /**
     * Create the xml node for the object
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public abstract function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement;

    /**
     * Create the xml node for the object
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public abstract function parseXmlNode(\SimpleXMLElement $node): void;

    /**
     * Force to clone all object properties
     * @since 1.0.0
     */
    public function __clone()
    {
        $refClass = new \ReflectionClass($this);

        foreach ($refClass->getProperties() as $prop) {
            /* @var $prop \ReflectionProperty */
            $prop->setAccessible(true);
            try {
                $value = $prop->getValue($this);
                if (\is_object($value)) {
                    $prop->setValue($this, clone $value);
                }
            } catch (\Error $e) {
                \Logger::getLogger(\get_class($this))
                    ->debug(
                        \sprintf(
                            __METHOD__." cloning error '%s'", $e->getMessage()
                        )
                    );
            }
        }
    }

    /**
     * Validate the string if length is zero throws AuditFileException, if
     * greater than $lentgh will return a truncated string
     *
     * @param string $string
     * @param int $length
     * @param string $method
     * @param bool $trucate If truncate is set to <code>false</code> and the string is bigger will throw AuditFileException
     * @return string
     * @throws AuditFileException
     * @since 1.0.0
     */
    public static function valTextMandMaxCar(string $string, int $length,
                                             string $method,
                                             bool $trucate = true): string
    {
        if ($trucate === false && \strlen($string) > $length) {
            $msg = \sprintf(
                "string length '%s' is bigger than '\$length' '%s' ",
                (string) \strlen($string), (string) $length
            );
            \Logger::getLogger(__CLASS__)
                ->error(\sprintf($method." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $subString = \substr(\trim($string), 0, $length);
        if (strlen($subString) === 0) {
            $msg = "string can not be empty";
            \Logger::getLogger(__CLASS__)
                ->error(\sprintf($method." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        return $subString;
    }

    /**
     * Validate Portuguese VAT Number
     * @param int $nif
     * @return bool
     * @since 1.0.0
     */
    public static function valPortugueseVatNumber(int $nif): bool
    {
        if ((preg_match("/^[1235689]{1}[0-9]{8}$/", \strval($nif)) &&
            self::validateMod11auxFunction(\strval($nif))) ||
            $nif === 999999990) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * validate Mod11 numbers
     * (true whene the remaining of the division per 11 is 0)
     * number % 11 === 0
     *
     * @param string $nif
     * @return bool
     * @since 1.0.0
     */
    public static function validateMod11auxFunction(string $nif): bool
    {
        if (\strlen($nif) < 9) {
            $nif = \str_pad($nif, 9, "0", STR_PAD_LEFT);
        }
        $checkerVal = 0;
        $c          = array();
        for ($i = \strlen($nif) - 1; $i >= 0; $i--) {
            $c[] = \intval(\substr($nif, $i, 1));
        }

        foreach ($c as $k => $v) {
            $checkerVal += ($k + 1) * $v;
        }
        if (($checkerVal % 11) === 0) {
            return true;
        } else {
            $checkerVal = 0;
            $c[0]       = 10;
            foreach ($c as $k => $v) {
                $checkerVal += ($k + 1) * $v;
            }
            if (($checkerVal % 11) === 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Format a float with grouped thousands
     *
     * @param float $float The float to be format
     * @param int $decimals Number of decimals
     * @param string $decPoint The decimal separator
     * @param string $thousandsSep the thousends separator
     * @return string
     * @since 1.0.0
     */
    public function floatFormat(float $float, int $decimals = 6,
                                string $decPoint = ".",
                                string $thousandsSep = ""): string
    {
//        if (IS_UNIT_TEST) {
//            return \strval($float);
//        }
        return \number_format($float, $decimals, $decPoint, $thousandsSep);
    }

    /**
     * Convert the encoded caracters encoded by SimpleXmlElment
     * @param string $string
     * @return string
     * @since 1.0.0
     */
    public static function replaceHexUtf(string &$string): string
    {
        $utf    = array(
            "&#xA1;" => "¡",
            "&#xA2;" => "¢",
            "&#xA3;" => "£",
            "&#xA4;" => "¤",
            "&#xA5;" => "¥",
            "&#xA6;" => "¦",
            "&#xA7;" => "§",
            "&#xA8;" => "¨",
            "&#xA9;" => "©",
            "&#xAA;" => "ª",
            "&#xAB;" => "«",
            "&#xAC;" => "¬",
            "&#xAD;" => "­",
            "&#xAE;" => "®",
            "&#xAF;" => "¯",
            "&#xB0;" => "°",
            "&#xB1;" => "±",
            "&#xB2;" => "²",
            "&#xB3;" => "³",
            "&#xB4;" => "´",
            "&#xB5;" => "µ",
            "&#xB6;" => "¶",
            "&#xB7;" => "·",
            "&#xB8;" => "¸",
            "&#xB9;" => "¹",
            "&#xBA;" => "º",
            "&#xBB;" => "»",
            "&#xBC;" => "¼",
            "&#xBD;" => "½",
            "&#xBE;" => "¾",
            "&#xBF;" => "¿",
            "&#xC0;" => "À",
            "&#xC1;" => "Á",
            "&#xC2;" => "Â",
            "&#xC3;" => "Ã",
            "&#xC4;" => "Ä",
            "&#xC5;" => "Å",
            "&#xC6;" => "Æ",
            "&#xC7;" => "Ç",
            "&#xC8;" => "È",
            "&#xC9;" => "É",
            "&#xCA;" => "Ê",
            "&#xCB;" => "Ë",
            "&#xCC;" => "Ì",
            "&#xCD;" => "Í",
            "&#xCE;" => "Î",
            "&#xCF;" => "Ï",
            "&#xD0;" => "Ð",
            "&#xD1;" => "Ñ",
            "&#xD2;" => "Ò",
            "&#xD3;" => "Ó",
            "&#xD4;" => "Ô",
            "&#xD5;" => "Õ",
            "&#xD6;" => "Ö",
            "&#xD7;" => "×",
            "&#xD8;" => "Ø",
            "&#xD9;" => "Ù",
            "&#xDA;" => "Ú",
            "&#xDB;" => "Û",
            "&#xDC;" => "Ü",
            "&#xDD;" => "Ý",
            "&#xDE;" => "Þ",
            "&#xDF;" => "ß",
            "&#xE0;" => "à",
            "&#xE1;" => "á",
            "&#xE2;" => "â",
            "&#xE3;" => "ã",
            "&#xE4;" => "ä",
            "&#xE5;" => "å",
            "&#xE6;" => "æ",
            "&#xE7;" => "ç",
            "&#xE8;" => "è",
            "&#xE9;" => "é",
            "&#xEA;" => "ê",
            "&#xEB;" => "ë",
            "&#xEC;" => "ì",
            "&#xED;" => "í",
            "&#xEE;" => "î",
            "&#xEF;" => "ï",
            "&#xF0;" => "ð",
            "&#xF1;" => "ñ",
            "&#xF2;" => "ò",
            "&#xF3;" => "ó",
            "&#xF4;" => "ô",
            "&#xF5;" => "õ",
            "&#xF6;" => "ö",
            "&#xF7;" => "÷",
            "&#xF8;" => "ø",
            "&#xF9;" => "ù",
            "&#xFA;" => "ú",
            "&#xFB;" => "û",
            "&#xFC;" => "ü",
            "&#xFD;" => "ý",
            "&#xFE;" => "þ",
            "&#xFF;" => "ÿ");
        $string = \str_replace(
            \array_keys($utf), $utf, $string
        );
        return $string;
    }

    /**
     * Set the language to translate
     * @param \Rebelo\SaftPt\AuditFile\i18n\AI18n $i18n
     * @return void
     * @since 1.0.0
     */
    public static function setI18n(AI18n $i18n): void
    {
        static::$i18n = $i18n;
        \Logger::getLogger(__CLASS__)
            ->error(
                \sprintf(" I18n set to '%s'", \get_class(static::$i18n))
            );
    }

    /**
     * Get i18n class
     * @return \Rebelo\SaftPt\AuditFile\i18n\AI18n
     * @since 1.0.0
     */
    public static function getI18n(): AI18n
    {
        if (isset(static::$i18n) === false) {
            static::$i18n = new pt_PT();
        }
        return static::$i18n;
    }

    /**
     * Get the ErrorRegistor instance
     * @return \Rebelo\SaftPt\AuditFile\ErrorRegister
     * @since 1.0.0
     */
    public function getErrorRegistor(): ErrorRegister
    {
        return $this->errorRegister;
    }

    /**
     * Get all particular error
     * @return string[]
     * @since 1.0.0
     */
    public function getError(): array
    {
        return $this->error;
    }

    /**
     * Get all particular warning
     * @return string[]
     * @since 1.0.0
     */
    public function getWarning(): array
    {
        return $this->warning;
    }

    /**
     * Add a particular error
     * @param string $error
     * @param string|null $field The field name with error, will be used as array key, if null array key will be numeric
     * @return void
     * @since 1.0.0
     */
    public function addError(string $error, ?string $field = null): void
    {
        if ($field === null) {
            $this->error[] = $error;
        } else {
            $this->error[$field] = $error;
        }
    }

    /**
     * Add a particular warning
     * @param string $warning
     * @return void
     * @since 1.0.0
     */
    public function addWarning(string $warning): void
    {
        $this->warning[] = $warning;
    }

    /**
     * Validate documents number
     * &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *           &lt;xs:minLength value="1"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * @param string $docNumber
     * @return bool
     * @since 1.0.0
     */
    public static function validateDocNumber(string $docNumber): bool
    {
        if (\strlen($docNumber) > 60 ||
            \strlen($docNumber) < 1 ||
            \preg_match("/[^ ]+ [^\/^ ]+\/[0-9]+/", $docNumber) !== 1
        ) {
            return false;
        }
        return true;
    }
    
    /**
     * Calc the document period based on the fiscal year start month
     * @param int $fiscalYearStartMonth
     * @param \Rebelo\Date\Date $docDate
     * @return int
     * @throws CalcPeriodException
     * @since 1.0.0
     */
    public static function calcPeriod(int $fiscalYearStartMonth, RDate $docDate) : int
    {
        if($fiscalYearStartMonth < 1 || $fiscalYearStartMonth > 12){
            throw new CalcPeriodException("wrong fiscal year start month");
        }
        
        $docMonth = (int)$docDate->format(RDate::MONTH_SHORT);
        
        if($fiscalYearStartMonth === 1){
            return $docMonth;
        }
        
        if($docMonth >= $fiscalYearStartMonth){
            return ($docMonth - $fiscalYearStartMonth) + 1;
        }
        
        return (13 - $fiscalYearStartMonth) + $docMonth;
    }
    
}
