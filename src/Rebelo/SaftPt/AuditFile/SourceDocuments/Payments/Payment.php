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

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType;
use Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus;
use Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax;
use Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\TransactionID;

/**
 * Payment
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Payment extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * <xs:element name="Payment" minOccurs="0" maxOccurs="unbounded">
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENT = "Payment";

    /**
     * <xs:element ref="ATCUD"/>
     * Node name
     * @since 1.0.0
     */
    const N_ATCUD = "ATCUD";

    /**
     * <xs:element ref="PaymentRefNo"/>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTREFNO = "PaymentRefNo";

    /**
     * <xs:element ref="Period" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_PERIOD = "Period";

    /**
     * <xs:element ref="TransactionID" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONID = "TransactionID";

    /**
     * <xs:element ref="TransactionDate"/>
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONDATE = "TransactionDate";

    /**
     * <xs:element name="PaymentType" type="SAFTPTPaymentType"/>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTTYPE = "PaymentType";

    /**
     * <xs:element ref="Description" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * <xs:element ref="SystemID" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_SYSTEMID = "SystemID";

    /**
     * <xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * <br>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTMETHOD = "PaymentMethod";

    /**
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEID = "SourceID";

    /**
     * <xs:element ref="SystemEntryDate"/>
     * Node name
     * @since 1.0.0
     */
    const N_SYSTEMENTRYDATE = "SystemEntryDate";

    /**
     * <xs:element ref="CustomerID"/>
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMERID = "CustomerID";

    /**
     *
     * PaymentRefNo<br>
     * <pre>
     * <!-- Codigo unico do documento de venda -->
     * &lt;xs:element name="PaymentRefNo"&gt;
     *  &lt;xs:simpleType&gt;
     *      &lt;xs:restriction base="xs:string"&gt;
     *          &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *          &lt;xs:minLength value="1"/&gt;
     *          &lt;xs:maxLength value="60"/&gt;
     *      &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @var string $paymentRefNo
     * @since 1.0.0
     */
    private string $paymentRefNo;

    /**
     * <xs:element ref="ATCUD"/><br>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * @var string $aTCUD
     * @since 1.0.0
     */
    private string $atcud;

    /**
     * Period
     * <!-- Periodo contabilistico do documento -->
     * <pre>
     * &lt;xs:element name="Period"&gt;
     *    &lt;xs:simpleType&gt;
     *        &lt;xs:restriction base="xs:integer"&gt;
     *            &lt;xs:minInclusive value="1"/&gt;
     *            &lt;xs:maxInclusive value="12"/&gt;
     *        &lt;/xs:restriction&gt;
     *    &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @var int|null $period
     * @since 1.0.0
     */
    private ?int $period = null;

    /**
     * TransactionID
     * <pre>
     * &lt;xs:element name="TransactionID" type="SAFPTTransactionID"/&gt;
     * &lt;xs:simpleType name="SAFPTTransactionID"&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:pattern value="[1-9][0-9]{3}-[01][0-9]-[0-3][0-9] [^ ]{1,30} [^ ]{1,20}"/&gt;
     *       &lt;xs:minLength value="1"/&gt;
     *       &lt;xs:maxLength value="70"/&gt;
     *   &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @var \Rebelo\SaftPt\AuditFile\TransactionID|null $transactionID
     * @since 1.0.0
     */
    private ?TransactionID $transactionID = null;

    /**
     * <xs:element ref="TransactionDate"/>
     * @var \Rebelo\Date\Date $transactionDate
     * @since 1.0.0
     */
    private RDate $transactionDate;

    /**
     * PaymentType<br>
     * <pre>
     * &lt;xs:element name="PaymentType" type="SAFTPTPaymentType"/&gt;
     * &lt;xs:simpleType name="SAFTPTPaymentType"&gt;
     *   &lt;xs:annotation&gt;
     *       &lt;xs:documentation&gt; Restricao: RC para Recibo emitido no ambito do regime de IVA de Caixa
     *           (incluindo os relativos a adiantamentos desse regime), RG para Outros recibos
     *           emitidos &lt;/xs:documentation&gt;
     *   &lt;/xs:annotation&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:enumeration value="RC"/&gt;
     *       &lt;xs:enumeration value="RG"/&gt;
     *   &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     * @var  \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType $paymentType
     * @since 1.0.0
     */
    private PaymentType $paymentType;

    /**
     * <xs:element ref="Description" minOccurs="0"/><br>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @var string|null $description
     * @since 1.0.0
     */
    private ?string $description = null;

    /**
     * <xs:element ref="SystemID" minOccurs="0"/><br>
     * <xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @var string|null $systemID
     * @since 1.0.0
     */
    private ?string $systemID = null;

    /**
     * <xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus $documentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * <xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[] $paymentMethod
     * @since 1.0.0
     */
    private array $paymentMethod = array();

    /**
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @var string $sourceID
     * @since 1.0.0
     */
    private string $sourceID;

    /**
     * System entry date<br>
     * Format \Rebelo\Date\Date::DATE_T_TIME
     * <xs:element ref="SystemEntryDate"/>
     * @var \Rebelo\Date\Date $systemEntryDate
     * @since 1.0.0
     */
    private RDate $systemEntryDate;

    /**
     * <xs:element ref="CustomerID"/><br>
     * <xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @var string $customerID
     * @since 1.0.0
     */
    private string $customerID;

    /**
     * <xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line[] $line
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * @var \Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\DocumentTotalsAType $documentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax[] $withholdingTax
     * @since 1.0.0
     */
    private array $withholdingTax = array();

    /**
     * <pre>
     * &lt;xs:element name="Payment" minOccurs="0" maxOccurs="unbounded"&gt;
     * &lt;xs:complexType&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element ref="PaymentRefNo"/&gt;
     *        &lt;xs:element ref="ATCUD"/&gt;
     *        &lt;xs:element ref="Period" minOccurs="0"/&gt;
     *        &lt;xs:element ref="TransactionID" minOccurs="0"/&gt;
     *        &lt;xs:element ref="TransactionDate"/&gt;
     *        &lt;xs:element name="PaymentType" type="SAFTPTPaymentType"/&gt;
     *        &lt;xs:element ref="Description" minOccurs="0"/&gt;
     *        &lt;xs:element ref="SystemID" minOccurs="0"/&gt;
     *        &lt;xs:element name="DocumentStatus"&gt;
     *            &lt;xs:complexType&gt;
     *                &lt;xs:sequence&gt;
     *                    &lt;xs:element ref="PaymentStatus"/&gt;
     *                    &lt;xs:element ref="PaymentStatusDate"/&gt;
     *                    &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *                    &lt;xs:element ref="SourceID"/&gt;
     *                    &lt;xs:element name="SourcePayment"
     *                                type="SAFTPTSourcePayment"/&gt;
     *                &lt;/xs:sequence&gt;
     *            &lt;/xs:complexType&gt;
     *        &lt;/xs:element&gt;
     *        &lt;xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     *        &lt;xs:element ref="SourceID"/&gt;
     *        &lt;xs:element ref="SystemEntryDate"/&gt;
     *        &lt;xs:element ref="CustomerID"/&gt;
     *        &lt;xs:element name="Line" maxOccurs="unbounded"&gt;
     *            &lt;xs:complexType&gt;
     *                &lt;xs:sequence&gt;
     *                    &lt;xs:element ref="LineNumber"/&gt;
     *                    &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded"&gt;
     *                        &lt;xs:complexType&gt;
     *                            &lt;xs:sequence&gt;
     *                                &lt;xs:element ref="OriginatingON"/&gt;
     *                                &lt;xs:element ref="InvoiceDate"/&gt;
     *                                &lt;xs:element ref="Description" minOccurs="0"/&gt;
     *                            &lt;/xs:sequence&gt;
     *                        &lt;/xs:complexType&gt;
     *                    &lt;/xs:element&gt;
     *                    &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     *                    &lt;xs:choice&gt;
     *                        &lt;xs:element ref="DebitAmount"/&gt;
     *                        &lt;xs:element ref="CreditAmount"/&gt;
     *                    &lt;/xs:choice&gt;
     *                    &lt;xs:element name="Tax" type="PaymentTax" minOccurs="0"/&gt;
     *                    &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;
     *                    &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     *                &lt;/xs:sequence&gt;
     *                &lt;xs:assert
     *                    test="
     *                        if (not(ns:Tax/ns:TaxAmount) or ((ns:Tax/ns:TaxAmount !== 0 and not(ns:TaxExemptionReason)) or (ns:Tax/ns:TaxAmount eq 0 and ns:TaxExemptionReason))) then
     *                            true()
     *                        else
     *                            false()"/&gt;
     *                &lt;xs:assert
     *                    test="
     *                        if (not(ns:Tax/ns:TaxPercentage) or ((ns:Tax/ns:TaxPercentage !== 0 and not(ns:TaxExemptionReason)) or (ns:Tax/ns:TaxPercentage eq 0 and ns:TaxExemptionReason))) then
     *                            true()
     *                        else
     *                            false()"/&gt;
     *                &lt;xs:assert
     *                    test="
     *                        if ((ns:TaxExemptionReason and not(ns:TaxExemptionCode)) or (ns:TaxExemptionCode and not(ns:TaxExemptionReason))) then
     *                            false()
     *                        else
     *                            true()"/&gt;
     *                &lt;xs:assert
     *                    test="
     *                        if (../ns:PaymentType eq 'RC' and not(ns:Tax)) then
     *                            false()
     *                        else
     *                            true()"
     *                /&gt;
     *            &lt;/xs:complexType&gt;
     *        &lt;/xs:element&gt;
     *        &lt;xs:element name="DocumentTotals"&gt;
     *            &lt;xs:complexType&gt;
     *                &lt;xs:sequence&gt;
     *                    &lt;xs:element ref="TaxPayable"/&gt;
     *                    &lt;xs:element ref="NetTotal"/&gt;
     *                    &lt;xs:element ref="GrossTotal"/&gt;
     *                    &lt;!-- O conteudo desta estrutura Settlement representa o somatorio dos descontos reflectidos no elemento SettlementAmount das linhas do recibo. Trata-se de um raciocinio diverso da tabela 4.1 SalesInvoices --&gt;
     *                    &lt;xs:element name="Settlement" minOccurs="0"&gt;
     *                        &lt;xs:complexType&gt;
     *                            &lt;xs:sequence&gt;
     *                                &lt;xs:element ref="SettlementAmount"/&gt;
     *                            &lt;/xs:sequence&gt;
     *                        &lt;/xs:complexType&gt;
     *                    &lt;/xs:element&gt;
     *                    &lt;xs:element name="Currency" type="Currency"
     *                                minOccurs="0"/&gt;
     *                &lt;/xs:sequence&gt;
     *            &lt;/xs:complexType&gt;
     *        &lt;/xs:element&gt;
     *        &lt;xs:element name="WithholdingTax" type="WithholdingTax"
     *                    minOccurs="0" maxOccurs="unbounded"/&gt;
     *    &lt;/xs:sequence&gt;
     *    &lt;xs:assert
     *        test="
     *            if ((ns:Line/ns:Tax/ns:TaxType and ns:PaymentType eq 'RC') or (ns:PaymentType eq 'RG')) then
     *                true()
     *            else
     *                false()"
     *    /&gt;
     * &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as paymentRefNo <br>
     * <xs:element ref="PaymentRefNo"/><br>
     * <pre>
     * <!-- Codigo unico do documento de venda -->
     * &lt;xs:element name="PaymentRefNo"&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *             &lt;xs:minLength value="1"/&gt;
     *             &lt;xs:maxLength value="60"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getPaymentRefNo(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->paymentRefNo));
        return $this->paymentRefNo;
    }

    /**
     * Sets a new paymentRefNo<br>
     * <xs:element ref="PaymentRefNo"/><br>
     * <pre>
     * <!-- Codigo unico do documento de venda -->
     * &lt;xs:element name="PaymentRefNo"&gt;
     *     &lt;xs:simpleType&gt;
     *         &lt;xs:restriction base="xs:string"&gt;
     *             &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *             &lt;xs:minLength value="1"/&gt;
     *             &lt;xs:maxLength value="60"/&gt;
     *         &lt;/xs:restriction&gt;
     *     &lt;/xs:simpleType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param string $paymentRefNo
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setPaymentRefNo(string $paymentRefNo): void
    {
        if (\strlen($paymentRefNo) < 1 || \strlen($paymentRefNo) > 60) {
            $msg = "PaymentRefNo length must be between 1 and 60";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if (\preg_match("/[^ ]+ [^\/^ ]+\/[0-9]+/", $paymentRefNo) !== 1) {
            $msg = "PaymentRefNo length must respect the regexp '[^ ]+ [^/^ ]+/[0-9]+'";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->paymentRefNo = $paymentRefNo;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->paymentRefNo));
    }

    /**
     * Gets as ATCUD<br>
     * <xs:element ref="ATCUD"/><br>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getATCUD(): string
    {

        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->atcud));
        return $this->atcud;
    }

    /**
     * Sets a new ATCUD<br>
     * <xs:element ref="ATCUD"/><br>
     * <xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/>
     * @param string $atcud
     * @return void
     * @since 1.0.0
     */
    public function setATCUD(string $atcud): void
    {
        $this->atcud = $this->valTextMandMaxCar($atcud, 100, __METHOD__, false);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->atcud));
    }

    /**
     * Gets as period<br>
     * <pre>
     * &lt;xs:element ref="Period" minOccurs="0"/&gt;
     * &lt;!-- Periodo contabilistico do documento --&gt;
     *   &lt;xs:element name="Period"&gt;
     *       &lt;xs:simpleType&gt;
     *           &lt;xs:restriction base="xs:integer"&gt;
     *               &lt;xs:minInclusive value="1"/&gt;
     *               &lt;xs:maxInclusive value="12"/&gt;
     *           &lt;/xs:restriction&gt;
     *       &lt;/xs:simpleType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     * @return int|null
     * @since 1.0.0
     */
    public function getPeriod(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->period === null ? "null" : \strval($this->period)));
        return $this->period;
    }

    /**
     * Sets a new period<br>
     * <pre>
     * &lt;xs:element ref="Period" minOccurs="0"/&gt;
     * &lt;!-- Periodo contabilistico do documento --&gt;
     *   &lt;xs:element name="Period"&gt;
     *       &lt;xs:simpleType&gt;
     *           &lt;xs:restriction base="xs:integer"&gt;
     *               &lt;xs:minInclusive value="1"/&gt;
     *               &lt;xs:maxInclusive value="12"/&gt;
     *           &lt;/xs:restriction&gt;
     *       &lt;/xs:simpleType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     *
     * @param int|null $period
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setPeriod(?int $period): void
    {
        if ($period !== null) {
            if ($period < 1 || $period > 12) {
                throw new AuditFileException("Period must be between 1 and 12");
            }
        }
        $this->period = $period;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->period === null ? "null" : \strval($this->period)));
    }

    /**
     * Gets as transactionID
     * <pre>
     * &lt;xs:element ref="TransactionID" minOccurs="0"/&gt;
     * &lt;xs:simpleType name="SAFPTTransactionID"&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:pattern value="[1-9][0-9]{3}-[01][0-9]-[0-3][0-9] [^ ]{1,30} [^ ]{1,20}"/&gt;
     *       &lt;xs:minLength value="1"/&gt;
     *       &lt;xs:maxLength value="70"/&gt;
     *   &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    public function getTransactionID(): ?TransactionID
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"));
        return $this->transactionID;
    }

    /**
     * Sets a new transactionID     *
     * <pre>
     * &lt;xs:element ref="TransactionID" minOccurs="0"/&gt;
     * &lt;xs:simpleType name="SAFPTTransactionID"&gt;
     *   &lt;xs:restriction base="xs:string"&gt;
     *       &lt;xs:pattern value="[1-9][0-9]{3}-[01][0-9]-[0-3][0-9] [^ ]{1,30} [^ ]{1,20}"/&gt;
     *       &lt;xs:minLength value="1"/&gt;
     *       &lt;xs:maxLength value="70"/&gt;
     *   &lt;/xs:restriction&gt;
     *  &lt;/xs:simpleType&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\TransactionID|null $transactionID
     * @return void
     * @since 1.0.0
     */
    public function setTransactionID(?TransactionID $transactionID): void
    {
        $this->transactionID = $transactionID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"));
    }

    /**
     * Gets as transactionDate<br>
     * <xs:element ref="TransactionDate"/>
     * <xs:element name="TransactionDate" type="SAFdateType"/>
     *
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getTransactionDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->transactionDate->format(RDate::SQL_DATE)));
        return $this->transactionDate;
    }

    /**
     * Sets a new transactionDate<br>
     * <xs:element ref="TransactionDate"/>
     * <xs:element name="TransactionDate" type="SAFdateType"/>
     *
     * @param \Rebelo\Date\Date $transactionDate
     * @param bool $setPeriod Default true, If true will set the period to the month of TransactionDate
     * @return void
     * @since 1.0.0
     */
    public function setTransactionDate(RDate $transactionDate,
                                       bool $setPeriod = true): void
    {
        $this->transactionDate = $transactionDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->transactionDate->format(RDate::SQL_DATE)));
        if ($setPeriod) {
            $this->setPeriod((int) $transactionDate->format(RDate::MONTH_SHORT));
        }
    }

    /**
     * Gets as paymentType<br>
     * <xs:element name="PaymentType" type="SAFTPTPaymentType"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType
     * @since 1.0.0
     */
    public function getPaymentType(): PaymentType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->paymentType->get()));
        return $this->paymentType;
    }

    /**
     * Sets a new paymentType<br>
     * <xs:element name="PaymentType" type="SAFTPTPaymentType"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType $paymentType
     * @return void
     * @since 1.0.0
     */
    public function setPaymentType(PaymentType $paymentType): void
    {
        $this->paymentType = $paymentType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->paymentType->get()));
    }

    /**
     * Gets as description<br>
     * <xs:element ref="Description" minOccurs="0"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->description === null ?
                        "null" : $this->description));
        return $this->description;
    }

    /**
     * Sets a new description<br>
     * <xs:element ref="Description" minOccurs="0"/><br>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @param string|null $description
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description === null ? null :
            $this->valTextMandMaxCar($description, 200, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->description === null ? "null" : $this->description));
    }

    /**
     * Gets as systemID<br>
     * <xs:element ref="SystemID" minOccurs="0"/><br>
     * <xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getSystemID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->systemID === null ?
                        "null" : $this->systemID));
        return $this->systemID;
    }

    /**
     * Sets a new systemID<br>
     * <xs:element ref="SystemID" minOccurs="0"/><br>
     * <xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @param string|null $systemID
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSystemID(?string $systemID): void
    {
        $this->systemID = $systemID === null ? null :
            $this->valTextMandMaxCar($systemID, 60, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->systemID === null ? "null" : $this->systemID));
    }

    /**
     * Gets as documentStatus<br>
     * <xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "DocumentStatus"));
        return $this->documentStatus;
    }

    /**
     * Sets a new documentStatus<br>
     * <xs:element name="DocumentStatus">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus $documentStatus
     * @return void
     * @since 1.0.0
     */
    public function setDocumentStatus(DocumentStatus $documentStatus): void
    {
        $this->documentStatus = $documentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", "DocumentStatus"));
    }

    /**
     * Adds to paymentMethod stack
     * <xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * @return int
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod $paymentMethod
     * @since 1.0.0
     */
    public function addToPaymentMethod(PaymentMethod $paymentMethod): int
    {
        if (\count($this->paymentMethod) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->paymentMethod);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->paymentMethod[$index] = $paymentMethod;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " PaymentMethos add to index ".\strval($index));
        return $index;
    }

    /**
     * isset paymentMethod
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetPaymentMethod(int $index): bool
    {
        return isset($this->paymentMethod[$index]);
    }

    /**
     * unset paymentMethod
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetPaymentMethod(int $index): void
    {
        unset($this->paymentMethod[$index]);
    }

    /**
     * Gets paymentMethod stack<br>
     * <xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[]
     * @since 1.0.0
     */
    public function getPaymentMethod(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "PaymentMethod stack"));
        return $this->paymentMethod;
    }

    /**
     * Gets as sourceID<br>
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     * @return string
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->sourceID));
        return $this->sourceID;
    }

    /**
     * Sets a new sourceID<br>
     * <xs:element ref="SourceID"/><br>
     * <xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/><br>
     * @param string $sourceID
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): void
    {
        $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->sourceID));
    }

    /**
     * Gets as systemEntryDate<br>
     * <xs:element ref="SystemEntryDate"/><br>
     * <xs:element name="SystemEntryDate" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getSystemEntryDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)));
        return $this->systemEntryDate;
    }

    /**
     * Sets a new systemEntryDate<br>
     * <xs:element ref="SystemEntryDate"/><br>
     * <xs:element name="SystemEntryDate" type="SAFdateTimeType"/>
     *
     * @param \Rebelo\Date\Date $systemEntryDate
     * @return void
     * @since 1.0.0
     */
    public function setSystemEntryDate(RDate $systemEntryDate): void
    {
        $this->systemEntryDate = $systemEntryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)));
    }

    /**
     * Gets as customerID<br>
     * <xs:element ref="CustomerID"/><br>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @return string
     * @since 1.0.0
     */
    public function getCustomerID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->customerID));
        return $this->customerID;
    }

    /**
     * Sets a new customerID<br>
     * <xs:element ref="CustomerID"/><br>
     * <xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/>
     * @param string $customerID
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): void
    {
        $this->customerID = $this->valTextMandMaxCar($customerID, 30,
            __METHOD__, true);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->customerID));
    }

    /**
     * Adds as line<br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @return int
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line $line
     * @since 1.0.0
     */
    public function addToLine(Line $line): int
    {
        if (\count($this->line) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->line);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->line[$index] = $line;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " Line add to index ".\strval($index));
        return $index;
    }

    /**
     * isset line <br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetLine(int $index): bool
    {
        return isset($this->line[$index]);
    }

    /**
     * unset line
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetLine(int $index): void
    {
        unset($this->line[$index]);
    }

    /**
     * Gets as line<br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line[]
     * @since 1.0.0
     */
    public function getLine(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "Line stack"));
        return $this->line;
    }

    /**
     * Gets as documentTotals <br>
     * <xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "Document Totals"));
        return $this->documentTotals;
    }

    /**
     * Sets a new documentTotals <br>
     * <xs:element name="DocumentTotals">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals $documentTotals
     * @return void
     * @since 1.0.0
     */
    public function setDocumentTotals(DocumentTotals $documentTotals): void
    {
        $this->documentTotals = $documentTotals;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", "Document Totals"));
    }

    /**
     * Adds as withholdingTax <br>
     * <xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/>
     * @return int
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax $withholdingTax
     * @since 1.0.0
     */
    public function addToWithholdingTax(WithholdingTax $withholdingTax): int
    {
        if (\count($this->withholdingTax) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->withholdingTax);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->withholdingTax[$index] = $withholdingTax;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " WithholdingTax add to index ".\strval($index));
        return $index;
    }

    /**
     * isset withholdingTax
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetWithholdingTax(int $index): bool
    {
        return isset($this->withholdingTax[$index]);
    }

    /**
     * unset withholdingTax
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetWithholdingTax(int $index): void
    {
        unset($this->withholdingTax[$index]);
    }

    /**
     * Gets as withholdingTax<br>
     * <xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax[]
     * @since 1.0.0
     */
    public function getWithholdingTax(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "WithholdingTax"));
        return $this->withholdingTax;
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== Payments::N_PAYMENTS) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                Payments::N_PAYMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $payNode = $node->addChild(static::N_PAYMENT);

        $payNode->addChild(static::N_PAYMENTREFNO, $this->getPaymentRefNo());
        $payNode->addChild(static::N_ATCUD, $this->getATCUD());

        if ($this->getPeriod() !== null) {
            $payNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }

        if ($this->getTransactionID() !== null) {
            $this->getTransactionID()->createXmlNode($payNode);
        }

        $payNode->addChild(
            static::N_TRANSACTIONDATE,
            $this->getTransactionDate()->format(RDate::SQL_DATE)
        );

        $payNode->addChild(
            static::N_PAYMENTTYPE, $this->getPaymentType()->get()
        );

        if ($this->getDescription() !== null) {
            $payNode->addChild(
                static::N_DESCRIPTION, $this->getDescription()
            );
        }

        if ($this->getSystemID() !== null) {
            $payNode->addChild(
                static::N_SYSTEMID, $this->getSystemID()
            );
        }

        $this->getDocumentStatus()->createXmlNode($payNode);

        foreach ($this->getPaymentMethod() as $payMeth) {
            /* @var $payMeth PaymentMethod */
            $payMeth->createXmlNode($payNode);
        }

        if ($this->getSourceID() !== null) {
            $payNode->addChild(
                static::N_SOURCEID, $this->getSourceID()
            );
        }

        $payNode->addChild(
            static::N_SYSTEMENTRYDATE,
            $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
        );

        $payNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());

        foreach ($this->getLine() as $line) {
            /* @var $line Line */
            $line->createXmlNode($payNode);
        }

        $this->getDocumentTotals()->createXmlNode($payNode);

        foreach ($this->getWithholdingTax() as $tax) {
            /* @var $tax WithholdingTax */
            $tax->createXmlNode($payNode);
        }

        return $payNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_PAYMENT) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                static::N_PAYMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setPaymentRefNo((string) $node->{static::N_PAYMENTREFNO});
        $this->setATCUD((string) $node->{static::N_ATCUD});

        if ($node->{static::N_PERIOD}->count() > 0) {
            $this->setPeriod((int) $node->{static::N_PERIOD});
        }

        if ($node->{static::N_TRANSACTIONID}->count() > 0) {
            $trans = new TransactionID();
            $trans->parseXmlNode($node->{static::N_TRANSACTIONID});
            $this->setTransactionID($trans);
        }

        $this->setTransactionDate(
            RDate::parse(RDate::SQL_DATE,
                (string) $node->{static::N_TRANSACTIONDATE}), false
        );

        $this->setPaymentType(new PaymentType((string) $node->{static::N_PAYMENTTYPE}));

        if ($node->{static::N_DESCRIPTION}->count() > 0) {
            $this->setDescription((string) $node->{static::N_DESCRIPTION});
        }

        if ($node->{static::N_SYSTEMID}->count() > 0) {
            $this->setSystemID((string) $node->{static::N_SYSTEMID});
        }

        $docStatus = new DocumentStatus();
        $docStatus->parseXmlNode($node->{DocumentStatus::N_DOCUMENTSTATUS});
        $this->setDocumentStatus($docStatus);

        for ($n = 0; $n < $node->{static::N_PAYMENTMETHOD}->count(); $n++) {
            $payMeth = new PaymentMethod();
            $payMeth->parseXmlNode($node->{static::N_PAYMENTMETHOD}[$n]);
            $this->addToPaymentMethod($payMeth);
        }

        $this->setSourceID((string) $node->{static::N_SOURCEID});
        $this->setSystemEntryDate(
            RDate::parse(RDate::DATE_T_TIME,
                (string) $node->{static::N_SYSTEMENTRYDATE})
        );
        $this->setCustomerID((string) $node->{static::N_CUSTOMERID});

        for ($n = 0; $n < $node->{LINE::N_LINE}->count(); $n++) {
            $line = new Line();
            $line->parseXmlNode($node->{LINE::N_LINE}[$n]);
            $this->addToLine($line);
        }

        $docTotals = new DocumentTotals();
        $docTotals->parseXmlNode($node->{DocumentTotals::N_DOCUMENTTOTALS});
        $this->setDocumentTotals($docTotals);

        for ($n = 0; $n < $node->{WithholdingTax::N_WITHHOLDINGTAX}->count(); $n++) {
            $tax = new WithholdingTax();
            $tax->parseXmlNode($node->{WithholdingTax::N_WITHHOLDINGTAX}[$n]);
            $this->addToWithholdingTax($tax);
        }
    }
}