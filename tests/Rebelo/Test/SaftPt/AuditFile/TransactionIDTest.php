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
use Rebelo\SaftPt\AuditFile\AuditFileException;
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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(TransactionID::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstanceSetsAndGets()
    {
        $transactioID = new TransactionID();
        $this->assertInstanceOf(TransactionID::class, $transactioID);
        $transDate    = RDate::parse(RDate::SQL_DATE, "2019-10-05");
        $transactioID->setDate($transDate);
        $this->assertSame($transDate, $transactioID->getDate());

        $journal = "AAA999";
        $transactioID->setJournalID($journal);
        $this->assertSame($journal, $transactioID->getJournalID());

        $doc = "CCC999";
        $transactioID->setDocArchivalNumber($doc);
        $this->assertSame($doc, $transactioID->getDocArchivalNumber());
    }

    public function testExceptions()
    {
        $transactioID = new TransactionID();

        try {
            $transactioID->setJournalID("");
            $this->fail("Set JournalID to a string that nor respect the regexp must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $transactioID->setJournalID(\str_pad("A", 31, "A"));
            $this->fail("Set JournalID to a string that nor respect the regexp must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }

        try {
            $transactioID->setDocArchivalNumber("");
            $this->fail("Set DocArchivalNumber to a string that nor respect the regexp must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try {
            $transactioID->setDocArchivalNumber(\str_pad("A", 31, "A"));
            $this->fail("Set DocArchivalNumber to a string that nor respect the regexp must throw "
                ."\Rebelo\SaftPt\AuditFile\AuditFileException");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
    }

    public function testCreateAndParseXML()
    {
        $transactioID = new TransactionID();
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

        $parsed = new TransactionID();
        $parsed->parseXmlNode($tranNode);

        $this->assertSame(
            $transDate->format(RDate::SQL_DATE),
            $parsed->getDate()->format(RDate::SQL_DATE)
        );

        $this->assertSame($journal, $parsed->getJournalID());
        $this->assertSame($doc, $parsed->getDocArchivalNumber());
    }
}