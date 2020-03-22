<?php

namespace Rebelo\SaftPt;

/**
 * Class representing ShippingPointStructureType
 *
 * 
 * XSD Type: ShippingPointStructure
 */
class ShippingPointStructureType
{

    /**
     * @var string[] $deliveryID
     */
    private $deliveryID = [
        
    ];

    /**
     * @var \DateTime $deliveryDate
     */
    private $deliveryDate = null;

    /**
     * @var string[] $warehouseID
     */
    private $warehouseID = [
        
    ];

    /**
     * @var string[] $locationID
     */
    private $locationID = [
        
    ];

    /**
     * @var \Rebelo\SaftPt\Address $address
     */
    private $address = null;

    /**
     * Adds as deliveryID
     *
     * @return self
     * @param string $deliveryID
     */
    public function addToDeliveryID($deliveryID)
    {
        $this->deliveryID[] = $deliveryID;
        return $this;
    }

    /**
     * isset deliveryID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDeliveryID($index)
    {
        return isset($this->deliveryID[$index]);
    }

    /**
     * unset deliveryID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDeliveryID($index)
    {
        unset($this->deliveryID[$index]);
    }

    /**
     * Gets as deliveryID
     *
     * @return string[]
     */
    public function getDeliveryID()
    {
        return $this->deliveryID;
    }

    /**
     * Sets a new deliveryID
     *
     * @param string $deliveryID
     * @return self
     */
    public function setDeliveryID(array $deliveryID)
    {
        $this->deliveryID = $deliveryID;
        return $this;
    }

    /**
     * Gets as deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Sets a new deliveryDate
     *
     * @param \DateTime $deliveryDate
     * @return self
     */
    public function setDeliveryDate(\DateTime $deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * Adds as warehouseID
     *
     * @return self
     * @param string $warehouseID
     */
    public function addToWarehouseID($warehouseID)
    {
        $this->warehouseID[] = $warehouseID;
        return $this;
    }

    /**
     * isset warehouseID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWarehouseID($index)
    {
        return isset($this->warehouseID[$index]);
    }

    /**
     * unset warehouseID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWarehouseID($index)
    {
        unset($this->warehouseID[$index]);
    }

    /**
     * Gets as warehouseID
     *
     * @return string[]
     */
    public function getWarehouseID()
    {
        return $this->warehouseID;
    }

    /**
     * Sets a new warehouseID
     *
     * @param string $warehouseID
     * @return self
     */
    public function setWarehouseID(array $warehouseID)
    {
        $this->warehouseID = $warehouseID;
        return $this;
    }

    /**
     * Adds as locationID
     *
     * @return self
     * @param string $locationID
     */
    public function addToLocationID($locationID)
    {
        $this->locationID[] = $locationID;
        return $this;
    }

    /**
     * isset locationID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLocationID($index)
    {
        return isset($this->locationID[$index]);
    }

    /**
     * unset locationID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLocationID($index)
    {
        unset($this->locationID[$index]);
    }

    /**
     * Gets as locationID
     *
     * @return string[]
     */
    public function getLocationID()
    {
        return $this->locationID;
    }

    /**
     * Sets a new locationID
     *
     * @param string $locationID
     * @return self
     */
    public function setLocationID(array $locationID)
    {
        $this->locationID = $locationID;
        return $this;
    }

    /**
     * Gets as address
     *
     * @return \Rebelo\SaftPt\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * @param \Rebelo\SaftPt\Address $address
     * @return self
     */
    public function setAddress(\Rebelo\SaftPt\Address $address)
    {
        $this->address = $address;
        return $this;
    }


}

