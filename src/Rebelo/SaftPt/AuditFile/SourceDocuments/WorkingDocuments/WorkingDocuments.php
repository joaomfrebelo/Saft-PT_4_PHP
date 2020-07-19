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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Description of WorkingDocuments
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkingDocuments extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_WORKINGDOCUMENTS = "WorkingDocuments";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_NUMBEROFENTRIES = "NumberOfEntries";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALDEBIT = "TotalDebit";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALCREDIT = "TotalCredit";

    /**
     * <xs:element ref="NumberOfEntries"/>
     * @var int
     * @since 1.0.0
     */
    private int $numberOfEntries;

    /**
     * <xs:element ref="TotalDebit"/>
     * @var float
     * @since 1.0.0
     */
    private float $totalDebit;

    /**
     * <xs:element ref="TotalCredit"/>
     * @var float
     * @since 1.0.0
     */
    private float $totalCredit;

    /**
     * <xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[]
     * @since 1.0.0
     */
    private array $workDocument = array();

    /**
     *
     *   &lt;xs:element name="WorkingDocuments" minOccurs="0"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="NumberOfEntries"/&gt;
     *           &lt;xs:element ref="TotalDebit"/&gt;
     *           &lt;xs:element ref="TotalCredit"/&gt;
     *           &lt;xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded"&gt;&lt;/xs:element&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get NumberOfEntries<br>
     * <xs:element ref="NumberOfEntries"/>
     * @return int
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->numberOfEntries)));
        return $this->numberOfEntries;
    }

    /**
     * Set NumberOfEntries<br>
     * <xs:element ref="NumberOfEntries"/>
     * @param int $numberOfEntries
     * @return void
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): void
    {
        if ($numberOfEntries < 0) {
            $msg = "NumberOfEntries can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->numberOfEntries)));
    }

    /**
     * Set TotalDebit<br>
     * <xs:element ref="TotalDebit"/>
     * @return float
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->totalDebit)));
        return $this->totalDebit;
    }

    /**
     * Get TotalDebit<br>
     * <xs:element ref="TotalDebit"/>
     * @param float $totalDebit
     * @return void
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): void
    {
        if ($totalDebit < 0) {
            $msg = "TotalDebit can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->totalDebit)));
    }

    /**
     * Set TotalCredit<br>
     * <xs:element ref="TotalCredit"/>
     * @return float
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    \strval($this->totalCredit)));
        return $this->totalCredit;
    }

    /**
     * Get TotalCredit<br>
     * <xs:element ref="TotalCredit"/>
     * @param float $totalCredit
     * @return void
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): void
    {
        if ($totalCredit < 0) {
            $msg = "TotalCredit can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    \strval($this->totalCredit)));
    }

    /**
     * Get WorkDocument Stack<br>
     * <xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[]
     * @since 1.0.0
     */
    public function getWorkDocument(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "WorkDocument stack"));
        return $this->workDocument;
    }

    /**
     * Add WorkDocument to the stack<br>
     * <xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument $workDocument
     * @return int
     * @since 1.0.0
     */
    public function addToWorkDocument(WorkDocument $workDocument): int
    {
        if (\count($this->workDocument) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->workDocument);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->workDocument[$index] = $workDocument;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, "WorkDocument add to index ".\strval($index));
        return $index;
    }

    /**
     * isset workDocument
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkDocument(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->workDocument[$index]);
    }

    /**
     * unset workDocument
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetWorkDocument(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->workDocument[$index]);
    }

    /**
     * Create XML node
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
        $workingNode = $node->addChild(static::N_WORKINGDOCUMENTS);
        $workingNode->addChild(
            static::N_NUMBEROFENTRIES, \strval($this->getNumberOfEntries())
        );
        $workingNode->addChild(
            static::N_TOTALDEBIT, $this->floatFormat($this->getTotalDebit())
        );
        $workingNode->addChild(
            static::N_TOTALCREDIT, $this->floatFormat($this->getTotalCredit())
        );

        foreach ($this->getWorkDocument() as $workDocument) {
            /* @var $workDocument WorkDocument */
            $workDocument->createXmlNode($workingNode);
        }

        return $workingNode;
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_WORKINGDOCUMENTS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_WORKINGDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $nMax = $node->{WorkDocument::N_WORKDOCUMENT}->count();
        for ($n = 0; $n < $nMax; $n++) {
            $workDocument = new WorkDocument();
            $workDocument->parseXmlNode($node->{WorkDocument::N_WORKDOCUMENT}[$n]);
            $this->addToWorkDocument($workDocument);
        }
    }
}