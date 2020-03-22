<?php

namespace Rebelo\SaftPt\TaxTable;

/**
 * Class representing TaxTableAType
 */
class TaxTableAType
{

    /**
     * @var \Rebelo\SaftPt\TaxTableEntry[] $taxTableEntry
     */
    private $taxTableEntry = [
        
    ];

    /**
     * Adds as taxTableEntry
     *
     * @return self
     * @param \Rebelo\SaftPt\TaxTableEntry $taxTableEntry
     */
    public function addToTaxTableEntry(\Rebelo\SaftPt\TaxTableEntry $taxTableEntry)
    {
        $this->taxTableEntry[] = $taxTableEntry;
        return $this;
    }

    /**
     * isset taxTableEntry
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTaxTableEntry($index)
    {
        return isset($this->taxTableEntry[$index]);
    }

    /**
     * unset taxTableEntry
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTaxTableEntry($index)
    {
        unset($this->taxTableEntry[$index]);
    }

    /**
     * Gets as taxTableEntry
     *
     * @return \Rebelo\SaftPt\TaxTableEntry[]
     */
    public function getTaxTableEntry()
    {
        return $this->taxTableEntry;
    }

    /**
     * Sets a new taxTableEntry
     *
     * @param \Rebelo\SaftPt\TaxTableEntry[] $taxTableEntry
     * @return self
     */
    public function setTaxTableEntry(array $taxTableEntry)
    {
        $this->taxTableEntry = $taxTableEntry;
        return $this;
    }


}

