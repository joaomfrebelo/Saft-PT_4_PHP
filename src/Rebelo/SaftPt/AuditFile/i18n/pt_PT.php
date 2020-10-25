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
 * Description of pt_PT
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class pt_PT extends AI18n
{

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->stack["no_header_table"]                                          = "'Header' não exportado";
        $this->stack["no_master_files_table"]                                    = "Tabela 'MasterFiles' não exportada";
        $this->stack["company_id_not_valid"]                                     = "Campo 'CompanyID' não válido";
        $this->stack["TaxRegistrationNumber_not_valid_header"]                   = "O campo 'TaxRegistrationNumber' no 'Header' não é válido";
        $this->stack["invalid_header_companyname"]                               = "'CompanyName' no 'Header' não válido";
        $this->stack["invalid_BusinessName"]                                     = "'BusinessName' não é válido";
        $this->stack["FiscalYear_not_valid"]                                     = "'FiscalYear' não é válido";
        $this->stack["StartDate_not_valid"]                                      = "'StartDate' não é válido";
        $this->stack["EndDate_not_valid"]                                        = "'EndDate' não é válido";
        $this->stack["DateCreated_not_valid"]                                    = "'DateCreated' não é válido";
        $this->stack["TaxEntity_not_valid"]                                      = "'TaxEntity' não é válido";
        $this->stack["ProductCompanyTaxID_not_valid"]                            = "'ProductCompanyTaxID'  não é válido";
        $this->stack["SoftwareCertificateNumber_not_valid"]                      = "'SoftwareCertificateNumber' não ´eválido";
        $this->stack["ProductID_not_valid"]                                      = "'ProductID' não é válido";
        $this->stack["ProductVersion_not_valid"]                                 = "'ProductVersion' não é válido";
        $this->stack["HeaderComment_not_valid"]                                  = "'HeaderComment' não é válido";
        $this->stack["Telephone_Header_not_valid"]                               = "'Telephone' no 'Header' não é válido";
        $this->stack["Fax_Header_not_valid"]                                     = "'Fax' no 'Header' não é válido";
        $this->stack["Email_Header_not_valid"]                                   = "'Email' no 'Header' não é válido";
        $this->stack["Website_Header_not_valid"]                                 = "'Website' no 'Header' não é válido";
        $this->stack["TaxAccountingBasis_is_not_setted"]                         = "'TaxAccountingBasis' não está definido";
        $this->stack["CompanyAddress_is_not_setted"]                             = "'CompanyAddress' não está definido";
        $this->stack["BuildingNumber_not_valid"]                                 = "'BuildingNumber' não é válido";
        $this->stack["StreetName_not_valid"]                                     = "'StreetName' não é válido";
        $this->stack["AddressDetail_not_valid"]                                  = "'AddressDetail' não é válido";
        $this->stack["City_not_valid"]                                           = "'City' não é válido";
        $this->stack["Region_not_valid"]                                         = "'Region' não é válido";
        $this->stack["PostalCode_not_valid"]                                     = "'PostalCode' não é válido";
        $this->stack["Country_not_valid"]                                        = "'Country' não é válido";
        $this->stack["CustomerID_not_valid"]                                     = "'CustomerID' não é válido";
        $this->stack["CustomerTaxID_not_valid"]                                  = "'CustomerTaxID' não é válido";
        $this->stack["CompanyName_not_valid"]                                    = "'CompanyName' não é válido";
        $this->stack["Telephone_not_valid"]                                      = "'Telephone' não é válido";
        $this->stack["Fax_not_valid"]                                            = "'Fax' não é válido";
        $this->stack["Email_not_valid"]                                          = "'Email' não é válido";
        $this->stack["Website_not_valid"]                                        = "'Website' não é válido";
        $this->stack["BillingAddress_not_valid"]                                 = "'BillingAddress' não é válido";
        $this->stack["SelfBillingIndicator_not_valid"]                           = "'SelfBillingIndicator' não é válido";
        $this->stack["SupplierID_not_valid"]                                     = "'SupplierID' não é válido";
        $this->stack["SupplierTaxID_not_valid"]                                  = "'SupplierTaxID' não é válido";
        $this->stack["UNNumber_not_valid"]                                       = "'UNNumber' não é válido";
        $this->stack["CNCode_not_valid"]                                         = "'CNCode' não é válido";
        $this->stack["ProductCode_not_valid"]                                    = "'ProductCode' não é válido";
        $this->stack["ProductGroup_not_valid"]                                   = "'ProductGroup' não é válido";
        $this->stack["ProductDescription_not_valid"]                             = "'ProductDescription' não é válido";
        $this->stack["ProductNumberCode_not_valid"]                              = "'ProductNumberCode' não é válido";
        $this->stack["TaxEntry_Description_not_valid"]                           = "'Description' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_TaxPercentage_not_valid"]                         = "'TaxPercentage' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_TaxAmount_not_valid"]                             = "'TaxAmount' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_TaxType_not_valid"]                               = "'TaxType' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_CountryRegion_not_valid"]                         = "'CountryRegion' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_TaxCode_not_valid"]                               = "'TaxCode' em 'TaxEntry' não válido";
        $this->stack["TaxEntry_TaxAmount_and_Percentage_setted"]                 = "'TaxAmount' e 'TaxPercentage' em 'TaxEntry' definido em simultanio";
        $this->stack["JournalID_not_valid"]                                      = "'JournalID' não é válido";
        $this->stack["DocArchivalNumber_not_valid"]                              = "'DocArchivalNumber' não é válido";
        $this->stack["TransactionID_not_valid"]                                  = "'TransactionID' não é válido";
        $this->stack["TaxAmount_and_Percentage_setted"]                          = "'TaxAmount' e 'TaxPercentage' definido em simultanio";
        $this->stack["TaxPercentage_not_valid"]                                  = "'TaxPercentage' não é válido";
        $this->stack["TaxAmount_not_valid"]                                      = "'TaxAmount' não é válido";
        $this->stack["WarehouseID_not_valid"]                                    = "'WarehouseID' não é válido";
        $this->stack["WithholdingTaxDescription_not_valid"]                      = "'WithholdingTaxDescription' não é válido";
        $this->stack["WithholdingTaxAmount_not_valid"]                           = "'WithholdingTaxAmount' não é válido";
        $this->stack["DeliveryID_not_valid"]                                     = "'DeliveryID' não é válido";
        $this->stack["Reference_not_valid"]                                      = "'Reference' não é válido";
        $this->stack["SerialNumber_not_valid"]                                   = "'SerialNumber' não é válido";
        $this->stack["PaymentAmount_not_valid"]                                  = "'PaymentAmount' não é válido";
        $this->stack["OriginatingON_not_valid"]                                  = "'OriginatingON' não é válido";
        $this->stack["ARCNo_not_valid"]                                          = "'ARCNo' não é válido";
        $this->stack["IecAmount_not_valid"]                                      = "'IecAmount' não é válido";
        $this->stack["CurrencyAmount_not_valid"]                                 = "'CurrencyAmount' não é válido";
        $this->stack["ExchangeRate_not_valid"]                                   = "'ExchangeRate' não é válido";
        $this->stack["CurrencyCode_not_valid"]                                   = "'CurrencyCode' não é válido";
        $this->stack["TaxBase_not_valid"]                                        = "'TaxBase' não é válido";
        $this->stack["TaxPointDate_not_valid"]                                   = "'TaxPointDate' não é válido";
        $this->stack["Description_not_valid"]                                    = "'Description' não é válido";
        $this->stack["TaxPayable_not_valid"]                                     = "'TaxPayable' não é válido";
        $this->stack["NetTotal_not_valid"]                                       = "'NetTotal' não é válido";
        $this->stack["Atcud_not_valid"]                                          = "'Atcud' não é válido";
        $this->stack["Hash_not_valid"]                                           = "'Hash' não é válido";
        $this->stack["Period_not_valid"]                                         = "'Period' não é válido";
        $this->stack["EacCode_not_valid"]                                        = "'EacCode' não é válido";
        $this->stack["Quantity_not_valid"]                                       = "'Quantity' não é válido";
        $this->stack["UnitOfMeasure_not_valid"]                                  = "'UnitOfMeasure' não é válido";
        $this->stack["UnitOfMeasure_not_valid"]                                  = "'UnitPrice' não é válido";
        $this->stack["LineNumber_not_valid"]                                     = "'LineNumber' não é válido";
        $this->stack["DebitAmount_not_valid"]                                    = "'DebitAmount' não é válido";
        $this->stack["CreditAmount_not_valid"]                                   = "'CreditAmount' não é válido";
        $this->stack["TaxExemptionReason_not_valid"]                             = "'TaxExemptionReason' não é válido";
        $this->stack["SettlementAmount_not_valid"]                               = "'SettlementAmount' não é válido";
        $this->stack["Debit_and_Credit_setted_at_same_time"]                     = "'Debit' e 'Credit' definidos em simultanio";
        $this->stack["No_Debit_or_Credit_setted"]                                = "No 'Debit' or 'Credit' definidos";
        $this->stack["SourceID_not_valid"]                                       = "'SourceID' não é válido";
        $this->stack["WorkStatus_not_valid"]                                     = "'WorkStatus' não é válido";
        $this->stack["WorkStatusDate_not_valid"]                                 = "'WorkStatusDate' não é válido";
        $this->stack["SourceBilling_not_valid"]                                  = "'SourceBilling' não é válido";
        $this->stack["DocumentNumber_not_valid"]                                 = "'DocumentNumber' não é válido";
        $this->stack["Atcud_not_valid"]                                          = "'Atcud' não é válido";
        $this->stack["DocumentStatus_not_valid"]                                 = "'DocumentStatus' não é válido";
        $this->stack["DocumentTotals_not_valid"]                                 = "'DocumentTotals' não é válido";
        $this->stack["WorkDate_not_valid"]                                       = "'WorkDate' não é válido";
        $this->stack["WorkType_not_valid"]                                       = "'WorkType' não é válido";
        $this->stack["SystemEntryDate_not_valid"]                                = "'SystemEntryDate' não é válido";
        $this->stack["WorkDocument_without_lines"]                               = "'WorkDocument' sem linhas";
        $this->stack["NumberOfEntries_not_valid"]                                = "'NumberOfEntries' não é válido";
        $this->stack["TotalDebit_not_valid"]                                     = "'TotalDebit' não é válido";
        $this->stack["TotalCredit_not_valid"]                                    = "'TotalCredit' não é válido";
        $this->stack["InvoiceStatus_not_valid"]                                  = "'InvoiceStatus' não é válido";
        $this->stack["InvoiceStatusDate_not_valid"]                              = "'InvoiceStatusDate' não é válido";
        $this->stack["SettlementDiscount_not_valid"]                             = "'SettlementDiscount' não é válido";
        $this->stack["PaymentTerms_not_valid"]                                   = "'PaymentTerms' não é válido";
        $this->stack["InvoiceNo_not_valid"]                                      = "'InvoiceNo' não é válido";
        $this->stack["InvoiceDate_not_valid"]                                    = "'InvoiceDate' não é válido";
        $this->stack["InvoiceType_not_valid"]                                    = "'InvoiceType' não é válido";
        $this->stack["SpecialRegimes_not_valid"]                                 = "'SpecialRegimes' não é válido";
        $this->stack["Invoice_without_lines"]                                    = "'Invoice' sem linhas";
        $this->stack["Reason_not_valid"]                                         = "'Reason' não é válido";
        $this->stack["PaymentStatus_not_valid"]                                  = "'PaymentStatus' não é válido";
        $this->stack["PaymentStatusDate_not_valid"]                              = "'PaymentStatusDate' não é válido";
        $this->stack["SourcePayment_not_valid"]                                  = "'SourcePayment' não é válido";
        $this->stack["SourceDocumentID_without_elements"]                        = "'SourceDocumentID' sem elementos";
        $this->stack["PaymentRefNo_not_valid"]                                   = "'PaymentRefNo' não é válido";
        $this->stack["SystemID_not_valid"]                                       = "'SystemID' não é válido";
        $this->stack["PaymentType_not_valid"]                                    = "'PaymentType' não é válido";
        $this->stack["TaxType_not_valid"]                                        = "'TaxType' não é válido";
        $this->stack["TaxCountryRegion_not_valid"]                               = "'TaxCountryRegion' não é válido";
        $this->stack["TaxCode_not_valid"]                                        = "'TaxCode' não é válido";
        $this->stack["CustomerID_and_SupplierID_at_same_time"]                   = "'CustomerID' e 'SupplierID' definido em simultâneo";
        $this->stack["CustomerID_and_SupplierID_not_setted"]                     = "'CustomerID' e 'SupplierID' não definidos";
        $this->stack["AtDocCodeID_not_valid"]                                    = "'AtDocCodeID' não é válido";
        $this->stack["StockMovement_without_lines"]                              = "'StockMovement' sem linhas";
        $this->stack["NumberOfMovementLines_not_valid"]                          = "'NumberOfMovementLines' não é válido";
        $this->stack["TotalQuantityIssued_not_valid"]                            = "'TotalQuantityIssued' não é válido";
        $this->stack["duplicated_invoice"]                                       = "Entrada duplicada para a 'Invoice' '%s'";
        $this->stack["duplicated_stock_mov"]                                     = "Entrada duplicada para o 'StockMovement' '%s'";
        $this->stack["duplicated_workdoc"]                                       = "Entrada duplicada para o 'WorkDocument' '%s'";
        $this->stack["invoice_at_index_no_number"]                               = "A 'Invoice' no indice '%s' não tem o número de documento";
        $this->stack["workdoc_at_index_no_number"]                               = "O 'WorkDocument' no indice '%s' não tem o número de documento";
        $this->stack["stock_move_at_index_no_number"]                            = "O 'StockMovement' no indice '%s' não tem o número de documento";
        $this->stack["wrong_number_of_invoices"]                                 = "O número de 'Invoice' está errado, é esperado '%s' mas apenas existem 's%";
        $this->stack["wrong_number_of_workdocuments"]                            = "O número de 'WorkDocument' está errado, é esperado '%s' mas apenas existem 's%";
        $this->stack["does_not_have_hash"]                                       = "O documento '%s' não tem o hash da assinatura";
        $this->stack["is_valid_only_if_is_not_first_of_serie"]                   = "A assinatura do documento '%s' poderá ser válida se não for o primeiro documento da série";
        $this->stack["tax_iva_must_have_percentage"]                             = "O tipo de imposto do documento '%s' é IVA, a percentagem de imposto tem que ser definida";
        $this->stack["tax_zero_must_have_code_and_reason"]                       = "O documento '%s' tem a percentagem/valor de imposto zero mas não indica o código de excepção e/ou a razão";
        $this->stack["tax_iva_code_ise_must_have_code_and_reason"]               = "O documento '%s' tem o código de IVA isento mas não indica o código de excepção e/ou a razão";
        $this->stack["does_not_have_document_totals"]                            = "O documento '%s' não tem os totais definidos";
        $this->stack["document_gross_not_equal_tax_plus_net"]                    = "A soma do NetTotal com o TaxPayable do documento '%s' não corresponde ao valor do TotalGross";
        $this->stack["document_gross_not_equal_calc_gross"]                      = "O GrossTotal do documento '%s' não corresponde ao GrossTotal do documento";
        $this->stack["document_nettotal_not_equal_calc_nettotal"]                = "O NetTotal do documento '%s' não corresponde ao NetTotal do documento";
        $this->stack["document_taxpayable_not_equal_calc_taxpayable"]            = "O TaxPayable do documento '%s' não corresponde ao TaxPayable do documento";
        $this->stack["document_line_no_number"]                                  = "O documento '%s' tem linhas sem numeração";
        $this->stack["document_line_no_continues"]                               = "A numeração das linhas do documento '%s' não é continua ou não inicia na linha nº 1";
        $this->stack["document_line_duplicated"]                                 = "O documento '%s' tem linhas com numeração repetida";
        $this->stack["document_has_credit_and_debit_lines"]                      = "O documento tem linhas a débito e a crédito";
        $this->stack["document_line_no_quantity"]                                = "O documento '%s' tem linhas sem a quantidade definida";
        $this->stack["document_line_no_unit_price"]                              = "O documento '%s' tem linhas sem o peço unitátio definido";
        $this->stack["document_line_value_not_quantity_price"]                   = "O doumento '%s' o total da linha '%s' não corresponde ao UnitPrice * Quantity ";
        $this->stack["document_line_product_code_not_exist"]                     = "O documento '%s' na linha '%s' o  ProductCoce '%s' não existe";
        $this->stack["document_line_product_code_not_defined"]                   = "O documento '%s' na linha '%s' não tem o ProductCoce definido";
        $this->stack["customerID_not_defined_in_document"]                       = "O documento '%s' não tem o CustomerID definido";
        $this->stack["invoicetype_not_defined"]                                  = "A 'Invoice' '%s' não tem o tipo definido";
        $this->stack["workdoctype_not_defined"]                                  = "O 'WorkDocument' '%s' não tem o tipo definido";
        $this->stack["customerID_not_exits"]                                     = "O 'CustomerID' '%s' do documento '%s' não existe na tabela de clientes";
        $this->stack["supplierID_not_exits"]                                     = "O 'SupplierID' '%s' do documento '%s' não existe na tabela de fornecedores";
        $this->stack["invoicetno_not_defined"]                                   = "Número de 'Invoice' não definido";
        $this->stack["workdocument_number_not_defined"]                          = "Número de 'WorkDocument' não definido";
        $this->stack["document_no_debit_or_credit"]                              = "O documento '%s' na linha não tem débitos nem créditos definidos";
        $this->stack["document_must_be_debit_but_credit"]                        = "O documento '%s' do tipo '%s' tem de ser a débito mas está a crédito";
        $this->stack["document_must_be_credit_but_debit"]                        = "O documento '%s' do tipo '%s' tem de ser a crédito mas está a débito";
        $this->stack["document_has_cancel_lines_that_not_exist"]                 = "O documento '%s' tem linhas de anulação cujo o artigo não existe nas outras linhas";
        $this->stack["document_has_cancel_lines_with_grater_qt"]                 = "O documento '%s' tem linhas de anulação com quantidade superior a soma das quantidades";
        $this->stack["document_has_cancel_lines_with_grater_value"]              = "O documento '%s' tem linhas de anulação com valor superior a soma dos valores";
        $this->stack["document_correcting_line_without_refernces"]               = "O documento rectificativo '%s' na linha '%s' não faz referência à fatura de rectifica";
        $this->stack["reference_is_not_doc_valid"]                               = "No documento '%s' linha '%s' a referncia '%s' ao documento que retifica não é um número de documento válido";
        $this->stack["document_correcting_line_without_reason"]                  = "O documento rectificativo '%s' na linha '%s' não faz referência à razão da rectificação";
        $this->stack["no_tax_entry_for_line_document"]                           = "Nenhum imposto na tabela de imposto que corresponda ao imposto da linha '%s' do documento '%s'";
        $this->stack["document_currency_rate"]                                   = "No documento '%s' a taxa de cambio multiplicado pelo valor em moeda estrangeira não correspomde ao 'GrossTotal'";
        $this->stack["salesinvoice_total_credit_should_be_zero"]                 = "O total de credito do 'SalesInvoice' deveria ser zero mas é de '%s'";
        $this->stack["salesinvoice_total_debit_should_be_zero"]                  = "O total de debito do 'SalesInvoice' deveria ser zero mas é de '%s'";
        $this->stack["workingdocuments_total_credit_should_be_zero"]             = "O total de credito do 'WorkingDocuments' deveria ser zero mas é de '%s'";
        $this->stack["workingdocuments_total_debit_should_be_zero"]              = "O total de debito do 'WorkingDocuments' deveria ser zero mas é de '%s'";
        $this->stack["order_reference_document_not_incicated"]                   = "O documento '%s' na linha '%s' não faz referência ao número de documento que lhe deu origem";
        $this->stack["order_reference_document_number_not_valid"]                = "O Numero do documento de referência de origem no documento '%s' na linha '%s' não é válido";
        $this->stack["order_reference_date_not_incicated"]                       = "No documento '%s' na linha '%s' não tem a data do documento de origem";
        $this->stack["order_reference_date_later"]                               = "No documento '%s' na linha '%s' a data do documento de origem não pode ser posteriro à data deste documento";
        $this->stack["only_NC_and_ND_can_have_references"]                       = "O documento '%s' é do tipo '%s' mas só 'NC' e 'ND' é que podem ter 'References'";
        $this->stack["order_reference_not_for_NC_ND"]                            = "O documento '%s' não pode ter 'OrderReferences' por ser '%s'";
        $this->stack["tax_iva_exception_code_or_reason_only_isent"]              = "O documento '%s' tem o código de excepção de imposto e/ou a razão sem que o documento seja isento";
        $this->stack["only_FR_FT_can_be_stockMovement"]                          = "Na tabela 'SalesInvoices' apenas os documentos do tipo 'FT' ou 'FR' podem ser usados como guias de transporte, o docuemnto '%s' é do tipo '%s', não pode ter dados de tarnsporte definido";
        $this->stack["document_to_be_stockMovement_must_heve_start_time"]        = "O documento '%s' para serem usado como guia de transporte tem que ter data de inicio do transporte";
        $this->stack["start_movement_can_not_be earliar_doc_date"]               = "A data de inicio de transporte não pode ser inferior à data do documento '%s'";
        $this->stack["start_movement_can_not_be earliar_system_entry_date"]      = "A data/hora de inicio de transporte não pode ser inferior à data do 'SystemEntryDate' do documento '%s'";
        $this->stack["end_movement_can_not_be earliar_start_movement"]           = "A data/hora do fim de transporte não pode ser inferior à do inicio do transporte no documento '%s'";
        $this->stack["document_to_be_stockMovement_must_have_shipfrom"]          = "O documento '%s' para ser usado como guia de transporte tem que ter morada de origem do envio";
        $this->stack["shipement_address_from_must_heve_city"]                    = "A morada do local de origem do envio não contém a cidade no documento '%s'";
        $this->stack["shipement_address_from_must_heve_country"]                 = "A morada do local de origem envio não contém o país no documento '%s'";
        $this->stack["document_to_be_stockMovement_must_heve_shipto"]            = "O documento '%s' para ser usado como guia de transporte tem que ter morada de destino";
        $this->stack["shipement_address_to_must_heve_city"]                      = "A morada do local de destino não contém a cidade no documento '%s'";
        $this->stack["shipement_address_to_must_heve_country"]                   = "A morada do local de destino não contém o país no documento '%s'";
        $this->stack["document_line_have_tax_base_with_unit_price_credit_debit"]
            = "O documento '%s' na linha '%s' tem o 'TaxBase' definido, não pode ter o 'UnitPrice' e os 'CreditAmout'/'DebitAmout' com valores superiores a zero";
        $this->stack["document_status_not_defined"]                              = "O documento '%s' não tem o 'DocumentStatus' definido";
        $this->stack["document_date_not_defined"]                                = "O documento '%s' não tem a data do documento definido";
        $this->stack["document_status_date_earlier"]                             = "A data do estado do documento no documento '%s' é anteriro à data do documento";
        $this->stack["document_status_cancel_no_reason"]                         = "O estado do documento '%s' é anulado mas não tem a razão definida";
        $this->stack["tax_must_have_type"]                                       = "O tipo de imposto não está definido no documento '%s' linha '%s'";
        $this->stack["tax_must_have_code"]                                       = "O código de imposto não está definido no documento '%s' linha '%s'";
        $this->stack["tax_must_have_region"]                                     = "A região de imposto não está definida no documento '%s' linha '%s'";
        $this->stack["tax_must_be_defined"]                                      = "No documento '%s' linha '%s' os impostos tem que estar definidos";
        $this->stack["wrong_total_credit_of_invoices"]                           = "O valor de 'TotalCredit' no 'SalesInvoice' é de '%s' mas o calculado é de '%s'";
        $this->stack["wrong_total_credit_of_workingdocuments"]                   = "O valor de 'TotalCredit' no 'WorkingDocuments' é de '%s' mas o calculado é de '%s'";
        $this->stack["wrong_total_debit_of_invoices"]                            = "O valor de 'TotalDebit' no 'SalesInvoice' é de '%s' mas o calculado é de '%s'";
        $this->stack["wrong_total_debit_of_workingdocuments"]                    = "O valor de 'TotalDebit' no 'WorkingDocuments' é de '%s' mas o calculado é de '%s'";
        $this->stack["document_systementrydate_not_defined"]                     = "O documento '%s' não tem o 'SystemEntryDate' do documento definido";
        $this->stack["doc_date_out_of_range_start_end_header_date"]              = "A data do documento '%s' está fora do intervalo de inicio e fim definido no 'Header'";
        $this->stack["doc_date_not_cheked_start_end_header_date"]                = "Não é possivel verificar se a data do documento '%s' está fora do intervalo de inicio e fim definido no 'Header'";
        $this->stack["doc_date_earlier_previous_doc"]                            = "O documento '%s' tem data anterior ao documento anterior da mesma série";
        $this->stack["signature_not_valid"]                                      = "O hash da assinatura digital do documnto'%s' não é válida";
        $this->stack["doc_systementrydate_earlier_previous_doc"]                 = "O 'SystemEntryDate' d documento '%s' é anterior ao do último documento";
        $this->stack["document_without_lines"]                                   = "O documento '%s' não tem linhas";
        $this->stack["shipfrom_delivery_date_later_shipto_delivery_date"]        = "O 'DeliveryDate' do documento '%s' é posterior";
        $this->stack["stockmov_must_have_mov_start_time"]                        = "O documento de transporte '%s' têm que ter o campo 'MovementStartTime' definido";
        $this->stack["stockmov_end_time_earlier_start_time"]                     = "O documento de transporte '%s' tem a data/hora de fim de transporte anterior à data de inicio de transporte";
        $this->stack["stockmov_mov_start_time_earlier_doc_date"]                 = "O documento de transporte '%s' tem a data de inicio de transporte anterior à de documemto";
        $this->stack["stockmov_mov_start_time_earlier_systementrydate"]          = "O documento de transporte '%s' tem a data de inicio de transporte anterior à de 'SystemEntryDate'";
        $this->stack["wrong_number_of_total_qt_issued"]                          = "O 'TotalQuantityIssued' nos documentos de tranporte é '%s' mas o calculado é de '%s'";
        $this->stack["wrong_number_of_movement_lines"]                           = "O 'NumberOfMovementLines' nos documentos de tranporte é '%s' mas o calculado é de '%s'";
        $this->stack["document_line_no_tax_defined"]                             = "O documento '%s' na linha '%s' não tem o campo 'Tax' definido";
        $this->stack["mv_goods_total_qt_should_be_zero"]                         = "O 'TotalQuantityIssued' no 'MovementOfGoods' deve ser zero e é '%s'";
        $this->stack["mv_goods_num_lines_should_be_zero"]                        = "O 'NumberOfMovementLines' no 'MovementOfGoods' deve ser zero e é '%s'";
    }
}