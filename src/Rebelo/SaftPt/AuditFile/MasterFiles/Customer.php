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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * 2.2. - Customer<br>
 * This table shall contain all the existing records operated during the
 * taxation period in the relevant customers’ file, as well as those which
 * may be implicit in the operations and do not exist in the relevant file.
 * If, for instance, there is a sale for cash showing only the customer’s
 * taxpayer registration number or his name, and not included in the customers
 * file of the application, this client’s data shall be exported
 * as client in the SAF-T (PT).
 * <pre>
 *     &lt;xs:element name="Customer"&gt;
 *       &lt;xs:complexType&gt;
 *           &lt;xs:sequence&gt;
 *               &lt;xs:element ref="CustomerID"/&gt;
 *               &lt;xs:element ref="AccountID"/&gt;
 *               &lt;xs:element ref="CustomerTaxID"/&gt;
 *               &lt;xs:element ref="CompanyName"/&gt;
 *               &lt;xs:element ref="Contact" minOccurs="0"/&gt;
 *               &lt;xs:element ref="BillingAddress"/&gt;
 *               &lt;xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/&gt;
 *               &lt;xs:element ref="Telephone" minOccurs="0"/&gt;
 *               &lt;xs:element ref="Fax" minOccurs="0"/&gt;
 *               &lt;xs:element ref="Email" minOccurs="0"/&gt;
 *               &lt;xs:element ref="Website" minOccurs="0"/&gt;
 *               &lt;xs:element ref="SelfBillingIndicator"/&gt;
 *           &lt;/xs:sequence&gt;
 *       &lt;/xs:complexType&gt;
 *   &lt;/xs:element&gt;
 * </pre>
 *
 * Class Customer
 * @since 1.0.0
 */
