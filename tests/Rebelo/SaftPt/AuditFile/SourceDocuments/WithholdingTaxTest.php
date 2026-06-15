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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\Commune;

/**
 * WithholdingTaxTest
 *
 * @author João Rebelo
 */
class WithholdingTaxTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(WithholdingTax::class))->testReflection(WithholdingTax::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
        } catch (\Exception|\Error $e) {
            $this->assertInstanceOf(\Error::class, $e);
        }
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSetGetWithholdingTaxType(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());

        $taxType = WithholdingTaxType::IRS;
        $withHolTax->setWithholdingTaxType($taxType);
        $this->assertSame($taxType, $withHolTax->getWithholdingTaxType());
        $withHolTax->setWithholdingTaxType(null);
        $this->assertNull($withHolTax->getWithholdingTaxType());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
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
     */
    #[Test]
    public function testSetGetWithholdingTaxAmount(): void
    {
        $withHolTax = new WithholdingTax(new ErrorRegister());

        $amount = new Decimal("0.99");
        $withHolTax->setWithholdingTaxAmount($amount);
        $this->assertSame($amount, $withHolTax->getWithholdingTaxAmount());
        $this->assertTrue($withHolTax->issetWithholdingTaxAmount());

        $wrong = new Decimal("-0.01");
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
        $withholdingTax->setWithholdingTaxAmount(new Decimal("9.45"));
        $withholdingTax->setWithholdingTaxDescription("Test tax");
        $withholdingTax->setWithholdingTaxType(WithholdingTaxType::IRS);
        return $withholdingTax;
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWrongName(): void
    {
        $withholdingTax = new WithholdingTax(new ErrorRegister());
        $node           = new \SimpleXMLElement("<root></root>");
        try {
            $withholdingTax->createXmlNode($node);
            $this->fail(
                "Create a xml node on a wrong node should throw "
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
        $withholdingTax = new WithholdingTax(new ErrorRegister());
        $node           = new \SimpleXMLElement("<root></root>");
        try {
            $withholdingTax->parseXmlNode($node);
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNode(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );

        $withHolTaxNode = $withholdingTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $withHolTaxNode);

        $this->assertSame(
            WithholdingTax::N_WITHHOLDING_TAX, $withHolTaxNode->getName()
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxType()?->value,
            (string)$node->{WithholdingTax::N_WITHHOLDING_TAX}->{WithholdingTax::N_WITHHOLDING_TAX_TYPE}
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxDescription(),
            (string)$node->{WithholdingTax::N_WITHHOLDING_TAX}
                ->{WithholdingTax::N_WITHHOLDING_TAX_DESCRIPTION}
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount()->toFloat(),
            (float)$node->{WithholdingTax::N_WITHHOLDING_TAX}
                ->{WithholdingTax::N_WITHHOLDING_TAX_AMOUNT}
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeNull(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node = new \SimpleXMLElement(
            "<" . Payment::N_PAYMENT . "></" . Payment::N_PAYMENT . ">"
        );

        $withHolTaxNode = $withholdingTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $withHolTaxNode);

        $this->assertSame(
            WithholdingTax::N_WITHHOLDING_TAX, $withHolTaxNode->getName()
        );

        $this->assertSame(
            0,
            $node->{WithholdingTax::N_WITHHOLDING_TAX}
                ->{WithholdingTax::N_WITHHOLDING_TAX_TYPE}->count()
        );

        $this->assertSame(
            0,
            $node->{WithholdingTax::N_WITHHOLDING_TAX}
                ->{WithholdingTax::N_WITHHOLDING_TAX_DESCRIPTION}->count()
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount()->toFloat(),
            (float)$node->{WithholdingTax::N_WITHHOLDING_TAX}
                ->{WithholdingTax::N_WITHHOLDING_TAX_AMOUNT}
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXml(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml            = $withholdingTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new WithholdingTax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertSame(
            $withholdingTax->getWithholdingTaxType(),
            $parsed->getWithholdingTaxType()
        );
        $this->assertSame(
            $withholdingTax->getWithholdingTaxDescription(),
            $parsed->getWithholdingTaxDescription()
        );
        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount()->toFloat(),
            $parsed->getWithholdingTaxAmount()->toFloat()
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testParseXmlNull(): void
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml  = $withholdingTax->createXmlNode($node)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $parsed = new WithholdingTax(new ErrorRegister());
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getWithholdingTaxType());
        $this->assertNull($parsed->getWithholdingTaxDescription());
        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount()->toFloat(),
            $parsed->getWithholdingTaxAmount()->toFloat()
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $withholdingTaxNode = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
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
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $withholdingTaxNode = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $withholdingTax     = new WithholdingTax(new ErrorRegister());
        $withholdingTax->setWithholdingTaxAmount(new Decimal("-0.01"));
        $withholdingTax->setWithholdingTaxDescription("");

        $xml = $withholdingTax->createXmlNode($withholdingTaxNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to generate xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertEmpty($withholdingTax->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($withholdingTax->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($withholdingTax->getErrorRegistor()->getLibXmlError());
    }
}
