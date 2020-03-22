<?php

namespace Rebelo\SaftPt;

/**
 * Class representing GeneralLedgerAccounts
 */
class GeneralLedgerAccounts
{

    /**
     * @var string $taxonomyReference
     */
    private $taxonomyReference = null;

    /**
     * @var \Rebelo\SaftPt\GeneralLedgerAccounts\AccountAType[] $account
     */
    private $account = [
        
    ];

    /**
     * Gets as taxonomyReference
     *
     * @return string
     */
    public function getTaxonomyReference()
    {
        return $this->taxonomyReference;
    }

    /**
     * Sets a new taxonomyReference
     *
     * @param string $taxonomyReference
     * @return self
     */
    public function setTaxonomyReference($taxonomyReference)
    {
        $this->taxonomyReference = $taxonomyReference;
        return $this;
    }

    /**
     * Adds as account
     *
     * @return self
     * @param \Rebelo\SaftPt\GeneralLedgerAccounts\AccountAType $account
     */
    public function addToAccount(\Rebelo\SaftPt\GeneralLedgerAccounts\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAccount($index)
    {
        return isset($this->account[$index]);
    }

    /**
     * unset account
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAccount($index)
    {
        unset($this->account[$index]);
    }

    /**
     * Gets as account
     *
     * @return \Rebelo\SaftPt\GeneralLedgerAccounts\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * @param \Rebelo\SaftPt\GeneralLedgerAccounts\AccountAType[] $account
     * @return self
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

