<?php

namespace Rebelo\SaftPt;

/**
 * Class representing GeneralLedgerEntries
 */
class GeneralLedgerEntries
{

    /**
     * @var int $numberOfEntries
     */
    private $numberOfEntries = null;

    /**
     * @var float $totalDebit
     */
    private $totalDebit = null;

    /**
     * @var float $totalCredit
     */
    private $totalCredit = null;

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType[] $journal
     */
    private $journal = [
        
    ];

    /**
     * Gets as numberOfEntries
     *
     * @return int
     */
    public function getNumberOfEntries()
    {
        return $this->numberOfEntries;
    }

    /**
     * Sets a new numberOfEntries
     *
     * @param int $numberOfEntries
     * @return self
     */
    public function setNumberOfEntries($numberOfEntries)
    {
        $this->numberOfEntries = $numberOfEntries;
        return $this;
    }

    /**
     * Gets as totalDebit
     *
     * @return float
     */
    public function getTotalDebit()
    {
        return $this->totalDebit;
    }

    /**
     * Sets a new totalDebit
     *
     * @param float $totalDebit
     * @return self
     */
    public function setTotalDebit($totalDebit)
    {
        $this->totalDebit = $totalDebit;
        return $this;
    }

    /**
     * Gets as totalCredit
     *
     * @return float
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * Sets a new totalCredit
     *
     * @param float $totalCredit
     * @return self
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

    /**
     * Adds as journal
     *
     * @return self
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType $journal
     */
    public function addToJournal(\Rebelo\SaftPt\GeneralLedgerEntries\JournalAType $journal)
    {
        $this->journal[] = $journal;
        return $this;
    }

    /**
     * isset journal
     *
     * @param int|string $index
     * @return bool
     */
    public function issetJournal($index)
    {
        return isset($this->journal[$index]);
    }

    /**
     * unset journal
     *
     * @param int|string $index
     * @return void
     */
    public function unsetJournal($index)
    {
        unset($this->journal[$index]);
    }

    /**
     * Gets as journal
     *
     * @return \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType[]
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Sets a new journal
     *
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType[] $journal
     * @return self
     */
    public function setJournal(array $journal)
    {
        $this->journal = $journal;
        return $this;
    }


}

