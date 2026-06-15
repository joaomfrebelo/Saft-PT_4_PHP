<?php /** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
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

namespace Rebelo\SaftPt\Validate;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\Country;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Class SalesInvoiceTest
 *
 * @author João Rebelo
 */
class SalesInvoiceTest extends ASalesInvoiceBase
{

    protected function setUp(): void
    {
        $this->salesInvoicesFactory();
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(SalesInvoices::class))->testReflection(SalesInvoices::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTotalDebit(): void
    {
        $debit = new Decimal("909.99");

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDebit($debit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDebit($debit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setCredit($credit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()?->getSalesInvoices()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setCredit($credit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testOrderReferencesOneOrderReference')]
    #[Depends('testReferencesOneReference')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testInvoice')]
    public function testValidate(): void
    {
        $xml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_DEMO_PATH));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setAuditFile($auditFile);
        $this->salesInvoice->setDeltaLine(new Decimal("0.005"));
        $this->salesInvoice->setDeltaCurrency(new Decimal("0.005"));
        $this->salesInvoice->setDeltaTable(new Decimal("0.005"));
        $this->salesInvoice->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->salesInvoice->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testOrderReferencesOneOrderReference')]
    #[Depends('testReferencesOneReference')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testInvoice')]
    public function testValidateMissingInvoice(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_INVOICE);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_MISSING_INVOICE));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setAuditFile($auditFile);
        $this->salesInvoice->setDeltaLine(new Decimal("0.005"));
        $this->salesInvoice->setDeltaCurrency(new Decimal("0.005"));
        $this->salesInvoice->setDeltaTable(new Decimal("0.005"));
        $this->salesInvoice->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->salesInvoice->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    public function validateNoInvoices(): void
    {

        $auditFile = new AuditFile();
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setAuditFile($auditFile);

        $valid = $this->salesInvoice->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    public function validateNoInvoicesCreditNotZero(): void
    {

        $auditFile     = new AuditFile();
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        /** @phpstan-ignore-next-line */
        $salesInvoices->setTotalCredit(new Decimal("999.09"));
        /** @phpstan-ignore-next-line */
        $salesInvoices->setTotalDebit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $salesInvoices->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setAuditFile($auditFile);

        $valid = $this->salesInvoice->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    public function validateNoInvoicesDebitNotZero(): void
    {

        $auditFile     = new AuditFile();
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        /** @phpstan-ignore-next-line */
        $salesInvoices->setTotalCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $salesInvoices->setTotalDebit(new Decimal("999.0"));
        /** @phpstan-ignore-next-line */
        $salesInvoices->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setAuditFile($auditFile);

        $valid = $this->salesInvoice->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoice(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceOutOfDateType(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::VD);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongSign(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongDate(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount()
                ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongCustomerID(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID() . "A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceNoDocStatus(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.00")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceNoLines(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        //$this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongTotals(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable)->add(1));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getDocumentTotals()->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongShipment(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $invoice->getShipFrom(true);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getDebitAmount() ?? throw new \Exception("Debit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getDebitAmount()));

            $ref = $line->addReferences();
            $ref->setReference("FT FT/1");
            $ref->setReason("Cancel");
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal()
        );

        $invoice->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceWrongSign2(): void
    {
        $now = new RDate();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($invoice->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.00")->mul($line->getCreditAmount()));
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal(), "a"
        );

        $invoice->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testInvoiceNoInvoiceNo(): void
    {
        $now = new RDate();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        //$invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testInvoiceNoInvoiceType(): void
    {
        $now = new RDate();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        //$invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testInvoiceNoInvoiceDate(): void
    {
        $now = new RDate();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        //$invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testInvoiceNoInvoiceSystemEntryDate(): void
    {
        $now = new RDate();
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        //$invoice->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoice($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testInvoice')]
    public function testNumberOfEntries(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $salesInvoices->addInvoice();
        }

        $salesInvoices->setNumberOfEntries($nMax);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->numberOfEntries();
        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertSame(
            $nMax, $salesInvoices->getDocTableTotalCalc()?->getNumberOfEntries()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testWrongNumberOfEntries(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $salesInvoices->addInvoice();
        }

        $salesInvoices->setNumberOfEntries($nMax + 1);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->numberOfEntries();
        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertSame(
            $nMax, $salesInvoices->getDocTableTotalCalc()?->getNumberOfEntries()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($salesInvoices->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatus(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate($now);
        $invoice->setSystemEntryDate($now);
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusNotDefined(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate($now);
        $invoice->setInvoiceNo("FT FT/1");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_DOCUMENT_STATUS, \array_key_first($invoice->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusDateEarlierDocDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $invoice->setSystemEntryDate(RDate::parse(Pattern::SQL_DATE, "2020-10-04"));
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(
            RDate::parse(Pattern::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\Date\DateException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusDateEarlierSystemEntryDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $invoice->setSystemEntryDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N);
        $docStatus->setInvoiceStatusDate(
            RDate::parse(Pattern::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            DocumentStatus::N_INVOICE_STATUS_DATE,
            \array_key_first($invoice->getError())
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
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::A);
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setSystemEntryDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::A);
        $docStatus->setInvoiceStatusDate(new RDate());
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->documentStatus($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($invoice->getError())
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
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile  = $this->salesInvoice->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setCustomerID($customerID);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->customerId($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdCustomerNotExist(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setCustomerID("A999");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->customerId($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_CUSTOMER_ID, \array_key_first($invoice->getError())
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
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->customerId($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_CUSTOMER_ID, \array_key_first($invoice->getError())
        );
    }

    /**
     * Init variables
     *
     * @return void
     */
    public function iniSalesInvoiceForLineTest(): void
    {
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDocCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDocDebit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setDebit(new Decimal("0.0"));
    }

    /**
     *
     * @param Invoice $invoice
     *
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function iniInvoiceLinesForLinesTest(Invoice $invoice): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        for ($n = 1; $n <= 9; $n++) {
            $line = $invoice->addLine();
            $line->setQuantity(new Decimal($n));
            $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
            if ($invoice->getInvoiceType() === InvoiceType::NC) {
                $line->setDebitAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
                $ref = $line->addReferences();
                $ref->setReason("Reason");
                $ref->setReference("FT FT/1");
            } else {
                $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
            }
            $line->setDescription("Desc of line " . $n);
            $line->setProductCode("CODE_" . $n);
            $line->setProductDescription("Prod desc of line " . $n);
            $line->setSettlementAmount((new Decimal(".1"))->mul($n));
            $line->setTaxPointDate(clone $invoice->getInvoiceDate());
            $line->setUnitOfMeasure("UN");

            $tax = $line->getTax();
            $tax->setTaxCode($taxTableEntry->getTaxCode());
            $tax->setTaxCountryRegion($taxTableEntry->getTaxCountryRegion());
            $tax->setTaxPercentage($taxTableEntry->getTaxPercentage());
            $tax->setTaxType($taxTableEntry->getTaxType());

            $prod = $auditFile->getMasterFiles()->addProduct();
            $prod->setProductCode($line->getProductCode());
            $prod->setProductDescription($line->getProductDescription());
            $prod->setProductNumberCode($line->getProductCode());
            $prod->setProductType(ProductType::P);
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->salesInvoice->setContinuesLines(true);
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $lineStack = $invoice->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesRepeatedLineNumber(): void
    {
        $now = new RDate();
        $this->salesInvoice->setContinuesLines(false);
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());

        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);

        $this->iniInvoiceLinesForLinesTest($invoice);

        $lineStack = $invoice->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoQuantitySet(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoUnitPriceSet(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoCreditAndDebitSet(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal($n))->mul("1.2"));
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithTaxBaseAndUnitPriceGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount(new Decimal("0.0")); // Zero to test failure with TaxBase
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithTaxBaseAndCreditAmountGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice(new Decimal("0.0")); // Zero to test failure with TaxBase
        $line->setCreditAmount(new Decimal("9.49"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceDebitAmount(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setDebitAmount((new Decimal((string)$n))->mul($n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceCreditAmount(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal($n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal("0.0"));
        $line->setUnitPrice(new Decimal("0.0"));
        $line->setCreditAmount(new Decimal("0.0"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditSameCancellationValue(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($lastLine->getCreditAmount());
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditSameCancellationValueOnCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setCreditAmount($lastLine->getDebitAmount());
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditLessCancellationAndValue(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity()->div(2));
        $line->setUnitPrice($lastLine->getUnitPrice()->div(2));
        $line->setDebitAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditLessCancellationQtAndValueOnCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity()->div(2));
        $line->setUnitPrice($lastLine->getUnitPrice()->div(2));
        $line->setCreditAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditCancellationGreaterUnitPrice(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice()->add("0.02"));
        $line->setDebitAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditCancellationGreaterUnitPriceOnCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice()->add("0.01"));
        $line->setCreditAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditCancellationGreaterQt(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity()->add("0.02"));
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditCancellationGreaterQtOnCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
        $lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity()->add("0.01"));
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setCreditAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line " . $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC);
        $invoice->setDocTotalCalc(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(new Decimal("0.0"));
        $line->setUnitPrice(new Decimal("0.0"));
        $line->setDebitAmount(new Decimal("0.0"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->lines($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesOneReference(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FT FT/1");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesMultipleReference(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("ND A/1");
        $invoice->setInvoiceType(InvoiceType::ND);

        $line = $invoice->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("Some reason");
        $ref1->setReference("FT FT/1");

        $ref2 = $line->addReferences();
        $ref2->setReason("Some other reason");
        $ref2->setReference("FT FT/3");

        $ref3 = $line->addReferences();
        $ref3->setReason("Some other other reason");
        $ref3->setReference("FT FT/9");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesMultipleReferenceOneReason(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("Some reason");
        $ref1->setReference("FT FT/1");

        $ref2 = $line->addReferences();
        $ref2->setReason("Some other reason");

        $ref3 = $line->addReferences();
        $ref3->setReason("Some other other reason");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesMultipleReferenceNoReason(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReference("FT FT/1");

        $ref2 = $line->addReferences();
        $ref2->setReference("FT FT/3");

        $ref3 = $line->addReferences();
        $ref3->setReference("FT FT/9");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesMultipleReferenceNoReference(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("AAAAAA");

        $ref2 = $line->addReferences();
        $ref2->setReason("BBBBB");

        $ref3 = $line->addReferences();
        $ref3->setReason("CCCCCCC");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesMultipleReferenceNoReferenceNoReason(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref1 = $line->addReferences();
        $ref2 = $line->addReferences();
        $ref3 = $line->addReferences();

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testReferencesOneReferenceOnNonNCOrNd(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FT FT/1");

        $this->salesInvoice->references($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesOneOrderReference(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $invoice->getInvoiceDate());
        $ref->setOriginatingON("GT A/1");

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesMultipleReference(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("ND A/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref1 = $line->addOrderReferences();
        $ref1->setOrderDate(clone $invoice->getInvoiceDate());
        $ref1->setOriginatingON("GT A/1");

        $ref2 = $line->addOrderReferences();
        $ref2->setOrderDate((clone $invoice->getInvoiceDate())->addDays(-1));
        $ref2->setOriginatingON("GT A/2");

        $ref3 = $line->addOrderReferences();
        $ref3->setOrderDate(clone $invoice->getInvoiceDate());
        $ref3->setOriginatingON("GT A/3");

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref1->getError());
        $this->assertEmpty($ref2->getError());
        $this->assertEmpty($ref3->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesNoDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N);
        $invoice->getDocumentStatus()->setSourceBilling(SourceBilling::P);


        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesNoOriginateOn(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesDateLater(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesOnNC(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesOnND(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::ND);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesWrongOriginatingOn(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
        $this->assertNotEmpty($ref->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testProductCodeExists(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setProductCode($productCode);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->productCode($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testProductCodeNotExists(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setProductCode("COD999");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->productCode($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testProductCodeNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->productCode($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTypeNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTypeIvaPercentageNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxAmount(new Decimal("999.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxAmountZeroExceptionCodeNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxAmountZeroExceptionReasonNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionReasonNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIseExceptionReasonNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        $this/** @phpstan-ignore-next-line */
        ->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIseExceptionCodeNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIsePercentageNotZero(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTableTaxEmpty(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxWrongTableTaxEntry(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCode(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCountryRegion(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNotExistInTable(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
    public function testTaxCodeDateExpired(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
    public function testTaxTaxExpirationDateLater(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxExpirationDateNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxIS(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::OUT);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->tax($line, $invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("122.99");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal(new Decimal("123.00"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross->sub("0.01"));

        $this->salesInvoice->setDeltaTotalDoc(new Decimal("0.01"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        $this->salesInvoice->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        $this->salesInvoice->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.02");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $invoice->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $invoice->setDocTotalCalc($docTotalCal);

        $this->salesInvoice->setDeltaCurrency($delta);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
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
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $invoice->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->totals($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignNoHash(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignNoHashSkip(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignSkip(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setHash("AAA");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $this->salesInvoice->setSignValidation(false);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignPreviousHashEmpty(): void
    {

        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $invoice->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignPreviousHashNull(): void
    {

        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal()
        );

        $invoice->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignWrongHashFirstNumberNumberPreviousHashEmpty(): void
    {
        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $invoice->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignWrongHashNotFirstNumberNumberPreviousHashEmpty(): void
    {
        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(),
            $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(),
            ""
        );

        $invoice->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());

        $this->assertNotEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignWrongHashFirstNumberNumberPreviousHashNotEmpty(): void
    {
        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $latestHash = "AAA";
        $hash       = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), $latestHash
        );

        $invoice->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash($latestHash);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSign(): void
    {
        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
        }

        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $firstInvoice  = $salesInvoices->addInvoice();
        $firstInvoice->setInvoiceDate(clone $now);
        $firstInvoice->setSystemEntryDate(clone $now);
        $firstInvoice->setInvoiceNo("FT FT/1");
        $firstInvoice->setInvoiceType(InvoiceType::FT);
        $firstInvoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $firstHash = $sign->createSignature(
            $firstInvoice->getInvoiceDate(),
            $firstInvoice->getSystemEntryDate(), $firstInvoice->getInvoiceNo(),
            $firstInvoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $firstInvoice->setHash($firstHash);

        $secondInvoice = $salesInvoices->addInvoice();
        $secondInvoice->setInvoiceDate(clone $now);
        $secondInvoice->setSystemEntryDate(clone $now);
        $secondInvoice->setInvoiceNo("FT FT/2");
        $secondInvoice->setInvoiceType(InvoiceType::FT);
        $secondInvoice->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));


        $docStatus = $secondInvoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $secondHash = $sign->createSignature(
            $secondInvoice->getInvoiceDate(),
            $secondInvoice->getSystemEntryDate(),
            $secondInvoice->getInvoiceNo(),
            $secondInvoice->getDocumentTotals()->getGrossTotal(), $firstHash
        );

        $secondInvoice->setHash($secondHash);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastHash($firstHash);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->sign($secondInvoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($firstInvoice->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testShipmentAllNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testShipmentWrongInvoiceType(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FS FS/2");
        $invoice->setInvoiceType(InvoiceType::FS);
        $invoice->setMovementStartTime((clone $now)->addHours(1));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentMovementStartTimeNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $invoice->setMovementEndTime((clone $now)->addHours(1));

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentMovementStartTimeEarlierInvoiceDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate((clone $now)->addDays(-2));
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addDays(-1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentMovementStartTimeSystemEntryDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate((clone $now)->addDays(-2));
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addDays(-1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentMovementStartTimeLaterEndTime(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(9));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from address");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to address");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentNoShipFromAddress(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to address is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipmentNoShipFrom(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipFromNoStreetNameNoAddressDetail(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipFromEmptyStreetNameNoAddressDetail(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("");
        $fromAddr->setStreetName("");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipFromCityNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipFromEmptyCity(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipFromCountryNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToNoAddress(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToNoSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToStreetNameAndAddrDetailEmpty(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("");
        $toAddr->setStreetName("");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToStreetNameAndAddrDetailNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToCityNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToCityEmpty(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Ship to is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipToCountryNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship from is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
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
    public function testShipment(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
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
    public function testShipmentNoEndTime(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);
        $invoice->setMovementStartTime((clone $now)->addHours(1));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $from = $invoice->getShipFrom() ?? throw new \Exception("Ship from is null");
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress() ?? throw new \Exception("Address is null");
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT);

        $to = $invoice->getShipTo() ?? throw new \Exception("Ship to is null");
        $to->addDeliveryID("Delivery ID");
        $to->setDeliveryDate(clone $now);
        $toAddr = $to->getAddress() ?? throw new \Exception("Address is null");
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->shipment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateNoHeader(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastDocDate((clone $now)->addDays(1));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastSystemEntryDate((clone $now)->addSeconds(1));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDateAllDatesEqual(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastSystemEntryDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInvDateAndSyEntryDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastDocDate((clone $now)->addDays(-1));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        /** @phpstan-ignore-next-line */
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTax(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testMultipleWithholdingTax(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        for ($n = 0; $n <= 0; $n++) {
            $withholdingTax = $invoice->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));
        }

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxWithoutAmount(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        //$withholdingTax =
        $invoice->addWithholdingTax();
        //$withholdingTax->setWithholdingTaxAmount(10.0);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxGreaterThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);


        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross->add("0.10"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testMultipleWithholdingTaxGreaterThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $withholdingTax = $invoice->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount($gross->div($nMax)->add("0.1"));
        }

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithholdingTaxGreaterThanHalfGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross->div(2)->add("0.1"));

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->withholdingTax($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertNotEmpty($invoice->getWarning());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethod(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithWithholdingTax(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross->sub($withholdingTax->getWithholdingTaxAmount()));
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithoutPaymentMethodNotFR(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertEmpty($invoice->getWarning());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWithoutPaymentMethodFR(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FR FT/2");
        $invoice->setInvoiceType(InvoiceType::FR);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertNotEmpty($invoice->getWarning());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testMultiplePaymentMethod(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount($gross->div($nMax));
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU);
        }

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithoutAmount(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        //$payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodWithoutDate(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        //$payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodDiffGrossOnFR(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FR);

        $gross      = new Decimal("123.00");
        $net        = new Decimal(new Decimal("100.00"));
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross->sub("1.00"));
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodGrossDiffPayMethWithholdingTaxOnFR(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FR FT/2");
        $invoice->setInvoiceType(InvoiceType::FR);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(new Decimal("10.0"));

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodLessThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross->sub("10.0"));
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testMultiplePaymentMethodLessThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount($gross->div($nMax)->mul("0.9"));
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU);
        }

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testPaymentMethodGreaterThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross->add("10.0"));
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross->sub("10.0"));
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testMultiplePaymentMethodGreaterThanGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT);

        $gross      = new Decimal("123.00");
        $net        = new Decimal("100.00");
        $taxPayable = new Decimal("23.00");

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross->add("10.0"));
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount($gross->div($nMax)->mul("1.1"));
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU);
        }

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->payment($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetConfiguration(): void
    {
        $config = new ValidationConfig();
        $this->salesInvoice->setConfiguration($config);

        $this->assertSame(
            $config->getAllowDebitAndCredit(),
            $this->salesInvoice->getAllowDebitAndCredit()
        );

        $this->assertSame(
            $config->getContinuesLines(),
            $this->salesInvoice->getContinuesLines()
        );

        $this->assertSame(
            $config->getDeltaCurrency(), $this->salesInvoice->getDeltaCurrency()
        );

        $this->assertSame(
            $config->getDeltaLine(), $this->salesInvoice->getDeltaLine()
        );

        $this->assertSame(
            $config->getDeltaTable(), $this->salesInvoice->getDeltaTable()
        );

        $this->assertSame(
            $config->getDeltaTotalDoc(), $this->salesInvoice->getDeltaTotalDoc()
        );

        $this->assertSame(
            $config->getSignValidation(),
            $this->salesInvoice->getSignValidation()
        );
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetConfigurationNoDefaults(): void
    {
        $config = new ValidationConfig();
        $config->setAllowDebitAndCredit(false);
        $config->setContinuesLines(false);
        $config->setDeltaCurrency(new Decimal("0.09"));
        $config->setDeltaLine(new Decimal("0.04"));
        $config->setDeltaTable(new Decimal("0.19"));
        $config->setDeltaTotalDoc(new Decimal("0.29"));
        $config->setSignValidation(false);

        $this->salesInvoice->setConfiguration($config);

        $this->assertSame(
            $config->getAllowDebitAndCredit(),
            $this->salesInvoice->getAllowDebitAndCredit()
        );

        $this->assertSame(
            $config->getContinuesLines(),
            $this->salesInvoice->getContinuesLines()
        );

        $this->assertSame(
            $config->getDeltaCurrency(), $this->salesInvoice->getDeltaCurrency()
        );

        $this->assertSame(
            $config->getDeltaLine(), $this->salesInvoice->getDeltaLine()
        );

        $this->assertSame(
            $config->getDeltaTable(), $this->salesInvoice->getDeltaTable()
        );

        $this->assertSame(
            $config->getDeltaTotalDoc(), $this->salesInvoice->getDeltaTotalDoc()
        );

        $this->assertSame(
            $config->getSignValidation(),
            $this->salesInvoice->getSignValidation()
        );
    }

    /**
     * @return mixed[]
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    public static function outOfDateInvoiceTypesInDateProvider(): array
    {
        $inDateStack  = [
            RDate::parse(Pattern::SQL_DATE, "2012-12-31"), // Last valid day
            RDate::parse(Pattern::SQL_DATE, "2012-10-05")
        ];
        $outDateTypes = [
            InvoiceType::VD,
            InvoiceType::TV,
            InvoiceType::TD,
            InvoiceType::AA,
            InvoiceType::DA
        ];

        $stack = [];
        foreach ($inDateStack as $date) {
            foreach ($outDateTypes as $type) {
                $stack[] = [$date, $type];
            }
        }
        return $stack;
    }

    /**
     * @param \Rebelo\Date\Date                                                  $date
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $type
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    #[DataProvider('outOfDateInvoiceTypesInDateProvider')]
    public function testOutOfDateInvoiceTypesInDate(
        RDate       $date,
        InvoiceType $type
    ): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate($date);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType($type);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->outOfDateInvoiceTypes($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

    /**
     * @return mixed[]
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    public static function outOfDateInvoiceTypesOutDateProvider(): array
    {
        $inDateStack  = [
            RDate::parse(Pattern::SQL_DATE, "2013-01-01"), // First invalid day
            RDate::parse(Pattern::SQL_DATE, "2014-10-05")
        ];
        $outDateTypes = [
            InvoiceType::VD,
            InvoiceType::TV,
            InvoiceType::TD,
            InvoiceType::AA,
            InvoiceType::DA
        ];

        $stack = [];
        foreach ($inDateStack as $date) {
            foreach ($outDateTypes as $type) {
                $stack[] = [$date, $type];
            }
        }
        return $stack;
    }

    /**
     * @param \Rebelo\Date\Date                                                  $date
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $type
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    #[DataProvider('outOfDateInvoiceTypesOutDateProvider')]
    public function testOutOfDateInvoiceTypesOutDate(RDate $date, InvoiceType $type): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->salesInvoice->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()?->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate($date);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType($type);

        /** @phpstan-ignore-next-line */
        $this->salesInvoice->outOfDateInvoiceTypes($invoice);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }
}
