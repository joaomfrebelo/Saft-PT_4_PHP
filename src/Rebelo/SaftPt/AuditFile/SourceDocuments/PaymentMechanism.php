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

/**
 * PaymentMechanism<br>
 * The field shall be filled in with:<br>
 * “CC” - Credit card;<br>
 * “CD” - Debit card;<br>
 * “CH” - Bank cheque;<br>
 * “CI” – International Letter of Credit;<br>
 * “CO” - Gift cheque or gift card<br>
 * “CS” - Balance compensation in current account;<br>
 * “DE” - Electronic Money, for example, on fidelity or points cards;<br>
 * “LC” - Commercial Bill;<br>
 * “MB” - Payment references for ATM;<br>
 * “NU” – Cash;<br>
 * “OU” – Other means not mentioned;<br>
 * “PR” – Exchange of goods;<br>
 * “TB” – Banking transfer or authorized direct debit;<br>
 * “TR” - Non-wage compensation titles regardless of their support
 * [paper or digital format], for instance, meal or education vouchers, etc.<br>
 * &lt;xs:element name="PaymentMechanism"&gt;
 *    &lt;xs:simpleType&gt;
 *        &lt;xs:restriction base="xs:string"&gt;
 *            &lt;xs:enumeration value="CC"/&gt;
 *            &lt;xs:enumeration value="CD"/&gt;
 *            &lt;xs:enumeration value="CH"/&gt;
 *            &lt;xs:enumeration value="CI"/&gt;
 *            &lt;xs:enumeration value="CO"/&gt;
 *            &lt;xs:enumeration value="CS"/&gt;
 *            &lt;xs:enumeration value="DE"/&gt;
 *            &lt;xs:enumeration value="LC"/&gt;
 *            &lt;xs:enumeration value="MB"/&gt;
 *            &lt;xs:enumeration value="NU"/&gt;
 *            &lt;xs:enumeration value="OU"/&gt;
 *            &lt;xs:enumeration value="PR"/&gt;
 *            &lt;xs:enumeration value="TB"/&gt;
 *            &lt;xs:enumeration value="TR"/&gt;
 *        &lt;/xs:restriction&gt;
 *    &lt;/xs:simpleType&gt;
 * &lt;/xs:element&gt;
 *
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CD()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CH()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CI()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CO()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism CS()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism DE()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism LC()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism MB()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism NU()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism OU()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism PR()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism TB()
 * @method \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMechanism TR()
 *
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class PaymentMechanism extends \Rebelo\Enum\AEnum
{
    /**
     * “CC” - Credit card;<br>
     * &lt;xs:enumeration value="CC"/&gt;
     * @since 1.0.0
     */
    const CC = "CC";

    /**
     * “CD” - Debit card;<br>
     * &lt;xs:enumeration value="CD"/&gt;
     *
     * @since 1.0.0
     */
    const CD = "CD";

    /**
     * “CH” - Bank cheque<br>
     * &lt;xs:enumeration value="CH"/&gt;
     * @since 1.0.0
     */
    const CH = "CH";

    /**
     * “CI” – International Letter of Credit<br>
     * &lt;xs:enumeration value="CI"/&gt;
     * @since 1.0.0
     */
    const CI = "CI";

    /**
     * “CO” - Gift cheque or gift card<br>
     * &lt;xs:enumeration value="CO"/&gt;
     * @since 1.0.0
     */
    const CO = "CO";

    /**
     * “CS” - Balance compensation in current account<br>
     * &lt;xs:enumeration value="CS"/&gt;
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * “DE” - Electronic Money, for example, on fidelity or points cards<br>
     * &lt;xs:enumeration value="DE"/&gt;
     * @since 1.0.0
     */
    const DE = "DE";

    /**
     * “LC” - Commercial Bill;<<br>
     * &lt;xs:enumeration value="LC"/&gt;
     * @since 1.0.0
     */
    const LC = "LC";

    /**
     * “MB” - Payment references for ATM;<br>
     * &lt;xs:enumeration value="MB"/&gt;
     * @since 1.0.0
     */
    const MB = "MB";

    /**
     * NU “NU” – Cash;<br>
     * &lt;xs:enumeration value="NU"/&gt;
     * @since 1.0.0
     */
    const NU = "NU";

    /**
     * “OU” – Other means not mentioned<br>
     * &lt;xs:enumeration value="OU"/&gt;
     * @since 1.0.0
     */
    const OU = "OU";

    /**
     * “PR” – Exchange of goods<br>
     * &lt;xs:enumeration value="PR"/&gt;
     * @since 1.0.0
     */
    const PR = "PR";

    /**
     * “TB” – Banking transfer or authorized direct debit<br>
     * &lt;xs:enumeration value="TB"/&gt;
     * @since 1.0.0
     */
    const TB = "TB";

    /**
     * “TR” - Non-wage compensation titles regardless of their support
     * [paper or digital format], for instance, meal or education vouchers, etc.<br>
     * &lt;xs:enumeration value="TR"/&gt;
     * @since 1.0.0
     */
    const TR = "TR";

    /**
     * PaymentMechanism<br>
     * The field shall be filled in with:<br>
     * “CC” - Credit card;<br>
     * “CD” - Debit card;<br>
     * “CH” - Bank cheque;<br>
     * “CI” – International Letter of Credit;<br>
     * “CO” - Gift cheque or gift card<br>
     * “CS” - Balance compensation in current account;<br>
     * “DE” - Electronic Money, for example, on fidelity or points cards;<br>
     * “LC” - Commercial Bill;<br>
     * “MB” - Payment references for ATM;<br>
     * “NU” – Cash;<br>
     * “OU” – Other means not mentioned;<br>
     * “PR” – Exchange of goods;<br>
     * “TB” – Banking transfer or authorized direct debit;<br>
     * “TR” - Non-wage compensation titles regardless of their support
     * [paper or digital format], for instance, meal or education vouchers, etc.<br>
     * &lt;xs:element name="PaymentMechanism"&gt;
     *    &lt;xs:simpleType&gt;
     *        &lt;xs:restriction base="xs:string"&gt;
     *            &lt;xs:enumeration value="CC"/&gt;
     *            &lt;xs:enumeration value="CD"/&gt;
     *            &lt;xs:enumeration value="CH"/&gt;
     *            &lt;xs:enumeration value="CI"/&gt;
     *            &lt;xs:enumeration value="CO"/&gt;
     *            &lt;xs:enumeration value="CS"/&gt;
     *            &lt;xs:enumeration value="DE"/&gt;
     *            &lt;xs:enumeration value="LC"/&gt;
     *            &lt;xs:enumeration value="MB"/&gt;
     *            &lt;xs:enumeration value="NU"/&gt;
     *            &lt;xs:enumeration value="OU"/&gt;
     *            &lt;xs:enumeration value="PR"/&gt;
     *            &lt;xs:enumeration value="TB"/&gt;
     *            &lt;xs:enumeration value="TR"/&gt;
     *        &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     *
     * @param string $value
     * @since 1.0.0
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