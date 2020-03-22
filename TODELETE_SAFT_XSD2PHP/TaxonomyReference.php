<?php

namespace Rebelo\SaftPt;

/**
 * Class representing TaxonomyReference
 *
 * S para SNC base (Taxonomia S), M para SNC microentidades (Taxonomia
 *  M), N para Normas Internacionais de Contabilidade (Taxonomia S), O para outros
 *  referenciais contabilisticos cuja taxonomia nao se encontra
 *  codificada
 */
class TaxonomyReference
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

