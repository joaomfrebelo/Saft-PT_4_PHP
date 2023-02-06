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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocument;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AAuditFile;

/**
 * StockMovement
 *
 * @author João Rebelo
 * @since 1.0.0
 * @method addStockMovement()
 */
class StockMovement extends ADocument
{
    /**
     *
     * @since 1.0.0
     */
    const N_STOCKMOVEMENT = "StockMovement";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DOCUMENTNUMBER = "DocumentNumber";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTDATE = "MovementDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTTYPE = "MovementType";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SUPPLIERID = "SupplierID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTCOMMENTS = "MovementComments";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SHIPTO = "ShipTo";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SHIPFROM = "ShipFrom";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTENDTIME = "MovementEndTime";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_MOVEMENTSTARTTIME = "MovementStartTime";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ATDOCCODEID = "ATDocCodeID";

    /**
     * <pre>
     * &lt;xs:element ref="DocumentNumber"/&gt;
     * &lt;xs:element name="DocumentNumber"&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *           &lt;xs:minLength value="1"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $documentNumber;

    /**
     * &lt;xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * &lt;xs:element ref="MovementDate"/&gt;<br>
     * &lt;xs:element name="MovementDate" type="SAFdateType"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $movementDate;

    /**
     * &lt;xs:element ref="MovementType"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType
     * @since 1.0.0
     */
    private MovementType $movementType;

    /**
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $supplierID;

    /**
     * &lt;xs:element ref="MovementComments" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $movementComments = null;

    /**
     * &lt;xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo|null
     * @since 1.0.0
     */
    private ?ShipTo $shipTo = null;

    /**
     * &lt;xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom|null
     * @since 1.0.0
     */
    private ?ShipFrom $shipFrom = null;

    /**
     * &lt;xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementEndTime" type="SAFdateTimeType"/&gt;
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementEndTime = null;

    /**
     * &lt;xs:element ref="MovementStartTime" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementStartTime" type="SAFdateTimeType"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $movementStartTime;

    /**
     * &lt;xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @var string|null
     * @since 1.0.0
     */
    private ?string $atDocCodeID = null;

