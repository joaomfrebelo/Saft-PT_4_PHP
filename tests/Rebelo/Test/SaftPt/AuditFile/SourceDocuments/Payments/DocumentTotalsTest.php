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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\Payments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

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
    public function testInstanceSetGet()
    {
        $docTotals = new DocumentTotals();
        $this->assertInstanceOf(DocumentTotals::class, $docTotals);
        $this->assertNull($docTotals->getSettlementAmount());
        $this->assertNull($docTotals->getCurrency());

        $tax = 9.99;
        $docTotals->setTaxPayable($tax);
        $this->assertSame($tax, $docTotals->getTaxPayable());

        $net = 19.99;
        $docTotals->setNetTotal($net);
        $this->assertSame($net, $docTotals->getNetTotal());

        $gross = 99.99;
        $docTotals->setGrossTotal($gross);
        $this->assertSame($gross, $docTotals->getGrossTotal());

        $settlement = 29.99;
        $docTotals->setSettlementAmount($settlement);
        $this->assertSame($settlement, $docTotals->getSettlementAmount());

        $currency = new Currency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $docTotals->setCurrency($currency);
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());
    }

    public function testNegativeFloat()
    {
        $docTotals = new DocumentTotals();
        try {
            $docTotals->setGrossTotal(-0.09);
            $this->fail("Set GrossTotal to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException;");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $docTotals->setNetTotal(-0.09);
            $this->fail("Set NetTotal to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException;");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $docTotals->setSettlementAmount(-0.09);
            $this->fail("Set SettlementAmount to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException;");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $docTotals->setTaxPayable(-0.09);
            $this->fail("Set TaxPayable to a negative number should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException;");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     * Reads all Payments's Documents totals from the Demo SAFT in Test\Ressources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $paymentsStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{Payments::N_PAYMENTS}
            ->{Payment::N_PAYMENT};

        if ($paymentsStack->count() === 0) {
            $this->fail("No Payment in XML");
        }

        for ($i = 0; $i < $paymentsStack->count(); $i++) {
            $paymentXml     = $paymentsStack[$i];
            $docTotalsStack = $paymentXml->{DocumentTotals::N_DOCUMENTTOTALS};

            if ($docTotalsStack->count() === 0) {
                $this->fail("No document totals in Payment");
            }

            for ($l = 0; $l < $docTotalsStack->count(); $l++) {
                /* @var $docTotalsXml \SimpleXMLElement */
                $docTotalsXml = $docTotalsStack[$l];
                $docTotals    = new DocumentTotals();
                $docTotals->parseXmlNode($docTotalsXml);


                $xmlRootNode   = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $docTotals->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($docTotalsXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Payment '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO}, $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $e->getMessage()));
                }
            }
        }
    }

    /**
     *
     */
    public function testCreateParseXmlCurrency()
    {
        $docTotals  = new DocumentTotals();
        $tax        = 9.99;
        $docTotals->setTaxPayable($tax);
        $net        = 19.99;
        $docTotals->setNetTotal($net);
        $gross      = 99.99;
        $docTotals->setGrossTotal($gross);
        $settlement = 29.99;
        $docTotals->setSettlementAmount($settlement);

        $currency = new Currency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $currency->setCurrencyAmount(100.0);
        $currency->setExchangeRate(0.99);
        $docTotals->setCurrency($currency);
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
        $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
        $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

        $xml = $docTotals->createXmlNode($payNode);

        $parsed = new DocumentTotals();
        $parsed->parseXmlNode($xml);

        $this->assertSame(
            $docTotals->getCurrency()->getCurrencyCode()->get(),
            $parsed->getCurrency()->getCurrencyCode()->get()
        );

        $this->assertSame(
            $docTotals->getGrossTotal(), $parsed->getGrossTotal()
        );

        $this->assertSame(
            $docTotals->getNetTotal(), $parsed->getNetTotal()
        );

        $this->assertSame(
            $docTotals->getSettlementAmount(), $parsed->getSettlementAmount()
        );

        $this->assertSame(
            $docTotals->getTaxPayable(), $parsed->getTaxPayable()
        );
    }
}