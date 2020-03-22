<?php

namespace Rebelo\SaftPt\GeneralLedgerAccounts;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * @var string $accountID
     */
    private $accountID = null;

    /**
     * @var string $accountDescription
     */
    private $accountDescription = null;

    /**
     * @var float $openingDebitBalance
     */
    private $openingDebitBalance = null;

    /**
     * @var float $openingCreditBalance
     */
    private $openingCreditBalance = null;

    /**
     * @var float $closingDebitBalance
     */
    private $closingDebitBalance = null;

    /**
     * @var float $closingCreditBalance
     */
    private $closingCreditBalance = null;

    /**
     * @var string $groupingCategory
     */
    private $groupingCategory = null;

    /**
     * @var string $groupingCode
     */
    private $groupingCode = null;

    /**
     * @var int $taxonomyCode
     */
    private $taxonomyCode = null;

    /**
     * Gets as accountID
     *
     * @return string
     */
    public function getAccountID()
    {
        return $this->accountID;
    }

    /**
     * Sets a new accountID
     *
     * @param string $accountID
     * @return self
     */
    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;
        return $this;
    }

    /**
     * Gets as accountDescription
     *
     * @return string
     */
    public function getAccountDescription()
    {
        return $this->accountDescription;
    }

    /**
     * Sets a new accountDescription
     *
     * @param string $accountDescription
     * @return self
     */
    public function setAccountDescription($accountDescription)
    {
        $this->accountDescription = $accountDescription;
        return $this;
    }

    /**
     * Gets as openingDebitBalance
     *
     * @return float
     */
    public function getOpeningDebitBalance()
    {
        return $this->openingDebitBalance;
    }

    /**
     * Sets a new openingDebitBalance
     *
     * @param float $openingDebitBalance
     * @return self
     */
    public function setOpeningDebitBalance($openingDebitBalance)
    {
        $this->openingDebitBalance = $openingDebitBalance;
        return $this;
    }

    /**
     * Gets as openingCreditBalance
     *
     * @return float
     */
    public function getOpeningCreditBalance()
    {
        return $this->openingCreditBalance;
    }

    /**
     * Sets a new openingCreditBalance
     *
     * @param float $openingCreditBalance
     * @return self
     */
    public function setOpeningCreditBalance($openingCreditBalance)
    {
        $this->openingCreditBalance = $openingCreditBalance;
        return $this;
    }

    /**
     * Gets as closingDebitBalance
     *
     * @return float
     */
    public function getClosingDebitBalance()
    {
        return $this->closingDebitBalance;
    }

    /**
     * Sets a new closingDebitBalance
     *
     * @param float $closingDebitBalance
     * @return self
     */
    public function setClosingDebitBalance($closingDebitBalance)
    {
        $this->closingDebitBalance = $closingDebitBalance;
        return $this;
    }

    /**
     * Gets as closingCreditBalance
     *
     * @return float
     */
    public function getClosingCreditBalance()
    {
        return $this->closingCreditBalance;
    }

    /**
     * Sets a new closingCreditBalance
     *
     * @param float $closingCreditBalance
     * @return self
     */
    public function setClosingCreditBalance($closingCreditBalance)
    {
        $this->closingCreditBalance = $closingCreditBalance;
        return $this;
    }

    /**
     * Gets as groupingCategory
     *
     * @return string
     */
    public function getGroupingCategory()
    {
        return $this->groupingCategory;
    }

    /**
     * Sets a new groupingCategory
     *
     * @param string $groupingCategory
     * @return self
     */
    public function setGroupingCategory($groupingCategory)
    {
        $this->groupingCategory = $groupingCategory;
        return $this;
    }

    /**
     * Gets as groupingCode
     *
     * @return string
     */
    public function getGroupingCode()
    {
        return $this->groupingCode;
    }

    /**
     * Sets a new groupingCode
     *
     * @param string $groupingCode
     * @return self
     */
    public function setGroupingCode($groupingCode)
    {
        $this->groupingCode = $groupingCode;
        return $this;
    }

    /**
     * Gets as taxonomyCode
     *
     * @return int
     */
    public function getTaxonomyCode()
    {
        return $this->taxonomyCode;
    }

    /**
     * Sets a new taxonomyCode
     *
     * @param int $taxonomyCode
     * @return self
     */
    public function setTaxonomyCode($taxonomyCode)
    {
        $this->taxonomyCode = $taxonomyCode;
        return $this;
    }


}

