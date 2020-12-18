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

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\MasterFiles\Supplier;
use Rebelo\SaftPt\AuditFile\SupplierAddress;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SupplierCountry;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;

/**
 * Class SupplierTest
 *
 * @author João Rebelo
 */
class SupplierTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\Supplier::class
            );
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $supplier = new Supplier(new ErrorRegister());
        $this->assertInstanceOf(Supplier::class, $supplier);

        $this->assertFalse($supplier->issetAccountID());
        $this->assertFalse($supplier->issetBillingAddress());
        $this->assertFalse($supplier->issetCompanyName());
        $this->assertFalse($supplier->issetSupplierID());
        $this->assertFalse($supplier->issetSupplierTaxID());

        $this->assertNull($supplier->getContact());
        $this->assertNull($supplier->getEmail());
        $this->assertNull($supplier->getFax());
        $this->assertNull($supplier->getTelephone());
        $this->assertNull($supplier->getWebsite());
        $this->assertEquals(array(), $supplier->getShipFromAddress());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetSupplierID(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        try {
            $supplier->getSupplierID();
            $this->fail("Get supplier id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $supplierId = "Contumer 1209";
        $this->assertTrue($supplier->setSupplierID($supplierId));
        $this->assertEquals($supplierId, $supplier->getSupplierID());
        $this->assertTrue($supplier->issetSupplierID());

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setSupplierID(""));
        $this->assertSame("", $supplier->getSupplierID());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        try {
            $supplier->setSupplierID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetAccountID(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        try {
            $supplier->getAccountID();
            $this->fail("Get Account id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $accountId = "AccountID999";
        $this->assertTrue($supplier->setAccountID($accountId));
        $this->assertEquals($accountId, $supplier->getAccountID());
        $this->assertTrue($supplier->issetAccountID());
        $this->assertTrue($supplier->setAccountID(Supplier::DESCONHECIDO));
        $this->assertEquals(Supplier::DESCONHECIDO, $supplier->getAccountID());

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setAccountID(""));
        $this->assertSame("", $supplier->getAccountID());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $wrong = str_pad("A", 32, "A");
        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setAccountID($wrong));
        $this->assertSame($wrong, $supplier->getAccountID());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        try {
            $supplier->setAccountID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSestGetSupplierTaxID(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        try {
            $supplier->getSupplierTaxID();
            $this->fail("Get Costomer tax id without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $supplierTaxId = "SupplierTaxID999";
        $this->assertTrue($supplier->setSupplierTaxID($supplierTaxId));
        $this->assertTrue($supplier->issetSupplierTaxID());
        $this->assertEquals($supplierTaxId, $supplier->getSupplierTaxID());

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setSupplierTaxID(""));
        $this->assertSame("", $supplier->getSupplierTaxID());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $wrong = str_pad("A", 32, "A");
        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setSupplierTaxID($wrong));
        $this->assertSame($wrong, $supplier->getSupplierTaxID());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        try {
            $supplier->setSupplierTaxID(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCompanyName(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        try {
            $supplier->getCompanyName();
            $this->fail("Get CompanyName without initialize should throw error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }

        $name = "CompanyName FACTURACAO";
        $this->assertTrue($supplier->setCompanyName($name));
        $this->assertEquals($name, $supplier->getCompanyName());
        $this->assertTrue($supplier->issetCompanyName());
        $this->assertTrue($supplier->setCompanyName(\str_pad("_", 109, "_")));
        $this->assertEquals(100, \strlen($supplier->getCompanyName()));

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setCompanyName(""));
        $this->assertSame("", $supplier->getCompanyName());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        try {
            $supplier->setCompanyName(null);/** @phpstan-ignore-line */
        } catch (\Exception | \TypeError $e) {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testContact(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertNull($supplier->getContact());

        $name = "Contact name test";
        $this->assertTrue($supplier->setContact($name));
        $this->assertEquals($name, $supplier->getContact());
        $this->assertTrue($supplier->setContact(\str_pad("_", 51, "_")));
        $this->assertEquals(50, \strlen($supplier->getContact()));

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setContact(""));
        $this->assertSame("", $supplier->getContact());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->setContact(null);
        $this->assertNull($supplier->getContact());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testBillingSupplierAddress(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertInstanceOf(
            SupplierAddress::class, $supplier->getBillingAddress()
        );

        $this->assertTrue($supplier->issetBillingAddress());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShipFromAddress(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertEquals(array(), $supplier->getShipFromAddress());

        $this->assertInstanceOf(
            SupplierAddress::class, $supplier->addShipFromAddress()
        );
        $this->assertInstanceOf(
            SupplierAddress::class, $supplier->addShipFromAddress()
        );
        $this->assertInstanceOf(
            SupplierAddress::class, $supplier->addShipFromAddress()
        );

        $this->assertEquals(3, \count($supplier->getShipFromAddress()));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTelephone(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertNull($supplier->getTelephone());

        $telephone = "Telephone test";
        $this->assertTrue($supplier->setTelephone($telephone));
        $this->assertEquals($telephone, $supplier->getTelephone());

        $this->assertTrue($supplier->setTelephone(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($supplier->getTelephone()));

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setTelephone(""));
        $this->assertSame("", $supplier->getTelephone());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->setTelephone(null);
        $this->assertNull($supplier->getTelephone());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testFax(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertNull($supplier->getFax());

        $fax = "Fax test";
        $this->assertTrue($supplier->setFax($fax));
        $this->assertEquals($fax, $supplier->getFax());

        $this->assertTrue($supplier->setFax(\str_pad("_", 300, "_")));
        $this->assertEquals(20, \strlen($supplier->getFax()));

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setFax(""));
        $this->assertSame("", $supplier->getFax());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->setFax(null);
        $this->assertNull($supplier->getFax());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testEmail(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertNull($supplier->getEmail());

        $email = "email@email.pt";
        $this->assertTrue($supplier->setEmail($email));
        $this->assertEquals($email, $supplier->getEmail());

        $wrong = \str_pad($email, 255, "a", STR_PAD_LEFT);
        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setEmail($wrong));
        $this->assertSame($wrong, $supplier->getEmail());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $wrong2 = "isNotEmail";
        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setEmail($wrong2));
        $this->assertSame($wrong2, $supplier->getEmail());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setEmail(""));
        $this->assertSame("", $supplier->getEmail());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->setEmail(null);
        $this->assertNull($supplier->getEmail());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWebsite(): void
    {
        $supplier = new Supplier(new ErrorRegister());

        $this->assertNull($supplier->getWebsite());

        $website = "http://saft.pt";
        $supplier->setWebsite($website);
        $this->assertEquals($website, $supplier->getWebsite());

        $wrong = \str_pad($website, 61, "a", STR_PAD_RIGHT);
        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setWebsite($wrong));
        $this->assertSame($wrong, $supplier->getWebsite());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($supplier->setWebsite(""));
        $this->assertSame("", $supplier->getWebsite());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $supplier->setWebsite(null);
        $this->assertNull($supplier->getWebsite());
    }

    /**
     * Create and populate a instance of Supplier to be used in tests
     * @return Supplier
     */
    public function createSupplier(): Supplier
    {
        $supplier = new Supplier(new ErrorRegister());
        $address  = $supplier->getBillingAddress();
        $address->setAddressDetail("Billing Street test 999");
        $address->setCity("Sintra");
        $address->setPostalCode("1999-999");
        $address->setRegion("Lisbon");
        $address->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));

        $shToAdd = $supplier->addShipFromAddress();
        $shToAdd->setAddressDetail("Ship Street test 999");
        $shToAdd->setCity("Sintra");
        $shToAdd->setPostalCode("1999-999");
        $shToAdd->setRegion("Lisbon");
        $shToAdd->setCountry(new SupplierCountry(SupplierCountry::ISO_BR));

        $supplier->setSupplierID("ID999999990");
        $supplier->setAccountID("Account id test");
        $supplier->setSupplierTaxID("599999990");
        $supplier->setCompanyName("Supplier name test");
        $supplier->setContact("Supplier contact neme");
        $supplier->setTelephone("+351 987654321");
        $supplier->setFax("123456789");
        $supplier->setEmail("email@emil.pt");
        $supplier->setWebsite("http://saft.pt");
        $supplier->setSelfBillingIndicator(false);
        return $supplier;
    }

    /**
     * Set the properties that can have nulll to null
     * @param Supplier $supplier
     */
    public function setNullsSupplier(Supplier $supplier): void
    {
        $supplier->setContact(null);
        $supplier->setTelephone(null);
        $supplier->setFax(null);
        $supplier->setEmail(null);
        $supplier->setWebsite(null);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $supplier = $this->createSupplier();

        $this->assertInstanceOf(
            \SimpleXMLElement::class, $supplier->createXmlNode($node)
        );

        $supplierNode = $node->{Supplier::N_SUPPLIER};

        $this->assertEquals(
            $supplier->getSupplierID(),
            (string) $supplierNode->{Supplier::N_SUPPLIERID}
        );

        $this->assertEquals(
            $supplier->getAccountID(),
            (string) $supplierNode->{Supplier::N_ACCOUNTID}
        );
        $this->assertEquals(
            $supplier->getSupplierTaxID(),
            (int) $supplierNode->{Supplier::N_SUPPLIERTAXID}
        );

        $this->assertEquals(
            $supplier->getCompanyName(),
            (string) $supplierNode->{Supplier::N_COMPANYNAME}
        );

        $this->assertEquals(
            $supplier->getContact(),
            (string) $supplierNode->{Supplier::N_CONTACT}
        );

        $this->assertEquals(
            $supplier->getBillingAddress()->getAddressDetail(),
            (string) $supplierNode
            ->{Supplier::N_BILLINGADDRESS}->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $shToAddr = $supplier->getShipFromAddress();

        $this->assertEquals(
            $shToAddr[0]->getAddressDetail(),
            (string) $supplierNode
            ->{Supplier::N_SHIPTOADDRESS}->{SupplierAddress::N_ADDRESSDETAIL}
        );

        $this->assertEquals(
            $supplier->getTelephone(),
            (string) $supplierNode->{Supplier::N_TELEPHONE}
        );

        $this->assertEquals(
            $supplier->getFax(),
            (string) $supplierNode->{Supplier::N_FAX}
        );

        $this->assertEquals(
            $supplier->getEmail(),
            (string) $supplierNode->{Supplier::N_EMAIL}
        );
        $this->assertEquals(
            $supplier->getWebsite(),
            (string) $supplierNode->{Supplier::N_WEBSITE}
        );

        $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnSetValue());

        $this->setNullsSupplier($supplier);

        unset($node);
        $nodeNull         = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $supplier->createXmlNode($nodeNull);
        $supplierNodeNull = $nodeNull->{Supplier::N_SUPPLIER};
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_CONTACT}->count());

        $this->assertEquals(
            1, $supplierNodeNull->{Supplier::N_SHIPTOADDRESS}->count()
        );

        $this->assertEquals(
            0, $supplierNodeNull->{Supplier::N_TELEPHONE}->count()
        );

        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_FAX}->count());
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_EMAIL}->count());
        $this->assertEquals(0, $supplierNodeNull->{Supplier::N_WEBSITE}->count());

        $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $node = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );

        $supplier = $this->createSupplier();

        $xml = $supplier->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed      = new Supplier(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));
        $this->assertEquals($supplier->getSupplierID(), $parsed->getSupplierID());
        $this->assertEquals($supplier->getAccountID(), $parsed->getAccountID());
        $this->assertEquals(
            $supplier->getCompanyName(), $parsed->getCompanyName()
        );
        $this->assertEquals($supplier->getContact(), $parsed->getContact());
        $this->assertEquals(
            $supplier->getBillingAddress()->getAddressDetail(),
            $parsed->getBillingAddress()->getAddressDetail()
        );
        $cusShToAddr = $supplier->getShipFromAddress();
        $parShTAddr  = $parsed->getShipFromAddress();
        $this->assertEquals(
            $cusShToAddr[0]->getAddressDetail(),
            $parShTAddr[0]->getAddressDetail()
        );
        $this->assertEquals($supplier->getTelephone(), $parsed->getTelephone());
        $this->assertEquals($supplier->getFax(), $parsed->getFax());
        $this->assertEquals($supplier->getEmail(), $parsed->getEmail());
        $this->assertEquals($supplier->getWebsite(), $parsed->getWebsite());

        $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnSetValue());

        unset($parsed);

        $spXml = $supplier->createXmlNode($node)->asXML();
        if ($spXml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->setNullsSupplier($supplier);
        $parsedNull = new Supplier(new ErrorRegister());
        $parsedNull->parseXmlNode(new \SimpleXMLElement($spXml));
    }

    public function testCreateXmlNodeWrongName(): void
    {
        $supplier = new Supplier(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $supplier->createXmlNode($node);
            $this->fail(
                "Creat a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNodeWrongName(): void
    {
        $supplier = new Supplier(new ErrorRegister());
        $node     = new \SimpleXMLElement("<root></root>");
        try {
            $supplier->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     * Reads all Suppliers from the Demo SAFT in Test\Ressources
     * and parse then to Supplier class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if($saftDemoXml === false){
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $supplierStack = $saftDemoXml
            ->{MasterFiles::N_MASTERFILES}
            ->{Supplier::N_SUPPLIER};

        if ($supplierStack->count() === 0) {
            $this->fail("No Supplier in XML");
        }

        for ($i = 0; $i < $supplierStack->count(); $i++) {
            /* @var $supplierXml \SimpleXMLElement */
            $supplierXml = $supplierStack[$i];

            $supplier = new Supplier(new ErrorRegister());
            $supplier->parseXmlNode($supplierXml);


            $xmlRootNode     = (new \Rebelo\SaftPt\AuditFile\AuditFile())->createRootElement();
            $masterFilesNode = $xmlRootNode->addChild(MasterFiles::N_MASTERFILES);

            $xml = $supplier->createXmlNode($masterFilesNode);

            try {
                $assertXml = $this->xmlIsEqual($supplierXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Producy '%s' with error '%s'",
                        $supplierXml->{Supplier::N_SUPPLIERID}, $assertXml
                    )
                );
            } catch (\Exception | \Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $supplierXml->{Supplier::N_SUPPLIERID}, $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($supplier->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $supplier     = new Supplier(new ErrorRegister());
        $xml          = $supplier->createXmlNode($supplierNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($supplier->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<".MasterFiles::N_MASTERFILES."></".MasterFiles::N_MASTERFILES.">"
        );
        $supplier     = new Supplier(new ErrorRegister());
        $supplier->setAccountID("");
        $supplier->setCompanyName("");
        $supplier->setContact("");
        $supplier->setSupplierID("");
        $supplier->setSupplierTaxID("");
        $supplier->setEmail("----");
        $supplier->setTelephone("");
        $supplier->setFax("");
        $supplier->setWebsite("");

        $xml = $supplier->createXmlNode($supplierNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($supplier->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($supplier->getErrorRegistor()->getLibXmlError());
    }
}
