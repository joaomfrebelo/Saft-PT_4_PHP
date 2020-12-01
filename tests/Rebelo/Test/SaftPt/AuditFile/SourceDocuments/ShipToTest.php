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
use Rebelo\SaftPt\AuditFile\Address;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Warehouse;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\Country;

/**
 * Class TaxTest
 *
 * @author João Rebelo
 */
class ShipToTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(ShipTo::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $ship = new ShipTo(new ErrorRegister());
        $this->assertInstanceOf(ShipTo::class, $ship);

        $this->assertNull($ship->getAddress(false));
        $this->assertNull($ship->getDeliveryDate());
        $this->assertSame(0, \count($ship->getDeliveryID()));
        $this->assertSame(0, \count($ship->getWarehouse()));

        $ids = ["A", "B", "C"];
        foreach ($ids as $k => $id) {
            $this->assertTrue($ship->addDeliveryID($id));
            $this->assertSame($ids[$k], $ship->getDeliveryID()[$k]);
        }

        $this->assertTrue($ship->addDeliveryID(\str_pad("A", 300, "A")));
        $this->assertSame(255, \strlen($ship->getDeliveryID()[++$k]));

        $ship->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($ship->addDeliveryID(""));
        $this->assertSame("", $ship->getDeliveryID()[++$k]);
        $this->assertNotEmpty($ship->getErrorRegistor()->getOnSetValue());

        $deliveryDate = new RDate();
        $ship->setDeliveryDate($deliveryDate);
        $this->assertSame($deliveryDate, $ship->getDeliveryDate());

        foreach ($ids as $k => $id) {
            $warehouse = $ship->addWarehouse();
            $warehouse->setWarehouseID($id);
            $this->assertSame($warehouse, $ship->getWarehouse()[$k]);
        }

        $this->assertInstanceOf(Address::class, $ship->getAddress());
        $ship->setAddressToNull();
        $this->assertNull($ship->getAddress(false));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $ship = new ShipTo(new ErrorRegister());
        $node = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );

        $ids          = ["A", "B", "C"];
        $deliveryDate = new RDate();
        $address      = $ship->getAddress();
        $address->setCity("Lisboa");
        $address->setCountry(new Country(Country::ISO_PT));
        $address->setAddressDetail("Rua das Escolas Gerais");
        $address->setPostalCode("1999-999");

        foreach ($ids as $id) {
            $this->assertTrue($ship->addDeliveryID($id));
            $warehouse = $ship->addWarehouse();
            $warehouse->setLocationID("L-".$id);
            $warehouse->setWarehouseID("W-".$id);
        }

        $ship->setDeliveryDate($deliveryDate);

        $shipNode = $ship->createXmlNode($node);
        $parse    = new ShipTo(new ErrorRegister());
        $parse->parseXmlNode($shipNode);

        foreach ($ids as $k => $id) {
            $this->assertSame(
                $ids[$k], (string) $shipNode->{ShipTo::N_DELIVERYID}[$k]
            );
            $this->assertSame(
                "L-".$ids[$k], (string) $shipNode->{Warehouse::N_LOCATIONID}[$k]
            );
            $this->assertSame(
                "W-".$ids[$k],
                (string) $shipNode->{Warehouse::N_WAREHOUSEID}[$k]
            );

            $this->assertSame(
                $ship->getDeliveryID()[$k], $parse->getDeliveryID()[$k]
            );

            $this->assertSame(
                $ship->getWarehouse()[$k]->getWarehouseID(),
                $parse->getWarehouse()[$k]->getWarehouseID()
            );

            $this->assertSame(
                $ship->getWarehouse()[$k]->getLocationID(),
                $parse->getWarehouse()[$k]->getLocationID()
            );
        }

        $this->assertSame(
            $deliveryDate->format(RDate::SQL_DATE),
            (string) $shipNode->{ShipTo::N_DELIVERYDATE}
        );

        $this->assertSame(
            $ship->getDeliveryDate()->format(RDate::SQL_DATE),
            $parse->getDeliveryDate()->format(RDate::SQL_DATE)
        );

        $this->assertSame(
            $address->getAddressDetail(),
            (string) $shipNode->{ShipTo::N_ADDRESS}->{Address::N_ADDRESSDETAIL}
        );

        $this->assertSame(
            $ship->getAddress()->getAddressDetail(),
            $parse->getAddress()->getAddressDetail()
        );

        $this->assertEmpty($ship->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($ship->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ship->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $shipToNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $ship       = new ShipTo(new ErrorRegister());
        $xml        = $ship->createXmlNode($shipToNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ship->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($ship->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ship->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $shipToNode = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );
        $ship       = new ShipTo(new ErrorRegister());
        $ship->addDeliveryID("");

        $xml = $ship->createXmlNode($shipToNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($ship->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($ship->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($ship->getErrorRegistor()->getLibXmlError());
    }
}