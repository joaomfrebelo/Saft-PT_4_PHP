<?php

namespace Rebelo\SaftPt\SourceDocuments;

/**
 * Class representing MovementOfGoodsAType
 */
class MovementOfGoodsAType
{

    /**
     * @var int $numberOfMovementLines
     */
    private $numberOfMovementLines = null;

    /**
     * @var float $totalQuantityIssued
     */
    private $totalQuantityIssued = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType[] $stockMovement
     */
    private $stockMovement = [
        
    ];

    /**
     * Gets as numberOfMovementLines
     *
     * @return int
     */
    public function getNumberOfMovementLines()
    {
        return $this->numberOfMovementLines;
    }

    /**
     * Sets a new numberOfMovementLines
     *
     * @param int $numberOfMovementLines
     * @return self
     */
    public function setNumberOfMovementLines($numberOfMovementLines)
    {
        $this->numberOfMovementLines = $numberOfMovementLines;
        return $this;
    }

    /**
     * Gets as totalQuantityIssued
     *
     * @return float
     */
    public function getTotalQuantityIssued()
    {
        return $this->totalQuantityIssued;
    }

    /**
     * Sets a new totalQuantityIssued
     *
     * @param float $totalQuantityIssued
     * @return self
     */
    public function setTotalQuantityIssued($totalQuantityIssued)
    {
        $this->totalQuantityIssued = $totalQuantityIssued;
        return $this;
    }

    /**
     * Adds as stockMovement
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType $stockMovement
     */
    public function addToStockMovement(\Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType $stockMovement)
    {
        $this->stockMovement[] = $stockMovement;
        return $this;
    }

    /**
     * isset stockMovement
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStockMovement($index)
    {
        return isset($this->stockMovement[$index]);
    }

    /**
     * unset stockMovement
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStockMovement($index)
    {
        unset($this->stockMovement[$index]);
    }

    /**
     * Gets as stockMovement
     *
     * @return \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType[]
     */
    public function getStockMovement()
    {
        return $this->stockMovement;
    }

    /**
     * Sets a new stockMovement
     *
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType[] $stockMovement
     * @return self
     */
    public function setStockMovement(array $stockMovement)
    {
        $this->stockMovement = $stockMovement;
        return $this;
    }


}

