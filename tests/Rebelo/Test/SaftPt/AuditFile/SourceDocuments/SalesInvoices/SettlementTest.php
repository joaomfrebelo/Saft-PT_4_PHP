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
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;

/**
 * Class SettlementTest
 *
 * @author João Rebelo
 */
class SettlementTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Settlement::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $settl = new Settlement();
        $this->assertNull($settl->getSettlementDiscount());
        $this->assertNull($settl->getSettlementAmount());
        $this->assertNull($settl->getSettlementDate());
        $this->assertNull($settl->getPaymentTerms());
    }

    public function testSetGetSettlementDiscount()
    {
        $settl = new Settlement();
        $disc  = "Discount";
        $settl->setSettlementDiscount($disc);
        $this->assertSame($disc, $settl->getSettlementDiscount());
        $settl->setSettlementDiscount(null);
        $this->assertNull($settl->getSettlementDiscount());
        $settl->setSettlementDiscount(str_pad("A", 39, "B"));
        $this->assertSame(30, \strlen($settl->getSettlementDiscount()));
        try
        {
            $settl->setSettlementDiscount("");
            $this->fail("Set SettlementDiscount to an empty string "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    public function testSetGetSettlementAmount()
    {
        $settl  = new Settlement();
        $amount = 4.99;
        $settl->setSettlementAmount($amount);
        $this->assertSame($amount, $settl->getSettlementAmount());
        $settl->setSettlementAmount(null);
        $this->assertNull($settl->getSettlementAmount());
        try
        {
            $settl->setSettlementAmount(-1.0);
            $this->fail("Set SettlementAmount to a negative number "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    public function testSetGetSettlementDate()
    {
        $settl = new Settlement();
        $date  = new RDate();
        $settl->setSettlementDate($date);
        $this->assertSame($date, $settl->getSettlementDate());
        $settl->setSettlementDate(null);
        $this->assertNull($settl->getSettlementDate());
    }

    public function testSetGetPaymentTerms()
    {
        $settl = new Settlement();
        $terms = "Discount";
        $settl->setPaymentTerms($terms);
        $this->assertSame($terms, $settl->getPaymentTerms());
        $settl->setPaymentTerms(null);
        $this->assertNull($settl->getPaymentTerms());
        $settl->setPaymentTerms(str_pad("A", 110, "B"));
        $this->assertSame(100, \strlen($settl->getPaymentTerms()));
        try
        {
            $settl->setPaymentTerms("");
            $this->fail("Set PaymentTerms to an empty string "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement
     */
    public function createSettlement(): Settlement
    {
        $settl = new Settlement();
        $settl->setSettlementDiscount("Some type of Discount");
        $settl->setSettlementAmount(409.79);
        $settl->setSettlementDate(new RDate());
        $settl->setPaymentTerms("The termes");
        return $settl;
    }

    public function testCreateXmlNodeWrongName()
    {
        $settl = new Settlement();
        $node  = new \SimpleXMLElement("<root></root>");
        try
        {
            $settl->createXmlNode($node);
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

    public function testParseXmlNodeWrongName()
    {
        $settl = new Settlement();
        $node  = new \SimpleXMLElement("<root></root>");
        try
        {
            $settl->parseXmlNode($node);
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

    public function testCreateXmlNode()
    {
        $settl = $this->createSettlement();
        $node  = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
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
    }

    public function testCreateXmlNodeNull()
    {
        $settl = new Settlement();
        $node  = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
        );

        $settlNode = $settl->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $settlNode);

        $this->assertSame(
            Settlement::N_SETTLEMENT, $settlNode->getName()
        );

        $this->assertTrue($node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDISCOUNT}->count() === 0
        );

        $this->assertTrue($node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTAMOUNT}->count() === 0
        );

        $this->assertTrue($node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_SETTLEMENTDATE}->count() === 0
        );

        $this->assertTrue($node->{Settlement::N_SETTLEMENT}
            ->{Settlement::N_PAYMENTTERMS}->count() === 0
        );
    }

    public function testeParseXml()
    {
        $settl = $this->createSettlement();
        $node  = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
        );
        $xml   = $settl->createXmlNode($node)->asXML();

        $parsed = new Settlement();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($settl->getSettlementDiscount(),
                          $parsed->getSettlementDiscount());
        $this->assertSame($settl->getSettlementAmount(),
                          $parsed->getSettlementAmount());
        $this->assertSame(
            $settl->getSettlementDate()->format(RDate::SQL_DATE),
                                                $parsed->getSettlementDate()->format(RDate::SQL_DATE)
        );
        $this->assertSame($settl->getPaymentTerms(), $parsed->getPaymentTerms());
    }

    public function testeParseXmlNull()
    {
        $settl = new Settlement();
        $node  = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
        );
        $xml   = $settl->createXmlNode($node)->asXML();

        $parsed = new Settlement();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getSettlementDiscount());
        $this->assertNull($parsed->getSettlementAmount());
        $this->assertNull($parsed->getSettlementDate());
        $this->assertNull($parsed->getPaymentTerms());
    }

}
