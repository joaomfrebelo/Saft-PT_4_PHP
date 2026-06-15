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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\SaftPt\Commune;

/**
 * Class SettlementTest
 *
 * @author João Rebelo
 */
class SettlementTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(Settlement::class))->testReflection(Settlement::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $this->assertNull($settlement->getSettlementDiscount());
        $this->assertNull($settlement->getSettlementAmount());
        $this->assertNull($settlement->getSettlementDate());
        $this->assertNull($settlement->getPaymentTerms());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetSettlementDiscount(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $disc       = "Discount";
        $this->assertTrue($settlement->setSettlementDiscount($disc));
        $this->assertSame($disc, $settlement->getSettlementDiscount());
        $this->assertTrue($settlement->setSettlementDiscount(null));
        $this->assertNull($settlement->getSettlementDiscount());
        $this->assertTrue($settlement->setSettlementDiscount(str_pad("A", 39, "B")));
        $this->assertSame(30, \strlen($settlement->getSettlementDiscount()));

        $this->assertFalse($settlement->setSettlementDiscount(""));
        $this->assertSame("", $settlement->getSettlementDiscount());
        $this->assertNotEmpty($settlement->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetSettlementAmount(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $amount     = new Decimal("4.99");
        $this->assertTrue($settlement->setSettlementAmount($amount));
        $this->assertSame($amount, $settlement->getSettlementAmount());
        $this->assertTrue($settlement->setSettlementAmount(null));
        $this->assertNull($settlement->getSettlementAmount());

        $wrong = new Decimal("-1.0");
        $this->assertFalse($settlement->setSettlementAmount($wrong));
        $this->assertSame($wrong, $settlement->getSettlementAmount());
        $this->assertNotEmpty($settlement->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetSettlementDate(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $date       = new RDate();
        $settlement->setSettlementDate($date);
        $this->assertSame($date, $settlement->getSettlementDate());
        $settlement->setSettlementDate(null);
        $this->assertNull($settlement->getSettlementDate());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetPaymentTerms(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $terms      = "Discount";
        $this->assertTrue($settlement->setPaymentTerms($terms));
        $this->assertSame($terms, $settlement->getPaymentTerms());
        $this->assertTrue($settlement->setPaymentTerms(null));
        $this->assertNull($settlement->getPaymentTerms());
        $this->assertTrue($settlement->setPaymentTerms(str_pad("A", 110, "B")));
        $this->assertSame(100, \strlen($settlement->getPaymentTerms()));

        $this->assertFalse($settlement->setSettlementDiscount(""));
        $this->assertSame("", $settlement->getSettlementDiscount());
        $this->assertNotEmpty($settlement->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return Settlement
     */
    public function createSettlement(): Settlement
    {
        $settlement = new Settlement(new ErrorRegister());
        $settlement->setSettlementDiscount("Some type of Discount");
        $settlement->setSettlementAmount(new Decimal("409.79"));
        $settlement->setSettlementDate(new RDate());
        $settlement->setPaymentTerms("The terms");
        return $settlement;
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $node       = new \SimpleXMLElement("<root></root>");
        try {
            $settlement->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNodeWrongName(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $node       = new \SimpleXMLElement("<root></root>");
        try {
            $settlement->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $settlement = $this->createSettlement();
        $node       = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );

        $settlementNode = $settlement->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $settlementNode);

        $this->assertSame(
            Settlement::N_SETTLEMENT, $settlementNode->getName()
        );

        $this->assertSame(
            $settlement->getSettlementDiscount(),
            (string)$node->{Settlement::N_SETTLEMENT}
                ->{Settlement::N_SETTLEMENT_DISCOUNT}
        );

        $this->assertSame(
            $settlement->getSettlementAmount()?->toFloat(),
            (float)$node->{Settlement::N_SETTLEMENT}->{Settlement::N_SETTLEMENT_AMOUNT}
        );

        $this->assertSame(
            $settlement->getSettlementDate()?->format(Pattern::SQL_DATE),
            (string)$node->{Settlement::N_SETTLEMENT}->{Settlement::N_SETTLEMENT_DATE}
        );

        $this->assertSame(
            $settlement->getPaymentTerms(),
            (string)$node->{Settlement::N_SETTLEMENT}->{Settlement::N_PAYMENT_TERMS}
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeNull(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $node       = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );

        $settlementNode = $settlement->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $settlementNode);

        $this->assertSame(
            Settlement::N_SETTLEMENT, $settlementNode->getName()
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
                ->{Settlement::N_SETTLEMENT_DISCOUNT}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
                ->{Settlement::N_SETTLEMENT_AMOUNT}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
                ->{Settlement::N_SETTLEMENT_DATE}->count() === 0
        );

        $this->assertTrue(
            $node->{Settlement::N_SETTLEMENT}
                ->{Settlement::N_PAYMENT_TERMS}->count() === 0
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $settlement = $this->createSettlement();
        $node       = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $xml        = $settlement->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Settlement(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $settlement->getSettlementDiscount(), $parsed->getSettlementDiscount()
        );

        $this->assertSame(
            $settlement->getSettlementAmount()?->toFloat(),
            $parsed->getSettlementAmount()?->toFloat()
        );

        $this->assertSame(
            $settlement->getSettlementDate()?->format(Pattern::SQL_DATE),
            $parsed->getSettlementDate()?->format(Pattern::SQL_DATE)
        );

        $this->assertSame($settlement->getPaymentTerms(), $parsed->getPaymentTerms());

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNull(): void
    {
        $settlement = new Settlement(new ErrorRegister());
        $node       = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $xml        = $settlement->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $settlementNode = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $settlement     = new Settlement(new ErrorRegister());
        $xml            = $settlement->createXmlNode($settlementNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $settlementNode = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENT_TOTALS . "></" . ADocumentTotals::N_DOCUMENT_TOTALS . ">"
        );
        $settlement     = new Settlement(new ErrorRegister());
        $settlement->setPaymentTerms("");
        $settlement->setSettlementAmount(new Decimal("-1.0"));
        $settlement->setSettlementDiscount("");

        $xml = $settlement->createXmlNode($settlementNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($settlement->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($settlement->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($settlement->getErrorRegistor()->getLibXmlError());
    }
}
