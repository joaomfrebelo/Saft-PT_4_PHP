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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\SaftPt\AuditFile\{
    AuditFileException,
    ErrorRegister,
    SourceDocuments\SourceDocuments,
    AAuditFile
};
use Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc;

/**
 * MovementOfGoods<br>
 * The documents to be exported are any transport documents or delivery
 * notes that serve as transport documents, as provided for under the
 * “Regime de bens em Circulação” [Goods Circulation Regime],
 * approved by the Decree No. 147/2003 of 11th July.
 * The documents listed under 4.1. –SalesInvoices also used as
 * transport documents (invoices for example) shall not be exported here.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementOfGoods extends AAuditFile
{
    /**
     * &lt;xs:element name="MovementOfGoods" minOccurs="0">
     * Node Name
     * @since 1.0.0
     */
    const N_MOVEMENTOFGOODS = "MovementOfGoods";

    /**
     * &lt;xs:element ref="NumberOfMovementLines"/&gt;
     * Node Name
     * @since 1.0.0
     */
    const N_NUMBEROFMOVEMENTLINES = "NumberOfMovementLines";

    /**
     * &lt;xs:element ref="TotalQuantityIssued"/&gt;
     * Node Name
     * @since 1.0.0
     */
    const N_TOTALQUANTITYISSUED = "TotalQuantityIssued";

    /**
     * &lt;xs:element ref="NumberOfMovementLines"/&gt;
     * @var int
     * @since 1.0.0
     */
    private int $numberOfMovementLines;

    /**
     * &lt;xs:element ref="TotalQuantityIssued"/&gt;
     * @var float
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
     * @var \Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc|null
     * @since 1.0.0
     */
    protected ?MovOfGoodsTableTotalCalc $movOfGoodsTableTotalCalc = null;

    /**
     * $array[type][serie][number] = $stockMovement
     * \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement[]
     * @var array
     */
    protected array $order = array();

    /**
     * MovementOfGoods<br>
     * The documents to be exported are any transport documents or delivery
     * notes that serve as transport documents, as provided for under the
     * “Regime de bens em Circulação” [Goods Circulation Regime],
     * approved by the Decree No. 147/2003 of 11th July.
     * The documents listed under 4.1. –SalesInvoices also used as
     * transport documents (invoices for example) shall not be exported here.
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get the Doc Table resume calculation from validation classes
     * @return \Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc|null
     * @since 1.0.0
     */
    public function getMovOfGoodsTableTotalCalc(): ?MovOfGoodsTableTotalCalc
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        return $this->movOfGoodsTableTotalCalc;
    }

    /**
     * Get the Doc Table resume calculation from validation classes
     * @param \Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc|null $movOfGoodsTableTotalCalc
     * @return void
     * @since 1.0.0
     */
    public function setMovOfGoodsTableTotalCalc(?MovOfGoodsTableTotalCalc $movOfGoodsTableTotalCalc): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->movOfGoodsTableTotalCalc = $movOfGoodsTableTotalCalc;
    }

    /**
     * Get NumberOfMovementLines<br>
     * The field shall contain the total number of lines relevant for tax purposes,
     *  regarding the documents of the period, including the lines of the
     * documents which content in field 4.2.3.3.1. – MovementStatus, is type “A”.<br>
     * &lt;xs:element ref="NumberOfMovementLines"/&gt;
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfMovementLines(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " get '%s'",
                    $this->numberOfMovementLines
                )
            );
        return $this->numberOfMovementLines;
    }

    /**
     * Get if is set NumberOfMovementLines
     * @return bool
     * @since 1.0.0
     */
    public function issetNumberOfMovementLines(): bool
    {
        return isset($this->numberOfMovementLines);
    }

    /**
     * Get NumberOfMovementLines<br>
     * The field shall contain the total number of lines relevant for tax purposes,
     *  regarding the documents of the period, including the lines of the
     * documents which content in field 4.2.3.3.1. – MovementStatus, is type “A”.<br>
     * &lt;xs:element ref="NumberOfMovementLines"/&gt;
     * @param int $numberOfMovementLines
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNumberOfMovementLines(int $numberOfMovementLines): bool
    {
        if ($numberOfMovementLines < 0) {
            $msg = "NumberOfMovementLines can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NumberOfMovementLines_not_valid");
        } else {
            $return = true;
        }
        $this->numberOfMovementLines = $numberOfMovementLines;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " set to '%s'", $this->numberOfMovementLines
                )
            );
        return $return;
    }

    /**
     * Get TotalQuantityIssued<br>
     * The field shall contain the control sum of
     * field 4.2.3.21.5 – Quantity, excluding the lines of the documents
     * which content in field 4.2.3.3.1. - MovementStatus, is type “A”.<br>
     * &lt;xs:element ref="TotalQuantityIssued"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalQuantityIssued(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " get '%s'",
                    $this->totalQuantityIssued
                )
            );
        return $this->totalQuantityIssued;
    }

    /**
     * Get if is set TotalQuantityIssued
     * @return bool
     * @since 1.0.0
     */
    public function issetTotalQuantityIssued(): bool
    {
        return isset($this->totalQuantityIssued);
    }

    /**
     * Set TotalQuantityIssued<br>
     * The field shall contain the control sum of
     * field 4.2.3.21.5 – Quantity, excluding the lines of the documents
     * which content in field 4.2.3.3.1. - MovementStatus, is type “A”.<br>
     * &lt;xs:element ref="TotalQuantityIssued"/&gt;
     * @param float $totalQuantityIssued
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalQuantityIssued(float $totalQuantityIssued): bool
    {
        if ($totalQuantityIssued < 0) {
            $msg = "TotalQuantityIssued can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalQuantityIssued_not_valid");
        } else {
            $return = true;
        }
        $this->totalQuantityIssued = $totalQuantityIssued;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " set to '%s'", $this->totalQuantityIssued
                )
            );
        return $return;
    }

    /**
     * Get StockMovement
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement[]
     * @since 1.0.0
     */
    public function getStockMovement(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " get with '%s' elements",
                    \count($this->stockMovement)
                )
            );
        return $this->stockMovement;
    }

    /**
     * Create a new instance of StockMovement, add to the stack then
     * is returned to be populated<br>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement
     * @since 1.0.0
     */
    public function addStockMovement(): StockMovement
    {
        // Every time that a stockMovement is added the order is reset and is
        // contracted when called
        $this->order           = array();
        $stockMovement         = new StockMovement($this->getErrorRegistor());
        $this->stockMovement[] = $stockMovement;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__ . " Line add to stack"
        );
        return $stockMovement;
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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $mogNode = $node->addChild(static::N_MOVEMENTOFGOODS);

        if (isset($this->numberOfMovementLines)) {
            $mogNode->addChild(
                static::N_NUMBEROFMOVEMENTLINES,
                \strval($this->getNumberOfMovementLines())
            );
        } else {
            $mogNode->addChild(static::N_NUMBEROFMOVEMENTLINES);
            $this->getErrorRegistor()->addOnCreateXmlNode("NumberOfMovementLines_not_valid");
        }

        if (isset($this->totalQuantityIssued)) {
            $mogNode->addChild(
                static::N_TOTALQUANTITYISSUED,
                $this->floatFormat($this->getTotalQuantityIssued())
            );
        } else {
            $mogNode->addChild(static::N_TOTALQUANTITYISSUED);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalQuantityIssued_not_valid");
        }

        foreach ($this->getStockMovement() as $stkMv) {
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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_MOVEMENTOFGOODS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfMovementLines(
            (int)$node->{static::N_NUMBEROFMOVEMENTLINES}
        );

        $this->setTotalQuantityIssued(
            (float)$node->{static::N_TOTALQUANTITYISSUED}
        );

        $stkMovCount = $node->{StockMovement::N_STOCKMOVEMENT}->count();
        for ($n = 0; $n < $stkMovCount; $n++) {
            $this->addStockMovement()->parseXmlNode(
                $node->{StockMovement::N_STOCKMOVEMENT}[$n]
            );
        }
    }

    /**
     * Get StockMovement order by type/serie/number<br>
     * Ex: $stack[type][serie][InvoiceNo] = StockMovement<br>
     * If an error exist, th error is added to ValidationErrors stack
     * @return array<string, array<string , array<int, \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement>>>
     * @since 1.0.0
     */
    public function getOrder(): array
    {
        if (\count($this->order) > 0) {
            return $this->order;
        }

        foreach ($this->getStockMovement() as $k => $stkMv) {
            if ($stkMv->issetDocumentNumber() === false) {
                $msg = \sprintf(
                    AAuditFile::getI18n()->get("stock_move_at_index_no_number"), $k
                );
                $this->getErrorRegistor()->addValidationErrors($msg);
                $stkMv->addError($msg, StockMovement::N_DOCUMENTNUMBER);
                \Logger::getLogger(\get_class($this))->error($msg);
                continue;
            }

            list($type, $serie, $no) = \explode(
                " ",
                \str_replace("/", " ", $stkMv->getDocumentNumber())
            );

            if (\array_key_exists($type, $this->order)) {
                if (\array_key_exists($serie, $this->order[$type])) {
                    if (\array_key_exists(
                        \intval($no),
                        $this->order[$type][$serie]
                    )
                    ) {
                        $msg = \sprintf(
                            AAuditFile::getI18n()->get("duplicated_stock_mov"),
                            $stkMv->getDocumentNumber()
                        );
                        $this->getErrorRegistor()->addValidationErrors($msg);
                        $stkMv->addError($msg, StockMovement::N_STOCKMOVEMENT);
                        \Logger::getLogger(\get_class($this))->error($msg);
                    }
                }
            }
            $this->order[$type][$serie][\intval($no)] = $stkMv;
        }

        $cloneOrder = $this->order;

        foreach (\array_keys($cloneOrder) as $type) {
            foreach (\array_keys($cloneOrder[$type]) as $serie) {
                ksort($this->order[$type][$serie], SORT_NUMERIC);
            }
            ksort($this->order[$type], SORT_STRING);
        }
        ksort($this->order, SORT_STRING);

        return $this->order;
    }
}
