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

use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
     * &lt;xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/&gt;<br>
     * &lt;xs:element name="DeliveryID" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * @var string[]
     * @since 1.0.0
     */
    private array $deliveryID = array();

    /**
     * &lt;xs:element ref="DeliveryDate" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="DeliveryDate" type="SAFdateType"/&gt;
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
     * &lt;xs:element ref="Address" minOccurs="0"/&gt;
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get DeliveryID stack<br>
     * The license plate number of the carrier vehicle or the means
     * of shipping used shall be indicated, e.g. express mail, etc.
     * &lt;xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return string[]
     * @since 1.0.0
     */
    public function getDeliveryID(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted with '%s' elements in stack",
                    \count($this->deliveryID)
                )
            );
        return $this->deliveryID;
    }

    /**
     * Add DeliveryID to the stack<br>
     * The license plate number of the carrier vehicle or the means
     * of shipping used shall be indicated, e.g. express mail, etc.
     * &lt;xs:element ref="DeliveryID" minOccurs="0" maxOccurs="unbounded"/&gt;<br>
     * &lt;xs:element name="DeliveryID" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * @param string $deliveryID
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addDeliveryID(string $deliveryID): bool
    {
        try {
            $val    = $this->valTextMandMaxCar(
                $deliveryID, 255, __METHOD__
            );
            $return = true;
        } catch (AuditFileException $e) {
            $val    = $deliveryID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("DeliveryID_not_valid");
            $return = false;
        }
        $this->deliveryID[] = $val;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." DeliveryID add to index "
        );
        return $return;
    }

    /**
     * Get deliveryDate<br>
     * For the insurance companies sector, this field shall
     * be filled in with the date of beginning of the risk coverage period.
     * &lt;xs:element ref="DeliveryDate" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="DeliveryDate" type="SAFdateType"/&gt;
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getDeliveryDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->deliveryDate === null ?
                        "null" :
                        $this->deliveryDate->format(RDate::SQL_DATE)
                )
            );
        return $this->deliveryDate;
    }

    /**
     * Get deliveryDate<br><br>
     * For the insurance companies sector, this field shall
     * be filled in with the date of beginning of the risk coverage period.
     * &lt;xs:element ref="DeliveryDate" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="DeliveryDate" type="SAFdateType"/&gt;
     * @param \Rebelo\Date\Date|null $deliveryDate
     * @return void
     * @since 1.0.0
     */
    public function setDeliveryDate(?RDate $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->deliveryDate === null ? "null" :
                    $this->deliveryDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Add Warehouse to stack
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse[]
     * @since 1.0.0
     */
    public function getWarehouse(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted with '%s' elements in stack",
                    \count($this->warehouse)
                )
            );
        return $this->warehouse;
    }

    /**
     * Create a new instance of Warehouse and add to stack than will be
     * returned to be populated<br>
     * Every time that you invoke this method a new instance will be created
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse
     * @since 1.0.0
     */
    public function addWarehouse(): Warehouse
    {
        $warehouse         = new Warehouse($this->getErrorRegistor());
        $this->warehouse[] = $warehouse;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." Warehouse add to index "
        );
        return $warehouse;
    }

    /**
     * Get Address<br>
     * If the Address instance is not created and $create is true a new instance will be created
     * &lt;xs:element ref="Address" minOccurs="0"/&gt;
     * @param bool $create If true a new instance will be created if wasn't before
     * @return \Rebelo\SaftPt\AuditFile\Address|null
     * @since 1.0.0
     */
    public function getAddress(bool $create = true): ?Address
    {
        if (isset($this->address) === false && $create) {
            $this->address = new Address($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->address;
    }

    /**
     * Set Address to null
     * @return void
     * @since 1.0.0
     */
    public function setAddressToNull(): void
    {
        $this->address = null;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted to null");
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

        if ($this->getAddress(false) !== null) {
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

        $nodeXml = $node->asXML();
        if ($nodeXml === false) {
            $msg = \sprintf("Error generating xml of node");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $dom = new \DOMDocument();
        $dom->loadXML($nodeXml);
        $shipNode    = $dom->childNodes[0];
        $lastWhNode  = null;
        $lastWhIndex = null;
        $n           = 0;
        $stack       = array();
        foreach ($shipNode->childNodes as $domNode) {
            try {
                /* @var $domNode \DOMNode */
                switch ($domNode->nodeName) {
                    case static::N_DELIVERYID:
                        $this->addDeliveryID($domNode->nodeValue);
                        break;
                    case static::N_DELIVERYDATE:
                        $this->setDeliveryDate(
                            RDate::parse(RDate::SQL_DATE, $domNode->nodeValue)
                        );
                        break;
                    case static::N_ADDRESS:
                        $this->getAddress()->parseXmlNode($node->{static::N_ADDRESS});
                        break;
                    case Warehouse::N_WAREHOUSEID:
                        $warehouse   = $this->addWarehouse();
                        $warehouse->setWarehouseID($domNode->nodeValue);
                        $stack[$n]   = $warehouse;
                        $lastWhNode  = Warehouse::N_WAREHOUSEID;
                        $lastWhIndex = $n;
                        $n++;
                        break;
                    case Warehouse::N_LOCATIONID:
                        if ($lastWhNode === Warehouse::N_LOCATIONID ||
                            $lastWhNode === null) {
                            $warehouse   = $this->addWarehouse();
                            $warehouse->setLocationID($domNode->nodeValue);
                            $stack[$n]   = $warehouse;
                            $lastWhNode  = Warehouse::N_LOCATIONID;
                            $lastWhIndex = $n;
                            $n++;
                        } else {
                            $stack[$lastWhIndex]->setLocationID(
                                $domNode->nodeValue
                            );
                        }
                        $lastWhNode = Warehouse::N_LOCATIONID;
                        break;
                    case "#text":
                        continue 2;
                    default :
                        $msg        = \sprintf(
                            "Unknow node name '%s'", $domNode->nodeName
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