<?php

namespace Rebelo\SaftPt\SourceDocuments\WorkingDocumentsAType\WorkDocumentAType;

/**
 * Class representing DocumentStatusAType
 */
class DocumentStatusAType
{

    /**
     * @var string $workStatus
     */
    private $workStatus = null;

    /**
     * @var \DateTime $workStatusDate
     */
    private $workStatusDate = null;

    /**
     * @var string $reason
     */
    private $reason = null;

    /**
     * @var string $sourceID
     */
    private $sourceID = null;

    /**
     * @var string $sourceBilling
     */
    private $sourceBilling = null;

    /**
     * Gets as workStatus
     *
     * @return string
     */
    public function getWorkStatus()
    {
        return $this->workStatus;
    }

    /**
     * Sets a new workStatus
     *
     * @param string $workStatus
     * @return self
     */
    public function setWorkStatus($workStatus)
    {
        $this->workStatus = $workStatus;
        return $this;
    }

    /**
     * Gets as workStatusDate
     *
     * @return \DateTime
     */
    public function getWorkStatusDate()
    {
        return $this->workStatusDate;
    }

    /**
     * Sets a new workStatusDate
     *
     * @param \DateTime $workStatusDate
     * @return self
     */
    public function setWorkStatusDate(\DateTime $workStatusDate)
    {
        $this->workStatusDate = $workStatusDate;
        return $this;
    }

    /**
     * Gets as reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * @param string $reason
     * @return self
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Gets as sourceID
     *
     * @return string
     */
    public function getSourceID()
    {
        return $this->sourceID;
    }

    /**
     * Sets a new sourceID
     *
     * @param string $sourceID
     * @return self
     */
    public function setSourceID($sourceID)
    {
        $this->sourceID = $sourceID;
        return $this;
    }

    /**
     * Gets as sourceBilling
     *
     * @return string
     */
    public function getSourceBilling()
    {
        return $this->sourceBilling;
    }

    /**
     * Sets a new sourceBilling
     *
     * @param string $sourceBilling
     * @return self
     */
    public function setSourceBilling($sourceBilling)
    {
        $this->sourceBilling = $sourceBilling;
        return $this;
    }


}

