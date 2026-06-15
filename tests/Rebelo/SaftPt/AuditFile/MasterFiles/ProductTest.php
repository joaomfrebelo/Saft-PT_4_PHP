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

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\Commune;
use Rebelo\SaftPt\TXmlTest;

/**
 * Class ProductTest
 *
 * @author João Rebelo
 */
class ProductTest extends TestCase
{

    use TXmlTest;

    /**
     * @throws \ReflectionException
     * @author       João Rebelo
     * @noinspection PhpExpressionResultUnusedInspection
     */
    #[Test]
    public function testReflection(): void
    {
        $this->doesNotPerformAssertions();
        (new Commune(Product::class))->testReflection(Product::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $product = new Product(new ErrorRegister());
        $this->assertInstanceOf(Product::class, $product);

        $this->assertFalse($product->issetProductCode());
        $this->assertFalse($product->issetProductDescription());
        $this->assertFalse($product->issetProductNumberCode());
        $this->assertFalse($product->issetProductType());
        $this->assertFalse($product->issetCustomsDetails());

        $this->assertNull($product->getProductGroup());
        $this->assertInstanceOf(
            CustomsDetails::class, $product->getCustomsDetails()
        );

        try {
            $product->getProductCode();
            $this->fail("Get ProductCode without initialize should throw Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try {
            $product->getProductDescription();
            $this->fail("Get ProductDescription without initialize should throw Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try {
            $product->getProductNumberCode();
            $this->fail("Get ProductNumberCode without initialize should throw Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try {
            $product->getProductType();
            $this->fail("Get ProductType without initialize should throw Error");
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testProductType(): void
    {
        $product = new Product(new ErrorRegister());

        $type = ProductType::P;
        $product->setProductType($type);
        $this->assertEquals($type, $product->getProductType());
        $this->assertTrue($product->issetProductType());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testProductCode(): void
    {
        $product = new Product(new ErrorRegister());
        $code    = "COD0001";
        $this->assertTrue($product->setProductCode($code));
        $this->assertEquals($code, $product->getProductCode());
        $this->assertTrue($product->setProductCode(str_pad("A", 61, "9")));
        $this->assertEquals(60, \strlen($product->getProductCode()));
        $this->assertTrue($product->issetProductCode());

        $product->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($product->setProductCode(""));
        $this->assertSame("", $product->getProductCode());
        $this->assertNotEmpty($product->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testProductGroup(): void
    {
        $product = new Product(new ErrorRegister());
        $group   = "Group prod";
        $this->assertTrue($product->setProductGroup($group));
        $this->assertEquals($group, $product->getProductGroup());
        $this->assertTrue($product->setProductGroup(null));
        $this->assertNull($product->getProductGroup());
        $this->assertTrue($product->setProductGroup(str_pad("A", 61, "9")));
        /** @phpstan-ignore-next-line */
        $this->assertEquals(50, \strlen($product->getProductGroup() ?? ""));

        $product->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($product->setProductGroup(""));
        $this->assertSame("", $product->getProductGroup());
        $this->assertNotEmpty($product->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testProductDescription(): void
    {
        $product = new Product(new ErrorRegister());
        $desc    = "Description of product";
        $this->assertTrue($product->setProductDescription($desc));
        $this->assertEquals($desc, $product->getProductDescription());
        $this->assertTrue($product->issetProductDescription());
        $this->assertTrue($product->setProductDescription(str_pad("A", 201, "9")));
        $this->assertEquals(200, \strlen($product->getProductDescription()));

        $product->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($product->setProductDescription("A"));
        $this->assertSame("A", $product->getProductDescription());
        $this->assertNotEmpty($product->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testProductNumberCode(): void
    {
        $product = new Product(new ErrorRegister());
        $numCode = "CD999";
        $this->assertTrue($product->setProductNumberCode($numCode));
        $this->assertEquals($numCode, $product->getProductNumberCode());
        $this->assertTrue($product->issetProductNumberCode());
        $this->assertTrue($product->setProductNumberCode(str_pad("A", 61, "9")));
        $this->assertEquals(60, \strlen($product->getProductNumberCode()));

        $product->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($product->setProductNumberCode(""));
        $this->assertSame("", $product->getProductNumberCode());
        $this->assertNotEmpty($product->getErrorRegistor()->getOnSetValue());

        $CNCode        = "12345678";
        $customsDetail = $product->getCustomsDetails();
        $this->assertTrue($product->issetCustomsDetails());
        $customsDetail->addCNCode($CNCode);
        $this->assertEquals(
            $CNCode, $product->getCustomsDetails()->getCNCode()[0]
        );
    }

    /**
     *
     * @return Product
     */
    public function createProduct(): Product
    {
        $prod = new Product(new ErrorRegister());
        $prod->setProductCode("COD999");
        $prod->setProductDescription("Description of the product");
        $prod->setProductGroup("The group");
        $prod->setProductNumberCode("A9999999");
        $prod->setProductType(ProductType::P);
        $customsDetails = $prod->getCustomsDetails();
        $customsDetails->addCNCode("12345678");
        $customsDetails->addUNNumber("4321");
        return $prod;
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $prod = $this->createProduct();

        $node = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTER_FILES . "></" . MasterFiles::N_MASTER_FILES . ">"
        );

        $prodNode = $prod->createXmlNode($node);

        $customsDetailNode = $prodNode->{Product::N_CUSTOMS_DETAILS};

        for ($n = 0; $n < $customsDetailNode->{CustomsDetails::N_CN_CODE}->count(); $n++) {
            $this->assertEquals(
                $prod->getCustomsDetails()->getCNCode()[$n],
                (string)$customsDetailNode->{CustomsDetails::N_CN_CODE}[$n]
            );
        }

        for ($n = 0; $n < $customsDetailNode->{CustomsDetails::N_UN_NUMBER}->count(); $n++) {
            $this->assertEquals(
                $prod->getCustomsDetails()->getUNNumber()[$n],
                (string)$customsDetailNode->{CustomsDetails::N_UN_NUMBER}[$n]
            );
        }

        $this->assertEquals(
            $prod->getProductCode(),
            (string)$prodNode->{Product::N_PRODUCT_CODE}
        );

        $this->assertEquals(
            $prod->getProductDescription(),
            (string)$prodNode->{Product::N_PRODUCT_DESCRIPTION}
        );

        $this->assertEquals(
            $prod->getProductGroup(),
            (string)$prodNode->{Product::N_PRODUCT_GROUP}
        );

        $this->assertEquals(
            $prod->getProductNumberCode(),
            (string)$prodNode->{Product::N_PRODUCT_NUMBER_CODE}
        );

        $this->assertEquals(
            $prod->getProductType()->value,
            (string)$prodNode->{Product::N_PRODUCT_TYPE}
        );

        $prod->setProductGroup(null);
        $xmlNull = $prod->createXmlNode($node);
        $this->assertEquals(0, $xmlNull->{Product::N_PRODUCT_GROUP}->count());
        $this->assertEquals(1, $xmlNull->{Product::N_CUSTOMS_DETAILS}->count());

        $this->assertEmpty($prod->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($prod->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($prod->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNode(): void
    {
        $node    = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTER_FILES . "></" . MasterFiles::N_MASTER_FILES . ">"
        );
        $product = $this->createProduct();
        $xml     = $product->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new Product(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals(
            $product->getProductCode(), $parsed->getProductCode()
        );

        $this->assertEquals(
            $product->getProductDescription(), $parsed->getProductDescription()
        );

        $this->assertEquals(
            $product->getProductGroup(), $parsed->getProductGroup()
        );

        $this->assertEquals(
            $product->getProductNumberCode(), $parsed->getProductNumberCode()
        );

        $this->assertEquals(
            $product->getProductType(), $parsed->getProductType()
        );

        $this->assertEquals(
            $product->getCustomsDetails()->getCNCode()[0],
            $parsed->getCustomsDetails()->getCNCode()[0]
        );

        $this->assertEquals(
            $product->getCustomsDetails()->getUNNumber()[0],
            $parsed->getCustomsDetails()->getUNNumber()[0]
        );

        $this->assertEmpty($product->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($product->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($product->getErrorRegistor()->getOnSetValue());

        $product->setProductGroup(null);

        $parsedNull = new Product(new ErrorRegister());
        $xmlNull    = $product->createXmlNode($node)->asXML();
        if ($xmlNull === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));
        $this->assertNull($parsedNull->getProductGroup());

        $this->assertEmpty($product->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($product->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($product->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $product = new Product(new ErrorRegister());
        $node    = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $product->createXmlNode($node);
            $this->fail(
                "Creat a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNodeWrongName(): void
    {
        $product = new Product(new ErrorRegister());
        $node    = new \SimpleXMLElement(
            "<root></root>"
        );
        try {
            $product->parseXmlNode($node);
            $this->fail(
                "Parse a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                AuditFileException::class, $e
            );
        }
    }

    /**
     * Reads all Products from the Demo SAFT in Test\Resources
     * and parse then to Product class, after that generate a xml from the
     * Line class and test if the xml strings are equal
     *
     * @throws AuditFileException
     * @author João Rebelo
     */
    #[Test]
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Error opening file '%s'", SAFT_DEMO_PATH));
        }

        $productStack = $saftDemoXml
            ->{MasterFiles::N_MASTER_FILES}
            ->{Product::N_PRODUCT};

        if ($productStack->count() === 0) {
            $this->fail("No Products in XML");
        }

        for ($i = 0; $i < $productStack->count(); $i++) {
            /* @var $productXml \SimpleXMLElement */
            $productXml = $productStack[$i];

            $product = new Product(new ErrorRegister());
            try {
                $product->parseXmlNode($productXml);
                /** @phpstan-ignore-next-line */
            } catch (\ValueError|AuditFileException) {
            }


            $xmlRootNode     = (new AuditFile())->createRootElement();
            $masterFilesNode = $xmlRootNode->addChild(MasterFiles::N_MASTER_FILES);

            $xml = $product->createXmlNode($masterFilesNode);

            try {
                $assertXml = $this->xmlIsEqual($productXml, $xml);
                $this->assertTrue(
                    $assertXml,
                    \sprintf(
                        "Fail on Product '%s' with error '%s'",
                        $productXml->{Product::N_PRODUCT_CODE}, $assertXml
                    )
                );
            } catch (\Exception|\Error $e) {
                $this->fail(
                    \sprintf(
                        "Fail on Document '%s' with error '%s'",
                        $productXml->{Product::N_PRODUCT_CODE}, $e->getMessage()
                    )
                );
            }

            $this->assertEmpty($product->getErrorRegistor()->getLibXmlError());
            $this->assertEmpty($product->getErrorRegistor()->getOnCreateXmlNode());
            $this->assertEmpty($product->getErrorRegistor()->getOnSetValue());
        }
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $productNode = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTER_FILES . "></" . MasterFiles::N_MASTER_FILES . ">"
        );
        $product     = new Product(new ErrorRegister());
        $xml         = $product->createXmlNode($productNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($product->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($product->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($product->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $productNode = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTER_FILES . "></" . MasterFiles::N_MASTER_FILES . ">"
        );
        $product     = new Product(new ErrorRegister());
        $product->setProductCode("");
        $product->setProductDescription("");
        $product->setProductGroup("");
        $product->setProductNumberCode("");

        $xml = $product->createXmlNode($productNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($product->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($product->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($product->getErrorRegistor()->getLibXmlError());
    }
}
