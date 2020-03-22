<?php

namespace Rebelo\SaftPt;

/**
 * Class representing WorkType
 *
 * Restricao: DC para documentos emitidos ate 2017-06-30, CM para
 *  consulta de mesa, CC para credito de consignacao, FC para fatura de consignacao nos
 *  termos do art.38 do CIVA, FO para folha de obra, NE para nota de encomenda, OU para
 *  outros documentos suscetiveis de apresentacao ao cliente para conferencia de
 *  mercadorias ou de prestacao de servicos que nao se encontrem aqui devidamente
 *  identificados (ou seus equivalentes), OR para orcamento, PF para fatura pro-forma.
 *  Para o setor Segurador quando para os tipos de documentos a seguir identificados
 *  tambem deva existir na tabela 4.1 - Documentos comerciais a clientes (SalesInvoices)
 *  a correspondente fatura ou documento rectificativo de fatura, ainda pode ser
 *  preenchido com RP para premio ou recibo de premio, RE para estorno ou recibo de
 *  estorno, CS para imputacao a co-seguradoras, LD para imputacao a co-seguradora
 *  lider, RA para resseguro aceite.
 */
class WorkType
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

