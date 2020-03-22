<?php

namespace Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType;

/**
 * Class representing LinesAType
 */
class LinesAType
{

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\DebitLineAType[] $debitLine
     */
    private $debitLine = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\CreditLineAType[] $creditLine
     */
    private $creditLine = [
        
    ];

    /**
     * Adds as debitLine
     *
     * @return self
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\DebitLineAType $debitLine
     */
    public function addToDebitLine(\Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\DebitLineAType $debitLine)
    {
        $this->debitLine[] = $debitLine;
        return $this;
    }

    /**
     * isset debitLine
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDebitLine($index)
    {
        return isset($this->debitLine[$index]);
    }

    /**
     * unset debitLine
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDebitLine($index)
    {
        unset($this->debitLine[$index]);
    }

    /**
     * Gets as debitLine
     *
     * @return \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\DebitLineAType[]
     */
    public function getDebitLine()
    {
        return $this->debitLine;
    }

    /**
     * Sets a new debitLine
     *
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\DebitLineAType[] $debitLine
     * @return self
     */
    public function setDebitLine(array $debitLine)
    {
        $this->debitLine = $debitLine;
        return $this;
    }

    /**
     * Adds as creditLine
     *
     * @return self
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\CreditLineAType $creditLine
     */
    public function addToCreditLine(\Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\CreditLineAType $creditLine)
    {
        $this->creditLine[] = $creditLine;
        return $this;
    }

    /**
     * isset creditLine
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCreditLine($index)
    {
        return isset($this->creditLine[$index]);
    }

    /**
     * unset creditLine
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCreditLine($index)
    {
        unset($this->creditLine[$index]);
    }

    /**
     * Gets as creditLine
     *
     * @return \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\CreditLineAType[]
     */
    public function getCreditLine()
    {
        return $this->creditLine;
    }

    /**
     * Sets a new creditLine
     *
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType\LinesAType\CreditLineAType[] $creditLine
     * @return self
     */
    public function setCreditLine(array $creditLine)
    {
        $this->creditLine = $creditLine;
        return $this;
    }


}

