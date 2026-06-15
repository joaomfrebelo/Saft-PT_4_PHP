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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

/**
 * WithholdingTaxType<br>
 * Indicate the type of withholding tax in this field, filling in:<br>
 * “IRS” – Personal income tax;<br>
 * “IRC” – Corporate income tax;<br>
 * “IS” – Stamp Duty.<br>
 *
 * @author João Rebelo
 */
enum WithholdingTaxType : string
{
    /**
     * IRS para Imposto Sobre o Rendimento das Pessoas Singulares
     * @since 3.0.0
     */
    case IRS = "IRS";

    /**
     * IRC para Imposto Sobre o Rendimento das Pessoas colectivas
     * @since 3.0.0
     */
    case IRC = "IRC";

    /**
     * IS para Imposto do selo
     * @since 3.0.0
     */
    case IS = "IS";

}
