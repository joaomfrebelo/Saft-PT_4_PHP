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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

/**
 * SourcePayment<br>
 * “P” – Receipt created in the application;<br>
 * “I” – Receipt integrated and produced in a different application;<br>
 * “M” – Recovered or manually issued receipt.<br>
 * <pre>
 * &lt;xs:simpleType name="SAFTPTSourcePayment"&gt;
 *  &lt;xs:restriction base="xs:string"&gt;
 *      &lt;xs:enumeration value="P"/&gt;
 *      &lt;xs:enumeration value="I"/&gt;
 *      &lt;xs:enumeration value="M"/&gt;
 *  &lt;/xs:restriction&gt;
 * </pre>
 * @author João Rebelo
 */
class SourcePayment extends \Rebelo\SaftPt\AuditFile\SourceDocuments\SourceBilling
{

    /**
     * To be filled in with:<br>
     * “P” – Receipt created in the application;<br>
     * “I” – Receipt integrated and produced in a different application;<br>
     * “M” – Recovered or manually issued receipt.<br>
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}
