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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\MasterFiles\Product;
use Rebelo\SaftPt\AuditFile\MasterFiles\ProductType;
use Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails;
use Rebelo\SaftPt\AuditFile\MasterFiles\MasterFiles;

/**
 * Class ProductTest
 *
 * @author João Rebelo
 */
class ProductTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\Product::class
        );
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);
        $this->assertNull($product->getProductGroup());
        $this->assertNull($product->getCustomsDetails());

        try
        {
            $product->getProductCode();
            $this->fail("Get ProductCode without initialize should throw Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try
        {
            $product->getProductDescription();
            $this->fail("Get ProductDescription without initialize should throw Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try
        {
            $product->getProductNumberCode();
            $this->fail("Get ProductNumberCode without initialize should throw Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        try
        {
            $product->getProductType();
            $this->fail("Get ProductType without initialize should throw Error");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     *
     */
    public function testSetGet()
    {
        $product = new Product();

        $type = ProductType::P;
        $product->setProductType(new ProductType($type));
        $this->assertEquals($type, $product->getProductType()->get());

        $code = "COD0001";
        $product->setProductCode($code);
        $this->assertEquals($code, $product->getProductCode());
        $product->setProductCode(str_pad("A", 61, "9"));
        $this->assertEquals(60, \strlen($product->getProductCode()));
        try
        {
            $product->setProductCode("");
            $this->fail("Set ProductCode to a empty string should throws '" . AuditFileException::class . "'");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex,
                                    "Set ProductCode to a empty string should throws '" . AuditFileException::class . "'");
        }



        $group = "Group prod";
        $product->setProductGroup($group);
        $this->assertEquals($group, $product->getProductGroup());
        $product->setProductGroup(null);
        $this->assertNull($product->getProductGroup());
        $product->setProductGroup(str_pad("A", 61, "9"));
        $this->assertEquals(50, \strlen($product->getProductGroup()));
        try
        {
            $product->setProductGroup("");
            $this->fail("Set ProductGroup to a empty string should throws '" . AuditFileException::class . "'");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex,
                                    "Set ProductGroup to a empty string should throws '" . AuditFileException::class . "'");
        }

        $desc = "Description of product";
        $product->setProductDescription($desc);
        $this->assertEquals($desc, $product->getProductDescription());
        $product->setProductDescription(str_pad("A", 201, "9"));
        $this->assertEquals(200, \strlen($product->getProductDescription()));
        try
        {
            $product->setProductDescription("A");
            $this->fail("Set ProductDescription to a string length less then 2 should throws '" . AuditFileException::class . "'");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex,
                                    "Set ProductDescription to a string  length less than 2 should throws '" . AuditFileException::class . "'");
        }

        $numCode = "CD999";
        $product->setProductNumberCode($numCode);
        $this->assertEquals($numCode, $product->getProductNumberCode());
        $product->setProductNumberCode(str_pad("A", 61, "9"));
        $this->assertEquals(60, \strlen($product->getProductNumberCode()));
        try
        {
            $product->setProductNumberCode("");
            $this->fail("Set ProductNumberCode to a empty string should throws '" . AuditFileException::class . "'");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex,
                                    "Set ProductNumberCode to a empty string should throws '" . AuditFileException::class . "'");
        }

        $CNCode     = "12345678";
        $custDetail = new CustomsDetails();
        $index      = $custDetail->addToCNCode($CNCode);
        $product->setCustomsDetails($custDetail);
        $this->assertEquals($CNCode,
                            $product->getCustomsDetails()->getCNCode()[$index]);
    }

    /**
     *
     * @return Product
     */
    public function createProduct(): Product
    {
        $prod           = new Product();
        $prod->setProductCode("COD999");
        $prod->setProductDescription("Description of the product");
        $prod->setProductGroup("The group");
        $prod->setProductNumberCode("A9999999");
        $prod->setProductType(new ProductType(ProductType::P));
        $customsDetails = new CustomsDetails();
        $customsDetails->addToCNCode("12345678");
        $customsDetails->addToUNNumber("4321");
        $prod->setCustomsDetails($customsDetails);
        return $prod;
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $prod           = $this->createProduct();
        $node           = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTERFILES . "></" . MasterFiles::N_MASTERFILES . ">"
        );
        $prodNode       = $prod->createXmlNode($node);
        $custDetailNode = $prodNode->{Product::N_CUSTOMSDETAILS};
        for ($n = 0; $n < $custDetailNode->{CustomsDetails::N_CNCODE}->count(); $n++)
        {
            $this->assertEquals($prod->getCustomsDetails()->getCNCode()[$n],
                                (string) $custDetailNode->{CustomsDetails::N_CNCODE}[$n]);
        }
        for ($n = 0; $n < $custDetailNode->{CustomsDetails::N_UNNUMBER}->count(); $n++)
        {
            $this->assertEquals($prod->getCustomsDetails()->getUNNumber()[$n],
                                (string) $custDetailNode->{CustomsDetails::N_UNNUMBER}[$n]);
        }
        $this->assertEquals($prod->getProductCode(),
                            (string) $prodNode->{Product::N_PRODUCTCODE});
        $this->assertEquals($prod->getProductDescription(),
                            (string) $prodNode->{Product::N_PRODUCTDESCRIPTION});
        $this->assertEquals($prod->getProductGroup(),
                            (string) $prodNode->{Product::N_PRODUCTGROUP});
        $this->assertEquals($prod->getProductNumberCode(),
                            (string) $prodNode->{Product::N_PRODUCTNUMBERCODE});
        $this->assertEquals($prod->getProductType()->get(),
                            (string) $prodNode->{Product::N_PRODUCTTYPE});

        $prod->setProductGroup(null);
        $prod->setCustomsDetails(null);
        $xmlNull = $prod->createXmlNode($node);
        $this->assertEquals(0, $xmlNull->{Product::N_PRODUCTGROUP}->count());
        $this->assertEquals(0, $xmlNull->{Product::N_CUSTOMSDETAILS}->count());
    }

    /**
     *
     */
    public function testParseXmlNode()
    {
        $node    = new \SimpleXMLElement(
            "<" . MasterFiles::N_MASTERFILES . "></" . MasterFiles::N_MASTERFILES . ">"
        );
        $product = $this->createProduct();
        $xml     = $product->createXmlNode($node)->asXML();

        $parsed = new Product();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertEquals($product->getProductCode(),
                            $parsed->getProductCode());
        $this->assertEquals($product->getProductDescription(),
                            $parsed->getProductDescription());
        $this->assertEquals($product->getProductGroup(),
                            $parsed->getProductGroup());
        $this->assertEquals($product->getProductNumberCode(),
                            $parsed->getProductNumberCode());
        $this->assertEquals($product->getProductType()->get(),
                            $parsed->getProductType()->get());
        $this->assertEquals($product->getCustomsDetails()->getCNCode()[0],
                            $parsed->getCustomsDetails()->getCNCode()[0]);
        $this->assertEquals($product->getCustomsDetails()->getUNNumber()[0],
                            $parsed->getCustomsDetails()->getUNNumber()[0]);

        $product->setProductGroup(null);
        $product->setCustomsDetails(null);

        $parsedNull = new Product();
        $xmlNull    = $product->createXmlNode($node)->asXML();
        $parsedNull->parseXmlNode(new \SimpleXMLElement($xmlNull));
        $this->assertNull($parsedNull->getProductGroup());
        $this->assertNull($parsedNull->getCustomsDetails());
    }

    public function testCreateXmlNodeWrongName()
    {
        $product = new Product();
        $node    = new \SimpleXMLElement("<root></root>"
        );
        try
        {
            $product->createXmlNode($node);
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
        $product = new Product();
        $node    = new \SimpleXMLElement("<root></root>"
        );
        try
        {
            $product->parseXmlNode($node);
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

}
