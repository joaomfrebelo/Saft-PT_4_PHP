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
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTaxType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payment;

/**
 * WithholdingTaxTest
 *
 * @author João Rebelo
 */
class WithholdingTaxTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(WithholdingTax::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $withHolTax = new WithholdingTax();
        $this->assertInstanceOf(WithholdingTax::class, $withHolTax);
        $this->assertNull($withHolTax->getWithholdingTaxType());
        $this->assertNull($withHolTax->getWithholdingTaxDescription());

        try
        {
            $withHolTax->getWithholdingTaxAmount();
            $this->fail("Get WithholdingTaxAmount without initialization Should throw \Error");
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
        $withHolTax = new WithholdingTax();

        $taxType = new WithholdingTaxType(WithholdingTaxType::IRS);
        $withHolTax->setWithholdingTaxType($taxType);
        $this->assertSame($taxType, $withHolTax->getWithholdingTaxType());
        $withHolTax->setWithholdingTaxType(null);
        $this->assertNull($withHolTax->getWithholdingTaxType());

        $desc = "Tax description";
        $withHolTax->setWithholdingTaxDescription($desc);
        $this->assertSame($desc, $withHolTax->getWithholdingTaxDescription());
        $withHolTax->setWithholdingTaxDescription(null);
        $this->assertNull($withHolTax->getWithholdingTaxDescription());
        $withHolTax->setWithholdingTaxDescription(\str_pad("A", 61, "A"));
        $this->assertSame(60,
                          \strlen($withHolTax->getWithholdingTaxDescription()));
        try
        {
            $withHolTax->setWithholdingTaxDescription("");
            $this->fail("Set WithholdingTaxDescription to an empty string Should "
                . "throw \Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        $amount = 0.99;
        $withHolTax->setWithholdingTaxAmount($amount);
        $this->assertSame($amount, $withHolTax->getWithholdingTaxAmount());
        try
        {
            $withHolTax->setWithholdingTaxAmount(-0.01);
            $this->fail("Set WithholdingTaxAmount to a negative number Should "
                . "throw \Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    /**
     *
     * @return WithholdingTax
     */
    public function createWithholdingTax(): WithholdingTax
    {
        $withholdingTax = new WithholdingTax();
        $withholdingTax->setWithholdingTaxAmount(9.45);
        $withholdingTax->setWithholdingTaxDescription("Test tax");
        $withholdingTax->setWithholdingTaxType(
            new WithholdingTaxType(WithholdingTaxType::IRS)
        );
        return $withholdingTax;
    }

    /**
     *
     */
    public function testCreateXmlNodeWrongName()
    {
        $withholdingTax = new WithholdingTax();
        $node           = new \SimpleXMLElement("<root></root>");
        try
        {
            $withholdingTax->createXmlNode($node);
            $this->fail("Create a xml node on a wrong node should throw "
                . "\Rebelo\SaftPt\AuditFile\AuditFileException");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\AuditFileException::class, $e
            );
        }
    }

    /**
     *
     */
    public function testParseXmlNodeWrongName()
    {
        $withholdingTax = new WithholdingTax();
        $node           = new \SimpleXMLElement("<root></root>");
        try
        {
            $withholdingTax->parseXmlNode($node);
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

    /**
     *
     */
    public function testCreateXmlNode()
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
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
    }

    /**
     *
     */
    public function testCreateXmlNodeNull()
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node           = new \SimpleXMLElement(
            "<" . Payment::N_PAYMENT . "></" . Payment::N_PAYMENT . ">"
        );

        $withHolTaxNode = $withholdingTax->createXmlNode($node);
        $this->assertInstanceOf(\SimpleXMLElement::class, $withHolTaxNode);

        $this->assertSame(
            WithholdingTax::N_WITHHOLDINGTAX, $withHolTaxNode->getName()
        );

        $this->assertSame(0,
                          $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXTYPE}->count()
        );

        $this->assertSame(0,
                          $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXDESCRIPTION}->count()
        );

        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            (float) $node->{WithholdingTax::N_WITHHOLDINGTAX}
            ->{WithholdingTax::N_WITHHOLDINGTAXAMOUNT}
        );
    }

    /**
     *
     */
    public function testeParseXml()
    {
        $withholdingTax = $this->createWithholdingTax();
        $node           = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml            = $withholdingTax->createXmlNode($node)->asXML();

        $parsed = new WithholdingTax();
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
    }

    /**
     *
     */
    public function testeParseXmlNull()
    {
        $withholdingTax = $this->createWithholdingTax();
        $withholdingTax->setWithholdingTaxType(null);
        $withholdingTax->setWithholdingTaxDescription(null);
        $node           = new \SimpleXMLElement(
            "<" . Invoice::N_INVOICE . "></" . Invoice::N_INVOICE . ">"
        );
        $xml            = $withholdingTax->createXmlNode($node)->asXML();

        $parsed = new WithholdingTax();
        $parsed->parseXmlNode(new \SimpleXMLElement($xml));

        $this->assertNull($parsed->getWithholdingTaxType());
        $this->assertNull($parsed->getWithholdingTaxDescription());
        $this->assertSame(
            $withholdingTax->getWithholdingTaxAmount(),
            $parsed->getWithholdingTaxAmount()
        );
    }

}
