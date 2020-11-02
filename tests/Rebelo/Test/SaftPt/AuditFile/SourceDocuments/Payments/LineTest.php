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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Tax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class LineTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(Line::class, $line);
        $this->assertNull($line->getCreditAmount());
        $this->assertNull($line->getDebitAmount());
        $this->assertNull($line->getSettlementAmount());
        $this->assertNull($line->getTax(false));
        $this->assertNull($line->getTaxExemptionCode());
        $this->assertNull($line->getTaxExemptionReason());
        $this->assertSame(0, \count($line->getSourceDocumentID()));

        $this->assertFalse($line->issetLineNumber());
    }

    /**
     *
     */
    public function testSetGetLineNumber(): void
    {
        $line = new Line(new ErrorRegister());
        $num  = 1;
        $this->assertTrue($line->setLineNumber(1));
        $this->assertSame($num, $line->getLineNumber());
        $this->assertTrue($line->issetLineNumber());

        $wrong = -1;
        $this->assertFalse($line->setLineNumber($wrong));
        $this->assertSame($wrong, $line->getLineNumber());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     */
    public function testSetSourceDocumentID(): void
    {
        $line = new Line(new ErrorRegister());
        $nMax = 9;

        for ($n = 0; $n < $nMax; $n++) {
            $source = $line->addSourceDocumentID();
            $source->setOriginatingON(\strval($n));
            /* @var $stack \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID */
            $stack  = $line->getSourceDocumentID();
            $this->assertSame(\strval($n), $stack[$n]->getOriginatingON());
        }
    }

    /**
     *
     */
    public function testSetGetTax(): void
    {
        $line = new Line(new ErrorRegister());
        $this->assertInstanceOf(Tax::class, $line->getTax());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionReason(): void
    {
        $line   = new Line(new ErrorRegister());
        $reason = "Tax Exception Reason";
        $this->assertTrue($line->setTaxExemptionReason($reason));
        $this->assertSame($reason, $line->getTaxExemptionReason());
        $this->assertTrue($line->setTaxExemptionReason(\str_pad($reason, 99, "9")));
        $this->assertSame(60, \strlen($line->getTaxExemptionReason()));

        $wrong = "AAAAA";
        $this->assertFalse(
            $line->setTaxExemptionReason($wrong),
            "TaxExemptionReason with length less than 6"
        );
        $this->assertSame($wrong, $line->getTaxExemptionReason());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($line->setTaxExemptionReason(null));
        $this->assertNull($line->getTaxExemptionReason());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionCode(): void
    {
        $line = new Line(new ErrorRegister());
        $line->setTaxExemptionCode(new TaxExemptionCode(TaxExemptionCode::M01));
        $this->assertInstanceOf(
            TaxExemptionCode::class, $line->getTaxExemptionCode()
        );
        $line->setTaxExemptionCode(null);
        $this->assertNull($line->getTaxExemptionCode());
    }

    /**
     *
     */
    public function testSettlementAmount(): void
    {
        $line = new Line(new ErrorRegister());
        $sett = 9.09;
        $this->assertTrue($line->setSettlementAmount($sett));
        $this->assertSame($sett, $line->getSettlementAmount());
        $line->setSettlementAmount(null);
        $this->assertNull($line->getSettlementAmount());
    }

    public function testSetGetDebitCredit(): void
    {
        $line = new Line(new ErrorRegister());
        $deb  = 9.09;
        $cre  = 19.49;

        $this->assertTrue($line->setDebitAmount($deb));
        $this->assertSame($deb, $line->getDebitAmount());
        $this->assertTrue($line->setDebitAmount(null));
        $this->assertNull($line->getDebitAmount());

        $this->assertTrue($line->setCreditAmount($cre));
        $this->assertSame($cre, $line->getCreditAmount());
        $this->assertTrue($line->setCreditAmount(null));
        $this->assertNull($line->getCreditAmount());


        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        $line->setCreditAmount($cre);
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setDebitAmount($deb));
        $this->assertSame($deb, $line->getDebitAmount());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());

        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        $line->setDebitAmount($deb);
        $line->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($line->setCreditAmount($cre));
        $this->assertSame($cre, $line->getCreditAmount());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
    }

    /**
     * Reads all Payments's lines from the Demo SAFT in Test\Ressources
     * and parse then to Line class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
            return;
        }

        $paymentsStack = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{Payments::N_PAYMENTS}
            ->{Payment::N_PAYMENT};

        if ($paymentsStack->count() === 0) {
            $this->fail("No Payment in XML");
        }

        for ($i = 0; $i < $paymentsStack->count(); $i++) {
            $paymentXml = $paymentsStack[$i];
            $lineStack  = $paymentXml->{Line::N_LINE};

            if ($lineStack->count() === 0) {
                $this->fail("No lines in Payment");
            }

            for ($l = 0; $l < $lineStack->count(); $l++) {
                /* @var $lineXml \SimpleXMLElement */
                $lineXml = $lineStack[$l];
                $line    = new Line(new ErrorRegister());
                $line->parseXmlNode($lineXml);


                $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $line->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue(
                        $assertXml,
                        \sprintf(
                            "Fail on Payment '%s' Line '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $lineXml->{Line::N_LINENUMBER}, $assertXml
                        )
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(
                        \sprintf(
                            "Fail on Document '%s' Line '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $lineXml->{Line::N_LINENUMBER}, $e->getMessage()
                        )
                    );
                }
                $this->assertEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
                $this->assertEmpty($line->getErrorRegistor()->getOnSetValue());
                $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $lineNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $line     = new Line(new ErrorRegister());
        $xml      = $line->createXmlNode($lineNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($line->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $lineNode = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );
        $line     = new Line(new ErrorRegister());
        $line->setCreditAmount(-9.09);
        $line->setDebitAmount(-9.49);
        $line->setLineNumber(-1);
        $line->setSettlementAmount(-4.49);
        $line->setTaxExemptionReason("");

        $xml = $line->createXmlNode($lineNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($line->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($line->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($line->getErrorRegistor()->getLibXmlError());
    }
}