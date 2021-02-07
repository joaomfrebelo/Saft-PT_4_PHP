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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo;

/**
 * Class TaxTest
 *
 * @author João Rebelo
 */
class WarehouseTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Warehouse::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $warehouse = new Warehouse(new ErrorRegister());
        $this->assertInstanceOf(Warehouse::class, $warehouse);
        $this->assertNull($warehouse->getWarehouseID());
        $this->assertNull($warehouse->getLocationID());

        $warehouseid = "C999";
        $this->assertTrue($warehouse->setWarehouseID($warehouseid));
        $this->assertSame($warehouseid, $warehouse->getWarehouseID());
        $this->assertTrue($warehouse->setWarehouseID(null));
        $this->assertNull($warehouse->getWarehouseID());
        $this->assertTrue(
            $warehouse->setWarehouseID(
                \str_pad(
                    $warehouseid, 99,
                    "A"
                )
            )
        );
        $this->assertSame(50, \strlen($warehouse->getWarehouseID()));

        $warehouse->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($warehouse->setWarehouseID(""));
        $this->assertSame("", $warehouse->getWarehouseID());
        $this->assertNotEmpty($warehouse->getErrorRegistor()->getOnSetValue());

        $locationid = "A999";
        $this->assertTrue($warehouse->setLocationID($locationid));
        $this->assertSame($locationid, $warehouse->getLocationID());
        $this->assertTrue($warehouse->setLocationID(null));
        $this->assertNull($warehouse->getLocationID());
        $this->assertTrue(
            $warehouse->setLocationID(\str_pad($locationid, 99, "A"))
        );
        $this->assertSame(30, \strlen($warehouse->getLocationID()));

        $warehouse->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($warehouse->setLocationID(""));
        $this->assertSame("", $warehouse->getLocationID());
        $this->assertNotEmpty($warehouse->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $warehouse = new Warehouse(new ErrorRegister());
        $node      = new \SimpleXMLElement(
            "<".ShipFrom::N_SHIPFROM."></".ShipFrom::N_SHIPFROM.">"
        );

        $warehouseid = "A999";
        $locationid  = "C999";

        $warehouse->setWarehouseID($warehouseid);
        $warehouse->setLocationID($locationid);

        $warehouse->createXmlNode($node);

        $this->assertSame(
            $warehouse->getWarehouseID(),
            (string) $node->{Warehouse::N_WAREHOUSEID}
        );

        $this->assertSame(
            $warehouse->getLocationID(),
            (string) $node->{Warehouse::N_LOCATIONID}
        );

        $nullNode = new \SimpleXMLElement(
            "<".ShipTo::N_SHIPTO."></".ShipTo::N_SHIPTO.">"
        );

        $warehouse->setLocationID(null);
        $warehouse->setWarehouseID(null);
        $warehouse->createXmlNode($nullNode);
        $this->assertSame(0, $nullNode->{Warehouse::N_WAREHOUSEID}->count());
        $this->assertSame(0, $nullNode->{Warehouse::N_LOCATIONID}->count());

        $this->assertEmpty($warehouse->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($warehouse->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($warehouse->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $warehouse = new Warehouse(new ErrorRegister());
        $node      = new \SimpleXMLElement(
            "<".ShipFrom::N_SHIPFROM."></".ShipFrom::N_SHIPFROM.">"
        );

        $warehouseid = "A999";
        $locationid  = "C999";

        $warehouse->setWarehouseID($warehouseid);
        $warehouse->setLocationID($locationid);

        $xml = $warehouse->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Warehouse(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $warehouse->getWarehouseID(), $parsed->getWarehouseID()
        );
        $this->assertSame(
            $warehouse->getLocationID(), $parsed->getLocationID()
        );

        $nodeNull = new \SimpleXMLElement(
            "<".ShipTo::N_SHIPTO."></".ShipTo::N_SHIPTO.">"
        );
        $warehouse->setWarehouseID(null);
        $warehouse->setLocationID(null);
        $xmlNull  = $warehouse->createXmlNode($nodeNull)->asXML();
        if ($xmlNull === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsedNull = new Warehouse(new ErrorRegister());
        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));

        $this->assertNull($parsedNull->getWarehouseID());
        $this->assertNull($parsedNull->getLocationID());

        $this->assertEmpty($warehouse->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($warehouse->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($warehouse->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $warehouseNode = new \SimpleXMLElement(
            "<".ShipFrom::N_SHIPFROM."></".ShipFrom::N_SHIPFROM.">"
        );
        $warehouse     = new Warehouse(new ErrorRegister());
        $xml           = $warehouse->createXmlNode($warehouseNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($warehouse->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($warehouse->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($warehouse->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $warehouseNode = new \SimpleXMLElement(
            "<".ShipFrom::N_SHIPFROM."></".ShipFrom::N_SHIPFROM.">"
        );
        $warehouse     = new Warehouse(new ErrorRegister());
        $warehouse->setWarehouseID("");
        $warehouse->setLocationID("");

        $xml = $warehouse->createXmlNode($warehouseNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($warehouse->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($warehouse->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($warehouse->getErrorRegistor()->getLibXmlError());
    }
}
