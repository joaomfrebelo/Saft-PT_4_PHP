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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Currency;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\TXmlTest;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest extends TestCase
{

    use TXmlTest;

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
     */
    #[Test]
    public function testInstanceSetGetTaxPayable(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $tax       = new Decimal("9.99");
        $this->assertTrue($docTotals->setTaxPayable($tax));
        $this->assertTrue($docTotals->issetTaxPayable());
        $this->assertSame($tax, $docTotals->getTaxPayable());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $net       = new Decimal("19.99");
        $this->assertTrue($docTotals->setNetTotal($net));
        $this->assertTrue($docTotals->issetNetTotal());
        $this->assertSame($net, $docTotals->getNetTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $gross     = new Decimal("99.99");
        $docTotals->setGrossTotal($gross);
        $this->assertTrue($docTotals->issetGrossTotal());
        $this->assertSame($gross, $docTotals->getGrossTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetSettlementAmount(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $settlement = new Decimal("29.99");
        $this->assertTrue($docTotals->setSettlementAmount($settlement));
        $this->assertSame($settlement, $docTotals->getSettlementAmount());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetGetCurrency(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $currency  = $docTotals->getCurrency();
        $currency?->setCurrencyCode(CurrencyCode::ISO_GBP);
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNegativeFloatGrossTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $wrong     = new Decimal("-0.09");
        $this->assertFalse($docTotals->setGrossTotal($wrong));
        $this->assertSame($wrong, $docTotals->getGrossTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNegativeFloatNetTotal(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $wrong     = new Decimal("-0.09");
        $this->assertFalse($docTotals->setNetTotal($wrong));
        $this->assertSame($wrong, $docTotals->getNetTotal());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNegativeFloatSettlementAmount(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $wrong     = new Decimal("-0.09");
        $this->assertFalse($docTotals->setSettlementAmount($wrong));
        $this->assertSame($wrong, $docTotals->getSettlementAmount());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNegativeFloatTaxPayable(): void
    {
        $docTotals = new DocumentTotals(new ErrorRegister());
        $wrong     = new Decimal("-0.09");
        $this->assertFalse($docTotals->setTaxPayable($wrong));
        $this->assertSame($wrong, $docTotals->getTaxPayable());
        $this->assertNotEmpty($docTotals->getErrorRegistor()->getOnSetValue());
    }

    /**
     * Reads all Payment's Documents totals from the Demo SAFT in Test\Resources
     * and parse then to DocumentTotals class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @throws AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $paymentsStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCE_DOCUMENTS}
            ->{Payments::N_PAYMENTS}
            ->{Payment::N_PAYMENT};

        if ($paymentsStack->count() === 0) {
            $this->fail("No Payment in XML");
        }

        for ($i = 0; $i < $paymentsStack->count(); $i++) {
            $paymentXml     = $paymentsStack[$i];
            $docTotalsStack = $paymentXml->{DocumentTotals::N_DOCUMENT_TOTALS};

            if ($docTotalsStack->count() === 0) {
                $this->fail("No document totals in Payment");
            }

            for ($l = 0; $l < $docTotalsStack->count(); $l++) {
                /* @var $docTotalsXml \SimpleXMLElement */
                $docTotalsXml = $docTotalsStack[$l];
                $docTotals    = new DocumentTotals(new ErrorRegister());
                $docTotals->parseXmlNode($docTotalsXml);


                $xmlRootNode   = (new AuditFile())->createRootElement();
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $docTotals->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($docTotalsXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Payment '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENT_REF_NO}, $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENT_REF_NO},
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
     * @throws AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXmlCurrency(): void
    {
        $docTotals  = new DocumentTotals(new ErrorRegister());
        $tax        = new Decimal("9.99");
        $docTotals->setTaxPayable($tax);
        $net        = new Decimal("19.99");
        $docTotals->setNetTotal($net);
        $gross      = new Decimal("99.99");
        $docTotals->setGrossTotal($gross);
        $settlement = new Decimal("29.99");
        $docTotals->setSettlementAmount($settlement);

        $currency = $docTotals->getCurrency();
        $currency?->setCurrencyCode(CurrencyCode::ISO_GBP);
        $currency?->setCurrencyAmount(new Decimal("100.0"));
        $currency?->setExchangeRate(new Decimal("0.99"));
        $this->assertInstanceOf(Currency::class, $docTotals->getCurrency());

        $xmlRootNode   = (new AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCE_DOCUMENTS);
        $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
        $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

        $xml = $docTotals->createXmlNode($payNode);

        $parsed = new DocumentTotals(new ErrorRegister());
        $parsed->parseXmlNode($xml);

        $this->assertSame(
            $docTotals->getCurrency()->getCurrencyCode(),
            $parsed->getCurrency()?->getCurrencyCode()
        );

        $this->assertSame(
            $docTotals->getGrossTotal()->toFloat(),
            $parsed->getGrossTotal()->toFloat()
        );

        $this->assertSame(
            $docTotals->getNetTotal()->toFloat(), $parsed->getNetTotal()->toFloat()
        );

        $this->assertSame(
            $docTotals->getSettlementAmount()?->toFloat(),
            $parsed->getSettlementAmount()?->toFloat()
        );

        $this->assertSame(
            $docTotals->getTaxPayable()->toFloat(), $parsed->getTaxPayable()->toFloat()
        );

        $this->assertEmpty($docTotals->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($docTotals->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($docTotals->getErrorRegistor()->getOnSetValue());

        $this->assertEmpty($parsed->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($parsed->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $totalsNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $totals     = new DocumentTotals(new ErrorRegister());
        $totals->setGrossTotal(new Decimal("-9.03"));
        $totals->setGrossTotal(new Decimal("-9.45"));
        $totals->setTaxPayable(new Decimal("-9.74"));
        $totals->setSettlementAmount(new Decimal("-0.01"));

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
