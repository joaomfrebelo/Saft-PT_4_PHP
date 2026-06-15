<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
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

namespace Rebelo\SaftPt\Validate;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\SourcePayment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\Commune;

/**
 * Class SalesInvoiceTest
 *
 * @author João Rebelo
 */
class PaymentsTest extends APaymentsBase
{

    protected function setUp(): void
    {
        $this->paymentsFactory();
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(Payments::class))->testReflection(Payments::class);
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalDebit(): void
    {
        $debit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->payments->setDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->payments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalDebitGreaterDeltaZero(): void
    {
        $debit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->payments->setDebit($debit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->payments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalDebitLowerDeltaZero(): void
    {
        $debit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->payments->setDebit($debit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->payments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCredit(): void
    {
        $credit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->payments->setCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->payments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCreditGreaterDeltaZero(): void
    {
        $credit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->payments->setCredit($credit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->payments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCreditLowerDeltaZero(): void
    {
        $credit = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $auditFile->getSourceDocuments()?->getPayments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->payments->setCredit($credit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->payments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testPayment')] public function testValidate(): void
    {
        $xml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_DEMO_PATH));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);
        $this->payments->setDeltaLine(new Decimal("0.005"));
        $this->payments->setDeltaCurrency(new Decimal("0.005"));
        $this->payments->setDeltaTable(new Decimal("0.005"));
        $this->payments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->payments->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     *
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testPayment')]
    public function testValidateNcDebit(): void
    {
        $xml = \simplexml_load_file(SAFT_DEBIT_NC);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_DEBIT_NC));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);
        $this->payments->setDeltaLine(new Decimal("0.005"));
        $this->payments->setDeltaCurrency(new Decimal("0.005"));
        $this->payments->setDeltaTable(new Decimal("0.005"));
        $this->payments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->payments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo@author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testPayment')]
    public function testValidateNcCreditAndDebit(): void
    {
        $xml = \simplexml_load_file(SAFT_CREDIT_AND_DEBIT_NC);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_CREDIT_AND_DEBIT_NC));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);
        $this->payments->setDeltaLine(new Decimal("0.005"));
        $this->payments->setDeltaCurrency(new Decimal("0.005"));
        $this->payments->setDeltaTable(new Decimal("0.005"));
        $this->payments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->payments->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo@author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testPayment')]
    public function testValidateMissingPayments(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_PAYMENTS);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_MISSING_PAYMENTS));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);
        $this->payments->setDeltaLine(new Decimal("0.005"));
        $this->payments->setDeltaCurrency(new Decimal("0.005"));
        $this->payments->setDeltaTable(new Decimal("0.005"));
        $this->payments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->payments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testValidateNoPayments(): void
    {

        $auditFile = new AuditFile();
        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);

        $valid = $this->payments->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testValidateNoPaymentCreditNotZero(): void
    {

        $auditFile = new AuditFile();
        $payments  = $auditFile->getSourceDocuments()?->getPayments()
                ?? throw new \Exception("Payments is null");

        $payments->setTotalCredit(new Decimal("999.09"));
        $payments->setTotalDebit(new Decimal("0.0"));
        $payments->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);

        $valid = $this->payments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testValidateNoPaymentDebitNotZero(): void
    {

        $auditFile = new AuditFile();
        $payments  = $auditFile->getSourceDocuments()?->getPayments()
            ?? throw new \Exception("Payments is null");

        $payments->setTotalCredit(new Decimal("0.0"));
        $payments->setTotalDebit(new Decimal("999.0"));
        $payments->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line */
        $this->payments->setAuditFile($auditFile);

        $valid = $this->payments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPayment(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N);
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($payment->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage();
            $taxPayable = $taxPayable->add(
                $taxPerc?->div(100)->mul($line->getCreditAmount())
                    ?? throw new \Exception("Tax percentage is null")
            );
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentWrongCustomerID(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N);
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($payment->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage();
            $taxPayable = $taxPayable->add(
                $taxPerc?->div(100)->mul($line->getCreditAmount())
                    ?? throw new \Exception("Tax percentage is null")
            );
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID() . "A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentWrongTotals(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);
        $this->iniPaymentLinesForLinesTest($payment);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N);
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($payment->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage();
            $taxPayable = $taxPayable->add(
                $taxPerc?->div("100.0")->mul($line->getCreditAmount())
                    ?? throw new \Exception("Tax percentage is null")
            );
        }

        $docTotals = $payment->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable)->add(1));

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($payment->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getDocumentTotals()->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentRefNoPaymentRefNo(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        //$payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentRefNoPaymentType(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        //$payment->setPaymentType(PaymentType::RG());
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentRefNoTransactionDate(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        //$payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        $payment->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testPaymentRefNoInvoiceSystemEntryDate(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setAtcud("0");
        $payment->setCustomerID("CODE_A");
        $payment->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $payment->setSourceID("Rebelo");
        //$payment->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->payments->payment($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testPayment')]
    public function testNumberOfEntries(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $payments = $auditFile->getSourceDocuments()?->getPayments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $payments?->addPayment();
        }

        $payments?->setNumberOfEntries($nMax);

        /** @phpstan-ignore-next-line */
        $this->payments->numberOfEntries();
        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());

        $this->assertSame(
            $nMax, $payments?->getDocTableTotalCalc()?->getNumberOfEntries()
        );

        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWrongNumberOfEntries(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $payments = $auditFile->getSourceDocuments()?->getPayments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $payments?->addPayment();
        }

        $payments?->setNumberOfEntries($nMax + 1);

        /** @phpstan-ignore-next-line */
        $this->payments->numberOfEntries();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());

        $this->assertSame(
            $nMax, $payments?->getDocTableTotalCalc()?->getNumberOfEntries()
        );

        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payments->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatus(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        /** @phpstan-ignore-next-line */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N);
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->payments->documentStatus($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusNotDefined(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        /** @phpstan-ignore-next-line */
        $this->payments->documentStatus($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_PAYMENT_STATUS,
            \array_key_first($payment->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusDateEarlier(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::N);
        $docStatus->setPaymentStatusDate(
            RDate::parse(Pattern::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->payments->documentStatus($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_PAYMENT_STATUS_DATE,
            \array_key_first($payment->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusCancel(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $now      = new RDate();
        $payment->setTransactionDate($now);
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::A);
        $docStatus->setPaymentStatusDate(clone $now);
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        /** @phpstan-ignore-next-line */
        $this->payments->documentStatus($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setPaymentStatus(PaymentStatus::A);
        $docStatus->setPaymentStatusDate(new RDate());
        $docStatus->setSourcePayment(SourcePayment::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->payments->documentStatus($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($payment->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerId(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile  = $this->payments->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setCustomerID($customerID);

        /** @phpstan-ignore-next-line */
        $this->payments->customerId($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdCustomerNotExist(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setCustomerID("A999");

        /** @phpstan-ignore-next-line */
        $this->payments->customerId($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            Payment::N_CUSTOMER_ID, \array_key_first($payment->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdCustomerIsNotSet(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");

        /** @phpstan-ignore-next-line */
        $this->payments->customerId($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payments->getError());
        $this->assertSame(
            Payment::N_CUSTOMER_ID, \array_key_first($payment->getError())
        );
    }

    /**
     * Init variables
     *
     * @return void
     */
    public function iniPaymentsForLineTest(): void
    {
        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setDocCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setDocDebit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->payments->setDebit(new Decimal("0.0"));
    }

    /**
     *
     * @param Payment $payment
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     */
    public function iniPaymentLinesForLinesTest(Payment $payment): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile     = $this->payments->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        for ($n = 1; $n <= 9; $n++) {
            $line     = $payment->addLine();
            $sourceId = $line->addSourceDocumentID();
            $sourceId->setDescription(\sprintf("Description of line '%s'", $n));
            $sourceId->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            $sourceId->setOriginatingON(\sprintf("FT FT/%s", $n));

            $line->setSettlementAmount(new Decimal("0.0"));
            $tax = $line->getTax() ?? throw new \Exception("Tax is null");
            $tax->setTaxCode($taxTableEntry->getTaxCode());
            $tax->setTaxCountryRegion($taxTableEntry->getTaxCountryRegion());
            $tax->setTaxPercentage($taxTableEntry->getTaxPercentage());
            $tax->setTaxType($taxTableEntry->getTaxType());

            $line->setCreditAmount((new Decimal("10.0"))->mul((string)$n));
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->payments->setContinuesLines(true);
        $this->iniPaymentsForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N);
        $this->iniPaymentLinesForLinesTest($payment);

        $lineStack = $payment->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        /** @phpstan-ignore-next-line */
        $this->payments->lines($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesRepeatedLineNumber(): void
    {
        $now = new RDate();
        $this->payments->setContinuesLines(false);
        $this->iniPaymentsForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setDocTotalCal(new DocTotalCalc());

        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N);

        $this->iniPaymentLinesForLinesTest($payment);

        $lineStack = $payment->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        /** @phpstan-ignore-next-line */
        $this->payments->lines($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoCreditAndDebitSet(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N);

        $line     = $payment->addLine();
        $sourceID = $line->addSourceDocumentID();
        $sourceID->setDescription("Desc of line '1'");
        $sourceID->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $sourceID->setOriginatingON("FT FT/1");

        /** @phpstan-ignore-next-line */
        $this->payments->lines($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testSourceDocumentID')]
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniPaymentsForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);
        $payment->setDocTotalCal(new DocTotalCalc());
        $payment->getDocumentStatus()->setPaymentStatus(PaymentStatus::N);
        $this->iniPaymentLinesForLinesTest($payment);

        // add a line different of init lines
        $n    = \count($payment->getLine());
        $line = $payment->addLine();
        $line->setCreditAmount(new Decimal("999.99"));

        $sourceId = $line->addSourceDocumentID();
        $sourceId->setDescription(\sprintf("Description of line '%s'", $n));
        $sourceId->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $sourceId->setOriginatingON(\sprintf("FT FT/%s", $n));

        /** @phpstan-ignore-next-line */
        $this->payments->lines($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testSourceDocumentID(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG);

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($source->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testMultipleSourceDocumentID(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line    = $payment->addLine();
        $srStack = array();
        for ($n = 1; $n <= 9; $n++) {
            $source = $line->addSourceDocumentID();
            $source->setOriginatingON(\sprintf("FT FT/%s", $n));
            $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            if ($n % 2 === 0) { // Test source with a without description
                $source->setDescription("Source description");
            }
            $srStack[$n] = $source;
        }

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        foreach ($srStack as $source) {
            $this->assertEmpty($source->getError());
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testSourceDocumentIDRepeatedOriginatingOn(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line    = $payment->addLine();
        $srStack = array();
        for ($n = 1; $n <= 2; $n++) {
            $source = $line->addSourceDocumentID();
            $source->setOriginatingON("FT FT/1");
            $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
            if ($n % 2 === 0) { // Test source with a without description
                $source->setDescription("Source description");
            }
            $srStack[$n] = $source;
        }

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        foreach ($srStack as $source) {
            $this->assertEmpty($source->getError());
        }
        $this->assertNotEmpty($srStack[2]->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testNoSourceDocumentID(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testSourceDocumentIDNoDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile $payment */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG);

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        //$source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($source->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testSourceDocumentIDOriginDateLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG);

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT FT/1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($source->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testSourceDocumentIDOriginDocNotValid(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC A/1");
        $payment->setPaymentType(PaymentType::RG);

        $line   = $payment->addLine();
        $source = $line->addSourceDocumentID();
        $source->setOriginatingON("FT 1");
        $source->setInvoiceDate($payment->getTransactionDate()->addDays(-1));
        $source->setDescription("Source description");

        $this->payments->sourceDocumentID($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($source->getError());
        $this->assertNotEmpty($source->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxNotSetOnNonCashVatScheme(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxNotSetOnCashVatScheme(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RC);

        $line = $payment->addLine();

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTypeNotSet(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTypeIvaPercentageNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxAmount(new Decimal("999.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxAmountZeroExceptionCodeNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxAmountZeroExceptionReasonNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionReasonNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIseExceptionReasonNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIseExceptionCodeNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIsePercentageNotZero(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTableTaxEmpty(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxWrongTableTaxEntry(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCode(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCountryRegion(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNotExistInTable(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeDateExpired(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxExpirationDateLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxExpirationDateNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxIS(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::OUT);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $line = $payment->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->payments->tax($line, $payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsDocumentTotalsNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("122.99");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal("123.00"));

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGrossDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross->sub("0.01")));

        $this->payments->setDeltaTotalDoc(new Decimal("0.01"));

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net->sub($delta)));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNetDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        $this->payments->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayable(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayableDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        $this->payments->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedCurrency(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.02");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $payment->setDocTotalCal($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedCurrencyDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $payment->setDocTotalCal($docTotalCal);

        $this->payments->setDeltaCurrency($delta);
        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotals(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(new RDate());
        $payment->setPaymentRefNo("RC RC/1");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        $totals = $payment->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->payments->setNetTotal(new Decimal($net));
        /** @phpstan-ignore-next-line */
        $this->payments->setTaxPayable(new Decimal($tax));
        /** @phpstan-ignore-next-line */
        $this->payments->setGrossTotal(new Decimal($gross));

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $payment->setDocTotalCal($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->payments->totals($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateNoHeader(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->setLastDocDate((clone $now)->addDays(1));
        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->payments->setLastSystemEntryDate((clone $now)->addSeconds(1));
        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDateAllDatesEqual(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->payments->setLastSystemEntryDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentDateAndSyEntryDate(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $docStatus = $payment->getDocumentStatus();
        $docStatus->setSourcePayment(SourcePayment::P);

        /** @phpstan-ignore-next-line */
        $this->payments->setLastDocDate((clone $now)->addDays(-1));
        /** @phpstan-ignore-next-line */
        $this->payments->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        /** @phpstan-ignore-next-line */
        $this->payments->paymentDateAndSystemEntryDate($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethod(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithWithholdingTax(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross->sub($withholdingTax->getWithholdingTaxAmount()));
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithoutPaymentMethod(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
        $this->assertNotEmpty($payment->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testMultiplePaymentMethod(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $payment->addPaymentMethod();
            $payMeth->setPaymentAmount($gross->div($nMax));
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU);
        }

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithoutAmount(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        //$payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithoutDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        //$payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodGrossDiffPayMeth(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross->sub("1.00"));
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodGrossDiffPayMethWithholdingTax(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $payment->addPaymentMethod();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->payments->paymentMethod($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTax(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testMultipleWithholdingTax(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        for ($n = 0; $n <= 0; $n++) {
            $withholdingTax = $payment->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));
        }

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxWithoutAmount(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        //$withholdingTax =
        $payment->addWithholdingTax();
        //$withholdingTax->setWithholdingTaxAmount(10.0);

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxGreaterThanGross(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);


        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross->add("0.10"));

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testMultipleWithholdingTaxGreaterThanGross(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()?->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $withholdingTax = $payment->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount($gross->div($nMax)->add("0.1"));
        }

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->payments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payment->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxGreaterThanHalfGross(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->payments->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments */
        $payments = $auditFile->getSourceDocuments()->getPayments();
        $payment  = $payments->addPayment();
        $payment->setTransactionDate(clone $now);
        $payment->setSystemEntryDate(clone $now);
        $payment->setPaymentRefNo("RC RC/2");
        $payment->setPaymentType(PaymentType::RG);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $payment->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $payment->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross->div(2)->add("0.1"));

        /** @phpstan-ignore-next-line */
        $this->payments->withholdingTax($payment);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->payments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($payment->getError());
        $this->assertNotEmpty($payment->getWarning());
    }
}
