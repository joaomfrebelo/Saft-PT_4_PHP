<?php

namespace Rebelo\SaftPt;

/**
 * Class representing PaymentMechanism
 *
 * Restricao:CC para Cartao credito, CD para Cartao debito, CH para
 *  Cheque bancario, CI para credito documentario internacional, CO para Cheque ou
 *  cartao oferta, CS para Compensacao de saldos em conta corrente, DE para Dinheiro
 *  eletronico, por exemplo em cartoes de fidelidade ou de pontos, LC para Letra
 *  comercial, MB para Referencias de pagamento para Multibanco, NU para Numerario, OU
 *  para Outros meios aqui nao assinalados, PR para Permuta de bens, TB para
 *  Transferencia bancaria ou debito direto autorizado, TR para titulos de compensacao
 *  extrassalarial independentemente do seu suporte, por exemplo, titulos de refeicao,
 *  educacao, etc.
 */
class PaymentMechanism
{

    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }


}

