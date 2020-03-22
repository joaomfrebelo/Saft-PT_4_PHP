<?php

namespace Rebelo\SaftPt;

/**
 * Class representing Supplier
 */
class Supplier
{

    /**
     * @var string $supplierID
     */
    private $supplierID = null;

    /**
     * @var string $accountID
     */
    private $accountID = null;

    /**
     * @var string $supplierTaxID
     */
    private $supplierTaxID = null;

    /**
     * @var string $companyName
     */
    private $companyName = null;

    /**
     * @var string $contact
     */
    private $contact = null;

    /**
     * @var \Rebelo\SaftPt\SupplierAddressStructureType $billingAddress
     */
    private $billingAddress = null;

    /**
     * @var \Rebelo\SaftPt\SupplierAddressStructureType[] $shipFromAddress
     */
    private $shipFromAddress = [
        
    ];

    /**
     * @var string $telephone
     */
    private $telephone = null;

    /**
     * @var string $fax
     */
    private $fax = null;

    /**
     * @var string $email
     */
    private $email = null;

    /**
     * @var string $website
     */
    private $website = null;

    /**
     * @var int $selfBillingIndicator
     */
    private $selfBillingIndicator = null;

    /**
     * Gets as supplierID
     *
     * @return string
     */
    public function getSupplierID()
    {
        return $this->supplierID;
    }

    /**
     * Sets a new supplierID
     *
     * @param string $supplierID
     * @return self
     */
    public function setSupplierID($supplierID)
    {
        $this->supplierID = $supplierID;
        return $this;
    }

    /**
     * Gets as accountID
     *
     * @return string
     */
    public function getAccountID()
    {
        return $this->accountID;
    }

    /**
     * Sets a new accountID
     *
     * @param string $accountID
     * @return self
     */
    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;
        return $this;
    }

    /**
     * Gets as supplierTaxID
     *
     * @return string
     */
    public function getSupplierTaxID()
    {
        return $this->supplierTaxID;
    }

    /**
     * Sets a new supplierTaxID
     *
     * @param string $supplierTaxID
     * @return self
     */
    public function setSupplierTaxID($supplierTaxID)
    {
        $this->supplierTaxID = $supplierTaxID;
        return $this;
    }

    /**
     * Gets as companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Sets a new companyName
     *
     * @param string $companyName
     * @return self
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * Gets as contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Sets a new contact
     *
     * @param string $contact
     * @return self
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * Gets as billingAddress
     *
     * @return \Rebelo\SaftPt\SupplierAddressStructureType
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Sets a new billingAddress
     *
     * @param \Rebelo\SaftPt\SupplierAddressStructureType $billingAddress
     * @return self
     */
    public function setBillingAddress(\Rebelo\SaftPt\SupplierAddressStructureType $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Adds as shipFromAddress
     *
     * @return self
     * @param \Rebelo\SaftPt\SupplierAddressStructureType $shipFromAddress
     */
    public function addToShipFromAddress(\Rebelo\SaftPt\SupplierAddressStructureType $shipFromAddress)
    {
        $this->shipFromAddress[] = $shipFromAddress;
        return $this;
    }

    /**
     * isset shipFromAddress
     *
     * @param int|string $index
     * @return bool
     */
    public function issetShipFromAddress($index)
    {
        return isset($this->shipFromAddress[$index]);
    }

    /**
     * unset shipFromAddress
     *
     * @param int|string $index
     * @return void
     */
    public function unsetShipFromAddress($index)
    {
        unset($this->shipFromAddress[$index]);
    }

    /**
     * Gets as shipFromAddress
     *
     * @return \Rebelo\SaftPt\SupplierAddressStructureType[]
     */
    public function getShipFromAddress()
    {
        return $this->shipFromAddress;
    }

    /**
     * Sets a new shipFromAddress
     *
     * @param \Rebelo\SaftPt\SupplierAddressStructureType[] $shipFromAddress
     * @return self
     */
    public function setShipFromAddress(array $shipFromAddress)
    {
        $this->shipFromAddress = $shipFromAddress;
        return $this;
    }

    /**
     * Gets as telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Sets a new telephone
     *
     * @param string $telephone
     * @return self
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * Gets as fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets a new fax
     *
     * @param string $fax
     * @return self
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * Gets as email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets a new email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets as website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets a new website
     *
     * @param string $website
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Gets as selfBillingIndicator
     *
     * @return int
     */
    public function getSelfBillingIndicator()
    {
        return $this->selfBillingIndicator;
    }

    /**
     * Sets a new selfBillingIndicator
     *
     * @param int $selfBillingIndicator
     * @return self
     */
    public function setSelfBillingIndicator($selfBillingIndicator)
    {
        $this->selfBillingIndicator = $selfBillingIndicator;
        return $this;
    }


}

