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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom;

/**
 * StockMovement
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class StockMovement extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ADocument
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
     * <xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * <xs:element ref="MovementDate"/><br>
     * <xs:element name="MovementDate" type="SAFdateType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $movementDate;

    /**
     * <xs:element ref="MovementType"/>
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
     * <xs:element ref="MovementComments" minOccurs="0"/><br>
     * <xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $movementComments = null;

    /**
     * <xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo|null
     * @since 1.0.0
     */
    private ?ShipTo $shipTo = null;

    /**
     * <xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom|null
     * @since 1.0.0
     */
    private ?ShipFrom $shipFrom = null;

    /**
     * <xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="MovementEndTime" type="SAFdateTimeType"/>
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementEndTime = null;

    /**
     * <xs:element ref="MovementStartTime" maxOccurs="1"/><br>
     * <xs:element name="MovementStartTime" type="SAFdateTimeType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $movementStartTime;

    /**
     * <xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $atDocCodeID = null;

    /**
     * <xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * <xs:element name="DocumentTotals">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * StockMovement
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
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
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
            ->info(\sprintf(__METHOD__." getted '%s'", $this->documentNumber));
        return $this->documentNumber;
    }

    /**
     *
     * Get DocumentNumber
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
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDocumentNumber(string $documentNumber): void
    {
        if (\strlen($documentNumber) > 60 ||
            \strlen($documentNumber) < 1 ||
            \preg_match("/[^ ]+ [^\/^ ]+\/[0-9]+/", $documentNumber) !== 1
        ) {
            $msg = "DocumentNumber length must be between 1 and 60 and must respect regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->documentNumber = $documentNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->documentNumber));
    }

    /**
     * Set DocumentStatus<br>
     * <xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "DocumentSatus"));
        return $this->documentStatus;
    }

    /**
     * Set DocumentStatus<br>
     * <xs:element name="DocumentStatus">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentStatus $documentStatus
     * @return void
     * @since 1.0.0
     */
    public function setDocumentStatus(DocumentStatus $documentStatus): void
    {
        $this->documentStatus = $documentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", "DocumentStatus"));
    }

    /**     *
     * <xs:element ref="MovementDate"/><br>
     * <xs:element name="MovementDate" type="SAFdateType"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->movementDate->format(RDate::SQL_DATE)
        ));
        return $this->movementDate;
    }

    /**
     * <xs:element ref="MovementDate"/><br>
     * <xs:element name="MovementDate" type="SAFdateType"/>
     * @param \Rebelo\Date\Date $movementDate
     * @return void
     * @since 1.0.0
     */
    public function setMovementDate(RDate $movementDate): void
    {
        $this->movementDate = $movementDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->movementDate->format(RDate::SQL_DATE)
        ));
    }

    /**
     * Get MovementType <br>
     * <xs:element ref="MovementType"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType
     * @since 1.0.0
     */
    public function getMovementType(): MovementType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->movementType->get()
        ));
        return $this->movementType;
    }

    /**
     * Set MovementType <br>
     * <xs:element ref="MovementType"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementType $movementType
     * @return void
     * @since 1.0.0
     */
    public function setMovementType(MovementType $movementType): void
    {
        $this->movementType = $movementType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->movementType->get()));
    }

    /**
     * Set CustomerID
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @param string $customerID
     * @return void
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function setCustomerID(string $customerID): void
    {
        if (isset($this->supplierID)) {
            $msg = "Can not set CustomerID if SupplierID is setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        parent::setCustomerID($customerID);
    }

    /**
     * Set SupplierID
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getSupplierID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->supplierID
        ));
        return $this->supplierID;
    }

    /**
     *
     * Set SupplierID
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="CustomerID"/&gt;
     *    &lt;xs:element ref="SupplierID"/&gt;
     * &lt;/xs:choice&gt;
     * &lt;xs:element name="SupplierID" type="SAFPTtextTypeMandatoryMax30Car"/&gt;
     * </pre>
     * @param string $supplierID
     * @return void
     * @since 1.0.0
     */
    public function setSupplierID(string $supplierID): void
    {
        if (isset($this->customerID)) {
            $msg = "Can not set SupplierID if CustomerID is setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->supplierID = $this->valTextMandMaxCar(
            $supplierID, 30, __METHOD__, false
        );
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->supplierID
                )
        );
    }

    /**
     * Get MovementComments
     * <xs:element ref="MovementComments" minOccurs="0"/><br>
     * <xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getMovementComments(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->movementComments === null ? "null" : $this->movementComments
        ));
        return $this->movementComments;
    }

    /**
     * Set MovementComments
     * <xs:element ref="MovementComments" minOccurs="0"/><br>
     * <xs:element name="MovementComments" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @param string|null $movementComments
     * @return void
     * @since 1.0.0
     */
    public function setMovementComments(?string $movementComments): void
    {
        $this->movementComments = $movementComments === null ? null :
            $this->valTextMandMaxCar(
                $movementComments, 60, __METHOD__
        );
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->movementComments === null ? "null" : $this->movementComments
        ));
    }

    /**
     * Get ShipTo<br>
     * <xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo|null
     * @since 1.0.0
     */
    public function getShipTo(): ?ShipTo
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", "ShipTo"
                )
        );
        return $this->shipTo;
    }

    /**
     * Set ShipTo<br>
     * <xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipTo|null $shipTo
     * @return void
     * @since 1.0.0
     */
    public function setShipTo(?ShipTo $shipTo): void
    {
        $this->shipTo = $shipTo;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", "ShipTo"
                )
        );
    }

    /**
     * Get ShipFrom<br>
     * <xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom|null
     * @since 1.0.0
     */
    public function getShipFrom(): ?ShipFrom
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", "ShipFrom"
                )
        );
        return $this->shipFrom;
    }

    /**
     * Set ShipFrom<br>
     * <xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\ShipFrom|null $shipFrom
     * @return void
     * @since 1.0.0
     */
    public function setShipFrom(?ShipFrom $shipFrom): void
    {
        $this->shipFrom = $shipFrom;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", "ShipFrom"
                )
        );
    }

    /**
     * Set MovementEndTime<br>
     * <xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="MovementEndTime" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getMovementEndTime(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->movementEndTime === null ?
                        "null" :
                        $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
        );
        return $this->movementEndTime;
    }

    /**
     * Get MovementEndTime<br>
     * <xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="MovementEndTime" type="SAFdateTimeType"/>
     * @param \Rebelo\Date\Date|null $movementEndTime
     * @return void
     * @since 1.0.0
     */
    public function setMovementEndTime(?RDate $movementEndTime): void
    {
        $this->movementEndTime = $movementEndTime;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->movementEndTime === null ?
                        "null" :
                        $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
        );
    }

    /**
     * Get MovementStartTime<br>
     * <xs:element ref="MovementStartTime" maxOccurs="1"/><br>
     * <xs:element name="MovementStartTime" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementStartTime(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
        );
        return $this->movementStartTime;
    }

    /**
     * Set MovementStartTime<br>
     * <xs:element ref="MovementStartTime" maxOccurs="1"/><br>
     * <xs:element name="MovementStartTime" type="SAFdateTimeType"/>
     * @param \Rebelo\Date\Date $movementStartTime
     * @return void
     * @since 1.0.0
     */
    public function setMovementStartTime(RDate $movementStartTime): void
    {
        $this->movementStartTime = $movementStartTime;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
        );
    }

    /**
     * Get AtDocCodeID<br>
     * <xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getAtDocCodeID(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->atDocCodeID === null ? "null" :
                        $this->atDocCodeID
                )
        );
        return $this->atDocCodeID;
    }

    /**
     * Set AtDocCodeID<br>
     * <xs:element ref="ATDocCodeID" minOccurs="0" maxOccurs="1"/><br>
     * <xs:element name="ATDocCodeID" type="SAFPTtextTypeMandatoryMax200Car"/>
     * @param string|null $atDocCodeID
     * @return void
     * @since 1.0.0
     */
    public function setAtDocCodeID(?string $atDocCodeID): void
    {
        $this->atDocCodeID = $atDocCodeID === null ? null :
            $this->valTextMandMaxCar($atDocCodeID, 200, __METHOD__, false);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->atDocCodeID === "null" ?
                        "" : $this->atDocCodeID
                )
        );
    }

    /**
     * Get Line Stack<br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line[]
     * @since 1.0.0
     */
    public function getLine(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted stack with '%s' elements",
                    \count($this->line)
                )
        );
        return $this->line;
    }

    /**
     * Add Line to stack<br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line $line
     * @return int
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
     * isset line
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
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->documentTotals;
    }

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\DocumentTotals $documentTotals
     * @return void
     * @since 1.0.0
     */
    public function setDocumentTotals(DocumentTotals $documentTotals): void
    {
        $this->documentTotals = $documentTotals;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== MovementOfGoods::N_MOVEMENTOFGOODS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                MovementOfGoods::N_MOVEMENTOFGOODS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $stkMov = $node->addChild(static::N_STOCKMOVEMENT);
        $stkMov->addChild(static::N_DOCUMENTNUMBER, $this->getDocumentNumber());
        $stkMov->addChild(static::N_ATCUD, $this->getAtcud());
        $this->getDocumentStatus()->createXmlNode($stkMov);
        $stkMov->addChild(static::N_HASH, $this->getHash());
        $stkMov->addChild(self::N_HASHCONTROL, $this->getHashControl());
        if ($this->getPeriod() !== null) {
            $stkMov->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }
        $stkMov->addChild(
            static::N_MOVEMENTDATE,
            $this->getMovementDate()->format(RDate::SQL_DATE)
        );
        $stkMov->addChild(
            static::N_MOVEMENTTYPE, $this->getMovementType()->get()
        );
        $stkMov->addChild(
            static::N_SYSTEMENTRYDATE,
            $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
        );
        if ($this->getTransactionID() !== null) {
            $this->getTransactionID()->createXmlNode($stkMov);
        }

        if (isset($this->customerID) === false && isset($this->supplierID) === false) {
            $msg = "CustomerID or SupplierID must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if (isset($this->customerID)) {
            $stkMov->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        }

        if (isset($this->supplierID)) {
            $stkMov->addChild(static::N_SUPPLIERID, $this->getSupplierID());
        }

        $stkMov->addChild(static::N_SOURCEID, $this->getSourceID());

        if ($this->getEacCode() !== null) {
            $stkMov->addChild(static::N_EACCODE, $this->getEacCode());
        }

        if ($this->getMovementComments() !== null) {
            $stkMov->addChild(
                static::N_MOVEMENTCOMMENTS, $this->getMovementComments()
            );
        }

        if ($this->getShipTo() !== null) {
            $this->getShipTo()->createXmlNode($stkMov);
        }

        if ($this->getShipFrom() !== null) {
            $this->getShipFrom()->createXmlNode($stkMov);
        }

        if ($this->getMovementEndTime() !== null) {
            $stkMov->addChild(
                static::N_MOVEMENTENDTIME,
                $this->getMovementEndTime()->format(RDate::DATE_T_TIME)
            );
        }

        $stkMov->addChild(
            static::N_MOVEMENTSTARTTIME,
            $this->getMovementStartTime()->format(RDate::DATE_T_TIME)
        );

        if ($this->getAtDocCodeID() !== null) {
            $stkMov->addChild(static::N_ATDOCCODEID, $this->getAtDocCodeID());
        }

        if (\count($this->line) === 0) {
            $msg = "StockMovement without lines";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        foreach ($this->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\Line */
            $line->createXmlNode($stkMov);
        }

        $this->getDocumentTotals()->createXmlNode($stkMov);

        return $stkMov;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_STOCKMOVEMENT) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_STOCKMOVEMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        parent::parseXmlNode($node);

        $this->setDocumentNumber((string) $node->{static::N_DOCUMENTNUMBER});
        $status = new DocumentStatus();
        $status->parseXmlNode($node->{DocumentStatus::N_DOCUMENTSTATUS});
        $this->setDocumentStatus($status);
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
            $shipTo = new ShipTo();
            $shipTo->parseXmlNode($node->{static::N_SHIPTO});
            $this->setShipTo($shipTo);
        }

        if ($node->{static::N_SHIPFROM}->count() > 0) {
            $shipFrom = new ShipFrom();
            $shipFrom->parseXmlNode($node->{static::N_SHIPFROM});
            $this->setShipFrom($shipFrom);
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
            $line = new Line();
            $line->parseXmlNode($node->{Line::N_LINE}[$n]);
            $this->addToLine($line);
        }

        $totals = new DocumentTotals();
        $totals->parseXmlNode($node->{DocumentTotals::N_DOCUMENTTOTALS});
        $this->setDocumentTotals($totals);
    }
}