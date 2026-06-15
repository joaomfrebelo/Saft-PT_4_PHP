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

namespace Rebelo\SaftPt\AuditFile;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\Commune;

/**
 * TransactionIDTest
 *
 * @author João Rebelo
 */
class TransactionIDTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(TransactionID::class))->testReflection(TransactionID::class);
    }

    /**
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @author João Rebelo
     */
    #[Test]
    public function testInstanceSetsAndGets(): void
    {
        $transactionID = new TransactionID(new ErrorRegister());
        $this->assertInstanceOf(TransactionID::class, $transactionID);
        $transDate    = RDate::parse(Pattern::SQL_DATE, "2019-10-05");
        $transactionID->setDate($transDate);
        $this->assertSame($transDate, $transactionID->getDate());

        $journal = "AAA999";
        $this->assertTrue($transactionID->setJournalID($journal));
        $this->assertSame($journal, $transactionID->getJournalID());

        $doc = "CCC999";
        $this->assertTrue($transactionID->setDocArchivalNumber($doc));
        $this->assertSame($doc, $transactionID->getDocArchivalNumber());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testExceptions(): void
    {
        $transactionID = new TransactionID(new ErrorRegister());

        $transactionID->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($transactionID->setJournalID(""));
        $this->assertSame("", $transactionID->getJournalID());
        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnSetValue());

        $wrong = \str_pad("A", 31, "A");
        $transactionID->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($transactionID->setJournalID($wrong));
        $this->assertSame($wrong, $transactionID->getJournalID());
        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnSetValue());

        $transactionID->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($transactionID->setDocArchivalNumber(""));
        $this->assertSame("", $transactionID->getDocArchivalNumber());
        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnSetValue());

        $transactionID->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($transactionID->setDocArchivalNumber($wrong));
        $this->assertSame($wrong, $transactionID->getDocArchivalNumber());
        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnSetValue());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     */
    #[Test]
    public function testCreateAndParseXML(): void
    {
        $transactionID = new TransactionID(new ErrorRegister());
        $transDate    = RDate::parse(Pattern::SQL_DATE, "2019-10-05");
        $transactionID->setDate($transDate);
        $journal      = "AAA999";
        $transactionID->setJournalID($journal);
        $doc          = "CCC999";
        $transactionID->setDocArchivalNumber($doc);

        $rootNode = new \SimpleXMLElement("<root></root>");
        $tranNode = $transactionID->createXmlNode($rootNode);
        $this->assertSame(
            TransactionID::N_TRANSACTION_ID,
            $rootNode->{TransactionID::N_TRANSACTION_ID}->getName()
        );

        $this->assertSame(TransactionID::N_TRANSACTION_ID, $tranNode->getName());

        $this->assertSame(
            $transDate->format(Pattern::SQL_DATE)." ".$journal." ".$doc,
            (string) $tranNode
        );

        $parsed = new TransactionID(new ErrorRegister());
        $parsed->parseXmlNode($tranNode);

        $this->assertSame(
            $transDate->format(Pattern::SQL_DATE),
            $parsed->getDate()->format(Pattern::SQL_DATE)
        );

        $this->assertSame($journal, $parsed->getJournalID());
        $this->assertSame($doc, $parsed->getDocArchivalNumber());

        $this->assertEmpty($transactionID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($transactionID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactionID->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlNodeWithoutSet(): void
    {
        $customerNode  = new \SimpleXMLElement(
            "<root></root>"
        );
        $transactionID = new TransactionID(new ErrorRegister());
        $xml           = $transactionID->createXmlNode($customerNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($transactionID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactionID->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws \Exception
     * @author João Rebelo
     */
    #[Test]
    public function testCreateXmlWithWrongValues(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<root></root>"
        );
        $transactionID = new TransactionID(new ErrorRegister());
        $transactionID->setDocArchivalNumber("");
        $transactionID->setJournalID("");

        $xml = $transactionID->createXmlNode($supplierNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($transactionID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactionID->getErrorRegistor()->getLibXmlError());
    }
}
