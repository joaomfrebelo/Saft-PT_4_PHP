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
     * &lt;xs:element ref="AuditFileVersion"/&gt;
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
     * &lt;xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/&gt;<br>
     * @var int $taxRegistrationNumber
     * @since 1.0.0
     */
    private int $taxRegistrationNumber;

    /**
     * &lt;xs:element ref="TaxAccountingBasis"/&gt;<br>
     * @var TaxAccountingBasis $taxAccountingBasis
     * @since 1.0.0
     */
    private TaxAccountingBasis $taxAccountingBasis;

    /**
     * &lt;xs:element ref="CompanyName"/&gt;<br>
     * &lt;xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/&gt;<br>
     * @var string $companyName
     * @since 1.0.0
     */
    private string $companyName;

    /**
     * &lt;xs:element ref="BusinessName" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/&gt;<br>
     * @var string $businessName
     * @since 1.0.0
     */
    private ?string $businessName = null;

    /**
     *
     * &lt;xs:element ref="CompanyAddress"/&gt;<br>
     * &lt;xs:element name="CompanyAddress" type="AddressStructurePT"/&gt;
     * @var AddressPT $companyAddress
     * @since 1.0.0
     */
    private AddressPT $companyAddress;

    /**
     * <pre>
     * &lt;xs:element ref="FiscalYear"/&gt;
     * &lt;xs:element name="FiscalYear"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:integer"&gt;
     *              &lt;xs:minInclusive value="2000"/&gt;
     *              &lt;xs:maxInclusive value="9999"/&gt;
     *        &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @var int $fiscalYear
     * @since 1.0.0
     */
    private int $fiscalYear;

    /**
     * <pre>
     * &lt;xs:element ref="StartDate"/&gt;
     * &lt;xs:element name="StartDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * @var \Rebelo\Date\Date $startDate
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $startDate;

    /**
     * <pre>
     * &lt;xs:element ref="EndDate"/&gt;
     * &lt;xs:element name="EndDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * @var \Rebelo\Date\Date $endDate
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $endDate;

    /**
     * &lt;xs:element name="CurrencyCode" fixed="EUR"/&gt;
     * @var string $currencyCode
     * @since 1.0.0
     */
    private string $currencyCode = "EUR";

    /**
     * &lt;xs:element ref="DateCreated"/&gt;
     * @var \Rebelo\Date\Date $dateCreated
     * @since 1.0.0
     */
    private \Rebelo\Date\Date $dateCreated;

    /**
     *
     * &lt;xs:element ref="TaxEntity"/&gt;<br>
     * &lt;xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     * @var string $taxEntity
     * @since 1.0.0
     */
    private string $taxEntity;

    /**
     *
     * &lt;xs:element ref="ProductCompanyTaxID"/&gt;<br>
     * &lt;xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     *
     * @var string $productCompanyTaxID
     * @since 1.0.0
     */
    private string $productCompanyTaxID;

    /**
     * &lt;xs:element ref="SoftwareCertificateNumber"/&gt;<br>
     * &lt;xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/&gt;<br>
     *
     * @var int $softwareCertificateNumber
     * @since 1.0.0
     */
    private int $softwareCertificateNumber;

    /**
     * <pre>
     * &lt;xs:element ref="ProductID"/&gt;
     * &lt;xs:simpleType name="SAFPTProductID"&gt;
     *     &lt;xs:restriction base="xs:string"&gt;
     *         &lt;xs:pattern value="[^/]+/[^/]+"/&gt;
     *         &lt;xs:minLength value="3"/&gt;
     *         &lt;xs:maxLength value="255"/&gt;
     *     &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @var string $productID
     * @since 1.0.0
     */
    private string $productID;

    /**
     * &lt;xs:element ref="ProductVersion"/&gt;<br>
     * &lt;xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     * @var string $productVersion
     * @since 1.0.0
     */
    private string $productVersion;

    /**
     * &lt;xs:element ref="HeaderComment" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/&gt;<br>
     * @var string|null $headerComment
     * @since 1.0.0
     */
    private ?string $headerComment = null;

    /**
     * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     * @var string $telephone
     * @since 1.0.0
     */
    private ?string $telephone = null;

    /**
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     * @var string $fax
     * @since 1.0.0
     */
    private ?string $fax = null;

    /**
     * &lt;xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/&gt;<br>
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;<br>
     * @var string $email
     * @since 1.0.0
     */
    private ?string $email = null;

    /**
     *
     * &lt;xs:element ref="Website" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @var string|null $website
     * @since 1.0.0
     */
    private ?string $website = null;

    /**
     * The item Header contains the general information regarding the taxpayer,
     * whom the SAF-T (PT) refers to.<br>
     * &lt;xs:element name="Header"&gt;
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
        $this->dateCreated = new \Rebelo\Date\Date();
    }

    /**
     * Gets as auditFileVersion<br>
     * The version of XML scheme to be used is the one available
     * on http://www.portaldasfinancas.gov.pt
     * @return string
     * @since 1.0.0
     */
    public function getAuditFileVersion(): string
    {
        return $this->auditFileVersion;
    }

    /**
     * Gets as companyID <br>
     * It is obtained by linking together the name of the commercial
     * registry office and the commercial registration number,
     * separated by a space.
     * When there is no commercial registration, the Tax Registration Number shall be inserted.<br>
     * <pre>
     * &lt;xs:element ref="CompanyID"/&gt;
     * &lt;xs:element name="CompanyID"&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:pattern value="([0-9]{9})+|([^^]+ [0-9/]+)"/&gt;
     *             &lt;xs:minLength value="1"/&gt;
     *             &lt;xs:maxLength value="50"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCompanyID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", \strval($this->companyID)));
        return $this->companyID;
    }

    /**
     * Get if is set CompanyID
     * @return bool
     * @since 1.0.0
     */
    public function issetCompanyID(): bool
    {
        return isset($this->companyID);
    }

    /**
     * Sets a new companyID<br>
     * It is obtained by linking together the name of the commercial
     * registry office and the commercial registration number,
     * separated by a space.
     * When there is no commercial registration, the Tax Registration Number shall be inserted.<br>
     * <pre>
     * &lt;xs:element ref="CompanyID"/&gt;
     * &lt;xs:element name="CompanyID"&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:pattern value="([0-9]{9})+|([^^]+ [0-9/]+)"/&gt;
     *             &lt;xs:minLength value="1"/&gt;
     *             &lt;xs:maxLength value="50"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param string $companyID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCompanyID(string $companyID): bool
    {
        try {

            if (\preg_match("/([0-9]{9})+|([^^]+ [0-9\/]+)/", $companyID) !== 1) {
                $msg = "Value Not valid";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                $this->getErrorRegistor()->addOnSetValue("company_id_not_valid");
                throw new AuditFileException($msg);
            }
            $this->companyID = static::valTextMandMaxCar(
                $companyID, 50,
                __METHOD__
            );

            $return = true;
        } catch (AuditFileException $e) {
            $this->companyID = $companyID;
            $this->getErrorRegistor()->addOnSetValue("company_id_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $return          = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->companyID));
        return $return;
    }

    /**
     * Gets as taxRegistrationNumber<br>
     * To be filled in with the Portuguese Tax Identification Number/Tax
     * Registration Number without spaces and without country prefixes.<br>
     * &lt;xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/&gt;<br>
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxRegistrationNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->taxRegistrationNumber)
                )
            );
        return $this->taxRegistrationNumber;
    }

    /**
     * Get if is set TaxRegistrationNumber
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxRegistrationNumber(): bool
    {
        return isset($this->taxRegistrationNumber);
    }

    /**
     * Sets the taxRegistrationNumber<br>
     * To be filled in with the Portuguese Tax Identification Number/Tax
     * Registration Number without spaces and without country prefixes.<br>
     * &lt;xs:element name="TaxRegistrationNumber" type="SAFPTPortugueseVatNumber"/&gt;<br>
     * @param int $taxRegistrationNumber
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxRegistrationNumber(int $taxRegistrationNumber): bool
    {
        if (!static::valPortugueseVatNumber($taxRegistrationNumber)) {
            $msg    = strval($taxRegistrationNumber)." is not a valid PT nif";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("TaxRegistrationNumber_not_valid_header");
            $return = false;
        } else {
            $return = true;
        }
        $this->taxRegistrationNumber = $taxRegistrationNumber;
        \Logger::getLogger(\get_class($this))->debug(
            \sprintf(
                __METHOD__." setted to '%s'",
                \strval($this->taxRegistrationNumber)
            )
        );
        return $return;
    }

    /**
     * Gets as taxAccountingBasis<br>
     * Shall be filled in with the type of program, indicating the applicable
     * data (including the transport documents, conference documents and issued receipts, if any):<br>
     * “C” - Accounting;<br>
     * “E” - Invoices issued by third parties;<br>
     * “F” - Invoicing;<br>
     * “I” - Invoicing and accounting integrated data;<br>
     * “P” - Invoicing partial data.<br>
     * “R” - Receipts (a);<br>
     * “S” - Self-billing;<br>
     * “T” - Transport documents (a).<br>
     * (a) Type of program should be indicated, in case only this type of
     * documents are issued. If not, fill in with type “C”, “F” or “I”.
     * &lt;xs:element name="TaxAccountingBasis"&gt;
     * @return \Rebelo\SaftPt\AuditFile\TaxAccountingBasis
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxAccountingBasis(): TaxAccountingBasis
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxAccountingBasis->get()
                )
            );
        return $this->taxAccountingBasis;
    }

    /**
     * Get if is set TaxAccountingBasis
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxAccountingBasis(): bool
    {
        return isset($this->taxAccountingBasis);
    }

    /**
     * Sets a new taxAccountingBasis<br>
     * Shall be filled in with the type of program, indicating the applicable
     * data (including the transport documents, conference documents and issued receipts, if any):<br>
     * “C” - Accounting;<br>
     * “E” - Invoices issued by third parties;<br>
     * “F” - Invoicing;<br>
     * “I” - Invoicing and accounting integrated data;<br>
     * “P” - Invoicing partial data.<br>
     * “R” - Receipts (a);<br>
     * “S” - Self-billing;<br>
     * “T” - Transport documents (a).<br>
     * (a) Type of program should be indicated, in case only this type of
     * documents are issued. If not, fill in with type “C”, “F” or “I”.
     * &lt;xs:element name="TaxAccountingBasis"&gt;
     * @param \Rebelo\SaftPt\AuditFile\TaxAccountingBasis $taxAccountingBasis
     * @return void
     * @since 1.0.0
     */
    public function setTaxAccountingBasis(TaxAccountingBasis $taxAccountingBasis): void
    {
        $this->taxAccountingBasis = $taxAccountingBasis;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->taxAccountingBasis->get()
                )
            );
    }

    /**
     * Gets as companyName<br>
     * Social designation of the company or taxpayer’s name.<br>
     * &lt;xs:element ref="CompanyName"/&gt;<br>
     * &lt;xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/&gt;<br>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCompanyName(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->companyName));
        return $this->companyName;
    }

    /**
     * Get if is set CompanyName
     * @return bool
     * @since 1.0.0
     */
    public function issetCompanyName(): bool
    {
        return isset($this->companyName);
    }

    /**
     * Sets a CompanyName<br>
     * Social designation of the company or taxpayer’s name.<br>
     * &lt;xs:element ref="CompanyName"/&gt;<br>
     * &lt;xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/&gt;<br>
     * @param string $companyName
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCompanyName(string $companyName): bool
    {
        try {
            $this->companyName = static::valTextMandMaxCar(
                $companyName, 100,
                __METHOD__
            );

            $return = true;
        } catch (AuditFileException $e) {
            $this->companyName = $companyName;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("invalid_header_companyname");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(__METHOD__." setted to '%s'", $this->companyName)
            );
        return $return;
    }

    /**
     * Gets as businessName<br>
     * Commercial designation of the taxpayer<br>
     * &lt;xs:element ref="BusinessName" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/&gt;<br>     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBusinessName(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->businessName === null ? "null" : $this->businessName
                )
            );
        return $this->businessName;
    }

    /**
     * Sets a new businessName<br>
     * Commercial designation of the taxpayer<br>
     * &lt;xs:element ref="BusinessName" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="BusinessName" type="SAFPTtextTypeMandatoryMax60Car"/&gt;<br>     *
     * @param string|null $businessName
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setBusinessName(?string $businessName): bool
    {
        try {
            $this->businessName = $businessName === null ?
                null : static::valTextMandMaxCar($businessName, 60, __METHOD__);

            $return = true;
        } catch (AuditFileException $e) {
            $this->businessName = $businessName;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("invalid_BusinessName");
            $return             = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->businessName === null ? "null" : $this->businessName
                )
            );
        return $return;
    }

    /**
     * Gets as companyAddress<br>
     * &lt;xs:element ref="CompanyAddress"/&gt;<br>
     * &lt;xs:element name="CompanyAddress" type="AddressStructurePT"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\AddressPT
     * @since 1.0.0
     */
    public function getCompanyAddress(): AddressPT
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        if (isset($this->companyAddress) === false) {
            $this->companyAddress = new AddressPT($this->getErrorRegistor());
        }
        return $this->companyAddress;
    }

    /**
     * Get if is set CompanyAddress
     * @return bool
     * @since 1.0.0
     */
    public function issetCompanyAddress(): bool
    {
        return isset($this->companyAddress);
    }

    /**
     * Gets Fiscal Year<br>
     * Use Corporate Income Tax Code rules, in the case of accounting
     * periods that do not coincide with the calendar year.
     * (E.g. taxation period from 2012-10-01 to 2013-09-30 corresponds to
     * the Fiscal year = 2012).
     * <pre>
     * &lt;xs:element ref="FiscalYear"/&gt;
     * &lt;xs:element name="FiscalYear"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:integer"&gt;
     *              &lt;xs:minInclusive value="2000"/&gt;
     *              &lt;xs:maxInclusive value="9999"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * <br>
     * The max date allow is the next year<br>     *
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getFiscalYear(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->fiscalYear)
                )
            );
        return $this->fiscalYear;
    }

    /**
     * Get if is set FiscalYear
     * @return bool
     * @since 1.0.0
     */
    public function issetFiscalYear(): bool
    {
        return isset($this->fiscalYear);
    }

    /**
     * Sets a Fiscal Year<br>
     * Use Corporate Income Tax Code rules, in the case of accounting
     * periods that do not coincide with the calendar year.
     * (E.g. taxation period from 2012-10-01 to 2013-09-30 corresponds to
     * the Fiscal year = 2012).
     * <pre>
     * &lt;xs:element ref="FiscalYear"/&gt;
     * &lt;xs:element name="FiscalYear"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:integer"&gt;
     *              &lt;xs:minInclusive value="2000"/&gt;
     *              &lt;xs:maxInclusive value="????"/&gt; the current year
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * <br>
     * The max date allow is the next year<br>     *
     * @param int $fiscalYear
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setFiscalYear(int $fiscalYear): bool
    {
        $ano = \intval(\Date("Y"));
        if ($fiscalYear < 2000 || $fiscalYear > $ano) {
            $msg    = \strval($fiscalYear)." is not a valid year";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("FiscalYear_not_valid");
            $return = false;
        } else {
            $return = true;
        }
        $this->fiscalYear = $fiscalYear;
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->fiscalYear)
                )
            );
        return $return;
    }

    /**
     * Gets Start Date<br>
     * <pre>
     * &lt;xs:element ref="StartDate"/&gt;
     * &lt;xs:element name="StartDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getStartDate(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->startDate->getTimestamp())
                )
            );
        return $this->startDate;
    }

    /**
     * Get if is set StartDate
     * @return bool
     * @since 1.0.0
     */
    public function issetStartDate(): bool
    {
        return isset($this->startDate);
    }

    /**
     * Sets StartDate<br>
     * <pre>
     * &lt;xs:element ref="StartDate"/&gt;
     * &lt;xs:element name="StartDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     *
     * @param \Rebelo\Date\Date $startDate
     * @return bool
     * @since 1.0.0
     */
    public function setStartDate(\Rebelo\Date\Date $startDate): bool
    {
        $year    = \intval($startDate->format(\Rebelo\Date\Date::YAER));
        $yearNow = \intval((new \Rebelo\Date\Date())->format(\Rebelo\Date\Date::YAER))
            + 1;
        if ($year < 2000 || $year > $yearNow) {
            $msg    = \sprintf(
                "Date must be between '%s' and '%s'", $year,
                $yearNow
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("StartDate_not_valid");
            $return = false;
        } else {
            $return = true;
        }
        $this->startDate = $startDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->startDate->format(\Rebelo\Date\Date::SQL_DATE)
                )
            );
        return $return;
    }

    /**
     * Gets as endDate<br>
     * <pre>
     * &lt;xs:element ref="EndDate"/&gt;
     * &lt;xs:element name="EndDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * <br>
     * The max date allow is the next year<br>
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getEndDate(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->endDate->format(\Rebelo\Date\Date::SQL_DATE)
                )
            );
        return $this->endDate;
    }

    /**
     * Get if is set EndDate
     * @return bool
     * @since 1.0.0
     */
    public function issetEndDate(): bool
    {
        return isset($this->endDate);
    }

    /**
     * Sets a new endDate<br>
     * <pre>
     * &lt;xs:element ref="EndDate"/&gt;
     * &lt;xs:element name="EndDate" type="SAFPTDateSpan"/&gt;
     * &lt;xs:simpleType name="SAFPTDateSpan"&gt;
     *       &lt;xs:restriction base="xs:date"&gt;
     *           &lt;xs:minInclusive value="2000-01-01"/&gt;
     *           &lt;xs:maxInclusive value="9999-12-31"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     * @param \Rebelo\Date\Date $endDate
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setEndDate(\Rebelo\Date\Date $endDate): bool
    {
        $year    = \intval($endDate->format(\Rebelo\Date\Date::YAER));
        $yearNow = \intval((new \Rebelo\Date\Date())->format(\Rebelo\Date\Date::YAER))
            + 1;
        if ($year < 2000 || $year > $yearNow) {
            $msg    = \sprintf(
                "Date must be between '%s' and '%s'", $year,
                $yearNow
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("EndDate_not_valid");
            $return = false;
        } else {
            $return = true;
        }
        $this->endDate = $endDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->endDate->format(\Rebelo\Date\Date::SQL_DATE)
                )
            );
        return $return;
    }

    /**
     * Gets as currencyCode<br>
     * Identifies the default currency to use in the monetary type
     * fields in the file. Fill in with "EUR".<br>
     * &lt;xs:element name="CurrencyCode" fixed="EUR"/&gt;
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
     * Get if is set CurrencyCode
     * @return bool
     * @since 1.0.0
     */
    public function issetCurrencyCode(): bool
    {
        return isset($this->currencyCode);
    }

    /**
     * Gets as dateCreated<br>
     * Date of creation of file XML of SAF-T (PT)<br>
     * &lt;xs:element ref="DateCreated"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getDateCreated(): \Rebelo\Date\Date
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->dateCreated->format(\Rebelo\Date\Date::ATOM)
                )
            );
        return $this->dateCreated;
    }

    /**
     * Get if is set DateCreated
     * @return bool
     * @since 1.0.0
     */
    public function issetDateCreated(): bool
    {
        return isset($this->dateCreated);
    }

    /**
     * Sets a new dateCreated<br>
     * Date of creation of file XML of SAF-T (PT)<br>
     * &lt;xs:element ref="DateCreated"/&gt;<br>
     * Tthe creation date is setten when the object is created,
     * use this if you wont a diferent date from that one in the OS<br>
     * @param \Rebelo\Date\Date $dateCreated
     * @return void
     * @since 1.0.0
     */
    public function setDateCreated(\Rebelo\Date\Date $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->dateCreated->format(\Rebelo\Date\Date::ATOM)
                )
            );
    }

    /**
     * Gets as taxEntity<br>
     * In the case of an invoicing file, it shall be specified which
     * establishment the produced file refers to, if applicable, otherwise it
     * must be filled in with the specification “Global”.
     * In the case of an accounting file or integrated file this
     * field must be filled in with the specification “Sede”. <br>
     * &lt;xs:element ref="TaxEntity"/&gt;<br>
     * &lt;xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxEntity(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxEntity));
        return $this->taxEntity;
    }

    /**
     * Get if is set TaxEntity
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxEntity(): bool
    {
        return isset($this->taxEntity);
    }

    /**
     * Sets a new taxEntity<br>
     * In the case of an invoicing file, it shall be specified which
     * establishment the produced file refers to, if applicable, otherwise it
     * must be filled in with the specification “Global”.
     * In the case of an accounting file or integrated file this
     * field must be filled in with the specification “Sede”. <br>
     * &lt;xs:element ref="TaxEntity"/&gt;<br>
     * &lt;xs:element name="TaxEntity" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     *
     * @param string $taxEntity
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxEntity(string $taxEntity): bool
    {
        try {
            $this->taxEntity = static::valTextMandMaxCar(
                $taxEntity, 20, __METHOD__
            );
            $return          = true;
        } catch (AuditFileException $e) {
            $this->taxEntity = $taxEntity;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntity_not_valid");
            $return          = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxEntity));
        return $return;
    }

    /**
     * Gets as productCompanyTaxID<br>
     * Fill in with the Tax Identification Number/Tax Registration Number
     * of the entity that produced the software.<br>
     * &lt;xs:element ref="ProductCompanyTaxID"/&gt;<br>
     * &lt;xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductCompanyTaxID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->productCompanyTaxID
                )
            );
        return $this->productCompanyTaxID;
    }

    /**
     * Get if is set ProductCompanyTaxID
     * @return bool
     * @since 1.0.0
     */
    public function issetProductCompanyTaxID(): bool
    {
        return isset($this->productCompanyTaxID);
    }

    /**
     * Sets a new productCompanyTaxID<br>
     * Fill in with the Tax Identification Number/Tax Registration Number
     * of the entity that produced the software.<br>
     * &lt;xs:element ref="ProductCompanyTaxID"/&gt;<br>
     * &lt;xs:element name="ProductCompanyTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     *
     * @param string $productCompanyTaxID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setProductCompanyTaxID(string $productCompanyTaxID): bool
    {
        try {
            $this->productCompanyTaxID = static::valTextMandMaxCar(
                $productCompanyTaxID, 30, __METHOD__
            );
            $return                    = true;
        } catch (AuditFileException $e) {
            $this->productCompanyTaxID = $productCompanyTaxID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductCompanyTaxID_not_valid");
            $return                    = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->productCompanyTaxID
                )
            );
        return $return;
    }

    /**
     * Get SoftwareCertificateNumber<br>
     * Number of the software certificate allocated to the entity that
     * created the software, pursuant to Ordinance No. 363/2010, of 23th June.
     * it doesn’t apply, the field must be filled in with “0” (zero).<br>
     * &lt;xs:element ref="SoftwareCertificateNumber"/&gt;<br>
     * &lt;xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/&gt;<br>
     *
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getSoftwareCertificateNumber(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->softwareCertificateNumber
                )
            );
        return $this->softwareCertificateNumber;
    }

    /**
     * Get if is set SoftwareCertificateNumber
     * @return bool
     * @since 1.0.0
     */
    public function issetSoftwareCertificateNumber(): bool
    {
        return isset($this->softwareCertificateNumber);
    }

    /**
     * Sets a new softwareCertificateNumber<br><br>
     * Number of the software certificate allocated to the entity that
     * created the software, pursuant to Ordinance No. 363/2010, of 23th June.
     * it doesn’t apply, the field must be filled in with “0” (zero).<br>
     * &lt;xs:element ref="SoftwareCertificateNumber"/&gt;<br>
     * &lt;xs:element name="SoftwareCertificateNumber" type="xs:nonNegativeInteger"/&gt;<br>
     *
     * @param int $softwareCertificateNumber
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSoftwareCertificateNumber(int $softwareCertificateNumber): bool
    {
        if ($softwareCertificateNumber < 0) {
            $msg    = "certification number must be non negative integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("SoftwareCertificateNumber_not_valid");
            $return = false;
        } else {
            $return = true;
        }
        $this->softwareCertificateNumber = $softwareCertificateNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->softwareCertificateNumber
                )
            );
        return $return;
    }

    /**
     * Gets as productID<br>
     * Name of the product that generates the SAF-T (PT).
     * The commercial name of the software as well as the name of the company that
     * produced it shall be indicated in the format “Product name/company name”.
     * <pre>
     * &lt;xs:element ref="ProductID"/&gt;
     * &lt;xs:simpleType name="SAFPTProductID"&gt;
     *     &lt;xs:restriction base="xs:string"&gt;
     *         &lt;xs:pattern value="[^/]+/[^/]+"/&gt;
     *         &lt;xs:minLength value="3"/&gt;
     *         &lt;xs:maxLength value="255"/&gt;
     *     &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productID));
        return $this->productID;
    }

    /**
     * Get if is set ProductID
     * @return bool
     * @since 1.0.0
     */
    public function issetProductID(): bool
    {
        return isset($this->productID);
    }

    /**
     * Sets a new productID<br>
     * Name of the product that generates the SAF-T (PT).
     * The commercial name of the software as well as the name of the company that
     * produced it shall be indicated in the format “Product name/company name”.
     * <pre>
     * &lt;xs:element ref="ProductID"/&gt;
     * &lt;xs:simpleType name="SAFPTProductID"&gt;
     *     &lt;xs:restriction base="xs:string"&gt;
     *         &lt;xs:pattern value="[^/]+/[^/]+"/&gt;
     *         &lt;xs:minLength value="3"/&gt;
     *         &lt;xs:maxLength value="255"/&gt;
     *     &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @param string $productID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setProductID(string $productID): bool
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
            $this->getErrorRegistor()->addOnSetValue("ProductID_not_valid");
            $return = false;
        } else {
            $return = true;
        }
        $this->productID = $productID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->productID));
        return $return;
    }

    /**
     * Gets as productVersion<br>
     * The product version shall be indicated<br>
     * &lt;xs:element ref="ProductVersion"/&gt;<br>
     * &lt;xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getProductVersion(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->productVersion));
        return $this->productVersion;
    }

    /**
     * Get if is set ProductVersion
     * @return bool
     * @since 1.0.0
     */
    public function issetProductVersion(): bool
    {
        return isset($this->productVersion);
    }

    /**
     * Sets a new productVersion<br>
     * The product version shall be indicated<br>
     * &lt;xs:element ref="ProductVersion"/&gt;<br>
     * &lt;xs:element name="ProductVersion" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     *
     * @param string $productVersion
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setProductVersion(string $productVersion): bool
    {
        try {
            $this->productVersion = static::valTextMandMaxCar(
                $productVersion,
                30, __METHOD__
            );
            $return               = true;
        } catch (AuditFileException $e) {
            $this->productVersion = $productVersion;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductVersion_not_valid");
            $return               = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->productVersion));
        return $return;
    }

    /**
     * Gets as headerComment<br>
     * &lt;xs:element ref="HeaderComment" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/&gt;<br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getHeaderComment(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->headerComment === null ? "null" : $this->headerComment
                )
            );
        return $this->headerComment;
    }

    /**
     * Sets HeaderComment<br>
     * &lt;xs:element ref="HeaderComment" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="HeaderComment" type="SAFPTtextTypeMandatoryMax255Car"/&gt;<br>
     *
     * @param string|null $headerComment
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setHeaderComment(?string $headerComment): bool
    {
        try {
            $this->headerComment = $headerComment === null ?
                null : static::valTextMandMaxCar(
                    $headerComment, 255, __METHOD__
                );
            $return              = true;
        } catch (AuditFileException $e) {
            $this->headerComment = $headerComment;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("HeaderComment_not_valid");
            $return              = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->headerComment === null ? "null" : $this->headerComment
                )
            );
        return $return;
    }

    /**
     * Gets as telephone<br>
     * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTelephone(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->telephone === null ? "null" : $this->telephone
                )
            );
        return $this->telephone;
    }

    /**
     * Sets a new telephone<br>
     * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     *
     * @param string|null $telephone
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTelephone(?string $telephone): bool
    {
        try {
            $this->telephone = $telephone === null ?
                null : static::valTextMandMaxCar($telephone, 20, __METHOD__);
            $return          = true;
        } catch (AuditFileException $e) {
            $this->telephone = $telephone;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Telephone_Header_not_valid");
            $return          = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->telephone === null ? "null" : $this->telephone
                )
            );
        return $return;
    }

    /**
     * Gets as fax<br>
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getFax(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->fax === null ? "null" : $this->fax
                )
            );
        return $this->fax;
    }

    /**
     * Sets a new fax<br>
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
     *
     * @param string|null $fax
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setFax(?string $fax): bool
    {
        try {
            $this->fax = $fax === null ?
                null : static::valTextMandMaxCar($fax, 20, __METHOD__);
            $return    = true;
        } catch (AuditFileException $e) {
            $this->fax = $fax;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Fax_Header_not_valid");
            $return    = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->fax === null ? "null" : $this->fax
                )
            );
        return $return;
    }

    /**
     * Gets as email<br>
     * &lt;xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/&gt;<br>
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;<br>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getEmail(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->email === null ? "null" : $this->email
                )
            );
        return $this->email;
    }

    /**
     * Sets a new email<br>
     * &lt;xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/&gt;<br>
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;<br>
     *
     * @param string|null $email
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setEmail(?string $email): bool
    {
        if ($email === null) {
            $return = true;
        } else {
            if (\filter_var($email, FILTER_VALIDATE_EMAIL) === false ||
                \strlen($email) > 254) {
                $msg    = $email." is not a valid email";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                $this->getErrorRegistor()->addOnSetValue("Email_Header_not_valid");
                $return = false;
            } else {
                $return = true;
            }
        }
        $this->email = $email;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->email === null ? "null" : $this->email
                )
            );
        return $return;
    }

    /**
     * Gets as website<br>
     * &lt;xs:element ref="Website" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getWebsite(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->website === null ? "null" : $this->website
                )
            );
        return $this->website;
    }

    /**
     * Sets a new website
     * &lt;xs:element ref="Website" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @param string|null $website
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setWebsite(?string $website): bool
    {
        try {
            $this->website = $website === null ? null :
                $this->valTextMandMaxCar($website, 60, __METHOD__, false);
            $return        = true;
        } catch (AuditFileException $e) {
            $this->website = $website;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Website_Header_not_valid");
            $return        = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->website === null ? "null" : $this->website
                )
            );
        return $return;
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== AuditFile::N_AUDITFILE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                AuditFile::N_AUDITFILE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $dateYmd    = RDate::SQL_DATE;
        $headerNode = $node->addChild(static::N_HEADER);
        $headerNode->addChild(
            static::N_AUDITFILEVERSION, $this->getAuditFileVersion()
        );

        if (isset($this->companyID)) {
            $headerNode->addChild(static::N_COMPANYID, $this->getCompanyID());
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("company_id_not_valid");
        }

        if (isset($this->taxRegistrationNumber)) {
            $headerNode->addChild(
                static::N_TAXREGISTRATIONNUMBER,
                \strval($this->getTaxRegistrationNumber())
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxRegistrationNumber_not_valid_header");
        }

        if (isset($this->taxAccountingBasis)) {
            $headerNode->addChild(
                static::N_TAXACCOUNTINGBASIS,
                $this->getTaxAccountingBasis()->get()
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxAccountingBasis_is_not_setted");
        }

        if (isset($this->companyName)) {
            $headerNode->addChild(static::N_COMPANYNAME, $this->getCompanyName());
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("invalid_header_companyname");
        }

        if ($this->getBusinessName() !== null) {
            $headerNode->addChild(
                static::N_BUSINESSNAME, $this->getBusinessName()
            );
        }

        if (isset($this->companyAddress)) {
            $compAddr = $headerNode->addChild(static::N_COMPANYADDRESS);
            $this->getCompanyAddress()->createXmlNode($compAddr);
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("CompanyAddress_is_not_setted");
        }

        if (isset($this->fiscalYear)) {
            $headerNode->addChild(
                static::N_FISCALYEAR, \strval($this->getFiscalYear())
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("FiscalYear_not_valid");
        }

        if (isset($this->startDate)) {
            $headerNode->addChild(
                static::N_STARTDATE, $this->getStartDate()->format($dateYmd)
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("StartDate_not_valid");
        }

        if (isset($this->endDate)) {
            $headerNode->addChild(
                static::N_ENDDATE, $this->getEndDate()->format($dateYmd)
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("EndDate_not_valid");
        }

        $headerNode->addChild(static::N_CURRENCYCODE, $this->getCurrencyCode());

        if (isset($this->dateCreated)) {
            $headerNode->addChild(
                static::N_DATECREATED, $this->getDateCreated()->format($dateYmd)
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("DateCreated_not_valid");
        }

        if (isset($this->taxEntity)) {
            $headerNode->addChild(static::N_TAXENTITY, $this->getTaxEntity());
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntity_not_valid");
        }

        if (isset($this->productCompanyTaxID)) {
            $headerNode->addChild(
                static::N_PRODUCTCOMPANYTAXID, $this->getProductCompanyTaxID()
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductCompanyTaxID_not_valid");
        }

        if (isset($this->softwareCertificateNumber)) {
            $headerNode->addChild(
                static::N_SOFTWARECERTIFICATENUMBER,
                \strval($this->getSoftwareCertificateNumber())
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("SoftwareCertificateNumber_not_valid");
        }

        if (isset($this->productID)) {
            $headerNode->addChild(static::N_PRODUCTID, $this->getProductID());
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductID_not_valid");
        }

        if (isset($this->productVersion)) {
            $headerNode->addChild(
                static::N_PRODUCTVERSION, $this->getProductVersion()
            );
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("ProductVersion_not_valid");
        }

        if ($this->getHeaderComment() !== null) {
            $headerNode->addChild(
                static::N_HEADERCOMMENT, $this->getHeaderComment()
            );
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
            $msg = sprintf(
                "Wrong audit file version, your file is '%s' and should be '%s'",
                $node->{static::N_AUDITFILEVERSION},
                $this->getAuditFileVersion()
            );
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
        $addr = $this->getCompanyAddress();
        $addr->parseXmlNode($node->{static::N_COMPANYADDRESS});

        $this->setFiscalYear((int) $node->{static::N_FISCALYEAR});

        $this->setStartDate(
            RDate::parse($dateYmd, (string) $node->{static::N_STARTDATE})
        );

        $this->setEndDate(
            RDate::parse($dateYmd, (string) $node->{static::N_ENDDATE})
        );

        if ($this->getCurrencyCode() !== (string) $node->{static::N_CURRENCYCODE}) {
            $msg = sprintf(
                "Wrong currency code, your currency is '%s' and should be '%s'",
                $node->{static::N_CURRENCYCODE}, $this->getCurrencyCode()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setDateCreated(
            RDate::parse($dateYmd, (string) $node->{static::N_DATECREATED})
        );

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