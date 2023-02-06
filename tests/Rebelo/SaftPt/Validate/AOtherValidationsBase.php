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

namespace Rebelo\SaftPt\Validate;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class ASalesWorkDocumentTeste
 *
 * @author João Rebelo
 */
abstract class AOtherValidationsBase extends TestCase
{
    /**
     * The OtherValidations to be possible to access to protected methods
     * @var \Rebelo\SaftPt\Validate\OtherValidations
     */
    protected OtherValidations $otherValidations;

    public function otherValidationsFactory(): void
    {
        $this->otherValidations = new class extends OtherValidations {

            public function __construct()
            {

                parent::__construct(
                    new AuditFile()
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

            public function checkInvoicesType(array       &$invoices, array &$type,
                                              ProgressBar $progressBar = null): void
            {
                parent::checkInvoicesType($invoices, $type, $progressBar);
            }

            public function checkStockMovementType(array       &$stockMovements,
                                                   array       &$type,
                                                   ProgressBar $progressBar = null): void
            {
                parent::checkStockMovementType(
                    $stockMovements, $type,
                    $progressBar
                );
            }

            public function checkWorkDocumentType(array       &$workDocs,
                                                  array       &$type,
                                                  ProgressBar $progressBar = null): void
            {
                parent::checkWorkDocumentType($workDocs, $type, $progressBar);
            }

            public function checkPaymentType(array       &$payments, array &$type,
                                             ProgressBar $progressBar = null): void
            {
                parent::checkPaymentType($payments, $type, $progressBar);
            }
        };
    }
}
