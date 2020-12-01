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

namespace \Your\Erp\App;

use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\Date\Date as RDate;

// Because is essential to guaranty that the applications must have to export 
// the SAFT-PT file even if are errors, is good to abuse of try/catch block, 
// in that way it will be reduced the possibility of the file not be exported 
// in the case of some inconsistency of the data in your database
// The validation of the SAFT-PT file can be a big processor and time consumer 
// depending on the file size, is best to use the file validation only in 
// your unit tests or when importing data from an external SAFT-PT file,  
// not when you export in production.

/**
 * The Erp Saft generator 
 */
class MyErpSaft
{
    public AuditFile $audit;
    public int $fiscalYearStartMonth = 1;

    public function __construct()
    {
        // if need
        // ini_set("memory_limit", "-1");
        //set_time_limit(0);
        $this->audit = new AuditFile();
    }

    /**
     * Send the SAFT to the broser
     * @return void
     */
    public function sendToBrowser(): void
    {
        header("Content-Type: text/xml");
        echo $audit->toXmlStringWindows1252();
        exit();
    }

    /**
     * Create a global or period SAFT
     * @param RDate $from
     * @param RDate $to
     * @return void
     */
    public function createSaft(RDate $from, RDate$to): void
    {
        $orm    = MyOrm::getInstance();
        $header = $orm->getCompanyInfo();
        try {
            $this->createHeader($hader);
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allCustumers = $orm->getAllCustomers($from, $to);
            if (\count($allCustumers) > 0) {
                $this->createCustomers($allCustumers);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allSuppliers = $orm->getAllSuppliers($from, $to);
            if (\count($allSuppliers) > 0) {
                $this->createSuppliers($allSuppliers);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allProduct = $orm->getAllProduct($from, $to);
            if (\count($allProduct) > 0) {
                $this->createProducts($allProduc);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allTax = $orm->getAllTax($from, $to);
            if (\count($allTax) > 0) {
                $this->createTaxs($allTax);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allInvoices = $orm->getAllInvoices($from, $to);
            if (\count($allInvoices) > 0) {
                $numEntries = $orm->getNumberOfInvoices($from, $to);
                // Or
                // $numEntries = \count($allInvoices);
                $debit      = $orm->getCreditOfInvoices($from, $to);
                $credit     = $orm->getDebitOfInvoices($from, $to);
                $this->createSalesInvoices(
                    $allInvoices, $numEntries, $credit, $debit
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allStockMovement = $orm->getAllStockMovement($from, $to);
            if (\count($allStockMovement) > 0) {
                $numLines = $orm->getStockMovementTotalLines($from, $to);
                $totalQt  = $orm->getStockMovementTotalQt($from, $to);
                $this->createMovementOfGoods(
                    $allStockMovement, $numLines, $totalQt
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allWorkDocument = $orm->getAllWorkDocument($from, $to);
            if (\count($allWorkDocument) > 0) {
                $numEntries = $orm->getNumberOfWorkDocument($from, $to);
                // Or
                // $numEntries = \count($allWorkDocument); 
                $debit      = $orm->getCreditOfWorkDocument($from, $to);
                $credit     = $orm->getDebitOfWorkDocument($from, $to);
                $this->createWorkingDocuments(
                    $allWorkDocument, $numEntries, $credit, $debit
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allPayments = $orm->getAllPayments($from, $to);
            if (\count($allPayments) > 0) {
                $numEntries = $orm->getNumberOfPayments($from, $to);
                // Or
                // $numEntries = \count($allPayments);
                $debit      = $orm->getCreditOfPayments($from, $to);
                $credit     = $orm->getDebitOfPayments($from, $to);
                $this->createPayments(
                    $allWorkDocument, $numEntries, $credit, $debit
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * Create the SAFT file of a selfbilling supplier
     * @param RDate $from
     * @param RDate $to     * 
     * @param string $idSupplier
     * @return void
     */
    public function createSaftSelfBilling(RDate $from, RDate$to,
                                          string $idSupplier): void
    {
        $orm    = MyOrm::getInstance();
        $header = $orm->getSupplierInfo();
        try {
            $this->createHeader($hader);
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allCustumers = $orm->getMySelfBillingInfoAsCustomer();
            $this->createCustomers($allCustumers);
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allProduct = $orm->getAllProductOfSelfBilling(
                $from, $to, $idSupplier
            );
            if (\count($allProduct) > 0) {
                $this->createProducts($allProduc);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allTax = $orm->getAllTaxOfSelfBilling($from, $to, $idSupplier);
            if (\count($allTax) > 0) {
                $this->createTaxs($allTax);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allInvoices = $orm->getAllInvoicesOfSelfBilling(
                $from, $to, $idSupplier
            );
            if (\count($allInvoices) > 0) {
                $numEntries = $orm->getNumberOfInvoicesOfSelfBilling(
                    $from, $to, $idSupplier
                );
                // Or
                // $numEntries = \count($allInvoices);
                $debit      = $orm->getCreditOfInvoicesOfSelfBilling(
                    $from, $to, $idSupplier
                );
                $credit     = $orm->getDebitOfInvoicesOfSelfBilling(
                    $from, $to, $idSupplier
                );
                $this->createSalesInvoices(
                    $allInvoices, $numEntries, $credit, $debit
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * Create the SAFT file StockMovement to get the ATDocCodeID
     * in  e-fatura portal
     * @param array $idDocStack
     * @return void
     */
    public function createSaftOfMovementOfGoodsToGetATDocCodeID(array $idDocStack): void
    {
        $orm    = MyOrm::getInstance();
        $header = $orm->getCompanyInfo();
        try {
            $this->createHeader($hader);
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allCustumers = $orm->getAllCustomersOfStockMovements($idDocStack);
            if (\count($allCustumers) > 0) {
                $this->createCustomers($allCustumers);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allSuppliers = $orm->getAllSuppliersOfStockMovements($idDocStack);
            if (\count($allSuppliers) > 0) {
                $this->createSuppliers($allSuppliers);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allProduct = $orm->getAllProductOfStockMovements($idDocStack);
            if (\count($allProduct) > 0) {
                $this->createProducts($allProduc);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allTax = $orm->getAllTaxOfStockMovements($idDocStack);
            if (\count($allTax) > 0) {
                $this->createTaxs($allTax);
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }

        try {
            $allStockMovement = $orm->getAllStockMovementOfStockMovements($idDocStack);
            if (\count($allStockMovement) > 0) {
                $numLines = $orm->getStockMovementTotalLines($from, $to);
                $totalQt  = $orm->getStockMovementTotalQt($from, $to);
                $this->createMovementOfGoods(
                    $allStockMovement, $numLines, $totalQt
                );
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * Set the header values
     * @return void
     */
    public function createHeader($header): void
    {
        $header = $this->audit->getHeader();
        try {
            // There are fields that are not mandatory, 
            // only if you have the information in your database
            $header->setCompanyID($header->companyID);
            $header->setTaxRegistrationNumber($header->taxRegistrationNumber);
            $header->setTaxAccountingBasis(Rebelo\SaftPt\AuditFile\TaxAccountingBasis::FACTURACAO());
            $header->setCompanyName($header->companyName);
            $header->setBusinessName($header->businessName);
            $headerAddr = $header->getCompanyAddress();
            try {
                $headerAddr->setAddressDetail($addressDetail);
                $headerAddr->setCity($city);
                $headerAddr->setPostalCode($postalCode);
                // Country is automatically set, is always PT
            } catch (\Exception | \Error $ex) {
                // Log error
            }

            $header->setFiscalYear($header->fiscalYear);
            // In all date use the parse format that you have in youir db 
            // the exportation will be done in the correct format
            $header->setStartDate(
                RDate::parse(
                    RDate::SQL_DATE, $header->startDate
                )
            );
            $header->setEndDate(RDate::parse(RDate::SQL_DATE, $header->endDate));
            // Currency is automatically set, is always EUR
            $header->setDateCreated(
                new RDate(
                    "now", new \DateTimeZone("Europe/Lisbon")
                )
            );
            $header->setTaxEntity($header->taxEntity);
            $header->setProductCompanyTaxID($productCompanyTaxID);
            $header->setSoftwareCertificateNumber($softwareCertificateNumber);
            $header->setProductID($productID);
            $header->setProductVersion($productVersion);
            $header->setHeaderComment($header->comment);
            $header->setTelephone($header->telephone);
            $header->setFax($header->fax);
            $header->setEmail($header->email);
            $header->setWebsite($header->website);
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * Set the values of customers
     * @param array $allCustumers
     * @param bool $addFinalConsumer Set if generate the entry for final consumer enerate the 
     * @return void
     */
    public function createCustomers(array $allCustumers,
                                    bool $addFinalConsumer = true): void
    {
        // Master files
        $master = $this->audit->getMasterFiles();
        // Add if you have documents of "Final Consumer" in your documents 
        $master->addCustomerFinalConsumer();
        // Add all other customers
        foreach ($allCustomer as $customer) {
            $cust = $master->addCustomer();
            try {
                $cust->setCustomerID($customer->customerID);
                $cust->setAccountID($customer->accountID ?? AuditFile::DESCONHECIDO);
                $cust->setCustomerTaxID($customer->customerTaxID);
                $cust->setCompanyName($companyName);
                $bill = $cust->getBillingAddress();
                try {
                    $bill->setAddressDetail($customer->addressDetail ?? AuditFile::DESCONHECIDO);
                    $bill->setCity($customer->city ?? AuditFile::DESCONHECIDO);
                    $bill->setPostalCode($customer->postalCode ?? AuditFile::DESCONHECIDO);
                    $bill->setCountry(
                        new Rebelo\SaftPt\AuditFile\Country(
                            $customer->addressDetail ?? AuditFile::DESCONHECIDO
                        )
                    );
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            } catch (\Exception | \Error $ex) {
                // Log error
            }
        }
    }

    /**
     * Set the values of suppliers
     * @param array $allSupplier
     */
    public function createSuppliers(array $allSupplier): void
    {
        // Master files
        $master = $this->audit->getMasterFiles();
        // Suppliers
        foreach ($allSupplier as $supplier) {
            $sup = $master->addSupplier();
            try {
                $sup->setSupplierID($supplier->supplierID);
                $sup->setAccountID($supplier->accountID ?? AuditFile::DESCONHECIDO);
                $sup->setSupplierTaxID($supplier->supplierTaxID);
                $sup->setCompanyName($companyName);
                $bill = $sup->getBillingAddress();
                try {
                    $bill->setAddressDetail($supplier->addressDetail);
                    $bill->setCity($supplier->city);
                    $bill->setPostalCode($supplier->postalCode);
                    $bill->setCountry(
                        new Rebelo\SaftPt\AuditFile\Country($supplier->addressDetail)
                    );
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            } catch (\Exception | \Error $ex) {
                // Log error
            }
        }
    }

    /**
     * Create products
     * @param array $allProducts
     * @return void
     */
    public function createProducts(array $allProducts): void
    {
        // Master files
        $master = $this->audit->getMasterFiles();
        // products
        foreach ($allProducts as $product) {
            $prod = $master->addProduct();
            try {
                $prod->setProductType(
                    new Rebelo\SaftPt\AuditFile\MasterFiles\ProductType($product->type)
                );
                $prod->setProductCode($product->code);
                $prod->setProductGroup($product->group);
                $prod->setProductDescription($product->description);
                $prod->setProductNumberCode($product->numberCode);
            } catch (\Exception | \Error $ex) {
                // Log error
            }
        }
    }

    /**
     * Set the tax values
     * @param array $allTax
     */
    public function createTaxs(array $allTax)
    {
        // Master files
        $master = $this->audit->getMasterFiles();
        // TAX
        foreach ($allTax as $taxEntity) {
            $tax = $master->addTaxTableEntry();
            try {
                $tax->setTaxType(
                    new Rebelo\SaftPt\AuditFile\MasterFiles\TaxType($taxEntity->type)
                );
                $tax->setTaxCountryRegion(
                    new Rebelo\SaftPt\AuditFile\TaxCountryRegion($taxEntity->taxCountryRegion)
                );
                $tax->setTaxCode(
                    new Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode($taxEntity->taxCode)
                );
                $tax->setDescription($description);
                $tax->setTaxPercentage($taxPercentage);
                //OR if is ammount $tax->setTaxAmount($taxAmount);
            } catch (\Exception | \Error $ex) {
                // Log error
            }
        }
    }

    /**
     * Set the Invoices values
     * @param array $allInvoices
     * @param int $numberOfEntries
     * @param float $totalCredit
     * @param float $totalDebit
     */
    public function createSalesInvoices(
        array $allInvoices, int $numberOfEntries, float $totalCredit,
        float $totalDebit)
    {
        $salesInvoices = $this->audit->getSourceDocuments()->getSalesInvoices();
        try {

            $salesInvoices->setNumberOfEntries($numberOfEntries);
            $salesInvoices->setTotalCredit($totalCredit);
            $salesInvoices->setTotalDebit($totalDebit);

            foreach ($allInvoices as $invoice) {
                /* @var $inv \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
                $inv = $salesInvoices->addInvoice();
                try {
                    $inv->setInvoiceNo($invoice->no);
                    $inv->setAtcud($invoice->atcud);
                    $status = $inv->getDocumentStatus();
                    try {
                        $status->setInvoiceStatus(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus($invoice->status)
                        );
                        $status->setInvoiceStatusDate(
                            RDate::parse(
                                RDate::SQL_DATETIME, $invoice->statusDate
                            )
                        );
                        $status->setSourceID($invoice->statusSourceID);
                        $status->setSourceBilling(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling($invoice->sourceBilling)
                        );
                        $status->setReason($invoice->statusReason);
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }
                    $inv->setHash($invoice->hash);
                    $inv->setHashControl($hashControl);

                    try {
                        $inv->setPeriod(
                            AuditFile::calcPeriod(
                                $this->fiscalYearStartMonth,
                                $inv->getInvoiceDate()
                            )
                        );
                    } catch (Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
                        // Log error
                    }

                    $inv->setInvoiceDate(
                        RDate::parse(RDate::SQL_DATE, $invoice->date)
                    );
                    $inv->setInvoiceType(
                        new \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType($invoice->type)
                    );
                    $sepReg = $inv->getSpecialRegimes();
                    try {
                        $sepReg->setSelfBillingIndicator($invoice->selfBillingIndicator);
                        $sepReg->setCashVATSchemeIndicator($invoice->cashVATSchemeIndicator);
                        $sepReg->setThirdPartiesBillingIndicator($invoice->thirdPartiesBillingIndicator);
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }
                    $inv->setSourceID($invoice->ssourceID);
                    $inv->setEacCode($invoice->eacCode);
                    $inv->setSystemEntryDate(
                        RDate::parse(
                            RDate::SQL_DATETIME, $invoice->systemEntryDate
                        )
                    );
                    //depending of your system, most of the time TransactionID is not need 
                    if ($invoice->hasTransactionID) {
                        $tran = $inv->getTransactionID();
                        try {
                            $tran->setDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $invoice->transactionDate
                                )
                            );
                            $tran->setDocArchivalNumber($invoice->docArchivalNumber);
                            $tran->setJournalID($invoice->journalID);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $inv->setCustomerID($invoice->customerID);

                    // If has shipto addr
                    if ($invoice->hasShipTo) {
                        $shipTo = $inv->getShipTo();
                        try {
                            $shipTo->addDeliveryID($invoice->shipToDeliveryID);
                            $shipTo->setDeliveryDate(
                                RDate::parse(
                                    RDate::SQL_DATE,
                                    $invoice->shipToDeliveryDate
                                )
                            );
                            $ware     = $shipTo->addWarehouse();
                            $ware->setWarehouseID($invoice->shipToWarehouseID);
                            $ware->setLocationID($invoice->shipToLocationID);
                            $shipAddr = $shipTo->getAddress();
                            $shipAddr->setAddressDetail($invoice->shipToAddressdetail);
                            $shipAddr->setCity($invoice->shipToCity);
                            $shipAddr->setCountry(
                                new Rebelo\SaftPt\AuditFile\Country($invoice->shipToCountry)
                            );
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    /// if has ShipFrom
                    if ($invoice->hasShipFrom) {
                        $shipFrom = $inv->getShipFrom();
                        try {
                            $shipFrom->addDeliveryID($invoice->shipFromDeliveryID);
                            $shipFrom->setDeliveryDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $invoice->deliveryDate
                                )
                            );
                            $ware     = $shipFrom->addWarehouse();
                            $ware->setWarehouseID($invoice->shipFromWarehouseID);
                            $ware->setLocationID($invoice->shipFromLocationID);
                            $shipAddr = $shipFrom->getAddress();
                            $shipAddr->setAddressDetail($invoice->shipFromAddressdetail);
                            $shipAddr->setCity($invoice->shipFromCity);
                            $shipAddr->setCountry(
                                new Rebelo\SaftPt\AuditFile\Country($invoice->shipFromCountry)
                            );
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    if ($invoice->hasMovEndDate) {
                        $inv->setMovementEndTime(
                            RDate::parse(
                                RDate::DATE_TIME, $invoice->movementEndTime
                            )
                        );
                    }

                    if ($invoice->hasMovStartDate) {
                        $inv->setMovementStartTime(
                            RDate::parse(
                                RDate::DATE_TIME, $invoice->movementStartTime
                            )
                        );
                    }

                    foreach ($invoice as $line) {
                        $li = $inv->addLine();
                        try {
                            // Line number is set automatically, set only if 
                            // not are in the correct order in the array
                            // $li->setLineNumber($line->lineNumeber);

                            if (\count($line->orderReferces) > 0) {
                                foreach ($line->orderReferces as $orderReferces) {
                                    $ordRef = $li->addOrderReferences();
                                    try {
                                        $ordRef->setOriginatingON($orderReferces->originatingON);
                                        $ordRef->setOrderDate(
                                            RDate::parse(
                                                RDate::SQL_DATE,
                                                $orderReferces->date
                                            )
                                        );
                                    } catch (Exception $ex) {
                                        // Log error
                                    }
                                }
                            }

                            $li->setProductCode($line->productCode);
                            $li->setProductDescription($line->productDescription);
                            $li->setQuantity($line->quantity);
                            $li->setUnitOfMeasure($line->unitOfMeasure);
                            $li->setUnitPrice($line->unitPrice);

                            if ($line->hasTaxBase) {
                                $li->setTaxBase($$line->taxBase);
                            }

                            $li->setTaxPointDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $line->taxPointDate
                                )
                            );

                            if (\count($li->references) > 0) {
                                foreach ($li->references as $reference) {
                                    $ref = $li->addReferences();
                                    try {
                                        $ref->setReference($reference->reference);
                                        $ref->setReason($reference->reason);
                                    } catch (\Exception | \Error $ex) {
                                        // Log error
                                    }
                                }
                            }

                            $li->setDescription($line->description);

                            if (\count($line->serialNumbers) > 0) {
                                foreach ($line->serialNumbers as $serial) {
                                    $li->getProductSerialNumber()->addSerialNumber($serial);
                                }
                            }

                            //If is an old erp plase verify VD, TV, TD, AA, DA
                            if ($inv->getInvoiceType()->isEqual(InvoiceType::NC)) {
                                $li->setDebitAmount($line->amout);
                            } else {
                                $li->setCreditAmount($line->amout);
                            }

                            // If you use dbit e credit in same document,
                            // do not use the above example use this one
                            //if($line->debit !== null){
                            //    $li->setDebitAmount($line->debit);
                            //}elseif($line->credit !== null){
                            //    $li->setDebitAmount($line->credit);
                            //}else{
                            //    // Log error
                            //}

                            $tax = $li->getTax();
                            $tax->setTaxType(new TaxType($line->taxType));
                            $tax->setTaxCountryRegion(new TaxCountryRegion($line->taxRegion));
                            $tax->setTaxCode(new TaxCode($line->taxCode));

                            if ($tax->getTaxType()->isEqual(TaxType::IVA)) {
                                $tax->setTaxPercentage($line->taxRate);
                            } else {
                                // do your checks
                                if ($myCheck) {
                                    $tax->setTaxPercentage($line->taxRate);
                                } else {
                                    $tax->setTaxAmount($line->taxAmount);
                                }
                            }

                            if ($line->hasTaxException) {
                                $li->setTaxExemptionReason($line->taxExemptionReason);
                                $li->setTaxExemptionCode(new TaxExemptionCode($line->taxExemptionCode));
                            }

                            $li->setSettlementAmount($line->settlementAmount);

                            if ($li->hasCustomsInfo) {
                                $ci = $li->getCustomsInformation();
                                foreach ($line->arcno as $arcno) {
                                    $ci->addARCNo($arcNo);
                                }
                                $ci->setIecAmount($line->iecAmount);
                            }
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $total = $inv->getDocumentTotals();
                    $total->setNetTotal($invoice->netTotal);
                    $total->setTaxPayable($invoice->taxPayable);
                    $total->setGrossTotal($invoice->gross);

                    if ($invoice->hasCurrency) {
                        try {
                            $curr = $total->getCurrency();
                            $curr->setCurrencyCode(new CurrencyCode($invoice->currencyCode));
                            $curr->setCurrencyAmount($invoice->currencyAmout);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    if ($invoice->hasSettlement) {
                        foreach ($invoice->settlement as $settlement) {
                            $sett = $total->addSettlement();
                            try {
                                $sett->setSettlementDiscount($settlement->discount);
                                $sett->setSettlementDate(
                                    RDate::parse(
                                        RDate::SQL_DATE, $settlemet->date
                                    )
                                );
                                $sett->setPaymentTerms($settlement->paymentTerms);
                            } catch (\Exception | \Error $ex) {
                                // Log error
                            }
                        }
                    }

                    if ($invoice->hasPayments) {
                        foreach ($invoice->payments as $payment) {
                            $pay = $total->addPayment();
                            try {
                                $pay->setPaymentMechanism(
                                    new PaymentMechanism($payment->mechanism)
                                );
                                $pay->setPaymentAmount($payment->amout);
                                $pay->setPaymentDate(
                                    RDate::parse(RDate::SQL_DATE, $payment->date)
                                );
                            } catch (\Exception | \Error $ex) {
                                // Log error
                            }
                        }
                    }

                    if ($invoice->hasWithholdingTax) {
                        foreach ($invoice->withholdingTax as $withholdingTax) {
                            $wh = $inv->addWithholdingTax();
                            try {
                                $wh->setWithholdingTaxType(
                                    new WithholdingTaxType($withholdingTax->type)
                                );
                                $wh->setWithholdingTaxDescription($withholding->taxDescription);
                                $wh->setWithholdingTaxAmount($withholdingTax->amount);
                            } catch (\Exception | \Error $ex) {
                                // Log error
                            }
                        }
                    }
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * 
     * @param array $allStockMov
     * @param int $numLines
     * @param float $totalQt
     * @return void
     */
    public function createMovementOfGoods(
        array $allStockMov, int $numLines, float $totalQt): void
    {
        // Master files
        $movementOfGoods = $this->audit->getSourceDocuments()->getMovementOfGoods();
        try {

            $movementOfGoods->setNumberOfEntries($numberOfEntries);
            $movementOfGoods->setTotalCredit($totalCredit);
            $movementOfGoods->setTotalDebit($totalDebit);

            foreach ($allStockMov as $stockMov) {
                $sm = $movementOfGoods->addStockMovement();
                try {
                    $sm->setDocumentNumber($stockMov->documentNumber);
                    $sm->setAtcud($stockMov->atcud);
                    $status = $sm->getDocumentStatus();
                    try {
                        $status->setMovementStatus(
                            new \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus(
                                $stockMov->status
                            )
                        );
                        $status->setMovementStatusDate(
                            RDate::parse(RDate::SQL_DATE, $stockMov->statusDate)
                        );
                        $status->setReason($stockMov->statusReason);
                        $status->setSourceBilling(
                            new \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling($stockMov->sourceBill)
                        );
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }

                    $sm->setHash($stockMov->hash);
                    $sm->setHashControl($stockMov->hashControl);

                    try {
                        $sm->setPeriod(
                            AuditFile::calcPeriod(
                                $this->fiscalYearStartMonth,
                                $sm->getMovementDate()
                            )
                        );
                    } catch (Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
                        // Log error
                    }

                    $sm->setSystemEntryDate(
                        RDate::parse(
                            RDate::DATE_TIME, $stockMov->systemEntryDate
                        )
                    );

                    // if necessary set Transactio ID, most of the cases not

                    if ($stockMov->custumerID !== null) {
                        $sm->setCustomerID($stockMov->custumerID);
                    } elseif ($stockMov->supplierID !== null) {
                        $sm->setSupplierID($stockMov->supplierID);
                    } else {
                        $sm->setCustomerID(AuditFile::CONSUMIDOR_FINAL_ID);
                    }

                    $sm->setSourceID($stockMov->souurceID);
                    $sm->setEacCode($stockMov->eacCode);
                    $sm->setMovementComments($stockMov->comments);

                    if ($stockMov->hasShipTo) {
                        $shipTo = $sm->getShipTo();
                        try {
                            $shipTo->addDeliveryID($stockMov->shipToDeliveryID);
                            $shipTo->setDeliveryDate(
                                RDate::parse(
                                    RDate::SQL_DATE,
                                    $stockMov->shipToDeliveryDate
                                )
                            );
                            $ware     = $shipTo->addWarehouse();
                            $ware->setWarehouseID($stockMov->shipToWarehouseID);
                            $ware->setLocationID($stockMov->shipToLocationID);
                            $shipAddr = $shipTo->getAddress();
                            $shipAddr->setAddressDetail($stockMov->shipToAddressdetail);
                            $shipAddr->setCity($stockMov->shipToCity);
                            $shipAddr->setCountry(
                                new Rebelo\SaftPt\AuditFile\Country($stockMov->shipToCountry)
                            );
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }


                    $shipFrom = $sm->getShipFrom();
                    try {
                        $shipFrom->addDeliveryID($stockMov->shipFromDeliveryID);
                        $shipFrom->setDeliveryDate(
                            RDate::parse(
                                RDate::SQL_DATE, $stockMov->deliveryDate
                            )
                        );
                        $ware     = $shipFrom->addWarehouse();
                        $ware->setWarehouseID($stockMov->shipFromWarehouseID);
                        $ware->setLocationID($stockMov->shipFromLocationID);
                        $shipAddr = $shipFrom->getAddress();
                        $shipAddr->setAddressDetail($stockMov->shipFromAddressdetail);
                        $shipAddr->setCity($stockMov->shipFromCity);
                        $shipAddr->setCountry(
                            new Rebelo\SaftPt\AuditFile\Country($stockMov->shipFromCountry)
                        );
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }

                    if ($stockMov->hasMovEndDate) {
                        $sm->setMovementEndTime(
                            RDate::parse(
                                RDate::DATE_TIME, $stockMov->movementEndTime
                            )
                        );
                    }

                    $sm->setMovementStartTime(
                        RDate::parse(
                            RDate::DATE_TIME, $stockMov->movementStartTime
                        )
                    );

                    $sm->setAtDocCodeID($stockMov->atDocCodeID);

                    foreach ($stockMov as $line) {
                        $li = $sm->addLine();
                        try {
                            // Line number is set automatically, set only if 
                            // not are in the correct order in the array
                            //$li->setLineNumber($line->lineNumeber);

                            if ($line->hasOrderRefences) {
                                foreach ($line->orderReferences as $order) {
                                    $orRef = $li->addOrderReferences();
                                    try {
                                        $orRef->setOriginatingON($order->originatingON);
                                        $orRef->setOrderDate(
                                            RDate::parse(
                                                RDate::SQL_DATE, $order->date
                                            )
                                        );
                                    } catch (\Exception | \Error $ex) {
                                        // Log errors
                                    }
                                }
                            }


                            $li->setProductCode($line->productCode);
                            $li->setProductDescription($line->productDescription);
                            $li->setQuantity($line->quantity);
                            $li->setUnitOfMeasure($line->unitOfMeasure);
                            $li->setDescription($line->description);
                            $li->setQuantity($line->quantity);

                            if (\count($line->serialNumbers) > 0) {
                                foreach ($line->serialNumbers as $serial) {
                                    $li->getProductSerialNumber()->addSerialNumber($serial);
                                }
                            }

                            if ($sm->getMovementType()->isEqual(MovementType::GD)) {
                                $li->setCreditAmount($line->amount);
                            } else {
                                $li->setDebitAmount($line->amount);
                            }

                            $tax = $li->getTax();
                            $tax->setTaxType(new TaxType($line->taxType));
                            $tax->setTaxCountryRegion(new TaxCountryRegion($line->taxRegion));
                            $tax->setTaxCode(new TaxCode($line->taxCode));
                            $tax->setTaxPercentage($line->taxRate);

                            if ($line->hasTaxException) {
                                $li->setTaxExemptionReason($line->taxExemptionReason);
                                $li->setTaxExemptionCode(new TaxExemptionCode($line->taxExemptionCode));
                            }

                            $li->setSettlementAmount($line->settlementAmount);

                            if ($li->hasCustomsInfo) {
                                $ci = $li->getCustomsInformation();
                                foreach ($line->arcno as $arcno) {
                                    $ci->addARCNo($arcNo);
                                }
                                $ci->setIecAmount($line->iecAmount);
                            }
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $total = $sm->getDocumentTotals();
                    $total->setNetTotal($stockMov->netTotal);
                    $total->setTaxPayable($stockMov->taxPayable);
                    $total->setGrossTotal($stockMov->gross);

                    if ($stockMov->hasCurrency) {
                        try {
                            $curr = $total->getCurrency();
                            $curr->setCurrencyCode(new CurrencyCode($stockMov->currencyCode));
                            $curr->setCurrencyAmount($stockMov->currencyAmout);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * 
     * @param array $allWorkDoc
     * @param int $numOfEntries
     * @param float $totalCredit
     * @param float $totalDebit
     * @return void
     */
    public function createWorkingDocuments(
        array $allWorkDoc, int $numOfEntries, float $totalCredit,
        float $totalDebit): void
    {

        $workingDocuments = $this->audit->getSourceDocuments()->getWorkingDocuments();
        try {

            $workingDocuments->setNumberOfEntries($numberOfEntries);
            $workingDocuments->setTotalCredit($totalCredit);
            $workingDocuments->setTotalDebit($totalDebit);

            foreach ($allWorkDoc as $workDoc) {
                /* @var $work \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
                $work = $workingDocuments->addWorkDocument();
                try {
                    $work->setDocumentNumber($workDoc->no);
                    $work->setAtcud($workDoc->atcud);
                    $status = $work->getDocumentStatus();
                    /* @var $status \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus */
                    try {
                        $status->setDoc(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus($workDoc->status)
                        );
                        $status->setWorkStatusDate(
                            RDate::parse(
                                RDate::SQL_DATETIME, $workDoc->statusDate
                            )
                        );
                        $status->setReason($workDoc->statusReason);
                        $status->setSourceID($workDoc->statusSourceID);
                        $status->setSourceBilling(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling($workDoc->sourceBilling)
                        );
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }
                    $work->setHash($workDoc->hash);
                    $work->setHashControl($hashControl);

                    try {
                        $work->setPeriod(
                            AuditFile::calcPeriod(
                                $this->fiscalYearStartMonth,
                                $work->getWorkDate()
                            )
                        );
                    } catch (Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
                        // Log error
                    }

                    $work->setWorkDate(
                        RDate::parse(RDate::SQL_DATE, $workDoc->date)
                    );
                    $work->setWorkType(
                        new \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType($workDoc->type)
                    );

                    $work->setSourceID($workDoc->ssourceID);
                    $work->setEacCode($workDoc->eacCode);
                    $work->setSystemEntryDate(
                        RDate::parse(
                            RDate::SQL_DATETIME, $workDoc->systemEntryDate
                        )
                    );

                    //depending of your system, most of the time TransactionID is not need 
                    if ($workDoc->hasTransactionID) {
                        $tran = $work->getTransactionID();
                        try {
                            $tran->setDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $workDoc->transactionDate
                                )
                            );
                            $tran->setDocArchivalNumber($workDoc->docArchivalNumber);
                            $tran->setJournalID($workDoc->journalID);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $work->setCustomerID($workDoc->customerID);

                    foreach ($workDoc as $line) {
                        $li = $work->addLine();
                        try {
                            // Line number is set automatically, set only if 
                            // not are in the correct order in the array
                            // $li->setLineNumber($line->lineNumeber);

                            if (\count($line->orderReferces) > 0) {
                                foreach ($line->orderReferces as $orderReferces) {
                                    $ordRef = $li->addOrderReferences();
                                    try {
                                        $ordRef->setOriginatingON($orderReferces->originatingON);
                                        $ordRef->setOrderDate(
                                            RDate::parse(
                                                RDate::SQL_DATE,
                                                $orderReferces->date
                                            )
                                        );
                                    } catch (Exception $ex) {
                                        // Log error
                                    }
                                }
                            }

                            $li->setProductCode($line->productCode);
                            $li->setProductDescription($line->productDescription);
                            $li->setQuantity($line->quantity);
                            $li->setUnitOfMeasure($line->unitOfMeasure);
                            $li->setUnitPrice($line->unitPrice);

                            if ($line->hasTaxBase) {
                                $li->setTaxBase($$line->taxBase);
                            }

                            $li->setTaxPointDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $line->taxPointDate
                                )
                            );

                            if (\count($li->references) > 0) {
                                foreach ($li->references as $reference) {
                                    $ref = $li->addReferences();
                                    try {
                                        $ref->setReference($reference->reference);
                                        $ref->setReason($reference->reason);
                                    } catch (\Exception | \Error $ex) {
                                        // Log error
                                    }
                                }
                            }

                            $li->setDescription($line->description);

                            if (\count($line->serialNumbers) > 0) {
                                foreach ($line->serialNumbers as $serial) {
                                    $li->getProductSerialNumber()->addSerialNumber($serial);
                                }
                            }

                            // If you use the "OUT" please make debit/credit as 
                            // it configured for that type
                            if ($work->getWorkType()->isEqual(\Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType::RE)) {
                                $li->setDebitAmount($line->amout);
                            } else {
                                $li->setCreditAmount($line->amout);
                            }

                            $tax = $li->getTax();
                            $tax->setTaxType(new TaxType($line->taxType));
                            $tax->setTaxCountryRegion(new TaxCountryRegion($line->taxRegion));
                            $tax->setTaxCode(new TaxCode($line->taxCode));

                            if ($tax->getTaxType()->isEqual(TaxType::IVA)) {
                                $tax->setTaxPercentage($line->taxRate);
                            } else {
                                // do your checks
                                if ($myCheck) {
                                    $tax->setTaxPercentage($line->taxRate);
                                } else {
                                    $tax->setTaxAmount($line->taxAmount);
                                }
                            }

                            if ($line->hasTaxException) {
                                $li->setTaxExemptionReason($line->taxExemptionReason);
                                $li->setTaxExemptionCode(new TaxExemptionCode($line->taxExemptionCode));
                            }

                            $li->setSettlementAmount($line->settlementAmount);

                            if ($li->hasCustomsInfo) {
                                $ci = $li->getCustomsInformation();
                                foreach ($line->arcno as $arcno) {
                                    $ci->addARCNo($arcNo);
                                }
                                $ci->setIecAmount($line->iecAmount);
                            }
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $total = $work->getDocumentTotals();
                    $total->setNetTotal($workDoc->netTotal);
                    $total->setTaxPayable($workDoc->taxPayable);
                    $total->setGrossTotal($workDoc->gross);

                    if ($workDoc->hasCurrency) {
                        try {
                            $curr = $total->getCurrency();
                            $curr->setCurrencyCode(new CurrencyCode($workDoc->currencyCode));
                            $curr->setCurrencyAmount($workDoc->currencyAmout);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            }
        } catch (\Exception | \Error $ex) {
            // Log error
        }
    }

    /**
     * 
     * @param array $allPayments
     * @param int $numOfEntries
     * @param float $totalCredit
     * @param float $totalDebit
     * @return void
     */
    public function createPayments(
        array $allPayments, int $numOfEntries, float $totalCredit,
        float $totalDebit): void
    {
        $payments = $this->audit->getSourceDocuments()->getPayments();
        try {

            $payments->setNumberOfEntries($numberOfEntries);
            $payments->setTotalCredit($totalCredit);
            $payments->setTotalDebit($totalDebit);

            foreach ($allPayments as $payment) {
                /* @var $pay \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
                $pay = $payments->addPayment();
                try {
                    $pay->setPaymentRefNo($payment->refNo);
                    $pay->setATCUD($payment->atcud);

                    //depending of your system, most of the time TransactionID is not need 
                    if ($payment->hasTransactionID) {
                        $tran = $pay->getTransactionID();
                        try {
                            $tran->setDate(
                                RDate::parse(
                                    RDate::SQL_DATE, $payment->transactionDate
                                )
                            );
                            $tran->setDocArchivalNumber($payment->docArchivalNumber);
                            $tran->setJournalID($payment->journalID);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $pay->setPaymentType(
                        new \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType($payment->type)
                    );

                    $pay->setDescription($payment->description);
                    $pay->setSystemID($payment->systemID);

                    $status = $pay->getDocumentStatus();
                    /* @var $status \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus */
                    try {
                        $status->setDoc(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus($payment->status)
                        );
                        $status->setPaymentStatusDate(
                            RDate::parse(
                                RDate::SQL_DATETIME, $payment->statusDate
                            )
                        );
                        $status->setReason($payment->statusReason);
                        $status->setSourceID($payment->statusSourceID);
                        $status->setSourcePayment(
                            new Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment($payment->sourceBilling)
                        );
                    } catch (\Exception | \Error $ex) {
                        // Log error
                    }

                    foreach ($payment->payMethods as $payMethod) {
                        $method = $pay->addPaymentMethod();
                        try {
                            $method->setPaymentMechanism(
                                new PaymentMechanism($payMethod->mechanism)
                            );
                            $method->setPaymentAmount($payMethod->amount);
                            $method->setPaymentDate(
                                RDate::parse(RDate::SQL_DATE, $payMethod->date)
                            );
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $pay->setSourceID($payment->sourceID);
                    $pay->setSystemEntryDate(
                        RDate::parse(
                            RDate::SQL_DATETIME, $payment->systemEntryDate
                        )
                    );
                    $pay->setCustomerID($payment->customerID);

                    foreach ($payment->line as $line) {
                        $li = $pay->addLine();
                        try {
                            // Line number is set automatically, set only if 
                            // not are in the correct order in the array
                            // $li->setLineNumber($line->lineNumeber);

                            if ($line->hasSourceDocumentID) {
                                foreach ($line->hasSourceDocumentID as $sourceDocId) {
                                    $source = $li->addSourceDocumentID();
                                    $source->setOriginatingON($sourceDocId->originatingON);
                                    $source->setInvoiceDate(
                                        RDate::parse(
                                            RDate::SQL_DATE,
                                            $sourceDocId->invoiceDate
                                        )
                                    );
                                    $source->setDescription($sourceDocId->description);
                                }
                            }

                            $li->setSettlementAmount($line->settlement);

                            //Most of the times
                            $li->setCreditAmount($line->amount);

                            if ($line->hasTax) {
                                $tax = $li->getTax();
                                $tax->setTaxType(new TaxType($line->taxType));
                                $tax->setTaxCountryRegion(new TaxCountryRegion($line->taxRegion));
                                $tax->setTaxCode(new TaxCode($line->taxCode));

                                if ($tax->getTaxType()->isEqual(TaxType::IVA)) {
                                    $tax->setTaxPercentage($line->taxRate);
                                } else {
                                    // do your checks
                                    if ($myCheck) {
                                        $tax->setTaxPercentage($line->taxRate);
                                    } else {
                                        $tax->setTaxAmount($line->taxAmount);
                                    }
                                }

                                if ($line->hasTaxException) {
                                    $li->setTaxExemptionReason($line->taxExemptionReason);
                                    $li->setTaxExemptionCode(new TaxExemptionCode($line->taxExemptionCode));
                                }
                            }
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    $total = $pay->getDocumentTotals();
                    $total->setNetTotal($payment->netTotal);
                    $total->setTaxPayable($payment->taxPayable);
                    $total->setGrossTotal($payment->gross);
                    $sett  = $total->setSettlementAmount($payment->settlement);

                    if ($payment->hasCurrency) {
                        try {
                            $curr = $total->getCurrency();
                            $curr->setCurrencyCode(new CurrencyCode($payment->currencyCode));
                            $curr->setCurrencyAmount($payment->currencyAmout);
                        } catch (\Exception | \Error $ex) {
                            // Log error
                        }
                    }

                    if ($payment->hasWithholdingTax) {
                        foreach ($payment->withholdingTax as $withholdingTax) {
                            $wh = $pay->addWithholdingTax();
                            try {
                                $wh->setWithholdingTaxType(
                                    new WithholdingTaxType($withholdingTax->type)
                                );
                                $wh->setWithholdingTaxDescription($withholding->taxDescription);
                                $wh->setWithholdingTaxAmount($withholdingTax->amount);
                            } catch (\Exception | \Error $ex) {
                                // Log error
                            }
                        }
                    }
                } catch (\Exception | \Error $ex) {
                    // Log error
                }
            }
        } catch (Exception $ex) {
            
        }
    }
}
/* * **************************************************************************
 *                                                                            *
 * Instanciate the class to generate a SAFT-PT global file                    *
 *                                                                            *
 * *************************************************************************** */

$mySaft = new MyErpSaft();
try {
    $mySaft->createSaft($from, $to);

    //*************************************************************************/
    // if is UnitTest make validation, 
    $config     = new Rebelo\SaftPt\Validate\ValidationConfig(); // Set configuration has your needs
    $pubKeyPath = "/path/of/my/pubkey";
    $mySaft->audit->validate($pubKeyPath, $config);
    // asertation of $mySaft->audit->getErrorRegistor()
    //*************************************************************************/

    if ($mySaft->audit->getErrorRegistor()->hasErrors()) {
        // Log errors
    }
} catch (\Exception | \Error $ex) {
    // Log error
} finally {
    // If you wich to send to the browser
    $mySaft->sendToBrowser();
    // Or save as a file
    $mySaft->audit->toFile("/path/to/file");
}


/* * **************************************************************************
 *                                                                            *
 * Instanciate the class to generate a SelfBilling SAFT-PT                    *
 *                                                                            *
 * *************************************************************************** */

$mySaft = new MyErpSaft();
try {
    $mySaft->createSaftSelfBilling($from, $to, $idSupplier);

    //*************************************************************************/
    // if is UnitTest make validation, 
    $config     = new Rebelo\SaftPt\Validate\ValidationConfig(); // Set configuration has your needs
    $pubKeyPath = "/path/of/my/pubkey";
    $mySaft->audit->validate($pubKeyPath, $config);
    // asertation of $mySaft->audit->getErrorRegistor()
    //*************************************************************************/

    if ($mySaft->audit->getErrorRegistor()->hasErrors()) {
        // Log errors
    }
} catch (\Exception | \Error $ex) {
    // Log error
} finally {
    // If you wich to send to the browser
    $mySaft->sendToBrowser();
    // Or save as a file
    $mySaft->audit->toFile("/path/to/file");
}


/* * *********************************************************************************
 *                                                                                   *
 * Instanciate the class to generate SAFT-PT of StockMovement to get the ATDocCodeID *
 *                                                                                   *
 * ********************************************************************************* */

$mySaft = new MyErpSaft();
try {
    $mySaft->createSaftOfMovementOfGoodsToGetATDocCodeID($idDocStack);

    //*************************************************************************/
    // if is UnitTest make validation, 
    $config     = new Rebelo\SaftPt\Validate\ValidationConfig(); // Set configuration has your needs
    $pubKeyPath = "/path/of/my/pubkey";
    $mySaft->audit->validate($pubKeyPath, $config);
    // asertation of $mySaft->audit->getErrorRegistor()
    //*************************************************************************/

    if ($mySaft->audit->getErrorRegistor()->hasErrors()) {
        // Log errors
    }
} catch (\Exception | \Error $ex) {
    // Log error
} finally {
    // If you wich to send to the browser
    $mySaft->sendToBrowser();
    // Or save as a file
    $mySaft->audit->toFile("/path/to/file");
}


