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

namespace Rebelo\Test\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class DocumentStatusTest
 *
 * @author João Rebelo
 */
class DocumentStatusTest
    extends TestCase
{

    /**
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DocumentStatus::class);
        $this->assertTrue(true);
    }

    public function testInstance()
    {
        $docStatus = new DocumentStatus();
        $this->assertInstanceOf(
            DocumentStatus::class, $docStatus
        );

        $this->assertNull($docStatus->getReason());

        try
        {
            $docStatus->getInvoiceStatus();
            $this->fail("getInvoiceStatus should throw error before setted");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try
        {
            $docStatus->getInvoiceStatusDate();
            $this->fail("getInvoiceStatusDate should throw error before setted");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try
        {
            $docStatus->getSourceBilling();
            $this->fail("getSourceBilling should throw error before setted");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(\Error::class, $ex);
        }

        try
        {
            $docStatus->getSourceID();
            $this->fail("getSourceID should throw error before setted");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(\Error::class, $ex);
        }
    }

    public function testInvoiceStatus()
    {
        $docStatus = new DocumentStatus();
        $status    = InvoiceStatus::N;
        $docStatus->setInvoiceStatus(new InvoiceStatus($status));
        $this->assertEquals($status, $docStatus->getInvoiceStatus()->get());
    }

    public function testInvoiceStatusDate()
    {
        $date      = new RDate();
        $docStatus = new DocumentStatus();
        $docStatus->setInvoiceStatusDate($date);
        $this->assertEquals(
            $date->format(RDate::DATE_T_TIME),
                          $docStatus->getInvoiceStatusDate()->format(RDate::DATE_T_TIME)
        );
    }

    public function testReazon()
    {
        $docStatus = new DocumentStatus();
        $reason    = "Test reason";
        $docStatus->setReason($reason);
        $this->assertEquals($reason, $docStatus->getReason());
        $docStatus->setReason(null);
        $this->assertNull($docStatus->getReason());
        try
        {
            $docStatus->setReason("");
            $this->fail("Reason should throw AuditFileException "
                . "when setted to an empty string");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex);
        }
        $docStatus->setReason(\str_pad($reason, 51, "9"));
        $this->assertEquals(50, \strlen($docStatus->getReason()));
    }

    public function testSourceID()
    {
        $docStatus = new DocumentStatus();
        $sourceID  = "Test sourceID";
        $docStatus->setSourceID($sourceID);
        $this->assertEquals($sourceID, $docStatus->getSourceID());
        try
        {
            $docStatus->setSourceID("");
            $this->fail("SourceID should throw AuditFileException "
                . "when setted to an empty string");
        }
        catch (\Exception | \Error $ex)
        {
            $this->assertInstanceOf(AuditFileException::class, $ex);
        }
        $docStatus->setSourceID(\str_pad($sourceID, 31, "9"));
        $this->assertEquals(30, \strlen($docStatus->getSourceID()));
    }

    public function testSourceBilling()
    {
        $billing   = SourceBilling::P;
        $docStatus = new DocumentStatus();
        $docStatus->setSourceBilling(new SourceBilling($billing));
        $this->assertEquals($billing, $docStatus->getSourceBilling()->get());
    }

}
