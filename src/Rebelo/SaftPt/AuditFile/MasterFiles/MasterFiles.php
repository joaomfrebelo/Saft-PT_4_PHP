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

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\Customer;
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\MasterFiles\Product;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;

/**
 * MasterFiles
 *
 * <pre>
 * &lt;xs:element name="MasterFiles"&gt;
 *   &lt;xs:complexType&gt;
 *       &lt;xs:sequence&gt;
 *           &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
 *           &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
 * </pre>
 * @author João Rebelo
 */
class MasterFiles
    extends AAuditFile
{

    /**
     * <xs:element name="MasterFiles">
     * @since 1.0.0
     */
    const N_MASTERFILES = "MasterFiles";

    /**
     * <xs:element ref="GeneralLedgerAccounts" minOccurs="0"/>
     * @since 1.0.0
     */
    const N_GENERALLEDGERACCOUNTS = "GeneralLedgerAccounts";

    /**
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_CUSTOMER = "Customer";

    /**
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_SUPPLIER = "Supplier";

    /**
     * <xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_PRODUCT = "Product";

    /**
     * <xs:element ref="TaxTable" minOccurs="0"/>
     * @since 1.0.0
     */
    const N_TAXTABLE = "TaxTable";

    /**
     * <xs:element ref="GeneralLedgerAccounts" minOccurs="0"/>
     * @var array NotImplemented
     * @since 1.0.0
     */
    private array $generalLedgerAccounts = array();

    /**
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Customer[]
     * @since 1.0.0
     */
    private array $customer = array();

    /**
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier[]
     * @since 1.0.0
     */
    private array $supplier = array();

    /**
     * <xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Product[]
     * @since 1.0.0
     */
    private array $product = array();

    /**
     * <pre>
     * &lt:xs:element ref="TaxTable" minOccurs="0"/&gt:
     * &lt:xs:element name="TaxTable"&gt:
     *  &lt:xs:complexType&gt:
     *      &lt:xs:sequence&gt:
     *          &lt:xs:element ref="TaxTableEntry" minOccurs="1" maxOccurs="unbounded"/&gt:
     *      &lt:/xs:sequence&gt:
     *  &lt:/xs:complexType&gt:
     * &lt:/xs:element&gt:
     * </pre>
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry[]
     * @since 1.0.0
     */
    private array $taxTableEntry = array();

    /**
     *
     * <pre>
     * &lt;xs:element name="MasterFiles"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
     *           &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Is not implemented
     * @return array
     * @throws \Rebelo\SaftPt\AuditFile\NotImplemented
     * @since 1.0.0
     */
    public function getGeneralLedgerAccounts(): array
    {
        $msg = "GeneralLedgerAccounts not implemented";
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__ . " '%s'", $msg));
        throw new \Rebelo\SaftPt\AuditFile\NotImplemented($msg);
    }

    /**
     * Get Customer stack
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Customer[]
     * @since 1.0.0
     */
    public function getCustomer(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->customer;
    }

    /**
     * Add Customer stack
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\Customer $customer
     * @return void
     * @since 1.0.0
     */
    public function addCustomer(Customer $customer): void
    {
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");

        $this->customer[] = $customer;
    }

    /**
     * Get all Supplier stack
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier[]
     * @since 1.0.0
     */
    public function getSupplier(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->supplier;
    }

    /**
     * Add Supplier to stack
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier $supplier
     * @return void
     * @since 1.0.0
     */
    public function addSupplier(Supplier $supplier): void
    {
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        $this->supplier = $supplier;
    }

    /**
     * Get all Product stack
     * <xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Product[]
     * @since 1.0.0
     */
    public function getProduct(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->product;
    }

    /**
     * Add Product to stack
     * <xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\Product $product
     * @return void
     * @since 1.0.0
     */
    public function addProduct(Product $product): void
    {
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        $this->product = $product;
    }

    /**
     * Get (TaxTable) all TaxTableEntry stack
     * <pre>
     * &lt:xs:element ref="TaxTable" minOccurs="0"/&gt:
     * &lt:xs:element name="TaxTable"&gt:
     *  &lt:xs:complexType&gt:
     *      &lt:xs:sequence&gt:
     *          &lt:xs:element ref="TaxTableEntry" minOccurs="1" maxOccurs="unbounded"/&gt:
     *      &lt:/xs:sequence&gt:
     *  &lt:/xs:complexType&gt:
     * &lt:/xs:element&gt:
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry[]     *
     * @since 1.0.0
     */
    public function getTaxTableEntry(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__ . " getted");
        return $this->taxTableEntry;
    }

    /**
     * Add a TaxTableEntry  to stack  (TaxTable)
     * <pre>
     * &lt:xs:element ref="TaxTable" minOccurs="0"/&gt:
     * &lt:xs:element name="TaxTable"&gt:
     *  &lt:xs:complexType&gt:
     *      &lt:xs:sequence&gt:
     *          &lt:xs:element ref="TaxTableEntry" minOccurs="1" maxOccurs="unbounded"/&gt:
     *      &lt:/xs:sequence&gt:
     *  &lt:/xs:complexType&gt:
     * &lt:/xs:element&gt:
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry $taxTableEntry
     * @return void
     */
    public function addTaxTableEntry(TaxTableEntry $taxTableEntry): void
    {
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        $this->taxTableEntry = $taxTableEntry;
    }

    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

    }

    public function parseXmlNode(\SimpleXMLElement $node): void
    {

    }

}
