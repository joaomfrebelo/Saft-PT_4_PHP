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

namespace Rebelo\SaftPt\Validate;

use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class OtherValidationsTest
 *
 * @author João Rebelo
 */
class OtherValidationsTest extends AOtherValidationsBase
{

    protected function setUp(): void
    {
        $this->otherValidationsFactory();
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(OtherValidations::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesTypeEmptyStackAndTypes(): void
	{
        $invoices = [];
        $type     = [];
        $this->otherValidations->checkInvoicesType($invoices, $type);
        $audit    = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesTypeEmptyTypes(): void
	{
        $invoices = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/". $n);
            $inv->setInvoiceType(InvoiceType::FT());
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $this->otherValidations->checkInvoicesType($invoices, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesType(): void
	{
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/". $n);
            $inv->setInvoiceType(InvoiceType::FT());
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $this->otherValidations->checkInvoicesType($invoices, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesTypeDuplicatedInTypes(): void
	{
        $invoices = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $this->otherValidations->checkInvoicesType($invoices, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesTypeDuplicatedInInvoices(): void
	{
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FS());

        $this->otherValidations->checkInvoicesType($invoices, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckStockMovementTypeEmptyStackAndTypes(): void
	{
        $stockMovements = [];
        $type           = [];
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        $audit          = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckStockMovementTypeEmptyTypes(): void
	{
        $stockMovements = [];
        $type           = [];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/". $n);
            $mov->setMovementType(MovementType::GC());
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GC());

        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckStockMovementType(): void
	{
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/". $n);
            $mov->setMovementType(MovementType::GC());
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GC());

        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckStockMovementTypeDuplicatedInTypes(): void
	{
        $stockMovements = [];
        $type           = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("C GD/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GD());

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GT());

        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckStockMovementTypeDuplicatedInStockMovement(): void
	{
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E A/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GD());

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GT());

        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckWorkDocumentTypeEmptyStackAndTypes(): void
	{
        $worksDocs = [];
        $type      = [];
        $this->otherValidations->checkWorkDocumentType($worksDocs, $type);
        $audit     = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckWorkDocumentTypeEmptyTypes(): void
	{
        $workDocs = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("FO FO/". $n);
            $work->setWorkType(WorkType::FO());
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FC());

        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckWorkDocumentType(): void
	{
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("F H/". $n);
            $work->setWorkType(WorkType::CM());
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO());

        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckWorkDocumentTypeDuplicatedInTypes(): void
	{
        $workDocs = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("C 2019/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO());

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("CM 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::CM());

        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckWorkDocumentTypeDuplicatedInWorkDocument(): void
	{
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E A/". $n);
        $workDocs[$n]->setWorkType(WorkType::PF());

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO());

        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckPaymentTypeEmptyStackAndTypes(): void
	{
        $payments = [];
        $type     = [];
        $this->otherValidations->checkPaymentType($payments, $type);
        $audit    = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckPaymentTypeEmptyTypes(): void
	{
        $payments = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("RG RG/". $n);
            $work->setPaymentType(PaymentType::RG());
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG());

        $this->otherValidations->checkPaymentType($payments, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckPaymentType(): void
	{
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("F H/". $n);
            $work->setPaymentType(PaymentType::RC());
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG());

        $this->otherValidations->checkPaymentType($payments, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckPaymentTypeDuplicatedInTypes(): void
	{
        $payments = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("C 2019/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG());

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("RC 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RC());

        $this->otherValidations->checkPaymentType($payments, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckPaymentTypeDuplicatedInPayment(): void
	{
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E A/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG());

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RC());

        $this->otherValidations->checkPaymentType($payments, $type);
        $audit = $this->otherValidations->getAuditFile();
        /* @var $audit \Rebelo\SaftPt\AuditFile\AuditFile */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testValidate(): void
	{
        $audit = AuditFile::loadFile(SAFT_DEMO_PATH);
        $this->otherValidations->setAuditFile($audit);
        $this->assertTrue(
            $this->otherValidations->validate()
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testValidateRepeteadInvoiceInternalCode(): void
	{
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_INVOICE_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testValidateRepeteadStockMovementInternalCode(): void
	{
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_STOCK_MOVEMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testValidateRepeteadWorkDocumentInternalCode(): void
	{
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_WORK_DOCUMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

	/**
	 * @return void
	 * @throws \Rebelo\Date\DateFormatException
	 * @throws \Rebelo\Date\DateParseException
	 * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
	 * @author João Rebelo
	 * @test
	 */
    public function testValidateRepeteadPaymentInternalCode(): void
	{
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_PAYMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }
}
