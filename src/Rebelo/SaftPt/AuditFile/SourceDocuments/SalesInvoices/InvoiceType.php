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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

/**
 * InvoiceType<br>
 * The field shall be filled in with:
 * “FT” - Invoice;
 * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
 * “FR” – Invoice-receipt;
 * “ND” - Debit note;
 * “NC” - Credit note;
 * “VD” - Sale for cash and invoice/sales ticket; (a)
 * “TV” - Sale ticket; (a)
 * “TD” - Devolution ticket; (a)
 * “AA” - Assets sales; (a)
 * “DA” - Assets returns. (a) For the Insurance sector when it must not be included in table 4.3. - WorkingDocuments, may also be filled in with:
 * “RP” – Premium or premium receipt;
 * “RE” - Return insurance or receipt of return insurance;
 * “CS” - Imputation to co-insurance companies;
 * “LD” - Imputation to a leader co-insurance company;
 * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType FT()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType FS()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType FR()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType ND()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType NC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType VD()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType TV()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType TD()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType AA()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType DA()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType RP()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType RE()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType CS()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType LD()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType RA()
 * @author João Rebelo
 * @since 1.0.0
 */
class InvoiceType extends \Rebelo\Enum\AEnum
{
    /**
     * “FT” - Invoice issued according to article 36 of the VAT code;
     * do Codigo do IVA
     * @since 1.0.0
     */
    const FT = "FT";

    /**
     * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
     * @since 1.0.0
     */
    const FS = "FS";

    /**
     * “FR” – Invoice-receipt
     * @since 1.0.0
     */
    const FR = "FR";

    /**
     * “ND” - Debit note;
     * @since 1.0.0
     */
    const ND = "ND";

    /**
     * “NC” - Credit note
     * @since 1.0.0
     */
    const NC = "NC";

    /**
     * “VD” - Sale for cash and invoice/sales ticket; (a)
     * @since 1.0.0
     */
    const VD = "VD";

    /**
     * “TV” - Sale ticket; (a)
     * @since 1.0.0
     */
    const TV = "TV";

    /**
     * “TD” - Devolution ticket; (a)
     * @since 1.0.0
     */
    const TD = "TD";

    /**
     * “AA” - Assets sales; (a)
     * @since 1.0.0
     */
    const AA = "AA";

    /**
     * “DA” - Assets returns. (a) For the Insurance sector when it must not
     * be included in table 4.3. - WorkingDocuments
     * @since 1.0.0
     */
    const DA = "DA";

    /**
     * “RP” – Premium or premium receipt;
     * @since 1.0.0
     */
    const RP = "RP";

    /**
     * “RE” - Return insurance or receipt of return insurance;
     * @since 1.0.0
     */
    const RE = "RE";

    /**
     * “CS” - Imputation to co-insurance companies;
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * “LD” - Imputation to a leader co-insurance company
     * @since 1.0.0
     */
    const LD = "LD";

    /**
     * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
     * @since 1.0.0
     */
    const RA = "RA";

    /**
     * InvoiceType<br>
     * The field shall be filled in with:
     * “FT” - Invoice;
     * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
     * “FR” – Invoice-receipt;
     * “ND” - Debit note;
     * “NC” - Credit note;
     * “VD” - Sale for cash and invoice/sales ticket; (a)
     * “TV” - Sale ticket; (a)
     * “TD” - Devolution ticket; (a)
     * “AA” - Assets sales; (a)
     * “DA” - Assets returns. (a) For the Insurance sector when it must not
     * be included in table 4.3. - WorkingDocuments, may also be filled in with:
     * “RP” – Premium or premium receipt;
     * “RE” - Return insurance or receipt of return insurance;
     * “CS” - Imputation to co-insurance companies;
     * “LD” - Imputation to a leader co-insurance company;
     * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
     * @param string $value
     * @return string
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get enum value
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}