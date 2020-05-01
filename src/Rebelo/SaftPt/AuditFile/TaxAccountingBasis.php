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
 * TaxAccountingBasis<bR>
 * <code>
 * <xs:element name="TaxAccountingBasis">
 *     <xs:annotation>
 *         <xs:documentation>C para Contabilidade, E para Faturacao emitida por terceiros, F para
 *             Faturacao, I para Contabilidade integrada com a faturacao, P para Faturacao parcial,
 *             R para Recibos (a), S para Autofaturacao, T para Documentos de transporte (a). (a)
 *             Deve ser indicado este tipo, se o programa apenas este emitir este tipo de
 *             documento. Caso contrario, devera ser utilizado o tipo C, F ou I
 *         </xs:documentation>
 *     </xs:annotation>
 *     <xs:simpleType>
 *         <xs:restriction base="xs:string">
 *             <xs:enumeration value="C"/>
 *             <xs:enumeration value="E"/>
 *             <xs:enumeration value="F"/>
 *             <xs:enumeration value="I"/>
 *             <xs:enumeration value="P"/>
 *             <xs:enumeration value="R"/>
 *             <xs:enumeration value="S"/>
 *             <xs:enumeration value="T"/>
 *         </xs:restriction>
 *     </xs:simpleType>
 * <code>
 * @since 1.0.0
 * @author João Rebelo
 */
class TaxAccountingBasis extends \Rebelo\Enum\AEnum
{
    /**
     * <xs:enumeration value="C"/><br>
     * C para Contabilidade
     * @since 1.0.0
     */
    const CONTABILIDADE = "C";

    /**
     * <xs:enumeration value="E"/><br>
     * E para Faturacao emitida por terceiros
     * @since 1.0.0
     */
    const FACT_POR_TERC = "E";

    /**
     * <xs:enumeration value="F"/><br>
     * F para Faturacao
     * @since 1.0.0
     */
    const FACTURACAO = "F";

    /**
     * <xs:enumeration value="I"/><br>
     * I para Contabilidade integrada com a faturacao
     * @since 1.0.0
     */
    const CONTAB_FACTURACAO = "I";

    /**
     * <xs:enumeration value="P"/><br>
     * P para Faturacao parcial
     * @since 1.0.0
     */
    const FACT_PARCIAL = "P";

    /**
     * <xs:enumeration value="R"/><br>
     * R para Recibos<br>
     * Deve ser indicado este tipo, se o programa apenas este emitir este tipo de
     * documento. Caso contrario, devera ser utilizado o tipo C, F ou I
     * @since 1.0.0
     */
    const RECEIBOS = "R";

    /**
     * <xs:enumeration value="S"/><br>
     * S para Autofaturacao
     * @since 1.0.0
     */
    const AUTOFATURACAO = "S";

    /**
     * <xs:enumeration value="T"/><br>
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
}