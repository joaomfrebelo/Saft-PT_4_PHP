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
use Rebelo\SaftPt\AuditFile\SourceDocuments\ALine;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\Date\Date as RDate;

/**
 * Class OrderReferencesTest
 *
 * @author João Rebelo
 */
class OrderReferencesTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(OrderReferences::class);
        $this->assertTrue(true);
    }

    public function testInstanceAndSetGet()
    {
        $orderRef = new OrderReferences();
        $this->assertInstanceOf(OrderReferences::class, $orderRef);

        $this->assertNull($orderRef->getOriginatingON());
        $this->assertNull($orderRef->getOrderDate());

        $origin = "Origin document";
        $date   = new RDate();

        $orderRef->setOriginatingON($origin);
        $orderRef->setOrderDate($date);

        $this->assertSame($origin, $orderRef->getOriginatingON());
        $this->assertSame($date, $orderRef->getOrderDate());
    }

    /**
     *
     * @return OrderReferences
     */
    public function createOrderReferences(): OrderReferences
    {
        $orderRef = new OrderReferences();
        $orderRef->setOrderDate(new RDate());
        $orderRef->setOriginatingON("Order ref");
        return $orderRef;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $orderRef = new OrderReferences();
        $node     = new \SimpleXMLElement("<root></root>");
        try
        {
            $orderRef->createXmlNode($node);
            $this->fail("Create a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testParseXmlNodeWrongName()
    {
        $orderRef = new OrderReferences();
        $node     = new \SimpleXMLElement("<root></root>");
        try
        {
            $orderRef->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $orderRef = $this->createOrderReferences();
        $node     = new \SimpleXMLElement(
            "<" . ALine::N_LINE . "></" . ALine::N_LINE . ">"
        );

        $orderRefNode = $orderRef->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $orderRefNode);

        $this->assertSame(
            OrderReferences::N_ORDERREFERENCES, $orderRefNode->getName()
        );

        $this->assertSame(
            $orderRef->getOriginatingON(),
            (string) $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORIGINATINGON}
        );

        $this->assertSame(
            $orderRef->getOrderDate()->format(
                RDate::SQL_DATE
            ),
                (string) $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORDERDATE}
        );
    }

    /**
     *
     */
    public function testCreateXmlNodeNull()
    {
        $orderRef = new OrderReferences();
        $node     = new \SimpleXMLElement(
            "<" . ALine::N_LINE . "></" . ALine::N_LINE . ">"
        );

        $orderRefNode = $orderRef->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $orderRefNode);

        $this->assertSame(
            OrderReferences::N_ORDERREFERENCES, $orderRefNode->getName()
        );

        $this->assertSame(0,
                          $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORIGINATINGON}->count()
        );

        $this->assertSame(0,
                          $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORDERDATE}->count()
        );
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $orderRefTax = $this->createOrderReferences();
        $node        = new \SimpleXMLElement(
            "<" . ALine::N_LINE . "></" . ALine::N_LINE . ">"
        );
        $xml         = $orderRefTax->createXmlNode($node)->asXML();

        $parsed = new OrderReferences();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $orderRefTax->getOriginatingON(), $parsed->getOriginatingON()
        );
        $this->assertSame(
            $orderRefTax->getOrderDate()->format(RDate::SQL_DATE),
                                                 $parsed->getOrderDate()->format(RDate::SQL_DATE)
        );
    }

    /**
     *
     */
    public function testeParseXmlNull()
    {
        $orderRefTax = new OrderReferences();
        $node        = new \SimpleXMLElement(
            "<" . ALine::N_LINE . "></" . ALine::N_LINE . ">"
        );
        $xml         = $orderRefTax->createXmlNode($node)->asXML();

        $parsed = new OrderReferences();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getOriginatingON());
        $this->assertNull($parsed->getOrderDate());
    }

}
