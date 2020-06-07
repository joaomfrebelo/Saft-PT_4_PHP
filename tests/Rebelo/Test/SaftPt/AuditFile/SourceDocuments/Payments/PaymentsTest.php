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
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class PaymentsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Payments::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $payments = new Payments();
        $this->assertInstanceOf(Payments::class, $payments);
        $this->assertSame(0, \count($payments->getPayment()));

        $entries = 9;
        $payments->setNumberOfEntries($entries);
        $this->assertSame($entries, $payments->getNumberOfEntries());
        try {
            $payments->setNumberOfEntries(-1);
            $this->fail("Set a negative number of entries should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $debit = 4.49;
        $payments->setTotalDebit($debit);
        $this->assertSame($debit, $payments->getTotalDebit());
        try {
            $payments->setTotalDebit(-0.01);
            $this->fail("Set a negative debit should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $credit = 9.49;
        $payments->setTotalCredit($credit);
        $this->assertSame($credit, $payments->getTotalCredit());
        try {
            $payments->setTotalCredit(-0.01);
            $this->fail("Set a negative credit should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testPayment()
    {
        $payments = new Payments();
        $nMax     = 9;
        for ($n = 1; $n < $nMax; $n++) {
            $pay   = new Payment();
            $pay->setATCUD(\strval($n));
            $index = $payments->addToPayment($pay);
            $this->assertSame($index, $n - 1);
            $this->assertSame(
                \strval($n), $payments->getPayment()[$index]->getATCUD()
            );
        }
        $unset = 2;
        $payments->unsetPayment($unset);
        $this->assertSame($nMax - 2, \count($payments->getPayment()));
        $this->assertFalse($payments->issetPayment($unset));
    }

    /**
     * Reads all Payments  from the Demo SAFT in Test\Ressources
     * and parse them to Payment class, after tahr generate a xml from the
     * Payment class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        /* @var $paymentsXml \SimpleXMLElement */
        $paymentsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{Payments::N_PAYMENTS};

        if ($paymentsXml->count() === 0) {
            $this->fail("No Payment in XML");
        }

        $payments = new Payments();
        $payments->parseXmlNode($paymentsXml);

        $xmlRootNode   = new \SimpleXMLElement(
            '<AuditFile xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.04_01 .\SAFTPT1.04_01.xsd" '.
            'xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.04_01"></AuditFile>'
        );
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $payments->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($paymentsXml, $xml);

            $this->assertTrue(
                $assertXml, \sprintf("Fail on Payments '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(
                \sprintf("Fail on Payment '%s'", $e->getMessage()));
        }
    }
}