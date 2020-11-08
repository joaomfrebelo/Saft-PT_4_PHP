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

namespace Rebelo\SaftPt\AuditFile\i18n;

/**
 * en_GB
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class en_GB extends AI18n
{

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->stack["no_header_table"]                                          = "Header not exported";
        $this->stack["no_master_files_table"]                                    = "Table 'MasterFiles' not exported";
        $this->stack["company_id_not_valid"]                                     = "Field 'CompanyID' not valid";
        $this->stack["TaxRegistrationNumber_not_valid_header"]                   = "'TaxRegistrationNumber' field in 'Header' is not valid";
        $this->stack["invalid_header_companyname"]                               = "'CompanyName' in 'Header' is not valid";
        $this->stack["invalid_BusinessName"]                                     = "'BusinessName' is not valid";
        $this->stack["FiscalYear_not_valid"]                                     = "'FiscalYear' is not valid";
        $this->stack["StartDate_not_valid"]                                      = "'StartDate' is not valid";
        $this->stack["EndDate_not_valid"]                                        = "'EndDate' is not valid";
        $this->stack["DateCreated_not_valid"]                                    = "'DateCreated' is not valid";
        $this->stack["TaxEntity_not_valid"]                                      = "'TaxEntity' is not valid";
        $this->stack["ProductCompanyTaxID_not_valid"]                            = "'ProductCompanyTaxID' is not valid";
        $this->stack["SoftwareCertificateNumber_not_valid"]                      = "'SoftwareCertificateNumber' is not valid";
        $this->stack["ProductID_not_valid"]                                      = "'ProductID' is not valid";
        $this->stack["ProductVersion_not_valid"]                                 = "'ProductVersion' is not valid";
        $this->stack["HeaderComment_not_valid"]                                  = "'HeaderComment' is not valid";
        $this->stack["Telephone_Header_not_valid"]                               = "'Telephone' in 'Header' is not valid";
        $this->stack["Fax_Header_not_valid"]                                     = "'Fax' in 'Header' is not valid";
        $this->stack["Email_Header_not_valid"]                                   = "'Email' in 'Header' not valid";
        $this->stack["Website_Header_not_valid"]                                 = "'Website' in 'Header' not valid";
        $this->stack["TaxAccountingBasis_is_not_setted"]                         = "'TaxAccountingBasis' is not setted";
        $this->stack["CompanyAddress_is_not_setted"]                             = "'CompanyAddress' is not setted";
        $this->stack["BuildingNumber_not_valid"]                                 = "'BuildingNumber' is not valid";
        $this->stack["StreetName_not_valid"]                                     = "'StreetName' is not valid";
        $this->stack["AddressDetail_not_valid"]                                  = "'AddressDetail' is not valid";
        $this->stack["City_not_valid"]                                           = "'City' is not valid";
        $this->stack["Region_not_valid"]                                         = "'Region' is not valid";
        $this->stack["PostalCode_not_valid"]                                     = "'PostalCode' is not valid";
        $this->stack["Country_not_valid"]                                        = "'Country' is not valid";
        $this->stack["CustomerID_not_valid"]                                     = "'CustomerID' is not valid";
        $this->stack["CustomerTaxID_not_valid"]                                  = "'CustomerTaxID' is not valid";
        $this->stack["CompanyName_not_valid"]                                    = "'CompanyName' is not valid";
        $this->stack["Telephone_not_valid"]                                      = "'Telephone' is not valid";
        $this->stack["Fax_not_valid"]                                            = "'Fax' is not valid";
        $this->stack["Email_not_valid"]                                          = "'Email' is not valid";
        $this->stack["Website_not_valid"]                                        = "'Website' is not valid";
        $this->stack["BillingAddress_not_valid"]                                 = "'BillingAddress' is not valid";
        $this->stack["SelfBillingIndicator_not_valid"]                           = "'SelfBillingIndicator' is not valid";
        $this->stack["SupplierID_not_valid"]                                     = "'SupplierID' is not valid";
        $this->stack["SuppplierTaxID_not_valid"]                                 = "'SupplierTaxID' is not valid";
        $this->stack["UNNumber_not_valid"]                                       = "'UNNumber' is not valid";
        $this->stack["CNCode_not_valid"]                                         = "'CNCode' is not valid";
        $this->stack["ProductCode_not_valid"]                                    = "'ProductCode' is not valid";
        $this->stack["ProductDescription_not_valid"]                             = "'ProductDescription' is not valid";
        $this->stack["ProductNumberCode_not_valid"]                              = "'ProductNumberCode' is not valid";
        $this->stack["TaxEntry_Description_not_valid"]                           = "'Description' in 'TaxEntry' is not valid";
        $this->stack["TaxEntry_TaxAmount_not_valid"]                             = "'TaxAmount' in 'TaxEntry' is not valid";
        $this->stack["TaxEntry_TaxType_not_valid"]                               = "'TaxType' in 'TaxEntry' is not valid";
        $this->stack["TaxEntry_CountryRegion_not_valid"]                         = "'CountryRegion' in 'TaxEntry' is not valid";
        $this->stack["TaxEntry_TaxCode_not_valid"]                               = "'TaxCode' in 'TaxEntry' is not valid";
        $this->stack["TaxEntry_TaxAmount_and_Percentage_setted"]                 = "'TaxAmount' and 'TaxPercentage' in 'TaxEntry' defined at same time";
        $this->stack["JournalID_not_valid"]                                      = "'JournalID' is not valid";
        $this->stack["DocArchivalNumber_not_valid"]                              = "'DocArchivalNumber' is not valid";
        $this->stack["TransactionID_not_valid"]                                  = "'TransactionID' is not valid";
        $this->stack["TaxAmount_and_Percentage_setted"]                          = "'TaxAmount' and 'TaxPercentage' defined at same time";
        $this->stack["TaxPercentage_not_valid"]                                  = "'TaxPercentage' is not valid";
        $this->stack["TaxAmount_not_valid"]                                      = "'TaxAmount' is not valid";
        $this->stack["WarehouseID_not_valid"]                                    = "'WarehouseID' is not valid";
        $this->stack["WithholdingTaxDescription_not_valid"]                      = "'WithholdingTaxDescription' is not valid";
        $this->stack["WithholdingTaxAmount_not_valid"]                           = "'WithholdingTaxAmount' is not valid";
        $this->stack["DeliveryID_not_valid"]                                     = "'DeliveryID' is not valid";
        $this->stack["Reference_not_valid"]                                      = "'Reference' is not valid";
        $this->stack["SerialNumber_not_valid"]                                   = "'SerialNumber' is not valid";
        $this->stack["PaymentAmount_not_valid"]                                  = "'PaymentAmount' is not valid";
        $this->stack["OriginatingON_not_valid"]                                  = "'OriginatingON' is not valid";
        $this->stack["ARCNo_not_valid"]                                          = "'ARCNo' is not valid";
        $this->stack["IecAmount_not_valid"]                                      = "'IecAmount' is not valid";
        $this->stack["CurrencyAmount_not_valid"]                                 = "'CurrencyAmount' is not valid";
        $this->stack["ExchangeRate_not_valid"]                                   = "'ExchangeRate' is not valid";
        $this->stack["CurrencyCode_not_valid"]                                   = "'CurrencyCode' is not valid";
        $this->stack["TaxBase_not_valid"]                                        = "'TaxBase' is not valid";
        $this->stack["Description_not_valid"]                                    = "'Description' is not valid";
        $this->stack["TaxPayable_not_valid"]                                     = "'TaxPayable' is not valid";
        $this->stack["NetTotal_not_valid"]                                       = "'NetTotal' is not valid";
        $this->stack["Atcud_not_valid"]                                          = "'Atcud' is not valid";
        $this->stack["Hash_not_valid"]                                           = "'Hash' is not valid";
        $this->stack["HashControl_not_valid"]                                    = "'HashControl' is not valid";
        $this->stack["Period_not_valid"]                                         = "'Period' is not valid";
        $this->stack["EacCode_not_valid"]                                        = "'EacCode' is not valid";
        $this->stack["Quantity_not_valid"]                                       = "'Quantity' is not valid";
        $this->stack["UnitOfMeasure_not_valid"]                                  = "'UnitOfMeasure' is not valid";
        $this->stack["UnitPrice_not_valid"]                                      = "'UnitPrice' is not valid";
        $this->stack["LineNumber_not_valid"]                                     = "'LineNumber' is not valid";
        $this->stack["DebitAmount_not_valid"]                                    = "'DebitAmount' is not valid";
        $this->stack["CreditAmount_not_valid"]                                   = "'CreditAmount' is not valid";
        $this->stack["TaxExemptionReason_not_valid"]                             = "'TaxExemptionReason' is not valid";
        $this->stack["SettlementAmount_not_valid"]                               = "'SettlementAmount' is not valid";
        $this->stack["Debit_and_Credit_setted_at_same_time"]                     = "'Debit' and 'Credit' setted at same time";
        $this->stack["No_Debit_or_Credit_setted"]                                = "No 'Debit' or 'Credit' setted";
        $this->stack["SourceID_not_valid"]                                       = "'SourceID' is not valid";
        $this->stack["WorkStatus_not_valid"]                                     = "'WorkStatus' is not valid";
        $this->stack["WorkStatusDate_not_valid"]                                 = "'WorkStatusDate' is not valid";
        $this->stack["SourceBilling_not_valid"]                                  = "'SourceBilling' is not valid";
        $this->stack["DocumentNumber_not_valid"]                                 = "'DocumentNumber' is not valid";
        $this->stack["Atcud_not_valid"]                                          = "'Atcud' is not valid";
        $this->stack["DocumentStatus_not_valid"]                                 = "'DocumentStatus' is not valid";
        $this->stack["DocumentTotals_not_valid"]                                 = "'DocumentTotals' is not valid";
        $this->stack["WorkDate_not_valid"]                                       = "'WorkDate' is not valid";
        $this->stack["WorkType_not_valid"]                                       = "'WorkType' is not valid";
        $this->stack["SystemEntryDate_not_valid"]                                = "'SystemEntryDate' is not valid";
        $this->stack["WorkDocument_without_lines"]                               = "'WorkDocument' without lines";
        $this->stack["NumberOfEntries_not_valid"]                                = "'NumberOfEntries' is not valid";
        $this->stack["TotalDebit_not_valid"]                                     = "'TotalDebit' is not valid";
        $this->stack["TotalCredit_not_valid"]                                    = "'TotalCredit' is not valid";
        $this->stack["InvoiceStatus_not_valid"]                                  = "'InvoiceStatus' is not valid";
        $this->stack["InvoiceStatusDate_not_valid"]                              = "'InvoiceStatusDate' is not valid";
        $this->stack["SettlementDiscount_not_valid"]                             = "'SettlementDiscount' is not valid";
        $this->stack["PaymentTerms_not_valid"]                                   = "'PaymentTerms' is not valid";
        $this->stack["InvoiceNo_not_valid"]                                      = "'InvoiceNo' is not valid";
        $this->stack["InvoiceDate_not_valid"]                                    = "'InvoiceDate' is not valid";
        $this->stack["InvoiceType_not_valid"]                                    = "'InvoiceType' is not valid";
        $this->stack["SpecialRegimes_not_valid"]                                 = "'SpecialRegimes' is not valid";
        $this->stack["Invoice_without_lines"]                                    = "'Invoice' without lines";
        $this->stack["Reason_not_valid"]                                         = "'Reason' is not valid";
        $this->stack["PaymentStatus_not_valid"]                                  = "'PaymentStatus' is not valid";
        $this->stack["SourcePayment_not_valid"]                                  = "'SourcePayment' is not valid";
        $this->stack["SourceDocumentID_without_elements"]                        = "'SourceDocumentID' without elements";
        $this->stack["PaymentRefNo_not_valid"]                                   = "'PaymentRefNo' is not valid";
        $this->stack["SystemID_not_valid"]                                       = "'SystemID' is not valid";
        $this->stack["PaymentType_not_valid"]                                    = "'PaymentType' is not valid";
        $this->stack["TaxType_not_valid"]                                        = "'TaxType' is not valid";
        $this->stack["TaxCountryRegion_not_valid"]                               = "'TaxCountryRegion' is not valid";
        $this->stack["TaxCode_not_valid"]                                        = "'TaxCode' is not valid";
        $this->stack["CustomerID_and_SupplierID_at_same_time"]                   = "'CustomerID' and 'SupplierID' setted at same time";
        $this->stack["CustomerID_and_SupplierID_not_setted"]                     = "'CustomerID' and 'SupplierID' not setted";
        $this->stack["AtDocCodeID_not_valid"]                                    = "'AtDocCodeID' is not valid";
        $this->stack["StockMovement_without_lines"]                              = "'StockMovement' without lines";
        $this->stack["NumberOfMovementLines_not_valid"]                          = "'NumberOfMovementLines' is not valid";
        $this->stack["TotalQuantityIssued_not_valid"]                            = "'TotalQuantityIssued' is not valid";
        $this->stack["duplicated_invoice"]                                       = "Duplicated entry for 'Invoice' '%s'";
        $this->stack["duplicated_payment"]                                       = "Duplicated entry for 'Payment' '%s'";
        $this->stack["duplicated_stock_mov"]                                     = "Duplicated entry for 'StockMovement' '%s'";
        $this->stack["duplicated_workdoc"]                                       = "duplicated entry for 'WorkDocument' '%s'";
        $this->stack["invoice_at_index_no_number"]                               = "The 'Invoice' at index '%s' does not have document number";
        $this->stack["payment_at_index_no_number"]                               = "The 'Payment' at index '%s' does not have document number";
        $this->stack["stock_move_at_index_no_number"]                            = "The 'StockMovement' at index '%s' does not have document number";
        $this->stack["workdoc_at_index_no_number"]                               = "The 'WorkDocument' at index '%s' does not have document number";
        $this->stack["wrong_number_of_invoices"]                                 = "The number of 'Invoice' is wrong, is expected '%s' but only exists 's%";
        $this->stack["wrong_number_of_workdocuments"]                            = "The number of 'WorkDocument' is wrong, is expected '%s' but only exists 's%";
        $this->stack["does_not_have_hash"]                                       = "Invoice '%s' does not have the hash sign";
        $this->stack["is_valid_only_if_is_not_first_of_serie"]                   = "The signature of documento '%s' could be valid if is not the first document of teh serie";
        $this->stack["tax_iva_must_have_percentage"]                             = "The Tax in document '%s' is IVA, tax percentage is missing";
        $this->stack["tax_zero_must_have_code_and_reason"]                       = "The document '%s' has the tax percentage/amout as zero but does not have the indication of the exception code and/or the reason";
        $this->stack["tax_iva_code_ise_must_have_code_and_reason"]               = "The document '%s' has the IVA code as Exempted but not the indication of the exception code and/or the reason";
        $this->stack["does_not_have_document_totals"]                            = "The document '%s' does not have the totals defined";
        $this->stack["document_gross_not_equal_tax_plus_net"]                    = "The sum of NetTotal with the TaxPayable of Document '%s' is not euqal to TotalGross";
        $this->stack["document_gross_not_equal_calc_gross"]                      = "The calculated GrossTotal of document '%s' does not match the document GrossTotal";
        $this->stack["document_nettotal_not_equal_calc_nettotal"]                = "The calculated NetTotal of document '%s' does not match the document NetTotal";
        $this->stack["document_taxpayable_not_equal_calc_taxpayable"]            = "The calculated TaxPayable of document '%s' does not match the document TaxPayable";
        $this->stack["document_line_no_number"]                                  = "The document '%s' has lines without numeration";
        $this->stack["document_line_no_continues"]                               = "The lines numeration of document '%s' is not continues or the first line is not nº 1";
        $this->stack["document_line_duplicated"]                                 = "The document '%s' has reapeted line numbers";
        $this->stack["document_has_credit_and_debit_lines"]                      = "The document '%s' has debit and credit lines";
        $this->stack["document_line_no_quantity"]                                = "The document '%s' has lines without quantity";
        $this->stack["document_line_no_unit_price"]                              = "The document '%s' has lines without unit price";
        $this->stack["document_line_value_not_quantity_price"]                   = "The document '%s' at line '%s' the value UnitPrice * Quantity is not equals to line total";
        $this->stack["document_line_product_code_not_exit"]                      = "The document '%s' at line '%s' the ProductCoce '%s' does not exists";
        $this->stack["document_line_product_code_not_defined"]                   = "The document '%s' at line '%s' does not have to the ProductCode defined";
        $this->stack["customerID_not_exits"]                                     = "The 'CustomerID' '%s' of document '%s' does not exists int the customer table";
        $this->stack["supplierID_not_exits"]                                     = "The 'SupplierID' '%s' of document '%s' does not exists int the supplier table";
        $this->stack["customerID_not_defined_in_document"]                       = "The document '%s' does not have the 'CustomerID' setted";
        $this->stack["supplierID_not_defined_in_document"]                       = "The document '%s' does not have the 'SupplierID' setted";
        $this->stack["invoicetype_not_defined"]                                  = "The 'Invoice' '%s' does not have the type defined";
        $this->stack["paymenttype_not_defined"]                                  = "The 'Payment' '%s' does not have the type defined";
        $this->stack["workdoctype_not_defined"]                                  = "The 'WorkDocument' '%s' does not have the type defined";
        $this->stack["invoicetno_not_defined"]                                   = "'Invoice' Number not defined";
        $this->stack["paymentno_not_defined"]                                    = "'Payment' Number not defined";
        $this->stack["workdoc_number_not_defined"]                               = "'WorkDocument' Number not defined";
        $this->stack["stock_mov_number_not_defined"]                             = "'StockMovement' Number not defined";
        $this->stack["document_no_debit_or_credit"]                              = "O documento '%s' at line '%s' does not have debit or credit defined";
        $this->stack["document_must_be_debit_but_credit"]                        = "The document '%s' of type '%s' must be debit but is credit";
        $this->stack["document_must_be_credit_but_debit"]                        = "The document '%s' of type '%s' must be credit but is debit";
        $this->stack["document_has_cancel_lines_that_not_exist"]                 = "The document '%s' has cancel lines that the ProductCode not exists in the other lines";
        $this->stack["document_has_cancel_lines_with_greater_qt"]                = "The document '%s' have cancel lines with quantity greater than the sum of quantities";
        $this->stack["document_has_cancel_lines_with_greater_value"]             = "The document '%s' have cancel lines with value greater than the sum of values";
        $this->stack["document_correcting_line_without_refernces"]               = "The correcting document '%s' at line '%s' does not make refernce to the invoice that is correcting";
        $this->stack["reference_is_not_doc_valid"]                               = "In document '%s' at line '%s' the reference '%s' to the document that is correcting is not a document number valid";
        $this->stack["document_correcting_line_without_reason"]                  = "The correcting document '%s' at line '%s' does not make refernce to the reason of the correction";
        $this->stack["no_tax_entry_for_line_document"]                           = "No tax in tax table that matchs tah tax of line '%s' in document '%s'";
        $this->stack["document_currency_rate"]                                   = "In document '%s' the exchange rate multipied by the currency not match the 'GrossTotal'";
        $this->stack["salesinvoice_total_credit_should_be_zero"]                 = "The total credit of 'SalesInvoice' should be zero but is '%s'";
        $this->stack["salesinvoice_total_debit_should_be_zero"]                  = "The total debit of 'SalesInvoice' should be zero but is '%s'";
        $this->stack["workingdocuments_total_credit_should_be_zero"]             = "The total credit of 'WorkingDocuments' should be zero but is '%s'";
        $this->stack["workingdocuments_total_debit_should_be_zero"]              = "The total debit of 'WorkingDocuments' should be zero but is '%s'";
        $this->stack["order_reference_document_not_incicated"]                   = "The document '%s' at line '%s' does not make reference to the document of origin";
        $this->stack["order_reference_document_number_not_valid"]                = "The number of the document of order reference in document '%s' line '%s' is not valid";
        $this->stack["originatingon_document_number_not_valid"]                  = "The number of the originating in document '%s' line '%s' is not valid";
        $this->stack["order_reference_date_not_incicated"]                       = "The document '%s' at line '%s' does not have the date of the reference document";
        $this->stack["order_reference_date_later"]                               = "In document '%s' at line '%s' the date of the refernce origin can not be later that the date of this document";
        $this->stack["only_NC_and_ND_can_have_references"]                       = "The document '%s' is of type '%s' but only 'NC' and 'ND' can have 'References'";
        $this->stack["order_reference_not_for_NC_ND"]                            = "The document '%s' can not have 'OrderReferences' because it is '%s'";
        $this->stack["tax_iva_exception_code_or_reason_only_isent"]              = "The document '%s' has the tax exception code and/or the reason but the document is not free of tax";
        $this->stack["only_FR_FT_can_be_stockMovement"]                          = "On 'SalesInvoices' table only documents of type 'FT' or 'FR' can be used as stock movement document, the document '%s' is of the type '%s', can not have shipement data";
        $this->stack["document_to_be_stockMovement_must_heve_start_time"]        = "The document '%s' to be used as movement of goods have to have the movement start time";
        $this->stack["start_movement_can_not_be earliar_doc_date"]               = "The start movement date can not be earliar that the document '%s'";
        $this->stack["start_movement_can_not_be earliar_system_entry_date"]      = "The start movement date/time can not be earliar that the 'SystemEntryDate' of the document '%s'";
        $this->stack["end_movement_can_not_be earliar_start_movement"]           = "The date/time of the shipment end can not be ealier than of the start in document '%s'";
        $this->stack["document_to_be_stockMovement_must_have_shipfrom"]          = "The document to be used has stock movement '%s' have to have the ship from address";
        $this->stack["shipement_address_from_must_heve_city"]                    = "The ship from address in document '%s' does not have the city";
        $this->stack["shipement_address_from_must_heve_country"]                 = "The ship from address in document '%s' does not have the country";
        $this->stack["document_to_be_stockMovement_must_heve_shipto"]            = "The document to be used has stock movement '%s' have to have the ship to address";
        $this->stack["shipement_address_to_must_heve_city"]                      = "The ship to address in document '%s' does not have the city";
        $this->stack["shipement_address_to_must_heve_country"]                   = "The ship to address in document '%s' does not have the country";
        $this->stack["document_line_have_tax_base_with_unit_price_credit_debit"]
            = "The document '%s' at line '%s' has the 'TaxBase' defined, can not have the 'UnitPrice' and the 'CreditAmout'/'DebitAmout' with values higer than zero";
        $this->stack["document_status_not_defined"]                              = "The document '%s' does not have the 'DocumentStatus' defined";
        $this->stack["document_date_not_defined"]                                = "The document '%s' does not have the document date defined";
        $this->stack["document_status_date_earlier"]                             = "the date of document status of document '%s' is earlier that the document date";
        $this->stack["document_status_cancel_no_reason"]                         = "The status of document '%s' is canceled but does not have the reason defined";
        $this->stack["tax_must_have_type"]                                       = "The tax type is not defined on documemt '%s' line '%s'";
        $this->stack["tax_must_have_code"]                                       = "The tax code is not defined on documemt '%s' line '%s'";
        $this->stack["tax_must_have_region"]                                     = "The tax region is not defined on documemt '%s' line '%s'";
        $this->stack["tax_must_be_defined"]                                      = "In document '%s' at line '%s' the taxs must be defined";
        $this->stack["wrong_total_credit_of_invoices"]                           = "The value of 'TotalCredit' in 'SalesInvoice' is of '%s' but the calculated is '%s'";
        $this->stack["wrong_total_debit_of_invoices"]                            = "The value of 'TotalDebit' in 'SalesInvoice' is of '%s' but the calculated is '%s'";
        $this->stack["wrong_total_credit_of_workingdocuments"]                   = "The value of 'TotalCredit' in 'WorkingDocuments' is of '%s' but the calculated is '%s'";
        $this->stack["wrong_total_debit_of_workingdocuments"]                    = "The value of 'TotalDebit' in 'WorkingDocuments' is of '%s' but the calculated is '%s'";
        $this->stack["document_systementrydate_not_defined"]                     = "The document '%s' does not have the document 'SystemEntryDate' defined";
        $this->stack["doc_date_out_of_range_start_end_header_date"]              = "The document date '%s' is out of range of the start and end date defined in the 'Header'";
        $this->stack["doc_date_not_cheked_start_end_header_date"]                = "Can not check if the document date '%s' is out of range of the start and end date defined in the 'Header'";
        $this->stack["doc_date_earlier_previous_doc"]                            = "The document '%s' has date earlier than the previous document of the same serie";
        $this->stack["signature_not_valid"]                                      = "The digital signature hash of document '%s' is not valid";
        $this->stack["doc_systementrydate_earlier_previous_doc"]                 = "The 'SystemEntryDate' of document '%s' is earlier than precious document";
        $this->stack["document_without_lines"]                                   = "The document '%s' has no lines";
        $this->stack["shipfrom_delivery_date_later_shipto_delivery_date"]        = "The 'DeliveryDate' of document '%s' is later";
        $this->stack["stockmov_must_have_mov_start_time"]                        = "Stock movement document '%s' must have to have the 'MovementStartTime' defined";
        $this->stack["stockmov_end_time_earlier_start_time"]                     = "The stock movement document '%s' has the end of transport date/time earlier than start transport date/time";
        $this->stack["stockmov_mov_start_time_earlier_doc_date"]                 = "The stock movement document '%s' has the 'MovementStartTime' earlier than the document date";
        $this->stack["stockmov_mov_start_time_earlier_systementrydate"]          = "The stock movement document '%s' has the 'MovementStartTime' earlier than 'SystemEntryDate'";
        $this->stack["wrong_number_of_total_qt_issued"]                          = "The 'TotalQuantityIssued' in the movement of goods is '%s' but the calculated is '%s'";
        $this->stack["wrong_number_of_movement_lines"]                           = "The 'NumberOfMovementLines' in the movement of goods is '%s' but the calculated is '%s'";
        $this->stack["document_line_no_tax_defined"]                             = "The document '%s' at line '%s' does not have the field 'Tax' defined";
        $this->stack["mv_goods_total_qt_should_be_zero"]                         = "The 'TotalQuantityIssued' of 'MovementOfGoods' must be zero but is '%s'";
        $this->stack["mv_goods_num_lines_should_be_zero"]                        = "The 'NumberOfMovementLines' of 'MovementOfGoods' must be zero but is '%s'";
        $this->stack["no_shipto_only_in_global_doc_and_must_be_GT"]              = "The stock movement document '%s' does not have the 'ShipTo' defined, the global stock movement documents must be of type 'GT' and this document is of type '%s'";
        $this->stack["stockmov_can_not_be_cancel_after_movement_start"]          = "The document 'StockMovement' '%s' was canceled after the movement start 'MovementStartTime'";
        $this->stack["customerID_SupplierID_not_defined_in_document"]            = "The document 'StockMovement' '%s' does not have defined the 'CustomerID' or 'SupplierID'";
        $this->stack["customerID_and_supplierID_defined_in_document"]            = "The document 'StockMovement' '%s' has the 'CustomerID' nad the 'SupplierID' at same time";
        $this->stack["shipfrom_not_defined_in_stock_mov"]                        = "The document 'StockMovement' does not have the 'ShipFrom' definided";
        $this->stack["payment_cash_vat_without_tax"]                             = "The cash VAT schema '%s' in line '%s' does not has the TAX defined";
        $this->stack["payment_without_any_source_doc_id"]                        = "'Payment' '%s' in line '%s' does not have any 'SourceDocumentID' defined";
        $this->stack["originatingon_document_not_defined"]                       = "The 'Payment' '%s' does not have the 'OriginatingON' defined at line '%s'";
        $this->stack["payment_must_be_credit_document"]                          = "The 'Payment' '%s' has the debit value greater than credit";
        $this->stack["withholdingtax_greater_than_half_gross"]                   = "The document '%s' has the 'WithholdingTax' greater than half of the gross total";
        $this->stack["withholdingtax_greater_than_gross"]                        = "The document '%s' has the 'WithholdingTax' greater than the gross total";
        $this->stack["withholding_without_amout"]                                = "The document '%s' does not have the 'WithholdingTaxAmout'";
        $this->stack["paymentmethod_sum_not_equal_to_gross_less_tax"]            = "The document '%s' the sum of all 'PaymentMethod' less the 'WithholdingTax' is not equals to the gross total";
        $this->stack["paymentmethod_withou_payment_date"]                        = "The document '%s' does not have the 'PaymentDate' in 'PaymentMethod'";
        $this->stack["payment_withou_payment_method"]                            = "The document '%s' does not have 'PaymentMethod'";
        $this->stack["doc_systementrydate_earlier_previous_doc"]                 = "The document '%s' has the 'SystemEntryDate' earlier the previous document";
        $this->stack["doc_date_eaarlier_previous_doc"]                           = "The document '%s' has the date earlier the previous document";
        $this->stack["originatingon_document_repeated"]                          = "The document '%s' at line '%s' has the 'OriginatingOn' has reference to the same document";
        $this->stack["payment_settlement_sum_diff"]                              = "The settlement sum in document '%s' is wrong";
        $this->stack["wrong_total_credit_of_payments"]                           = "The 'TotalCredit' in 'Payments' is wrong";
        $this->stack["wrong_total_debit_of_payments"]                            = "The 'TotalDebit' in 'Payments' is wrong";
        $this->stack["wrong_number_of_payments"]                                 = "The 'NumberOfEntries' in 'Payments' is wrong";
        $this->stack["payments_total_debit_should_be_zero"]                      = "Payments total debit should be zero";
        $this->stack["payments_total_credit_should_be_zero"]                     = "Payments total credit should be zero";
        $this->stack["fr_withou_payment_method"]                                 = "The 'Invoice' '%s' of type 'Fatura-Recibo' without 'Payment'";
        $this->stack["paymentmethod_sum_greater_than_gross_lass_withholtax"] = "The sum of 'Payment' in document '%s' is greater than the gross total less the 'WithholdingTaxAmount'";
    }
}