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

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\Commune;

/**
 * Class XmlStructureTest
 *
 * @author João Rebelo
 */
class XmlStructureTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(AuditFile::class))->testReflection(AuditFile::class);
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlGlobalWithoutErrors(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_DEMO_PATH)
                ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertTrue($test);
        $this->assertFalse($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlGlobalWithErrors(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_ERROR_PATH) ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingSupplier(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_SUPPLIER_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingProductInWorkingDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_WORK_DOC_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingProductInStockMovementDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_MOV_STK_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingProductInInvoiceDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_PRODUCT_INVOICE_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingCustomerInStockMovementDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_CUSTOMER_STK_MV_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingCustomerInInvoiceDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_MISSING_CUSTOMER_INVOICE_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingCustomerInWorkingDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_CUSTOMER_MISSING_WORK_DOC_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testXmlMissingCustomerInPaymentDocument(): void
    {
        $auditFile      = new AuditFile();
        $validateXmlStr = new XmlStructure($auditFile);
        $xml            = \file_get_contents(SAFT_CUSTOMER_MISSING_PAYMENT_PATH)
            ?: throw new \Exception("Cannot read xml file");
        $test           = $validateXmlStr->validate($xml);
        $this->assertFalse($test);
        $this->assertEmpty($auditFile->getErrorRegistor()->getExceptionErrors());
        $this->assertNotEmpty($auditFile->getErrorRegistor()->getLibXmlError());
        $this->assertTrue($auditFile->getErrorRegistor()->hasErrors());
    }
}
