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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocumentTotals;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class CurrencyTest
 *
 * @author João Rebelo
 */
class CurrencyTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Currency::class);
        $this->assertTrue(true);
    }

    public function testInstanceGetSet()
    {
        $currency = new Currency();
        $this->assertInstanceOf(Currency::class, $currency);
        try
        {
            $currency->getCurrencyCode();
            $this->fail("Get CurrencyCode whitout be setted should throw "
                . "\Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try
        {
            $currency->getCurrencyAmount();
            $this->fail("Get Ammout whitout be setted should throw "
                . "\Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try
        {
            $currency->getExchangeRate();
            $this->fail("Get ExchangeRate whitout be setted should throw "
                . "\Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        $code = CurrencyCode::ISO_GBP;
        $currency->setCurrencyCode(new CurrencyCode($code));
        $this->assertSame($code, $currency->getCurrencyCode()->get());

        $amount = 459.95;
        $currency->setCurrencyAmount($amount);
        $this->assertSame($amount, $currency->getCurrencyAmount());

        try
        {
            $currency->setCurrencyAmount(-1.9);
            $this->fail("A negative CurrencyAmount should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
        $this->assertSame($amount, $currency->getCurrencyAmount());

        $rate = 1.59;
        $currency->setExchangeRate($rate);
        $this->assertSame($rate, $currency->getExchangeRate());
        try
        {
            $currency->setExchangeRate(-1.9);
            $this->fail("A negative ExchangeRate should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
        $this->assertSame($rate, $currency->getExchangeRate());
    }

    public function testCreateXmlNodeWrongName()
    {
        $currency = new Currency();
        $node     = new \SimpleXMLElement("<root></root>");
        try
        {
            $currency->createXmlNode($node);
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
        $currency = new Currency();
        $node     = new \SimpleXMLElement("<root></root>");
        try
        {
            $currency->parseXmlNode($node);
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

    public function createCurrency(): Currency
    {
        $currency = new Currency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $currency->setCurrencyAmount(259.99);
        $currency->setExchangeRate(0.99);
        return $currency;
    }

    public function testCreateXmlNode()
    {
        $currency = $this->createCurrency();
        $node     = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
        );

        $currencyNode = $currency->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $currencyNode);

        $this->assertSame(
            Currency::N_CURRENCY, $currencyNode->getName()
        );

        $this->assertSame(
            $currency->getCurrencyCode()->get(),
            (string) $node->{Currency::N_CURRENCY}
            ->{Currency::N_CURRENCYCODE}
        );

        $this->assertSame(
            $currency->getCurrencyAmount(),
            (float) $node->{Currency::N_CURRENCY}
            ->{Currency::N_CURRENCYAMOUNT}
        );

        $this->assertSame(
            $currency->getExchangeRate(),
            (float) $node->{Currency::N_CURRENCY}
            ->{Currency::N_EXCHANGERATE}
        );
    }

    public function testeParseXml()
    {
        $currency = $this->createCurrency();
        $node     = new \SimpleXMLElement(
            "<" . ADocumentTotals::N_DOCUMENTTOTALS . "></" . ADocumentTotals::N_DOCUMENTTOTALS . ">"
        );
        $xml      = $currency->createXmlNode($node)->asXML();

        $parsed = new Currency();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame($currency->getCurrencyCode()->get(),
                          $parsed->getCurrencyCode()->get());
        $this->assertSame($currency->getCurrencyAmount(),
                          $parsed->getCurrencyAmount());
        $this->assertSame(
            $currency->getExchangeRate(), $parsed->getExchangeRate()
        );
    }

}
