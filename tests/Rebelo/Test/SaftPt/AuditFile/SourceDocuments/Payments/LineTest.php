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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID;
use Rebelo\SaftPt\AuditFile\AuditFileException;
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
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $line = new Line();
        $this->assertInstanceOf(Line::class, $line);
        $this->assertNull($line->getCreditAmount());
        $this->assertNull($line->getDebitAmount());
        $this->assertNull($line->getSettlementAmount());
        $this->assertNull($line->getTax());
        $this->assertNull($line->getTaxExemptionCode());
        $this->assertNull($line->getTaxExemptionReason());
        $this->assertSame(0, \count($line->getSourceDocumentID()));
    }

    /**
     *
     */
    public function testSetGetLineNumber()
    {
        $line = new Line();
        $num  = 1;
        $line->setLineNumber(1);
        $this->assertSame($num, $line->getLineNumber());
    }

    /**
     *
     */
    public function testSetSourceDocumentID()
    {
        $line = new Line();
        $nMax = 9;

        for ($n = 0; $n < $nMax; $n++) {
            $source = new SourceDocumentID();
            $source->setOriginatingON(\strval($n));
            $index  = $line->addToSourceDocumentID($source);
            $this->assertSame($n, $index);
            /* @var $stack \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourceDocumentID */
            $stack  = $line->getSourceDocumentID();
            $this->assertSame(\strval($n), $stack[$index]->getOriginatingON());
        }

        $unset = 2;
        $line->unsetSourceDocumentID($unset);
        $this->assertSame($nMax - 1, \count($line->getSourceDocumentID()));
        $this->assertFalse($line->issetSourceDocumentID($unset));
    }

    /**
     *
     */
    public function testSetGetTax()
    {
        $line = new Line();
        $line->setTax(new Tax());
        $this->assertInstanceOf(Tax::class, $line->getTax());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionReason()
    {
        $line   = new Line();
        $reason = "Tax Exception Reason";
        $line->setTaxExemptionReason($reason);
        $this->assertSame($reason, $line->getTaxExemptionReason());
        $line->setTaxExemptionReason(\str_pad($reason, 99, "9"));
        $this->assertSame(60, \strlen($line->getTaxExemptionReason()));
        try {
            $line->setTaxExemptionReason("AAAAA");
            $this->fail("TaxExemptionReason with length less than 6 must throw "
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $line->setTaxExemptionReason(null);
        $this->assertNull($line->getTaxExemptionReason());
    }

    /**
     *
     */
    public function testSetGetTaxExemptionCode()
    {
        $line = new Line();
        $line->setTaxExemptionCode(new TaxExemptionCode(TaxExemptionCode::M01));
        $this->assertInstanceOf(TaxExemptionCode::class,
            $line->getTaxExemptionCode());
        $line->setTaxExemptionCode(null);
        $this->assertNull($line->getTaxExemptionCode());
    }

    /**
     *
     */
    public function testSettlementAmount()
    {
        $line = new Line();
        $sett = 9.09;
        $line->setSettlementAmount($sett);
        $this->assertSame($sett, $line->getSettlementAmount());
        $line->setSettlementAmount(null);
        $this->assertNull($line->getSettlementAmount());
    }

    public function testSetGetDebitCredit()
    {
        $line = new Line();
        $deb  = 9.09;
        $cre  = 19.49;

        $line->setDebitAmount($deb);
        $this->assertSame($deb, $line->getDebitAmount());
        $line->setDebitAmount(null);
        $this->assertNull($line->getDebitAmount());

        $line->setCreditAmount($cre);
        $this->assertSame($cre, $line->getCreditAmount());
        $line->setCreditAmount(null);
        $this->assertNull($line->getCreditAmount());

        try {
            $line->setDebitAmount($deb);
            $line->setCreditAmount($cre);
            $this->fail("When set CreditAmount without DebiAmout be null should throw"
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $line->setDebitAmount(null);
        $line->setCreditAmount(null);

        try {
            $line->setCreditAmount($cre);
            $line->setDebitAmount($deb);
            $this->fail("When set DebiAmout without  CreditAmount be null should throw"
                ."Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     * Reads all Payments's lines from the Demo SAFT in Test\Ressources
     * and parse them to Line class, after tahr generate a xml from the
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
            $paymentXml = $paymentsStack[$i];
            $lineStack  = $paymentXml->{Line::N_LINE};

            if ($lineStack->count() === 0) {
                $this->fail("No lines in Payment");
            }

            for ($l = 0; $l < $lineStack->count(); $l++) {
                /* @var $lineXml \SimpleXMLElement */
                $lineXml = $lineStack[$l];
                $line    = new Line();
                $line->parseXmlNode($lineXml);


                $xmlRootNode   = new \SimpleXMLElement(
                    '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
                    'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
                    'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
                );
                $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
                $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);
                $payNode       = $paymentsNode->addChild(Payment::N_PAYMENT);

                $xml = $line->createXmlNode($payNode);

                try {
                    $assertXml = $this->xmlIsEqual($lineXml, $xml);
                    $this->assertTrue($assertXml,
                        \sprintf("Fail on Payment '%s' Line '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $lineXml->{Line::N_LINENUMBER}, $assertXml)
                    );
                } catch (\Exception | \Error $e) {
                    $this->fail(\sprintf("Fail on Document '%s' Line '%s' with error '%s'",
                            $paymentXml->{Payment::N_PAYMENTREFNO},
                            $lineXml->{Line::N_LINENUMBER}, $e->getMessage()));
                }
            }
        }
    }
}