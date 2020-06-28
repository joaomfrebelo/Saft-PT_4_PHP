<?php
/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\SaftPt\AuditFile\{
    AuditFileException,
    SourceDocuments\ADocument,
    SourceDocuments\SourceDocuments
};

/**
 * MovementOfGoods
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementOfGoods extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * <xs:element name="MovementOfGoods" minOccurs="0">
     * Node Name
     * @since 1.0.0
     */
    const N_MOVEMENTOFGOODS = "MovementOfGoods";

    /**
     * <xs:element ref="NumberOfMovementLines"/>
     * Node Name
     * @since 1.0.0
     */
    const N_NUMBEROFMOVEMENTLINES = "NumberOfMovementLines";

    /**
     * <xs:element ref="TotalQuantityIssued"/>
     * Node Name
     * @since 1.0.0
     */
    const N_TOTALQUANTITYISSUED = "TotalQuantityIssued";

    /**
     * <xs:element ref="NumberOfMovementLines"/>
     * @var int
     * @since 1.0.0
     */
    private int $numberOfMovementLines;

    /**
     * <xs:element ref="TotalQuantityIssued"/>
     * @var int
     * @since 1.0.0
     */
    private float $totalQuantityIssued;

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement[]
     * @since 1.0.0
     */
    private array $stockMovement = array();

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get NumberOfMovementLines<br>
     * <xs:element ref="NumberOfMovementLines"/>
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfMovementLines(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->numberOfMovementLines));
        return $this->numberOfMovementLines;
    }

    /**
     * Get NumberOfMovementLines
     * <xs:element ref="NumberOfMovementLines"/>
     * @param int $numberOfMovementLines
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setNumberOfMovementLines(int $numberOfMovementLines): void
    {
        if ($numberOfMovementLines < 0) {
            $msg = "NumberOfMovementLines can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->numberOfMovementLines = $numberOfMovementLines;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->numberOfMovementLines
        ));
    }

    /**
     * Get TotalQuantityIssued
     * <xs:element ref="TotalQuantityIssued"/>
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalQuantityIssued(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->totalQuantityIssued));
        return $this->totalQuantityIssued;
    }

    /**
     * Set TotalQuantityIssued
     * <xs:element ref="TotalQuantityIssued"/>
     * @param float $totalQuantityIssued
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTotalQuantityIssued(float $totalQuantityIssued): void
    {
        if ($totalQuantityIssued < 0) {
            $msg = "TotalQuantityIssued can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalQuantityIssued = $totalQuantityIssued;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->totalQuantityIssued
        ));
    }

    /**
     * Get StockMovement
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement[]
     * @since 1.0.0
     */
    public function getStockMovement(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted with '%s' elements",
                    \count($this->stockMovement)));
        return $this->stockMovement;
    }

    /**
     * Add StockMovement to the stack
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMovement
     * @return int
     * @since 1.0.0
     */
    public function addToStockMovement(StockMovement $stockMovement): int
    {
        if (\count($this->stockMovement) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->stockMovement);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->stockMovement[$index] = $stockMovement;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " Line add to index ".\strval($index));
        return $index;
    }

    /**
     * isset stockMovement
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetStockMovement(int $index): bool
    {
        return isset($this->stockMovement[$index]);
    }

    /**
     * unset stockMovement
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetStockMovement(int $index): void
    {
        unset($this->stockMovement[$index]);
    }

    /**
     * Create Xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Error
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== SourceDocuments::N_SOURCEDOCUMENTS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $mogNode = $node->addChild(static::N_MOVEMENTOFGOODS);
        $mogNode->addChild(
            static::N_NUMBEROFMOVEMENTLINES,
            \strval($this->getNumberOfMovementLines())
        );
        $mogNode->addChild(
            static::N_TOTALQUANTITYISSUED,
            $this->floatFormat($this->getTotalQuantityIssued())
        );

        foreach ($this->getStockMovement() as $stkMv) {
            /* @var $stkMv \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement */
            $stkMv->createXmlNode($mogNode);
        }

        return $mogNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_MOVEMENTOFGOODS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_MOVEMENTOFGOODS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfMovementLines(
            (int) $node->{static::N_NUMBEROFMOVEMENTLINES}
        );

        $this->setTotalQuantityIssued(
            (float) $node->{static::N_TOTALQUANTITYISSUED}
        );

        $stkMovCount = $node->{StockMovement::N_STOCKMOVEMENT}->count();
        for ($n = 0; $n < $stkMovCount; $n++) {
            $stkMov = new StockMovement();
            $stkMov->parseXmlNode($node->{StockMovement::N_STOCKMOVEMENT}[$n]);
            $this->addToStockMovement($stkMov);
        }
    }
}