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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\Sign\Sign;

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
     * @return void
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(WorkingDocuments::class))->testReflection(WorkingDocuments::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTotalDebit(): void
    {
        $debit     = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setDebit($debit);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        $debit     = new Decimal("909.99");
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()->setTotalDebit($debit);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setDebit($debit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        $debit     = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()?->setTotalDebit($debit);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setDebit($debit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalDebit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCredit(): void
    {
        $credit    = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setCredit($credit);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCreditGreaterDeltaZero(): void
    {
        $credit    = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setCredit($credit->add("0.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTotalCreditLowerDeltaZero(): void
    {
        $credit    = new Decimal("909.99");
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $auditFile->getSourceDocuments()?->getWorkingDocuments()?->setTotalCredit($credit);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setCredit($credit->sub("0.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->totalCredit();

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     */
    #[Test]
    #[Depends('testOrderReferencesOneOrderReference')]
    #[Depends('testReferencesOneReference')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testWorkDoc')]
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
        $this->workingDocuments->setAuditFile($auditFile);
        $this->workingDocuments->setDeltaLine(new Decimal("0.005"));
        $this->workingDocuments->setDeltaCurrency(new Decimal("0.005"));
        $this->workingDocuments->setDeltaTable(new Decimal("0.005"));
        $this->workingDocuments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->workingDocuments->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     */
    #[Test]
    #[Depends('testOrderReferencesOneOrderReference')]
    #[Depends('testReferencesOneReference')]
    #[Depends('testTotalCredit')]
    #[Depends('testTotalDebit')]
    #[Depends('testNumberOfEntries')]
    #[Depends('testWorkDoc')]
    public function testValidateMissingDoc(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_WORKING_DOC);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_MISSING_WORKING_DOC));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setAuditFile($auditFile);
        $this->workingDocuments->setDeltaLine(new Decimal("0.005"));
        $this->workingDocuments->setDeltaCurrency(new Decimal("0.005"));
        $this->workingDocuments->setDeltaTable(new Decimal("0.005"));
        $this->workingDocuments->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->workingDocuments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function validateNoInvoices(): void
    {

        $auditFile = new AuditFile();
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setAuditFile($auditFile);

        $valid = $this->workingDocuments->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testValidateNoInvoicesCreditNotZero(): void
    {

        $auditFile   = new AuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workingDocs->setTotalCredit(new Decimal("999.09"));
        $workingDocs->setTotalDebit(new Decimal("0.0"));
        $workingDocs->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setAuditFile($auditFile);

        $valid = $this->workingDocuments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testValidateNoInvoicesDebitNotZero(): void
    {

        $auditFile   = new AuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workingDocs->setTotalCredit(new Decimal("0.0"));
        $workingDocs->setTotalDebit(new Decimal("999.0"));
        $workingDocs->setNumberOfEntries(0);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setAuditFile($auditFile);

        $valid = $this->workingDocuments->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
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
    public function testWorkDoc(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
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

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocTypeOutDate(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::DC);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertNotEmpty($workDoc->getError());
    }


    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocWrongSign(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add($line->getCreditAmount());
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocWrongDate(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocWrongCustomerID(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add($line->getCreditAmount());
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
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

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocNoDocStatus(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
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

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocNoLines(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        //$this->iniInvoiceLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
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

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocWrongTotals(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable)->add(1));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $docTotals->getGrossTotal()
        );

        $workDoc->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($workDoc->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getDocumentTotals()->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocDebit(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $header      = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("OU OU/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc, true);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getDebitAmount() ?? throw new \Exception("Debit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getDebitAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
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

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testCustomerId')]
    #[Depends('testDocumentStatus')]
    public function testWorkDocWrongSign2(): void
    {
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($workDoc->getLine() as $line) {
            $netValue = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            /** @var Decimal $taxPerc */
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $workDoc->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $docTotals->getGrossTotal(),
            "a"
        );

        $workDoc->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
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
    public function testWorkDocNoInvoiceNo(): void
    {
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        //$workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
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
    public function testWorkDocNoInvoiceType(): void
    {
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        //$workDoc->setWorkType(WorkType::FO());
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
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
    public function testWorkDocNoInvoiceDate(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        //$workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        $workDoc->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
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
    public function testWorkDocNoInvoiceSystemEntryDate(): void
    {
        $now         = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setAtcud("0");
        $workDoc->setCustomerID("CODE_A");
        $workDoc->setHashControl("1");
        $workDoc->setPeriod((int) $now->format(Pattern::MONTH_SHORT));
        $workDoc->setSourceID("Rebelo");
        //$workDoc->setSystemEntryDate(clone $now);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->workDocument($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testWorkDoc')]
    public function testNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $workingDocs?->addWorkDocument();
        }

        $workingDocs?->setNumberOfEntries($nMax);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->numberOfEntries();
        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertSame(
            $nMax, $workingDocs?->getDocTableTotalCalc()?->getNumberOfEntries()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @return void
     * @author João Rebelo
     */
    #[Test]
    public function testWrongNumberOfEntries(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $workingDocs?->addWorkDocument();
        }

        $workingDocs?->setNumberOfEntries($nMax + 1);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->numberOfEntries();
        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertSame(
            $nMax, $workingDocs?->getDocTableTotalCalc()?->getNumberOfEntries()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workingDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatus(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->documentStatus($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusNotDefined(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->documentStatus($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_DOCUMENT_STATUS,
            \array_key_first($workDoc->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocumentStatusStatusDateEarlier(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::N);
        $docStatus->setWorkStatusDate(
            RDate::parse(Pattern::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->documentStatus($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_WORK_STATUS_DATE,
            \array_key_first($workDoc->getError())
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $now         = new RDate();
        $workDoc->setWorkDate($now);
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::A);
        $docStatus->setWorkStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->documentStatus($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setWorkStatus(WorkStatus::A);
        $docStatus->setWorkStatusDate(new RDate());
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->documentStatus($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($workDoc->getError())
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
        $auditFile  = $this->workingDocuments->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setCustomerID($customerID);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->customerId($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setCustomerID("A999");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->customerId($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            WorkDocument::N_CUSTOMER_ID, \array_key_first($workDoc->getError())
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
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->customerId($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workingDocs->getError());
        $this->assertSame(
            WorkDocument::N_CUSTOMER_ID, \array_key_first($workDoc->getError())
        );
    }

    /**
     * Init variables
     *
     */
    public function iniWorkDocForLineTest(): void
    {
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setDocCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setDocDebit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setCredit(new Decimal("0.0"));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setDebit(new Decimal("0.0"));
    }

    /**
     *
     * @param WorkDocument $workDoc
     * @param bool $debit The line are to be debited
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function iniWorkDocLinesForLinesTest(WorkDocument $workDoc, bool $debit = false): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile     = $this->workingDocuments->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        for ($n = 1; $n <= 9; $n++) {
            $line = $workDoc->addLine();
            $line->setQuantity(new Decimal((string)$n));
            $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));

            $debit ? $line->setDebitAmount((new Decimal((string)$n))->mul($n)->mul("1.2")) :
                    $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));

            $line->setDescription("Desc of line ". $n);
            $line->setProductCode("CODE_". $n);
            $line->setProductDescription("Prod desc of line ". $n);
            $line->setSettlementAmount((new Decimal(".1"))->mul($n));
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
            $prod->setProductType(ProductType::P);
        }
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->workingDocuments->setContinuesLines(true);
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $lineStack = $workDoc->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    #[Test]
    public function testLinesRepeatedLineNumber(): void
    {

        $now = new RDate();
        $this->workingDocuments->setContinuesLines(false);
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());

        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);

        $this->iniWorkDocLinesForLinesTest($workDoc);


        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] */
        $lineStack = $workDoc->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoQuantitySet(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoUnitPriceSet(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal((string)$n));
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.2"));
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoCreditAndDebitSet(): void
    {

        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithTaxBaseAndUnitPriceGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount(new Decimal("0.0")); // Zero to test failure with TaxBase
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithTaxBaseAndCreditAmountGreaterThanZero(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice(new Decimal("0.0")); // Zero to test failure with TaxBase
        $line->setCreditAmount(new Decimal("9.49"));
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceDebitAmount(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal($n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setDebitAmount((new Decimal((string)$n))->mul($n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceCreditAmount(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul($n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniWorkDocForLineTest();

        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n    = \count($workDoc->getLine());
        $line = $workDoc->addLine();
        $line->setQuantity(new Decimal("0.0"));
        $line->setUnitPrice(new Decimal("0.0"));
        $line->setCreditAmount(new Decimal("0.0"));
        $line->setDescription("Desc of line ". $n);
        $line->setProductCode("CODE_". $n);
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount((new Decimal(".1"))->mul($n));
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $line->setTaxBase(new Decimal("999.09"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditSameCancellationValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity());
        $line->setUnitPrice($lastLine->getUnitPrice());
        $line->setDebitAmount($lastLine->getCreditAmount());
        $line->setDescription("Cancellation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditLessCancellationQAndValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $lastLine */
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity()->div(2));
        $line->setUnitPrice($lastLine->getUnitPrice()->div(2));
        $line->setDebitAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     *
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWithAllowDebitAndCreditLessCancellationQtAndValue(): void
    {
        $now         = new RDate();
        $this->iniWorkDocForLineTest();
        $this->workingDocuments->setAllowDebitAndCredit(true);
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile   = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setDocumentNumber("NC NC/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setDocTotalCalc(new DocTotalCalc());
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $this->iniWorkDocLinesForLinesTest($workDoc);

        $n        = \count($workDoc->getLine()) - 1;
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $lastLine */
        $lastLine = $workDoc->getLine()[$n];
        $line     = $workDoc->addLine();
        $line->setQuantity($lastLine->getQuantity()->div(2));
        $line->setUnitPrice($lastLine->getUnitPrice()->div(2));
        $line->setCreditAmount($line->getQuantity()->mul($line->getUnitPrice()));
        $line->setDescription("Cancellation of line ". $n);
        $line->setProductCode($lastLine->getProductCode());
        $line->setProductDescription("Prod desc of line ". $n);
        $line->setSettlementAmount($lastLine->getSettlementAmount());
        $line->setTaxPointDate(clone $workDoc->getWorkDate());
        $line->setUnitOfMeasure("UN");

        $lastTax = $lastLine->getTax();
        $tax     = $line->getTax();
        $tax->setTaxCode($lastTax->getTaxCode());
        $tax->setTaxCountryRegion($lastTax->getTaxCountryRegion());
        $tax->setTaxPercentage($lastTax->getTaxPercentage());
        $tax->setTaxType($lastTax->getTaxType());

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->lines($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line = $workDoc->addLine();
        $ref  = $line->addReferences();
        $ref->setReason("Some reason");
        $ref->setReference("FO FO/1");

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("ND A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line  = $workDoc->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("Some reason");
        $ref1->setReference("FO FO/1");

        $ref2 = $line->addReferences();
        $ref2->setReason("Some other reason");
        $ref2->setReference("FO FO/3");

        $ref3 = $line->addReferences();
        $ref3->setReason("Some other other reason");
        $ref3->setReference("FO FO/9");

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line  = $workDoc->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("Some reason");
        $ref1->setReference("FO FO/1");

        $ref2 = $line->addReferences();
        $ref2->setReason("Some other reason");

        $ref3 = $line->addReferences();
        $ref3->setReason("Some other other reason");

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line  = $workDoc->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReference("FO FO/1");

        $ref2 = $line->addReferences();
        $ref2->setReference("FO FO/3");

        $ref3 = $line->addReferences();
        $ref3->setReference("FO FO/9");

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line  = $workDoc->addLine();
        $ref1 = $line->addReferences();
        $ref1->setReason("AAAAAA");

        $ref2 = $line->addReferences();
        $ref2->setReason("BBBBB");

        $ref3 = $line->addReferences();
        $ref3->setReason("CCCCCCC");

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::PF);

        $line  = $workDoc->addLine();
        $ref1 = $line->addReferences();
        $ref2 = $line->addReferences();
        $ref3 = $line->addReferences();

        $this->workingDocuments->references($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
    public function testOrderReferencesOneOrderReference(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $workDoc->getWorkDate());
        $ref->setOriginatingON("GT A/1");

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testOrderReferencesMultipleReference(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("CM A/1");
        $workDoc->setWorkType(WorkType::FO);

        $line  = $workDoc->addLine();
        $ref1 = $line->addOrderReferences();
        $ref1->setOrderDate(clone $workDoc->getWorkDate());
        $ref1->setOriginatingON("OU A/1");

        $ref2 = $line->addOrderReferences();
        $ref2->setOrderDate((clone $workDoc->getWorkDate())->addDays(-1));
        $ref2->setOriginatingON("FO A/2");

        $ref3 = $line->addOrderReferences();
        $ref3->setOrderDate(clone $workDoc->getWorkDate());
        $ref3->setOriginatingON("GT A/3");

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentStatus()->setWorkStatus(WorkStatus::N);
        $workDoc->getDocumentStatus()->setSourceBilling(SourceBilling::P);


        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("CM CM/1");

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testOrderReferencesDateLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("OU A/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FO FO/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($ref->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferences(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO A/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("FO FO/1");
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesWrongOriginatingOn(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $workDoc->getWorkDate());

        $this->workingDocuments->orderReferences($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setProductCode($productCode);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->productCode($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setProductCode("COD999");

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->productCode($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->productCode($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxAmount(new Decimal("999.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
         $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxAmount(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax();
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(TaxCode::ISE);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testTaxCodeDateExpired(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testTaxTaxExpirationDateLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::NOR);
        $tax->setTaxType(TaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::OUT);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IS);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $line = $workDoc->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(TaxCode::OUT);
        $tax->setTaxType(TaxType::IS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->tax($line, $workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     */
    #[Test]
    public function testTotalsDocumentTotalsNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("122.99");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal(new Decimal("123.00"));

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGrossDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross->sub("0.01"));

        $this->workingDocuments->setDeltaTotalDoc(new Decimal("0.01"));

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNetDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        $this->workingDocuments->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayable(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayableDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        $this->workingDocuments->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     *
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedCurrency(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.02");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $workDoc->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     *
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedCurrencyDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->workingDocuments->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $workDoc->setDocTotalCalc($docTotalCal);

        $this->workingDocuments->setDeltaCurrency($delta);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     *
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotals(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals   = $workDoc->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setNetTotal($net);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setTaxPayable($tax);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $workDoc->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->totals($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     *
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignNoHash(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     *
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignNoHashSkip(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $this->workingDocuments->setSignValidation(false);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     *
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     */
    #[Test]
    public function testSignSkip(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(new RDate());
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->setHash("AAA");

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $this->workingDocuments->setSignValidation(false);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
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
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(),
            ""
        );

        $workDoc->setHash($hash);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash("");
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
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
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal()
        );

        $workDoc->setHash($hash);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash("");
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(),
            ""
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash("");
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\SaftPt\Sign\SignException
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(),
            ""
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash("");
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());

        $this->assertNotEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     *
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
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);
        $workDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign      = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $latestHash = "AAA";
        $hash      = $sign->createSignature(
            $workDoc->getWorkDate(),
            $workDoc->getSystemEntryDate(),
            $workDoc->getDocumentNumber(),
            $workDoc->getDocumentTotals()->getGrossTotal(),
            $latestHash
        );

        $workDoc->setHash("a".\substr($hash, 0, 171));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash($latestHash);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     *
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

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /* @var $workingDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $firstDoc    = $workingDocs->addWorkDocument();
        $firstDoc->setWorkDate(clone $now);
        $firstDoc->setSystemEntryDate(clone $now);
        $firstDoc->setDocumentNumber("FO FO/1");
        $firstDoc->setWorkType(WorkType::FO);
        $firstDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $firstHash = $sign->createSignature(
            $firstDoc->getWorkDate(),
            $firstDoc->getSystemEntryDate(),
            $firstDoc->getDocumentNumber(),
            $firstDoc->getDocumentTotals()->getGrossTotal(),
            ""
        );

        $firstDoc->setHash($firstHash);

        $secondDoc = $workingDocs->addWorkDocument();
        $secondDoc->setWorkDate(clone $now);
        $secondDoc->setSystemEntryDate(clone $now);
        $secondDoc->setDocumentNumber("FO FO/2");
        $secondDoc->setWorkType(WorkType::FO);
        $secondDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));


        $docStatus = $secondDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $secondHash = $sign->createSignature(
            $secondDoc->getWorkDate(),
            $secondDoc->getSystemEntryDate(),
            $secondDoc->getDocumentNumber(),
            $secondDoc->getDocumentTotals()->getGrossTotal(),
            $firstHash
        );

        $secondDoc->setHash($secondHash);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastHash($firstHash);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->sign($secondDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($firstDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     *
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeader(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile   = $this->workingDocuments->getAuditFile();
        $now         = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastDocDate((clone $now)->addDays(1));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastSystemEntryDate((clone $now)->addSeconds(1));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }

    /**
     * @return void
     */
    #[Test]
    public function testDocDateAndSyEntryDateAllDatesEqual(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastSystemEntryDate(clone $now);
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testDocDateAndSyEntryDate(): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->workingDocuments->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocs */
        $workingDocs = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc     = $workingDocs->addWorkDocument();
        $workDoc->setWorkDate(clone $now);
        $workDoc->setSystemEntryDate(clone $now);
        $workDoc->setDocumentNumber("FO FO/2");
        $workDoc->setWorkType(WorkType::FO);

        $docStatus = $workDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastDocDate((clone $now)->addDays(-1));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->workDocumentDateAndSystemEntryDate($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return mixed[]
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    public static function outOfDateWorkTypesInDateProvider(): array
    {
        $inDateStack  = [
            RDate::parse(Pattern::SQL_DATE, "2017-06-30"), // Last valid day
            RDate::parse(Pattern::SQL_DATE, "2015-10-05")
        ];
        $outDateTypes = [
            WorkType::DC
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
     * @param RDate    $date
     * @param WorkType $type @author João Rebelo
     *
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    #[DataProvider('outOfDateWorkTypesInDateProvider')]
    public function testOutOfDateWorkTypesInDate(RDate $date, WorkType $type): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile        = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocuments */
        $workingDocuments = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc          = $workingDocuments->addWorkDocument();
        $workDoc->setWorkDate($date);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType($type);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->outOfDateInvoiceTypes($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertTrue($this->workingDocuments->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($workDoc->getError());
    }

    /**
     * @return mixed[]
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    public static function outOfDateWorkTypesOutDateProvider(): array
    {
        $inDateStack  = [
            RDate::parse(Pattern::SQL_DATE, "2017-07-01"), // First invalid day
            RDate::parse(Pattern::SQL_DATE, "2017-10-05")
        ];
        $outDateTypes = [
            WorkType::DC
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
     * @param RDate    $date
     * @param WorkType $type
     *
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    #[DataProvider('outOfDateWorkTypesOutDateProvider')]
    public function testOutOfDateWorkTypesOutDate(RDate $date, WorkType $type): void
    {
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $auditFile */
        /** @phpstan-ignore-next-line  */
        $auditFile        = $this->workingDocuments->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocuments */
        $workingDocuments = $auditFile->getSourceDocuments()?->getWorkingDocuments();
        $workDoc          = $workingDocuments->addWorkDocument();
        $workDoc->setWorkDate($date);
        $workDoc->setDocumentNumber("FO FO/1");
        $workDoc->setWorkType($type);

        /** @phpstan-ignore-next-line  */
        $this->workingDocuments->outOfDateInvoiceTypes($workDoc);

        /** @phpstan-ignore-next-line  */
        $this->assertFalse($this->workingDocuments->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($workDoc->getError());
    }
}
