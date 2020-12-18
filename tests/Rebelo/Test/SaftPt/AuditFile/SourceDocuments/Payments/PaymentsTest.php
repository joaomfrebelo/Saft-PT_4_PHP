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
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class PaymentsTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(Payments::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $payments = new Payments(new ErrorRegister());
        $this->assertInstanceOf(Payments::class, $payments);
        $this->assertSame(0, \count($payments->getPayment()));

        $this->assertFalse($payments->issetNumberOfEntries());
        $this->assertFalse($payments->issetTotalCredit());
        $this->assertFalse($payments->issetTotalDebit());
        $this->assertNull($payments->getDocTableTotalCalc());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfEntries(): void
    {
        $payments = new Payments(new ErrorRegister());
        $entries  = [0, 999];
        foreach ($entries as $num) {
            $this->assertTrue($payments->setNumberOfEntries($num));
            $this->assertSame($num, $payments->getNumberOfEntries());
            $this->assertTrue($payments->issetNumberOfEntries());
        }

        $wrong = -1;
        $this->assertFalse($payments->setNumberOfEntries($wrong));
        $this->assertSame($wrong, $payments->getNumberOfEntries());
        $this->assertNotEmpty($payments->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalDebit(): void
    {
        $payments   = new Payments(new ErrorRegister());
        $debitStack = [0.0, 9.99];
        foreach ($debitStack as $debit) {
            $this->assertTrue($payments->setTotalDebit($debit));
            $this->assertSame($debit, $payments->getTotalDebit());
            $this->assertTrue($payments->issetTotalDebit());
        }

        $wrong = -19.9;
        $this->assertFalse($payments->setTotalDebit($wrong));
        $this->assertSame($wrong, $payments->getTotalDebit());
        $this->assertNotEmpty($payments->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalCredit(): void
    {
        $payments    = new Payments(new ErrorRegister());
        $creditStack = [0.0, 9.99];
        foreach ($creditStack as $credit) {
            $this->assertTrue($payments->setTotalCredit($credit));
            $this->assertSame($credit, $payments->getTotalCredit());
            $this->assertTrue($payments->issetTotalCredit());
        }

        $wrong = -19.9;
        $this->assertFalse($payments->setTotalCredit($wrong));
        $this->assertSame($wrong, $payments->getTotalCredit());
        $this->assertNotEmpty($payments->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testPayment(): void
    {
        $payments = new Payments(new ErrorRegister());
        $nMax     = 9;
        for ($n = 1; $n < $nMax; $n++) {
            $pay = $payments->addPayment();
            $pay->setATCUD(\strval($n));
            $this->assertSame(
                \strval($n), $payments->getPayment()[$n - 1]->getATCUD()
            );
        }
    }

    /**
     * Reads all Payments  from the Demo SAFT in Test\Ressources
     * and parse then to Payment class, after that generate a xml from the
     * Payment class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        /* @var $paymentsXml \SimpleXMLElement */
        $paymentsXml = $saftDemoXml
            ->{SourceDocuments::N_SOURCEDOCUMENTS}
            ->{Payments::N_PAYMENTS};

        if ($paymentsXml->count() === 0) {
            $this->fail("No Payment in XML");
        }

        $payments = new Payments(new ErrorRegister());
        $payments->parseXmlNode($paymentsXml);

        $xmlRootNode   = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
        $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);

        $xml = $payments->createXmlNode($sourceDocNode);

        try {
            $assertXml = $this->xmlIsEqual($paymentsXml, $xml);

            $this->assertTrue(
                $assertXml, \sprintf("Fail on Payments '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(
                \sprintf("Fail on Payment '%s'", $e->getMessage())
            );
        }

        $this->assertEmpty($payments->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payments->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($payments->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $paymentsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $payments     = new Payments(new ErrorRegister());
        $xml          = $payments->createXmlNode($paymentsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($payments->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payments->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($payments->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $paymentsNode = new \SimpleXMLElement(
            "<".SourceDocuments::N_SOURCEDOCUMENTS."></".SourceDocuments::N_SOURCEDOCUMENTS.">"
        );
        $payments     = new Payments(new ErrorRegister());
        $payments->setNumberOfEntries(-1);
        $payments->setTotalCredit(-0.99);
        $payments->setTotalDebit(-0.95);

        $xml = $payments->createXmlNode($paymentsNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($payments->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($payments->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($payments->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocTableTotalCalc(): void
    {
        $payments = new Payments(new ErrorRegister());
        $payments->setDocTableTotalCalc(new \Rebelo\SaftPt\Validate\DocTableTotalCalc);
        $this->assertInstanceOf(
            \Rebelo\SaftPt\Validate\DocTableTotalCalc::class,
            $payments->getDocTableTotalCalc()
        );
    }
    
     /**
     * @author João Rebelo
     * @test
     */
    public function testGetOrder(): void
    {
        $payments = new Payments(new ErrorRegister());
        $payNo      = array(
            "RC RC/1",
            "PA PA/4",
            "RC RC/5",
            "RC RC/2",
            "RC RC/9",
            "RC RC/4",
            "RC RC/3",
            "RC RC/10",
            "PA PA/3",
            "PA PA/2",
            "PA PA/1",
            "RC B/3",
            "RC B/1",
            "RC B/2",
        );
        foreach ($payNo as $no) {
            $payments->addPayment()->setPaymentRefNo($no);
        }

        $order = $payments->getOrder();
        $this->assertSame(array("PA", "RC"), \array_keys($order));
        $this->assertSame(array("PA"), \array_keys($order["PA"]));
        $this->assertSame(array("B", "RC"), \array_keys($order["RC"]));
        $this->assertSame(
            array(1, 2, 3, 4, 5, 9, 10), \array_keys($order["RC"]["RC"])
        );
        $this->assertSame(array(1, 2, 3), \array_keys($order["RC"]["B"]));
        $this->assertSame(array(1, 2, 3, 4), \array_keys($order["PA"]["PA"]));

        foreach ($order as $type => $serieStack) {
            foreach ($serieStack as $serie => $noSatck) {
                foreach ($noSatck as $no => $invoice) {
                    /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
                    $this->assertSame(
                        \sprintf("%s %s/%s", $type, $serie, $no),
                        $invoice->getPaymentRefNo()
                    );
                }
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDuplicateNumber(): void
    {
        $payments = new Payments(new ErrorRegister());
        $payNo      = array(
            "RC RC/1",
            "PA PA/4",
            "RC RC/1",
            "RC RC/2",
            "RC RC/9",
            "RC RC/4",
            "RC RC/3",
            "RC RC/10",
            "PA PA/3",
            "PA PA/2",
            "PA PA/1",
            "RC B/3",
            "RC B/1",
            "RC B/2",
        );
        foreach ($payNo as $no) {
            $payments->addPayment()->setPaymentRefNo($no);
        }

        $payments->getOrder();
        $this->assertNotEmpty($payments->getErrorRegistor()->getValidationErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNoNumber(): void
    {
        $payments = new Payments(new ErrorRegister());
        $payNo      = array(
            "RC RC/1",
            "RC B/2"
        );
        foreach ($payNo as $no) {
            $payments->addPayment()->setPaymentRefNo($no);
        }
        $payments->addPayment();
        $payments->getOrder();
        $this->assertNotEmpty($payments->getErrorRegistor()->getValidationErrors());
    }
    
}
