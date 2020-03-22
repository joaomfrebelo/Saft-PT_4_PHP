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

namespace Rebelo\Test\SaftPt\AuditFile\MasterFile;

/**
 * TaxCode
 * <pre>
 * <xs:simpleType name="SAFTPTMovementTaxCode">
 *      <xs:restriction base="xs:string">
 *          <xs:pattern value="RED|INT|NOR|ISE|OUT|NS"/>
 *          <xs:minLength value="1"/>
 *          <xs:maxLength value="3"/>
 *      </xs:restriction>
 *  </xs:simpleType>
 * </pre>
 * @author João Rebelo
 */
class TaxCode
    extends \Rebelo\Enum\AEnum
{

    /**
     * Iva taxa Reduzida
     * @since 1.0.0
     */
    const RED = "RED";

    /**
     * Iva taxa intermedio
     * @since 1.0.0
     */
    const INT = "INT";

    /**
     * Iva taxa normal
     * @since 1.0.0
     */
    const NOR = "NOR";

    /**
     * Iva taxa isenta
     * @since 1.0.0
     */
    const ISE = "ISE";

    /**
     * Outros
     * @since 1.0.0
     */
    const OUT = "OUT";

    /**
     * 
     * @since 1.0.0
     */
    const NS  = "NS";

    /**
     *
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct($value);
    }

}
