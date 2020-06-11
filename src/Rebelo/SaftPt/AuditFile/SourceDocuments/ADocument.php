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

/**
 * Description of ADocument
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
     *
     * <pre>
     * <xs:element ref = "ATCUD"/>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * </pre>
     * @since 1.0.0
     * @var string
     */
    private string $atcud;

    /**
     * <pre>
     * <xs:element ref = "Hash"/>
     * <xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/>
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $hash;

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
    private string $hashControl;

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
    private ?int $period = null;

    /**
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $sourceID;

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
    private ?string $eacCode = null;

    /**
     * <xs:element ref = "SystemEntryDate"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $systemEntryDate;

    /**
     * <xs:element ref = "TransactionID" minOccurs = "0"/>
     * @var \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    private ?TransactionID $transactionID = null;

    /**
     * <xs:element ref = "CustomerID"/>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @var string
     * @since 1.0.0
     */
    private string $customerID;

    /**
     * Abstract class for common method and properties of WorkDocument,
     * StockMovement and invoice
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Set the ATCUD
     *
     * <pre>
     * <xs:element ref = "ATCUD"/>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getAtcud(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->atcud));
        return $this->atcud;
    }

    /**
     * Set the ATCUD
     * <pre>
     * <xs:element ref = "ATCUD"/>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * </pre>
     * @param string $atcud
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return void
     * @since 1.0.0
     */
    public function setAtcud(string $atcud): void
    {
        $this->atcud = $this->valTextMandMaxCar($atcud, 100, __METHOD__, false);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->atcud));
    }

    /**
     * Get hash
     * <pre>
     * <xs:element ref = "Hash"/>
     * <xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getHash(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->hash));
        return $this->hash;
    }

    /**
     * Set Hash
     * <pre>
     * <xs:element ref = "Hash"/>
     * <xs:element name="Hash" type="SAFPTtextTypeMandatoryMax172Car"/>
     * </pre>
     * @param string $hash
     * @return void
     * @since 1.0.0
     */
    public function setHash(string $hash): void
    {
        $this->hash = $this->valTextMandMaxCar($hash, 172, __METHOD__, false);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->hash));
    }

    /**
     * Get hash control
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
     * @since 1.0.0
     */
    public function getHashControl(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->hashControl));
        return $this->hashControl;
    }

    /**
     * Set hash control
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
     * @return void
     * @since 1.0.0
     */
    public function setHashControl(string $hashControl): void
    {
        $pattern = "/[0-9]+|[0-9]+[.][0-9]+|[0-9]+-[A-Z]{2}(M )([^ ]+[\/][0-9]+)|[0-9]+-[A-Z]{2}(D )([^ ]+ [^\/^ ]+[\/][0-9]+)/";
        if (\strlen($hashControl) < 1 ||
            \strlen($hashControl) > 70 ||
            \preg_match($pattern, $hashControl) !== 1) {
            $msg = "HashControl must respect the regexp and length must be between 1 and 70";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->hashControl = $hashControl;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->hashControl));
    }

    /**
     * Get period
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
     * Set period
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
     * @return void
     * @since 1.0.0
     */
    public function setPeriod(?int $period): void
    {
        if ($period !== null && ($period < 1 || $period > 12)) {
            $msg = "Period must be null or between 1 and 12";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->period = $period;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->period === null ? "null" : \strval($this->period)
                )
        );
    }

    /**
     * Get source id
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
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
     * Set Source id
     * <pre>
     * &lt;xs:element ref = "SourceID"/&gt;
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @param string $sourceID
     * @return void
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): void
    {
        $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->sourceID
                )
        );
    }

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
     * @return string|null
     * @since 1.0.0
     */
    public function getEacCode(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->eacCode === null ? "null" : $this->eacCode));
        return $this->eacCode;
    }

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
     * @param string|null $eacCode
     * @return void
     * @since 1.0.0
     */
    public function setEacCode(?string $eacCode): void
    {
        if ($eacCode !== null &&
            (\strlen($eacCode) !== 5 || \preg_match("/(([0-9]*))/", $eacCode) !== 1)) {
            $msg = "Period must be null or have a length 5 and respect the regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->eacCode = $eacCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->eacCode === null ? "null" : $this->eacCode
                )
        );
    }

    /**
     * Set System entry date<br>
     * <xs:element ref = "SystemEntryDate"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getSystemEntryDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)));
        return $this->systemEntryDate;
    }

    /**
     * Set System entry date<br>
     * <xs:element ref = "SystemEntryDate"/>
     * @param \Rebelo\Date\Date $systemEntryDate
     * @return void
     * @since 1.0.0
     */
    public function setSystemEntryDate(RDate $systemEntryDate): void
    {
        $this->systemEntryDate = $systemEntryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)
                )
        );
    }

    /**
     * Get transaction id<br>
     * <xs:element ref = "TransactionID" minOccurs = "0"/>
     * @return \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    public function getTransactionID(): ?TransactionID
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"));
        return $this->transactionID;
    }

    /**
     * Set transaction id<br>
     * <xs:element ref = "TransactionID" minOccurs = "0"/>
     * @param Rebelo\SaftPt\AuditFile\TransactionID|null $transactionID
     * @return void
     * @since 1.0.0
     */
    public function setTransactionID(?TransactionID $transactionID): void
    {
        $this->transactionID = $transactionID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"
                )
        );
    }

    /**
     * Get CustomerID<br>
     * <xs:element ref = "CustomerID"/>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getCustomerID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->customerID));
        return $this->customerID;
    }

    /**
     * Set CustomerID<br>
     * <xs:element ref = "CustomerID"/>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @param string $customerID
     * @return void
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): void
    {
        $this->customerID = $this->valTextMandMaxCar(
            $customerID, 30, __METHOD__, false
        );
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->customerID
                )
        );
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
            $transactionID = new TransactionID();
            $transactionID->parseXmlNode($node->{static::N_TRANSACTIONID});
            $this->setTransactionID($transactionID);
        }
        if ($node->{static::N_CUSTOMERID}->count() > 0) {
            $this->setCustomerID((string) $node->{static::N_CUSTOMERID});
        }
    }
}