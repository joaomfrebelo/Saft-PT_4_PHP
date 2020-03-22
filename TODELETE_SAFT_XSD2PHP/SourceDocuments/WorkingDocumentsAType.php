<?php

namespace Rebelo\SaftPt\SourceDocuments;

/**
 * Class representing WorkingDocumentsAType
 */
class WorkingDocumentsAType
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
     * @var \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType[] $workDocument
     */
    private $workDocument = [
        
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
     * Adds as workDocument
     *
     * @return self
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType $workDocument
     */
    public function addToWorkDocument(\Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType $workDocument)
    {
        $this->workDocument[] = $workDocument;
        return $this;
    }

    /**
     * isset workDocument
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWorkDocument($index)
    {
        return isset($this->workDocument[$index]);
    }

    /**
     * unset workDocument
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWorkDocument($index)
    {
        unset($this->workDocument[$index]);
    }

    /**
     * Gets as workDocument
     *
     * @return \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType[]
     */
    public function getWorkDocument()
    {
        return $this->workDocument;
    }

    /**
     * Sets a new workDocument
     *
     * @param \Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType[] $workDocument
     * @return self
     */
    public function setWorkDocument(array $workDocument)
    {
        $this->workDocument = $workDocument;
        return $this;
    }


}

