<?php

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

/**
 * Class representing MasterFile
 */
class MasterFiles
{

    /**
     * @var \Rebelo\AuditFile\GeneralLedgerAccounts $generalLedgerAccounts
     */
    private $generalLedgerAccounts = null;

    /**
     * @var \Rebelo\AuditFile\Customer[] $customer
     */
    private $customer = array();

    /**
     * @var \Rebelo\AuditFile\Supplier[] $supplier
     */
    private $supplier = array();

    /**
     * @var \Rebelo\AuditFile\Product[] $product
     */
    private $product = array();

    /**
     * @var \Rebelo\AuditFile\TaxTableEntry[] $taxTable
     */
    private $taxTable = null;

    /**
     * Gets as generalLedgerAccounts
     *
     * @return \Rebelo\AuditFile\GeneralLedgerAccounts
     */
    public function getGeneralLedgerAccounts()
    {

        return $this->generalLedgerAccounts;
    }

    /**
     * Sets a new generalLedgerAccounts
     *
     * @param \Rebelo\AuditFile\GeneralLedgerAccounts $generalLedgerAccounts
     * @return self
     */
    public function setGeneralLedgerAccounts(\Rebelo\AuditFile\GeneralLedgerAccounts $generalLedgerAccounts)
    {
        $this->generalLedgerAccounts = $generalLedgerAccounts;
        return $this;
    }

    /**
     * Adds as customer
     *
     * @return self
     * @param \Rebelo\AuditFile\Customer $customer
     */
    public function addToCustomer(\Rebelo\AuditFile\Customer $customer)
    {
        $this->customer[] = $customer;
        return $this;
    }

    /**
     * isset customer
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCustomer($index)
    {
        return isset($this->customer[$index]);
    }

    /**
     * unset customer
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCustomer($index)
    {
        unset($this->customer[$index]);
    }

    /**
     * Gets as customer
     *
     * @return \Rebelo\AuditFile\Customer[]
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets a new customer
     *
     * @param \Rebelo\AuditFile\Customer[] $customer
     * @return self
     */
    public function setCustomer(array $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Adds as supplier
     *
     * @return self
     * @param \Rebelo\AuditFile\Supplier $supplier
     */
    public function addToSupplier(\Rebelo\AuditFile\Supplier $supplier)
    {
        $this->supplier[] = $supplier;
        return $this;
    }

    /**
     * isset supplier
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSupplier($index)
    {
        return isset($this->supplier[$index]);
    }

    /**
     * unset supplier
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSupplier($index)
    {
        unset($this->supplier[$index]);
    }

    /**
     * Gets as supplier
     *
     * @return \Rebelo\AuditFile\Supplier[]
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Sets a new supplier
     *
     * @param \Rebelo\AuditFile\Supplier[] $supplier
     * @return self
     */
    public function setSupplier(array $supplier)
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * Adds as product
     *
     * @return self
     * @param \Rebelo\AuditFile\Product $product
     */
    public function addToProduct(\Rebelo\AuditFile\Product $product)
    {
        $this->product[] = $product;
        return $this;
    }

    /**
     * isset product
     *
     * @param int|string $index
     * @return bool
     */
    public function issetProduct($index)
    {
        return isset($this->product[$index]);
    }

    /**
     * unset product
     *
     * @param int|string $index
     * @return void
     */
    public function unsetProduct($index)
    {
        unset($this->product[$index]);
    }

    /**
     * Gets as product
     *
     * @return \Rebelo\AuditFile\Product[]
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets a new product
     *
     * @param \Rebelo\AuditFile\Product[] $product
     * @return self
     */
    public function setProduct(array $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Adds as taxTableEntry
     *
     * @return self
     * @param \Rebelo\AuditFile\TaxTableEntry $taxTableEntry
     */
    public function addToTaxTable(\Rebelo\AuditFile\TaxTableEntry $taxTableEntry)
    {
        $this->taxTable[] = $taxTableEntry;
        return $this;
    }

    /**
     * isset taxTable
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTaxTable($index)
    {
        return isset($this->taxTable[$index]);
    }

    /**
     * unset taxTable
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTaxTable($index)
    {
        unset($this->taxTable[$index]);
    }

    /**
     * Gets as taxTable
     *
     * @return \Rebelo\AuditFile\TaxTableEntry[]
     */
    public function getTaxTable()
    {
        return $this->taxTable;
    }

    /**
     * Sets a new taxTable
     *
     * @param \Rebelo\AuditFile\TaxTableEntry[] $taxTable
     * @return self
     */
    public function setTaxTable(array $taxTable)
    {
        $this->taxTable = $taxTable;
        return $this;
    }

}
