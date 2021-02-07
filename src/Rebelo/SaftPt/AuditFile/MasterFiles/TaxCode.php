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

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * TaxCode<br>
 * In case field 2.5.1.1. – TaxType = IVA, the field must be filled in with:<br>
 * “RED” – Reduced tax rate;<br>
 * “INT” – Intermediate tax rate;<br>
 * “NOR” – Normal tax rate;<br>
 * “ISE” – Exempted;<br>
 * “OUT” – Others, applicable to the special VAT regimes.<br>
 * In case field 2.5.1.1. – TaxType = IS, it shall be filled in with:<br>
 * The correspondent code of the Stamp Duty’s table;<br>
 * In case it is not subject to tax it shall be filled in with “NS”.
 * In receipts issued without tax discriminated it shall be filled in with “NA”.
 * <pre>
 *  &lt;xs:simpleType name="TaxTableEntryTaxCode"&gt;
 *      &lt;xs:restriction base="xs:string"&gt;
 *          &lt;xs:minLength value="1"/&gt;
 *          &lt;xs:maxLength value="10"/&gt;
 *          &lt;xs:pattern value="RED|INT|NOR|ISE|OUT|([a-zA-Z0-9.])*|NS|NA"/&gt;
 *      &lt;/xs:restriction&gt;
 *  &lt;/xs:simpleType&gt;
 * </pre>
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode RED()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode INT()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode NOR()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode ISE()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode OUT()
 * @since 1.0.0
 * @author João Rebelo
 */
class TaxCode extends \Rebelo\Enum\AEnum
{
    /**
     * “RED” – Reduced tax rate
     * @since 1.0.0
     */
    const RED = "RED";

    /**
     * “INT” – Intermediate tax rate
     * @since 1.0.0
     */
    const INT = "INT";

    /**
     * “NOR” – Normal tax rate
     * @since 1.0.0
     */
    const NOR = "NOR";

    /**
     * “ISE” – Exempted
     * @since 1.0.0
     */
    const ISE = "ISE";

    /**
     * “OUT” – Others, applicable to the special VAT regimes
     * @since 1.0.0
     */
    const OUT = "OUT";

    /**
     * In case it is not subject to tax it shall be filled in with “NS”
     * @since 1.0.0
     */
    const NS = "NS";

    /**
     * In receipts issued without tax discriminated it shall be filled in with “NA”
     * @since 1.0.0
     */
    const NA = "NA";

    /**
     * TaxCode<br>
     * In case field 2.5.1.1. – TaxType = IVA, the field must be filled in with:<br>
     * “RED” – Reduced tax rate;<br>
     * “INT” – Intermediate tax rate;<br>
     * “NOR” – Normal tax rate;<br>
     * “ISE” – Exempted;<br>
     * “OUT” – Others, applicable to the special VAT regimes.<br>
     * In case field 2.5.1.1. – TaxType = IS, it shall be filled in with:<br>
     * The correspondent code of the Stamp Duty’s table;<br>
     * “ISE” – Exempted.<br>
     * In case it is not subject to tax it shall be filled in with “NS”.
     * In receipts issued without tax discriminated it shall be filled in with “NA”.
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (\Rebelo\Enum\EnumException $e) {
            $reg = "/([a-zA-Z0-9.])*/";
            $msg = null;
            if (\preg_match($reg, $value) !== 1) {
                $msg = sprintf("The TaxCode must respect the RegExp '%s'", $reg);
            } elseif (\strlen($value) < 1 || \strlen($value) > 10) {
                $msg = "The TaxCode must have a length between '1' and '10'";
            }
            if ($msg !== null) {
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $this->value = $value;
        }
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
