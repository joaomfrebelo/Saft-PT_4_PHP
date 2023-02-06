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

namespace Rebelo\SaftPt\Validate;

use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\Decimal\DecimalException;
use Rebelo\Decimal\UDecimal;
use Rebelo\Enum\EnumException;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
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
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\Sign\Sign;
use Rebelo\SaftPt\Sign\SignException;

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
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(SalesInvoices::class);
        $this->assertTrue(true);
    }

	/**
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalDebit(): void
    {
        $debit     = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalDebit($debit);

        $this->salesInvoice->setDebit(
            new UDecimal($debit, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->totalDebit();

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 *
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalDebitGreaterDeltaZero(): void
    {
        $debit     = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalDebit($debit);

        $this->salesInvoice->setDebit(
            (new UDecimal($debit, SalesInvoices::CALC_PRECISION))->plus(0.09)
        );

        $this->salesInvoice->totalDebit();

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 *
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalDebitLowerDeltaZero(): void
    {
        $debit     = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalDebit($debit);

        $this->salesInvoice->setDebit(
            (new UDecimal($debit, SalesInvoices::CALC_PRECISION))->subtract(0.09)
        );

        $this->salesInvoice->totalDebit();

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalCredit(): void
    {
        $credit    = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalCredit($credit);

        $this->salesInvoice->setCredit(
            new UDecimal($credit, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->totalCredit();

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 *
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalCreditGreaterDeltaZero(): void
    {
        $credit    = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalCredit($credit);

        $this->salesInvoice->setCredit(
            (new UDecimal($credit, SalesInvoices::CALC_PRECISION))->plus(0.09)
        );

        $this->salesInvoice->totalCredit();

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 *
	 * @return void
	 * @throws DecimalException
	 * @throws EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalCreditLowerDeltaZero(): void
    {
        $credit    = 909.99;
        $auditFile = $this->salesInvoice->getAuditFile();
        $auditFile->getSourceDocuments()->getSalesInvoices()
            ->setTotalCredit($credit);

        $this->salesInvoice->setCredit(
            (new UDecimal($credit, SalesInvoices::CALC_PRECISION))->subtract(0.09)
        );

        $this->salesInvoice->totalCredit();

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws SignException
	 * @throws DateFormatException
	 * @throws DateParseException
	 * @throws AuditFileException
	 * @author João Rebelo@author João Rebelo
	 * @depends testInvoice
	 * @depends testNumberOfEntries
	 * @depends testTotalDebit
	 * @depends testTotalCredit
	 * @depends testReferncesOneReference
	 * @depends testOrderReferencesOneOrderReference
	 */
    public function testValidate(): void
    {
        $xml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($xml === false) {
            $this->fail(\sprintf("Failling load file '%s'", SAFT_DEMO_PATH));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $this->salesInvoice->setAuditFile($auditFile);
        $this->salesInvoice->setDeltaLine(0.005);
        $this->salesInvoice->setDeltaCurrency(0.005);
        $this->salesInvoice->setDeltaTable(0.005);
        $this->salesInvoice->setDeltaTotalDoc(0.005);

        $valide = $this->salesInvoice->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws AuditFileException
	 * @throws DateFormatException
	 * @throws DateParseException
	 * @throws SignException
	 * @author João Rebelo@author João Rebelo
	 * @depends testInvoice
	 * @depends testNumberOfEntries
	 * @depends testTotalDebit
	 * @depends testTotalCredit
	 * @depends testReferncesOneReference
	 * @depends testOrderReferencesOneOrderReference
	 */
    public function testValidateMissingInvoice(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_INVOICE);
        if ($xml === false) {
            $this->fail(\sprintf("Failling load file '%s'", SAFT_MISSING_INVOICE));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $this->salesInvoice->setAuditFile($auditFile);
        $this->salesInvoice->setDeltaLine(0.005);
        $this->salesInvoice->setDeltaCurrency(0.005);
        $this->salesInvoice->setDeltaTable(0.005);
        $this->salesInvoice->setDeltaTotalDoc(0.005);

        $valide = $this->salesInvoice->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoices(): void
    {

        $auditFile = new AuditFile();
        $this->salesInvoice->setAuditFile($auditFile);

        $valide = $this->salesInvoice->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoicesCreditNotZero(): void
    {

        $auditFile     = new AuditFile();
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $salesInvoices->setTotalCredit(999.09);
        $salesInvoices->setTotalDebit(0.0);
        $salesInvoices->setNumberOfEntries(0);

        $this->salesInvoice->setAuditFile($auditFile);

        $valide = $this->salesInvoice->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoicesDebitNotZero(): void
    {

        $auditFile     = new AuditFile();
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $salesInvoices->setTotalCredit(0.0);
        $salesInvoices->setTotalDebit(999.0);
        $salesInvoices->setNumberOfEntries(0);

        $this->salesInvoice->setAuditFile($auditFile);

        $valide = $this->salesInvoice->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws DateFormatException
	 * @throws DecimalException
	 * @throws EnumException
	 * @throws SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoice(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceOutOfDateType(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::VD());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrohgSign(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($invoice->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrohgDate(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrongCustomerID(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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
        $customer->setCustomerID($invoice->getCustomerID()."A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceNoDocStatus(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceNoLines(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        //$this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrongTotals(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf() + 1);

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getDocumentTotals()->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrongShipement(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceCreditNote(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $header        = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(InvoiceStatus::N());
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getDebitAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getDebitAmount());

            $ref = $line->addReferences();
            $ref->setReference("FT FT/1");
            $ref->setReason("Cancel");
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

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

        $this->salesInvoice->invoice($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 */
    public function testInvoiceWrongSign(): void
    {
        $now           = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);
        $this->iniInvoiceLinesForLinesTest($invoice);

        $taxPayable = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, SalesInvoices::CALC_PRECISION);

        foreach ($invoice->getLine() as $line) {
			$netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $invoice->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(), $docTotals->getGrossTotal(), "a"
        );

        $invoice->setHash($hash);
        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 * @test
	 */
    public function testInvoiceNoInvoiceNo(): void
    {
        $now           = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        //$invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 * @test
	 */
    public function testInvoiceNoInvoiceType(): void
    {
        $now           = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        //$invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 * @test
	 */
    public function testInvoiceNoInvoiceDate(): void
    {
        $now           = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        //$invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        $invoice->setSystemEntryDate(clone $now);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @depends testDocumentStatus
	 * @depends testCustomerId
	 * @depends testLines
	 * @test
	 */
    public function testInvoiceNoInvoiceSystemEntryDate(): void
    {
        $now           = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setAtcud("0");
        $invoice->setCustomerID("CODE_A");
        $invoice->setHashControl("1");
        $invoice->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $invoice->setSourceID("Rebelo");
        //$invoice->setSystemEntryDate(clone $now);

        $this->salesInvoice->invoice($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @author João Rebelo
     * @depends testInvoice
     * @test
     * @return void
     */
    public function testNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $salesInvoices->addInvoice();
        }

        $salesInvoices->setNumberOfEntries($nMax);

        $this->salesInvoice->numberOfEntries();
        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertSame(
            $nMax, $salesInvoices->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWrongNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $salesInvoices->addInvoice();
        }

        $salesInvoices->setNumberOfEntries($nMax + 1);

        $this->salesInvoice->numberOfEntries();
        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertSame(
            $nMax, $salesInvoices->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($salesInvoices->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatus(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate($now);
        $invoice->setSystemEntryDate($now);
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(new InvoiceStatus(InvoiceStatus::N));
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->salesInvoice->documentStatus($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatusNotDefined(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate($now);
        $invoice->setInvoiceNo("FT FT/1");

        $this->salesInvoice->documentStatus($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_DOCUMENTSTATUS, \array_key_first($invoice->getError())
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatusStatusDateEalierDocDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $invoice->setSystemEntryDate(RDate::parse(RDate::SQL_DATE, "2020-10-04"));
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(new InvoiceStatus(InvoiceStatus::N));
        $docStatus->setInvoiceStatusDate(
            RDate::parse(RDate::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->salesInvoice->documentStatus($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatusStatusDateEalierSystemEntryDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $invoice->setSystemEntryDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(new InvoiceStatus(InvoiceStatus::N));
        $docStatus->setInvoiceStatusDate(
            RDate::parse(RDate::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->salesInvoice->documentStatus($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            DocumentStatus::N_INVOICESTATUSDATE,
            \array_key_first($invoice->getError())
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatusCancel(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $now           = new RDate();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(new InvoiceStatus(InvoiceStatus::A));
        $docStatus->setInvoiceStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        $this->salesInvoice->documentStatus($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setSystemEntryDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setInvoiceStatus(new InvoiceStatus(InvoiceStatus::A));
        $docStatus->setInvoiceStatusDate(new RDate());
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->salesInvoice->documentStatus($invoice);

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
	 * @test
	 */
    public function testCustomerId(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile  = $this->salesInvoice->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setCustomerID($customerID);

        $this->salesInvoice->customerId($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testCustomerIdCustomerNotExist(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setCustomerID("A999");

        $this->salesInvoice->customerId($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_CUSTOMERID, \array_key_first($invoice->getError())
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testCustomerIdCustomerIsNotSet(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");

        $this->salesInvoice->customerId($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($salesInvoices->getError());
        $this->assertSame(
            Invoice::N_CUSTOMERID, \array_key_first($invoice->getError())
        );
    }

	/**
	 * Init variables
	 * @return void
	 * @throws \Rebelo\Decimal\DecimalException
	 */
    public function iniSalesInvoiceForLineTest(): void
    {
        $this->salesInvoice->setNetTotal(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setGrossTotal(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setTaxPayable(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setDocCredit(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setDocDebit(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setCredit(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );

        $this->salesInvoice->setDebit(
            new UDecimal(0.0, SalesInvoices::CALC_PRECISION)
        );
    }

	/**
	 *
	 * @param Invoice $invoice
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 */
    public function iniInvoiceLinesForLinesTest(Invoice $invoice): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        for ($n = 1; $n <= 9; $n++) {
            $line = $invoice->addLine();
            $line->setQuantity($n);
            $line->setUnitPrice($n * 1.2);
            if ($invoice->getInvoiceType()->isEqual(InvoiceType::NC)) {
                $line->setDebitAmount($n * $n * 1.2);
                $ref = $line->addReferences();
                $ref->setReason("Reason");
                $ref->setReference("FT FT/1");
            } else {
                $line->setCreditAmount($n * $n * 1.2);
            }
            $line->setDescription("Desc of line ". $n);
            $line->setProductCode("CODE_". $n);
            $line->setProductDescription("Prod desc of line ". $n);
            $line->setSettlementAmount(.1 * $n);
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
            $prod->setProductType(ProductType::P());
        }
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->salesInvoice->setContinuesLines(true);
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

		$lineStack = $invoice->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesRepetedLineNumber(): void
    {

        $now = new RDate();
        $this->salesInvoice->setContinuesLines(false);
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());

        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());

        $this->iniInvoiceLinesForLinesTest($invoice);


		$lineStack = $invoice->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesNoQuantitySetted(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesNoUnitPriceSetted(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesNoCreditAndDebitSetted(): void
    {

        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithTaxBaseAndUnitPriceGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount(0.0); // Zero to test failure with TaxBase
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithTaxBaseAndCreditAmountGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice(0.0); // Zero to test failure with TaxBase
        $line->setCreditAmount(9.49);
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWrongQtUnitPriceDebitAmount(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setDebitAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWrongQtUnitPriceCreditAmount(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(0.0);
        $line->setUnitPrice(0.0);
        $line->setCreditAmount(0.0);
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditSameAnulationValue(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($lastLine->getCreditAmount());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditSameAnulationValueOnCreditNote(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setCreditAmount($lastLine->getDebitAmount());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditLessAnulationQAndtValue(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity() / 2);
        $line->setUnitPrice($lastLine->getUnitPrice() / 2);
        $line->setDebitAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditLessAnulationQtAndValueOnCreditNote(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity() / 2);
        $line->setUnitPrice($lastLine->getUnitPrice() / 2);
        $line->setCreditAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditAnulationGreaterUnitPrice(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice() + 0.02);
        $line->setDebitAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditAnulationGreaterUnitPriceOnCreditNote(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice() + 0.01);
        $line->setCreditAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditAnulationGreaterQt(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity() + 0.02);
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesWithAllowDebitAndCreditAnulationGreaterQtOnCreditNote(): void
    {
        $now           = new RDate();
        $this->iniSalesInvoiceForLineTest();
        $this->salesInvoice->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n        = \count($invoice->getLine()) - 1;
		$lastLine = $invoice->getLine()[$n];
        $line     = $invoice->addLine();
        $line->setQuantity($lastLine->getQuantity() + 0.01);
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setCreditAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->salesInvoice->lines($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testLinesCreditNote(): void
    {
        $now = new RDate();
        $this->iniSalesInvoiceForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setInvoiceNo("NC NC/1");
        $invoice->setInvoiceType(InvoiceType::NC());
        $invoice->setDocTotalcal(new DocTotalCalc());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $this->iniInvoiceLinesForLinesTest($invoice);

        $n    = \count($invoice->getLine());
        $line = $invoice->addLine();
        $line->setQuantity(0.0);
        $line->setUnitPrice(0.0);
        $line->setDebitAmount(0.0);
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $invoice->getInvoiceDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->salesInvoice->lines($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesOneReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::NC));

        $line = $invoice->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FT FT/1");

        $this->salesInvoice->references($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesMultipleReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("ND A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::ND));

        $line  = $invoice->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("Some reason");
        $ref_1->setReference("FT FT/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("Some other reason");
        $ref_2->setReference("FT FT/3");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("Some other other reason");
        $ref_3->setReference("FT FT/9");

        $this->salesInvoice->references($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesMultipleReferenceOneReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::NC));

        $line  = $invoice->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("Some reason");
        $ref_1->setReference("FT FT/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("Some other reason");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("Some other other reason");

        $this->salesInvoice->references($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesMultipleReferenceNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::NC));

        $line  = $invoice->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReference("FT FT/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReference("FT FT/3");

        $ref_3 = $line->addReferences();
        $ref_3->setReference("FT FT/9");

        $this->salesInvoice->references($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesMultipleReferenceNoReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::NC));

        $line  = $invoice->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("AAAAAA");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("BBBBB");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("CCCCCCC");

        $this->salesInvoice->references($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesMultipleReferenceNoReferenceNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::NC));

        $line  = $invoice->addLine();
        $ref_1 = $line->addReferences();
        $ref_2 = $line->addReferences();
        $ref_3 = $line->addReferences();

        $this->salesInvoice->references($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testReferncesOneReferenceOnNonNCOrNd(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(new InvoiceType(InvoiceType::FT));

        $line = $invoice->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FT FT/1");

        $this->salesInvoice->references($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesOneOrderReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $invoice->getInvoiceDate());
        $ref->setOriginatingON("GT A/1");

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesMultipleReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("ND A/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line  = $invoice->addLine();
        $ref_1 = $line->addOrderReferences();
        $ref_1->setOrderDate(clone $invoice->getInvoiceDate());
        $ref_1->setOriginatingON("GT A/1");

        $ref_2 = $line->addOrderReferences();
        $ref_2->setOrderDate((clone $invoice->getInvoiceDate())->addDays(-1));
        $ref_2->setOriginatingON("GT A/2");

        $ref_3 = $line->addOrderReferences();
        $ref_3->setOrderDate(clone $invoice->getInvoiceDate());
        $ref_3->setOriginatingON("GT A/3");

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesNoDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentStatus()->setInvoiceStatus(InvoiceStatus::N());
        $invoice->getDocumentStatus()->setSourceBilling(SourceBilling::P());


        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesNoOriginateOn(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT A/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesOnNC(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::NC());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesOnND(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("NC A/1");
        $invoice->setInvoiceType(InvoiceType::ND());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FT FT/1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testOrderReferencesWrongOriginatingOn(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $invoice->getInvoiceDate());

        $this->salesInvoice->orderReferences($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
        $this->assertNotEmpty($ref->getWarning());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testProducCodeExists(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setProductCode($productCode);

        $this->salesInvoice->producCode($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testProducCodeNotExists(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setProductCode("COD999");

        $this->salesInvoice->producCode($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testProducCodeNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();

        $this->salesInvoice->producCode($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTypeNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTypeIvaPercentageNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxAmount(999.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxAmountZeroExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxAmountZeroExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxPercentageZeroExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeIseExceptionReasonNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeIseExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeIsePercentageNotZero(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTableTaxEmpty(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxWrongTableTaxEntry(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeNoTaxCode(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeNoTaxCoountryRegion(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeNotExistInTable(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxCodeDateExpierd(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTaxExpirationDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTaxExpirationDateNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testTaxTaxIS(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::OUT());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS());
        $taxTableEntry->setDescription("Tax description");

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $line = $invoice->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->salesInvoice->tax($line, $invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsDocumentTotalsNotSetted(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongGross(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 122.99;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedGross(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal(123.00, 4));

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedGrossDelta(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross - 0.01, 4));

        $this->salesInvoice->setDeltaTotalDoc(0.01);

        $this->salesInvoice->totals($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedNet(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->salesInvoice->setNetTotal(new UDecimal($net - $delta, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedNetDelta(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->salesInvoice->setNetTotal(new UDecimal($net - $delta, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $this->salesInvoice->setDeltaTotalDoc($delta);

        $this->salesInvoice->totals($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedTaxPayable(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedTaxPayableDelta(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $this->salesInvoice->setDeltaTotalDoc($delta);

        $this->salesInvoice->totals($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedCurrency(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.02;
        $rate      = 0.5;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $invoice->setDocTotalcal($docTotalcal);

        $this->salesInvoice->totals($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotalsWrongCalculatedCurrencyDelta(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $invoice->setDocTotalcal($docTotalcal);

        $this->salesInvoice->setDeltaCurrency($delta);
        $this->salesInvoice->totals($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testTotals(): void
    {
        $auditFile = $this->salesInvoice->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $rate      = 0.5;

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $invoice->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount($gross / $rate);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->salesInvoice->setNetTotal(new UDecimal($net, 4));
        $this->salesInvoice->setTaxPayable(new UDecimal($tax, 4));
        $this->salesInvoice->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $invoice->setDocTotalcal($docTotalcal);

        $this->salesInvoice->totals($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
    public function testSignNoHash(): void
    {

        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->sign($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
    public function testSignNoHashSkip(): void
    {

        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->sign($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
    public function testSignSkip(): void
    {

        $auditFile = $this->salesInvoice->getAuditFile();

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(new RDate());
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setHash("AAA");

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->setSignValidation(false);
        $this->salesInvoice->sign($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
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

        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $invoice->setHash($hash);
        $this->salesInvoice->setLastHash("");
        $this->salesInvoice->sign($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
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

        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal()
        );

        $invoice->setHash($hash);
        $this->salesInvoice->setLastHash("");
        $this->salesInvoice->sign($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\SaftPt\Sign\SignException
	 * @author João Rebelo
	 * @test
	 */
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $invoice->setHash("a".\substr($hash, 0, 171));
        $this->salesInvoice->setLastHash("");
        $this->salesInvoice->sign($invoice);

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
	 * @test
	 */
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), ""
        );

        $invoice->setHash("a".\substr($hash, 0, 171));
        $this->salesInvoice->setLastHash("");
        $this->salesInvoice->sign($invoice);

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
	 * @test
	 */
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign      = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $lasetHash = "AAA";
        $hash      = $sign->createSignature(
            $invoice->getInvoiceDate(), $invoice->getSystemEntryDate(),
            $invoice->getInvoiceNo(),
            $invoice->getDocumentTotals()->getGrossTotal(), $lasetHash
        );

        $invoice->setHash("a".\substr($hash, 0, 171));
        $this->salesInvoice->setLastHash($lasetHash);
        $this->salesInvoice->sign($invoice);

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
	 * @test
	 */
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $firstInvoice  = $salesInvoices->addInvoice();
        $firstInvoice->setInvoiceDate(clone $now);
        $firstInvoice->setSystemEntryDate(clone $now);
        $firstInvoice->setInvoiceNo("FT FT/1");
        $firstInvoice->setInvoiceType(InvoiceType::FT());
        $firstInvoice->getDocumentTotals()->setGrossTotal(999.99);

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
        $secondInvoice->setInvoiceType(InvoiceType::FT());
        $secondInvoice->getDocumentTotals()->setGrossTotal(999.99);


        $docStatus = $secondInvoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $secondHash = $sign->createSignature(
            $secondInvoice->getInvoiceDate(),
            $secondInvoice->getSystemEntryDate(),
            $secondInvoice->getInvoiceNo(),
            $secondInvoice->getDocumentTotals()->getGrossTotal(), $firstHash
        );

        $secondInvoice->setHash($secondHash);

        $this->salesInvoice->setLastHash($firstHash);
        $this->salesInvoice->sign($secondInvoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($firstInvoice->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementAllNull(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->shipement($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementWrongInvoiceType(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FS FS/2");
        $invoice->setInvoiceType(InvoiceType::FS());
        $invoice->setMovementStartTime((clone $now)->addHours(1));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementMovementStartTimeNull(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $invoice->setMovementEndTime((clone $now)->addHours(1));

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementMovementStartTimeEarlierInvoiceDate(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate((clone $now)->addDays(-2));
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addDays(-1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementMovementStartTimeSystemEntryDate(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate((clone $now)->addDays(-2));
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addDays(-1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementMovementStartTimeLaterEndTime(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(9));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementNoShipFromAddress(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementNoShipFrom(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipFromNoStreetNameNoAddressDetail(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipFromEmptyStreetNameNoAddressDetail(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("");
        $fromAddr->setStreetName("");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipFromCityNotSetted(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipFromEmptyCity(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipFromCountryNotSetted(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToNoAddress(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToNoSetted(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToStreetNameAndAddrDetailEmpty(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("");
        $toAddr->setStreetName("");
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToStreetNameAndAddrDetailNull(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setCity("Rio de Mouro");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToCityNotSetted(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToCityEmpty(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipToCountryNotSetted(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");

        $this->salesInvoice->shipement($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipement(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));
        $invoice->setMovementEndTime((clone $now)->addHours(2));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testShipementNoEndTime(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());
        $invoice->setMovementStartTime((clone $now)->addHours(1));

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $from     = $invoice->getShipFrom();
        $from->setDeliveryDate(clone $now);
        $from->addDeliveryID("ID delivery");
        $fromAddr = $from->getAddress();
        $fromAddr->setAddressDetail("Rua das Escolas Gerais");
        $fromAddr->setCity("Lisboa");
        $fromAddr->setPostalCode("1100-999");
        $fromAddr->setCountry(Country::ISO_PT());

        $to     = $invoice->getShipTo();
        $to->addDeliveryID("Delivery ID");
        $to->getDeliveryDate(clone $now);
        $toAddr = $to->getAddress();
        $toAddr->setAddressDetail("Estrada Marquês de Pombal");
        $toAddr->setCity("Lisboa");
        $toAddr->setPostalCode("2635-302");
        $toAddr->setCountry(Country::ISO_PT());

        $this->salesInvoice->shipement($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateNoHeader(): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        $now           = new RDate();
        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->setLastDocDate((clone $now)->addDays(1));
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->setLastDocDate(clone $now);
        $this->salesInvoice->setLastSystemEntryDate((clone $now)->addSeconds(1));
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDateAllDatesEqual(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->setLastDocDate(clone $now);
        $this->salesInvoice->setLastSystemEntryDate(clone $now);
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @author João Rebelo
	 * @test
	 */
    public function testInvDateAndSyEntryDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /* @var $salesInvoices \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $docStatus = $invoice->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->salesInvoice->setLastDocDate((clone $now)->addDays(-1));
        $this->salesInvoice->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        $this->salesInvoice->invoiceDateAndSystemEntryDate($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testMultipleWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        for ($n = 0; $n <= 0; $n++) {
            $withholdingTax = $invoice->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(10.0);
        }

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithholdingTaxWithoutAmount(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        //$withholdingTax =
        $invoice->addWithholdingTax();
        //$withholdingTax->setWithholdingTaxAmount(10.0);

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithholdingTaxGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);


        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount($gross + 0.10);

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testMultipleWithholdingTaxGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $withholdingTax = $invoice->addWithholdingTax();
            $withholdingTax->setWithholdingTaxAmount(($gross / $nMax) + 0.1);
        }

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithholdingTaxGreaterThanHalfGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(($gross / 2) + 0.1);

        $this->salesInvoice->withholdingTax($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertNotEmpty($invoice->getWarning());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethod(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodWithWithholdingTax(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross - $withholdingTax->getWithholdingTaxAmount());
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithoutPaymentMethodNotFR(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertEmpty($invoice->getWarning());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testWithoutPaymentMethodFR(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FR FT/2");
        $invoice->setInvoiceType(InvoiceType::FR());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
        $this->assertNotEmpty($invoice->getWarning());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testMultiplePaymentMethod(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount($gross / $nMax);
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU());
        }

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodWithoutAmout(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        //$payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodWithoutDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        //$payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($payMeth->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodDiffGrossOnFR(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FR());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross - 1.00);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodGrossDiffPayMethWithholdingTaxOnFR(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FR FT/2");
        $invoice->setInvoiceType(InvoiceType::FR());

        $withholdingTax = $invoice->addWithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(10.0);

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodLessThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross - 10);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testMultiplePaymentMethodLessThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount(($gross / $nMax) * 0.9);
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU());
        }

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testPaymentMethodGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross + 10);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $payMeth = $totals->addPayment();
        $payMeth->setPaymentAmount($gross - 10);
        $payMeth->setPaymentDate(clone $now);
        $payMeth->setPaymentMechanism(PaymentMechanism::NU());

        $this->salesInvoice->payment($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Decimal\DecimalException
	 * @throws \Rebelo\Enum\EnumException
	 * @author João Rebelo
	 * @test
	 */
    public function testMultiplePaymentMethodGreaterThanGross(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->salesInvoice->getAuditFile();
        $now       = new RDate();

        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate(clone $now);
        $invoice->setSystemEntryDate(clone $now);
        $invoice->setInvoiceNo("FT FT/2");
        $invoice->setInvoiceType(InvoiceType::FT());

        $gross      = 123.00;
        $net        = 100.00;
        $taxPayable = 23.00;

        $totals = $invoice->getDocumentTotals();
        $totals->setGrossTotal($gross + 10);
        $totals->setNetTotal($net);
        $totals->setTaxPayable($taxPayable);

        $nMax = 2;
        for ($n = 1; $n <= $nMax; $n++) {
            $payMeth = $totals->addPayment();
            $payMeth->setPaymentAmount(($gross / $nMax) * 1.1);
            $payMeth->setPaymentDate(clone $now);
            $payMeth->setPaymentMechanism(PaymentMechanism::NU());
        }

        $this->salesInvoice->payment($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
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
     * @test
     * @return void
     */
    public function testSetConfigurationNoDefaults(): void
    {
        $config = new ValidationConfig();
        $config->setAllowDebitAndCredit(false);
        $config->setContinuesLines(false);
        $config->setDeltaCurrency(0.09);
        $config->setDeltaLine(0.04);
        $config->setDeltaTable(0.19);
        $config->setDeltaTotalDoc(0.29);
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
	 * @return array
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 */
    public function outOfDateInvoiceTypesInDateProvieder(): array
    {
        $inDateStack  = [
            RDate::parse(RDate::SQL_DATE, "2012-12-31"), // Last valid day
            RDate::parse(RDate::SQL_DATE, "2012-10-05")
        ];
        $outDateTypes = [
            InvoiceType::VD(),
            InvoiceType::TV(),
            InvoiceType::TD(),
            InvoiceType::AA(),
            InvoiceType::DA()
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
	 * @param \Rebelo\Date\Date $date
	 * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $type
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 * @test
	 * @dataProvider outOfDateInvoiceTypesInDateProvieder
	 */
    public function testOutOfDateInvoiceTypesInDate(RDate $date,
                                                    InvoiceType $type): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate($date);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType($type);

        $this->salesInvoice->outOfDateInvoiceTypes($invoice);

        $this->assertTrue($this->salesInvoice->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($invoice->getError());
    }

	/**
	 * @return array
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 */
    public function outOfDateInvoiceTypesOutDateProvieder(): array
    {
        $inDateStack  = [
            RDate::parse(RDate::SQL_DATE, "2013-01-01"), // First invalid day
            RDate::parse(RDate::SQL_DATE, "2014-10-05")
        ];
        $outDateTypes = [
            InvoiceType::VD(),
            InvoiceType::TV(),
            InvoiceType::TD(),
            InvoiceType::AA(),
            InvoiceType::DA()
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
	 * @param \Rebelo\Date\Date $date
	 * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $type
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @author João Rebelo
	 * @test
	 * @dataProvider outOfDateInvoiceTypesOutDateProvieder
	 */
    public function testOutOfDateInvoiceTypesOutDate(RDate $date,
                                                     InvoiceType $type): void
    {
        $auditFile     = $this->salesInvoice->getAuditFile();
        /* @var $invoice \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice */
        $salesInvoices = $auditFile->getSourceDocuments()->getSalesInvoices();
        $invoice       = $salesInvoices->addInvoice();
        $invoice->setInvoiceDate($date);
        $invoice->setInvoiceNo("FT FT/1");
        $invoice->setInvoiceType($type);

        $this->salesInvoice->outOfDateInvoiceTypes($invoice);

        $this->assertFalse($this->salesInvoice->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($invoice->getError());
    }
}
