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
 * MovementType
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementType extends \Rebelo\Enum\AEnum
{
    /**
     * GR para Guia de remessa
     * @since 1.0.0
     */
    const GR = "GR";

    /**
     * GT para Guia de transporte incluindo as globais
     * @since 1.0.0
     */
    const GT = "GT";

    /**
     * GA para Guia de movimentacao de ativos fixos proprios
     * @since 1.0.0
     */
    const GA = "GA";

    /**
     * GC para Guia de consignacao
     * @since 1.0.0
     */
    const GC = "GC";

    /**
     * GD para Guia ou nota de devolucao
     * @since 1.0.0
     */
    const GD = "GD";

    /**
     *
     * <pre>
     * &lt;xs:element name="MovementType"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt; Restricao: Tipos de Documento (GR para Guia de remessa, GT para Guia
     *           de transporte incluindo as globais, GA para Guia de movimentacao de ativos fixos
     *           proprios, GC para Guia de consignacao, GD para Guia ou nota de devolucao
     *       &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="GR"/&gt;
     *           &lt;xs:enumeration value="GT"/&gt;
     *           &lt;xs:enumeration value="GA"/&gt;
     *           &lt;xs:enumeration value="GC"/&gt;
     *           &lt;xs:enumeration value="GD"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}