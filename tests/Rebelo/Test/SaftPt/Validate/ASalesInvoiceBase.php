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

namespace Rebelo\Test\SaftPt\Validate;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\SalesInvoices;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Invoice;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line;
use Rebelo\Decimal\UDecimal;
use Rebelo\Date\Date as RDate;

/**
 * Class ASalesInvoiceTeste
 *
 * @author João Rebelo
 */
abstract class ASalesInvoiceBase extends TestCase
{
    /**
     * The SalesInvoice to be possible to access to protected methods
     * @var \Rebelo\SaftPt\Validate\SalesInvoices
     */
    protected SalesInvoices $salesInvoice;

    public function salesInvoicesFactory(): void
    {
        $this->salesInvoice = new class extends SalesInvoices {

            public function __construct()
            {
                $private = \file_get_contents(PRIVATE_KEY_PATH);
                if ($private === false) {
                    TestCase::fail(
                        \sprintf(
                            "Private key '%s' not not loaded", PRIVATE_KEY_PATH
                        )
                    );
                }

                $public = \file_get_contents(PUBLIC_KEY_PATH);
                if ($public === false) {
                    TestCase::fail(
                        \sprintf(
                            "Public key '%s' not not loaded", PUBLIC_KEY_PATH
                        )
                    );
                }

                parent::__construct(
                    new \Rebelo\SaftPt\AuditFile\AuditFile(),
                    new \Rebelo\SaftPt\Sign\Sign(
                        $private, $public
                    )
                );
                $this->auditFile->getSourceDocuments()->getSalesInvoices()
                    ->setDocTableTotalCalc(
                        new \Rebelo\SaftPt\Validate\DocTableTotalCalc()
                    );
            }

            public function getAuditFile(): \Rebelo\SaftPt\AuditFile\AuditFile
            {
                return $this->auditFile;
            }

            public function setAuditFile(\Rebelo\SaftPt\AuditFile\AuditFile $auditFile): void
            {
                $this->auditFile = $auditFile;
            }

            /**
             * The total debit calculated from all docmunts of the table
             * @param \Rebelo\Decimal\UDecimal $debit
             * @return void
             */
            public function setDebit(UDecimal $debit): void
            {
                $this->debit = $debit;
            }

            /**
             * The total credit calculated from all documents of the table
             * @param \Rebelo\Decimal\UDecimal $credit
             * @return void
             */
            public function setCredit(UDecimal $credit): void
            {
                $this->credit = $credit;
            }

            /**
             * The total debit calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $docDebit
             * @return void
             */
            public function setDocDebit(UDecimal $docDebit): void
            {
                $this->docDebit = $docDebit;
            }

            /**
             * The total credit calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $docCredit
             * @return void
             */
            public function setDocCredit(UDecimal $docCredit): void
            {
                $this->docCredit = $docCredit;
            }

            /**
             * The net total calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $netTotal
             * @return void
             */
            public function setNetTotal(UDecimal $netTotal): void
            {
                $this->netTotal = $netTotal;
            }

            /**
             *  The total tax calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $taxPayable
             * @return void
             */
            public function setTaxPayable(UDecimal $taxPayable): void
            {
                $this->taxPayable = $taxPayable;
            }

            /**
             * The Gross total calculated of the current document
             * @param \Rebelo\Decimal\UDecimal $grossTotal
             * @return void
             */
            public function setGrossTotal(UDecimal $grossTotal): void
            {
                $this->grossTotal = $grossTotal;
            }

            /**
             * The last hash of document that has been the signature validated
             * in the same document serie
             * @var string
             * @since 1.0.0
             */
            public function setLastHash(string $hash): void
            {
                $this->lastHash = $hash;
            }

            public function setLastDocDate(RDate $date): void
            {
                $this->lastDocDate = $date;
            }

            public function setLastSystemEntryDate(RDate $date): void
            {
                $this->lastSystemEntryDate = $date;
            }

            public function isValid(): bool
            {
                return $this->isValid;
            }

            public function numberOfEntries(): void
            {
                parent::numberOfEntries();
            }

            public function documentStatus(Invoice $invoice): void
            {
                parent::documentStatus($invoice);
            }

            public function customerId(Invoice $invoice): void
            {
                parent::customerId($invoice);
            }

            public function lines(Invoice $invoice): void
            {
                parent::lines($invoice);
            }

            public function refernces(Line $line, Invoice $invoice): void
            {
                parent::refernces($line, $invoice);
            }

            public function producCode(Line $line, Invoice $invoice): void
            {
                parent::producCode($line, $invoice);
            }

            public function tax(Line $line, Invoice $invoice): void
            {
                parent::tax($line, $invoice);
            }

            public function totals(Invoice $invoice): void
            {
                parent::totals($invoice);
            }

            public function sign(Invoice $invoice): void
            {
                parent::sign($invoice);
            }

            public function shipement(Invoice $invoice): void
            {
                parent::shipement($invoice);
            }

            public function invoiceDateAndSystemEntryDate(Invoice $invoice): void
            {
                parent::invoiceDateAndSystemEntryDate($invoice);
            }

            public function invoice(Invoice $invoice): void
            {
                parent::invoice($invoice);
            }

            public function totalDebit(): void
            {
                parent::totalDebit();
            }

            public function totalCredit(): void
            {
                parent::totalCredit();
            }
            
            public function payment(Invoice $invoice) : void
            {
                parent::payment($invoice);
            }
            
            public function withholdingTax(Invoice $invoice) : void
            {
                parent::withholdingTax($invoice);
            }
            
            public function outOfDateInvoiceTypes(Invoice $invoice) : void
            {
                parent::outOfDateInvoiceTypes($invoice);
            }
        };
    }
}
