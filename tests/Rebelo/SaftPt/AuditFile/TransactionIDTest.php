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

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateFormatException;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\CommuneTest;

/**
 * TransactionIDTest
 *
 * @author João Rebelo
 */
class TransactionIDTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(TransactionID::class);
        $this->assertTrue(true);
    }

    /**
     * @throws DateParseException
     * @throws DateFormatException
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetsAndGets(): void
    {
        $transactionID = new TransactionID(new ErrorRegister());
        $this->assertInstanceOf(TransactionID::class, $transactionID);
        $transDate    = RDate::parse(RDate::SQL_DATE, "2019-10-05");
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
     * @test
     */
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
     * @author João Rebelo
     * @test
     */

    /**
     * @throws DateParseException
     * @throws DateFormatException
     * @throws AuditFileException
     * @author João Rebelo
     * @test
     */
    public function testCreateAndParseXML(): void
    {
        $transactionID = new TransactionID(new ErrorRegister());
        $transDate    = RDate::parse(RDate::SQL_DATE, "2019-10-05");
        $transactionID->setDate($transDate);
        $journal      = "AAA999";
        $transactionID->setJournalID($journal);
        $doc          = "CCC999";
        $transactionID->setDocArchivalNumber($doc);

        $rootNode = new \SimpleXMLElement("<root></root>");
        $tranNode = $transactionID->createXmlNode($rootNode);
        $this->assertSame(
            TransactionID::N_TRANSACTIONID,
            $rootNode->{TransactionID::N_TRANSACTIONID}->getName()
        );

        $this->assertSame(TransactionID::N_TRANSACTIONID, $tranNode->getName());

        $this->assertSame(
            $transDate->format(RDate::SQL_DATE)." ".$journal." ".$doc,
            (string) $tranNode
        );

        $parsed = new TransactionID(new ErrorRegister());
        $parsed->parseXmlNode($tranNode);

        $this->assertSame(
            $transDate->format(RDate::SQL_DATE),
            $parsed->getDate()->format(RDate::SQL_DATE)
        );

        $this->assertSame($journal, $parsed->getJournalID());
        $this->assertSame($doc, $parsed->getDocArchivalNumber());

        $this->assertEmpty($transactionID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($transactionID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactionID->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @throws DateFormatException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
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
     * @throws DateFormatException
     * @throws \Exception
     * @author João Rebelo
     * @test
     */
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
