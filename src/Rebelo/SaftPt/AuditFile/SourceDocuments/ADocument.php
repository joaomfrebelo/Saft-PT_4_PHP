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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\TransactionID;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\Validate\DocTotalCalc;

/**
 * ADocument
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class ADocument extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_ATCUD = "ATCUD";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_HASH = "Hash";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_HASHCONTROL = "HashControl";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PERIOD = "Period";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEID = "SourceID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_EACCODE = "EACCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SYSTEMENTRYDATE = "SystemEntryDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONID = "TransactionID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMERID = "CustomerID";

    /**
     * The calulated values made by the validation classes
     * @var \Rebelo\SaftPt\Validate\DocTotalCalc
     * @since 1.0.0
     */
    protected ?DocTotalCalc $docTotalcal = null;

    /**
     *
     * <pre>
     * &lt;xs:element ref = "ATCUD"/&gt;
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * </pre>
     * @since 1.0.0
     * @var string
     */
    protected string $atcud;

    /**
     * <pre>
     * &lt;xs:element ref = "Hash"/&gt;
     * &lt;xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    protected string $hash;

    /**
     * <pre>
     * &lt;xs:element ref = "HashControl"/&gt;
     * &lt;xs:element name="HashControl" type="SAFPTHashControl"/&gt;
     * &lt;xs:simpleType name="SAFPTHashControl"&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *      &lt;xs:pattern value="[0-9]+|[0-9]+[.][0-9]+|[0-9]+-[A-Z]{2}(M )([^ ]+[/][0-9]+)|[0-9]+-[A-Z]{2}(D )([^ ]+ [^/^ ]+[/][0-9]+)"&gt;&lt;/xs:pattern&gt;
     *      &lt;xs:minLength value="1"/&gt;
     *      &lt;xs:maxLength value="70"/&gt;
     *  &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    protected string $hashControl;

    /**
     * <pre>
     *  &lt;xs:element ref = "Period" minOccurs = "0"/&gt;
     *  &lt;xs:element name="Period"&gt;
     *    &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:integer"&gt;
     *           &lt;xs:minInclusive value="1"/&gt;
     *           &lt;xs:maxInclusive value="12"/&gt;
     *       &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @var int|null
     * @since 1.0.0
     */
    protected ?int $period = null;

    /**
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    protected string $sourceID;

    /**
     * <pre>
     *  &lt;xs:element name="EACCode"&gt;
     *  &lt;xs:simpleType&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:pattern value="(([0-9]*))"/&gt;
     *          &lt;xs:length value="5"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $eacCode = null;

    /**
     * &lt;xs:element ref = "SystemEntryDate"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    protected RDate $systemEntryDate;

    /**
     * &lt;xs:element ref = "TransactionID" minOccurs = "0"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    protected ?TransactionID $transactionID = null;

    /**
     * &lt;xs:element ref = "CustomerID"/&gt;
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $customerID;

    /**
     * Abstract class for common method and properties of WorkDocument,
     * StockMovement and invoice
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get Set the ATCUD
     * This field shall contain the Document Unique Code.
     * The field shall be filled in with '0' (zero) until its regulation.
     * <pre>
     * &lt;xs:element ref = "ATCUD"/&gt;
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getAtcud(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->atcud));
        return $this->atcud;
    }

    /**
     * Get if is set Atcud
     * @return bool
     * @since 1.0.0
     */
    public function issetAtcud(): bool
    {
        return isset($this->atcud);
    }

    /**
     * Set the ATCUD<br>
     * This field shall contain the Document Unique Code.
     * The field shall be filled in with '0' (zero) until its regulation.
     * <pre>
     * &lt;xs:element ref = "ATCUD"/&gt;
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * </pre>
     * @param string $atcud
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setAtcud(string $atcud): bool
    {
        try {
            $this->atcud = $this->valTextMandMaxCar(
                $atcud, 100, __METHOD__, false
            );
            $return      = true;
        } catch (AuditFileException $e) {
            $this->atcud = $atcud;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Atcud_not_valid");
            $return      = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->atcud));
        return $return;
    }

    /**
     * Get hash<br>
     * The signature in the terms of Ordinance nº 363/2010 of 23rd June.
     * The field shall be filled in with “0” (zero), in case the certification is not required.
     * <pre>
     * &lt;xs:element ref = "Hash"/&gt;
     * &lt;xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getHash(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->hash));
        return $this->hash;
    }

    /**
     * Get if is set Hash
     * @return bool
     * @since 1.0.0
     */
    public function issetHash(): bool
    {
        return isset($this->hash);
    }

    /**
     * Set Hash<br>
     * The signature in the terms of Ordinance nº 363/2010 of 23rd June.
     * The field shall be filled in with “0” (zero), in case the certification is not required.
     * <pre>
     * &lt;xs:element ref = "Hash"/&gt;
     * &lt;xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/&gt;
     * </pre>
     * @param string $hash
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setHash(string $hash): bool
    {
        try {
            $this->hash = $this->valTextMandMaxCar($hash, 172, __METHOD__, false);
            $return     = true;
        } catch (AuditFileException $e) {
            $this->hash = $hash;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Hash_not_valid");
            $return     = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->hash));
        return $return;
    }

    /**
     * Get hash control<br>
     * Version of the protected key used in the creation of signature of field 4.2.3.4. – Hash.
      The field shall be filled in with "0" (zero) if the document is generated by a non-certified program.
     * <pre>
     * &lt;xs:element ref = "HashControl"/&gt;
     * &lt;xs:element name="HashControl" type="SAFPTHashControl"/&gt;
     * &lt;xs:simpleType name="SAFPTHashControl"&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *      &lt;xs:pattern value="[0-9]+|[0-9]+[.][0-9]+|[0-9]+-[A-Z]{2}(M )([^ ]+[/][0-9]+)|[0-9]+-[A-Z]{2}(D )([^ ]+ [^/^ ]+[/][0-9]+)"&gt;&lt;/xs:pattern&gt;
     *      &lt;xs:minLength value="1"/&gt;
     *      &lt;xs:maxLength value="70"/&gt;
     *  &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getHashControl(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->hashControl));
        return $this->hashControl;
    }

    /**
     * Get if is set HashControl
     * @return bool
     * @since 1.0.0
     */
    public function issetHashControl(): bool
    {
        return isset($this->hashControl);
    }

    /**
     * Set hash control<br>
     * Version of the protected key used in the creation of signature of field 4.2.3.4. – Hash.
     * The field shall be filled in with "0" (zero) if the document is generated by a non-certified program.
     *
     * <pre>
     * &lt;xs:element ref = "HashControl"/&gt;
     * &lt;xs:element name="HashControl" type="SAFPTHashControl"/&gt;
     * &lt;xs:simpleType name="SAFPTHashControl"&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *      &lt;xs:pattern value="[0-9]+|[0-9]+[.][0-9]+|[0-9]+-[A-Z]{2}(M )([^ ]+[/][0-9]+)|[0-9]+-[A-Z]{2}(D )([^ ]+ [^/^ ]+[/][0-9]+)"&gt;&lt;/xs:pattern&gt;
     *      &lt;xs:minLength value="1"/&gt;
     *      &lt;xs:maxLength value="70"/&gt;
     *  &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @param string $hashControl
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setHashControl(string $hashControl): bool
    {
        $pattern = "/[0-9]+|[0-9]+[.][0-9]+|[0-9]+-[A-Z]{2}(M )"
            ."([^ ]+[\/][0-9]+)|[0-9]+-[A-Z]{2}(D )([^ ]+ [^\/^ ]+[\/][0-9]+)/";
        if (\strlen($hashControl) < 1 ||
            \strlen($hashControl) > 70 ||
            \preg_match($pattern, $hashControl) !== 1) {
            $msg    = "HashControl must respect the regexp and length must be between 1 and 70";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("HashControl_not_valid");
        } else {
            $return = true;
        }
        $this->hashControl = $hashControl;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->hashControl));
        return $return;
    }

    /**
     * Get period<br>
     * The month of the taxation period shall be indicated from “1” to “12”,
     * counting from the start.
     * <pre>
     *  &lt;xs:element ref = "Period" minOccurs = "0"/&gt;
     *  &lt;xs:element name="Period"&gt;
     *    &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:integer"&gt;
     *           &lt;xs:minInclusive value="1"/&gt;
     *           &lt;xs:maxInclusive value="12"/&gt;
     *       &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @return int|null
     * @since 1.0.0
     */
    public function getPeriod(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->period));
        return $this->period;
    }

    /**
     * Set period<br>
     * The month of the taxation period shall be indicated from “1” to “12”,
     * counting from the start.
     * <pre>
     *  &lt;xs:element ref = "Period" minOccurs = "0"/&gt;
     *  &lt;xs:element name="Period"&gt;
     *    &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:integer"&gt;
     *           &lt;xs:minInclusive value="1"/&gt;
     *           &lt;xs:maxInclusive value="12"/&gt;
     *       &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param int|null $period
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setPeriod(?int $period): bool
    {
        if ($period !== null && ($period < 1 || $period > 12)) {
            $msg    = "Period must be null or between 1 and 12";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("Period_not_valid");
        } else {
            $return = true;
        }
        $this->period = $period;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->period === null ? "null" : \strval($this->period)
                )
            );
        return $return;
    }

    /**
     * Get sourceiID<br>
     * User who created the document
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
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
     * Set sourceiID<br>
     * User who created the document
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @param string $sourceID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): bool
    {
        try {
            $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
            $return         = true;
        } catch (AuditFileException $e) {
            $this->sourceID = $sourceID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SourceID_not_valid");
            $return         = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'", $this->sourceID
                )
            );
        return $return;
    }

    /**
     * Get EacCode<br>
     * Code of the economic activity to which the document relates shall be indicated.
     * <pre>
     *  &lt;xs:element name="EACCode"&gt;
     *  &lt;xs:simpleType&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:pattern value="(([0-9]*))"/&gt;
     *          &lt;xs:length value="5"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @return string|null
     * @since 1.0.0
     */
    public function getEacCode(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->eacCode === null ? "null" : $this->eacCode
                )
            );
        return $this->eacCode;
    }

    /**
     * Set EacCode<br>
     * Code of the economic activity to which the document relates shall be indicated.
     * <pre>
     *  &lt;xs:element name="EACCode"&gt;
     *  &lt;xs:simpleType&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:pattern value="(([0-9]*))"/&gt;
     *          &lt;xs:length value="5"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param string|null $eacCode
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setEacCode(?string $eacCode): bool
    {
        if ($eacCode !== null &&
            (\strlen($eacCode) !== 5 || \preg_match("/(([0-9]*))/", $eacCode) !== 1)) {
            $msg    = "EacCode must be null or have a length 5 and respect the regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("EacCode_not_valid");
        } else {
            $return = true;
        }
        $this->eacCode = $eacCode;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->eacCode === null ? "null" : $this->eacCode
                )
            );
        return $return;
    }

    /**
     * Set System entry date<br>
     * Date of the last time the record was saved at the time of signing.
     * Shall include hour, minute and second. Date and time type: “YYYY–MM–DDThh:mm:ss”.<br>
     * &lt;xs:element ref = "SystemEntryDate"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getSystemEntryDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)
                )
            );
        return $this->systemEntryDate;
    }

    /**
     * Get if is set SystemEntryDate
     * @return bool
     * @since 1.0.0
     */
    public function issetSystemEntryDate(): bool
    {
        return isset($this->systemEntryDate);
    }

    /**
     * Set System entry date<br>
     * Date of the last time the record was saved at the time of signing.
     * Shall include hour, minute and second. Date and time type: “YYYY–MM–DDThh:mm:ss”.<br>
     * &lt;xs:element ref = "SystemEntryDate"/&gt;
     * @param \Rebelo\Date\Date $systemEntryDate
     * @return void
     * @since 1.0.0
     */
    public function setSystemEntryDate(RDate $systemEntryDate): void
    {
        $this->systemEntryDate = $systemEntryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get transaction id<br>
     * If an accounting record is created, filling in is compulsory if it is
     * an integrated accounting and invoicing system,
     * even if the file type (TaxAccountingBasis) shall not
     * contain tables relating accounting.<br>
     * If $create is true and a inatnce wasn't created previous a new instance will be created
     * &lt;xs:element ref = "TransactionID" minOccurs = "0"/&gt;
     * @param bool $create If is true and a inatnce wasn't created previous a new instance will be created
     * @return \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    public function getTransactionID(bool $create = true): ?TransactionID
    {
        if ($create && $this->transactionID === null) {
            $this->transactionID = new TransactionID($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"
                )
            );
        return $this->transactionID;
    }

    /**
     * Set TransactionID as null
     * @return void
     * @since 1.0.0
     */
    public function setTransactionIDAsNull(): void
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." setted as null");
        $this->transactionID = null;
    }

    /**
     * Get CustomerID<br>
     * The unique key of the table 2.2. - Customer respecting the rule defined for 2.2.1. - CustomerID.<br>
     * &lt;xs:element ref = "CustomerID"/&gt;<br>
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCustomerID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->customerID));
        return $this->customerID;
    }

    /**
     * Get if is set CustomerID
     * @return bool
     * @since 1.0.0
     */
    public function issetCustomerID(): bool
    {
        return isset($this->customerID);
    }

    /**
     * Set CustomerID<br>
     * The unique key of the table 2.2. - Customer respecting the rule defined for 2.2.1. - CustomerID.<br>
     * &lt;xs:element ref = "CustomerID"/&gt;<br>
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @param string $customerID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): bool
    {
        try {
            $this->customerID = $this->valTextMandMaxCar(
                $customerID, 30, __METHOD__, false
            );
            $return           = true;
        } catch (AuditFileException $e) {
            $this->customerID = $customerID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("CustomerID_not_valid");
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'", $this->customerID
                )
            );
        return $return;
    }

    /**
     * Parse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        //Validation of node name should be done in the child class
        $this->setAtcud((string) $node->{static::N_ATCUD});

        $this->setHash((string) $node->{static::N_HASH});

        $this->setHashControl((string) $node->{static::N_HASHCONTROL});

        if ($node->{static::N_PERIOD}->count() > 0) {
            $this->setPeriod((int) $node->{static::N_PERIOD});
        }

        $this->setSourceID((string) $node->{static::N_SOURCEID});

        if ($node->{static::N_EACCODE}->count() > 0) {
            $this->setEacCode((string) $node->{static::N_EACCODE});
        }

        $this->setSystemEntryDate(
            RDate::parse(
                RDate::DATE_T_TIME, (string) $node->{static::N_SYSTEMENTRYDATE}
            )
        );

        if ($node->{static::N_TRANSACTIONID}->count() > 0) {
            $this->getTransactionID()->parseXmlNode(
                $node->{static::N_TRANSACTIONID}
            );
        }

        if ($node->{static::N_CUSTOMERID}->count() > 0) {
            $this->setCustomerID((string) $node->{static::N_CUSTOMERID});
        }
    }

    /**
     * Get the caluleted values of the validation classes
     * @return \Rebelo\SaftPt\Validate\DocTotalCalc|null
     * @since 1.0.0
     */
    public function getDocTotalcal(): ?DocTotalCalc
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        return $this->docTotalcal;
    }

    /**
     * Set the caluleted values of the validation classes
     * @param \Rebelo\SaftPt\Validate\DocTotalCalc|null $docTotalcal
     * @return void
     * @since 1.0.0
     */
    public function setDocTotalcal(?DocTotalCalc $docTotalcal): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->docTotalcal = $docTotalcal;
    }
}