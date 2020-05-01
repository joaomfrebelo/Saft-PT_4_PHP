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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

/**
 * WithholdingTaxType
 *
 * @author João Rebelo
 */
class WithholdingTaxType extends \Rebelo\Enum\AEnum
{
    /**
     * IRS para Imposto Sobre o Rendimento das Pessoas Singulares
     * @since 1.0.0
     */
    const IRS = "IRS";

    /**
     * IRC para Imposto Sobre o Rendimento das Pessoas colectivas
     * @since 1.0.0
     */
    const IRC = "IRC";

    /**
     * IS para Imposto do selo
     * @since 1.0.0
     */
    const IS = "IS";

    /**
     * <pre>
     * <!-- Codigo do tipo de imposto retido -->
     * &lt;xs:element name="WithholdingTaxType"&gt;
     *     &lt;xs:annotation&gt;
     *         &lt;xs:documentation&gt;Restricao: IRS para Imposto Sobre o Rendimento das Pessoas Singulares,
     *             IRC para Imposto Sobre o Rendimento das Pessoas colectivas, IS para Imposto do selo
     *         &lt;/xs:documentation&gt;
     *     &lt;/xs:annotation&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:enumeration value="IRS"/&gt;
     *             &lt;xs:enumeration value="IRC"/&gt;
     *             &lt;xs:enumeration value="IS"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}