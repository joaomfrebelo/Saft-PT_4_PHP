<?php
/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\Address;
use Rebelo\Date\Date as RDate;

/**
 * AShippingPoint base class for ShipFrom and ShipTo
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AShippingPoint extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_DELIVERYID = "DeliveryID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DELIVERYDATE = "DeliveryDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ADDRESS = "Address";

    /**
     * <xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/><br>
     * <xs:element name="DeliveryID" type="SAFPTtextTypeMandatoryMax255Car"/>
     * @var string[]
     * @since 1.0.0
     */
    private array $deliveryID = array();

    /**
     * <xs:element ref="DeliveryDate" minOccurs="0"/><br>
     * <xs:element name="DeliveryDate" type="SAFdateType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private ?RDate $deliveryDate = null;

    /**
     * Array of Class warehouse
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse[]
     * @since 1.0.0
     */
    private array $warehouse = array();

    /**
     * <xs:element ref="Address" minOccurs="0"/>
     * @var \Rebelo\SaftPt\AuditFile\Address
     * @since 1.0.0
     */
    private ?Address $address = null;

    /**
     * <pre>
     * &lt;xs:complexType name="ShippingPointStructure"&gt;
     *   &lt;xs:sequence&gt;
     *       &lt;xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/&gt;
     *       &lt;xs:element ref="DeliveryDate" minOccurs="0"/&gt;
     *       &lt;xs:sequence minOccurs="0" maxOccurs="unbounded"&gt;
     *           &lt;xs:element ref="WarehouseID" minOccurs="0"/&gt;
     *           &lt;xs:element ref="LocationID" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *       &lt;xs:element ref="Address" minOccurs="0"/&gt;
     *   &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get DeliveryID stack<br>
     * <xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/>
     * @return string[]
     * @since 1.0.0
     */
    public function getDeliveryID(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted with '%s' elements in stack",
                    \count($this->deliveryID)
        ));
        return $this->deliveryID;
    }

    /**
     * Add DeliveryID to the stack<br>
     * <xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/><br>
     * <xs:element name="DeliveryID" type="SAFPTtextTypeMandatoryMax255Car"/>
     * @param string $deliveryID
     * @return int
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addToDeliveryID(string $deliveryID): int
    {
        if (\count($this->deliveryID) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->deliveryID);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->deliveryID[$index] = $this->valTextMandMaxCar(
            $deliveryID, 255, __METHOD__
        );
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " DeliveryID add to index ".\strval($index));
        return $index;
    }

    /**
     * isset deliveryID
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetDeliveryID(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->deliveryID[$index]);
    }

    /**
     * unset deliveryID
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetDeliveryID(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->deliveryID[$index]);
    }

    /**
     * Get deliveryDate<br>
     * <xs:element ref="DeliveryDate" minOccurs="0"/><br>
     * <xs:element name="DeliveryDate" type="SAFdateType"/>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getDeliveryDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->deliveryDate === null ?
                        "null" :
                        $this->deliveryDate->format(RDate::SQL_DATE)
        ));
        return $this->deliveryDate;
    }

    /**
     * Get deliveryDate<br>
     * <xs:element ref="DeliveryDate" minOccurs="0"/><br>
     * <xs:element name="DeliveryDate" type="SAFdateType"/>
     * @param \Rebelo\Date\Date|null $deliveryDate
     * @return void
     * @since 1.0.0
     */
    public function setDeliveryDate(?RDate $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->deliveryDate === null ? "null" :
                        $this->deliveryDate->format(RDate::SQL_DATE)));
    }

    /**
     * Add Warehouse to stack
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse[]
     * @since 1.0.0
     */
    public function getWarehouse(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted with '%s' elements in stack",
                    \count($this->warehouse)
        ));
        return $this->warehouse;
    }

    /**
     * Add Warehouse to stack
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse $warehouse
     * @return int
     * @since 1.0.0
     */
    public function addToWarehouse(Warehouse $warehouse): int
    {
        if (\count($this->warehouse) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->warehouse);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->warehouse[$index] = $warehouse;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " Warehouse add to index ".\strval($index));
        return $index;
    }

    /**
     * isset warehouse
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetWarehouse(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->warehouse[$index]);
    }

    /**
     * unset warehouse
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetWarehouse(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->warehouse[$index]);
    }

    /**
     * Add Address<br>
     * <xs:element ref="Address" minOccurs="0"/>
     * @return \Rebelo\SaftPt\AuditFile\Address|null
     * @since 1.0.0
     */
    public function getAddress(): ?Address
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->address;
    }

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\Address|null $address
     * @return void
     * @since 1.0.0
     */
    public function setAddress(?Address $address): void
    {
        $this->address = $address;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted to ");
    }

    /**
     * Create the xml node, the ShipFrom an ShipTo node muste be created
     * in the child class
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== ShipFrom::N_SHIPFROM &&
            $node->getName() !== ShipTo::N_SHIPTO) {
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s'",
                ShipFrom::N_SHIPFROM, ShipTo::N_SHIPTO, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if (\count($this->getDeliveryID()) > 0) {
            foreach ($this->getDeliveryID() as $deliveryID) {
                $node->addChild(static::N_DELIVERYID, $deliveryID);
            }
        }

        if ($this->getDeliveryDate() !== null) {
            $node->addChild(
                static::N_DELIVERYDATE,
                $this->getDeliveryDate()->format(RDate::SQL_DATE)
            );
        }

        if (\count($this->getWarehouse()) > 0) {
            foreach ($this->getWarehouse() as $warehouse) {
                /* @var $warehouse \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse */
                $warehouse->createXmlNode($node);
            }
        }

        if ($this->getAddress() !== null) {
            $addNode = $node->addChild(static::N_ADDRESS);
            $this->getAddress()->createXmlNode($addNode);
        }

        return $node;
    }

    /**
     * Parse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        if ($node->getName() !== ShipFrom::N_SHIPFROM &&
            $node->getName() !== ShipTo::N_SHIPTO) {
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s",
                ShipFrom::N_SHIPFROM, ShipTo::N_SHIPTO, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $dom         = new \DOMDocument();
        $dom->loadXML($node->asXML());
        $shipNode    = $dom->childNodes[0];
        $lastWhNode  = null;
        $lastWhIndex = null;
        foreach ($shipNode->childNodes as $domNode) {
            try {
                /* @var $domNode \DOMNode */
                switch ($domNode->nodeName) {
                    case static::N_DELIVERYID:
                        $this->addToDeliveryID($domNode->nodeValue);
                        break;
                    case static::N_DELIVERYDATE:
                        $this->setDeliveryDate(
                            RDate::parse(RDate::SQL_DATE, $domNode->nodeValue)
                        );
                        break;
                    case static::N_ADDRESS:
                        $address     = new Address();
                        $address->parseXmlNode($node->{static::N_ADDRESS});
                        $this->setAddress($address);
                        break;
                    case Warehouse::N_WAREHOUSEID:
                        $warehouse   = new Warehouse();
                        $warehouse->setWarehouseID($domNode->nodeValue);
                        $lastWhNode  = Warehouse::N_WAREHOUSEID;
                        $lastWhIndex = $this->addToWarehouse($warehouse);
                        break;
                    case Warehouse::N_LOCATIONID:
                        if ($lastWhNode === Warehouse::N_LOCATIONID ||
                            $lastWhNode === null) {
                            $warehouse   = new Warehouse();
                            $warehouse->setLocationID($domNode->nodeValue);
                            $lastWhIndex = $this->addToWarehouse($warehouse);
                        } else {
                            $this->getWarehouse()[$lastWhIndex]->setLocationID(
                                $domNode->nodeValue
                            );
                        }
                        $lastWhNode = Warehouse::N_LOCATIONID;
                        break;
                    default :
                        $msg        = \sprintf(
                            "Unknow node name '%'", $domNode->nodeName
                        );
                        \Logger::getLogger(\get_class($this))
                            ->error(\sprintf(__METHOD__." '%s'", $msg));
                        throw new AuditFileException($msg);
                }
            } catch (\Exception $e) {
                $msg = $e->getMessage();
            }
        }
    }
}