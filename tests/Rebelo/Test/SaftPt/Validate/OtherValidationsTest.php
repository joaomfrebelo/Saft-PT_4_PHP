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

use Rebelo\SaftPt\Validate\OtherValidations;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class OtherValidationsTest
 *
 * @author João Rebelo
 */
class OtherValidationsTest extends \Rebelo\Test\SaftPt\Validate\AOtherValidationsBase
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
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(OtherValidations::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCheckInvoicesTypeEmptyStackAndTypes()
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
    public function testCheckInvoicesTypeEmptyTypes()
    {
        $invoices = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/".\strval($n));
            $inv->setInvoiceType(InvoiceType::FT());
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/".\strval($n));
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
    public function testCheckInvoicesType()
    {
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/".\strval($n));
            $inv->setInvoiceType(InvoiceType::FT());
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/".\strval($n));
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
    public function testCheckInvoicesTypeDuplicatedInTypes()
    {
        $invoices = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/".\strval($n));
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/".\strval($n));
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
    public function testCheckInvoicesTypeDuplicatedInInvoices()
    {
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/".\strval($n));
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/".\strval($n));
        $invoices[$n]->setInvoiceType(InvoiceType::FT());

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/".\strval($n));
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
    public function testCheckStockMovementTypeEmptyStackAndTypes()
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
    public function testCheckStockMovementTypeEmptyTypes()
    {
        $stockMovements = [];
        $type           = [];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/".\strval($n));
            $mov->setMovementType(MovementType::GC());
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/".\strval($n));
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
    public function testCheckStockMovementType()
    {
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/".\strval($n));
            $mov->setMovementType(MovementType::GC());
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/".\strval($n));
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
    public function testCheckStockMovementTypeDuplicatedInTypes()
    {
        $stockMovements = [];
        $type           = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("C GD/".\strval($n));
        $stockMovements[$n]->setMovementType(MovementType::GD());

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/".\strval($n));
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
    public function testCheckStockMovementTypeDuplicatedInStockMovement()
    {
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E A/".\strval($n));
        $stockMovements[$n]->setMovementType(MovementType::GD());

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E 2020/".\strval($n));
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
    public function testCheckWorkDocumentTypeEmptyStackAndTypes()
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
    public function testCheckWorkDocumentTypeEmptyTypes()
    {
        $workDocs = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("FO FO/".\strval($n));
            $work->setWorkType(WorkType::FO());
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/".\strval($n));
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
    public function testCheckWorkDocumentType()
    {
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("F H/".\strval($n));
            $work->setWorkType(WorkType::CM());
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/".\strval($n));
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
    public function testCheckWorkDocumentTypeDuplicatedInTypes()
    {
        $workDocs = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("C 2019/".\strval($n));
        $workDocs[$n]->setWorkType(WorkType::FO());

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("CM 2020/".\strval($n));
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
    public function testCheckWorkDocumentTypeDuplicatedInWorkDocument()
    {
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E A/".\strval($n));
        $workDocs[$n]->setWorkType(WorkType::PF());

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E 2020/".\strval($n));
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
    public function testCheckPaymentTypeEmptyStackAndTypes()
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
    public function testCheckPaymentTypeEmptyTypes()
    {
        $payments = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("RG RG/".\strval($n));
            $work->setPaymentType(PaymentType::RG());
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/".\strval($n));
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
    public function testCheckPaymentType()
    {
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("F H/".\strval($n));
            $work->setPaymentType(PaymentType::RC());
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/".\strval($n));
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
    public function testCheckPaymentTypeDuplicatedInTypes()
    {
        $payments = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("C 2019/".\strval($n));
        $payments[$n]->setPaymentType(PaymentType::RG());

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("RC 2020/".\strval($n));
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
    public function testCheckPaymentTypeDuplicatedInPayment()
    {
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E A/".\strval($n));
        $payments[$n]->setPaymentType(PaymentType::RG());

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E 2020/".\strval($n));
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
    public function testValidate()
    {
        $audit = \Rebelo\SaftPt\AuditFile\AuditFile::loadFile(SAFT_DEMO_PATH);
        $this->otherValidations->setAuditFile($audit);
        $this->assertTrue(
            $this->otherValidations->validate()
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateRepeteadInvoiceInternalCode()
    {
        $audit = \Rebelo\SaftPt\AuditFile\AuditFile::loadFile(
            SAFT_REPEATED_INVOICE_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateRepeteadStockMovementInternalCode()
    {
        $audit = \Rebelo\SaftPt\AuditFile\AuditFile::loadFile(
            SAFT_REPEATED_STOCK_MOVEMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateRepeteadWorkDocumentInternalCode()
    {
        $audit = \Rebelo\SaftPt\AuditFile\AuditFile::loadFile(
            SAFT_REPEATED_WORK_DOCUMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testValidateRepeteadPaymentInternalCode()
    {
        $audit = \Rebelo\SaftPt\AuditFile\AuditFile::loadFile(
            SAFT_REPEATED_PAYMENT_INTERNAL_CODE
        );
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }
}