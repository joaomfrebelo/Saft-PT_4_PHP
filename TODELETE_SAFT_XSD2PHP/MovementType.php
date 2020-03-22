<?php

namespace Rebelo\SaftPt;

/**
 * Class representing MovementType
 *
 * Restricao: Tipos de Documento (GR para Guia de remessa, GT para Guia
 *  de transporte incluindo as globais, GA para Guia de movimentacao de ativos fixos
 *  proprios, GC para Guia de consignacao, GD para Guia ou nota de devolucao
 */
class MovementType
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

