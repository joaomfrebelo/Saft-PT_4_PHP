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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;

/**
 * WithholdingTaxTest
 *
 * @author João Rebelo
 */
class WithholdingTaxTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WithholdingTax::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());
        $this->assertInstanceOf(WithholdingTax::class, $withHolTax);
        $this->assertFalse($withHolTax->issetWithholdingTaxAmount());
        $this->assertNull($withHolTax->getWithholdingTaxType());
        $this->assertNull($withHolTax->getWithholdingTaxDescription());

        try {
            $withHolTax->getWithholdingTaxAmount();
            $this->fail("Get WithholdingTaxAmount without initialization Should throw \Error");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetWithholdingTaxType(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());

        $taxType = new WithholdingTaxType(WithholdingTaxType::IRS);
        $withHolTax->setWithholdingTaxType($taxType);
        $this->assertSame($taxType, $withHolTax->getWithholdingTaxType());
        $withHolTax->setWithholdingTaxType(null);
        $this->assertNull($withHolTax->getWithholdingTaxType());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetWithholdingTaxDescription(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());

        $desc = "Tax description";
        $this->assertTrue($withHolTax->setWithholdingTaxDescription($desc));
        $this->assertSame($desc, $withHolTax->getWithholdingTaxDescription());
        $this->assertTrue($withHolTax->setWithholdingTaxDescription(null));
        $this->assertNull($withHolTax->getWithholdingTaxDescription());
        $this->assertTrue(
            $withHolTax->setWithholdingTaxDescription(
                \str_pad("A", 61, "A")
            )
        );
        $this->assertSame(
            60, \strlen($withHolTax->getWithholdingTaxDescription())
        );

        $withHolTax->getErrorRegistor()->clearAllErrors();
        $withHolTax->setWithholdingTaxDescription("");
        $this->assertSame("", $withHolTax->getWithholdingTaxDescription());
        $this->assertNotEmpty($withHolTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testSetGetWithholdingTaxAmount(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());

        $amount = 0.99;
        $withHolTax->setWithholdingTaxAmount($amount);
        $this->assertSame($amount, $withHolTax->getWithholdingTaxAmount());
        $this->assertTrue($withHolTax->issetWithholdingTaxAmount());

        $wrong = -0.01;
        $withHolTax->getErrorRegistor()->clearAllErrors();
        $withHolTax->setWithholdingTaxAmount($wrong);
        $this->assertSame($wrong, $withHolTax->getWithholdingTaxAmount());
        $this->assertNotEmpty($withHolTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     *
     * @return WithholdingTax
     */
    public function createWithholdingTax(): WithholdingTax
    {
        $withholdingTax = new WithholdingTax(new ErrorRegister());
        $withholdingTax->setWithholdingTaxAmount(9.45);
        $withholdingTax->setWithholdingTaxDescription("Test tax");
        $withholdingTax->setWithholdingTaxType(
            new WithholdingTaxType(WithholdingTaxType::IRS)
        );
        return $withholdingTax;
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWrongName(): void
    {
        $withholdingTax = new WithholdingTax(new ErrorRegister());
        $node           = new \SimpleXMLElement("<root></root>");
        try {
            $withholdingTax->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
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
        $withholdingTax = new WithholdingTax(new ErrorRegister());
        $node           = new \SimpleXMLElement("<root></root>");
        try {
            $withholdingTax->parseXmlNode($node);
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNode(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );

        $withHolTaxNode = $withholdingTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $withHolTaxNode);

        $this->assertSame(
            WithholdingTax::N_WITHHOLDINGTAX, $withHolTaxNode->getName()
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxType()->get(),
            (string) $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXTYPE}
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxDescription(),
            (string) $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXDESCRIPTION}
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            (float) $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXAMOUNT}
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeNull(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node           = new \SimpleXMLElement(
            "<".Payment::N_PAYMENT."></".Payment::N_PAYMENT.">"
        );

        $withHolTaxNode = $withholdingTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $withHolTaxNode);

        $this->assertSame(
            WithholdingTax::N_WITHHOLDINGTAX, $withHolTaxNode->getName()
        );

        $this->assertSame(
            0,
            $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXTYPE}->count()
        );

        $this->assertSame(
            0,
            $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXDESCRIPTION}->count()
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            (float) $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXAMOUNT}
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXml(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $xml            = $withholdingTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new WithholdingTax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $withholdingTax->getWithholdingTaxType()->get(),
            $parsed->getWithholdingTaxType()->get()
        );
        $this->assertSame(
            $withholdingTax->getWithholdingTaxDescription(),
            $parsed->getWithholdingTaxDescription()
        );
        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            $parsed->getWithholdingTaxAmount()
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testeParseXmlNull(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node           = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $xml            = $withholdingTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new WithholdingTax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getWithholdingTaxType());
        $this->assertNull($parsed->getWithholdingTaxDescription());
        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            $parsed->getWithholdingTaxAmount()
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $withholdingTaxNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $withholdingTax     = new WithholdingTax(new ErrorRegister());
        $xml                = $withholdingTax->createXmlNode($withholdingTaxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $withholdingtaxNode = new \SimpleXMLElement(
            "<".Invoice::N_INVOICE."></".Invoice::N_INVOICE.">"
        );
        $withholdingtax     = new WithholdingTax(new ErrorRegister());
        $withholdingtax->setWithholdingTaxAmount(-0.01);
        $withholdingtax->setWithholdingTaxDescription("");

        $xml = $withholdingtax->createXmlNode($withholdingtaxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($withholdingtax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($withholdingtax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($withholdingtax->getErrorRegistor()->getLibXmlError());
    }
}
