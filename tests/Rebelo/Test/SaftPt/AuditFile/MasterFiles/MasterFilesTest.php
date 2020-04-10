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

namespace Rebelo\Test\SaftPt\AuditFile\MasterFile;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'CustomerTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'CustomsDetailsTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'SupplierTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'TaxTableEntryTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ProductTest.php';

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;
use Rebelo\SaftPt\AuditFile\MasterFiles\Customer;
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\MasterFiles\Product;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxTableEntry;
use Rebelo\SaftPt\AuditFile\ExportType;

/**
 * Class MasterFilesTest
 *
 * @author João Rebelo
 */
class MasterFilesTest
    extends TestCase
{

    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(MasterFiles::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $master = new MasterFiles();
        $this->assertInstanceOf(MasterFiles::class, $master);
        $this->assertEquals(array(), $master->getCustomer());
        $this->assertEquals(array(), $master->getProduct());
        $this->assertEquals(array(), $master->getSupplier());
        $this->assertEquals(array(), $master->getTaxTableEntry());
        try
        {
            $master->getGeneralLedgerAccounts();
            $this->fail("getGeneralLedgerAccounts should throw \Rebelo\SaftPt\AuditFile\NotImplemented");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\NotImplemented::class, $ex
            );
        }
    }

    public function testCustomerSatck()
    {
        $custTest = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\CustomerTest();
        $customer = $custTest->createCustomer();
        if (($customer instanceof Customer) === false)
        {
            $this->fail("Was not possible to create the Customer instance");
        }

        $master = new MasterFiles();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            $nCustomer = clone $customer;
            $nCustomer->setCustomerID(\strval($n));
            $this->assertEquals($n, $master->addToCustomer($nCustomer));
            $this->assertTrue($master->issetCustomer($n));
            /* @var $stack Customer[] */
            $stack     = $master->getCustomer();
            $this->assertEquals(
                \strval($n), $stack[$n]->getCustomerID()
            );
        }

        $unset   = 2;
        $master->unsetCustomer($unset);
        /* @var $unStack Customer[] */
        $unStack = $master->getCustomer();
        $this->assertEquals($nCount - 1, \count($unStack));
        for ($n = 0; $n < $nCount; $n++)
        {
            if ($n === $unset)
            {
                $this->assertFalse(\array_key_exists($unset, $unStack));
                continue;
            }
            $this->assertEquals(
                \strval($n), $unStack[$n]->getCustomerID()
            );
        }
    }

    public function testSupplierSatck()
    {
        $supplierTest = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\SupplierTest();
        $supplier     = $supplierTest->createSupplier();
        if (($supplier instanceof Supplier) === false)
        {
            $this->fail("Was not possible to create the Supplier instance");
        }

        $master = new MasterFiles();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            $nSupplier = clone $supplier;
            $nSupplier->setSupplierID(\strval($n));
            $this->assertEquals($n, $master->addToSupplier($nSupplier));
            $this->assertTrue($master->issetSupplier($n));
            /* @var $stack Supplier[] */
            $stack     = $master->getSupplier();
            $this->assertEquals(
                \strval($n), $stack[$n]->getSupplierID()
            );
        }

        $unset   = 2;
        $master->unsetSupplier($unset);
        /* @var $unStack Supplier[] */
        $unStack = $master->getSupplier();
        $this->assertEquals($nCount - 1, \count($unStack));
        for ($n = 0; $n < $nCount; $n++)
        {
            if ($n === $unset)
            {
                $this->assertFalse(\array_key_exists($unset, $unStack));
                continue;
            }
            $this->assertEquals(
                \strval($n), $unStack[$n]->getSupplierID()
            );
        }
    }

    public function testProductSatck()
    {
        $productTest = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\ProductTest();
        $product     = $productTest->createProduct();
        if (($product instanceof Product) === false)
        {
            $this->fail("Was not possible to create the Product instance");
        }

        $master = new MasterFiles();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            $nProduct = clone $product;
            $nProduct->setProductNumberCode(\strval($n));
            $this->assertEquals($n, $master->addToProduct($nProduct));
            $this->assertTrue($master->issetProduct($n));
            /* @var $stack Product[] */
            $stack    = $master->getProduct();
            $this->assertEquals(
                \strval($n), $stack[$n]->getProductNumberCode()
            );
        }

        $unset   = 2;
        $master->unsetProduct($unset);
        /* @var $unStack Product[] */
        $unStack = $master->getProduct();
        $this->assertEquals($nCount - 1, \count($unStack));
        for ($n = 0; $n < $nCount; $n++)
        {
            if ($n === $unset)
            {
                $this->assertFalse(\array_key_exists($unset, $unStack));
                continue;
            }
            $this->assertEquals(
                \strval($n), $unStack[$n]->getProductNumberCode()
            );
        }
    }

    public function testTaxTableEntrySatck()
    {
        $taxTableEntryTest = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\TaxTableEntryTest();
        $taxTableEntry     = $taxTableEntryTest->createTaxTableEntry();
        if (($taxTableEntry instanceof TaxTableEntry) === false)
        {
            $this->fail("Was not possible to create the TaxTableEntry instance");
        }

        $master = new MasterFiles();

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++)
        {
            $nTaxTableEntry = clone $taxTableEntry;
            $nTaxTableEntry->setTaxPercentage((float) $n);
            $this->assertEquals($n, $master->addToTaxTableEntry($nTaxTableEntry));
            $this->assertTrue($master->issetTaxTableEntry($n));
            /* @var $stack TaxTableEntry[] */
            $stack          = $master->getTaxTableEntry();
            $this->assertEquals(
                (float) $n, $stack[$n]->getTaxPercentage()
            );
        }

        $unset   = 2;
        $master->unsetTaxTableEntry($unset);
        /* @var $unStack TaxTableEntry[] */
        $unStack = $master->getTaxTableEntry();
        $this->assertEquals($nCount - 1, \count($unStack));
        for ($n = 0; $n < $nCount; $n++)
        {
            if ($n === $unset)
            {
                $this->assertFalse(\array_key_exists($unset, $unStack));
                continue;
            }
            $this->assertEquals(
                (float) $n, $unStack[$n]->getTaxPercentage()
            );
        }
    }

    public function createMasterFile(int $nCount): MasterFiles
    {
        AuditFile::$exportType = new ExportType(ExportType::C);
        $master                = new MasterFiles();
        $custTest              = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\CustomerTest();
        $customer              = $custTest->createCustomer();
        $supplierTest          = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\SupplierTest();
        $supplier              = $supplierTest->createSupplier();
        $productTest           = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\ProductTest();
        $product               = $productTest->createProduct();
        $taxTableEntryTest     = new \Rebelo\Test\SaftPt\AuditFile\MasterFile\TaxTableEntryTest();
        $taxTableEntry         = $taxTableEntryTest->createTaxTableEntry();

        for ($n = 0; $n < $nCount; $n++)
        {
            $nCustomer = clone $customer;
            $nCustomer->setCustomerID(\strval($n));
            $master->addToCustomer($nCustomer);
        }

        for ($n = 0; $n < $nCount; $n++)
        {
            $nSupplier = clone $supplier;
            $nSupplier->setSupplierID(\strval($n));
            $master->addToSupplier($nSupplier);
        }


        for ($n = 0; $n < $nCount; $n++)
        {
            $nProduct = clone $product;
            $nProduct->setProductNumberCode(\strval($n));
            $master->addToProduct($nProduct);
        }


        for ($n = 0; $n < $nCount; $n++)
        {
            $nTaxTableEntry = clone $taxTableEntry;
            $nTaxTableEntry->setTaxPercentage((float) $n);
            $master->addToTaxTableEntry($nTaxTableEntry);
        }
        return $master;
    }

    public function testCreateXmlNode()
    {

        $nCount = 5;

        $master = $this->createMasterFile($nCount);

        $node = new \SimpleXMLElement(
            "<" . AuditFile::N_AUDITFILE . "></" . AuditFile::N_AUDITFILE . ">"
        );

        $masterNode = $master->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $masterNode);
        $this->assertEquals(MasterFiles::N_MASTERFILES, $masterNode->getName());

        $this->assertEquals(
            $nCount,
            $node->{MasterFiles::N_MASTERFILES}->{Customer::N_CUSTOMER}->count()
        );
        $this->assertEquals(
            $nCount,
            $node->{MasterFiles::N_MASTERFILES}->{Supplier::N_SUPPLIER}->count()
        );
        $this->assertEquals(
            $nCount,
            $node->{MasterFiles::N_MASTERFILES}->{Product::N_PRODUCT}->count()
        );
        $this->assertEquals(
            $nCount,
            $node->{MasterFiles::N_MASTERFILES}->{MasterFiles::N_TAXTABLE}->{TaxTableEntry::N_TAXTABLEENTRY}->count()
        );

        for ($n = 0; $n < $nCount; $n++)
        {
            $customerNode = $node->{MasterFiles::N_MASTERFILES}->{Customer::N_CUSTOMER}[$n];
            $this->assertEquals(
                \strval($n), (string) $customerNode->{Customer::N_CUSTOMERID}
            );
        }

        for ($n = 0; $n < $nCount; $n++)
        {
            $supplierNode = $node->{MasterFiles::N_MASTERFILES}->{Supplier::N_SUPPLIER}[$n];
            $this->assertEquals(
                \strval($n), (string) $supplierNode->{Supplier::N_SUPPLIERID}
            );
        }

        for ($n = 0; $n < $nCount; $n++)
        {
            $productNode = $node->{MasterFiles::N_MASTERFILES}->{Product::N_PRODUCT}[$n];
            $this->assertEquals(
                \strval($n),
                        (string) $productNode->{Product::N_PRODUCTNUMBERCODE}
            );
        }

        for ($n = 0; $n < $nCount; $n++)
        {
            $taxTableEntryNode = $node->{MasterFiles::N_MASTERFILES}
                ->{MasterFiles::N_TAXTABLE}->{TaxTableEntry::N_TAXTABLEENTRY}[$n];
            $this->assertEquals(
                \strval($n),
                        (string) $taxTableEntryNode->{TaxTableEntry::N_TAXPERCENTAGE}
            );
        }

        // Test simple export
        $simple                = new \SimpleXMLElement(
            "<" . AuditFile::N_AUDITFILE . "></" . AuditFile::N_AUDITFILE . ">"
        );
        AuditFile::$exportType = new ExportType(ExportType::S);
        $simpleNode            = $master->createXmlNode($simple);
        $this->assertInstanceOf(\SimpleXMLElement::class, $simpleNode);
        $this->assertEquals(MasterFiles::N_MASTERFILES, $simpleNode->getName());

        $this->assertEquals(
            0,
            $simple->{MasterFiles::N_MASTERFILES}->{Customer::N_CUSTOMER}->count()
        );
        $this->assertEquals(
            0,
            $simple->{MasterFiles::N_MASTERFILES}->{Supplier::N_SUPPLIER}->count()
        );
        $this->assertEquals(
            0,
            $simple->{MasterFiles::N_MASTERFILES}->{Product::N_PRODUCT}->count()
        );
        $this->assertEquals(
            $nCount,
            $simple->{MasterFiles::N_MASTERFILES}->{MasterFiles::N_TAXTABLE}->{TaxTableEntry::N_TAXTABLEENTRY}->count()
        );
    }

    public function testCreateEmptyXmlNode()
    {
        AuditFile::$exportType = new ExportType(ExportType::C);
        $master                = new MasterFiles();
        $node                  = new \SimpleXMLElement(
            "<" . AuditFile::N_AUDITFILE . "></" . AuditFile::N_AUDITFILE . ">"
        );
        $masterNode            = $master->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $masterNode);
        $this->assertEquals(MasterFiles::N_MASTERFILES, $masterNode->getName());

        $this->assertEquals(
            0,
            $node->{MasterFiles::N_MASTERFILES}->{Customer::N_CUSTOMER}->count()
        );
        $this->assertEquals(
            0,
            $node->{MasterFiles::N_MASTERFILES}->{Supplier::N_SUPPLIER}->count()
        );
        $this->assertEquals(
            0,
            $node->{MasterFiles::N_MASTERFILES}->{Product::N_PRODUCT}->count()
        );
        $this->assertEquals(
            0,
            $node->{MasterFiles::N_MASTERFILES}->{MasterFiles::N_TAXTABLE}->count()
        );
    }

    public function testCreateXmlNodeWrongName()
    {
        $master = new MasterFiles();
        $node   = new \SimpleXMLElement("<root></root>"
        );
        try
        {
            $masterNode = $master->createXmlNode($node);
            $this->fail("Creat a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testParseXmlNodeWrongName()
    {
        $master = new MasterFiles();
        $node   = new \SimpleXMLElement("<root></root>"
        );
        try
        {
            $masterNode = $master->parseXmlNode($node);
            $this->fail("Parse a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    public function testParseXmlNode()
    {
        $nCount = 5;
        $master = $this->createMasterFile($nCount);
        $node   = new \SimpleXMLElement(
            "<" . AuditFile::N_AUDITFILE . "></" . AuditFile::N_AUDITFILE . ">"
        );

        $xml    = $master->createXmlNode($node)->asXML();
        $parsed = new MasterFiles();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($nCount, \count($parsed->getCustomer()));
        $this->assertEquals($nCount, \count($parsed->getSupplier()));
        $this->assertEquals($nCount, \count($parsed->getProduct()));
        $this->assertEquals($nCount, \count($parsed->getTaxTableEntry()));

        /* @var $customerStack Customer[] */
        $customerStack = $parsed->getCustomer();
        for ($n = 0; $n < $nCount; $n++)
        {
            $this->assertEquals(
                \strval($n), $customerStack[$n]->getCustomerID()
            );
        }

        /* @var $supplierStack Supplier[] */
        $supplierStack = $parsed->getSupplier();
        for ($n = 0; $n < $nCount; $n++)
        {
            $this->assertEquals(
                \strval($n), $supplierStack[$n]->getSupplierID()
            );
        }

        /* @var $productStack Product[] */
        $productStack = $parsed->getProduct();
        for ($n = 0; $n < $nCount; $n++)
        {
            $this->assertEquals(
                \strval($n), $productStack[$n]->getProductNumberCode()
            );
        }

        /* @var $taxTableEntryStack TaxTableEntry[] */
        $taxTableEntryStack = $parsed->getTaxTableEntry();
        for ($n = 0; $n < $nCount; $n++)
        {
            $this->assertEquals(
                (float) $n, $taxTableEntryStack[$n]->getTaxPercentage()
            );
        }
    }

    public function testParseXmlNodeEmpty()
    {
        AuditFile::$exportType = new ExportType(ExportType::C);
        $master                = new MasterFiles();
        $node                  = new \SimpleXMLElement(
            "<" . AuditFile::N_AUDITFILE . "></" . AuditFile::N_AUDITFILE . ">"
        );

        $xml    = $master->createXmlNode($node)->asXML();
        $parsed = new MasterFiles();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals(0, \count($parsed->getCustomer()));
        $this->assertEquals(0, \count($parsed->getSupplier()));
        $this->assertEquals(0, \count($parsed->getProduct()));
        $this->assertEquals(0, \count($parsed->getTaxTableEntry()));
    }

}
