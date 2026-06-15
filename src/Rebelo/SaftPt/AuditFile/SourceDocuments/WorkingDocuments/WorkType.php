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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

/**
 * Description of WorkType
 * @author João Rebelo
 * @since 3.0.0
 */
enum WorkType : string
{
    /**
     * “CM” – Table checks;
     * @since 3.0.0
     */
    case CM = "CM";

    /**
     * “CC” – Consignment credit note;
     * @since 3.0.0
     */
    case CC = "CC";

    /**
     * “FC” – Consignment invoice according to art. 38 of the Portuguese VAT Code;
     * @since 3.0.0
     */
    case FC = "FC";

    /**
     * “FO” – Worksheets [to record the service rendered or the work performed];
     * @since 3.0.0
     */
    case FO = "FO";

    /**
     * “NE” – Purchase order;
     * @since 3.0.0
     */
    case NE = "NE";

    /**
     * “OU” – Others [not specified in the remaining WorkTypes];
     * @since 3.0.0
     */
    case OU = "OU";

    /**
     * “OR” – Budgets;
     * @since 3.0.0
     */
    case OR = "OR";

    /**
     * “PF” – Pro forma invoice;
     * @since 3.0.0
     */
    case PF = "PF";

    /**
     * “DC” - Issued documents likely to be presented to the customer for the
     * purpose of checking goods or provision of services (for data until 2017-06-30).
     * @since 3.0.0
     */
    case DC = "DC";

    /**
     * “RP” – Premium or Premium receipt;
     * @since 3.0.0
     */
    case N_RP = "RP";

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
     * rance company
     * @since 3.0.0
     */
    case LD = "LD";

    /**
     * “RA” - Accepted reinsurance.
     * @since 3.0.0
     */
    case RA = "RA";

}
