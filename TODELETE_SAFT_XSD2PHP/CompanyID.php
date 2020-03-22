<?php

namespace Rebelo\SaftPt;

/**
 * Class representing CompanyID
 *
 * Concatenacao da Conservatoria do Registo Comercial com o numero do
 *  registo comercial separados pelo caracter espaco. Nos casos em que nao existe o
 *  registo comercial, deve ser indicado o NIF.
 */
class CompanyID
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

