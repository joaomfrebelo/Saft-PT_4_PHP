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

use Rebelo\SaftPt\Validate\MovementOfGoods;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\TaxExemptionCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\Decimal\UDecimal;
use Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode;
use Rebelo\SaftPt\Validate\DocTotalCalc;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line;
use Rebelo\SaftPt\Validate\ADocuments;
use Rebelo\SaftPt\Validate\MovOfGoodsTableTotalCalc;

/**
 * Class SalesInvoiceTest
 *
 * @author João Rebelo
 */
class MovementOfGoodsTest extends \Rebelo\Test\SaftPt\Validate\AMovementOfGoodsBase
{

    protected function setUp(): void
    {
        $this->movementOfGoodsFactory();
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(MovementOfGoods::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     * @-depends testMovOfStock
     * @-depends testNumberOfLinesAndTotalQuantity
     * @return void
     */
    public function testValidate(): void
    {
        $xml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($xml === false) {
            $this->fail(\sprintf("Failling load file '%s'", SAFT_DEMO_PATH));
            return;
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($xml);

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $this->movementOfGoods->setAuditFile($auditFile);
        $this->movementOfGoods->setDeltaLine(0.005);
        $this->movementOfGoods->setDeltaCurrency(0.005);
        $this->movementOfGoods->setDeltaTable(0.005);
        $this->movementOfGoods->setDeltaTotalDoc(0.005);

        $valide = $this->movementOfGoods->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testvalidateNoStockMov(): void
    {

        $auditFile = new AuditFile();
        $this->movementOfGoods->setAuditFile($auditFile);

        $valide = $this->movementOfGoods->validate();
        $this->assertTrue($valide);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStock(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrohgSign(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $customer = $auditFile->getMasterFiles()->addCustomer();
        $customer->setAccountID(AuditFile::DESCONHECIDO);
        $customer->setCompanyName("Rebelo SAFT");
        $customer->setCustomerID($stockMov->getCustomerID());
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrongDate(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrongCustomerID(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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
        $customer->setCustomerID($stockMov->getCustomerID()."A");
        $customer->setCustomerTaxID("999999990");
        $customer->setSelfBillingIndicator(false);

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrongSupplierID(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GD GD/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setSupplierID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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
        $supplier->setSupplierID($stockMov->getSupplierID()."A");
        $supplier->setSupplierTaxID("999999990");
        $supplier->setSelfBillingIndicator(false);

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockNoDocStatus(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockNoLines(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        //$this->iniInvoiceLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrongTotals(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf() + 1);

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getDocumentTotals()->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockDebit(): void
    {
        $now            = new RDate();
        $this->iniMovOfGoodsForLineTest();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $header         = $auditFile->getHeader();
        $header->setDateCreated(clone $now);
        $header->setStartDate($now->addDays(-1));
        $header->setEndDate($now->addDays(1));
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("OU OU/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->iniMovOfGoodsLinesForLinesTest($stockMov, true);

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setSourceID("Rebelo");

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line */
            $netValue->plusThis($line->getDebitAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getDebitAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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

        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @return void
     */
    public function testMovOfStockWrongSign(): void
    {
        $now            = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setMovementStartTime(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $status = $stockMov->getDocumentStatus();
        $status->setMovementStatus(MovementStatus::N());
        $status->setMovementStatusDate(clone $now);
        $status->setSourceBilling(SourceBilling::P());
        $status->setSourceID("Rebelo");

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $taxPayable = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);
        $netValue   = new UDecimal(0.0, MovementOfGoods::CALC_PRECISION);

        foreach ($stockMov->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $netValue->plusThis($line->getCreditAmount());
            $taxPerc = $line->getTax()->getTaxPercentage();
            $taxPayable->plusThis($taxPerc / 100 * $line->getCreditAmount());
        }

        $docTotals = $stockMov->getDocumentTotals();
        $docTotals->setNetTotal($netValue->valueOf());
        $docTotals->setTaxPayable($taxPayable->valueOf());
        $docTotals->setGrossTotal($netValue->plus($taxPayable)->valueOf());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(), $docTotals->getGrossTotal(), "a"
        );

        $stockMov->setHash($hash);
        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testSupplierId
     * @depends testLines
     * @test
     * @return void
     */
    public function testMovOfStockNoMovementNo(): void
    {
        $now            = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        //$stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testLines
     * @test
     * @return void
     */
    public function testMovOfStockNoMovementType(): void
    {
        $now            = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        //$stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testLines
     * @test
     * @return void
     */
    public function testMovOfStockNoMovementDate(): void
    {
        $now            = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        //$stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        $stockMov->setSystemEntryDate(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @depends testDocumentStatus
     * @depends testLines
     * @test
     * @return void
     */
    public function testMovOfStockNoSystemEntryDate(): void
    {
        $now            = new RDate();
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");
        $stockMov->setCustomerID("CODE_A");
        $stockMov->setHashControl("1");
        $stockMov->setPeriod((int) $now->format(RDate::MONTH_SHORT));
        $stockMov->setSourceID("Rebelo");
        //$stockMov->setSystemEntryDate(clone $now);
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->stockMovement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @depends testMovOfStock
     * @test
     * @return void
     */
    public function testNumberOfLinesAndTotalQuantity(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new UDecimal(1099.999, ADocuments::CALC_PRECISION);


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt->valueOf());

        $this->movementOfGoods->setNumberOfMovementLines($totalLines);
        $this->movementOfGoods->setTotalQuantityIssued($totalQt);

        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getNumberOfMovementLines()
        );
        $this->assertSame(
            $totalQt->valueOf(),
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @depends testMovOfStock
     * @test
     * @return void
     */
    public function testNumberOfLinesAndTotalQuantityWrongLines(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new UDecimal(1099.999, ADocuments::CALC_PRECISION);


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt->valueOf());

        $this->movementOfGoods->setNumberOfMovementLines($totalLines + 1);
        $this->movementOfGoods->setTotalQuantityIssued($totalQt);

        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertNotSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getNumberOfMovementLines()
        );
        $this->assertSame(
            $totalQt->valueOf(),
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @depends testMovOfStock
     * @test
     * @return void
     */
    public function testNumberOfLinesAndTotalQuantityWrongQt(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $movOfGoodsDocs->setMovOfGoodsTableTotalCalc(new MovOfGoodsTableTotalCalc());

        $totalLines = 9;
        $totalQt    = new UDecimal(1099.999, ADocuments::CALC_PRECISION);


        $movOfGoodsDocs->setNumberOfMovementLines($totalLines);
        $movOfGoodsDocs->setTotalQuantityIssued($totalQt->valueOf());

        $this->movementOfGoods->setNumberOfMovementLines($totalLines);
        $this->movementOfGoods->setTotalQuantityIssued($totalQt->plus(0.99));

        $this->movementOfGoods->numberOfLinesAndTotalQuantity();
        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertSame(
            $totalLines,
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getNumberOfMovementLines()
        );
        $this->assertNotSame(
            $totalQt->valueOf(),
            $movOfGoodsDocs->getMovOfGoodsTableTotalCalc()->getTotalQuantityIssued()
        );
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatus(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $now            = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->movementOfGoods->documentStatus($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusNotDefined(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $now            = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->documentStatus($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_DOCUMENTSTATUS,
            \array_key_first($stockMov->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusDateEalier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(RDate::parse(RDate::SQL_DATE, "2020-10-05"));
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(
            RDate::parse(RDate::SQL_DATE, "2020-10-04")
        );
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->movementOfGoods->documentStatus($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_MOVEMENTSTATUSDATE,
            \array_key_first($stockMov->getError())
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $now            = new RDate();
        $stockMov->setMovementDate($now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::A());
        $docStatus->setMovementStatusDate(clone $now);
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");
        $docStatus->setReason("Some reason");

        $this->movementOfGoods->documentStatus($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocumentStatusStatusCancelNoReason(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $this->assertInstanceOf(
            \Rebelo\SaftPt\AuditFile\AuditFile::class, $auditFile
        );

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setMovementStatus(MovementStatus::A());
        $docStatus->setMovementStatusDate(new RDate());
        $docStatus->setSourceBilling(new SourceBilling(SourceBilling::P));
        $docStatus->setSourceID("Rebelo");

        $this->movementOfGoods->documentStatus($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            DocumentStatus::N_REASON, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdInGt(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addCustomer();
        $customerID = "999G";
        $customer->setCustomerID($customerID);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setCustomerID($customerID);

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSupplierIdInGt(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $customer   = $auditFile->getMasterFiles()->addSupplier();
        $supplierID = "999G";
        $customer->setSupplierID($supplierID);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setSupplierID($supplierID);

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSupplierId(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile  = $this->movementOfGoods->getAuditFile();
        $supplier   = $auditFile->getMasterFiles()->addSupplier();
        $supplierID = "999G";
        $supplier->setSupplierID($supplierID);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD());
        $stockMov->setSupplierID($supplierID);

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdCustomerNotExist(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setCustomerID("A999");

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMERID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSupplierIdSupplierNotExist(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD());
        $stockMov->setSupplierID("A999");

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMERID, \array_key_first($stockMov->getError())
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMERID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testCustomerIdOrSupplierIsNotSet(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_CUSTOMERID, \array_key_first($stockMov->getError())
        );
    }
    
    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testSupplierIdSupplierIsNotSet(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GD());
        
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        
        $this->movementOfGoods->customerIdOrSupplierId($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($movOfGoodsDocs->getError());
        $this->assertSame(
            StockMovement::N_SUPPLIERID, \array_key_first($stockMov->getError())
        );
    }

    /**
     * Init variables
     * @return void
     */
    public function iniMovOfGoodsForLineTest(): void
    {
        $this->movementOfGoods->setNetTotal(
            new UDecimal(0.0, MovementOfGoods::CALC_PRECISION)
        );

        $this->movementOfGoods->setGrossTotal(
            new UDecimal(0.0, MovementOfGoods::CALC_PRECISION)
        );

        $this->movementOfGoods->setTaxPayable(
            new UDecimal(0.0, MovementOfGoods::CALC_PRECISION)
        );

        $this->movementOfGoods->setDocCredit(
            new UDecimal(0.0, MovementOfGoods::CALC_PRECISION)
        );

        $this->movementOfGoods->setDocDebit(
            new UDecimal(0.0, MovementOfGoods::CALC_PRECISION)
        );
    }

    /**
     * 
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     * @param bool $debit The line are to be debit
     * @return void
     */
    public function iniMovOfGoodsLinesForLinesTest(StockMovement $stockMov,
                                                   bool $debit = false): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile     = $this->movementOfGoods->getAuditFile();
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setDescription("IVA normal");
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        for ($n = 1; $n <= 9; $n++) {
            $line = $stockMov->addLine();
            $line->setQuantity($n);
            $line->setUnitPrice($n * 1.2);

            $debit ? $line->setDebitAmount($n * $n * 1.2) :
                    $line->setCreditAmount($n * $n * 1.2);

            $line->setDescription("Desc of line ".\strval($n));
            $line->setProductCode("CODE_".\strval($n));
            $line->setProductDescription("Prod desc of line ".\strval($n));
            $line->setSettlementAmount(.1 * $n);
            $line->setUnitOfMeasure("UN");

            $tax = $line->getTax();
            $tax->setTaxCode(new MovementTaxCode($taxTableEntry->getTaxCode()->get()));
            $tax->setTaxCountryRegion($taxTableEntry->getTaxCountryRegion());
            $tax->setTaxPercentage($taxTableEntry->getTaxPercentage());
            $tax->setTaxType(new MovementTaxType($taxTableEntry->getTaxType()->get()));

            $prod = $auditFile->getMasterFiles()->addProduct();
            $prod->setProductCode($line->getProductCode());
            $prod->setProductDescription($line->getProductDescription());
            $prod->setProductNumberCode($line->getProductCode());
            $prod->setProductType(ProductType::P());
        }
    }

    /**
     * @author João Rebelo
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line $line
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\StockMovement $stockMov
     * @return void
     */
    protected function cloneTaxForExtraLiinesFromLastLine(Line $line,
                                                          StockMovement $stockMov)
    {
        $n        = \count($stockMov->getLine());
        $prevLine = $stockMov->getLine()[$n - 2];
        $prevTax  = $prevLine->getTax();

        $tax = $line->getTax();
        $tax->setTaxCode(clone $prevTax->getTaxCode());
        $tax->setTaxCountryRegion(clone $prevTax->getTaxCountryRegion());
        $tax->setTaxPercentage($prevTax->getTaxPercentage());
        $tax->setTaxType(clone $prevTax->getTaxType());
    }

    /**
     * Create the minimun values of ShipFrom in StockMovement
     * @author João Rebelo
     * @param StockMovement $StockMovement
     * @return void
     */
    protected function createShipFrom(StockMovement $StockMovement)
    {
        $ship = $StockMovement->getShipFrom();
        $addr = $ship->getAddress();
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        $addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");
    }

    /**
     * Create the minimun values of ShipTo in StockMovement
     * @author João Rebelo
     * @param StockMovement $StockMovement
     * @return void
     */
    protected function createShipTo(StockMovement $StockMovement)
    {
        $ship = $StockMovement->getShipTo();
        $addr = $ship->getAddress();
        $addr->setAddressDetail("largo de Santo Estêvão");
        $addr->setCity("Lisboa");
        $addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1109-999");
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testLinesNoContinuesNumber(): void
    {
        $now = new RDate();
        $this->movementOfGoods->setContinuesLines(true);
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] */
        $lineStack = $stockMov->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() + 1);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->movementOfGoods->setContinuesLines(false);
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());

        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());

        $this->iniMovOfGoodsLinesForLinesTest($stockMov);


        /* @var $lineStack \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[] */
        $lineStack = $stockMov->getLine();
        $lastLine  = $lineStack[\count($lineStack) - 1];
        $lastLine->setLineNumber($lastLine->getLineNumber() - 1);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        //$line->setQuantity($n); Test
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity($n);
        //$line->setUnitPrice($n * 1.2); Test
        $line->setCreditAmount($n * $n * 1.2);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        //$line->setCreditAmount($n * $n * 1.2); Test no debit an credit
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GD GD/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov, true);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setDebitAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity($n);
        $line->setUnitPrice($n * 1.2);
        $line->setCreditAmount($n * $n * 1.1); //wrong Qt * UnPrice
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $this->iniMovOfGoodsForLineTest();

        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $this->iniMovOfGoodsLinesForLinesTest($stockMov);

        $n    = \count($stockMov->getLine());
        $line = $stockMov->addLine();
        $line->setQuantity(0.0);
        $line->setUnitPrice(0.0);
        $line->setCreditAmount(0.0);
        $line->setDescription("Desc of line ".\strval($n));
        $line->setProductCode("CODE_".\strval($n));
        $line->setProductDescription("Prod desc of line ".\strval($n));
        $line->setSettlementAmount(.1 * $n);
        $line->setUnitOfMeasure("UN");

        $this->cloneTaxForExtraLiinesFromLastLine($line, $stockMov);

        $this->movementOfGoods->lines($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testOrderReferencesOneOrderReference(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $stockMov->getMovementDate());
        $ref->setOriginatingON("GT A/1");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("NE NE/1");
        $stockMov->setMovementType(MovementType::GT());

        $line  = $stockMov->addLine();
        $ref_1 = $line->addOrderReferences();
        $ref_1->setOrderDate(clone $stockMov->getMovementDate());
        $ref_1->setOriginatingON("OU A/1");

        $ref_2 = $line->addOrderReferences();
        $ref_2->setOrderDate((clone $stockMov->getMovementDate())->addDays(-1));
        $ref_2->setOriginatingON("GR A/2");

        $ref_3 = $line->addOrderReferences();
        $ref_3->setOrderDate(clone $stockMov->getMovementDate());
        $ref_3->setOriginatingON("GT A/3");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::N());
        $stockMov->getDocumentStatus()->setSourceBilling(SourceBilling::P());


        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("NE NE/1");

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("OU A/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");
        $ref->setOrderDate((new RDate())->addDays(1));

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GR A/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT GT/1");
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $ref  = $line->addOrderReferences();
        $ref->setOriginatingON("GT 1");
        $ref->setOrderDate(clone $stockMov->getMovementDate());

        $this->movementOfGoods->orderReferences($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $productCode = "COD999";
        $product     = $auditFile->getMasterFiles()->addProduct();
        $product->setProductCode($productCode);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setProductCode($productCode);

        $this->movementOfGoods->producCode($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setProductCode("COD999");

        $this->movementOfGoods->producCode($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();

        $this->movementOfGoods->producCode($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($tax->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTaxPercentageZeroExceptionCodeNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(MovementTaxCode::OUT());
        $tax->setTaxType(MovementTaxType::NS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(0.00);
        $tax->setTaxCode(MovementTaxCode::OUT());
        $tax->setTaxType(MovementTaxType::NS());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        // The percentage is no setted to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(MovementTaxCode::ISE());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");

        $tax = $line->getTax();
        // The percentage is no setted to zero in a ISE for exceprion test
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(MovementTaxCode::ISE());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $line->setTaxExemptionReason("reason");
        $line->setTaxExemptionCode(TaxExemptionCode::M99());

        $tax = $line->getTax();
        $tax->setTaxPercentage(9.00);
        $tax->setTaxCode(MovementTaxCode::ISE());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $auditFile->getMasterFiles()->addTaxTableEntry();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(13.00);
        $taxTableEntry->setTaxCode(TaxCode::RED());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(new RDate());
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(-1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage(23.00);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate((new RDate())->addDays(1));
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage(23.00);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile     = $this->movementOfGoods->getAuditFile();
        $taxPerc       = 23.00;
        $taxTableEntry = $auditFile->getMasterFiles()->addTaxTableEntry();
        $taxTableEntry->setTaxPercentage($taxPerc);
        $taxTableEntry->setTaxCode(TaxCode::NOR());
        $taxTableEntry->setTaxCountryRegion(TaxCountryRegion::ISO_PT());
        $taxTableEntry->setTaxExpirationDate(null);
        $taxTableEntry->setTaxType(TaxType::IVA());
        $taxTableEntry->setDescription("Tax description");

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $line = $stockMov->addLine();
        $tax  = $line->getTax();
        $tax->setTaxPercentage($taxPerc);
        $tax->setTaxCode(MovementTaxCode::NOR());
        $tax->setTaxType(MovementTaxType::IVA());
        $tax->setTaxCountryRegion(TaxCountryRegion::ISO_PT());

        $this->movementOfGoods->tax($line, $stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testTotalsWrongGross(): void
    {
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 122.99;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal(100.00);
        $totals->setTaxPayable(23.00);
        $totals->setGrossTotal(122.99);

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal(123.00, 4));

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross - 0.01, 4));

        $this->movementOfGoods->setDeltaTotalDoc(0.01);

        $this->movementOfGoods->totals($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->movementOfGoods->setNetTotal(new UDecimal($net - $delta, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->movementOfGoods->setNetTotal(new UDecimal($net - $delta, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $this->movementOfGoods->setDeltaTotalDoc($delta);

        $this->movementOfGoods->totals($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax - $delta, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $this->movementOfGoods->setDeltaTotalDoc($delta);

        $this->movementOfGoods->totals($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $stockMov->setDocTotalcal($docTotalcal);

        $this->movementOfGoods->totals($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $delta     = 0.01;
        $rate      = 0.5;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount(($gross / $rate) + $delta);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $stockMov->setDocTotalcal($docTotalcal);

        $this->movementOfGoods->setDeltaCurrency($delta);
        $this->movementOfGoods->totals($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile = $this->movementOfGoods->getAuditFile();
        $net       = 100.00;
        $tax       = 23.00;
        $gross     = 123.00;
        $rate      = 0.5;

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $totals   = $stockMov->getDocumentTotals();
        $totals->setNetTotal($net);
        $totals->setTaxPayable($tax);
        $totals->setGrossTotal($gross);
        $currency = $totals->getCurrency();
        $currency->setCurrencyAmount($gross / $rate);
        $currency->setExchangeRate($rate);
        $currency->setCurrencyCode(CurrencyCode::ISO_AED());

        $this->movementOfGoods->setNetTotal(new UDecimal($net, 4));
        $this->movementOfGoods->setTaxPayable(new UDecimal($tax, 4));
        $this->movementOfGoods->setGrossTotal(new UDecimal($gross, 4));

        $docTotalcal = new \Rebelo\SaftPt\Validate\DocTotalCalc();
        $docTotalcal->setGrossTotal($gross);
        $docTotalcal->setNetTotal($net);
        $docTotalcal->setTaxPayable($tax);
        $docTotalcal->setGrossTotalFromCurrency($gross / $rate);
        $stockMov->setDocTotalcal($docTotalcal);

        $this->movementOfGoods->totals($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
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

        $auditFile = $this->movementOfGoods->getAuditFile();

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->sign($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }

        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash($hash);
        $this->movementOfGoods->setLastHash("");
        $this->movementOfGoods->sign($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }

        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal()
        );

        $stockMov->setHash($hash);
        $this->movementOfGoods->setLastHash("");
        $this->movementOfGoods->sign($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash("a".\substr($hash, 0, 171));
        $this->movementOfGoods->setLastHash("");
        $this->movementOfGoods->sign($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());

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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), ""
        );

        $stockMov->setHash("a".\substr($hash, 0, 171));
        $this->movementOfGoods->setLastHash("");
        $this->movementOfGoods->sign($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());

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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentTotals()->setGrossTotal(999.99);

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $sign      = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);
        $lasetHash = "AAA";
        $hash      = $sign->createSignature(
            $stockMov->getMovementDate(), $stockMov->getSystemEntryDate(),
            $stockMov->getDocumentNumber(),
            $stockMov->getDocumentTotals()->getGrossTotal(), $lasetHash
        );

        $stockMov->setHash("a".\substr($hash, 0, 171));
        $this->movementOfGoods->setLastHash($lasetHash);
        $this->movementOfGoods->sign($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());

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
            return;
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail("Was not possible to get file contents of public key file");
            return;
        }
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();

        $sign = new \Rebelo\SaftPt\Sign\Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $firstDoc       = $movOfGoodsDocs->addStockMovement();
        $firstDoc->setMovementDate(clone $now);
        $firstDoc->setSystemEntryDate(clone $now);
        $firstDoc->setDocumentNumber("GT GT/1");
        $firstDoc->setMovementType(MovementType::GT());
        $firstDoc->getDocumentTotals()->setGrossTotal(999.99);

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
        $secondDoc->setMovementType(MovementType::GT());
        $secondDoc->getDocumentTotals()->setGrossTotal(999.99);


        $docStatus = $secondDoc->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $secondHash = $sign->createSignature(
            $secondDoc->getMovementDate(), $secondDoc->getSystemEntryDate(),
            $secondDoc->getDocumentNumber(),
            $secondDoc->getDocumentTotals()->getGrossTotal(), $firstHash
        );

        $secondDoc->setHash($secondHash);

        $this->movementOfGoods->setLastHash($firstHash);
        $this->movementOfGoods->sign($secondDoc);

        $this->assertTrue($this->movementOfGoods->isValid());
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
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $now            = new RDate();
        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateNoHeaderStartDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateNoHeaderEndDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateHeaderStartDateLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateHeaderEndDateEarlier(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-2));
        $header->setEndDate((clone $now)->addDays(-1));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastDocDateAnsSystemNull(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(1));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastDocDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->setLastDocDate((clone $now)->addDays(1));
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateLastSysEntDateIsLater(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-1));
        $header->setEndDate((clone $now)->addDays(2));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->setLastDocDate(clone $now);
        $this->movementOfGoods->setLastSystemEntryDate((clone $now)->addSeconds(1));
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDateAllDatesEqual(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate(clone $now);
        $header->setEndDate(clone $now);

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->setLastDocDate(clone $now);
        $this->movementOfGoods->setLastSystemEntryDate(clone $now);
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testDocDateAndSyEntryDate(): void
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile = $this->movementOfGoods->getAuditFile();
        $now       = new RDate();
        $header    = $auditFile->getHeader();
        $header->setStartDate((clone $now)->addDays(-9));
        $header->setEndDate((clone $now)->addDays(9));

        /* @var $movOfGoodsDocs \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods */
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setMovementDate(clone $now);
        $stockMov->setSystemEntryDate(clone $now);
        $stockMov->setDocumentNumber("GT GT/2");
        $stockMov->setMovementType(MovementType::GT());

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());

        $this->movementOfGoods->setLastDocDate((clone $now)->addDays(-1));
        $this->movementOfGoods->setLastSystemEntryDate((clone $now)->addSeconds(-1));
        $this->movementOfGoods->stockMovementDateAndSystemEntryDate($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * Common properties for ship test
     * @author João Rebelo
     * @return StockMovement
     */
    protected function createStockMovForTestShip(): StockMovement
    {
        /* @var $auditFile \Rebelo\SaftPt\AuditFile\AuditFile */
        $auditFile      = $this->movementOfGoods->getAuditFile();
        $movOfGoodsDocs = $auditFile->getSourceDocuments()->getMovementOfGoods();
        $stockMov       = $movOfGoodsDocs->addStockMovement();
        $stockMov->setDocTotalcal(new DocTotalCalc());
        $stockMov->setMovementDate(new RDate());
        $stockMov->setDocumentNumber("GT GT/1");
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->setAtcud("0");

        $docStatus = $stockMov->getDocumentStatus();
        $docStatus->setSourceBilling(SourceBilling::P());
        $docStatus->setMovementStatus(MovementStatus::N());
        $docStatus->setMovementStatusDate(clone $stockMov->getMovementDate());
        $docStatus->setSourceID("Rebelo");

        return $stockMov;
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipement(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementCancelStatusDateAfterMoveStartTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())->addHours(1)
        );

        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );

        $stockMov->getDocumentStatus()->setMovementStatus(
            MovementStatus::A()
        );

        $stockMov->getDocumentStatus()->setMovementStatusDate(
            (clone $stockMov->getMovementStartTime())->addMinutes(1)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertTrue(
            $stockMov->getDocumentStatus()->getErrorRegistor()->hasErrors()
        );
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipFrom(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        // $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementGlobal(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov); 
        // Global not have ShipTo And Only Can be of type GT
        $stockMov->setMovementType(MovementType::GT());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }
    
    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementResumeNoShipFrom(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        
        $stockMov->setMovementType(MovementType::GT());
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::R());
        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipToNoGT(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementShipfromDeliveryDateLaterShipToDeliveryDate()
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");

        $stockMov->getShipFrom()->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(3)
        );

        $stockMov->getShipTo()->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(2)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }
    
    
    

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementShipfromDeliveryDateLaterShipToDeliveryDateInDocResume()
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->getDocumentStatus()->setMovementStatus(MovementStatus::R());
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");

        $stockMov->getShipFrom()->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(3)
        );

        $stockMov->getShipTo()->setDeliveryDate(
            (clone $stockMov->getMovementDate())->addHours(2)
        );

        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }
    

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoMovementStartTime(): void
    {
        $stockMov = $this->createStockMovForTestShip();
//        $stockMov->setMovementStartTime(
//            clone $stockMov->getMovementDate()
//        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementMovementStartTimeEarlierDocDate(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            (clone $stockMov->getMovementDate())->addMinutes(-1)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementMovementStartTimeEarlierSystemDateSourceBillP(): void
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
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementMovementStartTimeEarlierSystemDateSourceBillNotP(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->getDocumentStatus()->setSourceBilling(SourceBilling::I());
        $stockMov->setSystemEntryDate(
            (clone $stockMov->getMovementDate())->addMinutes(30)
        );
        $stockMov->setMovementStartTime(
            (clone $stockMov->getSystemEntryDate())->addMinutes(-1)
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoMovementEndTime(): void
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
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertTrue($this->movementOfGoods->isValid());
        $this->assertFalse(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementMovementEndTimeEalierStartMovTime(): void
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
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipFromAddress(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        //$this->createShipFrom($stockMov);
        $stockMov->getShipFrom();
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipFromCity(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        //$this->createShipFrom($stockMov);
        $addr = $stockMov->getShipFrom()->getAddress();
        $addr->setAddressDetail("Rua das Escolas Gerais");
        //$addr->setCity("Lisboa");
        $addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");
        
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipFromCountry(): void
    {
        
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        //$this->createShipFrom($stockMov);
        $addr = $stockMov->getShipFrom()->getAddress();
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        //$addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");
        
        $this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementNoShipToAddress(): void
    {        
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GR());
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementShipToAddressNoAddress(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $stockMov->getShipTo();
        $stockMov->setMovementType(MovementType::GC());

        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementShipToAddressNoCity(): void
    {
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GC());
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $addr = $stockMov->getShipTo()->getAddress();
        $addr->setAddressDetail("Rua das Escolas Gerais");
        //$addr->setCity("Lisboa");
        $addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");
        
        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }

    /**
     * @author João Rebelo
     * @test
     * @return void
     */
    public function testShipementShipToAddressNoCountry(): void
    {        
        $stockMov = $this->createStockMovForTestShip();
        $stockMov->setMovementType(MovementType::GC());
        $stockMov->setMovementStartTime(
            clone $stockMov->getMovementDate()
        );
        $stockMov->setMovementEndTime(
            (clone $stockMov->getMovementDate())->addHours(9)
        );
        $stockMov->getShipFrom()->addDeliveryID("AA-99-99");
        $this->createShipFrom($stockMov);
        //$this->createShipTo($stockMov);
        $addr = $stockMov->getShipTo()->getAddress();
        $addr->setAddressDetail("Rua das Escolas Gerais");
        $addr->setCity("Lisboa");
        //$addr->setCountry(\Rebelo\SaftPt\AuditFile\Country::ISO_PT());
        $addr->setPostalCode("1100-999");
        
        $this->movementOfGoods->shipement($stockMov);

        $this->assertFalse($this->movementOfGoods->isValid());
        $this->assertTrue(
            $this->movementOfGoods->getAuditFile()->getErrorRegistor()->hasErrors()
        );
        $this->assertNotEmpty($stockMov->getError());
    }
}