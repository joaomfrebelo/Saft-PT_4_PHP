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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

/**
 * MovementStatus<br>
 * The field must be filled in with:<br>
 * “N” - Normal;<br>
 * “T” - On behalf of third parties;<br>
 * “A” - Cancelled document;<br>
 * “F” – Billed document, even if partially, when for the same document there is
 * also on table 4.1. – SalesInvoices the corresponding invoice or simplified invoice;<br>
 * “R” - Summary document for other documents created in other applications and
 * generated in this application.<br>
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus N()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus T()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus A()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus F()
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementStatus extends \Rebelo\Enum\AEnum
{
    /**
     * “N” - Normal
     * @since 1.0.0
     */
    const N = "N";

    /**
     * “T” - On behalf of third parties
     * @since 1.0.0
     */
    const T = "T";

    /**
     * “A” - Cancelled document
     * @since 1.0.0
     */
    const A = "A";

    /**
     * “F” – Billed document, even if partially, when for the same document there is
     * also on table 4.1. – SalesInvoices the corresponding invoice or simplified invoice
     * @since 1.0.0
     */
    const F = "F";

    /**
     * “R” - Summary document for other documents created in other applications and
     * generated in this application
     * @since 1.0.0
     */
    const R = "R";

    /**
     * MovementStatus<br>
     * The field must be filled in with:<br>
     * “N” - Normal;<br>
     * “T” - On behalf of third parties;<br>
     * “A” - Cancelled document;<br>
     * “F” – Billed document, even if partially, when for the same document there is
     * also on table 4.1. – SalesInvoices the corresponding invoice or simplified invoice;<br>
     * “R” - Summary document for other documents created in other applications and
     * generated in this application.<br>
     * <pre>
     * &lt;xs:element name="MovementStatus"&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="N"/&gt;
     *           &lt;xs:enumeration value="T"/&gt;
     *           &lt;xs:enumeration value="A"/&gt;
     *           &lt;xs:enumeration value="F"/&gt;
     *           &lt;xs:enumeration value="R"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the value as string
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}