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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Description of ACustomerSupplier
 *
 * @author João Rebelo
 */
abstract class ACustomerSupplier extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_ACCOUNTID = "AccountID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_COMPANYNAME = "CompanyName";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CONTACT = "Contact";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_BILLINGADDRESS = "BillingAddress";

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
     * Node name
     * @since 1.0.0
     */
    const N_SELFBILLINGINDICATOR = "SelfBillingIndicator";

    /**
     * &lt;xs:element ref="AccountID"/&gt;
     * @var string $accountID
     * @since 1.0.0
     */
    protected string $accountID;

    /**
     * &lt;xs:element ref="CompanyName"/&gt;
     * @var string $companyName
     * @since 1.0.0
     */
    protected string $companyName;

    /**
     * &lt;xs:element ref="Contact" minOccurs="0"/&gt;
     * @var string|null $contact
     * @since 1.0.0
     */
    protected ?string $contact = null;

    /**
     * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;
     * @var string|null $telephone
     * @since 1.0.0
     */
    protected ?string $telephone = null;

    /**
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;
     * @var string|null $fax
     * @since 1.0.0
     */
    protected ?string $fax = null;

    /**
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;
     * @var string|null $email
     * @since 1.0.0
     */
    protected ?string $email = null;

    /**
     * &lt;xs:element ref="Website" minOccurs="0"/&gt;
     * @var string|null $website
     * @since 1.0.0
     */
    protected ?string $website = null;

    /**
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * @var bool $selfBillingIndicator
     * @since 1.0.0
     */
    protected bool $selfBillingIndicator;

    /**
     * Base class for Customer and supplier
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets as accountID<br>
     * The respective current account must be indicated in the general
     * accounting plan, if it is defined. Otherwise the field shall be filled
     * in with the designation “Desconhecido” (Unknown).
     *
     * <pre>
     * &lt;xs:element ref="AccountID"/&gt;
     * &lt;xs:element name="AccountID"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:pattern value="(([^^]*)|Desconhecido)"/&gt;
     *              &lt;xs:minLength value="1"/&gt;
     *              &lt;xs:maxLength value="30"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getAccountID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->accountID));
        return $this->accountID;
    }

    /**
     * Get if is set AccountID
     * @return bool
     * @since 1.0.0
     */
    public function issetAccountID(): bool
    {
        return isset($this->accountID);
    }

    /**
     * Sets AccountID<br>
     * The respective current account must be indicated in the general
     * accounting plan, if it is defined. Otherwise the field shall be filled
     * in with the designation “Desconhecido” (Unknown).
     * <pre>
     * &lt;xs:element ref="AccountID"/&gt;
     * &lt;xs:element name="AccountID"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:pattern value="(([^^]*)|Desconhecido)"/&gt;
     *              &lt;xs:minLength value="1"/&gt;
     *              &lt;xs:maxLength value="30"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     *
     * @param string $accountID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setAccountID(string $accountID): bool
    {
        $msg    = null;
        $length = \strlen($accountID);
        if ($length < 1 || $length > 30) {
            $msg = sprintf(
                "AccountID length must be between 1 and 30 but have '%s'",
                $length
            );
        }

        $regexp = "/(([^^]*)|Desconhecido)/";
        if (\preg_match($regexp, $accountID) !== 1) {
            $msg = sprintf("AccountID does not respect the regexp '%s'", $regexp);
        }
        if ($msg !== null) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("AccountID_not_valid");
        } else {
            $return = true;
        }
        $this->accountID = $accountID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->accountID));
        return $return;
    }

    /**
     * Gets as companyName<br>
     * The generic client/supplier shall be identified with the designation
     * “Consumidor final” (Final Consumer).<br>
     * &lt;xs:element ref="CompanyName"/&gt;<br>
     * &lt;xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     *
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
     * Sets CompanyName<br>
     * The generic client/supplier shall be identified with the designation
     * “Consumidor final” (Final Consumer).<br>
     * &lt;xs:element ref="CompanyName"/&gt;<br>
     * &lt;xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     *
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
            $return            = true;
        } catch (AuditFileException $e) {
            $this->companyName = $companyName;
            $return            = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("CompanyName_not_valid");
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->companyName));
        return $return;
    }

    /**
     * Gets Contact<br>
     * Name of the contact person in the company<br>
     * &lt;xs:element ref="Contact" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Contact" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getContact(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->contact === null ? "null" : $this->contact
                )
            );
        return $this->contact;
    }

    /**
     * Sets Contact<br><br>
     * Name of the contact person in the company<br>
     * &lt;xs:element ref="Contact" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Contact" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     *
     * @param string|null $contact
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setContact(?string $contact): bool
    {

        try {
            $this->contact = $contact === null ?
                null : static::valTextMandMaxCar($contact, 50, __METHOD__);
            $return        = true;
        } catch (AuditFileException $e) {
            $this->contact = $contact;
            $return        = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("CompanyName_not_valid");
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->contact === null ? "null" : $this->contact
                )
            );
        return $return;
    }

    /**
     * Gets as telephone<br>
     * &lt;xs:element ref="Telephone" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
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
     * &lt;xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
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
            $return          = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Telephone_not_valid");
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
     * Gets fax<br>
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
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
     * Sets fax<br>
     * &lt;xs:element ref="Fax" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
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
            $return    = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Fax_not_valid");
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
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/&gt;
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
     * &lt;xs:element ref="Email" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/&gt;
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
                $return = false;
                $this->getErrorRegistor()->addOnSetValue("Email_not_valid");
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
     * Sets a new website<br>
     * &lt;xs:element ref="Website" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @param string|null $website
     * @return bool true if the value is valid
     * @throws AuditFileException
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
            $return        = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Website_not_valid");
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
     * Gets as selfBillingIndicator<br>
     * Indicator of the existence of a self-billing agreement
     * between the customer and the supplier.
     * The field shall be filled in with “1” if there is an agreement and
     * with “0” (zero) if there is not one.
     * <pre>
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * <!-- Indicador de Autofaturacao -->
     *   &lt;xs:element name="SelfBillingIndicator"&gt;
     *       &lt;xs:simpleType&gt;
     *           &lt;xs:restriction base="xs:integer"&gt;
     *               &lt;xs:minInclusive value="0"/&gt;
     *               &lt;xs:maxInclusive value="1"/&gt;
     *           &lt;/xs:restriction&gt;
     *       &lt;/xs:simpleType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     * @return bool
     * @throws \Error
     * @since 1.0.0
     */
    public function getSelfBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->website ? "true" : "false"
                )
            );
        return $this->selfBillingIndicator;
    }

    /**
     * Sets a new selfBillingIndicator<br>
     * Indicator of the existence of a self-billing agreement
     * between the customer and the supplier.
     * The field shall be filled in with “1” if there is an agreement and
     * with “0” (zero) if there is not one.
     * <pre>
     * &lt;xs:element ref="SelfBillingIndicator"/&gt;
     * <!-- Indicador de Autofaturacao -->
     *   &lt;xs:element name="SelfBillingIndicator"&gt;
     *       &lt;xs:simpleType&gt;
     *           &lt;xs:restriction base="xs:integer"&gt;
     *               &lt;xs:minInclusive value="0"/&gt;
     *               &lt;xs:maxInclusive value="1"/&gt;
     *           &lt;/xs:restriction&gt;
     *       &lt;/xs:simpleType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     *
     * @param bool $selfBillingIndicator
     * @return void
     * @since 1.0.0
     */
    public function setSelfBillingIndicator(bool $selfBillingIndicator): void
    {
        $this->selfBillingIndicator = $selfBillingIndicator;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->website ? "true" : "false"
                )
            );
    }
}