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
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\Customer;
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\MasterFiles\Product;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;
use Rebelo\SaftPt\AuditFile\ExportType;
use Rebelo\SaftPt\AuditFile\AuditFileException;

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
 * @since 1.0.0
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
     * Type of xml saft file export, simplified or complete
     * @var \Rebelo\SaftPt\AuditFile\ExportType $exportType
     * @since 1.0.0
     */
    private ExportType $exportType;

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
     * @param \Rebelo\SaftPt\AuditFile\ExportType|null $exportType
     * @since 1.0.0
     */
    public function __construct(?ExportType $exportType = null)
    {
        parent::__construct();
        $this->setExportType(
            $exportType === null
                ? new ExportType(ExportType::C)
                :
                $exportType
        );
    }

    /**
     * Get the exported type setted
     * @return \Rebelo\SaftPt\AuditFile\ExportType
     * @since 1.0.0
     */
    public function getExportType(): ExportType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->exportType->get()));
        return $this->exportType;
    }

    /**
     * Set the exported type
     * @param \Rebelo\SaftPt\AuditFile\ExportType $exportType
     * @return void
     * @since 1.0.0
     */
    public function setExportType(ExportType $exportType): void
    {
        $this->exportType = $exportType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->exportType->get()));
    }

    /**
     * Is not implemented
     * @return GeneralLedgerAccounts[]
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
        \Logger::getLogger(\get_class($this))->info(__METHOD__ . " getted");
        return $this->customer;
    }

    /**
     * Add Customer to stack
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\Customer $customer
     * @return int
     * @since 1.0.0
     */
    public function addToCustomer(Customer $customer): int
    {
        if (\count($this->customer) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->customer);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->customer[$index] = $customer;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset customer index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetCustomer(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->customer[$index]);
    }

    /**
     * unset customer
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetCustomer(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->customer[$index]);
    }

    /**
     * Get all Supplier stack
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier[]
     * @since 1.0.0
     */
    public function getSupplier(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__ . " getted");
        return $this->supplier;
    }

    /**
     * Add Supplier to stack
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier $supplier
     * @return int
     * @since 1.0.0
     */
    public function addToSupplier(Supplier $supplier): int
    {
        if (\count($this->supplier) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->supplier);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->supplier[$index] = $supplier;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset supplier index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetSupplier(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->supplier[$index]);
    }

    /**
     * unset supplier
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetSupplier(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->supplier[$index]);
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
     * @return int
     * @since 1.0.0
     */
    public function addToProduct(Product $product): int
    {
        if (\count($this->product) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->product);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->product[$index] = $product;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset product index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetProduct(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->product[$index]);
    }

    /**
     * unset shipFromAddress
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetProduct(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->product[$index]);
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
     * @return int
     * @since 1.0.0
     */
    public function addToTaxTableEntry(TaxTableEntry $taxTableEntry): int
    {
        if (\count($this->taxTableEntry) == 0)
        {
            $index = 0;
        }
        else
        {
// The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->taxTableEntry);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->taxTableEntry[$index] = $taxTableEntry;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__ . " add to stack");
        return $index;
    }

    /**
     * isset taxTableEntry index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxTableEntry(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->taxTableEntry[$index]);
    }

    /**
     * unset shipFromAddress
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetTaxTableEntry(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->taxTableEntry[$index]);
    }

    /**
     * Create the MasterFile xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== AuditFile::N_AUDITFILE)
        {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                            AuditFile::N_AUDITFILE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $masterNode = $node->addChild(static::N_MASTERFILES);

        // GeneralLedgerAccounts is not implemented
        //<xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
        if (\count($this->getCustomer()) > 0 &&
            $this->exportType->get() == ExportType::C)
        {
            array_map(function($customer) use ($masterNode)
            {
                /* @var $customer Customer */
                $customer->createXmlNode($masterNode);
            }, $this->getCustomer());
        }

        //<xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
        if (\count($this->getSupplier()) > 0 &&
            $this->exportType->get() == ExportType::C)
        {
            array_map(function($supplier) use ($masterNode)
            {
                /* @var $supplier Supplier */
                $supplier->createXmlNode($masterNode);
            }, $this->getSupplier());
        }
        //<xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
        if (\count($this->getProduct()) > 0 &&
            $this->exportType->get() == ExportType::C)
        {
            array_map(function($product) use ($masterNode)
            {
                /* @var $product Product */
                $product->createXmlNode($masterNode);
            }, $this->getProduct());
        }

        //<xs:element ref="TaxTable" minOccurs="0"/>
        if (\count($this->getTaxTableEntry()) > 0)
        {
            $taxTableNode = $masterNode->addChild(static::N_TAXTABLE);
            array_map(function($taxTableEntry) use ($taxTableNode)
            {
                /* @var $taxTableEntry TaxTableEntry */
                $taxTableEntry->createXmlNode($taxTableNode);
            }, $this->getTaxTableEntry());
        }

        return $masterNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_MASTERFILES)
        {
            $msg = sprintf("Node name should be '%s' but is '%s",
                           static::N_MASTERFILES, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        // GeneralLedgerAccounts is not implemented
        //<xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
        $countCustomer = $node->{Customer::N_CUSTOMER}->count();
        if ($countCustomer > 0)
        {
            for ($n = 0; $n < $countCustomer; $n++)
            {
                $customer = new Customer();
                $customer->parseXmlNode($node->{Customer::N_CUSTOMER}[$n]);
                $this->addToCustomer($customer);
            }
        }

        //<xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
        $countSupplier = $node->{Supplier::N_SUPPLIER}->count();
        if ($countSupplier > 0)
        {
            for ($n = 0; $n < $countSupplier; $n++)
            {
                $supplier = new Supplier();
                $supplier->parseXmlNode($node->{Supplier::N_SUPPLIER}[$n]);
                $this->addToSupplier($supplier);
            }
        }

        //<xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
        $countProduct = $node->{Product::N_PRODUCT}->count();
        if ($countProduct > 0)
        {
            for ($n = 0; $n < $countProduct; $n++)
            {
                $product = new Product();
                $product->parseXmlNode($node->{Product::N_PRODUCT}[$n]);
                $this->addToProduct($product);
            }
        }

        //<xs:element ref="TaxTable" minOccurs="0"/>
        if ($node->{static::N_TAXTABLE}->count() > 0)
        {
            $countTaxTableEntry = $node->{static::N_TAXTABLE}
                ->{TaxTableEntry::N_TAXTABLEENTRY}->count();
            if ($countTaxTableEntry > 0)
            {
                for ($n = 0; $n < $countTaxTableEntry; $n++)
                {
                    $taxTableEntry = new TaxTableEntry();
                    $taxTableEntry->parseXmlNode(
                        $node->{static::N_TAXTABLE}->{TaxTableEntry::N_TAXTABLEENTRY}[$n]
                    );
                    $this->addToTaxTableEntry($taxTableEntry);
                }
            }
        }
    }

}
