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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
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
 * @author João Rebelo
 * @since 3.0.0
 */
enum InvoiceType : string
{
    /**
     * “FT” - Invoice issued according to article 36 of the VAT code;
     *
     * @since 3.0.0
     */
    case FT = "FT";

    /**
     * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
     * @since 3.0.0
     */
    case FS = "FS";

    /**
     * “FR” – Invoice-receipt
     * @since 3.0.0
     */
    case FR = "FR";

    /**
     * “ND” - Debit note;
     * @since 3.0.0
     */
    case ND = "ND";

    /**
     * “NC” - Credit note
     * @since 3.0.0
     */
    case NC = "NC";

    /**
     * “VD” - Sale for cash and invoice/sales ticket; (a)
     * @since 3.0.0
     */
    case VD = "VD";

    /**
     * “TV” - Sale ticket; (a)
     * @since 3.0.0
     */
    case TV = "TV";

    /**
     * “TD” - Devolution ticket; (a)
     * @since 3.0.0
     */
    case TD = "TD";

    /**
     * “AA” - Assets sales; (a)
     * @since 3.0.0
     */
    case AA = "AA";

    /**
     * “DA” - Assets returns. (a) For the Insurance sector when it must not
     * be included in table 4.3. - WorkingDocuments
     * @since 3.0.0
     */
    case DA = "DA";

    /**
     * “RP” – Premium or premium receipt;
     * @since 3.0.0
     */
    case RP = "RP";

    /**
     * “RE” - Return insurance or receipt of return insurance;
     * @since 3.0.0
     */
    case RE = "RE";

    /**
     * “CS” - Imputation to co-insurance companies;
     * @since 3.0.0
     */
    case CS = "CS";

    /**
     * “LD” - Imputation to a leader co-insurance company
     * @since 3.0.0
     */
    case LD = "LD";

    /**
     * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
     * @since 3.0.0
     */
    case RA = "RA";

}
