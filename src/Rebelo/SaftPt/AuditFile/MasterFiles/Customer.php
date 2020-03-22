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

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\Address;

/**
 * <pre>
 *     <xs:element name="Customer">
 *       <xs:complexType>
 *           <xs:sequence>
 *               <xs:element ref="CustomerID"/>
 *               <xs:element ref="AccountID"/>
 *               <xs:element ref="CustomerTaxID"/>
 *               <xs:element ref="CompanyName"/>
 *               <xs:element ref="Contact" minOccurs="0"/>
 *               <xs:element ref="BillingAddress"/>
 *               <xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/>
 *               <xs:element ref="Telephone" minOccurs="0"/>
 *               <xs:element ref="Fax" minOccurs="0"/>
 *               <xs:element ref="Email" minOccurs="0"/>
 *               <xs:element ref="Website" minOccurs="0"/>
 *               <xs:element ref="SelfBillingIndicator"/>
 *           </xs:sequence>
 *       </xs:complexType>
 *   </xs:element>
 * </pre>
 *
 * Class Customer
 * @since 1.0.0
 */
class Customer
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMER = "Customer";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMERID = "CustomerID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ACCOUNTID = "AccountID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMERTAXID = "CustomerTaxID";

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
    const N_SHIPTOADDRESS = "ShipToAddress";

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
     * <xs:element ref="CustomerID"/>
     * @var string $customerID
     * @since 1.0.0
     */
    private string $customerID;

    /**
     * <xs:element ref="AccountID"/>
     * @var string $accountID
     * @since 1.0.0
     */
    private string $accountID;

    /**
     * <xs:element ref="CustomerTaxID"/>
     * @var string $customerTaxID
     * @since 1.0.0
     */
    private string $customerTaxID;

    /**
     * <xs:element ref="CompanyName"/>
     * @var string $companyName
     * @since 1.0.0
     */
    private string $companyName;

    /**
     * <xs:element ref="Contact" minOccurs="0"/>
     * @var string|null $contact
     * @since 1.0.0
     */
    private ?string $contact = null;

    /**
     * <xs:element ref="BillingAddress"/>
     * @var \Rebelo\SaftPt\AuditFile\Address $billingAddress
     * @since 1.0.0
     */
    private Address $billingAddress;

    /**
     * <xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\Address[] $shipToAddress
     * @since 1.0.0
     */
    private array $shipToAddress = array();

    /**
     * <xs:element ref="Telephone" minOccurs="0"/>
     * @var string|null $telephone
     * @since 1.0.0
     */
    private ?string $telephone = null;

    /**
     * <xs:element ref="Fax" minOccurs="0"/>
     * @var string|null $fax
     * @since 1.0.0
     */
    private ?string $fax = null;

    /**
     * <xs:element ref="Email" minOccurs="0"/>
     * @var string|null $email
     * @since 1.0.0
     */
    private ?string $email = null;

    /**
     * <xs:element ref="Website" minOccurs="0"/>
     * @var string|null $website
     * @since 1.0.0
     */
    private ?string $website = null;

    /**
     * <xs:element ref="SelfBillingIndicator"/>
     * @var bool $selfBillingIndicator
     * @since 1.0.0
     */
    private bool $selfBillingIndicator;

    /**
     *
     * <pre>
     *     <xs:element name="Customer">
     *       <xs:complexType>
     *           <xs:sequence>
     *               <xs:element ref="CustomerID"/>
     *               <xs:element ref="AccountID"/>
     *               <xs:element ref="CustomerTaxID"/>
     *               <xs:element ref="CompanyName"/>
     *               <xs:element ref="Contact" minOccurs="0"/>
     *               <xs:element ref="BillingAddress"/>
     *               <xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/>
     *               <xs:element ref="Telephone" minOccurs="0"/>
     *               <xs:element ref="Fax" minOccurs="0"/>
     *               <xs:element ref="Email" minOccurs="0"/>
     *               <xs:element ref="Website" minOccurs="0"/>
     *               <xs:element ref="SelfBillingIndicator"/>
     *           </xs:sequence>
     *       </xs:complexType>
     *   </xs:element>
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as customerID<br>
     * <xs:element ref="CustomerID"/><br>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     *
     * @return string
     * @since 1.0.0
     */
    public function getCustomerID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->customerID));
        return $this->customerID;
    }

    /**
     * Sets a new customerID<br>
     * <xs:element ref="CustomerID"/><br>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     *
     * @param string $customerID
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): void
    {
        $this->customerID = static::valTextMandMaxCar($customerID, 30,
                                                      __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->customerID));
    }

    /**
     * Gets as accountID<br>
     * <br>
     * <xs:element ref="AccountID"/><br>
     * <pre>
     * <xs:element name="AccountID">
     *      <xs:simpleType>
     *          <xs:restriction base="xs:string">
     *              <xs:pattern value="(([^^]*)|Desconhecido)"/>
     *              <xs:minLength value="1"/>
     *              <xs:maxLength value="30"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     *
     * @return string
     * @since 1.0.0
     */
    public function getAccountID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->accountID));
        return $this->accountID;
    }

    /**
     * Sets a new accountID<br>
     * <xs:element ref="AccountID"/><br>
     * <pre>
     * <xs:element name="AccountID">
     *      <xs:simpleType>
     *          <xs:restriction base="xs:string">
     *              <xs:pattern value="(([^^]*)|Desconhecido)"/>
     *              <xs:minLength value="1"/>
     *              <xs:maxLength value="30"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     *
     * @param string $accountID
     * @return void
     * @since 1.0.0
     */
    public function setAccountID(string $accountID): void
    {
        $msg    = null;
        $length = \strlen($accountID);
        if ($length < 1 || $length > 30)
        {
            $msg = sprintf("AccountID length must be between 1 and 30 but have '%s'",
                           $length);
        }

        $regexp = "/(([^^]*)|Desconhecido)/";
        if (\preg_match($regexp, $accountID) !== 1)
        {
            $msg = sprintf("AccountID does not respect the regexp '%s'", $regexp);
        }
        if ($msg !== null)
        {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->accountID = $accountID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->accountID));
    }

    /**
     * Gets as customerTaxID<br>
     * <xs:element ref="CustomerTaxID"/><br>
     * <xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/>
     *
     * @return string
     * @since 1.0.0
     */
    public function getCustomerTaxID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->customerTaxID));
        return $this->customerTaxID;
    }

    /**
     * Sets a new customerTaxID<br>
     * <xs:element ref="CustomerTaxID"/><br>
     * <xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/>
     *
     * @param string $customerTaxID
     * @return void
     * @since 1.0.0
     */
    public function setCustomerTaxID(string $customerTaxID): void
    {
        $msg    = null;
        $length = \strlen($customerTaxID);
        if ($length < 1 || $length > 30)
        {
            $msg = sprintf("CustomerTaxID length must be between 1 and 30 but have '%s'",
                           $length);
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->customerTaxID = $customerTaxID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->customerTaxID));
    }

    /**
     * Gets as companyName<br>
     * <xs:element ref="CompanyName"/><br>
     * <xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/>
     *
     * @return string
     * @since 1.0.0
     */
    public function getCompanyName(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->companyName));
        return $this->companyName;
    }

    /**
     * Sets a new companyName<br>
     * <xs:element ref="CompanyName"/><br>
     * <xs:element name="CompanyName" type="SAFPTtextTypeMandatoryMax100Car"/>
     *
     * @param string $companyName
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = static::valTextMandMaxCar($companyName, 100,
                                                       __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->companyName));
    }

    /**
     * Gets as contact<br>
     * <xs:element ref="Contact" minOccurs="0"/><br>
     * <xs:element name="Contact" type="SAFPTtextTypeMandatoryMax50Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getContact(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->contact === null
                        ? "null"
                        : $this->contact));
        return $this->contact;
    }

    /**
     * Sets a new contact<br>
     * <xs:element ref="Contact" minOccurs="0"/><br>
     * <xs:element name="Contact" type="SAFPTtextTypeMandatoryMax50Car"/>
     *
     * @param string|null $contact
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setContact(?string $contact): void
    {

        if ($contact === null)
        {
            $this->contact = null;
        }
        else
        {
            $this->contact = static::valTextMandMaxCar($contact, 50, __METHOD__);
            \Logger::getLogger(\get_class($this))
                ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                                 $this->contact === null
                            ? "null"
                            : $this->contact));
        }
    }

    /**
     * Gets as billingAddress<br>
     * <xs:element ref="BillingAddress"/><br>
     * <xs:element name="BillingAddress" type="AddressStructure"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\Address
     * @since 1.0.0
     */
    public function getBillingAddress(): Address
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->billingAddress;
    }

    /**
     * Sets a new billingAddress<br>
     * <xs:element ref="BillingAddress"/><br>
     * <xs:element name="BillingAddress" type="AddressStructure"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\Address $billingAddress
     * @return void
     * @since 1.0.0
     */
    public function setBillingAddress(Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Adds as shipToAddress<br>
     * <xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/><br>
     * <xs:element name="ShipToAddress" type="AddressStructure"/>
     *
     * @param \Rebelo\SaftPt\AuditFile\Address $shipToAddress
     * @return int The stack index
     * @since 1.0.0
     */
    public function addToShipToAddress(Address $shipToAddress): int
    {
        if (\count($this->shipToAddress) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->shipToAddress);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->shipToAddress[$index] = $shipToAddress;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " ShipToAddress add to index " . \strval($index));
        return $index;
    }

    /**
     * isset shipToAddress
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetShipToAddress(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->shipToAddress[$index]);
    }

    /**
     * unset shipToAddress
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetShipToAddress(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->shipToAddress[$index]);
    }

    /**
     * Gets as shipToAddress
     *
     * @return \Rebelo\SaftPt\AuditFile\Address[]
     * @since 1.0.0
     */
    public function getShipToAddress(): array
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->shipToAddress;
    }

    /**
     * Gets as telephone<br>
     * <xs:element ref="Telephone" minOccurs="0"/><br>
     * <xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTelephone(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->telephone === null
                        ? "null"
                        : $this->telephone));
        return $this->telephone;
    }

    /**
     * Sets a new telephone<br>
     * <xs:element ref="Telephone" minOccurs="0"/><br>
     * <xs:element name="Telephone" type="SAFPTtextTypeMandatoryMax20Car"/>
     * @param string|null $telephone
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setTelephone(?string $telephone): void
    {
        if ($telephone === null)
        {
            $this->telephone = null;
        }
        else
        {
            $this->telephone = static::valTextMandMaxCar($telephone, 20,
                                                         __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->telephone === null
                        ? "null"
                        : $this->telephone));
    }

    /**
     * Gets as fax<br>
     * <xs:element ref="Fax" minOccurs="0"/><br>
     * <xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getFax(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->fax === null
                        ? "null"
                        : $this->fax));
        return $this->fax;
    }

    /**
     * Sets a new fax<br>
     * <xs:element ref="Fax" minOccurs="0"/><br>
     * <xs:element name="Fax" type="SAFPTtextTypeMandatoryMax20Car"/>
     * @param string|null $fax
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setFax(?string $fax): void
    {
        if ($fax === null)
        {
            $this->fax = null;
        }
        else
        {
            $this->fax = static::valTextMandMaxCar($fax, 20, __METHOD__);
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->fax === null
                        ? "null"
                        : $this->fax));
    }

    /**
     * Gets as email<br>
     * <xs:element ref="Email" minOccurs="0"/><br>
     * <xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getEmail(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->email === null
                        ? "null"
                        : $this->email));
        return $this->email;
    }

    /**
     * Sets a new email<br>
     * <xs:element ref="Email" minOccurs="0"/><br>
     * <xs:element name="Email" type="SAFPTtextTypeMandatoryMax254Car"/>
     *
     * @param string|null $email
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setEmail(?string $email): void
    {
        if ($email === null)
        {
            $this->email = $email;
        }
        else
        {
            if (\filter_var($email, FILTER_VALIDATE_EMAIL) === false ||
                \strlen($email) > 254)
            {
                $msg = $email . " is not a valide email";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new AuditFileException($msg);
            }
            else
            {
                $this->email = $email;
            }
        }
        $this->email = $email;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->email === null
                        ? "null"
                        : $this->email));
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
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->website === null
                        ? "null"
                        : $this->website));
        return $this->website;
    }

    /**
     * Sets a new website<br>
     * <xs:element ref="Website" minOccurs="0"/><br>
     * <xs:element name="Website" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @param string|null $website
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setWebsite(?string $website): void
    {
        if ($website === null)
        {
            $this->website = $website;
        }
        else
        {
            if (\filter_var($website, FILTER_VALIDATE_URL) === false ||
                \strlen($website) > 60)
            {
                $msg = "The URL is not valide";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->website = $website;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->website === null
                        ? "null"
                        : $this->website));
    }

    /**
     * Gets as selfBillingIndicator
     * <pre>
     * <xs:element ref="SelfBillingIndicator"/>
     * <!-- Indicador de Autofaturacao -->
     *   <xs:element name="SelfBillingIndicator">
     *       <xs:simpleType>
     *           <xs:restriction base="xs:integer">
     *               <xs:minInclusive value="0"/>
     *               <xs:maxInclusive value="1"/>
     *           </xs:restriction>
     *       </xs:simpleType>
     *   </xs:element>
     * </pre>
     * @return bool
     * @since 1.0.0
     */
    public function getSelfBillingIndicator(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->website
                        ? "true"
                        : "false"));
        return $this->selfBillingIndicator;
    }

    /**
     * Sets a new selfBillingIndicator
     * <pre>
     * <xs:element ref="SelfBillingIndicator"/>
     * <!-- Indicador de Autofaturacao -->
     *   <xs:element name="SelfBillingIndicator">
     *       <xs:simpleType>
     *           <xs:restriction base="xs:integer">
     *               <xs:minInclusive value="0"/>
     *               <xs:maxInclusive value="1"/>
     *           </xs:restriction>
     *       </xs:simpleType>
     *   </xs:element>
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
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->website
                        ? "true"
                        : "false"));
    }

    /**
     * Create the xml node for Customer
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        $customerNode = $node->addChild(static::N_CUSTOMER);
        $customerNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        $customerNode->addChild(static::N_ACCOUNTID, $this->getAccountID());
        $customerNode->addChild(static::N_CUSTOMERTAXID,
                                \strval($this->getCustomerTaxID()));
        $customerNode->addChild(static::N_COMPANYNAME, $this->getCompanyName());
        if ($this->getContact() !== null)
        {
            $customerNode->addChild(static::N_CONTACT, $this->getContact());
        }
        $billAddr = $customerNode->addChild(static::N_BILLINGADDRESS);
        $this->getBillingAddress()->createXmlNode($billAddr);

        foreach ($this->getShipToAddress() as $shipAddr)
        {
            /* @var $shipAddr Address */
            $shipAddr->createXmlNode($customerNode->addChild(static::N_SHIPTOADDRESS));
        }

        if ($this->getTelephone() !== null)
        {
            $customerNode->addChild(static::N_TELEPHONE, $this->getTelephone());
        }

        if ($this->getFax() !== null)
        {
            $customerNode->addChild(static::N_FAX, $this->getFax());
        }

        if ($this->getEmail() !== null)
        {
            $customerNode->addChild(static::N_EMAIL, $this->getEmail());
        }
        if ($this->getWebsite() !== null)
        {
            $customerNode->addChild(static::N_WEBSITE, $this->getWebsite());
        }
        $customerNode->addChild(static::N_SELFBILLINGINDICATOR,
                                $this->getSelfBillingIndicator()
                ? "1"
                : "0");
        return $customerNode;
    }

    /**
     * Pasrse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($node->getName() !== static::N_CUSTOMER)
        {
            $msg = sprinf("Node name should be '%s' but is '%s",
                          static::N_CUSTOMER, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->setCustomerID((string) $node->{static::N_CUSTOMERID});
        $this->setAccountID((string) $node->{static::N_ACCOUNTID});
        $this->setCustomerTaxID((string) $node->{static::N_CUSTOMERTAXID});
        $this->setCompanyName((string) $node->{static::N_COMPANYNAME});
        if ($node->{static::N_CONTACT}->count() > 0)
        {
            $this->setContact((string) $node->{static::N_CONTACT});
        }
        else
        {
            $this->setContact(null);
        }
        $billAddr = new Address();
        $billAddr->parseXmlNode($node->{static::N_BILLINGADDRESS});
        $this->setBillingAddress($billAddr);

        $count = $node->{static::N_SHIPTOADDRESS}->count();
        for ($i = 0; $i <= $count - 1; $i++)
        {
            $shToAddr = new Address();
            $shToAddr->parseXmlNode($node->{static::N_SHIPTOADDRESS}[$i]);
            $this->addToShipToAddress($shToAddr);
        }

        $this->setSelfBillingIndicator(((int) $node->{static::N_SELFBILLINGINDICATOR}) === 1
                ? true
                : false );
        if ($node->{static::N_TELEPHONE}->count() > 0)
        {
            $this->setTelephone((string) $node->{static::N_TELEPHONE});
        }
        else
        {
            $this->setTelephone(null);
        }
        if ($node->{static::N_FAX}->count() > 0)
        {
            $this->setFax((string) $node->{static::N_FAX});
        }
        else
        {
            $this->setFax(null);
        }
        if ($node->{static::N_EMAIL}->count() > 0)
        {
            $this->setEmail((string) $node->{static::N_EMAIL});
        }
        else
        {
            $this->setEmail(null);
        }
        if ($node->{static::N_WEBSITE}->count() > 0)
        {
            $this->setWebsite((string) $node->{static::N_WEBSITE});
        }
        else
        {
            $this->setWebsite(null);
        }
    }

}
