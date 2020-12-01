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

require_once __DIR__.DIRECTORY_SEPARATOR.'SettlementTest.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'PaymentMethodTest.php';

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\Date\Date as RDate;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentTotals::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
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
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try {
            $documentTotals->getNetTotal();
            $this->fail("Get NetTotal without initialization Should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try {
            $documentTotals->getTaxPayable();
            $this->fail("Get TaxPayable without initialization Should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGet(): void
    {
        $documentTotals = new DocumentTotals(new ErrorRegister());
        $grossTotal     = 909.59;
        $netTotal       = 500.59;
        $taxPayable     = 209.49;

        $documentTotals->setGrossTotal($grossTotal);
        $this->assertTrue($documentTotals->issetGrossTotal());
        $documentTotals->setNetTotal($netTotal);
        $this->assertTrue($documentTotals->issetNetTotal());
        $documentTotals->setTaxPayable($taxPayable);
        $this->assertTrue($documentTotals->issetTaxPayable());

        $currency = $documentTotals->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $this->assertSame($grossTotal, $documentTotals->getGrossTotal());
        $this->assertSame($netTotal, $documentTotals->getNetTotal());
        $this->assertSame($taxPayable, $documentTotals->getTaxPayable());
        $this->assertSame($currency, $documentTotals->getCurrency());

        $documentTotals->setCurrencyAsNull();
        $this->assertNull($documentTotals->getCurrency(false));

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $nPayment = $documentTotals->addPayment();
            $nPayment->setPaymentAmount((float) $n);
            /* @var $stack PaymentMethod[] */
            $stack    = $documentTotals->getPayment();
            $this->assertSame(
                (float) $n, $stack[$n]->getPaymentAmount()
            );
        }

        for ($n = 0; $n < $nCount; $n++) {
            $nSettlement = $documentTotals->addSettlement();
            $nSettlement->setSettlementAmount((float) ($n));
            /* @var $stack Settlement[] */
            $stack       = $documentTotals->getSettlement();
            $this->assertSame(
                (float) $n, $stack[$n]->getSettlementAmount()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNegativeSet(): void
    {
        $docTot = new DocumentTotals(new ErrorRegister());

        $wrong = -0.01;
        $this->assertFalse($docTot->setGrossTotal($wrong));
        $this->assertSame($wrong, $docTot->getGrossTotal());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());

        $docTot->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($docTot->setNetTotal($wrong));
        $this->assertSame($wrong, $docTot->getNetTotal());
        $this->assertNotEmpty($docTot->getErrorRegistor()->getOnSetValue());

        $docTot->getErrorRegistor()->cleaeAllErrors();
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
        $grossTotal     = 909.59;
        $netTotal       = 500.59;
        $taxPayable     = 209.49;
        $currency       = $documentTotals->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $documentTotals->setGrossTotal($grossTotal);
        $documentTotals->setNetTotal($netTotal);
        $documentTotals->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            /* @var $pay \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
            $pay = $documentTotals->addPayment();
            $pay->setPaymentAmount((float) $n);
            $pay->setPaymentDate(new RDate());

            /* @var $sett \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement */
            $sett = $documentTotals->addSettlement();
            $sett->setSettlementAmount((float) $n);
        }

        return $documentTotals;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $docTot = new DocumentTotals(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $docTot->createXmlNode($node);
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
        $docTot = new DocumentTotals(new ErrorRegister());
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $docTot->parseXmlNode($node);
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
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            $docTot->getGrossTotal(),
            (float) $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_GROSSTOTAL}
        );

        $this->assertSame(
            $docTot->getNetTotal(),
            (float) $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_NETTOTAL}
        );

        $this->assertSame(
            $docTot->getTaxPayable(),
            (float) $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_TAXPAYABLE}
        );

        $this->assertSame(
            $docTot->getCurrency()->getCurrencyAmount(),
            (float) $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_CURRENCY}->{Currency::N_CURRENCYAMOUNT}
        );

        $settStack = $docTot->getSettlement();
        $payStack  = $docTot->getPayment();
        for ($n = 0; $n < 5; $n++) {
            /* @var $settlement Settlement */
            $settlement = $settStack[$n];
            $this->assertSame(
                $settlement->getSettlementAmount(),
                (float) $node->{DocumentTotals::N_DOCUMENTTOTALS}
                ->{DocumentTotals::N_SETTLEMENT}[$n]->{Settlement::N_SETTLEMENTAMOUNT}
            );

            /* @var $payment PaymentMethod */
            $payment = $payStack[$n];
            $pNode   = $node->{DocumentTotals::N_DOCUMENTTOTALS}
                ->{DocumentTotals::N_PAYMENT}[$n];

            $this->assertSame(
                $payment->getPaymentAmount(),
                (float) $pNode->{PaymentMethod::N_PAYMENTAMOUNT}
            );
        }

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $xml    = $docTot->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $parsed->getCurrency()->getCurrencyCode()->get(),
            $docTot->getCurrency()->getCurrencyCode()->get()
        );

        $this->assertSame(
            $parsed->getGrossTotal(), $docTot->getGrossTotal()
        );

        $this->assertSame(
            $parsed->getNetTotal(), $docTot->getNetTotal()
        );

        $this->assertSame(
            $parsed->getTaxPayable(), $docTot->getTaxPayable()
        );

        for ($n = 0; $n < 5; $n++) {
            /* @var $set Settlement */
            $set = $docTot->getSettlement()[$n];
            /* @var $par Settlement */
            $par = $parsed->getSettlement()[$n];
            $this->assertSame(
                $par->getSettlementAmount(), $set->getSettlementAmount()
            );
        }

        for ($n = 0; $n < 5; $n++) {
            /* @var $pay PaymentMethod */
            $pay = $docTot->getPayment()[$n];
            /* @var $par PaymentMethod */
            $par = $parsed->getPayment()[$n];
            $this->assertSame(
                $par->getPaymentAmount(), $pay->getPaymentAmount()
            );
        }

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeNullCurrency(): void
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrencyAsNull();
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_CURRENCY}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeEmptySettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            /* @var $pay \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
            $pay = $docTot->addPayment();
            $pay->setPaymentAmount((float) $n);
            $pay->setPaymentDate(new RDate());
        }

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_SETTLEMENT}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeEmptyPayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            /* @var $sett \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement */
            $sett = $docTot->addSettlement();
            $sett->setSettlementAmount((float) $n);
        }

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(
            0,
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_PAYMENT}->count()
        );

        $this->assertEmpty($docTot->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTot->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($docTot->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlNullCurrency(): void
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrencyAsNull();
        $node   = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $xml    = $docTot->createXmlNode($node)->asXML();
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
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlEmptySettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            /* @var $pay \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
            $pay = $docTot->addPayment();
            $pay->setPaymentAmount((float) $n);
            $pay->setPaymentDate(new RDate());
        }

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlEmptyPayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlSingleSettlement(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $docTot->addSettlement();

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlSinglePayment(): void
    {
        $docTot     = new DocumentTotals(new ErrorRegister());
        $grossTotal = 909.59;
        $netTotal   = 500.59;
        $taxPayable = 209.49;
        $currency   = $docTot->getCurrency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $docTot->setGrossTotal($grossTotal);
        $docTot->setNetTotal($netTotal);
        $docTot->setTaxPayable($taxPayable);

        $pay = $docTot->addPayment();
        $pay->setPaymentDate(new RDate());
        $pay->setPaymentAmount(9.99);

        $node = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $docTotNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $docTotNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $docTot     = new DocumentTotals(new ErrorRegister());
        $docTot->setGrossTotal(-9.5);
        $docTot->setNetTotal(-9.45);
        $docTot->setTaxPayable(9.59);

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