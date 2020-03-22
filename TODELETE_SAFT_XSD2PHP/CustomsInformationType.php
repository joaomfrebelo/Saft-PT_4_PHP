<?php

namespace Rebelo\SaftPt;

/**
 * Class representing CustomsInformationType
 *
 * 
 * XSD Type: CustomsInformation
 */
class CustomsInformationType
{

    /**
     * @var string[] $aRCNo
     */
    private $aRCNo = [
        
    ];

    /**
     * @var float $iECAmount
     */
    private $iECAmount = null;

    /**
     * Adds as aRCNo
     *
     * @return self
     * @param string $aRCNo
     */
    public function addToARCNo($aRCNo)
    {
        $this->aRCNo[] = $aRCNo;
        return $this;
    }

    /**
     * isset aRCNo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetARCNo($index)
    {
        return isset($this->aRCNo[$index]);
    }

    /**
     * unset aRCNo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetARCNo($index)
    {
        unset($this->aRCNo[$index]);
    }

    /**
     * Gets as aRCNo
     *
     * @return string[]
     */
    public function getARCNo()
    {
        return $this->aRCNo;
    }

    /**
     * Sets a new aRCNo
     *
     * @param string $aRCNo
     * @return self
     */
    public function setARCNo(array $aRCNo)
    {
        $this->aRCNo = $aRCNo;
        return $this;
    }

    /**
     * Gets as iECAmount
     *
     * @return float
     */
    public function getIECAmount()
    {
        return $this->iECAmount;
    }

    /**
     * Sets a new iECAmount
     *
     * @param float $iECAmount
     * @return self
     */
    public function setIECAmount($iECAmount)
    {
        $this->iECAmount = $iECAmount;
        return $this;
    }


}

