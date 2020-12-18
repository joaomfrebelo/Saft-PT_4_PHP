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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocument;
use Rebelo\SaftPt\AuditFile\AAuditFile;

/**
 * WorkDocument
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkDocument extends ADocument
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const N_WORKDOCUMENT = "WorkDocument";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_DOCUMENTNUMBER = "DocumentNumber";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_WORKDATE = "WorkDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_WORKTYPE = "WorkType";

    /**
     * &lt;xs:element ref="DocumentNumber"/&gt;
     * @var String
     * @since 1.0.0
     */
    private string $documentNumber;

    /**
     * &lt;xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * &lt;xs:element ref="WorkType"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $workDate;

    /**
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType
     * @since 1.0.0
     */
    private WorkType $workType;

    /**
     * &lt;xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * &lt;xs:element name="DocumentTotals">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     * WorkDocument
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Set DocumentNumber<br>
     * This identification is a sequential composition of following elements:
     * the document type internal code, followed by a space, followed by the
     * identifier of the document series, followed by (/) and by a sequential
     * number of the document within the series.
     * Records with the same identification are not allowed in this field.
     * The same document type internal code cannot be used for different types of documents.<br>
     * &lt;xs:element ref="DocumentNumber"/&gt;
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getDocumentNumber(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->documentNumber));
        return $this->documentNumber;
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
     * Set DocumentNumber<br>
     * This identification is a sequential composition of following elements:
     * the document type internal code, followed by a space, followed by the
     * identifier of the document series, followed by (/) and by a sequential
     * number of the document within the series.
     * Records with the same identification are not allowed in this field.
     * The same document type internal code cannot be used for different types of documents.<br>
     * &lt;xs:element ref="DocumentNumber"/&gt;
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
     * Get DocumentStatus<br>
     * When this method is invoked will create a new instance of DocumentStatus
     * if not created previous is returned to be populated<br>
     * The field must be filled in with:<br>
      “N” - Normal;<br>
      “A” - Cancelled document;<br>
      “F” - Billed document, even if partially, when for the same document
     * there is also on table 4.1. – SalesInvoices, the corresponding
     * invoice or simplified invoice.<br>
     * &lt;xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus
     * @since 1.0.0
     */
    public function getDocumentStatus(): DocumentStatus
    {
        if (isset($this->documentStatus) === false) {
            $this->documentStatus = new DocumentStatus($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "DocumentSatus"));
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
     * Get WorkDate<br>
     * Date of the last storage of the document status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.<br>
     * &lt;xs:element ref="WorkDate"/&gt;
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->workDate->format(RDate::SQL_DATE)
                )
            );
        return $this->workDate;
    }

    /**
     * Get if is set WorkDate
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkDate(): bool
    {
        return isset($this->workDate);
    }

    /**
     * Set WorkDate<br>
     * Date of the last storage of the document status to the second.
     * Date and time type: “YYYY-MM-DDThh:mm:ss”.<br>
     * &lt;xs:element ref="WorkDate"/&gt;
     * @param \Rebelo\Date\Date $workDate
     * @return void
     * @since 1.0.0
     */
    public function setWorkDate(RDate $workDate): void
    {
        $this->workDate = $workDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->workDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Set WorkType<br><br>
     * The field shall be filled in with:<br>
     * “CM” – Table checks;<br>
     * “CC” – Consignment credit note;<br>
     * “FC” – Consignment invoice according to art. 38 of the Portuguese VAT Code;<br>
     * “FO” – Worksheets [to record the service rendered or the work performed];<br>
     * “NE” – Purchase order;<br>
     * “OU” – Others [not specified in the remaining WorkTypes];<br>
     * “OR” – Budgets;<br>
     * “PF” – Pro forma invoice;<br>
     * “DC” - Issued documents likely to be presented to the customer for the purpose of checking goods or provision of services (for data until 2017-06-30).<br>
     * For the insurance sector as to the types of documents identified below must also exist in table 4.1. - SalesInvoices the corresponding invoice or invoice amending document, can also be filled with:<br>
     * “RP” – Premium or Premium receipt;<br>
     * “RE” - Return insurance or receipt of return insurance;<br>
     * “CS” - Imputation to co-insurance companies;<br>
     * “LD” - Imputation to a leader co-insurance company;<br>
     * “RA” - Accepted reinsurance.<br>
     * &lt;xs:element ref="WorkType"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkType(): WorkType
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'", $this->workType->get()
                )
            );
        return $this->workType;
    }

    /**
     * Get if is set WorkType
     * @return bool
     * @since 1.0.0
     */
    public function issetWorkType(): bool
    {
        return isset($this->workType);
    }

    /**
     * Get WorkType<br><br>
     * The field shall be filled in with:<br>
     * “CM” – Table checks;<br>
     * “CC” – Consignment credit note;<br>
     * “FC” – Consignment invoice according to art. 38 of the Portuguese VAT Code;<br>
     * “FO” – Worksheets [to record the service rendered or the work performed];<br>
     * “NE” – Purchase order;<br>
     * “OU” – Others [not specified in the remaining WorkTypes];<br>
     * “OR” – Budgets;<br>
     * “PF” – Pro forma invoice;<br>
     * “DC” - Issued documents likely to be presented to the customer for the purpose of checking goods or provision of services (for data until 2017-06-30).<br>
     * For the insurance sector as to the types of documents identified below must also exist in table 4.1. - SalesInvoices the corresponding invoice or invoice amending document, can also be filled with:<br>
     * “RP” – Premium or Premium receipt;<br>
     * “RE” - Return insurance or receipt of return insurance;<br>
     * “CS” - Imputation to co-insurance companies;<br>
     * “LD” - Imputation to a leader co-insurance company;<br>
     * “RA” - Accepted reinsurance.<br>
     * &lt;xs:element ref="WorkType"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType $workType
     * @return void
     * @since 1.0.0
     */
    public function setWorkType(WorkType $workType): void
    {
        $this->workType = $workType;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'", $this->workType->get()
                )
            );
    }

    /**
     * Add Line<br>
     * This method when is invoke will create a new Line instace and add to satck
     * then will be returned to be populated. The line number is set automatacly
     * but you can set other.
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line
     * @since 1.0.0
     */
    public function addLine(): Line
    {
        $line         = new Line($this->getErrorRegistor());
        $this->line[] = $line;
        $line->setLineNumber(\count($this->line));
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__."Line add to stack"
        );
        return $line;
    }

    /**
     * Get Line Stack
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line[]
     * @since 1.0.0
     */
    public function getLine(): array
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return $this->line;
    }

    /**
     * Get DocumentTotals<br>
     * When this method is invoked will create a new instance of DocumentTotals
     * if nor created previous is returned to be populated<br>
     * &lt;xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals
     * @throws \Error
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        if (isset($this->documentTotals) === false) {
            $this->documentTotals = new DocumentTotals($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'", "DocumentTotals"
                )
            );
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
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== WorkingDocuments::N_WORKINGDOCUMENTS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                WorkingDocuments::N_WORKINGDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $workNode = $node->addChild(static::N_WORKDOCUMENT);

        if (isset($this->documentNumber)) {
            $workNode->addChild(
                static::N_DOCUMENTNUMBER, $this->getDocumentNumber()
            );
        } else {
            $workNode->addChild(static::N_DOCUMENTNUMBER);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentNumber_not_valid");
        }

        if (isset($this->atcud)) {
            $workNode->addChild(static::N_ATCUD, $this->getAtcud());
        } else {
            $workNode->addChild(static::N_ATCUD);
            $this->getErrorRegistor()->addOnCreateXmlNode("Atcud_not_valid");
        }

        if (isset($this->documentStatus)) {
            $this->getDocumentStatus()->createXmlNode($workNode);
        } else {
            $workNode->addChild(DocumentStatus::N_DOCUMENTSTATUS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentStatus_not_valid");
        }

        if (isset($this->hash)) {
            $workNode->addChild(static::N_HASH, $this->getHash());
        } else {
            $workNode->addChild(static::N_HASH);
            $this->getErrorRegistor()->addOnCreateXmlNode("Hash_not_valid");
        }

        if (isset($this->hashControl)) {
            $workNode->addChild(static::N_HASHCONTROL, $this->getHashControl());
        } else {
            $workNode->addChild(static::N_HASHCONTROL);
            $this->getErrorRegistor()->addOnCreateXmlNode("HashControl_not_valid");
        }

        if ($this->getPeriod() !== null) {
            $workNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }

        if (isset($this->workDate)) {
            $workNode->addChild(
                static::N_WORKDATE,
                $this->getWorkDate()->format(RDate::SQL_DATE)
            );
        } else {
            $workNode->addChild(static::N_WORKDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkDate_not_valid");
        }

        if (isset($this->workType)) {
            $workNode->addChild(static::N_WORKTYPE, $this->getWorkType()->get());
        } else {
            $workNode->addChild(static::N_WORKTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkType_not_valid");
        }

        if (isset($this->sourceID)) {
            $workNode->addChild(static::N_SOURCEID, $this->getSourceID());
        } else {
            $workNode->addChild(static::N_SOURCEID);
            $this->getErrorRegistor()->addOnCreateXmlNode("SourceID_not_valid");
        }

        if ($this->getEacCode() !== null) {
            $workNode->addChild(static::N_EACCODE, $this->getEacCode());
        }

        if (isset($this->systemEntryDate)) {
            $workNode->addChild(
                static::N_SYSTEMENTRYDATE,
                $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
            );
        } else {
            $workNode->addChild(static::N_SYSTEMENTRYDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("SystemEntryDate_not_valid");
        }

        if ($this->getTransactionID(false) !== null) {
            $this->getTransactionID()->createXmlNode($workNode);
        }

        if (isset($this->customerID)) {
            $workNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());
        } else {
            $workNode->addChild(static::N_CUSTOMERID);
            $this->getErrorRegistor()->addOnCreateXmlNode("CustomerID_not_valid");
        }

        if (\count($this->getLine()) === 0) {
            $msg = "Line stack in WorkDocument can not be empty";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("WorkDocument_without_lines");
        }

        foreach ($this->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
            $line->createXmlNode($workNode);
        }

        if (isset($this->documentTotals)) {
            $this->getDocumentTotals()->createXmlNode($workNode);
        } else {
            $workNode->addChild(DocumentTotals::N_DOCUMENTTOTALS);
            $this->getErrorRegistor()->addOnCreateXmlNode("DocumentTotals_not_valid");
        }

        return $workNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_WORKDOCUMENT) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_WORKDOCUMENT, $node->getName()
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

        $this->setWorkDate(
            RDate::parse(RDate::SQL_DATE, (string) $node->{static::N_WORKDATE})
        );

        $this->setWorkType(new WorkType((string) $node->{static::N_WORKTYPE}));

        for ($n = 0; $n < $node->{Line::N_LINE}->count(); $n++) {
            $this->addLine()->parseXmlNode($node->{Line::N_LINE}[$n]);
        }

        $this->getDocumentTotals()->parseXmlNode(
            $node->{DocumentTotals::N_DOCUMENTTOTALS}
        );
    }
}
