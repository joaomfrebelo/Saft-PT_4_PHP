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
 * PaymentType<br>
 * To fill in with:<br>
 * “RC” – Receipt issued according to the Cash VAT regime
 * (including advance payments in this regime);<br>
 * “RG” – Other issued receipts.<br>
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType RC()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType RG()
 * @author João Rebelo
 * @since 1.0.0
 */
class PaymentType extends \Rebelo\Enum\AEnum
{
    /**
     * RC para Recibo emitido no ambito do regime de IVA de Caixa
     * (incluindo os relativos a adiantamentos desse regime)
     * @since 1.0.0
     */
    const RC = "RC";

    /**
     * RG para Outros recibos
     * @since 1.0.0
     */
    const RG = "RG";

    /**
     * PaymentType<br>
     * To fill in with:<br>
     * “RC” – Receipt issued according to the Cash VAT regime
     * (including advance payments in this regime);<br>
     * “RG” – Other issued receipts.<br>
     * <pre>
     * &lt;xs:simpleType name="SAFTPTPaymentType"&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="RC"/&gt;
     *       &lt;xs:enumeration value="RG"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get enum value
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}