    /**
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * &lt;xs:element name="DocumentTotals">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * StockMovement
     * <pre>
     * &lt;xs:element name="StockMovement" minOccurs="0" maxOccurs="unbounded"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="DocumentNumber"/&gt;
     *           &lt;xs:element ref="ATCUD"/&gt;
     *           &lt;!-- Estrutura da situacao atual do documento --&gt;
     *           &lt;xs:element name="DocumentStatus"/&gt;
     *           &lt;xs:element ref="Hash"/&gt;
     *           &lt;xs:element ref="HashControl"/&gt;
     *           &lt;xs:element ref="Period" minOccurs="0"/&gt;
     *           &lt;xs:element ref="MovementDate"/&gt;
     *           &lt;xs:element ref="MovementType"/&gt;
     *           &lt;xs:element ref="SystemEntryDate"/&gt;
     *           &lt;xs:element ref="TransactionID" minOccurs="0"/&gt;
     *           &lt;xs:choice&gt;
     *               &lt;xs:element ref="CustomerID"/&gt;
     *               &lt;xs:element ref="SupplierID"/&gt;
     *           &lt;/xs:choice&gt;
     *           &lt;xs:element ref="SourceID"/&gt;
     *           &lt;xs:element ref="EACCode" minOccurs="0"/&gt;
     *           &lt;xs:element ref="MovementComments" minOccurs="0"/&gt;
     *           &lt;xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/&gt;
     *           &lt;xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/&gt;
     *           &lt;xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/&gt;
     *           &lt;xs:element ref="MovementStartTime" maxOccurs="1"/&gt;
     *           &lt;!-- Nos documentos resumo (MovementStatus "R"), na falta desta informacao em concreto, a hora de inicio de transporte (MovementStartTime) pode ser indicada com a concatenacao da data constante no campo MovementDate com a hora 00:00:00--&gt;
     *           &lt;xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/&gt;
     *           &lt;xs:element name="Line" maxOccurs="unbounded" /&gt;
     *           &lt;xs:element name="DocumentTotals"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
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
     * Get DocumentNumber
     * &lt;xs:element ref="DocumentNumber"/&gt;
     * &lt;xs:element name="DocumentNumber"&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *           &lt;xs:minLength value="1"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * @return string
     * @since 1.0.0
     */
    public function getDocumentNumber(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->documentNumber));
        return $this->documentNumber;
    }

    /**
     *
     * Get DocumentNumber<br>
     * This identification is sequentially composed by following elements:
     * the document type internal code, followed by a space, followed by the identifier of the document series, followed by (/) and by the sequential number of that document within the series.
     * This field does not allow records with the same identification.
     * <pre>
     * &lt;xs:element ref="DocumentNumber"/&gt;
     * &lt;xs:element name="DocumentNumber"&gt;
     *   &lt;xs:simpleType&gt;
     *       &lt;xs:restriction base="xs:string"&gt;
     *           &lt;xs:pattern value="[^ ]+ [^/^ ]+/[0-9]+"/&gt;
     *           &lt;xs:minLength value="1"/&gt;
     *           &lt;xs:maxLength value="60"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     *  &lt;/xs:element&gt;
     * </pre>
     * @param string $documentNumber
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setDocumentNumber(string $documentNumber): bool
    {
        if (AAuditFile::validateDocNumber($documentNumber) === false) {
            $msg    = "DocumentNumber length must be between 1 and 60 and must respect regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("DocumentNumber_not_valid");
        } else {
            $return = true;
        }
        $this->documentNumber = $documentNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->documentNumber));
        return $return;
    }

    /**
     * Get if is set DocumentNumber
     * @return bool
     * @since 1.0.0
     */
    public function issetDocumentNumber(): bool
    {
        return isset($this->documentNumber);
    }

    /**
     * Set DocumentStatus<br><br>
     * This identification is sequentially composed by following elements:
     * the document type internal code, followed by a space, followed by the identifier of the document series, followed by (/) and by the sequential number of that document within the series.
     * This field does not allow records with the same identification.
     * &lt;xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        if (isset($this->documentStatus) === false) {
            $this->documentStatus = new DocumentStatus($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", "DocumentSatus"));
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
     * Set DocumentStatus<br>
     * &lt;xs:element name="DocumentStatus">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus $documentStatus
     * @return void
     * @since 1.0.0
     */
    public function setDocumentStatus(DocumentStatus $documentStatus): void
    {
        $this->documentStatus = $documentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", "DocumentStatus"));
    }

    /**
     * Get MovementDate<br>
     * Date of the last record of the document status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.
     * &lt;xs:element ref="MovementDate"/&gt;<br>
     * &lt;xs:element name="MovementDate" type="SAFdateType"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function getMovementDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementDate->format(RDate::SQL_DATE)
                )
            );
        return $this->movementDate;
    }

    /**
     * Get if is set MovementDate
     * @return bool
     * @since 1.0.0
     */
    public function issetMovementDate(): bool
    {
        return isset($this->movementDate);
    }

    /**
     * Set MovementDate<br>
     * Date of the last record of the document status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.
     * &lt;xs:element ref="MovementDate"/&gt;<br>
     * &lt;xs:element ref="MovementDate"/&gt;<br>
     * &lt;xs:element name="MovementDate" type="SAFdateType"/&gt;
     * @param \Rebelo\Date\Date $movementDate
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function setMovementDate(RDate $movementDate): void
    {
        $this->movementDate = $movementDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Get MovementType <br>
     * Shall be filled in with:<br>
     * "GR" - Delivery note;<br>
     * "GT" - Transport guide (include here the global transport documents);<br>
     * “GA” – Transport document for own fixed assets;<br>
     * “GC” - Consignment note;<br>
     * “GD” – Return note.<br>
     * &lt;xs:element ref="MovementType"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType
     * @since 1.0.0
     */
    public function getMovementType(): MovementType
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementType->get()
                )
            );
        return $this->movementType;
    }

    /**
     * Get if is set MovementType
     * @return bool
     * @since 1.0.0
     */
    public function issetMovementType(): bool
    {
        return isset($this->movementType);
    }

    /**
     * Set MovementType <br>
     * Shall be filled in with:<br>
     * "GR" - Delivery note;<br>
     * "GT" - Transport guide (include here the global transport documents);<br>
     * “GA” – Transport document for own fixed assets;<br>
     * “GC” - Consignment note;<br>
     * “GD” – Return note.<br>
     * &lt;xs:element ref="MovementType"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType $movementType
     * @return void
     * @since 1.0.0
     */
    public function setMovementType(MovementType $movementType): void
    {
        $this->movementType = $movementType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementType->get()
                )
            );
    }

    /**
     * Set CustomerID<br>
     * The unique key of table 2.2. – Customer complying with rule defined
     * for field 2.2.1. - CustomerID.
     * In case of bills/notes without recipient, the generic customer
     * of table 2.2. – Customer, shall be used.
     * This fields shall also be filled out in case of transport
     * documents referring to movements of goods of the sender himself.
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @param string $customerID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): bool
    {
        if (isset($this->supplierID)) {
            $msg              = "Can not set CustomerID if SupplierID is setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnSetValue("CustomerID_and_SupplierID_at_same_time");
            $this->customerID = $customerID;
            return false;
        } else {
            return parent::setCustomerID($customerID);
        }
    }

    /**
     * Set SupplierID<br>
     * The unique key of table 2.3. – Supplier, complying with rule defined
     * for field 2.3.1. – SupplierID, in case of return notes or
     * transport notes regarding movable assets produced or assembled
     * according to an order of materials supplied for this purpose
     * by the owner (work from materials supplied without the ownership
     * being transferred).<br>
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getSupplierID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'", $this->supplierID
                )
            );
        return $this->supplierID;
    }

    /**
     * Get if is set SupplierID
     * @return bool
     * @since 1.0.0
     */
    public function issetSupplierID(): bool
    {
        return isset($this->supplierID);
    }

    /**     *
     * Set SupplierID<br>
     * The unique key of table 2.3. – Supplier, complying with rule defined
     * for field 2.3.1. – SupplierID, in case of return notes or
     * transport notes regarding movable assets produced or assembled
     * according to an order of materials supplied for this purpose
     * by the owner (work from materials supplied without the ownership
     * being transferred).<br>
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * &lt;xs:element name="SupplierID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @param string $supplierID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setSupplierID(string $supplierID): bool
    {
        try {
            if (isset($this->customerID)) {
                $msg = "Can not set SupplierID if CustomerID is setted";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__." '%s'", $msg));
                $this->getErrorRegistor()->addOnSetValue("CustomerID_and_SupplierID_at_same_time");
                throw new AuditFileException($msg);
            }
            $this->supplierID = $this->valTextMandMaxCar(
                $supplierID, 30, __METHOD__, false
            );
            $return           = true;
        } catch (AuditFileException $e) {
            $this->supplierID = $supplierID;
            $return           = false;
            $this->getErrorRegistor()->addOnSetValue("SupplierID_not_valid");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $e->getMessage()));
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", $this->supplierID
                )
            );
        return $return;
    }

    /**
     * Get MovementComments
     * &lt;xs:element ref="MovementComments" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getMovementComments(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementComments === null ? "null" : $this->movementComments
                )
            );
        return $this->movementComments;
    }

    /**
     * Set MovementComments<br>
     * &lt;xs:element ref="MovementComments" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @param string|null $movementComments
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setMovementComments(?string $movementComments): bool
    {
        try {
            $this->movementComments = $movementComments === null ? null :
                $this->valTextMandMaxCar(
                    $movementComments, 60, __METHOD__
                );
            $return                 = true;
        } catch (AuditFileException $e) {
            $this->movementComments = $movementComments;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ProductNumberCode_not_valid");
            $return                 = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementComments === null ? "null" : $this->
                        movementComments
                )
            );
        return $return;
    }

    /**
     * Get ShipTo<br>
     * Information about the delivery place and date,
     * where and when the goods have been made available for the client,
     * or anyone assigned by him in the case of triangular transactions.<br>
     * &lt;xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/&gt;
     * @param bool $create if true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo|null
     * @since 1.0.0
     */
    public function getShipTo(bool $create = true): ?ShipTo
    {
        if ($create && $this->shipTo === null) {
            $this->shipTo = new ShipTo($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(__METHOD__." get '%s'", "ShipTo")
            );
        return $this->shipTo;
    }

    /**
     * Set ShipTo As Null
     * @return void
     * @since 1.0.0
     */
    public function setShipToAsNull(): void
    {
        $this->shipTo = null;
    }

    /**
     * Get ShipFrom<br>
     * Information about the place and date of the shipping of
     * the articles sold to the customer.<br>
     * &lt;xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/&gt;
     * @param bool $create if true a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom|null
     * @since 1.0.0
     */
    public function getShipFrom(bool $create = true): ?ShipFrom
    {
        if ($create && $this->shipFrom === null) {
            $this->shipFrom = new ShipFrom($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'", "ShipFrom"
                )
            );
        return $this->shipFrom;
    }

    /**
     * Set ShipFrom As Null
     * @return void
     * @since 1.0.0
     */
    public function setShipFromAsNull(): void
    {
        $this->shipFrom = null;
    }

    /**
     * Set MovementEndTime<br>
     * Date and time: “YYYY-MM-DDThh:mm:ss”, where “ss” may be “00”,
     * if no specific information is available.<br>
     * &lt;xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementEndTime" type="SAFdateTimeType"/&gt;
     * @return \Rebelo\Date\Date|null
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function getMovementEndTime(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementEndTime === null ?
                        "null" : $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
            );
        return $this->movementEndTime;
    }

    /**
     * Get MovementEndTime<br>
     * Date and time: “YYYY-MM-DDThh:mm:ss”, where “ss” may be “00”,
     * if no specific information is available.<br>
     * &lt;xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementEndTime" type="SAFdateTimeType"/&gt;
     * @param \Rebelo\Date\Date|null $movementEndTime
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function setMovementEndTime(?RDate $movementEndTime): void
    {
        $this->movementEndTime = $movementEndTime;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementEndTime === null ?
                        "null" : $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get MovementStartTime<br>
     * Date and time: “YYYY-MM-DDThh:mm:ss”, where “ss” may be “00”, ”
     * if no specific information is available.<br>
     * &lt;xs:element ref="MovementStartTime" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementStartTime" type="SAFdateTimeType"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function getMovementStartTime(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
            );
        return $this->movementStartTime;
    }

    /**
     * Get if is set MovementStartTime
     * @return bool
     * @since 1.0.0
     */
    public function issetMovementStartTime(): bool
    {
        return isset($this->movementStartTime);
    }

    /**
     * Set MovementStartTime<br>
     * Date and time: “YYYY-MM-DDThh:mm:ss”, where “ss” may be “00”, ”
     * if no specific information is available.<br>
     * &lt;xs:element ref="MovementStartTime" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementStartTime" type="SAFdateTimeType"/&gt;
     * @param \Rebelo\Date\Date $movementStartTime
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function setMovementStartTime(RDate $movementStartTime): void
    {
        $this->movementStartTime = $movementStartTime;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get AtDocCodeID<br>
     * Identification code given by the Tax Authority to the document,
     * according to Decree No. 147/2003, of 11th July.<br>
     * &lt;xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getAtDocCodeID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->atDocCodeID === null ? "null" : $this->atDocCodeID
                )
            );
        return $this->atDocCodeID;
    }

    /**
     * Set AtDocCodeID<br>
     * Identification code given by the Tax Authority to the document,
     * according to Decree No. 147/2003, of 11th July.<br>
     * &lt;xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * @param string|null $atDocCodeID
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setAtDocCodeID(?string $atDocCodeID): bool
    {
        try {
            $this->atDocCodeID = $atDocCodeID === null ? null :
                $this->valTextMandMaxCar($atDocCodeID, 200, __METHOD__, false);
            $return            = true;
        } catch (AuditFileException $e) {
            $this->atDocCodeID = $atDocCodeID;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("AtDocCodeID_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->atDocCodeID === "null" ?
                        "" : $this->
                        atDocCodeID
                )
            );
        return $return;
    }

    /**
     * Get Line Stack<br>
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line[]
     * @since 1.0.0
     */
    public function getLine(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get stack with '%s' elements",
                    \count($this->line)
                )
            );
        return $this->line;
    }

    /**
     * Add Line to stack<br>
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line
     * @since 1.0.0
     */
    public function addLine(): Line
    {
        $line         = new Line($this->getErrorRegistor());
        $this->line[] = $line;
        $line->setLineNumber(\count($this->line));
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." Line add to stack"
        );
        return $line;
    }

    /**
     * Get DocumentTotals<br>
     * When this method is invoked a new instance will be created if wasn't previous
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        if (isset($this->documentTotals) === false) {
            $this->documentTotals = new DocumentTotals($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
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
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Error
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== MovementOfGoods::N_MOVEMENTOFGOODS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                MovementOfGoods::N_MOVEMENTOFGOODS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $stkMov = $node->addChild(static::N_STOCKMOVEMENT);

        if (isset($this->documentNumber)) {
            $stkMov->addChild(
                static::N_DOCUMENTNUMBER, $this->getDocumentNumber()
            );
        } else {
            $stkMov->addChild(static::N_DOCUMENTNUMBER);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentNumber_not_valid");
        }

        if (isset($this->atcud)) {
            $stkMov->addChild(static::N_ATCUD, $this->getAtcud());
        } else {
            $stkMov->addChild(static::N_ATCUD);
            $this->getErrorRegistor()->addOnCreateXmlNode("Atcud_not_valid");
        }

        if (isset($this->documentStatus)) {
            $this->getDocumentStatus()->createXmlNode($stkMov);
        } else {
            $stkMov->addChild(DocumentStatus::N_DOCUMENTSTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentStatus_not_valid");
        }

        if (isset($this->hash)) {
            $stkMov->addChild(static::N_HASH, $this->getHash());
        } else {
            $stkMov->addChild(static::N_HASH);
            $this->getErrorRegistor()->addOnCreateXmlNode("Hash_not_valid");
        }

        if (isset($this->hashControl)) {
            $stkMov->addChild(self::N_HASHCONTROL, $this->getHashControl());
        } else {
            $stkMov->addChild(static::N_HASHCONTROL);
            $this->getErrorRegistor()->addOnCreateXmlNode("HashControl_not_valid");
        }

        if ($this->getPeriod() !== null) {
            $stkMov->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }

        if (isset($this->movementDate)) {
            $stkMov->addChild(
                static::N_MOVEMENTDATE,
                $this->getMovementDate()->format(RDate::SQL_DATE)
            );
        } else {
            $stkMov->addChild(static::N_MOVEMENTDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("MovementDate_not_valid");
        }

        if (isset($this->movementType)) {
            $stkMov->addChild(
                static::N_MOVEMENTTYPE, $this->getMovementType()->get()
            );
        } else {
            $stkMov->addChild(static::N_MOVEMENTTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("MovementType_not_valid");
        }

        if (isset($this->systemEntryDate)) {
            $stkMov->addChild(
                static::N_SYSTEMENTRYDATE,
                $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $stkMov->addChild(static::N_SYSTEMENTRYDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("SystemEntryDate_not_valid");
        }

        $this->getTransactionID(false)?->createXmlNode($stkMov);

        if (isset($this->customerID) === false && isset($this->supplierID) === false) {
            $msg = "CustomerID or SupplierID must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerID_and_SupplierID_not_setted");
        }

        if (isset($this->customerID)) {
            $stkMov->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        }

        if (isset($this->supplierID)) {
            $stkMov->addChild(static::N_SUPPLIERID, $this->getSupplierID());
        }

        if (isset($this->sourceID)) {
            $stkMov->addChild(static::N_SOURCEID, $this->getSourceID());
        } else {
            $stkMov->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if ($this->getEacCode() !== null) {
            $stkMov->addChild(static::N_EACCODE, $this->getEacCode());
        }

        if ($this->getMovementComments() !== null) {
            $stkMov->addChild(
                static::N_MOVEMENTCOMMENTS, $this->getMovementComments()
            );
        }

        $this->getShipTo(false)?->createXmlNode($stkMov);
        $this->getShipFrom(false)?->createXmlNode($stkMov);

        if ($this->getMovementEndTime() !== null) {
            $stkMov->addChild(
                static::N_MOVEMENTENDTIME,
                $this->getMovementEndTime()->format(RDate::DATE_T_TIME)
            );
        }

        if (isset($this->movementStartTime)) {
            $stkMov->addChild(
                static::N_MOVEMENTSTARTTIME,
                $this->getMovementStartTime()->format(RDate::DATE_T_TIME)
            );
        } else {
            $stkMov->addChild(static::N_MOVEMENTSTARTTIME);
            $this->getErrorRegistor()->addOnCreateXmlNode("MovementStartTime_not_valid");
        }

        if ($this->getAtDocCodeID() !== null) {
            $stkMov->addChild(static::N_ATDOCCODEID, $this->getAtDocCodeID());
        }

        if (\count($this->line) === 0) {
            $msg = "StockMovement without lines";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("StockMovement_without_lines");
        }

        foreach ($this->getLine() as $line) {
            $line->createXmlNode($stkMov);
        }

        if (isset($this->documentTotals)) {
            $this->getDocumentTotals()->createXmlNode($stkMov);
        } else {
            $stkMov->addChild(DocumentTotals::N_DOCUMENTTOTALS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentTotals_not_valid");
        }

        return $stkMov;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_STOCKMOVEMENT) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_STOCKMOVEMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        parent::parseXmlNode($node);

        $this->setDocumentNumber((string) $node->{static::N_DOCUMENTNUMBER});
        $this->getDocumentStatus()->parseXmlNode(
            $node->{DocumentStatus::N_DOCUMENTSTATUS}
        );

        $this->setMovementDate(
            RDate::parse(RDate::SQL_DATE, (string) $node->{self::N_MOVEMENTDATE})
        );

        $this->setMovementType(
            new MovementType((string) $node->{static::N_MOVEMENTTYPE})
        );

        if ($node->{static::N_SUPPLIERID}->count() > 0) {
            $this->setSupplierID((string) $node->{static::N_SUPPLIERID});
        }

        if ($node->{static::N_MOVEMENTCOMMENTS}->count() > 0) {
            $this->setMovementComments((string) $node->{static::N_MOVEMENTCOMMENTS});
        }

        if ($node->{static::N_SHIPTO}->count() > 0) {
            $this->getShipTo()?->parseXmlNode($node->{static::N_SHIPTO});
        }

        if ($node->{static::N_SHIPFROM}->count() > 0) {
            $this->getShipFrom()?->parseXmlNode($node->{static::N_SHIPFROM});
        }

        if ($node->{static::N_MOVEMENTENDTIME}->count() > 0) {
            $this->setMovementEndTime(
                RDate::parse(
                    RDate::DATE_T_TIME,
                    (string) $node->{static::N_MOVEMENTENDTIME}
                )
            );
        }

        $this->setMovementStartTime(
            RDate::parse(
                RDate::DATE_T_TIME,
                (string) $node->{static::N_MOVEMENTSTARTTIME}
            )
        );

        if ($node->{static::N_ATDOCCODEID}->count() > 0) {
            $this->setAtDocCodeID((string) $node->{static::N_ATDOCCODEID});
        }

        $nLine = $node->{Line::N_LINE}->count();
        for ($n = 0; $n < $nLine; $n++) {
            $this->addLine()->parseXmlNode($node->{Line::N_LINE}[$n]);
        }

        $this->getDocumentTotals()->parseXmlNode(
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
        );
    }
}
