<?php

namespace Rebelo\SaftPt;

/**
 * Class representing MovementStatus
 *
 * N para Normal, T para Por conta de terceiros, A para Documento
 *  anulado, F para Documento faturado, quando para este documento tambem existe na
 *  tabela 4.1. para Documentos comerciais a clientes (SalesInvoices) o correspondente
 *  do tipo fatura ou fatura simplificada, R para Documento de resumo doutros documentos
 *  criados noutras aplicacoes e gerado nesta aplicacao
 */
class MovementStatus
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

