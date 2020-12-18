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

namespace Rebelo\SaftPt\AuditFile;

/**
 * TaxAccountingBasis<br>
 * Shall be filled in with the type of program, indicating the applicable data
 * (including the transport documents, conference documents and issued receipts, if any):<br>
 * “C” - Accounting;<br>
 * “E” - Invoices issued by third parties;<br>
 * “F” - Invoicing;<br>
 * “I” - Invoicing and accounting integrated data;<br>
 * “P” - Invoicing partial data.<br>
 * “R” - Receipts (a);<br>
 * “S” - Self-billing;<br>
 * “T” - Transport documents (a).<br>
 * (a) Type of program should be indicated, in case only this type of
 * documents are issued. If not, fill in with type “C”, “F” or “I”.
 * <pre>
 * &lt;xs:element name="TaxAccountingBasis">
 *     &lt;xs:simpleType>
 *         &lt;xs:restriction base="xs:string">
 *             &lt;xs:enumeration value="C"/&gt;
 *             &lt;xs:enumeration value="E"/&gt;
 *             &lt;xs:enumeration value="F"/&gt;
 *             &lt;xs:enumeration value="I"/&gt;
 *             &lt;xs:enumeration value="P"/&gt;
 *             &lt;xs:enumeration value="R"/&gt;
 *             &lt;xs:enumeration value="S"/&gt;
 *             &lt;xs:enumeration value="T"/&gt;
 *         &lt;/xs:restriction&gt;
 *     &lt;/xs:simpleType&gt;
 * <pre>
 * @since 1.0.0
 * @author João Rebelo
 */
class TaxAccountingBasis extends \Rebelo\Enum\AEnum
{
    /**
     * “C” - Accounting<br>
     * &lt;xs:enumeration value="C"/&gt;<br>
     * C para Contabilidade
     * @since 1.0.0
     */
    const CONTABILIDADE = "C";

    /**
     * “E” - Invoices issued by third parties;<br>
     * &lt;xs:enumeration value="E"/&gt;<br>
     * E para Faturacao emitida por terceiros
     * @since 1.0.0
     */
    const FACT_POR_TERC = "E";

    /**
     * “F” - Invoicing;<br>
     * &lt;xs:enumeration value="F"/&gt;<br>
     * F para Faturacao
     * @since 1.0.0
     */
    const FACTURACAO = "F";

    /**
     * “I” - Invoicing and accounting integrated data;<br>
     * &lt;xs:enumeration value="I"/&gt;<br>
     * I para Contabilidade integrada com a faturacao
     * @since 1.0.0
     */
    const CONTAB_FACTURACAO = "I";

    /**
     * “P” - Invoicing partial data.<br>
     * &lt;xs:enumeration value="P"/&gt;<br>
     * P para Faturacao parcial
     * @since 1.0.0
     */
    const FACT_PARCIAL = "P";

    /**
     * “R” - Receipts (a);<br>
     * &lt;xs:enumeration value="R"/&gt;<br>
     * R para Recibos<br>
     * Deve ser indicado este tipo, se o programa apenas este emitir este tipo de
     * documento. Caso contrario, devera ser utilizado o tipo C, F ou I
     * @since 1.0.0
     */
    const RECEIBOS = "R";

    /**
     * “S” - Self-billing;<br>
     * &lt;xs:enumeration value="S"/&gt;<br>
     * S para Autofaturacao
     * @since 1.0.0
     */
    const AUTOFATURACAO = "S";

    /**
     * “T” - Transport documents (a).<br>
     * (a) Type of program should be indicated, in case only this type of
     * documents are issued. If not, fill in with type “C”, “F” or “I”.<br>
     * &lt;xs:enumeration value="T"/&gt;<br>
     * T para Transporte
     * @since 1.0.0
     */
    const TRANSPORTE = "S";

    /**
     * <br>
     * Shall be filled in with the type of program, indicating the applicable data
     * (including the transport documents, conference documents and issued receipts, if any):<br>
     * “C” - Accounting;<br>
     * “E” - Invoices issued by third parties;<br>
     * “F” - Invoicing;<br>
     * “I” - Invoicing and accounting integrated data;<br>
     * “P” - Invoicing partial data.<br>
     * “R” - Receipts (a);<br>
     * “S” - Self-billing;<br>
     * “T” - Transport documents (a).<br>
     * (a) Type of program should be indicated, in case only this type of
     * documents are issued. If not, fill in with type “C”, “F” or “I”.
     * &lt;xs:enumeration value="T"/&gt;<br>
     * T para Documentos de transporte<br>
     * Deve ser indicado este tipo, se o programa apenas este emitir este tipo de
     * documento. Caso contrario, devera ser utilizado o tipo C, F ou I
     * @since 1.0.0
     */
    const DOC_TRANSP = "T";

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
