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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\Validate\DocTableTotalCalc;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * ASourceDocuments
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class ASourceDocuments extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     *
     * @var \Rebelo\SaftPt\Validate\DocTableTotalCalc
     * @since 1.0.0
     */
    protected ?DocTableTotalCalc $docTableTotalCalc = null;

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get the Doc Table resume calculation from validation classes
     * @return \Rebelo\SaftPt\Validate\DocTableTotalCalc|null
     * @since 1.0.0
     */
    public function getDocTableTotalCalc(): ?DocTableTotalCalc
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        return $this->docTableTotalCalc;
    }

    /**
     * Get the Doc Table resume calculation from validation classes
     * @param \Rebelo\SaftPt\Validate\DocTableTotalCalc|null $docTableTotalCalc
     * @return void
     * @since 1.0.0
     */
    public function setDocTableTotalCalc(?DocTableTotalCalc $docTableTotalCalc): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->docTableTotalCalc = $docTableTotalCalc;
    }
}
