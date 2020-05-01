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

use Rebelo\Date\Date as RDate;

/**
 * Class representing Header
 * <pre>
 * <!-- Estrutura de cabecalho (AuditFile.Header) -->
 * &lt;xs:element name="Header"&gt;
 * &lt;xs:complexType&gt;
 * &lt;xs:sequence&gt;
 * &lt;xs:element ref="AuditFileVersion"/&gt;
 * &lt;xs:element ref="CompanyID"/&gt;
 * &lt;xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/&gt;
 * &lt;xs:element ref="TaxAccountingBasis"/&gt;
 * &lt;xs:element ref="CompanyName"/&gt;
 * &lt;xs:element ref="BusinessName" minOccurs="0"/&gt;
 * &lt;xs:element ref="CompanyAddress"/&gt;
 * &lt;xs:element ref="FiscalYear"/&gt;
 * &lt;xs:element ref="StartDate"/&gt;
 * &lt;xs:element ref="EndDate"/&gt;
 * &lt;xs:element name="CurrencyCode" fixed="EUR"/&gt;
 * &lt;xs:element ref="DateCreated"/&gt;
 * &lt;xs:element ref="TaxEntity"/&gt;
 * &lt;xs:element ref="ProductCompanyTaxID"/&gt;
 * &lt;xs:element ref="SoftwareCertificateNumber"/&gt;
 * &lt;xs:element ref="ProductID"/&gt;
 * &lt;xs:element ref="ProductVersion"/&gt;
 * &lt;xs:element ref="HeaderComment" minOccurs="0"/&gt;
 * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;
 * &lt;xs:element ref="Fax" minOccurs="0"/&gt;
 * &lt;xs:element ref="Email" minOccurs="0"/&gt;
 * &lt;xs:element ref="Website" minOccurs="0"/&gt;
 * &lt;/xs:sequence&gt;
 * &lt;/xs:complexType&gt;
 * </pre>
 *
 * @since 1.0.0
 */
