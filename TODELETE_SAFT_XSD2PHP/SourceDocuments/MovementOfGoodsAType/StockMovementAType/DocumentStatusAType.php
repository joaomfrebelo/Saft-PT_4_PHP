<?php

namespace Rebelo\SaftPt\SourceDocuments\MovementOfGoodsAType\StockMovementAType;

/**
 * Class representing DocumentStatusAType
 */
class DocumentStatusAType
{

    /**
     * @var string $movementStatus
     */
    private $movementStatus = null;

    /**
     * @var \DateTime $movementStatusDate
     */
    private $movementStatusDate = null;

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
     * Gets as movementStatus
     *
     * @return string
     */
    public function getMovementStatus()
    {
        return $this->movementStatus;
    }

    /**
     * Sets a new movementStatus
     *
     * @param string $movementStatus
     * @return self
     */
    public function setMovementStatus($movementStatus)
    {
        $this->movementStatus = $movementStatus;
        return $this;
    }

    /**
     * Gets as movementStatusDate
     *
     * @return \DateTime
     */
    public function getMovementStatusDate()
    {
        return $this->movementStatusDate;
    }

    /**
     * Sets a new movementStatusDate
     *
     * @param \DateTime $movementStatusDate
     * @return self
     */
    public function setMovementStatusDate(\DateTime $movementStatusDate)
    {
        $this->movementStatusDate = $movementStatusDate;
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

