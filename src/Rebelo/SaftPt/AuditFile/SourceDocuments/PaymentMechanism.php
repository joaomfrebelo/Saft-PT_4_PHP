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
 * PaymentMechanism
 *
 *
 * &lt;xs:element name="PaymentMechanism"&gt;
 *    &lt;xs:annotation&gt;
 *        &lt;xs:documentation&gt; Restricao:CC para Cartao credito, CD para Cartao debito, CH para
 *            Cheque bancario, CI para credito documentario internacional, CO para Cheque ou
 *            cartao oferta, CS para Compensacao de saldos em conta corrente, DE para Dinheiro
 *            eletronico, por exemplo em cartoes de fidelidade ou de pontos, LC para Letra
 *            comercial, MB para Referencias de pagamento para Multibanco, NU para Numerario, OU
 *            para Outros meios aqui nao assinalados, PR para Permuta de bens, TB para
 *            Transferencia bancaria ou debito direto autorizado, TR para titulos de compensacao
 *            extrassalarial independentemente do seu suporte, por exemplo, titulos de refeicao,
 *            educacao, etc. &lt;/xs:documentation&gt;
 *    &lt;/xs:annotation&gt;
 *    &lt;xs:simpleType&gt;
 *        &lt;xs:restriction base="xs:string"&gt;
 *            &lt;xs:enumeration value="CC"/&gt;
 *            &lt;xs:enumeration value="CD"/&gt;
 *            &lt;xs:enumeration value="CH"/&gt;
 *            &lt;xs:enumeration value="CI"/&gt;
 *            &lt;xs:enumeration value="CO"/&gt;
 *            &lt;xs:enumeration value="CS"/&gt;
 *            &lt;xs:enumeration value="DE"/&gt;
 *            &lt;xs:enumeration value="LC"/&gt;
 *            &lt;xs:enumeration value="MB"/&gt;
 *            &lt;xs:enumeration value="NU"/&gt;
 *            &lt;xs:enumeration value="OU"/&gt;
 *            &lt;xs:enumeration value="PR"/&gt;
 *            &lt;xs:enumeration value="TB"/&gt;
 *            &lt;xs:enumeration value="TR"/&gt;
 *        &lt;/xs:restriction&gt;
 *    &lt;/xs:simpleType&gt;
 * &lt;/xs:element&gt;
 * @author João Rebelo
 * @since 1.0.0
 */
class PaymentMechanism
    extends \Rebelo\Enum\AEnum
{

    /**
     * CC para Cartao credito<br>
     * &lt;xs:enumeration value="CC"/&gt;
     * @since 1.0.0
     */
    const CC = "CC";

    /**
     * CD para Cartao debito<br>
     * &lt;xs:enumeration value="CD"/&gt;
     *
     * @since 1.0.0
     */
    const CD = "CD";

    /**
     * CH para Cheque bancario<br>
     * &lt;xs:enumeration value="CH"/&gt;
     * @since 1.0.0
     */
    const CH = "CH";

    /**
     * CI para credito documentario internacional<br>
     * &lt;xs:enumeration value="CI"/&gt;
     * @since 1.0.0
     */
    const CI = "CI";

    /**
     * CO para Cheque ou cartao oferta<br>
     * &lt;xs:enumeration value="CO"/&gt;
     * @since 1.0.0
     */
    const CO = "CO";

    /**
     * CS para Compensacao de saldos em conta corrente<br>
     * &lt;xs:enumeration value="CS"/&gt;
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * DE para Dinheiro eletronico,
     * por exemplo em cartoes de fidelidade ou de pontos<br>
     * &lt;xs:enumeration value="DE"/&gt;
     * @since 1.0.0
     */
    const DE = "DE";

    /**
     * LC para Letra comercial<br>
     * &lt;xs:enumeration value="LC"/&gt;
     * @since 1.0.0
     */
    const LC = "LC";

    /**
     * MB para Referencias de pagamento para Multibanco<br>
     * &lt;xs:enumeration value="MB"/&gt;
     * @since 1.0.0
     */
    const MB = "MB";

    /**
     * NU para Numerario<br>
     * &lt;xs:enumeration value="NU"/&gt;
     * @since 1.0.0
     */
    const NU = "NU";

    /**
     * OU para Outros meios aqui nao assinalados<br>
     * &lt;xs:enumeration value="OU"/&gt;
     * @since 1.0.0
     */
    const OU = "OU";

    /**
     * PR para Permuta de bens<br>
     * &lt;xs:enumeration value="PR"/&gt;
     * @since 1.0.0
     */
    const PR = "PR";

    /**
     * TB para Transferencia bancaria ou debito direto autorizado<br>
     * &lt;xs:enumeration value="TB"/&gt;
     * @since 1.0.0
     */
    const TB = "TB";

    /**
     * TR para titulos de compensacao extrassalarial
     * independentemente do seu suporte<br>
     * &lt;xs:enumeration value="TR"/&gt;
     * @since 1.0.0
     */
    const TR = "TR";

    /**
     *
     * &lt;xs:element name="PaymentMechanism"&gt;
     *    &lt;xs:annotation&gt;
     *        &lt;xs:documentation&gt; Restricao:CC para Cartao credito, CD para Cartao debito, CH para
     *            Cheque bancario, CI para credito documentario internacional, CO para Cheque ou
     *            cartao oferta, CS para Compensacao de saldos em conta corrente, DE para Dinheiro
     *            eletronico, por exemplo em cartoes de fidelidade ou de pontos, LC para Letra
     *            comercial, MB para Referencias de pagamento para Multibanco, NU para Numerario, OU
     *            para Outros meios aqui nao assinalados, PR para Permuta de bens, TB para
     *            Transferencia bancaria ou debito direto autorizado, TR para titulos de compensacao
     *            extrassalarial independentemente do seu suporte, por exemplo, titulos de refeicao,
     *            educacao, etc. &lt;/xs:documentation&gt;
     *    &lt;/xs:annotation&gt;
     *    &lt;xs:simpleType&gt;
     *        &lt;xs:restriction base="xs:string"&gt;
     *            &lt;xs:enumeration value="CC"/&gt;
     *            &lt;xs:enumeration value="CD"/&gt;
     *            &lt;xs:enumeration value="CH"/&gt;
     *            &lt;xs:enumeration value="CI"/&gt;
     *            &lt;xs:enumeration value="CO"/&gt;
     *            &lt;xs:enumeration value="CS"/&gt;
     *            &lt;xs:enumeration value="DE"/&gt;
     *            &lt;xs:enumeration value="LC"/&gt;
     *            &lt;xs:enumeration value="MB"/&gt;
     *            &lt;xs:enumeration value="NU"/&gt;
     *            &lt;xs:enumeration value="OU"/&gt;
     *            &lt;xs:enumeration value="PR"/&gt;
     *            &lt;xs:enumeration value="TB"/&gt;
     *            &lt;xs:enumeration value="TR"/&gt;
     *        &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     *
     * @param string $value
     * @return \Rebelo\Enum\type
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        return parent::__construct($value);
    }

}
