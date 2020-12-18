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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
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
        $docTotals = new DocumentTotals(new ErrorRegister());
        $this->assertInstanceOf(DocumentTotals::class, $docTotals);
        $this->assertNull($docTotals->getSettlementAmount());
        $this->assertNull($docTotals->getCurrency(false));
        $this->assertFalse($docTotals->issetGrossTotal());
        $this->assertFalse($docTotals->issetNetTotal());
        $this->assertFalse($docTotals->issetTaxPayable());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetTaxPayable(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $tax       = 9.99;
        $this->assertTrue($docTotals->setTaxPayable($tax));
        $this->assertTrue($docTotals->issetTaxPayable());
        $this->assertSame($tax, $docTotals->getTaxPayable());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $net       = 19.99;
        $this->assertTrue($docTotals->setNetTotal($net));
        $this->assertTrue($docTotals->issetNetTotal());
        $this->assertSame($net, $docTotals->getNetTotal());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $gross     = 99.99;
        $docTotals->setGrossTotal($gross);
        $this->assertTrue($docTotals->issetGrossTotal());
        $this->assertSame($gross, $docTotals->getGrossTotal());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetSettlementAmount(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $settlement = 29.99;
        $this->assertTrue($docTotals->setSettlementAmount($settlement));
        $this->assertSame($settlement, $docTotals->getSettlementAmount());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetGetCurrency(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $currency  = $docTotals->getCurrency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNegativeFloatGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $worng     = -0.09;
        $this->assertFalse($docTotals->setGrossTotal($worng));
        $this->assertSame($worng, $docTotals->getGrossTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNegativeFloatNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $worng     = -0.09;
        $this->assertFalse($docTotals->setNetTotal($worng));
        $this->assertSame($worng, $docTotals->getNetTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNegativeFloatSettlementAmount(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $worng     = -0.09;
        $this->assertFalse($docTotals->setSettlementAmount($worng));
        $this->assertSame($worng, $docTotals->getSettlementAmount());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNegativeFloatTaxPayable(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $worng     = -0.09;
        $this->assertFalse($docTotals->setTaxPayable($worng));
        $this->assertSame($worng, $docTotals->getTaxPayable());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * Reads all Payments's Documents totals from the Demo SAFT in Test\Ressources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

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
                $docTotals    = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($docTotalsXml);


                $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $docTotals->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($docTotalsXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Payment '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO}, $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $e->getMessage()
                        )
                    );
                }

                $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
                $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
                $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXmlCurrency(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $tax        = 9.99;
        $docTotals->setTaxPayable($tax);
        $net        = 19.99;
        $docTotals->setNetTotal($net);
        $gross      = 99.99;
        $docTotals->setGrossTotal($gross);
        $settlement = 29.99;
        $docTotals->setSettlementAmount($settlement);

        $currency = $docTotals->getCurrency();
        $currency->setCurrencyCode(new CurrencyCode(CurrencyCode::ISO_GBP));
        $currency->setCurrencyAmount(100.0);
        $currency->setExchangeRate(0.99);
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());

        $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
        $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
        $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

        $xml = $docTotals->createXmlNode($payNode);

        $parsed = new DocumentTotals(new ErrorRegister());
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

        $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());

        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $xml        = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $totals->setGrossTotal(-9.03);
        $totals->setGrossTotal(-9.45);
        $totals->setTaxPayable(-9.74);
        $totals->setSettlementAmount(-0.01);

        $xml = $totals->createXmlNode($totalsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($totals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($totals->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($totals->getErrorRegistor()->getLibXmlError());
    }
}
