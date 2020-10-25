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

use Rebelo\Enum\AEnum;

/**
 * SourceBilling<br>
 * To fill in with:<br>
 * “P” – Document created in the invoicing program;<br>
 * “I” – Document integrated and produced in a different invoicing program;<br>
 * “M” – Recovered or manually issued document.<br>
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling P()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling I()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling M()
 * <pre>
 * &lt;xs:simpleType name="SAFTPTSourceBilling"&gt;
 *  &lt;xs:annotation&gt;
 *      &lt;xs:documentation&gt;P para documento produzido na aplicacao, I para documento integrado e
 *          produzido noutra aplicacao, M para documento proveniente de recuperacao ou de
 *          emissao manual &lt;/xs:documentation&gt;
 *  &lt;/xs:annotation&gt;
 *  &lt;xs:restriction base="xs:string"&gt;
 *      &lt;xs:enumeration value="P"/&gt;
 *      &lt;xs:enumeration value="I"/&gt;
 *      &lt;xs:enumeration value="M"/&gt;
 *  &lt;/xs:restriction&gt;
 * </pre>
 * @author João Rebelo
 */
class SourceBilling extends AEnum
{
    /**
     * “P” – Document created in the invoicing program;<br>
     * &lt;xs:enumeration value="P"/&gt;
     */
    const P = "P";

    /**
     * I -> “I” – Document integrated and produced in a different invoicing program;<br>
     * &lt;xs:enumeration value="I"/&gt;
     */
    const I = "I";

    /**
     * “M” – Recovered or manually issued document.<br>
     * &lt;xs:enumeration value="M"/&gt;
     */
    const M = "M";

    /**
     * To fill in with:<br>
     * “P” – Document created in the invoicing program;<br>
     * “I” – Document integrated and produced in a different invoicing program;<br>
     * “M” – Recovered or manually issued document.<br>
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the value as string
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}