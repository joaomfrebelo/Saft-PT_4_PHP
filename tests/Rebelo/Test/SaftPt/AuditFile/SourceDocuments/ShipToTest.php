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
use Rebelo\SaftPt\AuditFile\AuditFileException;
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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(ShipTo::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $ship = new ShipTo();
        $this->assertInstanceOf(ShipTo::class, $ship);

        $this->assertNull($ship->getAddress());
        $this->assertNull($ship->getDeliveryDate());
        $this->assertSame(0, \count($ship->getDeliveryID()));
        $this->assertSame(0, \count($ship->getWarehouse()));

        $ids = ["A", "B", "C"];
        foreach ($ids as $k => $id) {
            $index = $ship->addToDeliveryID($id);
            $this->assertSame($ids[$k], $ship->getDeliveryID()[$index]);
            $this->assertSame($k, $index);
        }

        $indLen = $ship->addToDeliveryID(\str_pad("A", 300, "A"));
        $this->assertSame(255, \strlen($ship->getDeliveryID()[$indLen]));
        try {
            $ship->addToDeliveryID("");
            $this->fail("Set DeliveryID to an empty string should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $deliveryDate = new RDate();
        $ship->setDeliveryDate($deliveryDate);
        $this->assertSame($deliveryDate, $ship->getDeliveryDate());

        foreach ($ids as $k => $id) {
            $warehouse = new Warehouse();
            $warehouse->setWarehouseID($id);
            $index     = $ship->addToWarehouse($warehouse);
            $this->assertSame($warehouse, $ship->getWarehouse()[$index]);
            $this->assertSame($k, $index);
        }

        $ship->setAddress(new Address());
        $this->assertInstanceOf(Address::class, $ship->getAddress());
        $ship->setAddress(null);
        $this->assertNull($ship->getAddress());
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $ship = new ShipTo();
        $node = new \SimpleXMLElement(
            "<".StockMovement::N_STOCKMOVEMENT."></".StockMovement::N_STOCKMOVEMENT.">"
        );

        $ids          = ["A", "B", "C"];
        $deliveryDate = new RDate();
        $address      = new Address();
        $address->setCity("Lisboa");
        $address->setCountry(new Country(Country::ISO_PT));
        $address->setAddressDetail("Rua das Escolas Gerais");
        $address->setPostalCode("1999-999");

        foreach ($ids as $id) {
            $ship->addToDeliveryID($id);
            $warehouse = new Warehouse();
            $warehouse->setLocationID("L-".$id);
            $warehouse->setWarehouseID("W-".$id);
            $ship->addToWarehouse($warehouse);
        }

        $ship->setDeliveryDate($deliveryDate);
        $ship->setAddress($address);

        $shipNode = $ship->createXmlNode($node);
        $parse    = new ShipTo();
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
            $parse->getAddress()->getAddressDetail());
    }
}