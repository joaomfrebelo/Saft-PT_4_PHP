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

use Rebelo\Enum\AEnum;
use Rebelo\Enum\EnumException;

/**
 * WorkStatus
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus N()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus A()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus F()
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkStatus extends AEnum
{
    /**
     * “N” - Normal
     * @since 1.0.0
     */
    const N = "N";

    /**
     * “A” - Cancelled document
     * @since 1.0.0
     */
    const A = "A";

    /**
     * “F” - Billed document, even if partially,
     * when for the same document there is also on table 4.1. – SalesInvoices,
     * the corresponding invoice or simplified invoice.
     * @since 1.0.0
     */
    const F = "F";

    /**
     * WorkStatus<br>
     * The field must be filled in with:<br>
     * “N” - Normal;<br>
     * “A” - Cancelled document;<br>
     * “F” - Billed document, even if partially,
     * when for the same document there is also on table 4.1. – SalesInvoices,
     * the corresponding invoice or simplified invoice.
     * <pre>
     * &lt;xs:element name="WorkStatus"&gt;
     *  &lt;xs:simpleType&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:enumeration value="N"/&gt;
     *          &lt;xs:enumeration value="A"/&gt;
     *          &lt;xs:enumeration value="F"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @param string $value
     * @throws EnumException
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
