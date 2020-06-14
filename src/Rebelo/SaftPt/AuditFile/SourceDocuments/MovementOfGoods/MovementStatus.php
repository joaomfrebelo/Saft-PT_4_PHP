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
 * Description of MovementStatus
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementStatus extends \Rebelo\Enum\AEnum
{
    /**
     * N para Normal
     * @since 1.0.0
     */
    const N = "N";

    /**
     * T para Por conta de terceiros
     * @since 1.0.0
     */
    const T = "T";

    /**
     * A para Documento anulado
     * @since 1.0.0
     */
    const A = "A";

    /**
     * F para Documento faturado, quando para este documento tambem
     * existe na tabela 4.1. para Documentos comerciais a clientes
     * (SalesInvoices) o correspondente do tipo fatura ou fatura simplificada
     * @since 1.0.0
     */
    const F = "F";

    /**
     * R para Documento de resumo doutros documentos
     * criados noutras aplicacoes e gerado nesta aplicacao
     * @since 1.0.0
     */
    const R = "R";

    /**
     * MovementStatus
     * <pre>
     * &lt;xs:element name="MovementStatus"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt;N para Normal, T para Por conta de terceiros, A para Documento
     *           anulado, F para Documento faturado, quando para este documento tambem existe na
     *           tabela 4.1. para Documentos comerciais a clientes (SalesInvoices) o correspondente
     *           do tipo fatura ou fatura simplificada, R para Documento de resumo doutros documentos
     *           criados noutras aplicacoes e gerado nesta aplicacao &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="N"/&gt;
     *           &lt;xs:enumeration value="T"/&gt;
     *           &lt;xs:enumeration value="A"/&gt;
     *           &lt;xs:enumeration value="F"/&gt;
     *           &lt;xs:enumeration value="R"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        return parent::__construct($value);
    }
}