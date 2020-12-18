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

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\XmlStructure;
use Rebelo\SaftPt\AuditFile\AuditFile;

/**
 * Class XmlStructureTest
 *
 * @author João Rebelo
 */
class XmlStructureTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(AuditFile::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlGlobalWithoutErrors(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_DEMO_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertTrue($test);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlGlobalWithErrors(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_ERROR_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingSupplier(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_SUPPLIER_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingProductInWorkingDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_WORK_DOC_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingProductInStockMovementDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_MOV_STK_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingProductInInvoiceDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_INVOICE_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingCustomerInStockMovementDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_CUSTOMER_STK_MV_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingCustomerInInvoiceDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_CUSTOMER_INVOICE_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingCustomerInWorkingDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_CUSTOMER_MISSING_WORK_DOC_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testXmlMissingCustomerInPaymentDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_CUSTOMER_MISSING_PAYMENT_PATH);
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }
}
