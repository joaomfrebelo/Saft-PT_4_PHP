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
 * MovementTaxCode<br>
 * Tax rate code in the table of taxes.<br>
 * Shall be filled in with:<br>
 * “RED” - Reduced tax rate;<br>
 * “INT” - Intermediate tax rate;<br>
 * “NOR” - Normal tax rate;<br>
 * “ISE” - Exempted;<br>
 * “OUT” - Other, applicable to the special VAT regimes.<br>
 * In case of not subject to tax, to fill in with “NS”.<br>
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode RED()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode INT()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode NOR()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode ISE()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode OUT()
 * @author João Rebelo
 */
class MovementTaxCode extends \Rebelo\Enum\AEnum
{
    /**
     * “RED” - Reduced tax rate
     * @since 1.0.0
     */
    const RED = "RED";

    /**
     * “INT” - Intermediate tax rate
     * @since 1.0.0
     */
    const INT = "INT";

    /**
     * “NOR” - Normal tax rate
     * @since 1.0.0
     */
    const NOR = "NOR";

    /**
     * Exempted
     * @since 1.0.0
     */
    const ISE = "ISE";

    /**
     * “OUT” - Other, applicable to the special VAT regimes
     * @since 1.0.0
     */
    const OUT = "OUT";

    /**
     * In case of not subject to tax, to fill in with “NS”
     * @since 1.0.0
     */
    const NS = "NS";

    /**
     * MovementTaxCode
     * <br>
     * Tax rate code in the table of taxes.<br>
     * Shall be filled in with:<br>
     * “RED” - Reduced tax rate;<br>
     * “INT” - Intermediate tax rate;<br>
     * “NOR” - Normal tax rate;<br>
     * “ISE” - Exempted;<br>
     * “OUT” - Other, applicable to the special VAT regimes.<br>
     * In case of not subject to tax, to fill in with “NS”.<br>
     * <pre>
     *  &lt;xs:simpleType name="SAFTPTMovementTaxCode"&gt;
     *  &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:pattern value="RED|INT|NOR|ISE|OUT|NS"/&gt;
     *       &lt;xs:minLength value="1"/&gt;
     *       &lt;xs:maxLength value="3"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @param Mixed $value
     * @since 1.0.0
     */
    public function __construct($value)
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
