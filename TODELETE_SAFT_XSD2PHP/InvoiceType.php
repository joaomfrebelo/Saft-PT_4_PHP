<?php

namespace Rebelo\SaftPt;

/**
 * Class representing InvoiceType
 *
 * Restricao:FT para Fatura, emitida nos termos do artigo 36. do Codigo
 *  do IVA, FS para Fatura simplificada, emitida nos termos do artigo 40. do Codigo do
 *  IVA, FR para Fatura-recibo, ND para Nota de debito, NC para Nota de credito, VD para
 *  Venda a dinheiro e factura/recibo (a), TV para Talao de venda (a), TD para Talao de
 *  devolucao (a), AA para Alienacao de ativos (a), DA para Devolucao de ativos (a).
 *  Para o setor Segurador, ainda pode ser preenchido com: RP para Premio ou recibo de
 *  premio, RE para Estorno ou recibo de estorno, CS para Imputacao a co-seguradoras, LD
 *  para Imputacao a co-seguradora lider, RA para Resseguro aceite. (a) Para os dados
 *  ate 2012-12-31
 */
class InvoiceType
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

