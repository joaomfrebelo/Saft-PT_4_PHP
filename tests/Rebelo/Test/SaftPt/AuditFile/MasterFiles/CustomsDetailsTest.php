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
use Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class CustomsDetailsTest
 *
 * @author João Rebelo
 */
class CustomsDetailsTest extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails::class
        );
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $custDetail = new CustomsDetails();
        $this->assertInstanceOf(CustomsDetails::class, $custDetail);
        $this->assertEquals(array(), $custDetail->getCNCode());
        $this->assertEquals(array(), $custDetail->getUNNumber());
    }

    /**
     *
     */
    public function testCnCode()
    {
        $custDetail = new CustomsDetails();
        $this->assertEquals(array(), $custDetail->getCNCode());

        $cnNum = "12345678";
        $index = $custDetail->addToCNCode($cnNum);
        $this->assertEquals($cnNum, $custDetail->getCNCode()[$index]);

        $cnNum2 = "98765432";
        $index2 = $custDetail->addToCNCode($cnNum2);
        $this->assertEquals($cnNum2, $custDetail->getCNCode()[$index2]);

        $this->assertTrue($custDetail->issetCNCode($index));
        $this->assertTrue($custDetail->issetCNCode($index2));
        $this->assertFalse($custDetail->issetCNCode($index2 + 1));
        $custDetail->unsetCNCode($index);
        $this->assertFalse($custDetail->issetCNCode($index));

        try {
            $custDetail->addToCNCode("999");
            $this->fail("Add CNCode that does not respect regexp should throw AuditFileException");
        } catch (AuditFileException $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $custDetail->addToCNCode("9999999999");
            $this->fail("Add CNCode that does not respect regexp should throw AuditFileException");
        } catch (AuditFileException $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     */
    public function testUNNumber()
    {
        $custDetail = new CustomsDetails();
        $this->assertEquals(array(), $custDetail->getUNNumber());

        $cnNum = "1234";
        $index = $custDetail->addToUNNumber($cnNum);
        $this->assertEquals($cnNum, $custDetail->getUNNumber()[$index]);

        $cnNum2 = "9876";
        $index2 = $custDetail->addToUNNumber($cnNum2);
        $this->assertEquals($cnNum2, $custDetail->getUNNumber()[$index2]);

        $this->assertTrue($custDetail->issetUNNumber($index));
        $this->assertTrue($custDetail->issetUNNumber($index2));
        $this->assertFalse($custDetail->issetUNNumber($index2 + 1));
        $custDetail->unsetUNNumber($index);
        $this->assertFalse($custDetail->issetUNNumber($index));

        try {
            $custDetail->addToUNNumber("999");
            $this->fail("Add UNNumber that does not respect regexp should throw AuditFileException");
        } catch (AuditFileException $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $custDetail->addToUNNumber("9999999999");
            $this->fail("Add UNNumber that does not respect regexp should throw AuditFileException");
        } catch (AuditFileException $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    public function createCustomsDetail(): CustomsDetails
    {
        $custDetail = new CustomsDetails();
        $custDetail->addToCNCode("12345678");
        $custDetail->addToCNCode("87654321");
        $custDetail->addToUNNumber("2345");
        $custDetail->addToUNNumber("5432");
        return $custDetail;
    }

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();

        $customsNode = $customsDetail->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $customsNode);
        $this->assertEquals(CustomsDetails::N_CUSTOMSDETAILS,
            $customsNode->getName());

        $cNcodeNode = $customsNode->{CustomsDetails::N_CNCODE};
        for ($n = 0; $n < $cNcodeNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getCNCode()[$n], (string) $cNcodeNode[$n]
            );
        }

        $uNNumberNode = $customsNode->{CustomsDetails::N_UNNUMBER};
        for ($n = 0; $n < $uNNumberNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getUNNumber()[$n], (string) $uNNumberNode[$n]
            );
        }
    }

    /**
     *
     */
    public function testCreateXmlNodeCnCode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();
        $countUnNumber = \count($customsDetail->getUNNumber());
        for ($n = 0; $n < $countUnNumber; $n++) {
            $customsDetail->unsetUNNumber($n);
        }
        $customsNode = $customsDetail->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $customsNode);
        $this->assertEquals(CustomsDetails::N_CUSTOMSDETAILS,
            $customsNode->getName());

        $cNcodeNode = $customsNode->{CustomsDetails::N_CNCODE};
        for ($n = 0; $n < $cNcodeNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getCNCode()[$n], (string) $cNcodeNode[$n]
            );
        }

        $uNNumberNode = $customsNode->{CustomsDetails::N_UNNUMBER};
        $this->assertEquals(0, $uNNumberNode->count());
    }

    /**
     *
     */
    public function testCreateXmlNodeUNNnumber()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();
        $countCnCode   = \count($customsDetail->getCNCode());
        for ($n = 0; $n < $countCnCode; $n++) {
            $customsDetail->unsetCNCode($n);
        }
        $customsNode = $customsDetail->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $customsNode);
        $this->assertEquals(CustomsDetails::N_CUSTOMSDETAILS,
            $customsNode->getName());

        $cNcodeNode = $customsNode->{CustomsDetails::N_CNCODE};
        $this->assertEquals(0, $cNcodeNode->count());

        $uNNumberNode = $customsNode->{CustomsDetails::N_UNNUMBER};
        for ($n = 0; $n < $uNNumberNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getUNNumber()[$n], (string) $uNNumberNode[$n]
            );
        }
    }

    public function testParseXmlNode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();

        $xml = $customsDetail->createXmlNode($node)->asXML();

        $parsed = new CustomsDetails();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $countCnCode   = \count($parsed->getCNCode());
        $countUNNumber = \count($parsed->getUNNumber());

        $this->assertEquals(2, $countCnCode);
        $this->assertEquals(2, $countUNNumber);

        for ($n = 0; $n < $countCnCode; $n++) {
            $this->assertEquals($customsDetail->getCNCode()[$n],
                $parsed->getCNCode()[$n]);
        }

        for ($n = 0; $n < $countUNNumber; $n++) {
            $this->assertEquals($customsDetail->getUNNumber()[$n],
                $parsed->getUNNumber()[$n]);
        }
    }

    /**
     *
     */
    public function testParseXmlNodeCnCode()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();
        $countUnNumber = \count($customsDetail->getUNNumber());
        for ($n = 0; $n < $countUnNumber; $n++) {
            $customsDetail->unsetUNNumber($n);
        }
        $xml = $customsDetail->createXmlNode($node)->asXML();

        $parsed = new CustomsDetails();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $countCnCode   = \count($parsed->getCNCode());
        $countUNNumber = \count($parsed->getUNNumber());

        $this->assertEquals(2, $countCnCode);
        $this->assertEquals(0, $countUNNumber);

        for ($n = 0; $n < $countCnCode; $n++) {
            $this->assertEquals($customsDetail->getCNCode()[$n],
                $parsed->getCNCode()[$n]);
        }
    }

    /**
     *
     */
    public function testParseXmlNodeUNNumber()
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();
        $count         = \count($customsDetail->getCNCode());
        for ($n = 0; $n < $count; $n++) {
            $customsDetail->unsetCNCode($n);
        }
        $xml = $customsDetail->createXmlNode($node)->asXML();

        $parsed = new CustomsDetails();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $countCnCode   = \count($parsed->getCNCode());
        $countUNNumber = \count($parsed->getUNNumber());

        $this->assertEquals(0, $countCnCode);
        $this->assertEquals(2, $countUNNumber);

        for ($n = 0; $n < $countUNNumber; $n++) {
            $this->assertEquals($customsDetail->getUNNumber()[$n],
                $parsed->getUNNumber()[$n]);
        }
    }
}