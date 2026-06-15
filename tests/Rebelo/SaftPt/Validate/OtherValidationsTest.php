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

use PHPUnit\Framework\Attributes\Test;
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
use Rebelo\SaftPt\Commune;

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
     * @return void
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(OtherValidations::class))->testReflection(OtherValidations::class);
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckInvoicesTypeEmptyStackAndTypes(): void
    {
        $invoices = [];
        $type     = [];
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkInvoicesType($invoices, $type);
        /** @phpstan-ignore-next-line  */
        $audit    = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckInvoicesTypeEmptyTypes(): void
    {
        $invoices = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/". $n);
            $inv->setInvoiceType(InvoiceType::FT);
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkInvoicesType($invoices, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile$audit  */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckInvoicesType(): void
    {
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $inv          = new Invoice(new ErrorRegister());
            $inv->setInvoiceNo("FT FT/". $n);
            $inv->setInvoiceType(InvoiceType::FT);
            $invoices[$n] = $inv;
        }

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkInvoicesType($invoices, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckInvoicesTypeDuplicatedInTypes(): void
    {
        $invoices = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkInvoicesType($invoices, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckInvoicesTypeDuplicatedInInvoices(): void
    {
        $invoices = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $invoices[$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("FT FT/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FT);

        $invoices[++$n] = new Invoice(new ErrorRegister());
        $invoices[$n]->setInvoiceNo("A 2020/". $n);
        $invoices[$n]->setInvoiceType(InvoiceType::FS);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkInvoicesType($invoices, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckStockMovementTypeEmptyStackAndTypes(): void
    {
        $stockMovements = [];
        $type           = [];
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        /** @phpstan-ignore-next-line  */
        $audit          = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckStockMovementTypeEmptyTypes(): void
    {
        $stockMovements = [];
        $type           = [];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/". $n);
            $mov->setMovementType(MovementType::GC);
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckStockMovementType(): void
    {
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $mov                = new StockMovement(new ErrorRegister());
            $mov->setDocumentNumber("GC GC/". $n);
            $mov->setMovementType(MovementType::GC);
            $stockMovements[$n] = $mov;
        }

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckStockMovementTypeDuplicatedInTypes(): void
    {
        $stockMovements = [];
        $type           = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("C GD/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GD);

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("A 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GT);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckStockMovementTypeDuplicatedInStockMovement(): void
    {
        $stockMovements = [];
        $type           = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n                  = 0;
        $stockMovements[$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E A/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GD);

        $stockMovements[++$n] = new StockMovement(new ErrorRegister());
        $stockMovements[$n]->setDocumentNumber("E 2020/". $n);
        $stockMovements[$n]->setMovementType(MovementType::GT);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkStockMovementType($stockMovements, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckWorkDocumentTypeEmptyStackAndTypes(): void
    {
        $worksDocs = [];
        $type      = [];
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkWorkDocumentType($worksDocs, $type);
        /** @phpstan-ignore-next-line  */
        $audit     = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckWorkDocumentTypeEmptyTypes(): void
    {
        $workDocs = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("FO FO/". $n);
            $work->setWorkType(WorkType::FO);
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FC);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckWorkDocumentType(): void
    {
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new WorkDocument(new ErrorRegister());
            $work->setDocumentNumber("F H/". $n);
            $work->setWorkType(WorkType::CM);
            $workDocs[$n] = $work;
        }

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("A 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckWorkDocumentTypeDuplicatedInTypes(): void
    {
        $workDocs = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("C 2019/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO);

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("CM 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::CM);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckWorkDocumentTypeDuplicatedInWorkDocument(): void
    {
        $workDocs = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $workDocs[$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E A/". $n);
        $workDocs[$n]->setWorkType(WorkType::PF);

        $workDocs[++$n] = new WorkDocument(new ErrorRegister());
        $workDocs[$n]->setDocumentNumber("E 2020/". $n);
        $workDocs[$n]->setWorkType(WorkType::FO);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkWorkDocumentType($workDocs, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckPaymentTypeEmptyStackAndTypes(): void
    {
        $payments = [];
        $type     = [];
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkPaymentType($payments, $type);
        /** @phpstan-ignore-next-line  */
        $audit    = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckPaymentTypeEmptyTypes(): void
    {
        $payments = [];
        $type     = [];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("RG RG/". $n);
            $work->setPaymentType(PaymentType::RG);
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkPaymentType($payments, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckPaymentType(): void
    {
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        for ($n = 0; $n <= 9; $n++) {
            $work         = new Payment(new ErrorRegister());
            $work->setPaymentRefNo("F H/". $n);
            $work->setPaymentType(PaymentType::RC);
            $payments[$n] = $work;
        }

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("A 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkPaymentType($payments, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit  */
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckPaymentTypeDuplicatedInTypes(): void
    {
        $payments = [];
        $type     = ["A" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("C 2019/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG);

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("RC 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RC);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkPaymentType($payments, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @author João Rebelo
     * @return void
     */
    #[Test]
    public function testCheckPaymentTypeDuplicatedInPayment(): void
    {
        $payments = [];
        $type     = ["FR" => "FR", "C" => "GT", "D" => "NC"];

        $n            = 0;
        $payments[$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E A/". $n);
        $payments[$n]->setPaymentType(PaymentType::RG);

        $payments[++$n] = new Payment(new ErrorRegister());
        $payments[$n]->setPaymentRefNo("E 2020/". $n);
        $payments[$n]->setPaymentType(PaymentType::RC);

        /** @phpstan-ignore-next-line  */
        $this->otherValidations->checkPaymentType($payments, $type);
        /** @phpstan-ignore-next-line  */
        $audit = $this->otherValidations->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\AuditFile $audit */
        $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        $this->assertEmpty($audit->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testValidate(): void
    {
        $audit = AuditFile::loadFile(SAFT_DEMO_PATH);
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->setAuditFile($audit);
        $this->assertTrue(
            $this->otherValidations->validate()
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testValidateRepeatedInvoiceInternalCode(): void
    {
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_INVOICE_INTERNAL_CODE
        );
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testValidateRepeatedStockMovementInternalCode(): void
    {
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_STOCK_MOVEMENT_INTERNAL_CODE
        );
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testValidateRepeatedWorkDocumentInternalCode(): void
    {
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_WORK_DOCUMENT_INTERNAL_CODE
        );
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testValidateRepeatedPaymentInternalCode(): void
    {
        $audit = AuditFile::loadFile(
            SAFT_REPEATED_PAYMENT_INTERNAL_CODE
        );
        /** @phpstan-ignore-next-line  */
        $this->otherValidations->setAuditFile($audit);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertFalse(
            $this->otherValidations->validate()
        );
    }
}
