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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\MasterFiles\Customer;
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\MasterFiles\Product;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * MasterFiles<br>
 * Master Files 2.1, 2.2, 2.3, 2.4 and 2.5 are required under the
 * conditions stated in f), g), h) and i) of paragraph 1 of
 * Ordinance No. 302/2016 of the 2nd December
 * amended by the Rectification Statement No. 2-A/2017, of the 02nd February.
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
class MasterFiles extends AAuditFile
{
    /**
     * &lt;xs:element name="MasterFiles">
     * @since 1.0.0
     */
    const N_MASTERFILES = "MasterFiles";

    /**
     * &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
     * @since 1.0.0
     */
    const N_GENERALLEDGERACCOUNTS = "GeneralLedgerAccounts";

    /**
     * &lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
     * @since 1.0.0
     */
    const N_TAXTABLE = "TaxTable";

    /**
     * &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
     * @var array NotImplemented
     * @since 1.0.0
     */
    protected array $generalLedgerAccounts = array();

    /**
     * &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Customer[]
     * @since 1.0.0
     */
    protected array $customer = array();

    /**
     * Stack of all customerID
     * @var string[]
     * @since 1.0.0
     */
    protected array $customerID = array();

    /**
     * &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier[]
     * @since 1.0.0
     */
    protected array $supplier = array();

    /**
     * Stack of all supplierID
     * @var string[]
     * @since 1.0.0
     */
    protected array $supplierID = array();

    /**
     * &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\Product[]
     * @since 1.0.0
     */
    protected array $product = array();

    /**
     * Stack of all ProductID
     * @var string[]
     * @since 1.0.0
     */
    protected array $productCode = array();

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
    protected array $taxTableEntry = array();

    /**
     * Stores if the final consumer has add to the customer table or not
     * @var bool
     * @since 1.0.0
     */
    protected bool $isFinalConsumerAdd = false;


    /**
     * Master Files<br>
     * Master Files 2.1, 2.2, 2.3, 2.4 and 2.5 are required under the
     * conditions stated in f), g), h) and i) of paragraph 1 of
     * Ordinance No. 302/2016 of the 2nd December
     * amended by the Rectification Statement No. 2-A/2017, of the 02nd February.
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister|null $errorRegister
     * @since 1.0.0
     */
    public function __construct(?ErrorRegister $errorRegister = null)
    {
        parent::__construct(
            $errorRegister === null ?
                new ErrorRegister() : $errorRegister
        );
    }

