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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\{
    AuditFileException,
    SourceDocuments\ShipFrom,
    SourceDocuments\ShipTo,
    SourceDocuments\WithholdingTax
};

/**
 * Description of Invoice
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Invoice extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ADocument
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
     * <xs:element ref="InvoiceNo"/>
     * @var string
     * @since 1.0.0
     */
    private string $invoiceNo;

    /**
     * <xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * <xs:element ref="InvoiceDate"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $invoiceDate;

    /**
     * <xs:element ref="InvoiceType"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType
     * @since 1.0.0
     */
    private InvoiceType $invoiceType;

    /**
     * <xs:element name="SpecialRegimes" type="SpecialRegimes"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes
     * @since 1.0.0
     */
    private SpecialRegimes $specialRegimes;

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
     * <xs:element ref="MovementEndTime" minOccurs="0" maxOccurs="1"/>
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementEndTime = null;

    /**
     * <xs:element ref="MovementStartTime" minOccurs="0" maxOccurs="1"/>
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $movementStartTime = null;

    /**
     * <xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * <xs:element name="DocumentTotals"><br>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * <xs:element name="WithholdingTax" type="WithholdingTax" minOccurs="0" maxOccurs="unbounded"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WithholdingTax[]
     * @since 1.0.0
     */
    private array $withholdingTax = array();

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get InvoiceNo<br>
     * <xs:element ref="InvoiceNo"/>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceNo(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", $this->invoiceNo
        ));
        return $this->invoiceNo;
    }

    /**
     * Set InvoiceNo<br>
     * <xs:element ref="InvoiceNo"/>
     * @param string $invoiceNo
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setInvoiceNo(string $invoiceNo): void
    {
        if (\strlen($invoiceNo) > 60 ||
            \strlen($invoiceNo) < 1 ||
            \preg_match("/[^ ]+ [^\/^ ]+\/[0-9]+/", $invoiceNo) !== 1
        ) {
            $msg = "DocumentNumber length must be between 1 and 60 and must respect regexp";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->invoiceNo = $invoiceNo;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->invoiceNo
        ));
    }

    /**
     * Get DocumentStatus
     * <xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus
     * @throws \Error
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->documentStatus;
    }

    /**
     * Set DocumentStatus
     * <xs:element name="DocumentStatus">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentStatus $documentStatus
     * @return void
     * @since 1.0.0
     */
    public function setDocumentStatus(DocumentStatus $documentStatus): void
    {
        $this->documentStatus = $documentStatus;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
    }

    /**
     * Get InvoiceDate<br>
     * <xs:element ref="InvoiceDate"/>
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)
        ));
        return $this->invoiceDate;
    }

    /**
     * Set InvoiceDate<br>
     * <xs:element ref="InvoiceDate"/>
     * @param \Rebelo\Date\Date $invoiceDate
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceDate(RDate $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)
        ));
    }

    /**
     * Get InvoiceType<br>
     * <xs:element ref="InvoiceType"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType
     * @throws \Error
     * @since 1.0.0
     */
    public function getInvoiceType(): InvoiceType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", $this->invoiceType->get()
        ));
        return $this->invoiceType;
    }

    /**
     * Set InvoiceType<br>
     * <xs:element ref="InvoiceType"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\InvoiceType $invoiceType
     * @since 1.0.0
     * @return void
     */
    public function setInvoiceType(InvoiceType $invoiceType): void
    {
        $this->invoiceType = $invoiceType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->invoiceType->get()
        ));
    }

    /**
     * Get SpecialRegimes<br>
     * <xs:element name="SpecialRegimes" type="SpecialRegimes"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes
     * @throws \Error
     * @since 1.0.0
     */
    public function getSpecialRegimes(): SpecialRegimes
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->specialRegimes;
    }

    /**
     * Set SpecialRegimes<br>
     * <xs:element name="SpecialRegimes" type="SpecialRegimes"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SpecialRegimes $specialRegimes
     * @since 1.0.0
     * @return void
     */
    public function setSpecialRegimes(SpecialRegimes $specialRegimes): void
    {
        $this->specialRegimes = $specialRegimes;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
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
     * <xs:element ref="MovementStartTime" minOccurs="0"  maxOccurs="1"/><br>
     * <xs:element name="MovementStartTime" type="SAFdateTimeType"/>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getMovementStartTime(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->movementStartTime === null ? "null" :
                        $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
        );
        return $this->movementStartTime;
    }

    /**
     * Set MovementStartTime<br>
     * <xs:element ref="MovementStartTime" maxOccurs="1"/><br>
     * <xs:element name="MovementStartTime" type="SAFdateTimeType"/>
     * @param \Rebelo\Date\Date|null $movementStartTime
     * @return void
     * @since 1.0.0
     */
    public function setMovementStartTime(?RDate $movementStartTime): void
    {
        $this->movementStartTime = $movementStartTime;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->movementStartTime === null ? "null" :
                        $this->movementStartTime->format(RDate::DATE_T_TIME)
                )
        );
    }

    /**
     * Get Line Stack<br>
     * <xs:element name="Line" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line[]
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line $line
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
     * Set DocumentTotals<br>
     * <xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->documentTotals;
    }

    /**
     * Set DocumentTotals<br>
     * <xs:element name="DocumentTotals">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\DocumentTotals $documentTotals
     * @since 1.0.0
     * @return void
     */
    public function setDocumentTotals(DocumentTotals $documentTotals): void
    {
        $this->documentTotals = $documentTotals;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
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
     * @throws \Error
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== SalesInvoices::N_SALESINVOICES) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                SalesInvoices::N_SALESINVOICES, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $invNode = $node->addChild(self::N_INVOICE);
        $invNode->addChild(static::N_INVOICENO, $this->getInvoiceNo());
        $invNode->addChild(static::N_ATCUD, $this->getAtcud());
        $this->getDocumentStatus()->createXmlNode($invNode);
        $invNode->addChild(static::N_HASH, $this->getHash());
        $invNode->addChild(static::N_HASHCONTROL, $this->getHashControl());
        if ($this->getPeriod() !== null) {
            $invNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }
        $invNode->addChild(
            static::N_INVOICEDATE,
            $this->getInvoiceDate()->format(RDate::SQL_DATE)
        );
        $invNode->addChild(static::N_INVOICETYPE, $this->getInvoiceType()->get());
        $this->getSpecialRegimes()->createXmlNode($invNode);
        $invNode->addChild(static::N_SOURCEID, $this->getSourceID());
        if ($this->getEacCode() !== null) {
            $invNode->addChild(static::N_EACCODE, $this->getEacCode());
        }
        $invNode->addChild(
            static::N_SYSTEMENTRYDATE,
            $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
        );
        if ($this->getTransactionID() !== null) {
            $this->getTransactionID()->createXmlNode($invNode);
        }
        $invNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        if ($this->getShipTo() !== null) {
            $this->getShipTo()->createXmlNode($invNode);
        }
        if ($this->getShipFrom() !== null) {
            $this->getShipFrom()->createXmlNode($invNode);
        }
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
            throw new AuditFileException($msg);
        }

        foreach ($this->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\Line */
            $line->createXmlNode($invNode);
        }

        $this->getDocumentTotals()->createXmlNode($invNode);

        foreach ($this->getWithholdingTax() as $tax) {
            /* @var $tax WithholdingTax */
            $tax->createXmlNode($invNode);
        }

        return $invNode;
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

        if ($node->getName() !== static::N_INVOICE) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_INVOICE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        parent::parseXmlNode($node);
        $this->setInvoiceNo((string) $node->{static::N_INVOICENO});
        $this->setInvoiceType(new InvoiceType((string) $node->{static::N_INVOICETYPE}));
        $status = new DocumentStatus();
        $status->parseXmlNode($node->{DocumentStatus::N_DOCUMENTSTATUS});
        $this->setDocumentStatus($status);
        $this->setInvoiceDate(
            RDate::parse(RDate::SQL_DATE,
                (string) $node->{static::N_INVOICEDATE})
        );
        if ($node->{static::N_SPECIALREGIMES}->count() > 0) {
            $spcReg = new SpecialRegimes();
            $spcReg->parseXmlNode($node->{static::N_SPECIALREGIMES});
            $this->setSpecialRegimes($spcReg);
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
            $line = new Line();
            $line->parseXmlNode($node->{Line::N_LINE}[$n]);
            $this->addToLine($line);
        }

        $totals = new DocumentTotals();
        $totals->parseXmlNode($node->{DocumentTotals::N_DOCUMENTTOTALS});
        $this->setDocumentTotals($totals);

        $whtCount = $node->{WithholdingTax::N_WITHHOLDINGTAX}->count();
        for ($n = 0; $n < $whtCount; $n++) {
            $tax = new WithholdingTax();
            $tax->parseXmlNode($node->{WithholdingTax::N_WITHHOLDINGTAX}[$n]);
            $this->addToWithholdingTax($tax);
        }
    }
}