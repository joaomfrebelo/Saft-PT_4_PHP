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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TransactionID;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\Validate\DocTotalCalc;

/**
 * Payment<br>
 * Export the documents mentioned on field 4.4.4.6. - PaymentType.
 * @author João Rebelo
 * @since 1.0.0
 */
class Payment extends AAuditFile
{
    /**
     * &lt;xs:element name="Payment" minOccurs="0" maxOccurs="unbounded">
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENT = "Payment";

    /**
     * &lt;xs:element ref="ATCUD"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_ATCUD = "ATCUD";

    /**
     * &lt;xs:element ref="PaymentRefNo"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTREFNO = "PaymentRefNo";

    /**
     * &lt;xs:element ref="Period" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PERIOD = "Period";

    /**
     * &lt;xs:element ref="TransactionID" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONID = "TransactionID";

    /**
     * &lt;xs:element ref="TransactionDate"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONDATE = "TransactionDate";

    /**
     * &lt;xs:element name="PaymentType" type="SAFTPTPaymentType"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTTYPE = "PaymentType";

    /**
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * &lt;xs:element ref="SystemID" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_SYSTEMID = "SystemID";

    /**
     * &lt;xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     * <br>
     * Node name
     * @since 1.0.0
     */
    const N_PAYMENTMETHOD = "PaymentMethod";

    /**
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEID = "SourceID";

    /**
     * &lt;xs:element ref="SystemEntryDate"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_SYSTEMENTRYDATE = "SystemEntryDate";

    /**
     * &lt;xs:element ref="CustomerID"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMERID = "CustomerID";


    /**
     * The calulated values made by the validation classes
     * @var \Rebelo\SaftPt\Validate\DocTotalCalc
     * @since 1.0.0
     */
    protected ?DocTotalCalc $docTotalcal = null;

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
    protected string $paymentRefNo;

    /**
     * &lt;xs:element ref="ATCUD"/&gt;<br>
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * @var string $aTCUD
     * @since 1.0.0
     */
    protected string $atcud;

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
    protected ?int $period = null;

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
    protected ?TransactionID $transactionID = null;

    /**
     * &lt;xs:element ref="TransactionDate"/&gt;
     * @var \Rebelo\Date\Date $transactionDate
     * @since 1.0.0
     */
    protected RDate $transactionDate;

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
    protected PaymentType $paymentType;

    /**
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @var string|null $description
     * @since 1.0.0
     */
    protected ?string $description = null;

    /**
     * &lt;xs:element ref="SystemID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @var string|null $systemID
     * @since 1.0.0
     */
    protected ?string $systemID = null;

    /**
     * &lt;xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus $documentStatus
     * @since 1.0.0
     */
    protected DocumentStatus $documentStatus;

    /**
     * &lt;xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod[] $paymentMethod
     * @since 1.0.0
     */
    protected array $paymentMethod = array();

    /**
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string $sourceID
     * @since 1.0.0
     */
    protected string $sourceID;

    /**
     * System entry date<br>
     * Format \Rebelo\Date\Date::DATE_T_TIME
     * &lt;xs:element ref="SystemEntryDate"/&gt;
     * @var \Rebelo\Date\Date $systemEntryDate
     * @since 1.0.0
     */
    protected RDate $systemEntryDate;

    /**
     * &lt;xs:element ref="CustomerID"/&gt;<br>
     * &lt;xs:element name="CustomerTaxID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @var string $customerID
     * @since 1.0.0
     */
    protected string $customerID;

    /**
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line[] $line
     * @since 1.0.0
     */
    protected array $line = array();

    /**
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals $documentTotals
     * @since 1.0.0
     */
    protected DocumentTotals $documentTotals;

    /**
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax[] $withholdingTax
     * @since 1.0.0
     */
    protected array $withholdingTax = array();

    /**
     * Payment<br>
     * Export the documents mentioned on field 4.4.4.6. - PaymentType.
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
     * &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Gets as paymentRefNo <br>
     * This identification is a sequential composition of following elements:
     * the receipt type internal code created by the application, space,
     * receipt series identifier, slash (/) and sequential number of the receipt
     * within the series.
     * Records with the same identification are not allowed in this field.
     * The same document type internal code cannot be used for different types of documents.
     * &lt;xs:element ref="PaymentRefNo"/&gt;<br>
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
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentRefNo(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->paymentRefNo));
        return $this->paymentRefNo;
    }

    /**
     * Get if is set PaymentRefNo
     * @return bool
     * @since 1.0.0
     */
    public function issetPaymentRefNo(): bool
    {
        return isset($this->paymentRefNo);
    }

