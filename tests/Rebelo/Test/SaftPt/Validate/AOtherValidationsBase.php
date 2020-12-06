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
use Rebelo\SaftPt\Validate\OtherValidations;
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
                    new \Rebelo\SaftPt\AuditFile\AuditFile()
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

            public function checkInvoicesType(array &$invoices, array &$type,
                                              ProgressBar $progreBar = null): void
            {
                parent::checkInvoicesType($invoices, $type, $progreBar);
            }

            public function checkStockMovementType(array &$stockMovements,
                                                   array &$type,
                                                   ProgressBar $progreBar = null): void
            {
                parent::checkStockMovementType(
                    $stockMovements, $type,
                    $progreBar
                );
            }

            public function checkWorkDocumentType(array &$workDocs,
                                                  array &$type,
                                                  ProgressBar $progreBar = null): void
            {
                parent::checkWorkDocumentType($workDocs, $type, $progreBar);
            }

            public function checkPaymentType(array &$payments, array &$type,
                                             ProgressBar $progreBar = null): void
            {
                parent::checkPaymentType($payments, $type, $progreBar);
            }
        };
    }
}