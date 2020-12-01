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
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class CustomsDetailsTest
 *
 * @author João Rebelo
 */
class CustomsDetailsTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(
                \Rebelo\SaftPt\AuditFile\MasterFiles\CustomsDetails::class
            );
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $custDetail = new CustomsDetails(new ErrorRegister());
        $this->assertInstanceOf(CustomsDetails::class, $custDetail);
        $this->assertEquals(array(), $custDetail->getCNCode());
        $this->assertEquals(array(), $custDetail->getUNNumber());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCnCode(): void
    {
        $custDetail = new CustomsDetails(new ErrorRegister());
        $this->assertEquals(array(), $custDetail->getCNCode());

        $cnNum = "12345678";
        $this->assertTrue($custDetail->addCNCode($cnNum));
        $this->assertEquals($cnNum, $custDetail->getCNCode()[0]);

        $cnNum2 = "98765432";
        $this->assertTrue($custDetail->addCNCode($cnNum2));
        $this->assertEquals($cnNum2, $custDetail->getCNCode()[1]);

        $wrong = "999";
        $custDetail->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($custDetail->addCNCode($wrong));
        $this->assertSame($wrong, $custDetail->getCNCode()[2]);
        $this->assertNotEmpty($custDetail->getErrorRegistor()->getOnSetValue());

        //Add CNCode that does not respect regexp
        $wrong2 = "9999999999";
        $custDetail->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($custDetail->addCNCode($wrong2));
        $this->assertSame($wrong2, $custDetail->getCNCode()[3]);
        $this->assertNotEmpty($custDetail->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testUNNumber(): void
    {
        $custDetail = new CustomsDetails(new ErrorRegister());
        $this->assertEquals(array(), $custDetail->getUNNumber());

        $cnNum = "1234";
        $this->assertTrue($custDetail->addUNNumber($cnNum));
        $this->assertEquals($cnNum, $custDetail->getUNNumber()[0]);

        $cnNum2 = "9876";
        $this->assertTrue($custDetail->addUNNumber($cnNum2));
        $this->assertEquals($cnNum2, $custDetail->getUNNumber()[1]);

        $wrong = "999";
        $custDetail->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($custDetail->addUNNumber($wrong));
        $this->assertSame($wrong, $custDetail->getUNNumber()[2]);
        $this->assertNotEmpty($custDetail->getErrorRegistor()->getOnSetValue());

        $wrong2 = "9999999999";
        $custDetail->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($custDetail->addUNNumber($wrong2));
        $this->assertSame($wrong2, $custDetail->getUNNumber()[3]);
        $this->assertNotEmpty($custDetail->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    public function createCustomsDetail(): CustomsDetails
    {
        $custDetail = new CustomsDetails(new ErrorRegister());
        $custDetail->addCNCode("12345678");
        $custDetail->addCNCode("87654321");
        $custDetail->addUNNumber("2345");
        $custDetail->addUNNumber("5432");
        return $custDetail;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();

        $customsNode = $customsDetail->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $customsNode);
        $this->assertEquals(
            CustomsDetails::N_CUSTOMSDETAILS,
            $customsNode->getName()
        );

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

        $this->assertEmpty($customsDetail->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseXmlNode(): void
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();

        $xml = $customsDetail->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new CustomsDetails(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $countCnCode   = \count($parsed->getCNCode());
        $countUNNumber = \count($parsed->getUNNumber());

        $this->assertEquals(2, $countCnCode);
        $this->assertEquals(2, $countUNNumber);

        for ($n = 0; $n < $countCnCode; $n++) {
            $this->assertEquals(
                $customsDetail->getCNCode()[$n],
                $parsed->getCNCode()[$n]
            );
        }

        for ($n = 0; $n < $countUNNumber; $n++) {
            $this->assertEquals(
                $customsDetail->getUNNumber()[$n],
                $parsed->getUNNumber()[$n]
            );
        }

        $this->assertEmpty($customsDetail->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode = new \SimpleXMLElement(
            "<root></root>"
        );
        $details      = new CustomsDetails(new ErrorRegister());
        $xml          = $details->createXmlNode($customerNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($details->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($details->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($details->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $custNode = new \SimpleXMLElement(
            "<root></root>"
        );

        $details = new CustomsDetails(new ErrorRegister());
        $details->addCNCode("--------A");
        $details->addCNCode("");
        $details->addUNNumber("-------b");
        $details->addUNNumber("");

        $xml = $details->createXmlNode($custNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($details->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($details->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($details->getErrorRegistor()->getLibXmlError());
    }
}