    /**
     * Sets a new paymentRefNo<br>
     * This identification is a sequential composition of following elements:
     * the receipt type internal code created by the application, space,
     * receipt series identifier, slash (/) and sequential number of the receipt
     * within the series.
     * Records with the same identification are not allowed in this field.
     * The same document type internal code cannot be used for different types of documents.
     * &lt;xs:element ref="PaymentRefNo"/&gt;<br>
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
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setPaymentRefNo(string $paymentRefNo): bool
    {
        try {
            if (AAuditFile::validateDocNumber($paymentRefNo) === false) {
                $msg = "PaymentRefNo length must respect the regexp"
                    ." '[^ ]+ [^/^ ]+/[0-9]+' and length must be between 1 and 60";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("PaymentRefNo_not_valid");
            $return = false;
        }
        $this->paymentRefNo = $paymentRefNo;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->paymentRefNo));
        return $return;
    }

    /**
     * Gets as ATCUD<br>
     * This field shall contain the Document Unique Code.
     * The field shall be filled in with '0' (zero) until its regulation.<br>
     * &lt;xs:element ref="ATCUD"/&gt;<br>
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getATCUD(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->atcud));
        return $this->atcud;
    }

    /**
     * Get if is set ATCUD
     * @return bool
     * @since 1.0.0
     */
    public function issetATCUD(): bool
    {
        return isset($this->atcud);
    }

    /**
     * Sets a new ATCUD<br>
     * This field shall contain the Document Unique Code.
     * The field shall be filled in with '0' (zero) until its regulation.<br>
     * &lt;xs:element ref="ATCUD"/&gt;<br>
     * &lt;xs:element name="ATCUD" type="SAFPTtextTypeMandatoryMax100Car"/&gt;
     * @param string $atcud
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setATCUD(string $atcud): bool
    {
        try {
            $this->atcud = $this->valTextMandMaxCar(
                $atcud, 100, __METHOD__, false
            );
            $return      = true;
        } catch (AuditFileException $e) {
            $this->atcud = $atcud;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ATCUD_not_valid");
            $return      = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->atcud));
        return $return;
    }

    /**
     * Gets as period<br>
     * The month of the taxation period shall be indicated
     * from “1” to “12”, counting from the start.
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
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->period === null ? "null" : \strval($this->period)
                )
            );
        return $this->period;
    }

    /**
     * Sets a new period<br>     *
     * The month of the taxation period shall be indicated
     * from “1” to “12”, counting from the start.
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
     * </pre>     *
     * @param int|null $period
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setPeriod(?int $period): bool
    {
        $return = true;
        if ($period !== null) {
            if ($period < 1 || $period > 12) {
                $return = false;
                $this->getErrorRegistor()->addOnSetValue("Period_not_valid");
            }
        }
        $this->period = $period;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->period === null ? "null" : \strval($this->period)
                )
            );
        return $return;
    }

    /**
     * Gets as transactionID<br>
     * The filling is required, if it is an integrated accounting and
     * invoicing system, even if the file type (TaxAccountingBasis) shall
     * not contain tables relating to accounting.
     * The unique key of Table 3. - GeneralLedgerEntries of the transaction
     * where this document has been recorded, according to the rule defined for
     * field 3.4.3.1. - TransactionID.
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
     * @param bool $create If true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\TransactionID|null
     * @since 1.0.0
     */
    public function getTransactionID(bool $create = true): ?TransactionID
    {
        if ($create && $this->transactionID === null) {
            $this->transactionID = new TransactionID($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->transactionID === null ? "null" : "TransactionID"
                )
            );
        return $this->transactionID;
    }

