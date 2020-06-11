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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

/**
 * Description of WorkType
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkType extends \Rebelo\Enum\AEnum
{
    /**
     * Node name
     * @since 1.0.0
     */
    const CM = "CM";

    /**
     * Node name
     * @since 1.0.0
     */
    const CC = "CC";

    /**
     * Node name
     * @since 1.0.0
     */
    const FC = "FC";

    /**
     * Node name
     * @since 1.0.0
     */
    const FO = "FO";

    /**
     * Node name
     * @since 1.0.0
     */
    const NE = "NE";

    /**
     * Node name
     * @since 1.0.0
     */
    const OU = "OU";

    /**
     * Node name
     * @since 1.0.0
     */
    const OR = "OR";

    /**
     * Type
     * @since 1.0.0
     */
    const PF = "PF";

    /**
     * Type
     * @since 1.0.0
     */
    const DC = "DC";

    /**
     * Type
     * @since 1.0.0
     */
    const N_RP = "RP";

    /**
     * Type
     * @since 1.0.0
     */
    const RE = "RE";

    /**
     * Type
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * Type
     * @since 1.0.0
     */
    const LD = "LD";

    /**
     * Type
     * @since 1.0.0
     */
    const RA = "RA";

    /**
     * &lt;xs:element name="WorkType"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt; Restricao: DC para documentos emitidos ate 2017-06-30, CM para
     *           consulta de mesa, CC para credito de consignacao, FC para fatura de consignacao nos
     *           termos do art.38 do CIVA, FO para folha de obra, NE para nota de encomenda, OU para
     *           outros documentos suscetiveis de apresentacao ao cliente para conferencia de
     *           mercadorias ou de prestacao de servicos que nao se encontrem aqui devidamente
     *           identificados (ou seus equivalentes), OR para orcamento, PF para fatura pro-forma.
     *           Para o setor Segurador quando para os tipos de documentos a seguir identificados
     *           tambem deva existir na tabela 4.1 - Documentos comerciais a clientes (SalesInvoices)
     *           a correspondente fatura ou documento rectificativo de fatura, ainda pode ser
     *           preenchido com RP para premio ou recibo de premio, RE para estorno ou recibo de
     *           estorno, CS para imputacao a co-seguradoras, LD para imputacao a co-seguradora
     *           lider, RA para resseguro aceite. &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:enumeration value="CM"/&gt;
     *           &lt;xs:enumeration value="CC"/&gt;
     *           &lt;xs:enumeration value="FC"/&gt;
     *           &lt;xs:enumeration value="FO"/&gt;
     *           &lt;xs:enumeration value="NE"/&gt;
     *           &lt;xs:enumeration value="OU"/&gt;
     *           &lt;xs:enumeration value="OR"/&gt;
     *           &lt;xs:enumeration value="PF"/&gt;
     *           &lt;!-- Para para dados ate 2017-06-30--&gt;
     *           &lt;xs:enumeration value="DC"/&gt;
     *           &lt;!-- Para o sector segurador--&gt;
     *           &lt;xs:enumeration value="RP"/&gt;
     *           &lt;xs:enumeration value="RE"/&gt;
     *           &lt;xs:enumeration value="CS"/&gt;
     *           &lt;xs:enumeration value="LD"/&gt;
     *           &lt;xs:enumeration value="RA"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        return parent::__construct($value);
    }
}