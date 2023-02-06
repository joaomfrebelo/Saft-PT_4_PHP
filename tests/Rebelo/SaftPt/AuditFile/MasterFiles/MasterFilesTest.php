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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

require_once __DIR__.DIRECTORY_SEPARATOR.'CustomerTest.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'CustomsDetailsTest.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'SupplierTest.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'TaxTableEntryTest.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'ProductTest.php';

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\Country;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\NotImplemented;
use Rebelo\SaftPt\CommuneTest;
use Rebelo\SaftPt\TXmlTest;

/**
 * Class MasterFilesTest
 *
 * @author João Rebelo
 */
class MasterFilesTest extends TestCase
{

    use TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(MasterFiles::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $this->assertInstanceOf(MasterFiles::class, $master);
        $this->assertEquals(array(), $master->getCustomer());
        $this->assertEquals(array(), $master->getProduct());
        $this->assertEquals(array(), $master->getSupplier());
        $this->assertEquals(array(), $master->getTaxTableEntry());
        $this->assertFalse($master->isFinalConsumerAdd());
        try {
            $master->getGeneralLedgerAccounts();
            $this->fail("getGeneralLedgerAccounts should throw \Rebelo\SaftPt\AuditFile\NotImplemented");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(
                NotImplemented::class, $ex
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCustomerSatck(): void
    {
        $master = new MasterFiles(new ErrorRegister());

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $customer = $master->addCustomer();
            $customer->setCustomerID(\strval($n));
            $stack    = $master->getCustomer();
            $this->assertEquals(
                \strval($n), $stack[$n]->getCustomerID()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSupplierSatck(): void
    {
        $master = new MasterFiles(new ErrorRegister());

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $supplier = $master->addSupplier();
            $supplier->setSupplierID(\strval($n));
            $stack    = $master->getSupplier();
            $this->assertEquals(
                \strval($n), $stack[$n]->getSupplierID()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testProductSatck(): void
    {
        $master = new MasterFiles(new ErrorRegister());

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $product = $master->addProduct();
            $product->setProductNumberCode(\strval($n));
            $stack   = $master->getProduct();
            $this->assertEquals(
                \strval($n), $stack[$n]->getProductNumberCode()
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTaxTableEntrySatck(): void
    {
        $master = new MasterFiles(new ErrorRegister());

        $nCount = 5;
        for ($n = 0; $n < $nCount; $n++) {
            $taxTableEntry = $master->addTaxTableEntry();
            $taxTableEntry->setTaxPercentage((float) $n);
            $stack         = $master->getTaxTableEntry();
            $this->assertEquals(
                (float) $n, $stack[$n]->getTaxPercentage()
            );
        }
    }

    /**
     * Create master file
     * @param int $nCount
     * @return MasterFiles
     */
    public function createMasterFile(int $nCount): MasterFiles
    {
        $master = new MasterFiles(new ErrorRegister());

        for ($n = 0; $n < $nCount; $n++) {
            $customer = $master->addCustomer();
            $customer->setCustomerID(\strval($n));
        }

        for ($n = 0; $n < $nCount; $n++) {
            $supplier = $master->addSupplier();
            $supplier->setSupplierID(\strval($n));
        }

        for ($n = 0; $n < $nCount; $n++) {
            $product = $master->addProduct();
            $product->setProductNumberCode(\strval($n));
        }

        for ($n = 0; $n < $nCount; $n++) {
            $taxTableEntry = $master->addTaxTableEntry();
            $taxTableEntry->setTaxPercentage((float) $n);
        }

        return $master;
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateEmptyXmlNode(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $node   = new \SimpleXMLElement(
            "<".AuditFile::N_AUDITFILE."></".AuditFile::N_AUDITFILE.">"
        );

        $masterNode = $master->createXmlNode($node);
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

        $this->assertEmpty($master->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($master->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($master->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $node   = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $master->createXmlNode($node);
            $this->fail(
                "Creat a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $master = new MasterFiles();
        $node   = new \SimpleXMLElement("<root></root>");
        try {
            $master->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeEmpty(): void
    {
        $master = new MasterFiles();
        $node   = new \SimpleXMLElement(
            "<".AuditFile::N_AUDITFILE."></".AuditFile::N_AUDITFILE.">"
        );

        $xml = $master->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new MasterFiles(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals(0, \count($parsed->getCustomer()));
        $this->assertEquals(0, \count($parsed->getSupplier()));
        $this->assertEquals(0, \count($parsed->getProduct()));
        $this->assertEquals(0, \count($parsed->getTaxTableEntry()));

        $this->assertEmpty($master->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($master->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($master->getErrorRegistor()->getOnSetValue());
    }

    /**
     * Reads MasterFiles from the Demo SAFT in Test\Resources
     * and parse then to MasterFiles class, after that generate a xml from the
     * class and test if the xml strings are equal
     * @throws AuditFileException
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $sourceDocsXml = $saftDemoXml->{MasterFiles::N_MASTERFILES};

        if ($sourceDocsXml->count() === 0) {
            $this->fail("No MasterFiles in XML");
        }

        $masterFiles = new MasterFiles();
        $masterFiles->parseXmlNode($sourceDocsXml);

        $xmlRootNode = (new AuditFile())->createRootElement();

        $auditNode = $xmlRootNode->addChild(
            AuditFile::N_AUDITFILE
        );

        $xml = $masterFiles->createXmlNode($auditNode);

        try {
            $assertXml = $this->xmlIsEqual($sourceDocsXml, $xml);
            $this->assertTrue(
                $assertXml, \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }

        $this->assertEmpty($masterFiles->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($masterFiles->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($masterFiles->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode = new \SimpleXMLElement(
            "<".AuditFile::N_AUDITFILE."></".AuditFile::N_AUDITFILE.">"
        );
        $master       = new MasterFiles(new ErrorRegister());
        $xml          = $master->createXmlNode($customerNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($master->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($master->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($master->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllProductCode(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = [0 => "AAA", 1 => "BBB", 2 => "CCC"];

        foreach ($ids as $id) {
            $pro = $master->addProduct();
            $pro->setProductCode($id);
        }
        $master->addProduct();

        $this->assertSame($ids, $master->getAllProductCode());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllProductCodeAfterAdd(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = [0 => "AAA", 1 => "BBB", 2 => "CCC"];

        foreach ($ids as $id) {
            $pro = $master->addProduct();
            $pro->setProductCode($id);
        }
        $master->addProduct();

        $this->assertSame(
            \count($ids), \count($master->getAllProductCode())
        );

        $pro = $master->addProduct();
        $pro->setProductCode("DDD");

        $this->assertSame(
            \count($ids) + 1, \count($master->getAllProductCode())
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllSupplierID(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = ["AAA", "BBB", "CCC"];

        foreach ($ids as $id) {
            $supplier = $master->addSupplier();
            $supplier->setSupplierID($id);
        }
        $master->addSupplier();

        $this->assertSame($ids, $master->getAllSupplierID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllSupplierIDAftertAdd(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = ["AAA", "BBB", "CCC"];

        foreach ($ids as $id) {
            $supplier = $master->addSupplier();
            $supplier->setSupplierID($id);
        }
        $master->addSupplier();

        $this->assertSame(
            \count($ids), \count($master->getAllSupplierID())
        );

        $supplier = $master->addSupplier();
        $supplier->setSupplierID("DDD");

        $this->assertSame(
            \count($ids) + 1, \count($master->getAllSupplierID())
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllCustomerID(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = ["AAA", "BBB", "CCC"];

        foreach ($ids as $id) {
            $customer = $master->addCustomer();
            $customer->setCustomerID($id);
        }
        $master->addCustomer();

        $this->assertSame($ids, $master->getAllCustomerID());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testGetAllCustomerIDAfterAdd(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $ids    = ["AAA", "BBB", "CCC"];

        foreach ($ids as $id) {
            $customer = $master->addCustomer();
            $customer->setCustomerID($id);
        }
        $master->addCustomer();

        $this->assertSame(
            \count($ids), \count($master->getAllCustomerID())
        );

        $customer = $master->addCustomer();
        $customer->setCustomerID("DDD");

        $this->assertSame(
            \count($ids) + 1, \count($master->getAllCustomerID())
        );


    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testAddCustomerFinalConsumer(): void
    {
        $master = new MasterFiles(new ErrorRegister());
        $master->addCustomerFinalConsumer();

        $this->assertTrue($master->isFinalConsumerAdd());

        $customer = $master->getCustomer()[0];

        $this->assertSame(
            AuditFile::CONSUMIDOR_FINAL_ID, $customer->getCustomerID()
        );

        $this->assertSame(
            AuditFile::DESCONHECIDO, $customer->getAccountID()
        );

        $this->assertSame(
            AuditFile::CONSUMIDOR_FINAL_TAX_ID, $customer->getCustomerTaxID()
        );

        $this->assertSame(
            AuditFile::CONSUMIDOR_FINAL, $customer->getCompanyName()
        );

        $this->assertNull($customer->getContact());

        $addr = $customer->getBillingAddress();
        $this->assertNull($addr->getBuildingNumber());
        $this->assertNull($addr->getStreetName());

        $this->assertSame(
            AuditFile::DESCONHECIDO, $addr->getAddressDetail()
        );

        $this->assertSame(
            AuditFile::DESCONHECIDO, $addr->getCity()
        );

        $this->assertSame(
            AuditFile::DESCONHECIDO, $addr->getPostalCode()
        );

        $this->assertNull($addr->getRegion());

        $this->assertSame(
            Country::DESCONHECIDO,
            $addr->getCountry()->get()
        );

        $this->assertEmpty($customer->getShipToAddress());
    }
}