class Customer extends ACustomerSupplier
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
    const N_CUSTOMERTAXID = "CustomerTaxID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SHIPTOADDRESS = "ShipToAddress";

    /**
     * &lt;xs:element ref="CustomerID"/&gt;
     * @var string $customerID
     * @since 1.0.0
     */
    private string $customerID;

    /**
     * &lt;xs:element ref="CustomerTaxID"/&gt;
     * @var string $customerTaxID
     * @since 1.0.0
     */
    private string $customerTaxID;

    /**
     * &lt;xs:element ref="BillingAddress"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\Address $billingAddress
     * @since 1.0.0
     */
    private Address $billingAddress;

    /**
     * &lt;xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\Address[] $shipToAddress
     * @since 1.0.0
     */
    private array $shipToAddress = array();

    /**
     * Customer<br>
     * This table shall contain all the existing records operated during the
     * taxation period in the relevant customers’ file, as well as those
     * which may be implicit in the operations and do not exist in the relevant file.
     * If, for instance, there is a sale for cash showing only the customer’s
     * taxpayer registration number or his name, and not included in the customers
     * file of the application, this client’s data shall be exported as client
     * in the SAF-T (PT).
     * <pre>
     *     &lt;xs:element name="Customer"&gt;
     *       &lt;xs:complexType&gt;
     *           &lt;xs:sequence&gt;
     *               &lt;xs:element ref="CustomerID"/&gt;
     *               &lt;xs:element ref="AccountID"/&gt;
     *               &lt;xs:element ref="CustomerTaxID"/&gt;
     *               &lt;xs:element ref="CompanyName"/&gt;
     *               &lt;xs:element ref="Contact" minOccurs="0"/&gt;
     *               &lt;xs:element ref="BillingAddress"/&gt;
     *               &lt;xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/&gt;
     *               &lt;xs:element ref="Telephone" minOccurs="0"/&gt;
     *               &lt;xs:element ref="Fax" minOccurs="0"/&gt;
     *               &lt;xs:element ref="Email" minOccurs="0"/&gt;
     *               &lt;xs:element ref="Website" minOccurs="0"/&gt;
     *               &lt;xs:element ref="SelfBillingIndicator"/&gt;
     *           &lt;/xs:sequence&gt;
     *       &lt;/xs:complexType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets as customerID<br>
     * In the list of clients cannot exist more than one registration
     * with the same CustomerID. In the case of final consumers,
     * a generic client with the designation of “Consumidor final”
     * (Final Consumer) shall be created.
     * <pre>
     * &lt;xs:element ref="CustomerID"/&gt;
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
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
     * Sets a new customerID<br>
     * In the list of clients cannot exist more than one registration
     * with the same CustomerID. In the case of final consumers,
     * a generic client with the designation of “Consumidor final”
     * (Final Consumer) shall be created.
     * <pre>
     * &lt;xs:element ref="CustomerID"/&gt;
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @param string $customerID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): bool
    {
        try {
            $this->customerID = static::valTextMandMaxCar(
                $customerID, 30,
                __METHOD__
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
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->customerID));
        return $return;
    }

    /**
     * Gets as customerTaxID<br>
     * It must be indicated without the country’s prefix.
     * The generic client, corresponding to the
     * aforementioned “Consumidor final” (Final consumer)
     * shall be identified with the Tax Identification Number “999999990”.<br>
     * &lt;xs:element ref="CustomerTaxID"/&gt;<br>
     * &lt;xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCustomerTaxID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->customerTaxID));
        return $this->customerTaxID;
    }

    /**
     * Get if is set CustomerTaxID
     * @return bool
     * @since 1.0.0
     */
    public function issetCustomerTaxID(): bool
    {
        return isset($this->customerTaxID);
    }

    /**
     * Sets a new customerTaxID<br>
     * It must be indicated without the country’s prefix.
     * The generic client, corresponding to the
     * aforementioned “Consumidor final” (Final consumer)
     * shall be identified with the Tax Identification Number “999999990”.<br>
     * &lt;xs:element ref="CustomerTaxID"/&gt;<br>
     * &lt;xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @param string $customerTaxID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCustomerTaxID(string $customerTaxID): bool
    {
        $msg    = null;
        $length = \strlen($customerTaxID);
        if ($length < 1 || $length > 30) {
            $msg    = sprintf(
                "CustomerTaxID length must be between 1 and 30 but have '%s'",
                $length
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("CustomerTaxID_not_valid");
        } else {
            $return = true;
        }
        $this->customerTaxID = $customerTaxID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->customerTaxID));
        return $return;
    }

    /**
     * Gets as billingAddress<br>
     * Head office address or the fixed /permanent establishment address,
     * located on Portuguese territory.<br>
     * The Address instance will be create once you get this method<br>
     * &lt;xs:element ref="BillingAddress"/&gt;<br>
     * &lt;xs:element name="BillingAddress" type="AddressStructure"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\Address
     * @since 1.0.0
     */
    public function getBillingAddress(): Address
    {
        if (isset($this->billingAddress) === false) {
            $this->billingAddress = new Address($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->billingAddress;
    }

    /**
     * Get if is set BillingAddress
     * @return bool
     * @since 1.0.0
     */
    public function issetBillingAddress(): bool
    {
        return isset($this->billingAddress);
    }

    /**
     * Adds as shipToAddress<br>
     * This method every time that is invoked will return a new Instance
     * of 'Address' that shal must be populated with the correct values.<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.<br>
     * &lt;xs:element ref="ShipToAddress" minOccurs="0" maxOccurs="unbounded"/&gt;<br>
     * &lt;xs:element name="ShipToAddress" type="AddressStructure"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\Address The new Address instance that was add and must be populated
     * @since 1.0.0
     */
    public function addShipToAddress(): Address
    {
        $shipToAddress         = new Address($this->getErrorRegistor());
        $this->shipToAddress[] = $shipToAddress;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." ShipToAddress add to stack "
        );
        return $shipToAddress;
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
     * Create the xml node for Customer
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== MasterFiles::N_MASTERFILES) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                MasterFiles::N_MASTERFILES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $customerNode = $node->addChild(static::N_CUSTOMER);

        if (isset($this->customerID)) {
            $customerNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        } else {
            $customerNode->addChild(static::N_CUSTOMERID);
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerID_not_valid");
        }

        if (isset($this->accountID)) {
            $customerNode->addChild(static::N_ACCOUNTID, $this->getAccountID());
        } else {
            $customerNode->addChild(static::N_ACCOUNTID);
            $this->getErrorRegistor()->addOnCreateXmlNode("AccountID_not_valid");
        }

        if (isset($this->customerTaxID)) {
            $customerNode->addChild(
                static::N_CUSTOMERTAXID, \strval($this->getCustomerTaxID())
            );
        } else {
            $customerNode->addChild(static::N_CUSTOMERTAXID);
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerTaxID_not_valid");
        }

        if (isset($this->companyName)) {
            $customerNode->addChild(
                static::N_COMPANYNAME, $this->getCompanyName()
            );
        } else {
            $customerNode->addChild(static::N_COMPANYNAME);
            $this->getErrorRegistor()->addOnCreateXmlNode("CompanyName_not_valid");
        }

        if ($this->getContact() !== null) {
            $customerNode->addChild(static::N_CONTACT, $this->getContact());
        }

        $billAddr = $customerNode->addChild(static::N_BILLINGADDRESS);
        if (isset($this->billingAddress)) {
            $this->getBillingAddress()->createXmlNode($billAddr);
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("BillingAddress_not_valid");
        }

        foreach ($this->getShipToAddress() as $shipAddr) {
            /* @var $shipAddr Address */
            $shipAddr->createXmlNode($customerNode->addChild(static::N_SHIPTOADDRESS));
        }

        if ($this->getTelephone() !== null) {
            $customerNode->addChild(static::N_TELEPHONE, $this->getTelephone());
        }

        if ($this->getFax() !== null) {
            $customerNode->addChild(static::N_FAX, $this->getFax());
        }

        if ($this->getEmail() !== null) {
            $customerNode->addChild(static::N_EMAIL, $this->getEmail());
        }

        if ($this->getWebsite() !== null) {
            $customerNode->addChild(static::N_WEBSITE, $this->getWebsite());
        }

        if (isset($this->selfBillingIndicator)) {
            $customerNode->addChild(
                static::N_SELFBILLINGINDICATOR,
                $this->getSelfBillingIndicator() ? "1" : "0"
            );
        } else {
            $customerNode->addChild(static::N_SELFBILLINGINDICATOR);
            $this->getErrorRegistor()->addOnCreateXmlNode("SelfBillingIndicator_not_valid");
        }

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
        if ($node->getName() !== static::N_CUSTOMER) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_CUSTOMER, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->setCustomerID((string) $node->{static::N_CUSTOMERID});
        $this->setAccountID((string) $node->{static::N_ACCOUNTID});
        $this->setCustomerTaxID((string) $node->{static::N_CUSTOMERTAXID});
        $this->setCompanyName((string) $node->{static::N_COMPANYNAME});
        if ($node->{static::N_CONTACT}->count() > 0) {
            $this->setContact((string) $node->{static::N_CONTACT});
        } else {
            $this->setContact(null);
        }

        $this->getBillingAddress()->parseXmlNode($node->{static::N_BILLINGADDRESS});

        $count = $node->{static::N_SHIPTOADDRESS}->count();
        for ($i = 0; $i <= $count - 1; $i++) {
            $this->addShipToAddress()->parseXmlNode(
                $node->{static::N_SHIPTOADDRESS}[$i]
            );
        }

        $this->setSelfBillingIndicator(
            ((int) $node->{static::N_SELFBILLINGINDICATOR})
            === 1 ? true : false 
        );
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