<?php /** @noinspection PhpRedundantMethodOverrideInspection */
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

namespace Rebelo\SaftPt\Validate;

use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument;
use Rebelo\SaftPt\Sign\Sign;

/**
 * Class ASalesWorkDocumentTest
 *
 * @author João Rebelo
 */
abstract class AWorkingDocumentsBase extends TestCase
{
    /**
     * The WorkDocument to be possible to access to protected methods
     *
     * @var \Rebelo\SaftPt\Validate\WorkingDocuments
     */
    protected WorkingDocuments $workingDocuments;

    public function workingDocumentsFactory(): void
    {
        $this->workingDocuments = new class extends WorkingDocuments {

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
                    new AuditFile(),
                    new Sign(
                        $private, $public
                    )
                );
                $this->auditFile->getSourceDocuments()?->getWorkingDocuments()?->setDocTableTotalCalc(
                    new DocTableTotalCalc()
                );
            }

            public function getAuditFile(): AuditFile
            {
                return $this->auditFile;
            }

            public function setAuditFile(AuditFile $auditFile): void
            {
                $this->auditFile = $auditFile;
            }

            /**
             * The total debit calculated from all documents of the table
             *
             * @param \Decimal\Decimal $debit
             *
             * @return void
             */
            public function setDebit(Decimal $debit): void
            {
                $this->debit = $debit;
            }

            /**
             * The total credit calculated from all documents of the table
             *
             * @param \Decimal\Decimal $credit
             *
             * @return void
             */
            public function setCredit(Decimal $credit): void
            {
                $this->credit = $credit;
            }

            /**
             * The total debit calculated of the current document
             *
             * @param \Decimal\Decimal $docDebit
             *
             * @return void
             */
            public function setDocDebit(Decimal $docDebit): void
            {
                $this->docDebit = $docDebit;
            }

            /**
             * The total credit calculated of the current document
             *
             * @param \Decimal\Decimal $docCredit
             *
             * @return void
             */
            public function setDocCredit(Decimal $docCredit): void
            {
                $this->docCredit = $docCredit;
            }

            /**
             * The net total calculated of the current document
             *
             * @param \Decimal\Decimal $netTotal
             *
             * @return void
             */
            public function setNetTotal(Decimal $netTotal): void
            {
                $this->netTotal = $netTotal;
            }

            /**
             *  The total tax calculated of the current document
             *
             * @param \Decimal\Decimal $taxPayable
             *
             * @return void
             */
            public function setTaxPayable(Decimal $taxPayable): void
            {
                $this->taxPayable = $taxPayable;
            }

            /**
             * The Gross total calculated of the current document
             *
             * @param \Decimal\Decimal $grossTotal
             *
             * @return void
             */
            public function setGrossTotal(Decimal $grossTotal): void
            {
                $this->grossTotal = $grossTotal;
            }

            /**
             * The last hash of document that has been the signature validated
             * in the same document serial
             *
             * @param string $hash
             *
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

            public function documentStatus(WorkDocument $workDocument): void
            {
                parent::documentStatus($workDocument);
            }

            public function customerId(WorkDocument $workDocument): void
            {
                parent::customerId($workDocument);
            }

            public function lines(WorkDocument $workDocument): void
            {
                parent::lines($workDocument);
            }

            public function references(Line $line, WorkDocument $workDocument): void
            {
                parent::references($line, $workDocument);
            }

            public function productCode(Line $line, WorkDocument $workDocument): void
            {
                parent::productCode($line, $workDocument);
            }

            public function tax(Line $line, WorkDocument $workDocument): void
            {
                parent::tax($line, $workDocument);
            }

            public function totals(WorkDocument $workDocument): void
            {
                parent::totals($workDocument);
            }

            public function sign(WorkDocument $workDocument): void
            {
                parent::sign($workDocument);
            }

            public function workDocumentDateAndSystemEntryDate(WorkDocument $workDocument): void
            {
                parent::workDocumentDateAndSystemEntryDate($workDocument);
            }

            public function workDocument(WorkDocument $workDocument): void
            {
                parent::workDocument($workDocument);
            }

            public function totalDebit(): void
            {
                parent::totalDebit();
            }

            public function totalCredit(): void
            {
                parent::totalCredit();
            }

            public function outOfDateInvoiceTypes(WorkDocument $workDocument): void
            {
                parent::outOfDateInvoiceTypes($workDocument);
            }
        };
    }
}
