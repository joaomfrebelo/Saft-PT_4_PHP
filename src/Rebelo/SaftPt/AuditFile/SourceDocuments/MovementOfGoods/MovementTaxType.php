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
 * MovementTaxType<br>
 * This field shall be filled in with:<br>
 * “IVA” – Value Added Tax;<br>
 * “NS” – Not subject to VAT.<br>
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType IVA()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType NS()
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementTaxType extends \Rebelo\Enum\AEnum
{
    /**
     *
     * @since 1.0.0
     */
    const IVA = "IVA";

    /**
     *
     * @since 1.0.0
     */
    const NS = "NS";

    /**
     * MovementTaxType<br>
     * This field shall be filled in with:<br>
     * “IVA” – Value Added Tax;<br>
     * “NS” – Not subject to VAT.<br>
     * <pre>
     *   &lt;xs:simpleType name="SAFTPTMovementTaxType"&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="IVA"/&gt;
     *           &lt;xs:enumeration value="NS"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * @param string $value
     * </pre>
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
