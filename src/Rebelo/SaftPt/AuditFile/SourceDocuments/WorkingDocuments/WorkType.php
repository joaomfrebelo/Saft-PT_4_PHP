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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

/**
 * Description of WorkType
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType CM()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType CC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType FC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType FO()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType NE()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType OU()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType OR()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType PF()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType DC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType RP()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType RE()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType CS()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType LD()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType RA()
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkType extends \Rebelo\Enum\AEnum
{
    /**
     * “CM” – Table checks;
     * @since 1.0.0
     */
    const CM = "CM";

    /**
     * “CC” – Consignment credit note;
     * @since 1.0.0
     */
    const CC = "CC";

    /**
     * “FC” – Consignment invoice according to art. 38 of the Portuguese VAT Code;
     * @since 1.0.0
     */
    const FC = "FC";

    /**
     * “FO” – Worksheets [to record the service rendered or the work performed];
     * @since 1.0.0
     */
    const FO = "FO";

    /**
     * “NE” – Purchase order;
     * @since 1.0.0
     */
    const NE = "NE";

    /**
     * “OU” – Others [not specified in the remaining WorkTypes];
     * @since 1.0.0
     */
    const OU = "OU";

    /**
     * “OR” – Budgets;
     * @since 1.0.0
     */
    const OR = "OR";

    /**
     * “PF” – Pro forma invoice;
     * @since 1.0.0
     */
    const PF = "PF";

    /**
     * “DC” - Issued documents likely to be presented to the customer for the
     * purpose of checking goods or provision of services (for data until 2017-06-30).
     * @since 1.0.0
     */
    const DC = "DC";

    /**
     * “RP” – Premium or Premium receipt;
     * @since 1.0.0
     */
    const N_RP = "RP";

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
     * rance company
     * @since 1.0.0
     */
    const LD = "LD";

    /**
     * “RA” - Accepted reinsurance.
     * @since 1.0.0
     */
    const RA = "RA";

    /**
     * WorkType<br>
     * The field shall be filled in with:<br>
     * “CM” – Table checks;<br>
     * “CC” – Consignment credit note;<br>
     * “FC” – Consignment invoice according to art. 38 of the Portuguese VAT Code;<br>
     * “FO” – Worksheets [to record the service rendered or the work performed];<br>
     * “NE” – Purchase order;<br>
     * “OU” – Others [not specified in the remaining WorkTypes];<br>
     * “OR” – Budgets;<br>
     * “PF” – Pro forma invoice;<br>
     * “DC” - Issued documents likely to be presented to the customer for the purpose of checking goods or provision of services (for data until 2017-06-30).<br>
     * For the insurance sector as to the types of documents identified below must also exist in table 4.1. - SalesInvoices the corresponding invoice or invoice amending document, can also be filled with:<br>
     * “RP” – Premium or Premium receipt;<br>
     * “RE” - Return insurance or receipt of return insurance;<br>
     * “CS” - Imputation to co-insurance companies;<br>
     * “LD” - Imputation to a leader co-insurance company;<br>
     * “RA” - Accepted reinsurance.<br>
     *
     * &lt;xs:element name="WorkType"&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="CM"/&gt;
     *           &lt;xs:enumeration value="CC"/&gt;
     *           &lt;xs:enumeration value="FC"/&gt;
     *           &lt;xs:enumeration value="FO"/&gt;
     *           &lt;xs:enumeration value="NE"/&gt;
     *           &lt;xs:enumeration value="OU"/&gt;
     *           &lt;xs:enumeration value="OR"/&gt;
     *           &lt;xs:enumeration value="PF"/&gt;
     *           &lt;!-- Para para dados ate 2017-06-30--&gt;
     *           &lt;xs:enumeration value="DC"/&gt;
     *           &lt;!-- Para o sector segurador--&gt;
     *           &lt;xs:enumeration value="RP"/&gt;
     *           &lt;xs:enumeration value="RE"/&gt;
     *           &lt;xs:enumeration value="CS"/&gt;
     *           &lt;xs:enumeration value="LD"/&gt;
     *           &lt;xs:enumeration value="RA"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * @param string $value
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
