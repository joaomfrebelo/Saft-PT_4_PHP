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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

/**
 * PaymentStatus<br>
 * To fill in with:
 * “N” – Normal receipt in force;<br>
 * “A” – Cancelled receipt.<br>
 *
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus N()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus A()
 * @author João Rebelo
 * @since 1.0.0
 */
class PaymentStatus extends \Rebelo\Enum\AEnum
{
    /**
     * “N” – Normal receipt in force
     * “A” – Cancelled receipt.
     * @since 1.0.0
     */
    const N = "N";

    /**
     * A para Anulado
     * @since 1.0.0
     */
    const A = "A";

    /**
     * PaymentStatus<br>
     * To fill in with:<br>
     * “N” – Normal receipt in force;<br>
     * “A” – Cancelled receipt.<br>
     * <pre>
     *  &lt;xs:element name="PaymentStatus"&gt;
     *      &lt;xs:annotation&gt;
     *          &lt;xs:documentation&gt;N para normal, A para Anulado &lt;/xs:documentation&gt;
     *      &lt;/xs:annotation&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:enumeration value="N"/&gt;
     *              &lt;xs:enumeration value="A"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
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
     * Get enum value
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}
