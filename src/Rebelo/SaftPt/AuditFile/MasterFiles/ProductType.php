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
 * Description of ProductType<br>
 * The field shall be filled in with:<br>
 * “P” - Products;<br>
 * “S” - Services;<br>
 * “O” - Others (e.g. charged freights, advance payments received or sale of assets);<br>
 * “E” - Excise duties - (e.g. IABA, ISP, IT);<br>
 * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
 * which shall appear in table 2.5. – TaxTable and Excise Duties which
 * shall be filled in with the "E" code.
 * <pre>
 *     &lt;xs:element name="ProductType"&gt;
 *      &lt;xs:annotation>
 *          &lt;xs:documentation>Restricao: P para Produtos, S para Servicos, O para Outros (Ex: portes
 *              debitados, adiantamentos recebidos ou alienacao de ativos), E para Impostos
 *              Especiais de Consumo (ex.:IABA, ISP, IT); I para impostos, taxas e encargos
 *              parafiscais exceto IVA e IS que deverao ser refletidos na tabela 2.5 Tabela de
 *              impostos (TaxTable)e Impostos Especiais de Consumo &lt;/xs:documentation&gt;
 *      &lt;/xs:annotation>
 *      &lt;xs:simpleType>
 *          &lt;xs:restriction base="xs:string">
 *              &lt;xs:enumeration value="P"/&gt;
 *              &lt;xs:enumeration value="S"/&gt;
 *              &lt;xs:enumeration value="O"/&gt;
 *              &lt;xs:enumeration value="E"/&gt;
 *              &lt;xs:enumeration value="I"/&gt;
 *          &lt;/xs:restriction&gt;
 *      &lt;/xs:simpleType&gt;
 *  &lt;/xs:element&gt;
 * </pre>
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType P()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType S()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType O()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType E()
 * @method static \Rebelo\SaftPt\AuditFile\MasterFiles\ProductType I()
 * @author João Rebelo
 * @since 1.0.0
 */
class ProductType extends \Rebelo\Enum\AEnum
{
    /**
     * “P” - Products
     * @since 1.0.0
     */
    const P = "P";

    /**
     * “S” - Services
     * @since 1.0.0
     */
    const S = "S";

    /**
     * “O” - Others <br>
     * (e.g. charged freights, advance payments received or sale of assets)
     * @since 1.0.0
     */
    const O = "O";

    /**
     *  “E” - Excise duties - (e.g. IABA, ISP, IT)
     * @since 1.0.0
     */
    const E = "E";

    /**
     * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
     * which shall appear in table 2.5. – TaxTable and Excise Duties which
     * shall be filled in with the "E" code.
     * @since 1.0.0
     */
    const I = "I";

    /**
     * ProductType<br>
     * <br>
     * The field shall be filled in with:<br>
     * “P” - Products;<br>
     * “S” - Services;<br>
     * “O” - Others (e.g. charged freights, advance payments received or sale of assets);<br>
     * “E” - Excise duties - (e.g. IABA, ISP, IT);<br>
     * “I” - Taxes, tax rates and parafiscal charges except VAT and Stamp Duty
     * which shall appear in table 2.5. – TaxTable and Excise Duties which
     * shall be filled in with the "E" code.
     * <pre>
     *     &lt;xs:element name="ProductType">
     *      &lt;xs:simpleType>
     *          &lt;xs:restriction base="xs:string">
     *              &lt;xs:enumeration value="P"/&gt;
     *              &lt;xs:enumeration value="S"/&gt;
     *              &lt;xs:enumeration value="O"/&gt;
     *              &lt;xs:enumeration value="E"/&gt;
     *              &lt;xs:enumeration value="I"/&gt;
     *          &lt;/xs:restriction&gt;
     *      &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param Mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);
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
