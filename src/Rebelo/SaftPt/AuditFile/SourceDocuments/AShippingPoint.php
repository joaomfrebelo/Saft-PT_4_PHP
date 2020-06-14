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

/**
 * AShippingPoint base class for ShipFrom and ShipTo
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AShippingPoint extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

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
     * Create the xml node, the ShipFrom an ShipTo node muste be created
     * in the child class
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

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

    }
}