    /**
     * Is not implemented
     * @throws \Rebelo\SaftPt\AuditFile\NotImplemented
     * @return void
     * @since 1.0.0
     */
    public function getGeneralLedgerAccounts(): void
    {
        $msg = "GeneralLedgerAccounts not implemented";
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__." '%s'", $msg));
        throw new \Rebelo\SaftPt\AuditFile\NotImplemented($msg);
    }

    /**
     * Get Customer stack
     * &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Customer[]
     * @since 1.0.0
     */
    public function getCustomer(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->customer;
    }

    /**
     * Create a new instance of Customer and add to Customer to stack<br>
     * Every time tha you invoke thos method a new Customer instance will be created
     * and returned to be populated with the values.<br>
     * 2.2. - Customer<br>
     * This table shall contain all the existing records operated during
     * the taxation period in the relevant customers’ file, as well as
     * those which may be implicit in the operations and do not exist in
     * the relevant file. If, for instance, there is a sale for cash
     * showing only the customer’s taxpayer registration number or his name,
     * and not included in the customers file of the application,
     * this client’s data shall be exported as client in the SAF-T (PT).<br>
     * &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Customer
     * @since 1.0.0
     */
    public function addCustomer(): Customer
    {
        $this->customerID = [];
        $customer         = new Customer($this->getErrorRegistor());
        $this->customer[] = $customer;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." add to stack");
        return $customer;
    }

    /**
     * get all customerID defined
     * @return string[]
     * @since 1.0.0
     */
    public function getAllCustomerID(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        if (\count($this->customerID) === 0) {
            foreach ($this->getCustomer() as $customer) {
                /* @var $customer \Rebelo\SaftPt\AuditFile\MasterFiles\Customer */
                if ($customer->issetCustomerID()) {
                    $this->customerID[] = $customer->getCustomerID();
                }
            }
        }
        return $this->customerID;
    }

    /**
     * Get all Supplier stack<br>
     * 2.3. – Supplier<br>
     * This table shall contain all the records operated during the tax
     * period in the relevant database.<br>
     * &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier[]
     * @since 1.0.0
     */
    public function getSupplier(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->supplier;
    }

    /**
     * get all supplierID defined
     * @return string[]
     * @since 1.0.0
     */
    public function getAllSupplierID(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        if (\count($this->supplierID) === 0) {
            foreach ($this->getSupplier() as $supplier) {
                /* @var $supplier \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier */
                if ($supplier->issetSupplierID()) {
                    $this->supplierID[] = $supplier->getSupplierID();
                }
            }
        }
        return $this->supplierID;
    }

    /**
     * Create a Supplier instance and add to Supplier stack<br>
     * 2.3. – Supplier<br>
     * This table shall contain all the records operated during the tax
     * period in the relevant database.<br>
     * &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier
     * @since 1.0.0
     */
    public function addSupplier(): Supplier
    {
        $this->supplierID = [];
        $supplier         = new Supplier($this->getErrorRegistor());
        $this->supplier[] = $supplier;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." add to stack");
        return $supplier;
    }

    /**
     * Get all Product stack<br>
     * Every time that this method is invoked a new Supplier instance is created
     * and add to Supplier stack and than is returned to be populated with values.<br>
     * This table shall present the catalogue of products and types of services
     * used in the invoicing system, which have been operated, and also the records,
     * which are implicit in the operations and do not exist in the table of
     * products/services of the application.
     * If, for instance, there is an invoice with a line of freights that does
     * not exist in the articles’ file of the application, this file shall be
     * exported and represented as a product in the SAF-T (PT).
     * This table shall also show taxes, tax rates, eco taxes, parafiscal charges
     * mentioned in the invoice and contributing or not to the taxable basis
     * for VAT or Stamp Duty - except VAT and Stamp duty, which shall be showed
     * in 2.5. – TaxTable (Table of taxes).<br>
     * &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Product[]
     * @since 1.0.0
     */
    public function getProduct(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->product;
    }

    /**
     * get all productCode defined
     * @return string[]
     * @since 1.0.0
     */
    public function getAllProductCode(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        if (\count($this->productCode) === 0) {
            foreach ($this->getProduct() as $k => $product) {
                /* @var $product \Rebelo\SaftPt\AuditFile\MasterFiles\Product */
                if ($product->issetProductCode()) {
                    $this->productCode[$k] = $product->getProductCode();
                }
            }
        }
        return $this->productCode;
    }

    /**
     * Create a Product instance and add stack<br>
     * Every time that this method is invoked a new Product instance is created
     * and add to Product stack and than is returned to be populated with values.<br>
     * This table shall present the catalogue of products and types of services
     * used in the invoicing system, which have been operated, and also the records,
     * which are implicit in the operations and do not exist in the table of
     * products/services of the application.
     * If, for instance, there is an invoice with a line of freights that does
     * not exist in the articles’ file of the application, this file shall be
     * exported and represented as a product in the SAF-T (PT).
     * This table shall also show taxes, tax rates, eco taxes, parafiscal charges
     * mentioned in the invoice and contributing or not to the taxable basis
     * for VAT or Stamp Duty - except VAT and Stamp duty, which shall be showed
     * in 2.5. – TaxTable (Table of taxes).<br>
     * &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\Product $product
     * @since 1.0.0
     */
    public function addProduct(): Product
    {
        $this->productCode = [];
        $product         = new Product($this->getErrorRegistor());
        $this->product[] = $product;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." add to stack");
        return $product;
    }

    /**
     * Get (TaxTable) all TaxTableEntry stack<br>
     * 2.5. – TaxTable [Table of taxes].<br>
     * This table shows the VAT regimes applied in each fiscal area and the
     * different types of stamp duty to be paid,
     * applicable to the lines of documents recorded in Table 4.SourceDocuments.
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
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry[]
     * @since 1.0.0
     */
    public function getTaxTableEntry(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->taxTableEntry;
    }

    /**
     * Create a new TaxTableEntry instance and add to stack  (TaxTable)<br>
     * Every time that this method is invoked a new TaxTableEntry instance is created
     * and add to TaxTableEntry stack and than is returned to be populated with values.<br>
     * 2.5. – TaxTable [Table of taxes].<br>
     * This table shows the VAT regimes applied in each fiscal area and the
     * different types of stamp duty to be paid,
     * applicable to the lines of documents recorded in Table 4.SourceDocuments.
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
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry $taxTableEntry
     * @since 1.0.0
     */
    public function addTaxTableEntry(): TaxTableEntry
    {
        $taxTableEntry         = new TaxTableEntry($this->getErrorRegistor());
        $this->taxTableEntry[] = $taxTableEntry;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." add to stack");
        return $taxTableEntry;
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

        if ($node->getName() !== AuditFile::N_AUDITFILE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", AuditFile::N_AUDITFILE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $masterNode = $node->addChild(static::N_MASTERFILES);

        // GeneralLedgerAccounts is not implemented
        //&lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
        if (\count($this->getCustomer()) > 0) {
            array_map(
                function($customer) use ($masterNode)
                {
                                /* @var $customer Customer */
                                $customer->createXmlNode($masterNode);
                }, $this->getCustomer()
            );
        }

        //&lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
        if (\count($this->getSupplier()) > 0) {
            array_map(
                function($supplier) use ($masterNode)
                {
                                /* @var $supplier Supplier */
                                $supplier->createXmlNode($masterNode);
                }, $this->getSupplier()
            );
        }

        //&lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
        if (\count($this->getProduct()) > 0) {
            array_map(
                function($product) use ($masterNode)
                {
                                /* @var $product Product */
                                $product->createXmlNode($masterNode);
                }, $this->getProduct()
            );
        }


        //&lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
        if (\count($this->getTaxTableEntry()) > 0) {
            $taxTableNode = $masterNode->addChild(static::N_TAXTABLE);
            array_map(
                function($taxTableEntry) use ($taxTableNode)
                {
                                /* @var $taxTableEntry TaxTableEntry */
                                $taxTableEntry->createXmlNode($taxTableNode);
                }, $this->getTaxTableEntry()
            );
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

        if ($node->getName() !== static::N_MASTERFILES) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s", static::N_MASTERFILES,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        // GeneralLedgerAccounts is not implemented
        //&lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
        $countCustomer = $node->{Customer::N_CUSTOMER}->count();
        if ($countCustomer > 0) {
            for ($n = 0; $n < $countCustomer; $n++) {
                $this->addCustomer()->parseXmlNode($node->{Customer::N_CUSTOMER}[$n]);
            }
        }

        //&lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
        $countSupplier = $node->{Supplier::N_SUPPLIER}->count();
        if ($countSupplier > 0) {
            for ($n = 0; $n < $countSupplier; $n++) {
                $this->addSupplier()->parseXmlNode($node->{Supplier::N_SUPPLIER}[$n]);
            }
        }

        //&lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
        $countProduct = $node->{Product::N_PRODUCT}->count();
        if ($countProduct > 0) {
            for ($n = 0; $n < $countProduct; $n++) {
                $this->addProduct()->parseXmlNode($node->{Product::N_PRODUCT}[$n]);
            }
        }

        //&lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
        if ($node->{static::N_TAXTABLE}->count() > 0) {
            $countTaxTableEntry = $node->{static::N_TAXTABLE}
                ->{TaxTableEntry::N_TAXTABLEENTRY}->count();
            if ($countTaxTableEntry > 0) {
                for ($n = 0; $n < $countTaxTableEntry; $n++) {
                    $this->addTaxTableEntry()->parseXmlNode(
                        $node->{static::N_TAXTABLE}->{TaxTableEntry::N_TAXTABLEENTRY}[$n]
                    );
                }
            }
        }
    }
    
    /**
     * Short hand to add the final consumer to the Consumers table,
     * Only add if you has document issued to the “Final Consumer”, 
     * the CustomerID to be issued in the document’s CustomerID is
     *  \Rebelo\SaftPt\AuditFile\AuditFile::CONSUMIDOR_FINAL_ID
     * @return void
     * @since 1.0.0
     */
    public function addCustomerFinalConsumer() : void
    {
        if($this->isFinalConsumerAdd === true){
            return;
        }
        
        $customer = $this->addCustomer();
        $customer->setCustomerID(AuditFile::CONSUMIDOR_FINAL_ID);
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCustomerTaxID(AuditFile::CONSUMIDOR_FINAL_TAX_ID);
        $customer->setCompanyName(AuditFile::CONSUMIDOR_FINAL);
        $customer->setSelfBillingIndicator(false);
        
        $addr = $customer->getBillingAddress();
        $addr->setAddressDetail(AuditFile::DESCONHECIDO);
        $addr->setCity(AuditFile::DESCONHECIDO);
        $addr->setPostalCode(AuditFile::DESCONHECIDO);
        /** @phpstan-ignore-next-line */
        $addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::DESCONHECIDO());
        
        $this->isFinalConsumerAdd = true;
    }
    
    /**
     * Get if the final consumer is already add to the Customer table
     * @return bool
     * @since 1.0.0
     */
    public function isFinalConsumerAdd() : bool
    {
        return $this->isFinalConsumerAdd;
    }
}