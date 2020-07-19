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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\SaftPt\AuditFile\{
    AuditFileException,
    SourceDocuments\SourceDocuments
};

/**
 * Description of SalesInvoices
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SalesInvoices extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SALESINVOICES = "SalesInvoices";

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
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice[]
     * @since 1.0.0
     */
    private array $invoice = array();

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get NumberOfEntries
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->numberOfEntries));
        return $this->numberOfEntries;
    }

    /**
     *
     * @param int $numberOfEntries
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): void
    {
        if ($numberOfEntries < 0) {
            $msg = "NumberOdEntries can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->numberOfEntries));
    }

    /**
     * Get TotalDebit
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->totalDebit));
        return $this->totalDebit;
    }

    /**
     * Set TotalDebit
     * @param float $totalDebit
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): void
    {
        if ($totalDebit < 0) {
            $msg = "TotalDebit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->totalDebit));
    }

    /**
     * Get TotalCredit
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->totalCredit));
        return $this->totalCredit;
    }

    /**
     * Set TotalCredit
     * @param float $totalCredit
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): void
    {
        if ($totalCredit < 0) {
            $msg = "TotalCredit can not be less than zero";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->totalCredit));
    }

    /**
     * Get Invoice Stack
     * <xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Invoice[]
     * @since 1.0.0
     */
    public function getInvoice(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted '%s'");
        return $this->invoice;
    }

    /**
     * getInvoice
     * <xs:element name="Invoice" minOccurs="0" maxOccurs="unbounded">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice $invoice
     * @return int
     * @since 1.0.0
     */
    public function addToInvoice(Invoice $invoice): int
    {
        if (\count($this->invoice) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->invoice);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->invoice[$index] = $invoice;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, "Invoice add to index ".\strval($index));
        return $index;
    }

    /**
     * isset Invoice
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoice(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->invoice[$index]);
    }

    /**
     * unset Invoice
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetInvoice(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->invoice[$index]);
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
        if ($node->getName() !== SourceDocuments::N_SOURCEDOCUMENTS) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $salesNode = $node->addChild(static::N_SALESINVOICES);

        $salesNode->addChild(
            static::N_NUMBEROFENTRIES, \strval($this->getNumberOfEntries())
        );
        $salesNode->addChild(
            static::N_TOTALDEBIT, $this->floatFormat($this->getTotalDebit())
        );
        $salesNode->addChild(
            static::N_TOTALCREDIT, $this->floatFormat($this->getTotalCredit())
        );
        foreach ($this->getInvoice() as $invoice) {
            /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
            $invoice->createXmlNode($salesNode);
        }
        return $salesNode;
    }

    /**
     * Parse Xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SALESINVOICES) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_SALESINVOICES, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $nMax = $node->{Invoice::N_INVOICE}->count();
        for ($n = 0; $n < $nMax; $n++) {
            $invoice = new Invoice();
            $invoice->parseXmlNode($node->{Invoice::N_INVOICE}[$n]);
            $this->addToInvoice($invoice);
        }
    }
}