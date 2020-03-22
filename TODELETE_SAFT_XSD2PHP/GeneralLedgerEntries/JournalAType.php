<?php

namespace Rebelo\SaftPt\GeneralLedgerEntries;

/**
 * Class representing JournalAType
 */
class JournalAType
{

    /**
     * @var string $journalID
     */
    private $journalID = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType[] $transaction
     */
    private $transaction = [
        
    ];

    /**
     * Gets as journalID
     *
     * @return string
     */
    public function getJournalID()
    {
        return $this->journalID;
    }

    /**
     * Sets a new journalID
     *
     * @param string $journalID
     * @return self
     */
    public function setJournalID($journalID)
    {
        $this->journalID = $journalID;
        return $this;
    }

    /**
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Adds as transaction
     *
     * @return self
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType $transaction
     */
    public function addToTransaction(\Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType $transaction)
    {
        $this->transaction[] = $transaction;
        return $this;
    }

    /**
     * isset transaction
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTransaction($index)
    {
        return isset($this->transaction[$index]);
    }

    /**
     * unset transaction
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTransaction($index)
    {
        unset($this->transaction[$index]);
    }

    /**
     * Gets as transaction
     *
     * @return \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType[]
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Sets a new transaction
     *
     * @param \Rebelo\SaftPt\GeneralLedgerEntries\JournalAType\TransactionAType[] $transaction
     * @return self
     */
    public function setTransaction(array $transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }


}

