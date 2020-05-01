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

/**
 * Description of ProductType
 * <pre>
 *     <xs:element name="ProductType">
 *      <xs:annotation>
 *          <xs:documentation>Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
 *              debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
 *              Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
 *              parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
 *              impostos (TaxTable)e Impostos Especiais de Consumo </xs:documentation>
 *      </xs:annotation>
 *      <xs:simpleType>
 *          <xs:restriction base="xs:string">
 *              <xs:enumeration value="P"/>
 *              <xs:enumeration value="S"/>
 *              <xs:enumeration value="O"/>
 *              <xs:enumeration value="E"/>
 *              <xs:enumeration value="I"/>
 *          </xs:restriction>
 *      </xs:simpleType>
 *  </xs:element>
 * </pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class ProductType extends \Rebelo\Enum\AEnum
{
    /**
     * P para Produtos
     * @since 1.0.0
     */
    const P = "P";

    /**
     * S para Servicos
     * @since 1.0.0
     */
    const S = "S";

    /**
     * O para Outros<br>
     * (Ex: portes
     * debitados, adiantamentos recebidos ou alienacao de ativos)
     * @since 1.0.0
     */
    const O = "O";

    /**
     *  E para Impostos<br>
     *  Especiais de Consumo (ex.:IABA, ISP, IT)
     * @since 1.0.0
     */
    const E = "E";

    /**
     * I para impostos<br>
     * taxas e encargos parafiscais exceto IVA e IS que deverao
     * ser refletidos na tabela 2.5 Tabela de
     * impostos (TaxTable)e Impostos Especiais de Consumo
     * @since 1.0.0
     */
    const I = "I";

    /**
     *
     * <pre>
     *     <xs:element name="ProductType">
     *      <xs:annotation>
     *          <xs:documentation>Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
     *              debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
     *              Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
     *              parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
     *              impostos (TaxTable)e Impostos Especiais de Consumo </xs:documentation>
     *      </xs:annotation>
     *      <xs:simpleType>
     *          <xs:restriction base="xs:string">
     *              <xs:enumeration value="P"/>
     *              <xs:enumeration value="S"/>
     *              <xs:enumeration value="O"/>
     *              <xs:enumeration value="E"/>
     *              <xs:enumeration value="I"/>
     *          </xs:restriction>
     *      </xs:simpleType>
     *  </xs:element>
     * </pre>
     * @param type $value
     */
    public function __construct($value)
    {
        parent::__construct($value);
    }
}