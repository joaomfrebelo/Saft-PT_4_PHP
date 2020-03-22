<?php

namespace Rebelo\SaftPt;

/**
 * Class representing SpecialRegimesType
 *
 * 
 * XSD Type: SpecialRegimes
 */
class SpecialRegimesType
{

    /**
     * @var int $selfBillingIndicator
     */
    private $selfBillingIndicator = null;

    /**
     * @var int $cashVATSchemeIndicator
     */
    private $cashVATSchemeIndicator = null;

    /**
     * @var int $thirdPartiesBillingIndicator
     */
    private $thirdPartiesBillingIndicator = null;

    /**
     * Gets as selfBillingIndicator
     *
     * @return int
     */
    public function getSelfBillingIndicator()
    {
        return $this->selfBillingIndicator;
    }

    /**
     * Sets a new selfBillingIndicator
     *
     * @param int $selfBillingIndicator
     * @return self
     */
    public function setSelfBillingIndicator($selfBillingIndicator)
    {
        $this->selfBillingIndicator = $selfBillingIndicator;
        return $this;
    }

    /**
     * Gets as cashVATSchemeIndicator
     *
     * @return int
     */
    public function getCashVATSchemeIndicator()
    {
        return $this->cashVATSchemeIndicator;
    }

    /**
     * Sets a new cashVATSchemeIndicator
     *
     * @param int $cashVATSchemeIndicator
     * @return self
     */
    public function setCashVATSchemeIndicator($cashVATSchemeIndicator)
    {
        $this->cashVATSchemeIndicator = $cashVATSchemeIndicator;
        return $this;
    }

    /**
     * Gets as thirdPartiesBillingIndicator
     *
     * @return int
     */
    public function getThirdPartiesBillingIndicator()
    {
        return $this->thirdPartiesBillingIndicator;
    }

    /**
     * Sets a new thirdPartiesBillingIndicator
     *
     * @param int $thirdPartiesBillingIndicator
     * @return self
     */
    public function setThirdPartiesBillingIndicator($thirdPartiesBillingIndicator)
    {
        $this->thirdPartiesBillingIndicator = $thirdPartiesBillingIndicator;
        return $this;
    }


}

