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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

/**
 * InvoiceStatus
 * <pre>
 *  <!-- Estado do documento SalesInvoices -->
 *  &lt;xs:element name="InvoiceStatus"&gt;
 *      &lt;xs:annotation&gt;
 *          &lt;xs:documentation&gt;N para Normal, S para Autofaturacao, A para Documento anulado, R para
 *              Documento de resumo doutros documentos criados noutras aplicacoes e gerado nesta
 *              aplicacao, F para Documento faturado &lt;/xs:documentation&gt;
 *      &lt;/xs:annotation&gt;
 *      &lt;xs:simpleType&gt;
 *          &lt;xs:restriction base="xs:string"&gt;
 *              &lt;xs:enumeration value="N"/&gt;
 *              &lt;xs:enumeration value="S"/&gt;
 *              &lt;xs:enumeration value="A"/&gt;
 *              &lt;xs:enumeration value="R"/&gt;
 *              &lt;xs:enumeration value="F"/&gt;
 *          &lt;/xs:restriction&gt;
 *      &lt;/xs:simpleType&gt;
 *  &lt;/xs:element&gt;
 * </pre>
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus N()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus S()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus A()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus R()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus F()
 * @author João Rebelo
 * @since 1.0.0
 */
class InvoiceStatus extends \Rebelo\Enum\AEnum
{
    /**
     * “N” - Normal<br>
     *  &lt;xs:enumeration value="N"/&gt;
     * @since 1.0.0
     */
    const N = "N";

    /**
     * S para Autofaturacao<br>
     * &lt;xs:enumeration value="S"/&gt;
     * @since 1.0.0
     */
    const S = "S";

    /**
     * “A” - Cancelled document<br>
     * &lt;xs:enumeration value="A"/&gt;
     * @since 1.0.0
     */
    const A = "A";

    /**
     * “R” - Summary document for other documents created in other applications
     * and generated in this application<br>
     * &lt;xs:enumeration value="R"/&gt;
     * @since 1.0.0
     */
    const R = "R";

    /**
     * “F” - Invoiced document<br>
     * &lt;xs:enumeration value="F"/&gt;
     * @since 1.0.0
     */
    const F = "F";

    /**
     * InvoiceStatus<br>
     * The field must be filled in with:<br>
     * “N” - Normal;<br>
     * “A” - Cancelled document;<br>
     * “R” - Summary document for other documents created in other applications
     * and generated in this application;
     * “F” - Invoiced document.<br>
     * <pre>
     *  &lt;xs:element name="InvoiceStatus"&gt;
     *      &lt;xs:simpleType&gt;
     *          &lt;xs:restriction base="xs:string"&gt;
     *              &lt;xs:enumeration value="N"/&gt;
     *              &lt;xs:enumeration value="S"/&gt;
     *              &lt;xs:enumeration value="A"/&gt;
     *              &lt;xs:enumeration value="R"/&gt;
     *              &lt;xs:enumeration value="F"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
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
