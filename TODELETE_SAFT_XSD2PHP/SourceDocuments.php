<?php

namespace Rebelo\SaftPt;

/**
 * Class representing SourceDocuments
 */
class SourceDocuments
{

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType $salesInvoices
     */
    private $salesInvoices = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType $movementOfGoods
     */
    private $movementOfGoods = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType $workingDocuments
     */
    private $workingDocuments = null;

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType $payments
     */
    private $payments = null;

    /**
     * Gets as salesInvoices
     *
     * @return \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType
     */
    public function getSalesInvoices()
    {
        return $this->salesInvoices;
    }

    /**
     * Sets a new salesInvoices
     *
     * @param \Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType $salesInvoices
     * @return self
     */
    public function setSalesInvoices(\Rebelo\SaftPt\SourceDocuments\SalesInvoicesAType $salesInvoices)
    {
        $this->salesInvoices = $salesInvoices;
        return $this;
    }

    /**
     * Gets as movementOfGoods
     *
     * @return \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType
     */
    public function getMovementOfGoods()
    {
        return $this->movementOfGoods;
    }

    /**
     * Sets a new movementOfGoods
     *
     * @param \Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType $movementOfGoods
     * @return self
     */
    public function setMovementOfGoods(\Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType $movementOfGoods)
    {
        $this->movementOfGoods = $movementOfGoods;
        return $this;
    }

    /**
     * Gets as workingDocuments
     *
     * @return \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType
     */
    public function getWorkingDocuments()
    {
        return $this->workingDocuments;
    }

    /**
     * Sets a new workingDocuments
     *
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType $workingDocuments
     * @return self
     */
    public function setWorkingDocuments(\Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType $workingDocuments)
    {
        $this->workingDocuments = $workingDocuments;
        return $this;
    }

    /**
     * Gets as payments
     *
     * @return \Rebelo\SaftPt\SourceDocuments\PaymentsAType
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Sets a new payments
     *
     * @param \Rebelo\SaftPt\SourceDocuments\PaymentsAType $payments
     * @return self
     */
    public function setPayments(\Rebelo\SaftPt\SourceDocuments\PaymentsAType $payments)
    {
        $this->payments = $payments;
        return $this;
    }


}

