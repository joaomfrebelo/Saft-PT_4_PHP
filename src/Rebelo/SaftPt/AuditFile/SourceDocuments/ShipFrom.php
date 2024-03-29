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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;

/**
 * ShipFrom<br>
 * Information about the place and date of the shipping of the goods sold to the customer.
 * @author João Rebelo
 * @since 1.0.0
 */
class ShipFrom extends AShippingPoint
{
    /**
     *
     * @since 1.0.0
     */
    const N_SHIPFROM = "ShipFrom";

    /**
     * ShipFrom<br>
     * Information about the place and date of the shipping of the goods sold to the customer.
     * <pre>
     * &lt;:element name="ShipFrom" type="ShippingPointStructure"/&gt;
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
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== Invoice::N_INVOICE &&
            $node->getName() !== StockMovement::N_STOCKMOVEMENT) {
            $msg = \sprintf(
                "Node name should be '%s' or but is '%s",
                StockMovement::N_STOCKMOVEMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $shipNode = $node->addChild(static::N_SHIPFROM);
        return parent::createXmlNode($shipNode);
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        if ($node->getName() !== static::N_SHIPFROM) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", static::N_SHIPFROM,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        parent::parseXmlNode($node);
    }
}
