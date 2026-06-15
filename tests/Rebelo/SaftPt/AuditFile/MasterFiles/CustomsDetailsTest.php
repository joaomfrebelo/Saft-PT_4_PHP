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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\Commune;

/**
 * Class CustomsDetailsTest
 *
 * @author João Rebelo
 */
class CustomsDetailsTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        /** @noinspection PhpExpressionResultUnusedInspection */
        $this->doesNotPerformAssertions();
        (new Commune(CustomsDetails::class))->testReflection(CustomsDetails::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $customs = new CustomsDetails(new ErrorRegister());
        $this->assertInstanceOf(CustomsDetails::class, $customs);
        $this->assertEquals(array(), $customs->getCNCode());
        $this->assertEquals(array(), $customs->getUNNumber());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCnCode(): void
    {
        $customs = new CustomsDetails(new ErrorRegister());
        $this->assertEquals(array(), $customs->getCNCode());

        $cnNum = "12345678";
        $this->assertTrue($customs->addCNCode($cnNum));
        $this->assertEquals($cnNum, $customs->getCNCode()[0]);

        $cnNum2 = "98765432";
        $this->assertTrue($customs->addCNCode($cnNum2));
        $this->assertEquals($cnNum2, $customs->getCNCode()[1]);

        $wrong = "999";
        $customs->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($customs->addCNCode($wrong));
        $this->assertSame($wrong, $customs->getCNCode()[2]);
        $this->assertNotEmpty($customs->getErrorRegistor()->getOnSetValue());

        //Add CNCode that does not respect regexp
        $wrong2 = "9999999999";
        $customs->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($customs->addCNCode($wrong2));
        $this->assertSame($wrong2, $customs->getCNCode()[3]);
        $this->assertNotEmpty($customs->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testUNNumber(): void
    {
        $customs = new CustomsDetails(new ErrorRegister());
        $this->assertEquals(array(), $customs->getUNNumber());

        $cnNum = "1234";
        $this->assertTrue($customs->addUNNumber($cnNum));
        $this->assertEquals($cnNum, $customs->getUNNumber()[0]);

        $cnNum2 = "9876";
        $this->assertTrue($customs->addUNNumber($cnNum2));
        $this->assertEquals($cnNum2, $customs->getUNNumber()[1]);

        $wrong = "999";
        $customs->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($customs->addUNNumber($wrong));
        $this->assertSame($wrong, $customs->getUNNumber()[2]);
        $this->assertNotEmpty($customs->getErrorRegistor()->getOnSetValue());

        $wrong2 = "9999999999";
        $customs->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($customs->addUNNumber($wrong2));
        $this->assertSame($wrong2, $customs->getUNNumber()[3]);
        $this->assertNotEmpty($customs->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     */
    public function createCustomsDetail(): CustomsDetails
    {
        $customs = new CustomsDetails(new ErrorRegister());
        $customs->addCNCode("12345678");
        $customs->addCNCode("87654321");
        $customs->addUNNumber("2345");
        $customs->addUNNumber("5432");
        return $customs;
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $node = new \SimpleXMLElement("<root></root>");

        $customsDetail = $this->createCustomsDetail();

        $customsNode = $customsDetail->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $customsNode);
        $this->assertEquals(
            CustomsDetails::N_CUSTOMS_DETAILS,
            $customsNode->getName()
        );

        $cNCodeNode = $customsNode->{CustomsDetails::N_CN_CODE};
        for ($n = 0; $n < $cNCodeNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getCNCode()[$n], (string)$cNCodeNode[$n]
            );
        }

        $uNNumberNode = $customsNode->{CustomsDetails::N_UN_NUMBER};
        for ($n = 0; $n < $uNNumberNode->count(); $n++) {
            $this->assertEquals(
                $customsDetail->getUNNumber()[$n], (string)$uNNumberNode[$n]
            );
        }

        $this->assertEmpty($customsDetail->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($customsDetail->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws AuditFileException
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $customsNode = new \SimpleXMLElement(
            "<root></root>"
        );

        $details = new CustomsDetails(new ErrorRegister());
        $details->addCNCode("--------A");
        $details->addCNCode("");
        $details->addUNNumber("-------b");
        $details->addUNNumber("");

        $xml = $details->createXmlNode($customsNode)->asXML();
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
