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

/**
 * Abstract of AAuditFile
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AAuditFile
{

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
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
                    ->debug(\sprintf(__METHOD__." cloning error '%s'",
                            $e->getMessage()));
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
     * @return string
     * @throws AuditFileException
     * @since 1.0.0
     */
    public static function valTextMandMaxCar(string $string, int $length,
                                             string $method): string
    {
        $subString = \substr(\trim($string), 0, $length);
        if (strlen($subString) === 0 || is_bool($subString)) {
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
            $nif = str_pad($nif, 9, 0, STR_PAD_LEFT);
        }
        $checkerVal = 0;
        $c          = array();
        for ($i = strlen($nif) - 1; $i >= 0; $i--) {
            $c[] = $nif{$i};
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
     * @param string $dec_point The decimal separator
     * @param string $thousands_sep the thousends separator
     * @return string
     * @since 1.0.0
     */
    public function floatFormat(float $float, int $decimals = 6,
                                string $dec_point = ".",
                                string $thousands_sep = ""): string
    {
        return \number_format($float, $decimals, $dec_point, $thousands_sep);
    }
}