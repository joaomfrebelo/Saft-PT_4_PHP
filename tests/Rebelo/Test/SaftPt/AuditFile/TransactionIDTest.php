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

namespace Rebelo\Test\SaftPt\AuditFile;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TransactionID;
use Rebelo\Date\Date as RDate;

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
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(TransactionID::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstanceSetsAndGets(): void
    {
        $transactioID = new TransactionID(new ErrorRegister());
        $this->assertInstanceOf(TransactionID::class, $transactioID);
        $transDate    = RDate::parse(RDate::SQL_DATE, "2019-10-05");
        $transactioID->setDate($transDate);
        $this->assertSame($transDate, $transactioID->getDate());

        $journal = "AAA999";
        $this->assertTrue($transactioID->setJournalID($journal));
        $this->assertSame($journal, $transactioID->getJournalID());

        $doc = "CCC999";
        $this->assertTrue($transactioID->setDocArchivalNumber($doc));
        $this->assertSame($doc, $transactioID->getDocArchivalNumber());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testExceptions(): void
    {
        $transactioID = new TransactionID(new ErrorRegister());

        $transactioID->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($transactioID->setJournalID(""));
        $this->assertSame("", $transactioID->getJournalID());
        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnSetValue());

        $wrong = \str_pad("A", 31, "A");
        $transactioID->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($transactioID->setJournalID($wrong));
        $this->assertSame($wrong, $transactioID->getJournalID());
        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnSetValue());

        $transactioID->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($transactioID->setDocArchivalNumber(""));
        $this->assertSame("", $transactioID->getDocArchivalNumber());
        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnSetValue());

        $transactioID->getErrorRegistor()->cleaeAllErrors();
        $this->assertFalse($transactioID->setDocArchivalNumber($wrong));
        $this->assertSame($wrong, $transactioID->getDocArchivalNumber());
        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnSetValue());
    }
    /**
     * @author João Rebelo
     * @test
     */

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateAndParseXML(): void
    {
        $transactioID = new TransactionID(new ErrorRegister());
        $transDate    = RDate::parse(RDate::SQL_DATE, "2019-10-05");
        $transactioID->setDate($transDate);
        $journal      = "AAA999";
        $transactioID->setJournalID($journal);
        $doc          = "CCC999";
        $transactioID->setDocArchivalNumber($doc);

        $rootNode = new \SimpleXMLElement("<root></root>");
        $tranNode = $transactioID->createXmlNode($rootNode);
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

        $this->assertEmpty($transactioID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($transactioID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactioID->getErrorRegistor()->getLibXmlError());
    }

    /**
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
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlWithWrongValues(): void
    {
        $supplierNode = new \SimpleXMLElement(
            "<root></root>"
        );
        $transactioID = new TransactionID(new ErrorRegister());
        $transactioID->setDocArchivalNumber("");
        $transactioID->setJournalID("");

        $xml = $transactioID->createXmlNode($supplierNode)->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertNotEmpty($transactioID->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($transactioID->getErrorRegistor()->getLibXmlError());
    }
}