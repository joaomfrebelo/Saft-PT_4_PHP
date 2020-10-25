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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Warehouse sequence of ShipTo and ShipFrom class
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Warehouse extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_WAREHOUSEID = "WarehouseID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_LOCATIONID = "LocationID";

    /**
     *
     * &lt;xs:element ref="WarehouseID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="WarehouseID" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $warehouseID = null;

    /**
     * &lt;xs:element ref="LocationID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="LocationID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $locationID = null;

    /**
     * Warehouse sequence of ShipTo and ShipFrom class
     * <pre>
     * &lt;xs:sequence minOccurs="0" maxOccurs="unbounded"&gt;
     *    &lt;xs:element ref="WarehouseID" minOccurs="0"/&gt;
     *    &lt;xs:element ref="LocationID" minOccurs="0"/&gt;
     * &lt;/xs:sequence&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get WarehouseID<br>
     * WarehouseID [Identification of the destination warehouse]<br>
     * &lt;xs:element ref="WarehouseID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="WarehouseID" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getWarehouseID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->warehouseID === null ? "null" : $this->warehouseID
                )
            );
        return $this->warehouseID;
    }

    /**
     * Set WarehouseID<br>
     * WarehouseID [Identification of the destination warehouse]<br>
     * &lt;xs:element ref="WarehouseID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="WarehouseID" type="SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @param string|null $warehouseID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setWarehouseID(?string $warehouseID): bool
    {
        try {
            $this->warehouseID = $warehouseID === null ? null :
                $this->valTextMandMaxCar($warehouseID, 50, __METHOD__);
            $return            = true;
        } catch (AuditFileException $e) {
            $this->warehouseID = $warehouseID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("WarehouseID_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->warehouseID === null ? "null" : $this->warehouseID
                )
            );
        return $return;
    }

    /**
     * Get LocationID<br>
     * LocationID [Location of goods in the warehouse]<br>
     * &lt;xs:element ref="LocationID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="LocationID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getLocationID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->locationID === null ? "null" : $this->locationID
                )
            );
        return $this->locationID;
    }

    /**
     * Set LocationID<br>
     * LocationID [Location of goods in the warehouse]<br>
     * &lt;xs:element ref="LocationID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="LocationID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @param string|null $locationID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setLocationID(?string $locationID): bool
    {
        try {
            $this->locationID = $locationID === null ?
                null : $this->valTextMandMaxCar($locationID, 30, __METHOD__);
            $return           = true;
        } catch (AuditFileException $e) {
            $this->locationID = $locationID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("WarehouseID_not_valid");
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->locationID === null ? "null" : $this->locationID
                )
            );
        return $return;
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== ShipFrom::N_SHIPFROM && $node->getName() !== ShipTo::N_SHIPTO) {
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s",
                ShipFrom::N_SHIPFROM, ShipTo::N_SHIPTO, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($this->getWarehouseID() !== null) {
            $node->addChild(static::N_WAREHOUSEID, $this->getWarehouseID());
        }

        if ($this->getLocationID() !== null) {
            $node->addChild(static::N_LOCATIONID, $this->getLocationID());
        }

        return $node;
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($node->getName() !== ShipFrom::N_SHIPFROM && $node->getName() !== ShipTo::N_SHIPTO) {
            $msg = \sprintf(
                "Node name should be '%s' or '%s' but is '%s",
                ShipFrom::N_SHIPFROM, ShipTo::N_SHIPTO, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_WAREHOUSEID}->count() > 0) {
            $this->setWarehouseID((string) $node->{static::N_WAREHOUSEID});
        }

        if ($node->{static::N_LOCATIONID}->count() > 0) {
            $this->setLocationID((string) $node->{static::N_LOCATIONID});
        }
    }
}