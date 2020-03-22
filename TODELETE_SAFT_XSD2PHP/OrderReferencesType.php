<?php

namespace Rebelo\SaftPt;

/**
 * Class representing OrderReferencesType
 *
 * 
 * XSD Type: OrderReferences
 */
class OrderReferencesType
{

    /**
     * @var string $originatingON
     */
    private $originatingON = null;

    /**
     * @var \DateTime $orderDate
     */
    private $orderDate = null;

    /**
     * Gets as originatingON
     *
     * @return string
     */
    public function getOriginatingON()
    {
        return $this->originatingON;
    }

    /**
     * Sets a new originatingON
     *
     * @param string $originatingON
     * @return self
     */
    public function setOriginatingON($originatingON)
    {
        $this->originatingON = $originatingON;
        return $this;
    }

    /**
     * Gets as orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Sets a new orderDate
     *
     * @param \DateTime $orderDate
     * @return self
     */
    public function setOrderDate(\DateTime $orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }


}

