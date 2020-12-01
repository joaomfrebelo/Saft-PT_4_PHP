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

namespace Rebelo\SaftPt\AuditFile;

/**
 * File where is registed the error when create the xml node and export as xml.
 * This is because by teh Portuguese tax law the SAFT-PT file must exported even if as error.
 * So the best option is when the SAFT-PT file is exported if has errors dond't throw a
 * error but generate the file and show the errors to the user.
 * @author João Rebelo
 * @since 1.0.0
 */
class ErrorRegister
{
    /**
     * Errors on create xml node
     * @var string[]
     * @since 1.0.0
     */
    protected array $onCreateXmlNode = array();

    /**
     * Errors of teh test performed by libxml
     * @var string[]
     * @since 1.0.0
     */
    protected array $libXmlError = array();

    /**
     * Errors when the value is setted
     * @var string[]
     * @since 1.0.0
     */
    protected array $onSetValue = array();

    /**
     * Exception errors
     * @var string[]
     * @since 1.0.0
     */
    protected array $exceptionErrors = array();

    /**
     * Exception errors
     * @var string[]
     * @since 1.0.0
     */
    protected array $validationErrors = array();

    /**
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $warning = array();

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Get the onCreateXmlNode values error statck
     * @return string[]
     * @since 1.0.0
     */
    public function getOnCreateXmlNode(): array
    {
        return $this->onCreateXmlNode;
    }

    /**
     * Add error to stack
     * @param string $settedValError
     * @return void
     * @since 1.0.0
     */
    public function addOnCreateXmlNode(string $settedValError): void
    {
        $trans = AAuditFile::getI18n()->get($settedValError);
        if (\in_array($trans, $this->onCreateXmlNode) === false) {
            $this->onCreateXmlNode[] = $trans;
        }
    }

    /**
     * Get on set values errors stack
     * @return array
     * @since 1.0.0
     */
    public function getOnSetValue(): array
    {
        return $this->onSetValue;
    }

    /**
     * Add error to track
     * @param string $onSetValue
     * @return void
     * @since 1.0.0
     */
    public function addOnSetValue(string $onSetValue): void
    {
        $trans = AAuditFile::getI18n()->get($onSetValue);
        if (\in_array($trans, $this->onSetValue) === false) {
            $this->onSetValue[] = $trans;
        }
    }

    /**
     * Clear all errors
     * @return void
     * @since 1.0.0
     */
    public function cleaeAllErrors(): void
    {
        $this->libXmlError      = array();
        $this->onCreateXmlNode  = array();
        $this->onSetValue       = array();
        $this->exceptionErrors  = array();
        $this->validationErrors = array();
        $this->warning          = array();
    }

    /**
     * Get
     * @return string[]
     * @since 1.0.0
     */
    public function getLibXmlError(): array
    {
        return $this->libXmlError;
    }

    /**
     * Add error to stack
     * @param string $error
     * @return void
     * @since 1.0.0
     */
    public function addLibXmlError(string $error): void
    {
        $clean = \str_replace(["\n", "\r"], "", $error);
        if(\in_array($clean, $this->libXmlError)){
            return;
        }
        $this->libXmlError[] = $clean;
    }

    /**
     * Get
     * @return string[]
     * @since 1.0.0
     */
    public function getExceptionErrors(): array
    {
        return $this->exceptionErrors;
    }

    /**
     * Add error to stack
     * @param string $error
     * @return void
     * @since 1.0.0
     */
    public function addExceptionErrors(string $error): void
    {
        $this->exceptionErrors[] = $error;
    }

    /**
     * Get
     * @return string[]
     * @since 1.0.0
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * Add error to stack
     * @param string $error
     * @return void
     * @since 1.0.0
     */
    public function addValidationErrors(string $error): void
    {
        $this->validationErrors[] = $error;
    }

    /**
     * Get
     * @return string[]
     * @since 1.0.0
     */
    public function getWarnings(): array
    {
        return $this->warning;
    }

    /**
     * Add warning
     * @param string $warning
     * @return void
     * @since 1.0.0
     */
    public function addWarning(string $warning): void
    {
        $this->warning[] = $warning;
    }

    /**
     * Check if has errors
     * @return bool
     * @since 1.0.0
     */
    public function hasErrors(): bool
    {
        return \count($this->exceptionErrors) !== 0 ||
            \count($this->libXmlError) !== 0 ||
            \count($this->onCreateXmlNode) !== 0 ||
            \count($this->onSetValue) !== 0 ||
            \count($this->validationErrors) !== 0;
    }
}