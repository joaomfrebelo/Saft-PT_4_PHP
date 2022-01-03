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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

/**
 * TaxType<br>
 * This field shall be filled in with the tax type:<br>
 * “IVA” – Value Added Tax;<br>
 * “IS” – Stamp Duty;<br>
 * “NS” – Not subject to VAT or Stamp Duty.<br>
 * <pre>
 *  &lt;xs:element name="TaxType"&gt;
 *      &lt;xs:simpleType&gt;
 *          &lt;xs:restriction base="xs:string"&gt;
 *              &lt;xs:enumeration value="IVA"/&gt;
 *              &lt;xs:enumeration value="IS"/&gt;
 *              &lt;xs:enumeration value="NS"/&gt;
 *          &lt;/xs:restriction&gt;
 *      &lt;/xs:simpleType&gt;
 *  &lt;/xs:element&gt;
 * </pre>
 * @method static Rebelo\SaftPt\AuditFile\MasterFiles\TaxType IVA()
 * @method static Rebelo\SaftPt\AuditFile\MasterFiles\TaxType IS()
 * @method static Rebelo\SaftPt\AuditFile\MasterFiles\TaxType NS()
 * @author João Rebelo
 * @since 1.0.0
 */
class TaxType extends \Rebelo\Enum\AEnum
{
    /**
     * “IVA” – Value Added Tax;
     * @since 1.0.0
     */
    const IVA = "IVA";

    /**
     * “IS” – Stamp Duty
     * @since 1.0.0
     */
    const IS = "IS";

    /**
     * “NS” – Not subject to VAT or Stamp Duty
     * @since 1.0.0
     */
    const NS = "NS";

    /**
     * This field shall be filled in with the tax type:<br>
     * “IVA” – Value Added Tax;<br>
     * “IS” – Stamp Duty;<br>
     * “NS” – Not subject to VAT or Stamp Duty.<br>
     * <pre>
     *  &lt;xs:element name="TaxType"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:enumeration value="IVA"/&gt;
     *              &lt;xs:enumeration value="IS"/&gt;
     *              &lt;xs:enumeration value="NS"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     *
     * @param string $value a contant value from TaxType class
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the value as string
     * @return string
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}
