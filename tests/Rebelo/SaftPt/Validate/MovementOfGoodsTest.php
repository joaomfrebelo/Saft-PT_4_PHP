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
use Rebelo\SaftPt\AuditFile\Country;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
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
class MovementOfGoodsTest extends AMovementOfGoodsBase
{

    protected function setUp(): void
    {
        $this->movementOfGoodsFactory();
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(MovementOfGoods::class))->testReflection(MovementOfGoods::class);
    }

    /**
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author       João Rebelo
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    #[Test]
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
        $this->movementOfGoods->setAuditFile($auditFile);
        $this->movementOfGoods->setDeltaLine(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaCurrency(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaTable(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->movementOfGoods->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author       João Rebelo
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    #[Depends('testNumberOfLinesAndTotalQuantity')]
    #[Depends('testMovOfStock')]
    #[Test]
    public function testValidateMissingStockMov(): void
    {
        $xml = \simplexml_load_file(SAFT_MISSING_STOCK_MOV);
        if ($xml === false) {
            $this->fail(\sprintf("Failing load file '%s'", SAFT_MISSING_STOCK_MOV));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setAuditFile($auditFile);
        $this->movementOfGoods->setDeltaLine(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaCurrency(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaTable(new Decimal("0.005"));
        $this->movementOfGoods->setDeltaTotalDoc(new Decimal("0.005"));

        $valid = $this->movementOfGoods->validate();
        $this->assertFalse($valid);
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @author       João Rebelo
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    #[Test]
    public function testValidateNoStockMov(): void
    {

        $auditFile = new AuditFile();
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setAuditFile($auditFile);
        $valid = $this->movementOfGoods->validate();
        $this->assertTrue($valid);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author       João Rebelo
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStock(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        /** @var StockMovement $stockMov */
        $stockMov = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line */
            $netValue   = $netValue->add($line->getCreditAmount() ?? throw new \Exception("No credit amount"));
            $taxPerc    = $line->getTax()->getTaxPercentage() ?? throw new \Exception("No tax percentage");
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul(
                    $line->getCreditAmount() ?? throw new \Exception("No Credit amount")
                )
            );

        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author  João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongSign(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line */
            $netValue = $netValue->add($line->getCreditAmount() ?? throw new \Exception("No credit amount"));
            /** @var Decimal $taxPerc */
            $taxPerc    = $line->getTax()->getTaxPercentage();
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul(
                    $line->getCreditAmount() ?? throw new \Exception("No Credit amount")
                )
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongDate(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line */
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add(
                $taxPerc->div(100)->mul(
                    $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
                )
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongCustomerID(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul($line->getCreditAmount())
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID() . "A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongSupplierID(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GD GD/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setSupplierID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul($line->getCreditAmount())
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $supplier = $auditFile->getMasterFiles()->addSupplier();
        $supplier->setAccountID(AuditFile::DESCONHECIDO);
        $supplier->setCompanyName("Rebelo SAFT");
        $supplier->setSupplierID($stockMov->getSupplierID() . "A");
        $supplier->setSupplierTaxID("999999990");
        $supplier->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoDocStatus(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoLines(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        //$this->iniInvoiceLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getCreditAmount()));
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongTotals(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul($line->getCreditAmount())
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable)->add("1"));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getDocumentTotals()->getError());
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
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockDebit(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $header    = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("OU OU/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov, true);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getDebitAmount() ?? throw new \Exception("Debit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add($taxPerc->div("100.0")->mul($line->getDebitAmount()));
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal()
        );

        $stockMov->setHash($hash);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockWrongSign2(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $status = $stockMov->getDocumentStatus();
        $status->setMovementStatus(MovementStatus::N);
        $status->setMovementStatusDate(clone $now);
        $status->setSourceBilling(SourceBilling::P);
        $status->setSourceID("Rebelo");

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $taxPayable = new Decimal("0.0");
        $netValue   = new Decimal("0.0");

        foreach ($stockMov->getLine() as $line) {
            $netValue   = $netValue->add(
                $line->getCreditAmount() ?? throw new \Exception("Credit amount is null")
            );
            $taxPerc    = $line->getTax()?->getTaxPercentage() ?? throw new \Exception("Tax percentage is null");
            $taxPayable = $taxPayable->add(
                $taxPerc->div("100.0")->mul($line->getCreditAmount())
            );
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue);
        $docTotals->setTaxPayable($taxPayable);
        $docTotals->setGrossTotal($netValue->add($taxPayable));

        $sign = new Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal(), "a"
        );

        $stockMov->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testSupplierId')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoMovementNo(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        //$stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoMovementType(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        //$stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoMovementDate(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        //$stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testLines')]
    #[Depends('testDocumentStatus')]
    public function testMovOfStockNoSystemEntryDate(): void
    {
        $now = new RDate();
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int)$now->format(Pattern::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        //$stockMov->setSystemEntryDate(clone $now);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovement($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testMovOfStock')]
    public function testNumberOfLinesAndTotalQuantity(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new Decimal("1099.999");


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNumberOfMovementLines($totalLines);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTotalQuantityIssued($totalQt);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()?->getNumberOfMovementLines()
            ?? throw new \Exception("No table total document calculation instance")
        );
        $this->assertSame(
            $totalQt,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testMovOfStock')]
    public function testNumberOfLinesAndTotalQuantityWrongLines(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new Decimal("1099.999");


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNumberOfMovementLines($totalLines + 1);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTotalQuantityIssued($totalQt);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertNotSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()?->getNumberOfMovementLines()
            ?? throw new \Exception("No table total documents instance")
        );
        $this->assertSame(
            $totalQt,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    #[Depends('testMovOfStock')]
    public function testNumberOfLinesAndTotalQuantityWrongQt(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new Decimal("1099.999");


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNumberOfMovementLines($totalLines);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTotalQuantityIssued($totalQt->add("0.99"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());

        $this->assertSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()?->getNumberOfMovementLines()
            ?? throw new \Exception("No table total documents instance")
        );

        $this->assertNotSame(
            $totalQt,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatus(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $stockMov = $movOfGoodsDocs->addStockMovement();
        $now      = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->documentStatus($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusNotDefined(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $stockMov = $movOfGoodsDocs->addStockMovement();
        $now      = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->documentStatus($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_DOCUMENT_STATUS,
            \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusDateEarlier(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents");
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(RDate::parse(Pattern::SQL_DATE, "2020-10-05"));
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(
            RDate::parse(Pattern::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->documentStatus($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_MOVEMENT_STATUS_DATE,
            \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusCancel(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $stockMov = $movOfGoodsDocs->addStockMovement();
        $now      = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::A);
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->documentStatus($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            AuditFile::class, $auditFile
        );

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
            ?? throw new \Exception("No source documents instance");

        $stockMov = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::A);
        $docStatus->setMovementStatusDate(new RDate());
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setSourceID("Rebelo");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->documentStatus($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdInGt(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setCustomerID($customerID);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testSupplierIdInGt(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addSupplier();
        $supplierID = "999G";
        $customer->setSupplierID($supplierID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setSupplierID($supplierID);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testSupplierId(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $supplier   = $auditFile->getMasterFiles()->addSupplier();
        $supplierID = "999G";
        $supplier->setSupplierID($supplierID);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD);
        $stockMov->setSupplierID($supplierID);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdCustomerNotExist(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setCustomerID("A999");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMER_ID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testSupplierIdSupplierNotExist(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD);
        $stockMov->setSupplierID("A999");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMER_ID, \array_key_first($stockMov->getError())
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMER_ID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testCustomerIdOrSupplierIsNotSet(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMER_ID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testSupplierIdSupplierIsNotSet(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_SUPPLIER_ID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * Init variables
     */
    public function iniMovOfGoodsForLineTest(): void
    {
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal(new Decimal("0.0"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal(new Decimal("0.0"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable(new Decimal("0.0"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setDocCredit(new Decimal("0.0"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setDocDebit(new Decimal("0.0"));
    }

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     * @param bool                                                                   $debit The line are to be debited
     *
     * @return void
     * @throws \Exception
     */
    public function iniMovOfGoodsLinesForLinesTest(
        StockMovement $stockMov,
        bool          $debit = false
    ): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile     = $this->movementOfGoods->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        for ($n = 1; $n <= 9; $n++) {
            $line = $stockMov->addLine();
            $line->setQuantity(new Decimal((string)$n));
            $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));

            $debit ? $line->setDebitAmount((new Decimal((string)$n))->mul((string)$n)->mul("1.2")) :
                $line->setCreditAmount((new Decimal((string)$n))->mul((string)$n)->mul("1.2"));

            $line->setDescription("Desc of line " . $n);
            $line->setProductCode("CODE_" . $n);
            $line->setProductDescription("Prod desc of line " . $n);
            $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
            $line->setUnitOfMeasure("UN");

            $tax = $line->getTax() ?? throw new \Exception("No tax instance");
            $tax->setTaxCode(
                MovementTaxCode::from(
                    is_string($taxTableEntry->getTaxCode())
                        ? $taxTableEntry->getTaxCode()
                        : $taxTableEntry->getTaxCode()->value
                )
            );

            $tax->setTaxCountryRegion($taxTableEntry->getTaxCountryRegion());
            $tax->setTaxPercentage($taxTableEntry->getTaxPercentage());
            $tax->setTaxType(
                MovementTaxType::from(
                    $taxTableEntry->getTaxType()->value
                )
            );

            $prod = $auditFile->getMasterFiles()->addProduct();
            $prod->setProductCode($line->getProductCode());
            $prod->setProductDescription($line->getProductDescription());
            $prod->setProductNumberCode($line->getProductCode());
            $prod->setProductType(ProductType::P);
        }
    }

    /**
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line          $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     *
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    protected function cloneTaxForExtraLinesFromLastLine(
        Line          $line,
        StockMovement $stockMov
    ): void
    {
        $n        = \count($stockMov->getLine());
        $prevLine = $stockMov->getLine()[$n - 2];
        $prevTax  = $prevLine->getTax() ?? throw new \Exception("Tax is null");

        $tax = $line->getTax() ?? throw new \Exception("No tax instance");
        $tax->setTaxCode($prevTax->getTaxCode());
        $tax->setTaxCountryRegion($prevTax->getTaxCountryRegion());
        $tax->setTaxPercentage($prevTax->getTaxPercentage());
        $tax->setTaxType($prevTax->getTaxType());
    }

    /**
     * Create the minimum values of ShipFrom in StockMovement
     *
     * @param StockMovement $stockMovement
     *
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    protected function createShipFrom(StockMovement $stockMovement): void
    {
        $ship = $stockMovement->getShipFrom() ?? throw new \Exception("Ship is null");
        $addr = $ship->getAddress() ?? throw new \Exception("Ship address is null");
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        $addr->setCountry(Country::ISO_PT);
        $addr->setPostalCode("1100-999");
    }

    /**
     * Create the minimum values of ShipTo in StockMovement
     *
     * @param StockMovement $stockMovement
     *
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    protected function createShipTo(StockMovement $stockMovement): void
    {
        $ship = $stockMovement->getShipTo() ?? throw new \Exception("Ship is null");
        $addr = $ship->getAddress() ?? throw new \Exception("Ship address is null");
        $addr->setAddressDetail("largo de Santo Estêvão");
        $addr->setCity("Lisboa");
        $addr->setCountry(Country::ISO_PT);
        $addr->setPostalCode("1109-999");
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->movementOfGoods->setContinuesLines(true);
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] $lineStack */
        $lineStack = $stockMov->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesRepeatedLineNumber(): void
    {
        $now = new RDate();
        $this->movementOfGoods->setContinuesLines(false);
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());

        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);


        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] $lineStack */
        $lineStack = $stockMov->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($lastLine->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoQuantitySet(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul("1.2"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test] public function testLinesNoUnitPriceSet(): void
    {

        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(new Decimal((string)$n));
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount((new Decimal((string)$n))->mul((string)$n)->mul("1.2"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesNoCreditAndDebitSet(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceDebitAmount(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov, true);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setDebitAmount((new Decimal((string)$n))->mul((string)$n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLinesWrongQtUnitPriceCreditAmount(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(new Decimal((string)$n));
        $line->setUnitPrice((new Decimal((string)$n))->mul("1.2"));
        $line->setCreditAmount((new Decimal((string)$n))->mul((string)$n)->mul("1.1")); //wrong Qt * UnPrice
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testLines(): void
    {
        $now = new RDate();
        $this->iniMovOfGoodsForLineTest();

        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(new Decimal("0.0"));
        $line->setUnitPrice(new Decimal("0.0"));
        $line->setCreditAmount(new Decimal("0.0"));
        $line->setDescription("Desc of line " . $n);
        $line->setProductCode("CODE_" . $n);
        $line->setProductDescription("Prod desc of line " . $n);
        $line->setSettlementAmount((new Decimal("0.1"))->mul((string)$n));
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLinesFromLastLine($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->lines($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testOrderReferencesOneOrderReference(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $stockMov->getMovementDate());
        $ref->setOriginatingON("GT A/1");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("NE NE/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref1 = $line->addOrderReferences();
        $ref1->setOrderDate(clone $stockMov->getMovementDate());
        $ref1->setOriginatingON("OU A/1");

        $ref2 = $line->addOrderReferences();
        $ref2->setOrderDate((clone $stockMov->getMovementDate())->addDays(-1));
        $ref2->setOriginatingON("GR A/2");

        $ref3 = $line->addOrderReferences();
        $ref3->setOrderDate(clone $stockMov->getMovementDate());
        $ref3->setOriginatingON("GT A/3");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N);
        $stockMov->getDocumentStatus()->setSourceBilling(SourceBilling::P);


        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("NE NE/1");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GR A/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);
        $product->setProductType(ProductType::P);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setProductCode($productCode);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->productCode($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
        $this->assertEmpty($line->getError());
        $this->assertEmpty($line->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testProductTypeNoDefined(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setProductCode($productCode);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->productCode($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
        $this->assertNotEmpty($line->getError());
        $this->assertEmpty($line->getWarning());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testProductTypeWarning(): void
    {
        $types = [
            ProductType::S,
            ProductType::O
        ];

        foreach ($types as $type) {
            $auditFile = new AuditFile();
            /** @phpstan-ignore-next-line */
            $this->movementOfGoods->setAuditFile($auditFile);

            $productCode = "COD999";
            $product     = $auditFile->getMasterFiles()->addProduct();
            $product->setProductCode($productCode);
            $product->setProductType($type);

            $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods()
                ?? throw new \Exception("No source documents instance");

            $stockMov = $movOfGoodsDocs->addStockMovement();
            $stockMov->setMovementDate(new RDate());
            $stockMov->setDocumentNumber("GT GT/1");
            $stockMov->setMovementType(MovementType::GT);

            $line = $stockMov->addLine();
            $line->setProductCode($productCode);

            /** @phpstan-ignore-next-line */
            $this->movementOfGoods->productCode($line, $stockMov);

            /** @phpstan-ignore-next-line */
            $this->assertTrue($this->movementOfGoods->isValid());
            $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
            $this->assertNotEmpty($auditFile->getErrorRegistor()->getWarnings());
            $this->assertEmpty($line->getError());
            $this->assertNotEmpty($line->getWarning());
        }
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testProductTypeNotWarning(): void
    {
        $types = [
            ProductType::P,
            ProductType::I,
            ProductType::E
        ];

        foreach ($types as $type) {
            $auditFile = new AuditFile();
            /** @phpstan-ignore-next-line */
            $this->movementOfGoods->setAuditFile($auditFile);

            $productCode = "COD999";
            $product     = $auditFile->getMasterFiles()->addProduct();
            $product->setProductCode($productCode);
            $product->setProductType($type);

            /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
            $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();

            $stockMov = $movOfGoodsDocs->addStockMovement();
            $stockMov->setMovementDate(new RDate());
            $stockMov->setDocumentNumber("GT GT/1");
            $stockMov->setMovementType(MovementType::GT);

            $line = $stockMov->addLine();
            $line->setProductCode($productCode);

            /** @phpstan-ignore-next-line */
            $this->movementOfGoods->productCode($line, $stockMov);

            /** @phpstan-ignore-next-line */
            $this->assertTrue($this->movementOfGoods->isValid());
            $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
            $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
            $this->assertEmpty($line->getError());
            $this->assertEmpty($line->getWarning());
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testProductCodeNotExists(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setProductCode("COD999");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->productCode($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->productCode($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTypeIvaPercentageNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(MovementTaxCode::OUT);
        $tax->setTaxType(MovementTaxType::NS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPercentageZeroExceptionReasonNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("0.00"));
        $tax->setTaxCode(MovementTaxCode::OUT);
        $tax->setTaxType(MovementTaxType::NS);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeIseExceptionReasonNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(MovementTaxCode::ISE);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        // The percentage is not set to zero in a ISE for exception test
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(MovementTaxCode::ISE);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99);

        $tax = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("9.00"));
        $tax->setTaxCode(MovementTaxCode::ISE);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTableTaxEmpty(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCode(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNoTaxCountryRegion(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxCodeNotExistInTable(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("13.00"));
        $taxTableEntry->setTaxCode(TaxCode::RED);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(new Decimal("23.00"));
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage(new Decimal("23.00"));
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTaxTaxExpirationDateNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile     = $this->movementOfGoods->getAuditFile();
        $taxPerc       = new Decimal("23.00");
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage($taxPerc);
        $taxTableEntry->setTaxCode(TaxCode::NOR);
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT);
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA);
        $taxTableEntry->setDescription("Tax description");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $line = $stockMov->addLine();
        $tax  = $line->getTax() ?? throw new \Exception("Tax is null");
        $tax->setTaxPercentage($taxPerc);
        $tax->setTaxCode(MovementTaxCode::NOR);
        $tax->setTaxType(MovementTaxType::IVA);
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->tax($line, $stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($line->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsDocumentTotalsNotSet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongGross(): void
    {
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("122.99");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGross(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal(new Decimal("100.00"));
        $totals->setTaxPayable(new Decimal("23.00"));
        $totals->setGrossTotal(new Decimal("122.99"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal(new Decimal("123.00"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedGrossDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();

        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross->sub("0.01"));

        $this->movementOfGoods->setDeltaTotalDoc(new Decimal("0.01"));

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNet(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedNetDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        $this->movementOfGoods->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayable(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedTaxPayableDelta(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax->sub($delta));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        $this->movementOfGoods->setDeltaTotalDoc($delta);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($totals->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testTotalsWrongCalculatedCurrency(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.02");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("No currency instance");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $stockMov->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $delta     = new Decimal("0.01");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("No currency instance available");
        $currency->setCurrencyAmount($gross->div($rate)->add($delta));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $stockMov->setDocTotalCalc($docTotalCal);

        $this->movementOfGoods->setDeltaCurrency($delta);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = new Decimal("100.00");
        $tax       = new Decimal("23.00");
        $gross     = new Decimal("123.00");
        $rate      = new Decimal("0.5");

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency() ?? throw new \Exception("Currency is null");
        $currency->setCurrencyAmount($gross->div($rate));
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setNetTotal($net);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setTaxPayable($tax);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setGrossTotal($gross);

        $docTotalCal = new DocTotalCalc();
        $docTotalCal->setGrossTotal($gross);
        $docTotalCal->setNetTotal($net);
        $docTotalCal->setTaxPayable($tax);
        $docTotalCal->setGrossTotalFromCurrency($gross->div($rate));
        $stockMov->setDocTotalCalc($docTotalCal);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->totals($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $this->movementOfGoods->setSignValidation(false);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setHash("AAA");

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $this->movementOfGoods->setSignValidation(false);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal()
        );

        $stockMov->setHash($hash);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());

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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash("");
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());

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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $latestHash = "AAA";
        $hash      = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), $latestHash
        );

        $stockMov->setHash("a" . \substr($hash, 0, 171));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash($latestHash);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());

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
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $firstDoc       = $movOfGoodsDocs->addStockMovement();
        $firstDoc->setMovementDate(clone $now);
        $firstDoc->setSystemEntryDate(clone $now);
        $firstDoc->setDocumentNumber("GT GT/1");
        $firstDoc->setMovementType(MovementType::GT);
        $firstDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));

        $firstHash = $sign->createSignature(
            $firstDoc->getMovementDate(), $firstDoc->getSystemEntryDate(),
            $firstDoc->getDocumentNumber(),
            $firstDoc->getDocumentTotals()->getGrossTotal(), ""
        );

        $firstDoc->setHash($firstHash);

        $secondDoc = $movOfGoodsDocs->addStockMovement();
        $secondDoc->setMovementDate(clone $now);
        $secondDoc->setSystemEntryDate(clone $now);
        $secondDoc->setDocumentNumber("GT GT/2");
        $secondDoc->setMovementType(MovementType::GT);
        $secondDoc->getDocumentTotals()->setGrossTotal(new Decimal("999.99"));


        $docStatus = $secondDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        $secondHash = $sign->createSignature(
            $secondDoc->getMovementDate(), $secondDoc->getSystemEntryDate(),
            $secondDoc->getDocumentNumber(),
            $secondDoc->getDocumentTotals()->getGrossTotal(), $firstHash
        );

        $secondDoc->setHash($secondHash);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastHash($firstHash);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->sign($secondDoc);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($firstDoc->getError());

        $this->assertEmpty($auditFile->getErrorRegistor()->getWarnings());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeader(): void
    {
        /** @var AuditFile $auditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()?->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastDocDate((clone $now)->addDays(1));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastSystemEntryDate((clone $now)->addSeconds(1));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDateAllDatesEqual(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastDocDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastSystemEntryDate(clone $now);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testDocDateAndSyEntryDate(): void
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /** @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movOfGoodsDocs */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastDocDate((clone $now)->addDays(-1));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * Common properties for ship test
     *
     * @return StockMovement
     * @throws \Rebelo\Date\DateFormatException
     * @author João Rebelo
     */
    protected function createStockMovForTestShip(): StockMovement
    {
        /** @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        /** @phpstan-ignore-next-line */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalCalc(new DocTotalCalc());
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT);
        $stockMov->setAtcud("0");

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P);
        $docStatus->setMovementStatus(MovementStatus::N);
        $docStatus->setMovementStatusDate(clone $stockMov->getMovementDate());
        $docStatus->setSourceID("Rebelo");

        return $stockMov;
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
    public function testShipment(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
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
    public function testShipmentCancelStatusDateAfterMoveStartTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())->addHours(1)
        );

        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );

        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::A);

        $stockMov->getDocumentStatus()->setMovementStatusDate(
            (clone $stockMov->getMovementStartTime())->addMinutes(1)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertTrue(
            $stockMov->getDocumentStatus()->getErrorRegistor()->hasErrors()
        );
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
    public function testShipmentNoShipFrom(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        // $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentGlobal(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("No shipment instance");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        // Global not have ShipTo And Only Can be of type GT
        $stockMov->setMovementType(MovementType::GT);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testShipmentResumeNoShipFrom(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );

        $stockMov->setMovementType(MovementType::GT);
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::R);
        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
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
    public function testShipmentNoShipToNoGT(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99")
            ?? throw new \Exception("No shipment instance");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentShipFromDeliveryDateLaterShipToDeliveryDate(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");

        $stockMov->getShipFrom()?->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(3)
        );

        $stockMov->getShipTo()?->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(2)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentShipFromDeliveryDateLaterShipToDeliveryDateInDocResume(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::R);
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");

        $stockMov->getShipFrom()?->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(3)
        );

        $stockMov->getShipTo()?->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(2)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
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
    public function testShipmentNoMovementStartTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
//        $stockMov->setMovementStartTime(
//            clone $stockMov->getMovementDate()
//        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentMovementStartTimeEarlierDocDate(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())->addMinutes(-1)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    #[Test] public function testShipmentMovementStartTimeEarlierSystemDateSourceBillP(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setSystemEntryDate(
            (clone $stockMov->getMovementDate())->addMinutes(30)
        );
        $stockMov->setMovementStartTime(
            (clone $stockMov->getSystemEntryDate())->addMinutes(-1)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentMovementStartTimeEarlierSystemDateSourceBillNotP(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->getDocumentStatus()->setSourceBilling(SourceBilling::I);
        $stockMov->setSystemEntryDate(
            (clone $stockMov->getMovementDate())->addMinutes(30)
        );
        $stockMov->setMovementStartTime(
            (clone $stockMov->getSystemEntryDate())->addMinutes(-1)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testShipmentNoMovementEndTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setSystemEntryDate(
            (clone $stockMov->getMovementDate())
        );
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())
        );
//        $stockMov->setMovementEndTime(
//            (clone $stockMov->getMovementDate())->addHours(9)
//        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @return void
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testShipmentMovementEndTimeEarlierStartMovTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setSystemEntryDate(
            (clone $stockMov->getMovementDate())
        );
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())->addHours(3)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementStartTime())->addMinutes(-1)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentNoShipFromAddress(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        //$this->createShipFrom($stockMov);
        $stockMov->getShipFrom();
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentNoShipFromCity(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99") ?? throw new \Exception("Ship from is null");
        //$this->createShipFrom($stockMov);
        $addr = $stockMov->getShipFrom()->getAddress() ?? throw new \Exception("Address is null");
        $addr->setAddressDetail("Rua das Escolas Gerais");
        //$addr->setCity("Lisboa");
        $addr->setCountry(Country::ISO_PT);
        $addr->setPostalCode("1100-999");

        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentNoShipFromCountry(): void
    {

        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
            $stockMov->getShipFrom()?->addDeliveryID("AA-99-99")
                    ?? throw new \Exception("Ship from from is null");
        //$this->createShipFrom($stockMov);
        $addr = $stockMov->getShipFrom()->getAddress() ?? throw new \Exception("Ship from address is null");
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        //$addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");

        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentNoShipToAddress(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GR);
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentShipToAddressNoAddress(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->getShipTo();
        $stockMov->setMovementType(MovementType::GC);

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentShipToAddressNoCity(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GC);
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $addr = $stockMov->getShipTo()?->getAddress() ?? throw new \Exception("Ship to address is null");
        $addr->setAddressDetail("Rua das Escolas Gerais");
        //$addr->setCity("Lisboa");
        $addr->setCountry(Country::ISO_PT);
        $addr->setPostalCode("1100-999");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
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
    public function testShipmentShipToAddressNoCountry(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GC);
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()?->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $addr = $stockMov->getShipTo()?->getAddress() ?? throw new \Exception("Ship to addess is null");
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        //$addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");

        /** @phpstan-ignore-next-line */
        $this->movementOfGoods->shipment($stockMov);

        /** @phpstan-ignore-next-line */
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
        /** @phpstan-ignore-next-line */
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }
}
