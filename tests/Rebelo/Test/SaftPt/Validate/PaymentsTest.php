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

namespace Rebelo\Test\SaftPt\Validate;

use Rebelo\SaftPt\Validate\Payments;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\Decimal\UDecimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\Validate\DocTotalCalc;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;

/**
 * Class SalesInvoiceTest
 *
 * @author João Rebelo
 */
class PaymentsTest extends \Rebelo\Test\SaftPt\Validate\APaymentsBase
{

    protected function setUp(): void
    {
        $this->paymentsFactory();
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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
     * @return void
     */
    public function testTotalDebit(): void
    {
        $debit     = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalDebit($debit);

        $this->payments->setDebit(
            new UDecimal($debit, Payments::CALC_PRECISION)
        );

        $this->payments->totalDebit();

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalDebitGreaterDeltaZero(): void
    {
        $debit     = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalDebit($debit);

        $this->payments->setDebit(
            (new UDecimal($debit, Payments::CALC_PRECISION))->plus(0.09)
        );

        $this->payments->totalDebit();

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalDebitLowerDeltaZero(): void
    {
        $debit     = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalDebit($debit);

        $this->payments->setDebit(
            (new UDecimal($debit, Payments::CALC_PRECISION))->subtract(0.09)
        );

        $this->payments->totalDebit();

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalCredit(): void
    {
        $credit    = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalCredit($credit);

        $this->payments->setCredit(
            new UDecimal($credit, Payments::CALC_PRECISION)
        );

        $this->payments->totalCredit();

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalCreditGreaterDeltaZero(): void
    {
        $credit    = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalCredit($credit);

        $this->payments->setCredit(
            (new UDecimal($credit, Payments::CALC_PRECISION))->plus(0.09)
        );

        $this->payments->totalCredit();

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalCreditLowerDeltaZero(): void
    {
        $credit    = 909.99;
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()->getPayments()
            ->setTotalCredit($credit);

        $this->payments->setCredit(
            (new UDecimal($credit, Payments::CALC_PRECISION))->subtract(0.09)
        );

        $this->payments->totalCredit();

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo@author João Rebelo
     * @depends testPayment
     * @depends testNumberOfEntries
     * @depends testTotalDebit
     * @depends testTotalCredit
     * @depends testLines
     * @test
     * @return void
     */
    public function testValidate(): void
    {
        $xml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($xml === false) {
            $this->fail(\sprintf("Failling load file '%s'", SAFT_DEMO_PATH));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $this->payments->setAuditFile($auditFile);
        $this->payments->setDeltaLine(0.005);
        $this->payments->setDeltaCurrency(0.005);
        $this->payments->setDeltaTable(0.005);
        $this->payments->setDeltaTotalDoc(0.005);

        $valide = $this->payments->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateNoPayments(): void
    {

        $auditFile = new AuditFile();
        $this->payments->setAuditFile($auditFile);

        $valide = $this->payments->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateNoPaymentCreditNotZero(): void
    {

        $auditFile = new AuditFile();
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payments->setTotalCredit(999.09);
        $payments->setTotalDebit(0.0);
        $payments->setNumberOfEntries(0);

        $this->payments->setAuditFile($auditFile);

        $valide = $this->payments->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateNoPaymentDebitNotZero(): void
    {

        $auditFile = new AuditFile();
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payments->setTotalCredit(0.0);
        $payments->setTotalDebit(999.0);
        $payments->setNumberOfEntries(0);

        $this->payments->setAuditFile($auditFile);

        $valide = $this->payments->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPayment(): void
    {
        $now       = new RDate();
        $this->iniPaymentsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N());
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, Payments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, Payments::CALC_PRECISION);

        foreach ($payment->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->payments->payment($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testpaymentWrongCustomerID(): void
    {
        $now       = new RDate();
        $this->iniPaymentsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N());
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, Payments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, Payments::CALC_PRECISION);

        foreach ($payment->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID()."A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPaymentWrongTotals(): void
    {
        $now       = new RDate();
        $this->iniPaymentsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N());
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, Payments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, Payments::CALC_PRECISION);

        foreach ($payment->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf() + 1);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getDocumentTotals()->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPaymentRefNoPaymentRefNo(): void
    {
        $now       = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        //$payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPaymentRefNoPaymentType(): void
    {
        $now       = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        //$payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPaymentRefNoTransactionDate(): void
    {
        $now       = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        //$payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testPaymentRefNoInvoiceSystemEntryDate(): void
    {
        $now       = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        //$payment->setSystemEntryDate(clone $now);

        $this->payments->payment($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @depends testPayment
     * @test
     * @return void
     */
    public function testNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $payments = $auditFile->getSourceDocuments()->getPayments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $payments->addPayment();
        }

        $payments->setNumberOfEntries($nMax);

        $this->payments->numberOfEntries();
        $this->assertTrue($this->payments->isValid());
        $this->assertSame(
            $nMax, $payments->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWrongNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $payments = $auditFile->getSourceDocuments()->getPayments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $payments->addPayment();
        }

        $payments->setNumberOfEntries($nMax + 1);

        $this->payments->numberOfEntries();
        $this->assertFalse($this->payments->isValid());
        $this->assertSame(
            $nMax, $payments->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payments->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatus(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(new PaymentStatus(PaymentStatus::N));
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(new SourcePayment(SourcePayment::P));
        $docStatus->setSourceID("Rebelo");

        $this->payments->documentStatus($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusNotDefined(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        $this->payments->documentStatus($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_PAYMENTSTATUS,
            \array_key_first($payment->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusStatusDateEalier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(new PaymentStatus(PaymentStatus::N));
        $docStatus->setPaymentStatusDate(
            RDate::parse(RDate::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourcePayment(new SourcePayment(SourcePayment::P));
        $docStatus->setSourceID("Rebelo");

        $this->payments->documentStatus($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_PAYMENTSTATUSDATE,
            \array_key_first($payment->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusCancel(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(new PaymentStatus(PaymentStatus::A));
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(new SourcePayment(SourcePayment::P));
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        $this->payments->documentStatus($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(new PaymentStatus(PaymentStatus::A));
        $docStatus->setPaymentStatusDate(new RDate());
        $docStatus->setSourcePayment(new SourcePayment(SourcePayment::P));
        $docStatus->setSourceID("Rebelo");

        $this->payments->documentStatus($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($payment->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerId(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile  = $this->payments->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setCustomerID($customerID);

        $this->payments->customerId($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdCustomerNotExist(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setCustomerID("A999");

        $this->payments->customerId($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            Payment::N_CUSTOMERID, \array_key_first($payment->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdCustomerIsNotSet(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");

        $this->payments->customerId($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            Payment::N_CUSTOMERID, \array_key_first($payment->getError())
        );
    }

    /**
     * Init variables
     * @return void
     */
    public function iniPaymentsForLineTest(): void
    {
        $this->payments->setNetTotal(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setGrossTotal(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setTaxPayable(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setDocCredit(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setDocDebit(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setCredit(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );

        $this->payments->setDebit(
            new UDecimal(0.0, Payments::CALC_PRECISION)
        );
    }

    /**
     *
     * @param Payment $payment
     * @return void
     */
    public function iniPaymentLinesForLinesTest(Payment $payment): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->payments->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        for ($n = 1; $n <= 9; $n++) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
            $line     = $payment->addLine();
            $sourceId = $line->addSourceDocumentID();
            $sourceId->setDescription(\sprintf("Description of line '%s'", $n));
            $sourceId->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            $sourceId->setOriginatingON(\sprintf("FT FT/%s", $n));

            $line->setSettlementAmount(0.0);
            $tax = $line->getTax();
            $tax->setTaxCode($taxTableEntry->getTaxCode());
            $tax->setTaxCountryRegion($taxTableEntry->getTaxCountryRegion());
            $tax->setTaxPercentage($taxTableEntry->getTaxPercentage());
            $tax->setTaxType($taxTableEntry->getTaxType());

            $line->setCreditAmount(10 * $n);
        }
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->payments->setContinuesLines(true);
        $this->iniPaymentsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N());
        $this->iniPaymentLinesForLinesTest($payment);

        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line[] */
        $lineStack = $payment->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        $this->payments->lines($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesRepetedLineNumber(): void
    {
        $now = new RDate();
        $this->payments->setContinuesLines(false);
        $this->iniPaymentsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setDocTotalcal(new DocTotalCalc());

        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N());

        $this->iniPaymentLinesForLinesTest($payment);

        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line[] */
        $lineStack = $payment->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        $this->payments->lines($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoCreditAndDebitSetted(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N());

        /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line */
        $line     = $payment->addLine();
        $sourceID = $line->addSourceDocumentID();
        $sourceID->setDescription("Desc of line '1'");
        $sourceID->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $sourceID->setOriginatingON("FT FT/1");

        $this->payments->lines($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @depends testSourceDocumentID
     * @test
     * @return void
     */
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());
        $payment->setDocTotalcal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N());
        $this->iniPaymentLinesForLinesTest($payment);

        // add a line diferent of init lines
        $n    = \count($payment->getLine());
        $line = $payment->addLine();
        $line->setCreditAmount(999.99);

        $sourceId = $line->addSourceDocumentID();
        $sourceId->setDescription(\sprintf("Description of line '%s'", $n));
        $sourceId->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $sourceId->setOriginatingON(\sprintf("FT FT/%s", $n));

        $this->payments->lines($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSourceDocumentID(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG());

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($source->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testMultipleSourceDocumentID(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line    = $payment->addLine();
        $srStack = array();
        for ($n = 1; $n <= 9; $n++) {
            $source = $line->addSourceDocumentID();
            $source->setOriginatingON(\sprintf("FT FT/%s", $n));
            $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            if ($n % 2 === 0) { // Test source with an without description
                $source->setDescription("Source description");
            }
            $srStack[$n] = $source;
        }

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        foreach ($srStack as $source) {
            $this->assertEmpty($source->getError());
        }
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSourceDocumentIDRepeatedOriginatingOn(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line    = $payment->addLine();
        $srStack = array();
        for ($n = 1; $n <= 2; $n++) {
            $source = $line->addSourceDocumentID();
            $source->setOriginatingON("FT FT/1");
            $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            if ($n % 2 === 0) { // Test source with an without description
                $source->setDescription("Source description");
            }
            $srStack[$n] = $source;
        }

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        foreach ($srStack as $source) {
            $this->assertEmpty($source->getError());
        }
        $this->assertNotEmpty($srStack[2]->getWarning());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testNoSourceDocumentID(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSourceDocumentIDNoDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG());

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        //$source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($source->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSourceDocumentIDOriginDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG());

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($source->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSourceDocumentIDOriginDocNotValid(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG());

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT 1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($source->getError());
        $this->assertNotEmpty($source->getWarning());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxNotSettedOnNonCashVatScheme(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();

        $this->payments->tax($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxNotSettedOnCashVatScheme(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RC());

        $line = $payment->addLine();

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTypeNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTypeIvaPercentageNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxAmount(999.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxAmountZeroExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxAmountZeroExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxPercentageZeroExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeIseExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        // The percentage is no set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeIseExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is no set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeIsePercentageNotZero(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTableTaxEmpty(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxWrongTableTaxEntry(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeNoTaxCode(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeNoTaxCoountryRegion(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeNotExistInTable(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxCodeDateExpierd(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTaxExpirationDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTaxExpirationDateNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxTaxIS(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::OUT());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS());
        $taxTableEntry->setDescription("Tax description");

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $line = $payment->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->payments->tax($line, $payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsDocumentTotalsNotSetted(): void
    {
        $auditFile = $this->payments->getAuditFile();

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongGross(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 122.99;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedGross(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal(123.00, 4));

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedGrossDelta(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross - 0.01, 4));

        $this->payments->setDeltaTotalDoc(0.01);

        $this->payments->totals($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedNet(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->payments->setNetTotal(new UDecimal($net - $delta, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedNetDelta(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->payments->setNetTotal(new UDecimal($net - $delta, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $this->payments->setDeltaTotalDoc($delta);

        $this->payments->totals($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedTaxPayable(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedTaxPayableDelta(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $this->payments->setDeltaTotalDoc($delta);

        $this->payments->totals($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedCurrency(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals   = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $payment->setDocTotalcal($docTotalcal);

        $this->payments->totals($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongCalculatedCurrencyDelta(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals   = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $payment->setDocTotalcal($docTotalcal);

        $this->payments->setDeltaCurrency($delta);
        $this->payments->totals($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotals(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $rate      = 0.5;

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $totals   = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount($gross / $rate);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->payments->setNetTotal(new UDecimal($net, 4));
        $this->payments->setTaxPayable(new UDecimal($tax, 4));
        $this->payments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $payment->setDocTotalcal($docTotalcal);

        $this->payments->totals($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateNoHeader(): void
    {
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments  = $auditFile->getSourceDocuments()->getPayments();
        $payment   = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->setLastDocDate((clone $now)->addDays(1));
        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->setLastDocDate(clone $now);
        $this->payments->setLastSystemEntryDate((clone $now)->addSeconds(1));
        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDateAllDatesEqual(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /* @var $payments \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->setLastDocDate(clone $now);
        $this->payments->setLastSystemEntryDate(clone $now);
        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentDateAndSyEntryDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P());

        $this->payments->setLastDocDate((clone $now)->addDays(-1));
        $this->payments->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        $this->payments->paymentDateAndSystemEntryDate($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethod(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethodWithWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross - $withholdingTax->getWithholdingTaxAmount());
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWithoutPaymentMethod(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $this->payments->paymentMethod($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
        $this->assertNotEmpty($payment->getWarning());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testMultiplePaymentMethod(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $payment->addPaymentMethod();
            $payMeth->setPaymentAmount($gross / $nMax);
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU());
        }

        $this->payments->paymentMethod($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethodWithoutAmout(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        //$payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethodWithoutDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        //$payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethodGrossDiffPayMeth(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross - 1.00);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testPaymentMethodGrossDiffPayMethWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->payments->paymentMethod($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $this->payments->withholdingTax($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testMultipleWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        for ($n = 0; $n <= 0; $n++) {
            $withholdingTax = $payment->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(10.0);
        }

        $this->payments->withholdingTax($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWithholdingTaxWithoutAmount(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        //$withholdingTax = 
        $payment->addWithholdingTax();
        //$withholdingTax->setWithholdingTaxAmount(10.0);

        $this->payments->withholdingTax($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWithholdingTaxGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);


        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross + 0.10);

        $this->payments->withholdingTax($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testMultipleWithholdingTaxGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $withholdingTax = $payment->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(($gross / $nMax) + 0.1);
        }

        $this->payments->withholdingTax($payment);

        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWithholdingTaxGreaterThanHalfGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /* @var $payment \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(($gross / 2) + 0.1);

        $this->payments->withholdingTax($payment);

        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
        $this->assertNotEmpty($payment->getWarning());
    }
}