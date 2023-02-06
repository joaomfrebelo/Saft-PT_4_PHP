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

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\TransactionID;
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;
use Rebelo\SaftPt\Validate\DocTotalCalc;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class PaymentTest extends TestCase
{

    use TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(Payment::class);
        $this->assertTrue(true);
    }

    /**
     * @throws AuditFileException
*@author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $payment = new Payment(new ErrorRegister());
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertNull($payment->getPeriod());
        $this->assertNull($payment->getTransactionID(false));
        $this->assertNull($payment->getDescription());
        $this->assertNull($payment->getSystemID());
        $this->assertSame(0, \count($payment->getPaymentMethod()));
        $this->assertSame(0, \count($payment->getWithholdingTax()));

        $this->assertFalse($payment->issetATCUD());
        $this->assertFalse($payment->issetCustomerID());
        $this->assertFalse($payment->issetDocumentTotals());
        $this->assertFalse($payment->issetPaymentRefNo());
        $this->assertFalse($payment->issetPaymentType());
        $this->assertFalse($payment->issetSourceID());
        $this->assertFalse($payment->issetSystemEntryDate());
        $this->assertFalse($payment->issetTransactionDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPaymentRefNo(): void
    {
        $payment = new Payment(new ErrorRegister());
        $refNo   = "FT FT/1";
        $this->assertTrue($payment->setPaymentRefNo($refNo));
        $this->assertSame($refNo, $payment->getPaymentRefNo());
        $this->assertTrue($payment->issetPaymentRefNo());

        $wrong = "ORCA /1";
        $this->assertFalse($payment->setPaymentRefNo($wrong));
        $this->assertSame($wrong, $payment->getPaymentRefNo());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $wrong2 = "FTFT/1";
        $payment->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payment->setPaymentRefNo($wrong2));
        $this->assertSame($wrong2, $payment->getPaymentRefNo());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $wrong3 = \str_pad($refNo, 61, "1", STR_PAD_RIGHT);
        $payment->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payment->setPaymentRefNo($wrong3));
        $this->assertSame($wrong3, $payment->getPaymentRefNo());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $payment->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payment->setPaymentRefNo(""));
        $this->assertSame("", $payment->getPaymentRefNo());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetAtcud(): void
    {
        $payment = new Payment(new ErrorRegister());
        $atcud   = "ATCUD";
        $this->assertTrue($payment->setATCUD($atcud));
        $this->assertSame($atcud, $payment->getATCUD());
        $this->assertTrue($payment->issetATCUD());

        $wrong = \str_pad($atcud, 101, "A");
        $this->assertFalse($payment->setATCUD($wrong));
        $this->assertSame($wrong, $payment->getATCUD());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $payment->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payment->setATCUD(""));
        $this->assertSame("", $payment->getATCUD());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPeriod(): void
    {
        $payment = new Payment(new ErrorRegister());
        $period  = 9;
        $this->assertTrue($payment->setPeriod($period));
        $this->assertSame($period, $payment->getPeriod());

        $wrong = 0;
        $this->assertFalse($payment->setPeriod($wrong));
        $this->assertSame($wrong, $payment->getPeriod());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $wrong2 = 13;
        $payment->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($payment->setPeriod($wrong2));
        $this->assertSame($wrong2, $payment->getPeriod());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($payment->setPeriod(null));
        $this->assertNull($payment->getPeriod());
        $payment->setPeriod($period);
        $this->assertSame($period, $payment->getPeriod());
    }

    /**
     * @throws AuditFileException
     * @author João Rebelo
     * @test
     */
    public function testSetGetTransactionID(): void
    {
        $payment = new Payment(new ErrorRegister());
        $this->assertInstanceOf(
            TransactionID::class, $payment->getTransactionID()
        );
        $payment->setTransactionIDAsNull();
        $this->assertNull($payment->getTransactionID(false));
    }

    /**
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testSetGetTransactionDate(): void
    {
        $payment = new Payment(new ErrorRegister());
        $date    = new RDate();
        $payment->setTransactionDate($date);
        $this->assertSame($date, $payment->getTransactionDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetPaymentType(): void
    {
        $payment = new Payment(new ErrorRegister());
        $type    = new PaymentType(PaymentType::RC);
        $payment->setPaymentType($type);
        $this->assertSame($type->get(), $payment->getPaymentType()->get());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetDescription(): void
    {
        $payment = new Payment(new ErrorRegister());
        $desc    = "Descriptin of payment";
        $this->assertTrue($payment->setDescription($desc));
        $this->assertSame($desc, $payment->getDescription());

        $this->assertTrue($payment->setDescription(\str_pad($desc, 299, "A")));
        $this->assertSame(200, \strlen($payment->getDescription()));

        $this->assertFalse($payment->setDescription(""));
        $this->assertSame("", $payment->getDescription());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $this->assertTrue($payment->setDescription(null));
        $this->assertNull($payment->getDescription());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSystmeId(): void
    {
        $payment  = new Payment(new ErrorRegister());
        $systemID = "System ID";
        $this->assertTrue($payment->setSystemID($systemID));
        $this->assertSame($systemID, $payment->getSystemID());

        $this->assertTrue($payment->setSystemID(\str_pad($systemID, 99, "A")));
        $this->assertSame(60, \strlen($payment->getSystemID()));

        $this->assertFalse($payment->setSystemID(""));
        $this->assertSame("", $payment->getSystemID());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());

        $payment->setSystemID(null);
        $this->assertNull($payment->getSystemID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetDocumentStatus(): void
    {
        $payment = new Payment(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentStatus::class, $payment->getDocumentStatus()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testPymentMethod(): void
    {
        $payment = new Payment(new ErrorRegister());
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $method = $payment->addPaymentMethod();
            $method->setPaymentAmount($n + 0.99);
            $this->assertSame(
                $n + 0.99, $payment->getPaymentMethod()[$n]->getPaymentAmount()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetSourceId(): void
    {
        $payment  = new Payment(new ErrorRegister());
        $sourceID = "Source ID";
        $this->assertTrue($payment->setSourceID($sourceID));
        $this->assertSame($sourceID, $payment->getSourceID());
        $this->assertTrue($payment->issetSourceID());

        $this->assertTrue($payment->setSourceID(\str_pad($sourceID, 99, "A")));
        $this->assertSame(30, \strlen($payment->getSourceID()));

        $this->assertFalse($payment->setSourceID(""));
        $this->assertSame("", $payment->getSourceID());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testSetGetSystemEntryDate(): void
    {
        $payment = new Payment(new ErrorRegister());
        $date    = new RDate();
        $payment->setSystemEntryDate($date);
        $this->assertSame($date, $payment->getSystemEntryDate());
        $this->assertTrue($payment->issetSystemEntryDate());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testLine(): void
    {
        $payment = new Payment(new ErrorRegister());
        $nMax    = 9;
        for ($n = 1; $n < $nMax; $n++) {
            $payment->addLine();
            $this->assertSame(
                $n, $payment->getLine()[$n - 1]->getLineNumber()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetDocumentTotals(): void
    {
        $payment = new Payment(new ErrorRegister());
        $this->assertInstanceOf(
            DocumentTotals::class, $payment->getDocumentTotals()
        );
        $this->assertTrue($payment->issetDocumentTotals());
    }

    /**
     * @throws AuditFileException
     * @author João Rebelo
     * @test
     */
    public function testWithholdingTax(): void
    {
        $payment = new Payment(new ErrorRegister());
        $nMax    = 9;
        for ($n = 0; $n < $nMax; $n++) {
            $tax = $payment->addWithholdingTax();
            $tax->setWithholdingTaxAmount($n + 0.99);
            $this->assertSame(
                $n + 0.99,
                $payment->getWithholdingTax()[$n]->getWithholdingTaxAmount()
            );
        }
    }

    /**
     * Reads all Payments  from the Demo SAFT in Test\Ressources
     * and parse then to Payment class, after that generate a xml from the
     * Payment class and test if the xml strings are equal
     *
     * @throws AuditFileException
     * @throws DateFormatException
     * @throws DateParseException
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
            /* @var $paymentXml \SimpleXMLElement */
            $paymentXml = $paymentsStack[$i];
            $payment    = new Payment(new ErrorRegister());
            $payment->parseXmlNode($paymentXml);

            $xmlRootNode   = (new AuditFile())->createRootElement();
            $sourceDocNode = $xmlRootNode->addChild(SourceDocuments::N_SOURCEDOCUMENTS);
            $paymentsNode  = $sourceDocNode->addChild(Payments::N_PAYMENTS);

            $xml = $payment->createXmlNode($paymentsNode);

            try {
                $assertXml = $this->xmlIsEqual($paymentXml, $xml);

                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Payment index '%s' with mwssage '%s'",
                        $i + 1, $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Payment index '%s' with mwssage '%s'",
                        $i + 1, $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($payment->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($payment->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($payment->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $paymentNode = new \SimpleXMLElement(
            "<".Payments::N_PAYMENTS."></".Payments::N_PAYMENTS.">"
        );
        $payment     = new Payment(new ErrorRegister());
        $xml         = $payment->createXmlNode($paymentNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($payment->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($payment->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($payment->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $paymentNode = new \SimpleXMLElement(
            "<".Payments::N_PAYMENTS."></".Payments::N_PAYMENTS.">"
        );
        $payment     = new Payment(new ErrorRegister());
        $payment->setATCUD("");
        $payment->setCustomerID("");
        $payment->setDescription("");
        $payment->setPaymentRefNo("");
        $payment->setPeriod(-1);
        $payment->setSourceID("");
        $payment->setSystemID("");

        $xml = $payment->createXmlNode($paymentNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($payment->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($payment->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($payment->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testDocTotalcal(): void
    {
        $payment = new Payment(new ErrorRegister());
        $payment->setDocTotalcal(new DocTotalCalc());
        $this->assertInstanceOf(
            DocTotalCalc::class,
            $payment->getDocTotalcal()
        );
    }
}
