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
 * TaxExemptionCode<br>
 *  It shall be filled in with the code of the reason for exemption or non-settlement,
 * which is included in the "Manual de Integração de Software – Comunicação das Faturas à AT"
 * [Software Integration Manual - Communication of the Invoices to Tax and Customs Authority].
 * The filling is required when fields 4.1.4.19.15.4. - TaxPercentage
 * or 4.1.4.19.15.5. - TaxAmount are equal to zero.
 * This field shall also be filled in, for the cases not to subject to the
 * taxes mentioned in table 2.5. - TaxTable.
 *
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M01()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M03()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M04()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M05()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M06()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M07()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M08()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M09()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M10()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M11()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M12()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M13()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M14()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M15()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M16()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M20()
 *  @method \Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode M99()
 *
 * @author João Rebelo
 */
class TaxExemptionCode extends \Rebelo\Enum\AEnum
{
    /**
     * Mensção: Artigo 16.º n.º 6 do CIVA(ou similar<br>
     * Norma: Artigo 16.º  n.º  6 alíneas a) a d) do  CIVA
     * @since 1.0.0
     */
    const M01 = "M01";

    /**
     * Mensção: Artigo  6.º  do  Decreto-Lei  n.º  198/90,  de19  de Junho<br>
     * Norma: Artigo  6.º  do  Decreto‐Lei  n.º  198/90,  de  19  de  junho
     * @since 1.0.0
     */
    const M02 = "M02";

    /**
     * Mensção: Exigibilidade de caixa<br>
     * Norma: Decreto‐Lei n.º 204/97, de 9 de agosto,
     * Decreto-Lei n.º 418/99, de 21 de outubro,
     * Lei n.º 15/2009, de 1 de abril
     * @since 1.0.0
     */
    const M03 = "M03";

    /**
     * Mensção: Isento Artigo 13.º do CIVA(ou similar)<br>
     * Norma: Artigo 13.º do CIVA
     *
     * @since 1.0.0
     */
    const M04 = "M04";

    /**
     * Mensção: Isento Artigo 14.º do CIVA(ou similar)<br>
     * Norma: Artigo 14.º do CIVA
     *
     * @since 1.0.0
     */
    const M05 = "M05";

    /**
     * Mensção: sento Artigo 15.º do CIVA(ou similar)<br>
     * Norma: Artigo 15.º do CIVA
     *
     * @since 1.0.0
     */
    const M06 = "M06";

    /**
     * Mensção: Isento Artigo 9.º do CIVA(ou similar)<br>
     * Norma: Artigo 9.º do CIVA
     *
     * @since 1.0.0
     */
    const M07 = "M07";

    /**
     * Mensção: IVA –autoliquidação<br>
     * Norma: Artigo 2.º n.º 1 alínea i), j) ou l)do CIVA,
     * Artigo 6.º do CIVA, Decreto-Lei n.º 21/2007, de 29 de janeiro,
     * Decreto-Lei n.º 362/99, de 16 de setembro, Artigo 8.º do RITI
     *
     * @since 1.0.0
     */
    const M08 = "M08";

    /**
     * Mensção: IVA -não confere direito a dedução<br>
     * Norma: Artigo 60.º CIVA, Artigo 72.º n.º 4 do CIVA
     *
     * @since 1.0.0
     */
    const M09 = "M09";

    /**
     * Mensção: IVA –Regime de isenção<br>
     * Norma: Artigo 53.ºdo CIVA
     *
     * @since 1.0.0
     */
    const M10 = "M10";

    /**
     * Mensção: Regime particular do tabaco<br>
     * Norma: Decreto-Lei n.º 346/85, de 23 de agosto
     *
     * @since 1.0.0
     */
    const M11 = "M11";

    /**
     * Mensção: Regime  da  margem  de  lucro –Agências  de viagens<br>
     * Norma: Decreto-Lei n.º 221/85, de 3 de julho
     *
     * @since 1.0.0
     */
    const M12 = "M12";

    /**
     * Mensção: Regime da margem de lucro –Bens em segunda mão<br>
     * Norma: Decreto-Lei n.º 199/96, de 18 de outubro
     *
     * @since 1.0.0
     */
    const M13 = "M13";

    /**
     * Mensção: Regime da margem de lucro –Objetos de arte<br>
     * Norma: Decreto-Lei n.º 199/96, de 18 de outubro
     *
     * @since 1.0.0
     */
    const M14 = "M14";

    /**
     * Mensção: Regime   da   margem   de   lucro –Objetos   de coleção e antiguidades<br>
     * Norma: Decreto-Lei n.º 199/96, de 18 de outubro
     *
     * @since 1.0.0
     */
    const M15 = "M15";

    /**
     * Mensção: Isento Artigo 14.º do RITI (ou similar)<br>
     * Norma: Artigo 14.º do RITI
     *
     * @since 1.0.0
     */
    const M16 = "M16";

    /**
     * Mensção: IVA -Regime forfetário<br>
     * Norma: Artigo 59.º-B do CIVA
     *
     * @since 1.0.0
     */
    const M20 = "M20";

    /**
     * Mensção: Não sujeito; não tributado (ou similar) <br>
     * Norma: Outras  situações  de  não  liquidação  do  imposto
     * (Exemplos: artigo 2.º, n.º 2 ;
     *  artigo 3.º, n.ºs 4, 6 e 7; artigo 4.º, n.º 5, todos do CIVA)
     *
     * @since 1.0.0
     */
    const M99 = "M99";

    /**
     * It shall be filled in with the code of the reason for exemption or non-settlement,
     * which is included in the "Manual de Integração de Software – Comunicação das Faturas à AT"
     * [Software Integration Manual - Communication of the Invoices to Tax and Customs Authority].
     * The filling is required when fields 4.1.4.19.15.4. - TaxPercentage
     * or 4.1.4.19.15.5. - TaxAmount are equal to zero.
     * This field shall also be filled in, for the cases not to subject to the
     * taxes mentioned in table 2.5. - TaxTable.
     *
     * @param string $value
     * @since 1.0.0
     */
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
