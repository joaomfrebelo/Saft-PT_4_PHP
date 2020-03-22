<?php

namespace Rebelo\SaftPt;

/**
 * Class representing CustomsDetailsType
 *
 * 
 * XSD Type: CustomsDetails
 */
class CustomsDetailsType
{

    /**
     * @var string[] $cNCode
     */
    private $cNCode = [
        
    ];

    /**
     * @var string[] $uNNumber
     */
    private $uNNumber = [
        
    ];

    /**
     * Adds as cNCode
     *
     * @return self
     * @param string $cNCode
     */
    public function addToCNCode($cNCode)
    {
        $this->cNCode[] = $cNCode;
        return $this;
    }

    /**
     * isset cNCode
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCNCode($index)
    {
        return isset($this->cNCode[$index]);
    }

    /**
     * unset cNCode
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCNCode($index)
    {
        unset($this->cNCode[$index]);
    }

    /**
     * Gets as cNCode
     *
     * @return string[]
     */
    public function getCNCode()
    {
        return $this->cNCode;
    }

    /**
     * Sets a new cNCode
     *
     * @param string $cNCode
     * @return self
     */
    public function setCNCode(array $cNCode)
    {
        $this->cNCode = $cNCode;
        return $this;
    }

    /**
     * Adds as uNNumber
     *
     * @return self
     * @param string $uNNumber
     */
    public function addToUNNumber($uNNumber)
    {
        $this->uNNumber[] = $uNNumber;
        return $this;
    }

    /**
     * isset uNNumber
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUNNumber($index)
    {
        return isset($this->uNNumber[$index]);
    }

    /**
     * unset uNNumber
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUNNumber($index)
    {
        unset($this->uNNumber[$index]);
    }

    /**
     * Gets as uNNumber
     *
     * @return string[]
     */
    public function getUNNumber()
    {
        return $this->uNNumber;
    }

    /**
     * Sets a new uNNumber
     *
     * @param string $uNNumber
     * @return self
     */
    public function setUNNumber(array $uNNumber)
    {
        $this->uNNumber = $uNNumber;
        return $this;
    }


}