class Header extends AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_HEADER = "Header";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_AUDITFILEVERSION = "AuditFileVersion";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_COMPANYID = "CompanyID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXREGISTRATIONNUMBER = "TaxRegistrationNumber";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXACCOUNTINGBASIS = "TaxAccountingBasis";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_COMPANYNAME = "CompanyName";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_BUSINESSNAME = "BusinessName";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_COMPANYADDRESS = "CompanyAddress";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_FISCALYEAR = "FiscalYear";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_STARTDATE = "StartDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ENDDATE = "EndDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CURRENCYCODE = "CurrencyCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DATECREATED = "DateCreated";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXENTITY = "TaxEntity";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTCOMPANYTAXID = "ProductCompanyTaxID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOFTWARECERTIFICATENUMBER = "SoftwareCertificateNumber";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTID = "ProductID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTVERSION = "ProductVersion";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_HEADERCOMMENT = "HeaderComment";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TELEPHONE = "Telephone";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_FAX = "Fax";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_EMAIL = "Email";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_WEBSITE = "Website";

    /**
     * <xs:element ref="AuditFileVersion"/>
     * @var string $auditFileVersion
     * @since 1.0.0
     */
    private string $auditFileVersion = "1.04_01";

    /**
     * <pre>
     * &lt;xs:element ref="CompanyID"/&gt;
     * &lt;xs:element name="CompanyID"&gt;
     *     &lt;xs:annotation&gt;
     *         &lt;xs:documentation&gt;Concatenacao da Conservatoria do Registo Comercial com o numero do
     *             registo comercial separados pelo caracter espaco. Nos casos em que nao existe o
     *             registo comercial, deve ser indicado o NIF.&lt;/xs:documentation&gt;
     *     &lt;/xs:annotation&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:pattern value="([0-9]{9})+|([^^]+ [0-9/]+)"/&gt;
     *             &lt;xs:minLength value="1"/&gt;
     *             &lt;xs:maxLength value="50"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @var string $companyID
     * @since 1.0.0
     */
    private string $companyID;

    /**
     * <xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/><br>
     * @var int $taxRegistrationNumber
     * @since 1.0.0
     */
    private int $taxRegistrationNumber;

    /**
     * <xs:element ref="TaxAccountingBasis"/><br>
     * @var TaxAccountingBasis $taxAccountingBasis
     * @since 1.0.0
     */
    private TaxAccountingBasis $taxAccountingBasis;

    /**
     * <xs:element ref="CompanyName"/><br>
     * <xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/><br>
     * @var string $companyName
     * @since 1.0.0
     */
    private string $companyName;

    /**
     * <xs:element ref="BusinessName" minOccurs="0"/><br>
     * <xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/><br>
     * @var string $businessName
     * @since 1.0.0
     */
    private ?string $businessName = null;

    /**
     *
     * <xs:element ref="CompanyAddress"/><br>
     * <xs:element name="CompanyAddress" type="AddressStructurePT"/>
     * @var AddressPT $companyAddress
     * @since 1.0.0
     */
    private AddressPT $companyAddress;

    /**
     * <pre>
     * <xs:element ref="FiscalYear"/>
     * <xs:element name="FiscalYear">
     *      <xs:simpleType>
     *          <xs:restriction base="xs:integer">
     *              <xs:minInclusive value="2000"/>
     *              <xs:maxInclusive value="9999"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     * @var int $fiscalYear
     * @since 1.0.0
     */
    private int $fiscalYear;

    /**
     * <pre>
     * <xs:element ref="StartDate"/>
     * <xs:element name="StartDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @var \Rebelo\Date\Date $startDate
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $startDate;

    /**
     * <pre>
     * <xs:element ref="EndDate"/>
     * <xs:element name="EndDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @var \Rebelo\Date\Date $endDate
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $endDate;

    /**
     * <xs:element name="CurrencyCode" fixed="EUR"/>
     * @var string $currencyCode
     * @since 1.0.0
     */
    private string $currencyCode = "EUR";

    /**
     * <xs:element ref="DateCreated"/>
     * @var \Rebelo\Date\Date $dateCreated
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $dateCreated;

    /**
     *
     * <xs:element ref="TaxEntity"/><br>
     * <xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     * @var string $taxEntity
     * @since 1.0.0
     */
    private string $taxEntity;

    /**
     *
     * <xs:element ref="ProductCompanyTaxID"/><br>
     * <xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     *
     * @var string $productCompanyTaxID
     * @since 1.0.0
     */
    private string $productCompanyTaxID;

    /**
     * <xs:element ref="SoftwareCertificateNumber"/><br>
     * <xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/><br>
     *
     * @var int $softwareCertificateNumber
     * @since 1.0.0
     */
    private int $softwareCertificateNumber;

    /**
     * <pre>
     * <xs:element ref="ProductID"/>
     * <xs:simpleType name="SAFPTProductID">
     *     <xs:restriction base="xs:string">
     *         <xs:pattern value="[^/]+/[^/]+"/>
     *         <xs:minLength value="3"/>
     *         <xs:maxLength value="255"/>
     *     </xs:restriction>
     * </xs:simpleType>
     * </pre>
     * @var string $productID
     * @since 1.0.0
     */
    private string $productID;

    /**
     * <xs:element ref="ProductVersion"/><br>
     * <xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     * @var string $productVersion
     * @since 1.0.0
     */
    private string $productVersion;

    /**
     * <xs:element ref="HeaderComment" minOccurs="0"/><br>
     * <xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/><br>
     * @var string|null $headerComment
     * @since 1.0.0
     */
    private ?string $headerComment = null;

    /**
     * <xs:element ref="Telephone" minOccurs="0"/><br>
     * <xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     * @var string $telephone
     * @since 1.0.0
     */
    private ?string $telephone = null;

    /**
     * <xs:element ref="Fax" minOccurs="0"/><br>
     * <xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     * @var string $fax
     * @since 1.0.0
     */
    private ?string $fax = null;

    /**
     * <xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/><br>
     * <xs:element ref="Email" minOccurs="0"/><br>
     * @var string $email
     * @since 1.0.0
     */
    private ?string $email = null;

    /**
     *
     * <xs:element ref="Website" minOccurs="0"/><br>
     * <xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @var string|null $website
     * @since 1.0.0
     */
    private ?string $website = null;

    /**
     * <xs:element name="Header">
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->dateCreated = new \Rebelo\Date\Date();
    }

    /**
     * Gets as auditFileVersion
     *
     * @return string
     * @since 1.0.0
     */
    public function getAuditFileVersion(): string
    {
        return $this->auditFileVersion;
    }

    /**
     * Gets as companyID <br>
     * Concatenacao da Conservatoria do Registo Comercial com o numero do<br>
     * registo comercial separados pelo caracter espaco. Nos casos em que nao existe o<br>
     * registo comercial, deve ser indicado o NIF.<br>
     * <pre>
     * <xs:element ref="CompanyID"/>
     * <xs:element name="CompanyID">
     *     <xs:simpleType>
     *         <xs:restriction base="xs:string">
     *             <xs:pattern value="([0-9]{9})+|([^^]+ [0-9/]+)"/>
     *             <xs:minLength value="1"/>
     *             <xs:maxLength value="50"/>
     *         </xs:restriction>
     *     </xs:simpleType>
     * </xs:element>
     * </pre>
     * <br>
     * @return string
     * @since 1.0.0
     */
    public function getCompanyID(): string
    {
        return $this->companyID;
    }

    /**
     * Sets a new companyID<br>
     * Concatenacao da Conservatoria do Registo Comercial com o numero do
     * registo comercial separados pelo caracter espaco. Nos casos em que nao existe o
     * registo comercial, deve ser indicado o NIF.
     * <br>
     * <pre>
     * <xs:element ref="CompanyID"/>
     * <xs:element name="CompanyID">
     *     <xs:simpleType>
     *         <xs:restriction base="xs:string">
     *             <xs:pattern value="([0-9]{9})+|([^^]+ [0-9/]+)"/>
     *             <xs:minLength value="1"/>
     *             <xs:maxLength value="50"/>
     *         </xs:restriction>
     *     </xs:simpleType>
     * </xs:element>
     * </pre>
     * <br>
     * @param string $companyID
     * @return void
     * @since 1.0.0
     */
    public function setCompanyID(string $companyID): void
    {
        $valId = static::valTextMandMaxCar($companyID, 50, __METHOD__);
        if (\preg_match("/([0-9]{9})+|([^^]+ [0-9\/]+)/", $valId) !== 1) {
            $msg = "Value Not valide";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->companyID = $valId;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->companyID));
    }

    /**
     *
     * Gets as taxRegistrationNumber<br>
     * <xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/><br>
     * @return int
     * @since 1.0.0
     */
    public function getTaxRegistrationNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->taxRegistrationNumber)));
        return $this->taxRegistrationNumber;
    }

    /**
     * Sets a new taxRegistrationNumber<br>
     * <xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/><br>
     * @param int $taxRegistrationNumber
     * @return void
     * @since 1.0.0
     */
    public function setTaxRegistrationNumber(int $taxRegistrationNumber): void
    {
        if (!static::valPortugueseVatNumber($taxRegistrationNumber)) {
            $msg = strval($taxRegistrationNumber)." is not a valide PT nif";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxRegistrationNumber = $taxRegistrationNumber;
        \Logger::getLogger(\get_class($this))->debug(
            \sprintf(__METHOD__." setted to '%s'",
                \strval($this->taxRegistrationNumber)));
    }

    /**
     * Gets as taxAccountingBasis<br>
     * <xs:element name="TaxAccountingBasis">
     * @return Rebelo\SaftPt\AuditFile\TaxAccountingBasis
     * @since 1.0.0
     */
    public function getTaxAccountingBasis(): TaxAccountingBasis
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxAccountingBasis->get()));
        return $this->taxAccountingBasis;
    }

    /**
     * Sets a new taxAccountingBasis<br>
     * <xs:element name="TaxAccountingBasis">
     * @param Rebelo\SaftPt\AuditFile\TaxAccountingBasis $taxAccountingBasis
     * @return void
     * @since 1.0.0
     */
    public function setTaxAccountingBasis(TaxAccountingBasis $taxAccountingBasis): void
    {
        $this->taxAccountingBasis = $taxAccountingBasis;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxAccountingBasis->get()));
    }

    /**
     * Gets as companyName<br>
     * <xs:element ref="CompanyName"/><br>
     * <xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/><br>
     * @return string
     * @since 1.0.0
     */
    public function getCompanyName(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->companyName));
        return $this->companyName;
    }

    /**
     * Sets a new companyName<br>
     * <xs:element ref="CompanyName"/><br>
     * <xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/><br>
     * @param string $companyName
     * @return void
     * @since 1.0.0
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = static::valTextMandMaxCar($companyName, 100,
                __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->companyName));
    }

    /**
     * Gets as businessName<br>
     * <xs:element ref="BusinessName" minOccurs="0"/><br>
     * <xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/><br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBusinessName(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->businessName === null ? "null" : $this->businessName));
        return $this->businessName;
    }

    /**
     * Sets a new businessName<br>
     * <xs:element ref="BusinessName" minOccurs="0"/><br>
     * <xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/><br>
     *
     * @param string|null $businessName
     * @return void
     * @since 1.0.0
     */
    public function setBusinessName(?string $businessName): void
    {
        if ($businessName === null) {
            $this->businessName = $businessName;
        } else {
            $this->businessName = static::valTextMandMaxCar($businessName, 60,
                    __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->businessName === null ? "null" : $this->businessName));
    }

    /**
     * Gets as companyAddress<br>
     * <xs:element ref="CompanyAddress"/><br>
     * <xs:element name="CompanyAddress" type="AddressStructurePT"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\AddressPT
     * @since 1.0.0
     */
    public function getCompanyAddress(): AddressPT
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        return $this->companyAddress;
    }

    /**
     * Sets a new companyAddress<br>
     * <xs:element ref="CompanyAddress"/><br>
     * <xs:element name="CompanyAddress" type="AddressStructurePT"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\AddressPT $companyAddress
     * @return void
     * @since 1.0.0
     */
    public function setCompanyAddress(AddressPT $companyAddress): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->companyAddress = $companyAddress;
    }

    /**
     * Gets as fiscalYear<br>
     * <pre>
     * <xs:element ref="FiscalYear"/>
     * <xs:element name="FiscalYear">
     *      <xs:simpleType>
     *          <xs:restriction base="xs:integer">
     *              <xs:minInclusive value="2000"/>
     *              <xs:maxInclusive value="9999"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @return int
     * @since 1.0.0
     */
    public function getFiscalYear(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->fiscalYear)));
        return $this->fiscalYear;
    }

    /**
     * Sets a new fiscalYear<br>
     * <pre>
     * <xs:element ref="FiscalYear"/>
     * <xs:element name="FiscalYear">
     *      <xs:simpleType>
     *          <xs:restriction base="xs:integer">
     *              <xs:minInclusive value="2000"/>
     *              <xs:maxInclusive value="????"/> the current year
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @param int $fiscalYear
     * @return void
     * @since 1.0.0
     */
    public function setFiscalYear(int $fiscalYear): void
    {
        $ano = \intval(\Date("Y"));
        if ($fiscalYear < 2000 || $fiscalYear > $ano) {
            $msg = \strval($fiscalYear)." is not a valide year";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->fiscalYear = $fiscalYear;
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->fiscalYear)));
    }

    /**
     * Gets as startDate<br>
     * <pre>
     * <xs:element ref="StartDate"/>
     * <xs:element name="StartDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getStartDate(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->startDate->getTimestamp())));
        return $this->startDate;
    }

    /**
     * Sets a new startDate<br>
     * <pre>
     * <xs:element ref="StartDate"/>
     * <xs:element name="StartDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @param \Rebelo\Date\Date $startDate
     * @return void
     * @since 1.0.0
     */
    public function setStartDate(\Rebelo\Date\Date $startDate): void
    {
        $year    = \intval($startDate->format(\Rebelo\Date\Date::YAER));
        $yearNow = \intval((new \Rebelo\Date\Date())->format(\Rebelo\Date\Date::YAER))
            + 1;
        if ($year < 2000 || $year > $yearNow) {
            $msg = \sprintf("Date must be between '%s' and '%s'", $year,
                $yearNow);
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->startDate = $startDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->startDate->format(\Rebelo\Date\Date::SQL_DATE)));
    }

    /**
     * Gets as endDate<br>
     * <pre>
     * <xs:element ref="EndDate"/>
     * <xs:element name="EndDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getEndDate(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->endDate->format(\Rebelo\Date\Date::SQL_DATE)));
        return $this->endDate;
    }

    /**
     * Sets a new endDate<br>
     * <pre>
     * <xs:element ref="EndDate"/>
     * <xs:element name="EndDate" type="SAFPTDateSpan"/>
     * <xs:simpleType name="SAFPTDateSpan">
     *       <xs:restriction base="xs:date">
     *           <xs:minInclusive value="2000-01-01"/>
     *           <xs:maxInclusive value="9999-12-31"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @param \Rebelo\Date\Date $endDate
     * @return void
     * @since 1.0.0
     */
    public function setEndDate(\Rebelo\Date\Date $endDate): void
    {
        $year    = \intval($endDate->format(\Rebelo\Date\Date::YAER));
        $yearNow = \intval((new \Rebelo\Date\Date())->format(\Rebelo\Date\Date::YAER))
            + 1;
        if ($year < 2000 || $year > $yearNow) {
            $msg = \sprintf("Date must be between '%s' and '%s'", $year,
                $yearNow);
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->endDate = $endDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->endDate->format(\Rebelo\Date\Date::SQL_DATE)));
    }

    /**
     * Gets as currencyCode<br>     *
     * <xs:element name="CurrencyCode" fixed="EUR"/>
     * @return string
     * @since 1.0.0
     */
    public function getCurrencyCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->currencyCode));
        return $this->currencyCode;
    }

    /**
     * Gets as dateCreated<br>
     * <xs:element ref="DateCreated"/>     *
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getDateCreated(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->dateCreated->format(\Rebelo\Date\Date::ATOM)));
        return $this->dateCreated;
    }

    /**
     * Sets a new dateCreated<br>
     * <xs:element ref="DateCreated"/><br>
     * Tthe creation date is setten whene the object is created,
     * use this if you wont a diferent date from the one in the OS<br>
     * @param \Rebelo\Date\Date $dateCreated
     * @return void
     * @since 1.0.0
     */
    public function setDateCreated(\Rebelo\Date\Date $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->dateCreated->format(\Rebelo\Date\Date::ATOM)));
    }

    /**
     * Gets as taxEntity<br>     *
     * <xs:element ref="TaxEntity"/><br>
     * <xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     * @return string
     * @since 1.0.0
     */
    public function getTaxEntity(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxEntity));
        return $this->taxEntity;
    }

    /**
     * Sets a new taxEntity<br>     *
     * <xs:element ref="TaxEntity"/><br>
     * <xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     *
     * @param string $taxEntity
     * @return void
     * @since 1.0.0
     */
    public function setTaxEntity(string $taxEntity): void
    {
        $this->taxEntity = static::valTextMandMaxCar($taxEntity, 20, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxEntity));
    }

    /**
     * Gets as productCompanyTaxID<br>
     * <xs:element ref="ProductCompanyTaxID"/><br>
     * <xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     *
     * @return string
     * @since 1.0.0
     */
    public function getProductCompanyTaxID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->productCompanyTaxID));
        return $this->productCompanyTaxID;
    }

    /**
     * Sets a new productCompanyTaxID<br>
     * <xs:element ref="ProductCompanyTaxID"/><br>
     * <xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     *
     * @param string $productCompanyTaxID
     * @return void
     * @since 1.0.0
     */
    public function setProductCompanyTaxID(string $productCompanyTaxID): void
    {
        $this->productCompanyTaxID = static::valTextMandMaxCar($productCompanyTaxID,
                30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->productCompanyTaxID));
    }

    /**
     * <xs:element ref="SoftwareCertificateNumber"/><br>
     * <xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/><br>
     *
     * Gets as softwareCertificateNumber
     *
     * @return int
     * @since 1.0.0
     */
    public function getSoftwareCertificateNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->softwareCertificateNumber));
        return $this->softwareCertificateNumber;
    }

    /**
     * Sets a new softwareCertificateNumber<br>
     * <xs:element ref="SoftwareCertificateNumber"/><br>
     * <xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/><br>
     *
     * @param int $softwareCertificateNumber
     * @return void
     * @since 1.0.0
     */
    public function setSoftwareCertificateNumber(int $softwareCertificateNumber): void
    {
        if ($softwareCertificateNumber < 0) {
            $msg = "certification number must be non negative integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->softwareCertificateNumber = $softwareCertificateNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->softwareCertificateNumber));
    }

    /**
     * Gets as productID<br>
     * <pre>
     * <xs:element ref="ProductID"/>
     * <xs:simpleType name="SAFPTProductID">
     *     <xs:restriction base="xs:string">
     *         <xs:pattern value="[^/]+/[^/]+"/>
     *         <xs:minLength value="3"/>
     *         <xs:maxLength value="255"/>
     *     </xs:restriction>
     * </xs:simpleType>
     * </pre>
     *
     * @return string
     * @since 1.0.0
     */
    public function getProductID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productID));
        return $this->productID;
    }

    /**
     * Sets a new productID
     * <pre>
     * <xs:element ref="ProductID"/>
     * <xs:simpleType name="SAFPTProductID">
     *     <xs:restriction base="xs:string">
     *         <xs:pattern value="[^/]+/[^/]+"/>
     *         <xs:minLength value="3"/>
     *         <xs:maxLength value="255"/>
     *     </xs:restriction>
     * </xs:simpleType>
     * </pre>
     * @param string $productID
     * @return void
     * @since 1.0.0
     */
    public function setProductID(string $productID): void
    {
        $msg = null;
        if (\preg_match("/[^\/]+\/[^\/]/", $productID) !== 1) {
            $msg = "product doesn't match regexp '[^\/]+\/[^\/]'";
        }
        if (\strlen($productID) < 3 || \strlen($productID) > 255) {
            $msg = "string length must be between 3 and 255";
        }
        if ($msg !== null) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->productID = $productID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->productID));
    }

    /**
     * Gets as productVersion<br>
     * <xs:element ref="ProductVersion"/><br>
     * <xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     *
     * @return string
     * @since 1.0.0
     */
    public function getProductVersion(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productVersion));
        return $this->productVersion;
    }

    /**
     * Sets a new productVersion<br>
     * <xs:element ref="ProductVersion"/><br>
     * <xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     *
     * @param string $productVersion
     * @return void
     * @since 1.0.0
     */
    public function setProductVersion(string $productVersion): void
    {
        $this->productVersion = static::valTextMandMaxCar($productVersion, 30,
                __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->productVersion));
    }

    /**
     * Gets as headerComment<br>
     * <xs:element ref="HeaderComment" minOccurs="0"/><br>
     * <xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/><br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getHeaderComment(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->headerComment === null ? "null" : $this->headerComment));
        return $this->headerComment;
    }

    /**
     * Sets a new headerComment<br>
     * <xs:element ref="HeaderComment" minOccurs="0"/><br>
     * <xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/><br>
     *
     * @param string|null $headerComment
     * @return void
     * @since 1.0.0
     */
    public function setHeaderComment(?string $headerComment): void
    {
        if ($headerComment === null) {
            $this->headerComment = null;
        } else {
            $this->headerComment = static::valTextMandMaxCar($headerComment,
                    255, __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->headerComment === null ? "null" : $this->headerComment));
    }

    /**
     * Gets as telephone<br>
     * <xs:element ref="Telephone" minOccurs="0"/><br>
     * <xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTelephone(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->telephone === null ? "null" : $this->telephone));
        return $this->telephone;
    }

    /**
     * Sets a new telephone<br>
     * <xs:element ref="Telephone" minOccurs="0"/><br>
     * <xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     *
     * @param string|null $telephone
     * @return void
     * @since 1.0.0
     */
    public function setTelephone(?string $telephone): void
    {
        if ($telephone === null) {
            $this->telephone = null;
        } else {
            $this->telephone = static::valTextMandMaxCar($telephone, 20,
                    __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->telephone === null ? "null" : $this->telephone));
    }

    /**
     * Gets as fax<br>
     * <xs:element ref="Fax" minOccurs="0"/><br>
     * <xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getFax(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->fax === null ? "null" : $this->fax));
        return $this->fax;
    }

    /**
     * Sets a new fax<br>
     * <xs:element ref="Fax" minOccurs="0"/><br>
     * <xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/><br>
     *
     * @param string|null $fax
     * @return void
     * @since 1.0.0
     */
    public function setFax(?string $fax): void
    {
        if ($fax === null) {
            $this->fax = $fax;
        } else {
            $this->fax = static::valTextMandMaxCar($fax, 20, __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->fax === null ? "null" : $this->fax));
    }

    /**
     * Gets as email<br>
     * <xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/><br>
     * <xs:element ref="Email" minOccurs="0"/><br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getEmail(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->email === null ? "null" : $this->email));
        return $this->email;
    }

    /**
     * Sets a new email<br>
     * <xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/><br>
     * <xs:element ref="Email" minOccurs="0"/><br>
     *
     * @param string|null $email
     * @return void
     * @since 1.0.0
     */
    public function setEmail(?string $email): void
    {
        if ($email === null) {
            $this->email = $email;
        } else {
            if (\filter_var($email, FILTER_VALIDATE_EMAIL) === false ||
                \strlen($email) > 254) {
                $msg = $email." is not a valide email";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            } else {
                $this->email = $email;
            }
        }
        $this->email = $email;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->email === null ? "null" : $this->email));
    }

    /**
     * Gets as website<br>
     * <xs:element ref="Website" minOccurs="0"/><br>
     * <xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getWebsite(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->website === null ? "null" : $this->website
        ));
        return $this->website;
    }

    /**
     * Sets a new website
     * <xs:element ref="Website" minOccurs="0"/><br>
     * <xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @param string|null $website
     * @return void
     * @since 1.0.0
     */
    public function setWebsite(?string $website): void
    {
        if ($website === null) {
            $this->website = $website;
        } else {
            if (\filter_var($website, FILTER_VALIDATE_URL) === false ||
                \strlen($website) > 60) {
                $msg = "The URL is not valide";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->website = $website;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->website === null ? "null" : $this->website));
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        $dateYmd    = RDate::SQL_DATE;
        $headerNode = $node->addChild(static::N_HEADER);
        $headerNode->addChild(static::N_AUDITFILEVERSION,
            $this->getAuditFileVersion());
        $headerNode->addChild(static::N_COMPANYID, $this->getCompanyID());
        $headerNode->addChild(static::N_TAXREGISTRATIONNUMBER,
            \strval($this->getTaxRegistrationNumber()));
        $headerNode->addChild(static::N_TAXACCOUNTINGBASIS,
            $this->getTaxAccountingBasis()->get());
        $headerNode->addChild(static::N_COMPANYNAME, $this->getCompanyName());
        if ($this->getBusinessName() !== null) {
            $headerNode->addChild(static::N_BUSINESSNAME,
                $this->getBusinessName());
        }
        $compAddr = $headerNode->addChild(static::N_COMPANYADDRESS);
        $this->getCompanyAddress()->createXmlNode($compAddr);
        $headerNode->addChild(static::N_FISCALYEAR,
            \strval($this->getFiscalYear()));
        $headerNode->addChild(static::N_STARTDATE,
            $this->getStartDate()->format($dateYmd));
        $headerNode->addChild(static::N_ENDDATE,
            $this->getEndDate()->format($dateYmd));
        $headerNode->addChild(static::N_CURRENCYCODE, $this->getCurrencyCode());
        $headerNode->addChild(static::N_DATECREATED,
            $this->getDateCreated()->format($dateYmd));
        $headerNode->addChild(static::N_TAXENTITY, $this->getTaxEntity());
        $headerNode->addChild(static::N_PRODUCTCOMPANYTAXID,
            $this->getProductCompanyTaxID());
        $headerNode->addChild(static::N_SOFTWARECERTIFICATENUMBER,
            \strval($this->getSoftwareCertificateNumber()));
        $headerNode->addChild(static::N_PRODUCTID, $this->getProductID());
        $headerNode->addChild(static::N_PRODUCTVERSION,
            $this->getProductVersion());
        if ($this->getHeaderComment() !== null) {
            $headerNode->addChild(static::N_HEADERCOMMENT,
                $this->getHeaderComment());
        }
        if ($this->getTelephone() !== null) {
            $headerNode->addChild(static::N_TELEPHONE, $this->getTelephone());
        }

        if ($this->getFax() !== null) {
            $headerNode->addChild(static::N_FAX, $this->getFax());
        }

        if ($this->getEmail() !== null) {
            $headerNode->addChild(static::N_EMAIL, $this->getEmail());
        }
        if ($this->getWebsite() !== null) {
            $headerNode->addChild(static::N_WEBSITE, $this->getWebsite());
        }
        return $headerNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        $dateYmd = RDate::SQL_DATE;
        if ($this->getAuditFileVersion() !== (string) $node->{static::N_AUDITFILEVERSION}) {
            $msg = sprintf("Wrong audit file version, your file is '%s' and should be '%s'",
                $node->{static::N_AUDITFILEVERSION},
                $this->getAuditFileVersion());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->setCompanyID((string) $node->{static::N_COMPANYID});
        $this->setTaxRegistrationNumber((int) $node->{static::N_TAXREGISTRATIONNUMBER});
        $this->setTaxAccountingBasis(new TaxAccountingBasis((string) $node->{static::N_TAXACCOUNTINGBASIS}));
        $this->setCompanyName((string) $node->{static::N_COMPANYNAME});
        if ($node->{static::N_BUSINESSNAME}->count() > 0) {
            $this->setBusinessName((string) $node->{static::N_BUSINESSNAME});
        } else {
            $this->setBusinessName(null);
        }
        $addr = new AddressPT();
        $addr->parseXmlNode($node->{static::N_COMPANYADDRESS});
        $this->setCompanyAddress($addr);
        $this->setFiscalYear((int) $node->{static::N_FISCALYEAR});
        $this->setStartDate(RDate::parse($dateYmd,
                (string) $node->{static::N_STARTDATE}));
        $this->setEndDate(RDate::parse($dateYmd,
                (string) $node->{static::N_ENDDATE}));
        if ($this->getCurrencyCode() !== (string) $node->{static::N_CURRENCYCODE}) {
            $msg = sprintf("Wrong currency code, your currency is '%s' and should be '%s'",
                $node->{static::N_CURRENCYCODE}, $this->getCurrencyCode());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->setDateCreated(RDate::parse($dateYmd,
                (string) $node->{static::N_DATECREATED}));
        $this->setTaxEntity((string) $node->{static::N_TAXENTITY});
        $this->setProductCompanyTaxID((string) $node->{static::N_PRODUCTCOMPANYTAXID});
        $this->setSoftwareCertificateNumber((int) $node->{static::N_SOFTWARECERTIFICATENUMBER});
        $this->setProductID((string) $node->{static::N_PRODUCTID});
        $this->setProductVersion((string) $node->{static::N_PRODUCTVERSION});
        if ($node->{static::N_HEADERCOMMENT}->count() > 0) {
            $this->setHeaderComment((string) $node->{static::N_HEADERCOMMENT});
        } else {
            $this->setHeaderComment(null);
        }
        if ($node->{static::N_TELEPHONE}->count() > 0) {
            $this->setTelephone((string) $node->{static::N_TELEPHONE});
        } else {
            $this->setTelephone(null);
        }
        if ($node->{static::N_FAX}->count() > 0) {
            $this->setFax((string) $node->{static::N_FAX});
        } else {
            $this->setFax(null);
        }
        if ($node->{static::N_EMAIL}->count() > 0) {
            $this->setEmail((string) $node->{static::N_EMAIL});
        } else {
            $this->setEmail(null);
        }
        if ($node->{static::N_WEBSITE}->count() > 0) {
            $this->setWebsite((string) $node->{static::N_WEBSITE});
        } else {
            $this->setWebsite(null);
        }
    }
}