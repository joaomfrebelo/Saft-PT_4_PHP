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
 * Description of InvoiceType
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class InvoiceType extends \Rebelo\Enum\AEnum
{
    /**
     * FT para Fatura, emitida nos termos do artigo 36.
     * do Codigo do IVA
     * @since 1.0.0
     */
    const FT = "FT";

    /**
     * FS para Fatura simplificada, emitida nos termos
     * do artigo 40. do Codigo do IVA
     * @since 1.0.0
     */
    const FS = "FS";

    /**
     * FR para Fatura-recibo
     * @since 1.0.0
     */
    const FR = "FR";

    /**
     * ND para Nota de debito
     * @since 1.0.0
     */
    const ND = "ND";

    /**
     * NC para Nota de credito
     * @since 1.0.0
     */
    const NC = "NC";

    /**
     * VD para Venda a dinheiro e factura/recibo,
     * Para os dados ate 2012-12-31
     * @since 1.0.0
     */
    const VD = "VD";

    /**
     * TV para Talao de venda,
     * Para os dados ate 2012-12-31
     * @since 1.0.0
     */
    const TV = "TV";

    /**
     * TD para Talao de devolucao,
     * Para os dados ate 2012-12-31
     * @since 1.0.0
     */
    const TD = "TD";

    /**
     * AA para Alienacao de ativos,
     * Para os dados ate 2012-12-31
     * @since 1.0.0
     */
    const AA = "AA";

    /**
     * DA para Devolucao de ativos,
     * Para os dados ate 2012-12-31
     * @since 1.0.0
     */
    const DA = "DA";

    /**
     * Para o setor Segurador,
     * RP para Premio ou recibo de premio
     * @since 1.0.0
     */
    const RP = "RP";

    /**
     * Para o setor Segurador,
     * RE para Estorno ou recibo de estorno
     * @since 1.0.0
     */
    const RE = "RE";

    /**
     * Para o setor Segurador, CS para Imputacao a co-seguradoras
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * Para o setor Segurador,
     * para Imputacao a co-seguradora lider
     * @since 1.0.0
     */
    const LD = "LD";

    /**
     * Para o setor Segurador, RA para Resseguro aceite
     * @since 1.0.0
     */
    const RA = "RA";

    /**
     *
     * @param string $value
     * @return string
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        return parent::__construct($value);
    }
}