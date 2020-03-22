<?php

namespace Rebelo\SaftPt\SourceDocuments;

/**
 * Class representing SalesInvoicesAType
 */
class SalesInvoicesAType
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
     * @var \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType[] $invoice
     */
    private $invoice = [
        
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
     * Adds as invoice
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType $invoice
     */
    public function addToInvoice(\Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType $invoice)
    {
        $this->invoice[] = $invoice;
        return $this;
    }

    /**
     * isset invoice
     *
     * @param int|string $index
     * @return bool
     */
    public function issetInvoice($index)
    {
        return isset($this->invoice[$index]);
    }

    /**
     * unset invoice
     *
     * @param int|string $index
     * @return void
     */
    public function unsetInvoice($index)
    {
        unset($this->invoice[$index]);
    }

    /**
     * Gets as invoice
     *
     * @return \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType[]
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Sets a new invoice
     *
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType\InvoiceAType[] $invoice
     * @return self
     */
    public function setInvoice(array $invoice)
    {
        $this->invoice = $invoice;
        return $this;
    }


}

