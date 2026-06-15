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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\Commune;

/**
 * Class CurrencyTest
 *
 * @author João Rebelo
 */
class CurrencyTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(Currency::class))->testReflection(Currency::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceGetSet(): void
    {
        $currency = new Currency(new ErrorRegister());
        $this->assertInstanceOf(Currency::class, $currency);
        try {
            $currency->getCurrencyCode();
            $this->fail(
                "Get CurrencyCode without be set should throw "
                ."\Error"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try {
            $currency->getCurrencyAmount();
            $this->fail(
                "Get Amount without be set should throw "
                ."\Error"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        try {
            $currency->getExchangeRate();
            $this->fail(
                "Get ExchangeRate without be set should throw "
                ."\Error"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Error::class, $e
            );
        }

        $code = CurrencyCode::ISO_GBP;
        $currency->setCurrencyCode($code);
        $this->assertSame($code, $currency->getCurrencyCode());

        $amount = new Decimal("459.95");
        $this->assertTrue($currency->setCurrencyAmount($amount));
        $this->assertSame($amount, $currency->getCurrencyAmount());

        $wrong = new Decimal("-1.9");
        $currency->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($currency->setCurrencyAmount($wrong));
        $this->assertSame($wrong, $currency->getCurrencyAmount());
        $this->assertNotEmpty($currency->getErrorRegistor()->getOnSetValue());

        $rate = new Decimal("1.59");
        $this->assertTrue($currency->setExchangeRate($rate));
        $this->assertSame($rate, $currency->getExchangeRate());

        $currency->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($currency->setExchangeRate($wrong));
        $this->assertSame($wrong, $currency->getExchangeRate());
        $this->assertNotEmpty($currency->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $currency = new Currency(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $currency->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
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
        $currency = new Currency(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $currency->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     *
     * @return Currency
     */
    public function createCurrency(): Currency
    {
        $currency = new Currency(new ErrorRegister());
        $currency->setCurrencyCode(CurrencyCode::ISO_GBP);
        $currency->setCurrencyAmount(new Decimal("259.99"));
        $currency->setExchangeRate(new Decimal("0.99"));
        return $currency;
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $currency = $this->createCurrency();
        $node     = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENT_TOTALS."></".ADocumentTotals::N_DOCUMENT_TOTALS.">"
        );

        $currencyNode = $currency->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $currencyNode);

        $this->assertSame(
            Currency::N_CURRENCY, $currencyNode->getName()
        );

        $this->assertSame(
            $currency->getCurrencyCode()->value,
            (string) $node->{Currency::N_CURRENCY}->{Currency::N_CURRENCY_CODE}
        );

        $this->assertSame(
            $currency->getCurrencyAmount()->toFloat(),
            (float) $node->{Currency::N_CURRENCY}->{Currency::N_CURRENCY_AMOUNT}
        );

        $this->assertSame(
            $currency->getExchangeRate()->toFloat(),
            (float) $node->{Currency::N_CURRENCY}->{Currency::N_EXCHANGE_RATE}
        );

        $this->assertEmpty($currency->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($currency->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($currency->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $currency = $this->createCurrency();
        $node     = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENT_TOTALS."></".ADocumentTotals::N_DOCUMENT_TOTALS.">"
        );
        $xml      = $currency->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Currency(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $currency->getCurrencyCode(),
            $parsed->getCurrencyCode()
        );

        $this->assertSame(
            $currency->getCurrencyAmount()->toFloat(), $parsed->getCurrencyAmount()->toFloat()
        );

        $this->assertSame(
            $currency->getExchangeRate()->toFloat(), $parsed->getExchangeRate()->toFloat()
        );

        $this->assertEmpty($currency->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($currency->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($currency->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $currencyNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENT_TOTALS."></".ADocumentTotals::N_DOCUMENT_TOTALS.">"
        );
        $currency     = new Currency(new ErrorRegister());
        $xml          = $currency->createXmlNode($currencyNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($currency->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($currency->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($currency->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $currencyNode = new \SimpleXMLElement(
            "<".ADocumentTotals::N_DOCUMENT_TOTALS."></".ADocumentTotals::N_DOCUMENT_TOTALS.">"
        );
        $currency     = new Currency(new ErrorRegister());
        $currency->setCurrencyAmount(new Decimal("-1.0"));
        $currency->setExchangeRate(new Decimal("-1.0"));

        $xml = $currency->createXmlNode($currencyNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($currency->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($currency->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($currency->getErrorRegistor()->getLibXmlError());
    }
}
