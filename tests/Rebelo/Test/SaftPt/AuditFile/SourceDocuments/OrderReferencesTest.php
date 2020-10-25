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
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences;
use Rebelo\Date\Date as RDate;

/**
 * Class OrderReferencesTest
 *
 * @author João Rebelo
 */
class OrderReferencesTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(OrderReferences::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceAndSetGet(): void
    {
        $orderRef = new OrderReferences(new ErrorRegister());
        $this->assertInstanceOf(OrderReferences::class, $orderRef);

        $this->assertNull($orderRef->getOriginatingON());
        $this->assertNull($orderRef->getOrderDate());

        $origin = "Origin document";
        $date   = new RDate();

        $this->assertTrue($orderRef->setOriginatingON($origin));
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
        $orderRef = new OrderReferences(new ErrorRegister());
        $orderRef->setOrderDate(new RDate());
        $orderRef->setOriginatingON("Order ref");
        return $orderRef;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $orderRef = new OrderReferences(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $orderRef->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $orderRef = new OrderReferences(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $orderRef->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $orderRef = $this->createOrderReferences();
        $node     = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeNull(): void
    {
        $orderRef = new OrderReferences(new ErrorRegister());
        $node     = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );

        $orderRefNode = $orderRef->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $orderRefNode);

        $this->assertSame(
            OrderReferences::N_ORDERREFERENCES, $orderRefNode->getName()
        );

        $this->assertSame(
            0,
            $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORIGINATINGON}->count()
        );

        $this->assertSame(
            0,
            $node->{OrderReferences::N_ORDERREFERENCES}
            ->{OrderReferences::N_ORDERDATE}->count()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $orderRefTax = $this->createOrderReferences();
        $node        = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml         = $orderRefTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new OrderReferences(new ErrorRegister());
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
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlNull(): void
    {
        $orderRefTax = new OrderReferences(new ErrorRegister());
        $node        = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $xml         = $orderRefTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new OrderReferences(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getOriginatingON());
        $this->assertNull($parsed->getOrderDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $orderNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $order     = new OrderReferences(new ErrorRegister());
        $xml       = $order->createXmlNode($orderNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($order->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($order->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($order->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $orderNode = new \SimpleXMLElement(
            "<".A2Line::N_LINE."></".A2Line::N_LINE.">"
        );
        $order     = new OrderReferences(new ErrorRegister());
        $order->setOriginatingON("");

        $xml = $order->createXmlNode($orderNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($order->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($order->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($order->getErrorRegistor()->getLibXmlError());
    }
}