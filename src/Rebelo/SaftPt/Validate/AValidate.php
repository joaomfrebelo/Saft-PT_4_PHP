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

namespace Rebelo\SaftPt\Validate;

use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\Date\Date as RDate;

/**
 * Description of AValidate
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AValidate
{
    /**
     * To be return at the end of validation, within the validation will
     * be setted to false when not passed
     * @var bool
     */
    protected bool $isValid = true;

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\AuditFile;
     * @since 1.0.0
     */
    protected AuditFile $auditFile;

    /**
     * The last type of document that has been the signature validated
     * @var string
     * @since 1.0.0
     */
    protected ?string $lastType = null;

    /**
     * The last serie of document that has been the signature validated
     * @var string
     * @since 1.0.0
     */
    protected ?string $lastSerie = null;

    /**
     * The last hash of document that has been the signature validated
     * in the same document serie
     * @var string
     * @since 1.0.0
     */
    protected string $lastHash = "";

    /**
     * The SystemEntryDate of the last document of the same serie,
     * if is the first of the serie will be null
     *
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    protected ?RDate $lastDocDate = null;

    /**
     * The SystemEntryDate of the last document of the same serie,
     * if is the first of the serie will be null
     *
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    protected ?RDate $lastSystemEntryDate = null;

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->auditFile = $auditFile;
    }
}