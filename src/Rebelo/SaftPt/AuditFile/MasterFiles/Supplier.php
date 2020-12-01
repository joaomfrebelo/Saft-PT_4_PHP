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
use Rebelo\SaftPt\AuditFile\SupplierAddress;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * 2.3. – Supplier<br>
 * This table shall contain all the records operated during the tax
 * period in the relevant database.
 * <pre>
 *     &lt;xs:element name="Supplier"&gt;
 *       &lt;xs:complexType&gt;
 *           &lt;xs:sequence&gt;
 *               &lt;xs:element ref="SupplierID"/&gt;
 *               &lt;xs:element ref="AccountID"/&gt;
 *               &lt;xs:element ref="SupplierTaxID"/&gt;
 *               &lt;xs:element ref="CompanyName"/&gt;
 *               &lt;xs:element ref="Contact" minOccurs="0"/&gt;
 *              &lt;xs:element name="BillingAddress" type="SupplierAddressStructure"/&gt;
 *              &lt;xs:element name="ShipFromAddress" type="SupplierAddressStructure" minOccurs="0"
 *                  maxOccurs="unbounded"/&gt;
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
 * Class Supplier
 * @since 1.0.0
 */
class Supplier extends ACustomerSupplier
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SUPPLIER = "Supplier";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SUPPLIERID = "SupplierID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SUPPLIERTAXID = "SupplierTaxID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SHIPTOADDRESS = "ShipFromAddress";

    /**
     * &lt;xs:element ref="SupplierID"/&gt;
     * @var string $supplierID
     * @since 1.0.0
     */
    private string $supplierID;

    /**
     * &lt;xs:element ref="SupplierTaxID"/&gt;
     * @var string $supplierTaxID
     * @since 1.0.0
     */
    private string $supplierTaxID;

    /**
     * &lt;xs:element ref="BillingAddress"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SupplierAddress $billingAddress
     * @since 1.0.0
     */
    private SupplierAddress $billingAddress;

    /**
     * &lt;xs:element ref="ShipFromAddress" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SupplierAddress[] $shipFromAddress
     * @since 1.0.0
     */
    private array $shipFromAddress = array();

    /**
     * 2.3. – Supplier<br>
     * This table shall contain all the records operated during the tax
     * period in the relevant database.
     * <pre>
     *     &lt;xs:element name="Supplier"&gt;
     *       &lt;xs:complexType&gt;
     *           &lt;xs:sequence&gt;
     *               &lt;xs:element ref="SupplierID"/&gt;
     *               &lt;xs:element ref="AccountID"/&gt;
     *               &lt;xs:element ref="SupplierTaxID"/&gt;
     *               &lt;xs:element ref="CompanyName"/&gt;
     *               &lt;xs:element ref="Contact" minOccurs="0"/&gt;
     *               &lt;xs:element ref="BillingAddress"/&gt;
     *               &lt;xs:element ref="ShipFromAddress" minOccurs="0" maxOccurs="unbounded"/&gt;
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
     * Gets as supplierID<br>
     * In the list of suppliers cannot exist more than one record
     * with the same SupplierID.<br>
     * &lt;xs:element ref="SupplierID"/&gt;<br>
     * &lt;xs:element name="SupplierID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSupplierID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->supplierID));
        return $this->supplierID;
    }

    /**
     * Get if is set SupplierID
     * @return bool
     * @since 1.0.0
     */
    public function issetSupplierID(): bool
    {
        return isset($this->supplierID);
    }

    /**
     * Sets a new supplierID<br>
     * In the list of suppliers cannot exist more than one record
     * with the same SupplierID.<br>
     * &lt;xs:element ref="SupplierID"/&gt;<br>
     * &lt;xs:element name="SupplierID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @param string $supplierID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSupplierID(string $supplierID): bool
    {
        try {
            $this->supplierID = static::valTextMandMaxCar(
                $supplierID, 30,
                __METHOD__
            );
            $return           = true;
        } catch (AuditFileException $e) {
            $this->supplierID = $supplierID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SupplierID_not_valid");
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->supplierID));

        return $return;
    }

    /**
     * Gets as supplierTaxID<br>
     * It must be indicated without the prefix of the country.<br>
     * &lt;xs:element ref="SupplierTaxID"/&gt;<br>
     * &lt;xs:element name="SupplierTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSupplierTaxID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->supplierTaxID));
        return $this->supplierTaxID;
    }

    /**
     * Get if is set SupplierTaxID
     * @return bool
     * @since 1.0.0
     */
    public function issetSupplierTaxID(): bool
    {
        return isset($this->supplierTaxID);
    }

    /**
     * Sets a new supplierTaxID<br>
     * It must be indicated without the prefix of the country.<br>
     * &lt;xs:element name="SupplierTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     *
     * @param string $supplierTaxID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSupplierTaxID(string $supplierTaxID): bool
    {
        $msg    = null;
        $length = \strlen($supplierTaxID);
        if ($length < 1 || $length > 30) {
            $msg    = sprintf(
                "SupplierTaxID length must be between 1 and 30 but have '%s'",
                $length
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("SupplierTaxID_not_valid");
        } else {
            $return = true;
        }
        $this->supplierTaxID = $supplierTaxID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->supplierTaxID));
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
     * @return \Rebelo\SaftPt\AuditFile\SupplierAddress
     * @throws \Error
     * @since 1.0.0
     */
    public function getBillingAddress(): SupplierAddress
    {
        if (isset($this->billingAddress) === false) {
            $this->billingAddress = new SupplierAddress($this->getErrorRegistor());
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
     * Adds ShipFromAddress<br>
     * This method every time that is invoked will return a new Instance
     * of 'Address' that shal must be populated with the correct values.<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.<br>
     * &lt;xs:element ref="ShipFromAddress" minOccurs="0" maxOccurs="unbounded"/&gt;<br>
     * &lt;xs:element name="ShipFromAddress" type="SupplierAddressStructure"/&gt;
     *
     * @return \Rebelo\SaftPt\AuditFile\SupplierAddress The new SupplierAddress instance that was add and must be populated
     * @since 1.0.0
     */
    public function addShipFromAddress(): SupplierAddress
    {
        $shipFromAddress         = new SupplierAddress($this->getErrorRegistor());
        $this->shipFromAddress[] = $shipFromAddress;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__."ShipFromAddress add to stack "
        );
        return $shipFromAddress;
    }

    /**
     * Gets as shipFromAddress
     *
     * @return \Rebelo\SaftPt\AuditFile\SupplierAddress[]
     * @since 1.0.0
     */
    public function getShipFromAddress(): array
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->shipFromAddress;
    }

    /**
     * Create the xml node for Supplier
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
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

        $supplierNode = $node->addChild(static::N_SUPPLIER);

        if (isset($this->supplierID)) {
            $supplierNode->addChild(static::N_SUPPLIERID, $this->getSupplierID());
        } else {
            $supplierNode->addChild(static::N_SUPPLIERID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SupplierID_not_valid");
        }


        if (isset($this->accountID)) {
            $supplierNode->addChild(static::N_ACCOUNTID, $this->getAccountID());
        } else {
            $supplierNode->addChild(static::N_ACCOUNTID);
            $this->getErrorRegistor()->addOnCreateXmlNode("AccountID_not_valid");
        }

        if (isset($this->supplierTaxID)) {
            $supplierNode->addChild(
                static::N_SUPPLIERTAXID, \strval($this->getSupplierTaxID())
            );
        } else {
            $supplierNode->addChild(static::N_SUPPLIERTAXID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SupplierTaxID_not_valid");
        }

        if (isset($this->companyName)) {
            $supplierNode->addChild(
                static::N_COMPANYNAME, $this->getCompanyName()
            );
        } else {
            $supplierNode->addChild(static::N_COMPANYNAME);
            $this->getErrorRegistor()->addOnCreateXmlNode("CompanyName_not_valid");
        }


        if ($this->getContact() !== null) {
            $supplierNode->addChild(static::N_CONTACT, $this->getContact());
        }

        $billAddr = $supplierNode->addChild(static::N_BILLINGADDRESS);
        if (isset($this->billingAddress)) {
            $this->getBillingAddress()->createXmlNode($billAddr);
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("BillingAddress_not_valid");
        }

        foreach ($this->getShipFromAddress() as $shipAddr) {
            /* @var $shipAddr Address */
            $shipAddr->createXmlNode($supplierNode->addChild(static::N_SHIPTOADDRESS));
        }

        if ($this->getTelephone() !== null) {
            $supplierNode->addChild(static::N_TELEPHONE, $this->getTelephone());
        }

        if ($this->getFax() !== null) {
            $supplierNode->addChild(static::N_FAX, $this->getFax());
        }

        if ($this->getEmail() !== null) {
            $supplierNode->addChild(static::N_EMAIL, $this->getEmail());
        }

        if ($this->getWebsite() !== null) {
            $supplierNode->addChild(static::N_WEBSITE, $this->getWebsite());
        }

        if (isset($this->selfBillingIndicator)) {
            $supplierNode->addChild(
                static::N_SELFBILLINGINDICATOR,
                $this->getSelfBillingIndicator() ? "1" : "0"
            );
        } else {
            $supplierNode->addChild(static::N_SELFBILLINGINDICATOR);
            $this->getErrorRegistor()->addOnCreateXmlNode("SelfBillingIndicator_not_valid");
        }
        return $supplierNode;
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

        if ($node->getName() !== static::N_SUPPLIER) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SUPPLIER, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setSupplierID((string) $node->{static::N_SUPPLIERID});
        $this->setAccountID((string) $node->{static::N_ACCOUNTID});
        $this->setSupplierTaxID((string) $node->{static::N_SUPPLIERTAXID});
        $this->setCompanyName((string) $node->{static::N_COMPANYNAME});
        if ($node->{static::N_CONTACT}->count() > 0) {
            $this->setContact((string) $node->{static::N_CONTACT});
        } else {
            $this->setContact(null);
        }

        $this->getBillingAddress()->parseXmlNode($node->{static::N_BILLINGADDRESS});

        $count = $node->{static::N_SHIPTOADDRESS}->count();
        for ($i = 0; $i <= $count - 1; $i++) {
            $this->addShipFromAddress()
                ->parseXmlNode($node->{static::N_SHIPTOADDRESS}[$i]);
        }

        $this->setSelfBillingIndicator(
            ((int) $node->{static::N_SELFBILLINGINDICATOR}) === 1 ? true : false
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