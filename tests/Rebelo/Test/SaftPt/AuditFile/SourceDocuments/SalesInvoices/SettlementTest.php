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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;

/**
 * Class SettlementTest
 *
 * @author João Rebelo
 */
class SettlementTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Settlement::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $this->assertNull($settl->getSettlementDiscount());
        $this->assertNull($settl->getSettlementAmount());
        $this->assertNull($settl->getSettlementDate());
        $this->assertNull($settl->getPaymentTerms());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSettlementDiscount(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $disc  = "Discount";
        $this->assertTrue($settl->setSettlementDiscount($disc));
        $this->assertSame($disc, $settl->getSettlementDiscount());
        $this->assertTrue($settl->setSettlementDiscount(null));
        $this->assertNull($settl->getSettlementDiscount());
        $this->assertTrue($settl->setSettlementDiscount(str_pad("A", 39, "B")));
        $this->assertSame(30, \strlen($settl->getSettlementDiscount()));

        $this->assertFalse($settl->setSettlementDiscount(""));
        $this->assertSame("", $settl->getSettlementDiscount());
        $this->assertNotEmpty($settl->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSettlementAmount(): void
    {
        $settl  = new Settlement(new ErrorRegister());
        $amount = 4.99;
        $this->assertTrue($settl->setSettlementAmount($amount));
        $this->assertSame($amount, $settl->getSettlementAmount());
        $this->assertTrue($settl->setSettlementAmount(null));
        $this->assertNull($settl->getSettlementAmount());

        $wrong = -1.0;
        $this->assertFalse($settl->setSettlementAmount($wrong));
        $this->assertSame($wrong, $settl->getSettlementAmount());
        $this->assertNotEmpty($settl->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSettlementDate(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $date  = new RDate();
        $settl->setSettlementDate($date);
        $this->assertSame($date, $settl->getSettlementDate());
        $settl->setSettlementDate(null);
        $this->assertNull($settl->getSettlementDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPaymentTerms(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $terms = "Discount";
        $this->assertTrue($settl->setPaymentTerms($terms));
        $this->assertSame($terms, $settl->getPaymentTerms());
        $this->assertTrue($settl->setPaymentTerms(null));
        $this->assertNull($settl->getPaymentTerms());
        $this->assertTrue($settl->setPaymentTerms(str_pad("A", 110, "B")));
        $this->assertSame(100, \strlen($settl->getPaymentTerms()));

        $this->assertFalse($settl->setSettlementDiscount(""));
        $this->assertSame("", $settl->getSettlementDiscount());
        $this->assertNotEmpty($settl->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement
     */
    public function createSettlement(): Settlement
    {
        $settl = new Settlement(new ErrorRegister());
        $settl->setSettlementDiscount("Some type of Discount");
        $settl->setSettlementAmount(409.79);
        $settl->setSettlementDate(new RDate());
        $settl->setPaymentTerms("The termes");
        return $settl;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $node  = new \SimpleXMLElement("<root></root>");
        try {
            $settl->createXmlNode($node);
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
        $settl = new Settlement(new ErrorRegister());
        $node  = new \SimpleXMLElement("<root></root>");
        try {
            $settl->parseXmlNode($node);
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
        $settl = $this->createSettlement();
        $node  = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );

        $settlNode = $settl->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $settlNode);

        $this->assertSame(
            Settlement::N_SETTLEMENT, $settlNode->getName()
        );

        $this->assertSame(
            $settl->getSettlementDiscount(),
            (string) $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDISCOUNT}
        );

        $this->assertSame(
            $settl->getSettlementAmount(),
            (float) $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTAMOUNT}
        );

        $this->assertSame(
            $settl->getSettlementDate()->format(RDate::SQL_DATE),
            (string) $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDATE}
        );

        $this->assertSame(
            $settl->getPaymentTerms(),
            (string) $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_PAYMENTTERMS}
        );

        $this->assertEmpty($settl->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settl->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settl->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeNull(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $node  = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );

        $settlNode = $settl->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $settlNode);

        $this->assertSame(
            Settlement::N_SETTLEMENT, $settlNode->getName()
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDISCOUNT}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTAMOUNT}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDATE}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_PAYMENTTERMS}->count() === 0
        );

        $this->assertEmpty($settl->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settl->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settl->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $settl = $this->createSettlement();
        $node  = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $xml   = $settl->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new Settlement(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $settl->getSettlementDiscount(), $parsed->getSettlementDiscount()
        );

        $this->assertSame(
            $settl->getSettlementAmount(), $parsed->getSettlementAmount()
        );

        $this->assertSame(
            $settl->getSettlementDate()->format(RDate::SQL_DATE),
            $parsed->getSettlementDate()->format(RDate::SQL_DATE)
        );

        $this->assertSame($settl->getPaymentTerms(), $parsed->getPaymentTerms());

        $this->assertEmpty($settl->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settl->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settl->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlNull(): void
    {
        $settl = new Settlement(new ErrorRegister());
        $node  = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $xml   = $settl->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $parsed = new Settlement(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getSettlementDiscount());
        $this->assertNull($parsed->getSettlementAmount());
        $this->assertNull($parsed->getSettlementDate());
        $this->assertNull($parsed->getPaymentTerms());

        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $settlementNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $settlement     = new Settlement(new ErrorRegister());
        $xml            = $settlement->createXmlNode($settlementNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $settlementNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENTTOTALS."></".ADocumentTotals::N_DOCUMENTTOTALS.">"
        );
        $settlement     = new Settlement(new ErrorRegister());
        $settlement->setPaymentTerms("");
        $settlement->setSettlementAmount(-1.0);
        $settlement->setSettlementDiscount("");

        $xml = $settlement->createXmlNode($settlementNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }
}