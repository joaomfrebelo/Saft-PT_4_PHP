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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\{AAuditFile,
    AuditFileException,
    ErrorRegister,
    SourceDocuments\ADocument,
    SourceDocuments\ShipFrom,
    SourceDocuments\ShipTo,
    SourceDocuments\WithholdingTax};

/**
 * Invoice
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Invoice extends ADocument
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const N_INVOICE = "Invoice";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_INVOICENO = "InvoiceNo";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_DOCUMENTSTATUS = "DocumentStatus";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_INVOICEDATE = "InvoiceDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_INVOICETYPE = "InvoiceType";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SPECIALREGIMES = "SpecialRegimes";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SHIPTO = "ShipTo";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_SHIPFROM = "ShipFrom";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_MOVEMENTENDTIME = "MovementEndTime";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_MOVEMENTSTARTTIME = "MovementStartTime";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DOCUMENTTOTALS = "DocumentTotals";

    /**
     * &lt;xs:element ref="InvoiceNo"/&gt;
     * @var string
     * @since 1.0.0
     */
    private string $invoiceNo;

    /**
     * &lt;xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * &lt;xs:element ref="InvoiceDate"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $invoiceDate;

    /**
     * &lt;xs:element ref="InvoiceType"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType
     * @since 1.0.0
     */
    private InvoiceType $invoiceType;

    /**
     * &lt;xs:element name="SpecialRegimes" type="SpecialRegimes"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes
     * @since 1.0.0
     */
    private SpecialRegimes $specialRegimes;

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
     * &lt;xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/&gt;
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementEndTime = null;

    /**
     * &lt;xs:element ref="MovementStartTime" minOccurs="0" maxOccurs="1"/&gt;
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementStartTime = null;

    /**
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * &lt;xs:element name="DocumentTotals"><br>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * &lt;xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax[]
     * @since 1.0.0
     */
    private array $withholdingTax = array();

    /**
     * Invoice<br>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get InvoiceNo<br>
     * It is made of the document type internal code, followed by a space,
     * followed by the identifier of the document series, followed by (/) and by a
     * sequential number of the document within the series.
     * In this field cannot exist records with the same identification.
     * The same document type internal code cannot be used for different types of documents.<br>
     * &lt;xs:element ref="InvoiceNo"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceNo(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'", $this->invoiceNo
                )
            );
        return $this->invoiceNo;
    }

    /**
     * Get if is set InvoiceNo
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoiceNo(): bool
    {
        return isset($this->invoiceNo);
    }

    /**
     * Set InvoiceNo<br>
     * It is made of the document type internal code, followed by a space,
     * followed by the identifier of the document series, followed by (/) and by a
     * sequential number of the document within the series.
     * In this field cannot exist records with the same identification.
     * The same document type internal code cannot be used for different types of documents.<br>
     * &lt;xs:element ref="InvoiceNo"/&gt;
     * @param string $invoiceNo
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setInvoiceNo(string $invoiceNo): bool
    {
        if (AAuditFile::validateDocNumber($invoiceNo) === false) {
            $msg    = "DocumentNumber length must be between 1 and 60 and must respect regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue($msg);
        } else {
            $return = true;
        }
        $this->invoiceNo = $invoiceNo;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", $this->invoiceNo
                )
            );
        return $return;
    }

    /**
     * Get DocumentStatus<br>
     * When this method is invoked a new instance of DocumentStatus is created if
     * wasn't before and returned to be populated<br>
     * &lt;xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        if (isset($this->documentStatus) === false) {
            $this->documentStatus = new DocumentStatus($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
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
     * Get InvoiceDate<br>
     * Sale document’s issue date.<br>
     * &lt;xs:element ref="InvoiceDate"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function getInvoiceDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)
                )
            );
        return $this->invoiceDate;
    }

    /**
     * Get if is set InvoiceDate
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoiceDate(): bool
    {
        return isset($this->invoiceDate);
    }

    /**
     * Set InvoiceDate<br>
     * Sale document’s issue date.<br>
     * &lt;xs:element ref="InvoiceDate"/&gt;
     * @param \Rebelo\Date\Date $invoiceDate
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function setInvoiceDate(RDate $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Get  InvoiceType<br>
     * The field shall be filled in with:
     * “FT” - Invoice;
     * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
     * “FR” – Invoice-receipt;
     * “ND” - Debit note;
     * “NC” - Credit note;
     * “VD” - Sale for cash and invoice/sales ticket; (a)
     * “TV” - Sale ticket; (a)
     * “TD” - Devolution ticket; (a)
     * “AA” - Assets sales; (a)
     * “DA” - Assets returns. (a) For the Insurance sector when it must not
     * be included in table 4.3. - WorkingDocuments, may also be filled in with:
     * “RP” – Premium or premium receipt;
     * “RE” - Return insurance or receipt of return insurance;
     * “CS” - Imputation to co-insurance companies;
     * “LD” - Imputation to a leader co-insurance company;
     * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
     * &lt;xs:element ref="InvoiceType"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceType(): InvoiceType
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'", $this->invoiceType->get()
                )
            );
        return $this->invoiceType;
    }

    /**
     * Get if is set InvoiceType
     * @return bool
     * @since 1.0.0
     */
    public function issetInvoiceType(): bool
    {
        return isset($this->invoiceType);
    }

    /**
     * Set InvoiceType<br>
     * The field shall be filled in with:
     * “FT” - Invoice;
     * “FS” - Simplified Invoice issued according to article 40 of the VAT code;
     * “FR” – Invoice-receipt;
     * “ND” - Debit note;
     * “NC” - Credit note;
     * “VD” - Sale for cash and invoice/sales ticket; (a)
     * “TV” - Sale ticket; (a)
     * “TD” - Devolution ticket; (a)
     * “AA” - Assets sales; (a)
     * “DA” - Assets returns. (a) For the Insurance sector when it must not
     * be included in table 4.3. - WorkingDocuments, may also be filled in with:
     * “RP” – Premium or premium receipt;
     * “RE” - Return insurance or receipt of return insurance;
     * “CS” - Imputation to co-insurance companies;
     * “LD” - Imputation to a leader co-insurance company;
     * “RA” - Accepted reinsurance. (a) For data up to 2012-12-31.
     * &lt;xs:element ref="InvoiceType"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $invoiceType
     * @since 1.0.0
     * @return void
     */
    public function setInvoiceType(InvoiceType $invoiceType): void
    {
        $this->invoiceType = $invoiceType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", $this->invoiceType->get()
                )
            );
    }

    /**
     * Get SpecialRegimes<br>
     * When this method is invoked a new instance of SpecialRegimes is created if
     * wasn't previous and returned to be populated<br>
     * &lt;xs:element name="SpecialRegimes" type="SpecialRegimes"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes
     * @since 1.0.0
     */
    public function getSpecialRegimes(): SpecialRegimes
    {
        if (isset($this->specialRegimes) === false) {
            $this->specialRegimes = new SpecialRegimes($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
        return $this->specialRegimes;
    }

    /**
     * Get if is set SpecialRegimes
     * @return bool
     * @since 1.0.0
     */
    public function issetSpecialRegimes(): bool
    {
        return isset($this->specialRegimes);
    }

    /**
     * Get ShipTo<br>
     * Information about the place and delivery date of the products
     * that are sold to the client, or anyone assigned by him in the
     * case of triangular transactions.<br>
     * When this method is invoked if $create is true and wasn't created
     * previous a new instance of ShipTo is created and then returned
     * to be populated<br>
     * &lt;xs:element ref="ShipTo" minOccurs="0" maxOccurs="1"/&gt;     *
     * @param bool $create if true and wasn't previous a new instance will be created
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
                \sprintf(
                    __METHOD__." get '%s'", "ShipTo"
                )
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
     * Information about the place and date of the shipping
     * of the goods sold to the customer.<br><br>
     * When this method is invoked if $create is true and wasn't created
     * previous a new instance of ShipFrom is created and then returned
     * to be populated<br>
     * &lt;xs:element ref="ShipFrom" minOccurs="0" maxOccurs="1"/&gt;  *
     * @param bool $create if true and wasn't previous a new instance will be created
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
     * [Date and time of the end of the transport]<br>
     * Date and time type “YYYY-MM-DDThh:mm:ss”, “ss” may be “00”
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
                        "null" :
                        $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
            );
        return $this->movementEndTime;
    }

    /**
     * Get MovementEndTime<br>
     * [Date and time of the end of the transport]<br>
     * Date and time type “YYYY-MM-DDThh:mm:ss”, “ss” may be “00”
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
                        "null" :
                        $this->movementEndTime->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get MovementStartTime<br>
     * [Date and time of the beginning of the transport]<br>
     * Date and time type “YYYY-MM-DDThh:mm:ss”, “ss” may be “00” if no
     * specific information is available.
     * The filling is required if the document also serves as a
     * transportation document as provided for under
     * the “Regime de bens em Circulação”<br>
     * [Goods Circulation Regime], approved by Decree No. 147/2003 of 11th July.
     * &lt;xs:element ref="MovementStartTime" minOccurs="0"  maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementStartTime" type="SAFdateTimeType"/&gt;
     * @return \Rebelo\Date\Date|null
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function getMovementStartTime(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->movementStartTime === null ? "null" :
                        $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
            );
        return $this->movementStartTime;
    }

    /**
     * Set MovementStartTime<br>
     * [Date and time of the beginning of the transport]<br>
     * Date and time type “YYYY-MM-DDThh:mm:ss”, “ss” may be “00” if no
     * specific information is available.
     * The filling is required if the document also serves as a
     * transportation document as provided for under
     * the “Regime de bens em Circulação”<br>
     * &lt;xs:element ref="MovementStartTime" maxOccurs="1"/&gt;<br>
     * &lt;xs:element name="MovementStartTime" type="SAFdateTimeType"/&gt;
     * @param \Rebelo\Date\Date|null $movementStartTime
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function setMovementStartTime(?RDate $movementStartTime): void
    {
        $this->movementStartTime = $movementStartTime;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->movementStartTime === null ? "null" :
                        $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
            );
    }

    /**
     * Get Line Stack<br>
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[]
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
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line
     * @since 1.0.0
     */
    public function addLine(): Line
    {
        $line         = new Line($this->getErrorRegistor());
        $this->line[] = $line;
        $line->setLineNumber(\count($this->line));
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." Line add to index "
        );
        return $line;
    }

    /**
     * Set DocumentTotals<br>
     * When this method is invoked a new instance of DocumentTotals is created
     * if wasn't previous and then returned to be populated<br>
     * &lt;xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        if (isset($this->documentTotals) === false) {
            $this->documentTotals = new DocumentTotals($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
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
     * Adds as withholdingTax <br>
     * When this method is invoked a new instance of WithholdingTax is created,
     * add to the stack  and returned to be populated<br>
     * &lt;xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addWithholdingTax(): WithholdingTax
    {
        $withholdingTax         = new WithholdingTax($this->getErrorRegistor());
        $this->withholdingTax[] = $withholdingTax;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__." WithholdingTax add to index"
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
            ->info(\sprintf(__METHOD__." get '%s'", "WithholdingTax"));
        return $this->withholdingTax;
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

        if ($node->getName() !== SalesInvoices::N_SALESINVOICES) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                SalesInvoices::N_SALESINVOICES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $invNode = $node->addChild(self::N_INVOICE);

        if (isset($this->invoiceNo)) {
            $invNode->addChild(static::N_INVOICENO, $this->getInvoiceNo());
        } else {
            $invNode->addChild(static::N_INVOICENO);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceNo_not_valid");
        }

        if (isset($this->atcud)) {
            $invNode->addChild(static::N_ATCUD, $this->getAtcud());
        } else {
            $invNode->addChild(static::N_ATCUD);
            $this->getErrorRegistor()->addOnCreateXmlNode("Atcud_not_valid");
        }

        if (isset($this->documentStatus)) {
            $this->getDocumentStatus()->createXmlNode($invNode);
        } else {
            $invNode->addChild(DocumentStatus::N_INVOICESTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentStatus_not_valid");
        }

        if (isset($this->hash)) {
            $invNode->addChild(static::N_HASH, $this->getHash());
        } else {
            $invNode->addChild(static::N_HASH);
            $this->getErrorRegistor()->addOnCreateXmlNode("Hash_not_valid");
        }

        if (isset($this->hashControl)) {
            $invNode->addChild(static::N_HASHCONTROL, $this->getHashControl());
        } else {
            $invNode->addChild(static::N_HASHCONTROL);
            $this->getErrorRegistor()->addOnCreateXmlNode("HashControl_not_valid");
        }

        if ($this->getPeriod() !== null) {
            $invNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }

        if (isset($this->invoiceDate)) {
            $invNode->addChild(
                static::N_INVOICEDATE,
                $this->getInvoiceDate()->format(RDate::SQL_DATE)
            );
        } else {
            $invNode->addChild(static::N_INVOICEDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceDate_not_valid");
        }

        if (isset($this->invoiceType)) {
            $invNode->addChild(
                static:: N_INVOICETYPE, $this->getInvoiceType()->get()
            );
        } else {
            $invNode->addChild(static::N_INVOICETYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceType_not_valid");
        }

        if (isset($this->specialRegimes)) {
            $this->getSpecialRegimes()->createXmlNode($invNode);
        } else {
            $invNode->addChild(static::N_SPECIALREGIMES);
            $this->getErrorRegistor()->addOnCreateXmlNode("SpecialRegimes_not_valid");
        }

        if (isset($this->sourceID)) {
            $invNode->addChild(static:: N_SOURCEID, $this->getSourceID());
        } else {
            $invNode->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SpecialRegimes_not_valid");
        }

        if ($this->getEacCode() !== null) {
            $invNode->addChild(static:: N_EACCODE, $this->getEacCode());
        }

        if (isset($this->systemEntryDate)) {
            $invNode->addChild(
                static::N_SYSTEMENTRYDATE,
                $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $invNode->addChild(static::N_SYSTEMENTRYDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("SystemEntryDate_not_valid");
        }

        $this->getTransactionID(false)?->createXmlNode($invNode);

        if (isset($this->customerID)) {
            $invNode->addChild(static:: N_CUSTOMERID, $this->getCustomerID());
        } else {
            $invNode->addChild(static::N_CUSTOMERID);
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerID_not_valid");
        }

        $this->getShipTo(false)?->createXmlNode($invNode);
        $this->getShipFrom(false)?->createXmlNode($invNode);

        if ($this->getMovementEndTime() !== null) {
            $invNode->addChild(
                static::N_MOVEMENTENDTIME,
                $this->getMovementEndTime()->format(RDate::DATE_T_TIME)
            );
        }

        if ($this->getMovementStartTime() !== null) {
            $invNode->addChild(
                static::N_MOVEMENTSTARTTIME,
                $this->getMovementStartTime()->format(RDate::DATE_T_TIME)
            );
        }

        if (\count($this->line) === 0) {
            $msg = "Invoice without lines";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("Invoice_without_lines");
        }

        foreach ($this->getLine() as $line) {
            $line->createXmlNode($invNode);
        }

        if (isset($this->documentTotals)) {
            $this->getDocumentTotals()->createXmlNode($invNode);
        } else {
            $invNode->addChild(DocumentTotals::N_DOCUMENTTOTALS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentTotals_not_valid");
        }

        foreach ($this->getWithholdingTax() as $tax) {
            $tax->createXmlNode($invNode);
        }

        return $invNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_INVOICE) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s", static:: N_INVOICE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        parent::parseXmlNode($node);

        $this->setInvoiceNo((string) $node->{static::N_INVOICENO});
        $this->setInvoiceType(new InvoiceType((string) $node->{static::N_INVOICETYPE}));
        $this->getDocumentStatus()->parseXmlNode($node->{DocumentStatus::N_DOCUMENTSTATUS});

        $this->setInvoiceDate(
            RDate::parse(
                RDate::SQL_DATE, (string) $node->{static::N_INVOICEDATE}
            )
        );

        if ($node->{static::N_SPECIALREGIMES}->count() > 0) {
            $this->getSpecialRegimes()->parseXmlNode($node->{static::N_SPECIALREGIMES});
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

        if ($node->{static::N_MOVEMENTSTARTTIME}->count() > 0) {
            $this->setMovementStartTime(
                RDate::parse(
                    RDate::DATE_T_TIME,
                    (string) $node->{static::N_MOVEMENTSTARTTIME}
                )
            );
        }

        $nLine = $node->{Line::N_LINE}->count();
        for ($n = 0; $n < $nLine; $n++) {
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
}