    /**
     * Sets transactionID as null
     * @return void
     * @since 1.0.0
     */
    public function setTransactionIDAsNull(): void
    {
        $this->transactionID = null;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." set to 'null'");
    }

    /**
     * Gets as transactionDate<br>
     * Receipt’s issuing date.
     * &lt;xs:element ref="TransactionDate"/&gt;
     * &lt;xs:element name="TransactionDate" type="SAFdateType"/&gt;
     *
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getTransactionDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->transactionDate->format(RDate::SQL_DATE)
                )
            );
        return $this->transactionDate;
    }

    /**
     * Get if is set TransactionDate
     * @return bool
     * @since 1.0.0
     */
    public function issetTransactionDate(): bool
    {
        return isset($this->transactionDate);
    }

    /**
     * Sets a new transactionDate<br>
     * Receipt’s issuing date.
     * &lt;xs:element ref="TransactionDate"/&gt;
     * &lt;xs:element name="TransactionDate" type="SAFdateType"/&gt;
     *
     * @param \Rebelo\Date\Date $transactionDate
     * @return void
     * @since 1.0.0
     */
    public function setTransactionDate(RDate $transactionDate): void
    {
        $this->transactionDate = $transactionDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->transactionDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Gets as paymentType<br>
     * &lt;xs:element name="PaymentType" type="SAFTPTPaymentType"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType
     * @throws \Error
     * @since 1.0.0
     */
    public function getPaymentType(): PaymentType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->paymentType->get()));
        return $this->paymentType;
    }

    /**
     * Get if is set PaymentType
     * @return bool
     * @since 1.0.0
     */
    public function issetPaymentType(): bool
    {
        return isset($this->paymentType);
    }

    /**
     * Sets PaymentType<br>
     * &lt;xs:element name="PaymentType" type="SAFTPTPaymentType"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\PaymentType $paymentType
     * @return void
     * @since 1.0.0
     */
    public function setPaymentType(PaymentType $paymentType): void
    {
        $this->paymentType = $paymentType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->paymentType->get()
                )
            );
    }

    /**
     * Gets Description<br>
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->description === null ?
                    "null" : $this->description
                )
            );
        return $this->description;
    }

    /**
     * Sets Description<br>
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @param string|null $description
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDescription(?string $description): bool
    {
        try {
            $this->description = $description === null ? null :
                $this->valTextMandMaxCar($description, 200, __METHOD__);
            $return            = true;
        } catch (AuditFileException $e) {
            $this->description = $description;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Description_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->description === null ? "null" : $this->description
                )
            );
        return $return;
    }

    /**
     * Gets as systemID<br>
     * Unique receipt number generated internally by the application.<br>
     * &lt;xs:element ref="SystemID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getSystemID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->systemID === null ?
                    "null" : $this->systemID
                )
            );
        return $this->systemID;
    }

    /**
     * Sets a new systemID<br>
     * Unique receipt number generated internally by the application.<br>
     * &lt;xs:element ref="SystemID" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="SystemID" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @param string|null $systemID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSystemID(?string $systemID): bool
    {
        try {
            $this->systemID = $systemID === null ? null :
                $this->valTextMandMaxCar(
                    $systemID, 60, __METHOD__
                );
            $return         = true;
        } catch (AuditFileException $e) {
            $this->systemID = $systemID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SystemID_not_valid");
            $return         = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->systemID === null ? "null" : $this->systemID
                )
            );
        return $return;
    }

    /**
     * Gets as documentStatus<br>
     * &lt;xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentStatus
     * @param bool $create If true a new instance will be create if wasn't previous
     * @since 1.0.0
     */
    public function getDocumentStatus(bool $create = true): DocumentStatus
    {
        if ($create && isset($this->documentStatus) === false) {
            $this->documentStatus = new DocumentStatus($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "DocumentStatus"));
        return $this->documentStatus;
    }

    /**
     * Get if is set DocumentStatus
     * @return bool
     * @since 1.0.0
     */
    public function issetDocumentStatus(): bool
    {
        return isset($this->documentStatus);
    }
    
    /**
     * Adds to paymentMethod stack<br>
     * Indicate the payment method. In case of mixed payments, the amounts
     * should be mentioned by payment type and date.
      If there is a need to make more than one reference, this structure can
     * be generated as many times as necessary.<br>
     * When this method is invoked a new instance of PaymentMethod is created,
     * add to the stack then returned to be populated
     * &lt;xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\PaymentMethod
     * @since 1.0.0
     */
    public function addPaymentMethod(): PaymentMethod
    {
        $paymentMethod         = new PaymentMethod($this->getErrorRegistor());
        $this->paymentMethod[] = $paymentMethod;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." PaymentMethos add to index"
        );
        return $paymentMethod;
    }

    /**
     * Gets paymentMethod stack<br>
     * &lt;xs:element name="PaymentMethod" type="PaymentMethod" minOccurs="0" maxOccurs="unbounded"/&gt;
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
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSourceID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->sourceID));
        return $this->sourceID;
    }

    /**
     * Get if is set SourceID
     * @return bool
     * @since 1.0.0
     */
    public function issetSourceID(): bool
    {
        return isset($this->sourceID);
    }

    /**
     * Sets a new sourceID<br>
     * &lt;xs:element ref="SourceID"/&gt;<br>
     * &lt;xs:element name="SourceID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;<br>
     * @param string $sourceID
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setSourceID(string $sourceID): bool
    {
        try {
            $this->sourceID = $this->valTextMandMaxCar($sourceID, 30, __METHOD__);
            $return         = true;
        } catch (AuditFileException $e) {
            $this->sourceID = $sourceID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("SourceID_not_valid");
            $return         = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->sourceID));
        return $return;
    }

    /**
     * Gets as systemEntryDate<br>
     * &lt;xs:element ref="SystemEntryDate"/&gt;<br>
     * &lt;xs:element name="SystemEntryDate" type="SAFdateTimeType"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getSystemEntryDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)
                )
            );
        return $this->systemEntryDate;
    }

    /**
     * Get if is set SystemEntryDate
     * @return bool
     * @since 1.0.0
     */
    public function issetSystemEntryDate(): bool
    {
        return isset($this->systemEntryDate);
    }

    /**
     * Sets a new systemEntryDate<br>
     * &lt;xs:element ref="SystemEntryDate"/&gt;<br>
     * &lt;xs:element name="SystemEntryDate" type="SAFdateTimeType"/&gt;     *
     * @param \Rebelo\Date\Date $systemEntryDate
     * @return void
     * @since 1.0.0
     */
    public function setSystemEntryDate(RDate $systemEntryDate): void
    {
        $this->systemEntryDate = $systemEntryDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->systemEntryDate->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Gets as customerID<br>
     * &lt;xs:element ref="CustomerID"/&gt;<br>
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getCustomerID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->customerID));
        return $this->customerID;
    }

    /**
     * Get if is set CustomerID
     * @return bool
     * @since 1.0.0
     */
    public function issetCustomerID(): bool
    {
        return isset($this->customerID);
    }

    /**
     * Sets a new customerID<br>
     * &lt;xs:element ref="CustomerID"/&gt;<br>
     * &lt;xs:element name="CustomerID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * @param string $customerID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): bool
    {
        try {
            $this->customerID = $this->valTextMandMaxCar(
                $customerID, 30, __METHOD__, true
            );
            $return           = true;
        } catch (AuditFileException $e) {
            $this->customerID = $customerID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("CustomerID_not_valid");
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->customerID));
        return $return;
    }

    /**
     * Adds as line<br>
     * When this method is invoked a new instance is created, add to the stack
     * and returned to be populated<br>
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Line
     * @since 1.0.0
     */
    public function addLine(): Line
    {
        $line         = new Line($this->getErrorRegistor());
        $this->line[] = $line;
        $line->setLineNumber(\count($this->line));
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." Line add to index"
        );
        return $line;
    }

    /**
     * Gets line stack<br>
     * &lt;xs:element name="Line" maxOccurs="unbounded">
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
     * Gets DocumentTotals <br>
     * When this method is invoked a new instance of DocumentTotals is created,
     * add returned to be populated<br>
     * &lt;xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        if (isset($this->documentTotals) === false) {
            $this->documentTotals = new DocumentTotals($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "Document Totals"));
        return $this->documentTotals;
    }

    /**
     * Get if is set DocumentTotals
     * @return bool
     * @since 1.0.0
     */
    public function issetDocumentTotals(): bool
    {
        return isset($this->documentTotals);
    }

    /**
     * Adds as withholdingTax <br><br>
     * When this method is invoked a new instance is created, add to the stack
     * and returned to be populated
     * &lt;xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax
     * @since 1.0.0
     */
    public function addWithholdingTax(): WithholdingTax
    {
        $withholdingTax         = new WithholdingTax($this->getErrorRegistor());
        $this->withholdingTax[] = $withholdingTax;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." WithholdingTax add to indexs"
        );
        return $withholdingTax;
    }

    /**
     * Gets as withholdingTax<br>
     * &lt;xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/&gt;
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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                Payments::N_PAYMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $payNode = $node->addChild(static::N_PAYMENT);

        if (isset($this->paymentRefNo)) {
            $payNode->addChild(static::N_PAYMENTREFNO, $this->getPaymentRefNo());
        } else {
            $payNode->addChild(static::N_PAYMENTREFNO);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentRefNo_not_valid");
        }

        if (isset($this->atcud)) {
            $payNode->addChild(static::N_ATCUD, $this->getATCUD());
        } else {
            $payNode->addChild(static::N_ATCUD);
            $this->getErrorRegistor()->addOnCreateXmlNode("ATCUD_not_valid");
        }


        if ($this->getPeriod() !== null) {
            $payNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }

        if ($this->getTransactionID(false) !== null) {
            $this->getTransactionID()->createXmlNode($payNode);
        }

        if (isset($this->transactionDate)) {
            $payNode->addChild(
                static::N_TRANSACTIONDATE,
                $this->getTransactionDate()->format(RDate::SQL_DATE)
            );
        } else {
            $payNode->addChild(static::N_TRANSACTIONDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TransactionDate_not_valid");
        }

        if (isset($this->paymentType)) {
            $payNode->addChild(
                static::N_PAYMENTTYPE, $this->getPaymentType()->get()
            );
        } else {
            $payNode->addChild(static::N_PAYMENTTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("PaymentType_not_valid");
        }

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

        if (isset($this->documentStatus)) {
            $this->getDocumentStatus()->createXmlNode($payNode);
        } else {
            $payNode->addChild(DocumentStatus::N_DOCUMENTSTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentStatus_not_valid");
        }

        foreach ($this->getPaymentMethod() as $payMeth) {
            /* @var $payMeth PaymentMethod */
            $payMeth->createXmlNode($payNode);
        }

        if (isset($this->sourceID)) {
            $payNode->addChild(
                static::N_SOURCEID, $this->getSourceID()
            );
        } else {
            $payNode->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if (isset($this->systemEntryDate)) {
            $payNode->addChild(
                static::N_SYSTEMENTRYDATE,
                $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $payNode->addChild(static::N_SYSTEMENTRYDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("SystemEntryDate_not_valid");
        }

        if (isset($this->customerID)) {
            $payNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        } else {
            $payNode->addChild(static::N_CUSTOMERID);
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerID_not_valid");
        }

        foreach ($this->getLine() as $line) {
            /* @var $line Line */
            $line->createXmlNode($payNode);
        }

        if (isset($this->documentTotals)) {
            $this->getDocumentTotals()->createXmlNode($payNode);
        } else {
            $payNode->addChild(DocumentTotals::N_DOCUMENTTOTALS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentTotals_not_valid");
        }

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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_PAYMENT, $node->getName()
            );
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
            $this->getTransactionID()->parseXmlNode(
                $node->{static::N_TRANSACTIONID}
            );
        }

        $this->setTransactionDate(
            RDate::parse(
                RDate::SQL_DATE,
                (string) $node->{static::N_TRANSACTIONDATE}
            )
        );

        $this->setPaymentType(
            new PaymentType((string) $node->{static::N_PAYMENTTYPE})
        );

        if ($node->{static::N_DESCRIPTION}->count() > 0) {
            $this->setDescription((string) $node->{static::N_DESCRIPTION});
        }

        if ($node->{static::N_SYSTEMID}->count() > 0) {
            $this->setSystemID((string) $node->{static::N_SYSTEMID});
        }

        $this->getDocumentStatus()->parseXmlNode(
            $node->{DocumentStatus::N_DOCUMENTSTATUS}
        );

        for ($n = 0; $n < $node->{static::N_PAYMENTMETHOD}->count(); $n++) {
            $this->addPaymentMethod()->parseXmlNode(
                $node->{static::N_PAYMENTMETHOD}[$n]
            );
        }

        $this->setSourceID((string) $node->{static::N_SOURCEID});

        $this->setSystemEntryDate(
            RDate::parse(
                RDate::DATE_T_TIME,
                (string) $node->{static::N_SYSTEMENTRYDATE}
            )
        );

        $this->setCustomerID((string) $node->{static::N_CUSTOMERID});

        for ($n = 0; $n < $node->{Line::N_LINE}->count(); $n++) {
            $this->addLine()->parseXmlNode($node->{Line::N_LINE}[$n]);
        }

        $this->getDocumentTotals()->parseXmlNode(
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
        );

        $whtCount = $node->{WithholdingTax::N_WITHHOLDINGTAX}->count();
        for ($n = 0; $n < $whtCount; $n++) {
            $this->addWithholdingTax()->parseXmlNode(
                $node->{WithholdingTax::N_WITHHOLDINGTAX}[$n]
            );
        }
    }

    /**
     * Get the caluleted values of the validation classes
     * @return \Rebelo\SaftPt\Validate\DocTotalCalc|null
     * @since 1.0.0
     */
    public function getDocTotalcal(): ?DocTotalCalc
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__);
        return $this->docTotalcal;
    }

    /**
     * Set the caluleted values of the validation classes
     * @param \Rebelo\SaftPt\Validate\DocTotalCalc|null $docTotalcal
     * @return void
     * @since 1.0.0
     */
    public function setDocTotalcal(?DocTotalCalc $docTotalcal): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->docTotalcal = $docTotalcal;
    }
}