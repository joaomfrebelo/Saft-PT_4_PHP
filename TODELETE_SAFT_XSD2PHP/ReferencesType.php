<?php

namespace Rebelo\SaftPt;

/**
 * Class representing ReferencesType
 *
 * 
 * XSD Type: References
 */
class ReferencesType
{

    /**
     * @var string $reference
     */
    private $reference = null;

    /**
     * @var string $reason
     */
    private $reason = null;

    /**
     * Gets as reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Sets a new reference
     *
     * @param string $reference
     * @return self
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
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


}

