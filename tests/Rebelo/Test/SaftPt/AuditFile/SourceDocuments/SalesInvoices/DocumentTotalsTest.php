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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'SettlementTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'PaymentMethodTest.php';

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentTotals::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $documentTotals = new DocumentTotals();
        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
        $this->assertNull($documentTotals->getCurrency());
        $this->assertSame(0, \count($documentTotals->getSettlement()));
        $this->assertSame(0, \count($documentTotals->getPayment()));

        try
        {
            $documentTotals->getGrossTotal();
            $this->fail("Get GrossTotal without initialization Should throw \Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try
        {
            $documentTotals->getNetTotal();
            $this->fail("Get NetTotal without initialization Should throw \Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }

        try
        {
            $documentTotals->getTaxPayable();
            $this->fail("Get TaxPayable without initialization Should throw \Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    public function testSetGet()
    {
        $documentTotals = new DocumentTotals();
        $grossTotal     = 909.59;
        $netTotal       = 500.59;
        $taxPayable     = 209.49;
        $currency       = new Currency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $documentTotals->setGrossTotal($grossTotal);
        $documentTotals->setNetTotal($netTotal);
        $documentTotals->setTaxPayable($taxPayable);
        $documentTotals->setCurrency($currency);

        $this->assertSame($grossTotal, $documentTotals->getGrossTotal());
        $this->assertSame($netTotal, $documentTotals->getNetTotal());
        $this->assertSame($taxPayable, $documentTotals->getTaxPayable());
        $this->assertSame($currency, $documentTotals->getCurrency());

        $documentTotals->setCurrency(null);
        $this->assertNull($documentTotals->getCurrency());

        $payTest = new \Rebelo\Test\SaftPt\AuditFile\SourceDocuments\PaymentMethodTest();
        $payment = $payTest->createPaymentMethod();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            $nPayment = clone $payment;
            $nPayment->setPaymentAmount((float) $n);
            $this->assertSame($n, $documentTotals->addToPayment($nPayment));
            $this->assertTrue($documentTotals->issetPayment($n));
            /* @var $stack PaymentMethod[] */
            $stack    = $documentTotals->getPayment();
            $this->assertSame(
                (float) $n, $stack[$n]->getPaymentAmount()
            );
        }

        $settTest   = new \Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SettlementTest();
        $settlement = $settTest->createSettlement();

        for ($n = 0; $n < $nCount; $n++)
        {
            $nSettlement = clone $settlement;
            $nSettlement->setSettlementAmount((float) ($n));
            $this->assertSame($n, $documentTotals->addToSettlement($nSettlement));
            $this->assertTrue($documentTotals->issetSettlement($n));
            /* @var $stack Settlement[] */
            $stack       = $documentTotals->getSettlement();
            $this->assertSame(
                (float) $n, $stack[$n]->getSettlementAmount()
            );
        }
    }

    /**
     *
     */
    public function testNegativeSet()
    {
        $docTot = new DocumentTotals();
        try
        {
            $docTot->setGrossTotal(-0.01);
            $this->fail("Set GrossTotal to a negative number Should throw "
                . "Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $docTot->setNetTotal(-0.01);
            $this->fail("Set NetTotal to a negative number Should throw "
                . "Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $docTot->setTaxPayable(-0.01);
            $this->fail("Set TaxPayable to a negative number Should throw "
                . "Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     * @return DocumentTotals
     */
    public function createDocumentTotals(): DocumentTotals
    {
        $documentTotals = new DocumentTotals();
        $grossTotal     = 909.59;
        $netTotal       = 500.59;
        $taxPayable     = 209.49;
        $currency       = new Currency();
        $currency->setCurrencyAmount(90.09);
        $currency->setExchangeRate(1.094);
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_LBP));

        $documentTotals->setGrossTotal($grossTotal);
        $documentTotals->setNetTotal($netTotal);
        $documentTotals->setTaxPayable($taxPayable);
        $documentTotals->setCurrency($currency);

        $payTest = new \Rebelo\Test\SaftPt\AuditFile\SourceDocuments\PaymentMethodTest();
        $payment = $payTest->createPaymentMethod();

        $settTest   = new \Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SettlementTest();
        $settlement = $settTest->createSettlement();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            /* @var $pay \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod */
            $pay = clone $payment;
            $pay->setPaymentAmount((float) $n);
            $documentTotals->addToPayment($pay);

            /* @var $sett \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Settlement */
            $sett = clone $settlement;
            $sett->setSettlementAmount((float) $n);
            $documentTotals->addToSettlement($sett);
        }

        return $documentTotals;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $docTot = new DocumentTotals();
        $node   = new \SimpleXMLElement("<root></root>");
        try
        {
            $docTot->createXmlNode($node);
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
        $docTot = new DocumentTotals();
        $node   = new \SimpleXMLElement("<root></root>");
        try
        {
            $docTot->parseXmlNode($node);
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
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
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

        $nCount    = 5;
        $settStack = $docTot->getSettlement();
        $payStack  = $docTot->getPayment();
        for ($n = 0; $n < 5; $n++)
        {
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
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $docTot = $this->createDocumentTotals();
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml    = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
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

        for ($n = 0; $n < 5; $n++)
        {
            /* @var $set Settlement */
            $set = $docTot->getSettlement()[$n];
            /* @var $par Settlement */
            $par = $parsed->getSettlement()[$n];
            $this->assertSame(
                $par->getSettlementAmount(), $set->getSettlementAmount()
            );
        }

        for ($n = 0; $n < 5; $n++)
        {
            /* @var $pay PaymentMethod */
            $pay = $docTot->getPayment()[$n];
            /* @var $par PaymentMethod */
            $par = $parsed->getPayment()[$n];
            $this->assertSame(
                $par->getPaymentAmount(), $pay->getPaymentAmount()
            );
        }
    }

    public function testCreateXmlNodeNullCurrency()
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrency(null);
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(0,
                          $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_CURRENCY}->count()
        );
    }

    public function testCreateXmlNodeEmptySettlement()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getSettlement();
        for ($n = 0; $n < \count($nCount); $n++)
        {
            $docTot->unsetSettlement($n);
        }

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(0,
                          $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_SETTLEMENT}->count()
        );
    }

    public function testCreateXmlNodeEmptyPayment()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getPayment();
        for ($n = 0; $n < \count($nCount); $n++)
        {
            $docTot->unsetPayment($n);
        }

        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $docTotNode = $docTot->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $docTotNode);

        $this->assertSame(
            DocumentTotals::N_DOCUMENTTOTALS, $docTotNode->getName()
        );

        $this->assertSame(0,
                          $node->{DocumentTotals::N_DOCUMENTTOTALS}
            ->{DocumentTotals::N_PAYMENT}->count()
        );
    }

    /**
     *
     */
    public function testeParseXmlNullCurrency()
    {
        $docTot = $this->createDocumentTotals();
        $docTot->setCurrency(null);
        $node   = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml    = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getCurrency());
    }

    /**
     *
     */
    public function testeParseXmlEmptySettlement()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getSettlement();
        for ($n = 0; $n < \count($nCount); $n++)
        {
            $docTot->unsetSettlement($n);
        }
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(0, \count($parsed->getSettlement()));
    }

    /**
     *
     */
    public function testeParseXmlEmptyPayment()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getPayment();
        for ($n = 0; $n < \count($nCount); $n++)
        {
            $docTot->unsetPayment($n);
        }
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(0, \count($parsed->getPayment()));
    }

    /**
     *
     */
    public function testeParseXmlSingleSettlement()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getSettlement();
        for ($n = 1; $n < \count($nCount); $n++)
        {
            $docTot->unsetSettlement($n);
        }
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(1, \count($parsed->getSettlement()));
    }

    /**
     *
     */
    public function testeParseXmlSinglePayment()
    {
        $docTot = $this->createDocumentTotals();
        $nCount = $docTot->getPayment();
        for ($n = 1; $n < \count($nCount); $n++)
        {
            $docTot->unsetPayment($n);
        }
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $docTot->createXmlNode($node)->asXML();

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(1, \count($parsed->getPayment()));
    }

}
