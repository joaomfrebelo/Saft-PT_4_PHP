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

use Rebelo\SaftPt\Validate\WorkingDocuments;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\Decimal\UDecimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\Validate\DocTotalCalc;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\AuditFile;

/**
 * Class WorkingDocumentsTest
 *
 * @author João Rebelo
 */
class WorkingDocumentsTest extends AWorkingDocumentsBase
{

    protected function setUp(): void
    {
        $this->workingDocumentsFactory();
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WorkingDocuments::class);
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalDebit($debit);

        $this->workingDocuments->setDebit(
            new UDecimal($debit, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->totalDebit();

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalDebit($debit);

        $this->workingDocuments->setDebit(
            (new UDecimal($debit, WorkingDocuments::CALC_PRECISION))->plus(0.09)
        );

        $this->workingDocuments->totalDebit();

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalDebit($debit);

        $this->workingDocuments->setDebit(
            (new UDecimal($debit, WorkingDocuments::CALC_PRECISION))->subtract(0.09)
        );

        $this->workingDocuments->totalDebit();

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalCredit($credit);

        $this->workingDocuments->setCredit(
            new UDecimal($credit, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->totalCredit();

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalCredit($credit);

        $this->workingDocuments->setCredit(
            (new UDecimal($credit, WorkingDocuments::CALC_PRECISION))->plus(0.09)
        );

        $this->workingDocuments->totalCredit();

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()->getWorkingDocuments()
            ->setTotalCredit($credit);

        $this->workingDocuments->setCredit(
            (new UDecimal($credit, WorkingDocuments::CALC_PRECISION))->subtract(0.09)
        );

        $this->workingDocuments->totalCredit();

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo@author João Rebelo
     * @depends testWorkDoc
     * @depends testNumberOfEntries
     * @depends testTotalDebit
     * @depends testTotalCredit
     * @depends testReferncesOneReference
     * @depends testOrderReferencesOneOrderReference
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

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $this->workingDocuments->setAuditFile($auditFile);
        $this->workingDocuments->setDeltaLine(0.005);
        $this->workingDocuments->setDeltaCurrency(0.005);
        $this->workingDocuments->setDeltaTable(0.005);
        $this->workingDocuments->setDeltaTotalDoc(0.005);

        $valide = $this->workingDocuments->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo@author João Rebelo
     * @depends testWorkDoc
     * @depends testNumberOfEntries
     * @depends testTotalDebit
     * @depends testTotalCredit
     * @depends testReferncesOneReference
     * @depends testOrderReferencesOneOrderReference
     * @return void
     */
    public function testValidateMissingDoc(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_WORKING_DOC);
        if ($xml === false) {
            $this->fail(\sprintf("Failling load file '%s'", SAFT_MISSING_WORKING_DOC));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $this->workingDocuments->setAuditFile($auditFile);
        $this->workingDocuments->setDeltaLine(0.005);
        $this->workingDocuments->setDeltaCurrency(0.005);
        $this->workingDocuments->setDeltaTable(0.005);
        $this->workingDocuments->setDeltaTotalDoc(0.005);

        $valide = $this->workingDocuments->validate();
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
        $this->workingDocuments->setAuditFile($auditFile);

        $valide = $this->workingDocuments->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoicesCreditNotZero(): void
    {

        $auditFile   = new AuditFile();
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workingDocs->setTotalCredit(999.09);
        $workingDocs->setTotalDebit(0.0);
        $workingDocs->setNumberOfEntries(0);

        $this->workingDocuments->setAuditFile($auditFile);

        $valide = $this->workingDocuments->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoicesDebitNotZero(): void
    {

        $auditFile   = new AuditFile();
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workingDocs->setTotalCredit(0.0);
        $workingDocs->setTotalDebit(999.0);
        $workingDocs->setNumberOfEntries(0);

        $this->workingDocuments->setAuditFile($auditFile);

        $valide = $this->workingDocuments->validate();
        $this->assertFalse($valide);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDoc(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocTypeOutDate(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::DC());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertNotEmpty($workDoc->getError());
    }





    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocWrohgSign(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocWrohgDate(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocWrongCustomerID(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID()."A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocNoDocStatus(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocNoLines(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        //$this->iniInvoiceLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocWrongTotals(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf() + 1);

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getDocumentTotals()->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocDebit(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("OU OU/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc, true);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line */
            $netValue->plusThis($line->getDebitAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getDebitAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @return void
     */
    public function testWorkDocWrongSign(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $taxPayable = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, WorkingDocuments::CALC_PRECISION);

        foreach ($workDoc->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(), $docTotals->getGrossTotal(), "a"
        );

        $workDoc->setHash($hash);
        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testWorkDocNoInvoiceNo(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        //$workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testWorkDocNoInvoiceType(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        //$workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testWorkDocNoInvoiceDate(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        //$workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testCustomerId
     * @depends testLines
     * @test
     * @return void
     */
    public function testWorkDocNoInvoiceSystemEntryDate(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        //$workDoc->setSystemEntryDate(clone $now);

        $this->workingDocuments->workDocument($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @depends testWorkDoc
     * @test
     * @return void
     */
    public function testNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $workingDocs->addWorkDocument();
        }

        $workingDocs->setNumberOfEntries($nMax);

        $this->workingDocuments->numberOfEntries();
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertSame(
            $nMax, $workingDocs->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testWrongNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $workingDocs->addWorkDocument();
        }

        $workingDocs->setNumberOfEntries($nMax + 1);

        $this->workingDocuments->numberOfEntries();
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertSame(
            $nMax, $workingDocs->getDocTableTotalCalc()->getNumberOfEntries()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workingDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatus(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->workingDocuments->documentStatus($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusNotDefined(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        $this->workingDocuments->documentStatus($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_DOCUMENTSTATUS,
            \array_key_first($workDoc->getError())
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N());
        $docStatus->setWorkStatusDate(
            RDate::parse(RDate::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->workingDocuments->documentStatus($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_WORKSTATUSDATE,
            \array_key_first($workDoc->getError())
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::A());
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        $this->workingDocuments->documentStatus($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::A());
        $docStatus->setWorkStatusDate(new RDate());
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->workingDocuments->documentStatus($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($workDoc->getError())
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
        $auditFile  = $this->workingDocuments->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setCustomerID($customerID);

        $this->workingDocuments->customerId($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdCustomerNotExist(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setCustomerID("A999");

        $this->workingDocuments->customerId($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            WorkDocument::N_CUSTOMERID, \array_key_first($workDoc->getError())
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");

        $this->workingDocuments->customerId($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            WorkDocument::N_CUSTOMERID, \array_key_first($workDoc->getError())
        );
    }

    /**
     * Init variables
     * @return void
     */
    public function iniWorkDocForLineTest(): void
    {
        $this->workingDocuments->setNetTotal(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setGrossTotal(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setTaxPayable(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setDocCredit(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setDocDebit(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setCredit(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );

        $this->workingDocuments->setDebit(
            new UDecimal(0.0, WorkingDocuments::CALC_PRECISION)
        );
    }

    /**
     *
     * @param WorkDocument $workDoc
     * @param bool $debit The line are to be debit
     * @return void
     */
    public function iniWorkDocLinesForLinesTest(WorkDocument $workDoc,
                                                bool $debit = false): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->workingDocuments->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        for ($n = 1; $n <= 9; $n++) {
            $line = $workDoc->addLine();
            $line->setQuantity($n);
            $line->setUnitPrice($n * 1.2);

            $debit ? $line->setDebitAmount($n * $n * 1.2) :
                    $line->setCreditAmount($n * $n * 1.2);

            $line->setDescription("Desc of line ".\strval($n));
            $line->setProductCode("CODE_".\strval($n));
            $line->setProductDescription("Prod desc of line ".\strval($n));
            $line->setSettlementAmount(.1 * $n);
            $line->setTaxPointDate(clone $workDoc->getWorkDate());
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
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->workingDocuments->setContinuesLines(true);
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] */
        $lineStack = $workDoc->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $this->workingDocuments->setContinuesLines(false);
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());

        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());

        $this->iniWorkDocLinesForLinesTest($workDoc);


        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] */
        $lineStack = $workDoc->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoQuantitySetted(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoUnitPriceSetted(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoCreditAndDebitSetted(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWithTaxBaseAndUnitPriceGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount(0.0); // Zero to test failure with TaxBase
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWithTaxBaseAndCreditAmountGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice(0.0); // Zero to test failure with TaxBase
        $line->setCreditAmount(9.49);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWrongQtUnitPriceDebitAmount(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setDebitAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWrongQtUnitPriceCreditAmount(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $this->workingDocuments->lines($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(0.0);
        $line->setUnitPrice(0.0);
        $line->setCreditAmount(0.0);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(999.09);

        $this->workingDocuments->lines($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWithAllowDebitAndCreditSameAnulationValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        /* @var $lastLine \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($lastLine->getCreditAmount());
        $line->setDescription("Anulation of line ".\strval($n));
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->workingDocuments->lines($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWithAllowDebitAndCreditLessAnulationQAndtValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        /* @var $lastLine \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity() / 2);
        $line->setUnitPrice($lastLine->getUnitPrice() / 2);
        $line->setDebitAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ".\strval($n));
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->workingDocuments->lines($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesWithAllowDebitAndCreditLessAnulationQtAndValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("NC NC/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setDocTotalcal(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        /* @var $lastLine \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity() / 2);
        $line->setUnitPrice($lastLine->getUnitPrice() / 2);
        $line->setCreditAmount($line->getQuantity() * $line->getUnitPrice());
        $line->setDescription("Anulation of line ".\strval($n));
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        $this->workingDocuments->lines($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesOneReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line = $workDoc->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FO FO/1");

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesMultipleReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("ND A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("Some reason");
        $ref_1->setReference("FO FO/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("Some other reason");
        $ref_2->setReference("FO FO/3");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("Some other other reason");
        $ref_3->setReference("FO FO/9");

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesMultipleReferenceOneReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("Some reason");
        $ref_1->setReference("FO FO/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("Some other reason");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("Some other other reason");

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesMultipleReferenceNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReference("FO FO/1");

        $ref_2 = $line->addReferences();
        $ref_2->setReference("FO FO/3");

        $ref_3 = $line->addReferences();
        $ref_3->setReference("FO FO/9");

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesMultipleReferenceNoReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addReferences();
        $ref_1->setReason("AAAAAA");

        $ref_2 = $line->addReferences();
        $ref_2->setReason("BBBBB");

        $ref_3 = $line->addReferences();
        $ref_3->setReason("CCCCCCC");

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReferncesMultipleReferenceNoReferenceNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addReferences();
        $ref_2 = $line->addReferences();
        $ref_3 = $line->addReferences();

        $this->workingDocuments->refernces($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesOneOrderReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $workDoc->getWorkDate());
        $ref->setOriginatingON("GT A/1");

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesMultipleReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("CM A/1");
        $workDoc->setWorkType(WorkType::FO());

        $line  = $workDoc->addLine();
        $ref_1 = $line->addOrderReferences();
        $ref_1->setOrderDate(clone $workDoc->getWorkDate());
        $ref_1->setOriginatingON("OU A/1");

        $ref_2 = $line->addOrderReferences();
        $ref_2->setOrderDate((clone $workDoc->getWorkDate())->addDays(-1));
        $ref_2->setOriginatingON("FO A/2");

        $ref_3 = $line->addOrderReferences();
        $ref_3->setOrderDate(clone $workDoc->getWorkDate());
        $ref_3->setOriginatingON("GT A/3");

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref_1->getError());
        $this->assertEmpty($ref_2->getError());
        $this->assertEmpty($ref_3->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesNoDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N());
        $workDoc->getDocumentStatus()->setSourceBilling(SourceBilling::P());


        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("CM CM/1");

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesNoOriginateOn(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FO FO/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferences(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FO FO/1");
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesWrongOriginatingOn(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
        $this->assertNotEmpty($ref->getWarning());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testProducCodeExists(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setProductCode($productCode);

        $this->workingDocuments->producCode($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testProducCodeNotExists(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setProductCode("COD999");

        $this->workingDocuments->producCode($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testProducCodeNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();

        $this->workingDocuments->producCode($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxNotSetted(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxAmount(999.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxAmount(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        // The percentage is no set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is no set to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(TaxCode::ISE());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::NOR());
        $tax->setTaxType(TaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::OUT());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS());
        $taxTableEntry->setDescription("Tax description");

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(TaxCode::OUT());
        $tax->setTaxType(TaxType::IS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->workingDocuments->tax($line, $workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongGross(): void
    {
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 122.99;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal(123.00, 4));

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross - 0.01, 4));

        $this->workingDocuments->setDeltaTotalDoc(0.01);

        $this->workingDocuments->totals($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->workingDocuments->setNetTotal(new UDecimal($net - $delta, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->workingDocuments->setNetTotal(new UDecimal($net - $delta, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $this->workingDocuments->setDeltaTotalDoc($delta);

        $this->workingDocuments->totals($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $this->workingDocuments->setDeltaTotalDoc($delta);

        $this->workingDocuments->totals($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.02;
        $rate      = 0.5;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $workDoc->setDocTotalcal($docTotalcal);

        $this->workingDocuments->totals($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $workDoc->setDocTotalcal($docTotalcal);

        $this->workingDocuments->setDeltaCurrency($delta);
        $this->workingDocuments->totals($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $rate      = 0.5;

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount($gross / $rate);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->workingDocuments->setNetTotal(new UDecimal($net, 4));
        $this->workingDocuments->setTaxPayable(new UDecimal($tax, 4));
        $this->workingDocuments->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $workDoc->setDocTotalcal($docTotalcal);

        $this->workingDocuments->totals($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSignNoHash(): void
    {

        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->sign($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSignNoHashSkip(): void
    {

        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setSignValidation(false);
        $this->workingDocuments->sign($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSignSkip(): void
    {

        $auditFile = $this->workingDocuments->getAuditFile();

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->setHash("AAA");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setSignValidation(false);
        $this->workingDocuments->sign($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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

        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(), ""
        );

        $workDoc->setHash($hash);
        $this->workingDocuments->setLastHash("");
        $this->workingDocuments->sign($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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

        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal()
        );

        $workDoc->setHash($hash);
        $this->workingDocuments->setLastHash("");
        $this->workingDocuments->sign($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(), ""
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        $this->workingDocuments->setLastHash("");
        $this->workingDocuments->sign($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(), ""
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        $this->workingDocuments->setLastHash("");
        $this->workingDocuments->sign($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());

        $this->assertNotEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());
        $workDoc->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign      = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $lasetHash = "AAA";
        $hash      = $sign->createSignature(
            $workDoc->getWorkDate(), $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(), $lasetHash
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        $this->workingDocuments->setLastHash($lasetHash);
        $this->workingDocuments->sign($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
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
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $firstDoc    = $workingDocs->addWorkDocument();
        $firstDoc->setWorkDate(clone $now);
        $firstDoc->setSystemEntryDate(clone $now);
        $firstDoc->setDocumentNumber("FO FO/1");
        $firstDoc->setWorkType(WorkType::FO());
        $firstDoc->getDocumentTotals()->setGrossTotal(999.99);

        $firstHash = $sign->createSignature(
            $firstDoc->getWorkDate(), $firstDoc->getSystemEntryDate(),
            $firstDoc->getDocumentNumber(),
            $firstDoc->getDocumentTotals()->getGrossTotal(), ""
        );

        $firstDoc->setHash($firstHash);

        $secondDoc = $workingDocs->addWorkDocument();
        $secondDoc->setWorkDate(clone $now);
        $secondDoc->setSystemEntryDate(clone $now);
        $secondDoc->setDocumentNumber("FO FO/2");
        $secondDoc->setWorkType(WorkType::FO());
        $secondDoc->getDocumentTotals()->setGrossTotal(999.99);


        $docStatus = $secondDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $secondHash = $sign->createSignature(
            $secondDoc->getWorkDate(), $secondDoc->getSystemEntryDate(),
            $secondDoc->getDocumentNumber(),
            $secondDoc->getDocumentTotals()->getGrossTotal(), $firstHash
        );

        $secondDoc->setHash($secondHash);

        $this->workingDocuments->setLastHash($firstHash);
        $this->workingDocuments->sign($secondDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($firstDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateNoHeader(): void
    {
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setLastDocDate((clone $now)->addDays(1));
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setLastDocDate(clone $now);
        $this->workingDocuments->setLastSystemEntryDate((clone $now)->addSeconds(1));
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateAllDatesEqual(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setLastDocDate(clone $now);
        $this->workingDocuments->setLastSystemEntryDate(clone $now);
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO());

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->workingDocuments->setLastDocDate((clone $now)->addDays(-1));
        $this->workingDocuments->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @return array
     */
    public function outOfDateWorkTypesInDateProvieder(): array
    {
        $inDateStack  = [
            RDate::parse(RDate::SQL_DATE, "2017-06-30"), // Last valid day
            RDate::parse(RDate::SQL_DATE, "2015-10-05")
        ];
        $outDateTypes = [
            WorkType::DC()
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
     *
     * @param RDate $date
     * @param WorkType $type@author João Rebelo
     * @test
     * @dataProvider outOfDateWorkTypesInDateProvieder
     * @return void
     */
    public function testOutOfDateWorkTypesInDate(RDate $date, WorkType $type): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile        = $this->workingDocuments->getAuditFile();
        /* @var $workDoc \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
        $workingDocuments = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc          = $workingDocuments->addWorkDocument();
        $workDoc->setWorkDate($date);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType($type);

        $this->workingDocuments->outOfDateInvoiceTypes($workDoc);

        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @author João Rebelo
     * @return array
     */
    public function outOfDateWorkTypesOutDateProvieder(): array
    {
        $inDateStack  = [
            RDate::parse(RDate::SQL_DATE, "2017-07-01"), // First invalid day
            RDate::parse(RDate::SQL_DATE, "2017-10-05")
        ];
        $outDateTypes = [
            WorkType::DC()
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
     *
     * @param RDate $date
     * @param WorkType $type
     * @author João Rebelo
     * @test
     * @dataProvider outOfDateWorkTypesOutDateProvieder
     * @return void
     */
    public function testOutOfDateWorkTypesOutDate(RDate $date, WorkType $type): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile        = $this->workingDocuments->getAuditFile();
        /* @var $workDoc \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
        $workingDocuments = $auditFile->getSourceDocuments()->getWorkingDocuments();
        $workDoc          = $workingDocuments->addWorkDocument();
        $workDoc->setWorkDate($date);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType($type);

        $this->workingDocuments->outOfDateInvoiceTypes($workDoc);

        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }
}
