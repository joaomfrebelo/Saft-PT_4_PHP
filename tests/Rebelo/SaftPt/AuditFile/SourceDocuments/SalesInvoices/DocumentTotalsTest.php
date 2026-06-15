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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'SettlementTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'PaymentMethodTest.php';

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\Commune;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(DocumentTotals::class))->testReflection(DocumentTotals::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $documentTotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
        $this->assertNull($documentTotals->getCurrency(false));
        $this->assertSame(0, \count($documentTotals->getSettlement()));
        $this->assertSame(0, \count($documentTotals->getPayment()));

        $this->assertFalse($documentTotals->issetGrossTotal());
        $this->assertFalse($documentTotals->issetNetTotal());
        $this->assertFalse($documentTotals->issetTaxPayable());

        try {
            $documentTotals->getGrossTotal();
            $this->fail("Get GrossTotal without initialization Should throw \Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try {
            $documentTotals->getNetTotal();
            $this->fail("Get NetTotal without initialization Should throw \Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try {
            $documentTotals->getTaxPayable();
            $this->fail("Get TaxPayable without initialization Should throw \Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGet(): void
    {
        $documentTotals = new DocumentTotals(new ErrorRegister());
        $grossTotal     = new Decimal("909.59");
        $netTotal       = new Decimal("500.59");
        $taxPayable     = new Decimal("209.49");

        $documentTotals->setGrossTotal($grossTotal);
        $this->assertTrue($documentTotals->issetGrossTotal());
        $documentTotals->setNetTotal($netTotal);
        $this->assertTrue($documentTotals->issetNetTotal());
        $documentTotals->setTaxPayable($taxPayable);
        $this->assertTrue($documentTotals->issetTaxPayable());

        $currency = $documentTotals->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $this->assertSame($grossTotal, $documentTotals->getGrossTotal());
        $this->assertSame($netTotal, $documentTotals->getNetTotal());
        $this->assertSame($taxPayable, $documentTotals->getTaxPayable());
        $this->assertSame($currency, $documentTotals->getCurrency());

        $documentTotals->setCurrencyAsNull();
        $this->assertNull($documentTotals->getCurrency(false));

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $nPayment = $documentTotals->addPayment();
            $nPayment->setPaymentAmount(new Decimal((string)$n));
            $stack = $documentTotals->getPayment();
            $this->assertSame(
                (float)$n, $stack[$n]->getPaymentAmount()->toFloat()
            );
        }

        for ($n = 0; $n < $nCount; $n++) {
            $nSettlement = $documentTotals->addSettlement();
            $nSettlement->setSettlementAmount(new Decimal((string)($n)));
            $stack = $documentTotals->getSettlement();
            $this->assertSame(
                (float)$n, $stack[$n]->getSettlementAmount()?->toFloat()
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNegativeSet(): void
    {
        $docTot = new DocumentTotals(new ErrorRegister());

        $wrong = new Decimal("-0.01");
        $this->assertFalse($docTot->setGrossTotal($wrong));
        $this->assertSame($wrong, $docTot->getGrossTotal());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());

        $docTot->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($docTot->setNetTotal($wrong));
        $this->assertSame($wrong, $docTot->getNetTotal());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());

        $docTot->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($docTot->setTaxPayable($wrong));
        $this->assertSame($wrong, $docTot->getTaxPayable());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return DocumentTotals
     */
    public function createDocumentTotals(): DocumentTotals
    {
        $documentTotals = new DocumentTotals(new ErrorRegister());
        $grossTotal     = new Decimal("909.59");
        $netTotal       = new Decimal("500.59");
        $taxPayable     = new Decimal("209.49");
        $currency       = $documentTotals->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $documentTotals->setGrossTotal($grossTotal);
        $documentTotals->setNetTotal($netTotal);
        $documentTotals->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $pay = $documentTotals->addPayment();
            $pay->setPaymentAmount(new Decimal((string)$n));
            $pay->setPaymentDate(new RDate());

            $sett = $documentTotals->addSettlement();
            $sett->setSettlementAmount(new Decimal((string)$n));
        }

        return $documentTotals;
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $docTot = new DocumentTotals(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $docTot->createXmlNode($node);
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
        $docTot = new DocumentTotals(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $docTot->parseXmlNode($node);
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
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENT_TOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            $docTot->getGrossTotal()->toFloat(),
            (float)$node->{DocumentTotals::N_DOCUMENT_TOTALS}->{DocumentTotals::N_GROSS_TOTAL}
        );

        $this->assertSame(
            $docTot->getNetTotal()->toFloat(),
            (float)$node->{DocumentTotals::N_DOCUMENT_TOTALS}->{DocumentTotals::N_NET_TOTAL}
        );

        $this->assertSame(
            $docTot->getTaxPayable()->toFloat(),
            (float)$node->{DocumentTotals::N_DOCUMENT_TOTALS}->{DocumentTotals::N_TAX_PAYABLE}
        );

        $this->assertSame(
            $docTot->getCurrency()?->getCurrencyAmount()->toFloat(),
            (float)$node->{DocumentTotals::N_DOCUMENT_TOTALS}
                ->{DocumentTotals::N_CURRENCY}
                ->{Currency::N_CURRENCY_AMOUNT}
        );

        $settStack = $docTot->getSettlement();
        $payStack  = $docTot->getPayment();
        for ($n = 0; $n < 5; $n++) {
            $settlement = $settStack[$n];
            $this->assertSame(
                $settlement->getSettlementAmount()?->toFloat(),
                (float)$node->{DocumentTotals::N_DOCUMENT_TOTALS}
                           ->{DocumentTotals::N_SETTLEMENT}[$n]->{Settlement::N_SETTLEMENT_AMOUNT}
            );

            $payment = $payStack[$n];
            $pNode   = $node->{DocumentTotals::N_DOCUMENT_TOTALS}
                           ->{DocumentTotals::N_PAYMENT}[$n];

            $this->assertSame(
                $payment->getPaymentAmount()->toFloat(),
                (float)$pNode->{PaymentMethod::N_PAYMENT_AMOUNT}
            );
        }

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml    = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $parsed->getCurrency()?->getCurrencyCode(),
            $docTot->getCurrency()?->getCurrencyCode()
        );

        $this->assertSame(
            $parsed->getGrossTotal()->toFloat(),
            $docTot->getGrossTotal()->toFloat()
        );

        $this->assertSame(
            $parsed->getNetTotal()->toFloat(), $docTot->getNetTotal()->toFloat()
        );

        $this->assertSame(
            $parsed->getTaxPayable()->toFloat(), $docTot->getTaxPayable()->toFloat()
        );

        for ($n = 0; $n < 5; $n++) {
            $set = $docTot->getSettlement()[$n];
            $par = $parsed->getSettlement()[$n];
            $this->assertSame(
                $par->getSettlementAmount()?->toFloat(), $set->getSettlementAmount()?->toFloat()
            );
        }

        for ($n = 0; $n < 5; $n++) {
            $pay = $docTot->getPayment()[$n];
            $par = $parsed->getPayment()[$n];
            $this->assertSame(
                $par->getPaymentAmount()->toFloat(),
                $pay->getPaymentAmount()->toFloat()
            );
        }

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeNullCurrency(): void
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrencyAsNull();
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENT_TOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENT_TOTALS}
                ->{DocumentTotals::N_CURRENCY}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeEmptySettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $pay = $docTot->addPayment();
            $pay->setPaymentAmount(new Decimal((string)$n));
            $pay->setPaymentDate(new RDate());
        }

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENT_TOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENT_TOTALS}
                ->{DocumentTotals::N_SETTLEMENT}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeEmptyPayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $sett = $docTot->addSettlement();
            $sett->setSettlementAmount(new Decimal((string)$n));
        }

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENT_TOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENT_TOTALS}
                ->{DocumentTotals::N_PAYMENT}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNullCurrency(): void
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrencyAsNull();
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getCurrency(false));

        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlEmptySettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $pay = $docTot->addPayment();
            $pay->setPaymentAmount(new Decimal((string)$n));
            $pay->setPaymentDate(new RDate());
        }

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(0, \count($parsed->getSettlement()));

        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlEmptyPayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(0, \count($parsed->getPayment()));

        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlSingleSettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $docTot->addSettlement();

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(1, \count($parsed->getSettlement()));

        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlSinglePayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = new Decimal("909.59");
        $netTotal   = new Decimal("500.59");
        $taxPayable = new Decimal("209.49");
        $currency   = $docTot->getCurrency();
        $currency?->setCurrencyAmount(new Decimal("90.09"));
        $currency?->setExchangeRate(new Decimal("1.094"));
        $currency?->setCurrencyCode(CurrencyCode::ISO_LBP);

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $pay = $docTot->addPayment();
        $pay->setPaymentDate(new RDate());
        $pay->setPaymentAmount(new Decimal("9.99"));

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(1, \count($parsed->getPayment()));

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
        $docTotNode = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $docTot     = new DocumentTotals(new ErrorRegister());
        $xml        = $docTot->createXmlNode($docTotNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $docTotNode = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $docTot     = new DocumentTotals(new ErrorRegister());
        $docTot->setGrossTotal(new Decimal("-9.5"));
        $docTot->setNetTotal(new Decimal("-9.45"));
        $docTot->setTaxPayable(new Decimal("9.59"));

        $xml = $docTot->createXmlNode($docTotNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }
}
