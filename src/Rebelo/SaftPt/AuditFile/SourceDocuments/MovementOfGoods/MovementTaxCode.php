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
 * @author João Rebelo
 */
enum MovementTaxCode : string
{
    /**
     * “RED” - Reduced tax rate
     * @since 3.0.0
     */
    case RED = "RED";

    /**
     * “INT” - Intermediate tax rate
     * @since 3.0.0
     */
    case INT = "INT";

    /**
     * “NOR” - Normal tax rate
     * @since 3.0.0
     */
    case NOR = "NOR";

    /**
     * Exempted
     * @since 3.0.0
     */
    case ISE = "ISE";

    /**
     * “OUT” - Other, applicable to the special VAT regimes
     * @since 3.0.0
     */
    case OUT = "OUT";

    /**
     * In case of not subject to tax, to fill in with “NS”
     * @since 3.0.0
     */
    case NS = "NS";

}
