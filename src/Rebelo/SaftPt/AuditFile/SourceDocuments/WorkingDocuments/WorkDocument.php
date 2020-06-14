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
use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\SourceDocuments\ADocument;

/**
 * Description of WorkDocument
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
     * <xs:element ref="DocumentNumber"/>
     * @var String
     * @since 1.0.0
     */
    private string $documentNumber;

    /**
     * <xs:element name="DocumentStatus">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus
     * @since 1.0.0
     */
    private DocumentStatus $documentStatus;

    /**
     * <xs:element ref="WorkType"/>
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
     * <xs:element name="Line" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line[]
     * @since 1.0.0
     */
    private array $line = array();

    /**
     * <xs:element name="DocumentTotals">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals
     * @since 1.0.0
     */
    private DocumentTotals $documentTotals;

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set DocumentNumber<br>
     * <xs:element ref="DocumentNumber"/>
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
     * Set DocumentNumber<br>
     * <xs:element ref="DocumentNumber"/>
     * @param string $documentNumber
     * @return void
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
     * Get DocumentStatus<br>
     * <xs:element name="DocumentStatus">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus
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
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentStatus $documentStatus
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
     * Get WorkDate<br>
     * <xs:element ref="WorkDate"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getWorkDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'",
                    $this->workDate->format(RDate::SQL_DATE)
                )
        );
        return $this->workDate;
    }

    /**
     * Get WorkDate<br>
     * <xs:element ref="WorkDate"/>
     * @param \Rebelo\Date\Date $workDate
     * @return void
     * @since 1.0.0
     */
    public function setWorkDate(RDate $workDate): void
    {
        $this->workDate = $workDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'",
                    $this->workDate->format(RDate::SQL_DATE)
                )
        );
    }

    /**
     * Set WorkType<br>
     * <xs:element ref="WorkType"/>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType
     * @since 1.0.0
     */
    public function getWorkType(): WorkType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", $this->workType->get()
                )
        );
        return $this->workType;
    }

    /**
     * Get WorkType<br>
     * <xs:element ref="WorkType"/>
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkType $workType
     * @return void
     * @since 1.0.0
     */
    public function setWorkType(WorkType $workType): void
    {
        $this->workType = $workType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", $this->workType->get()
                )
        );
    }

    /**
     * Add Line to the stack
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\Line $line
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
            __METHOD__, "Line add to index ".\strval($index));
        return $index;
    }

    /**
     * isset line
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetLine(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
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
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->line[$index]);
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
     * <xs:element name="DocumentTotals">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                    __METHOD__." getted '%s'", "DocumentTotals"
                )
        );
        return $this->documentTotals;
    }

    /**
     * Set DocumentTotals<br>
     * <xs:element name="DocumentTotals">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\DocumentTotals $documentTotals
     * @return void
     * @since 1.0.0
     */
    public function setDocumentTotals(DocumentTotals $documentTotals): void
    {
        $this->documentTotals = $documentTotals;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                    __METHOD__." setted to '%s'", "DocumentTotals"
                )
        );
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
            $msg = sprintf("Node name should be '%s' but is '%s",
                WorkingDocuments::N_WORKINGDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $workNode = $node->addChild(static::N_WORKDOCUMENT);

        $workNode->addChild(static::N_DOCUMENTNUMBER, $this->getDocumentNumber());
        $workNode->addChild(static::N_ATCUD, $this->getAtcud());
        $this->getDocumentStatus()->createXmlNode($workNode);
        $workNode->addChild(static::N_HASH, $this->getHash());
        $workNode->addChild(static::N_HASHCONTROL, $this->getHashControl());
        if ($this->getPeriod() !== null) {
            $workNode->addChild(static::N_PERIOD, \strval($this->getPeriod()));
        }
        $workNode->addChild(
            static::N_WORKDATE, $this->getWorkDate()->format(RDate::SQL_DATE)
        );
        $workNode->addChild(static::N_WORKTYPE, $this->getWorkType()->get());
        $workNode->addChild(static::N_SOURCEID, $this->getSourceID());
        if ($this->getEacCode() !== null) {
            $workNode->addChild(static::N_EACCODE, $this->getEacCode());
        }
        $workNode->addChild(
            static::N_SYSTEMENTRYDATE,
            $this->getSystemEntryDate()->format(RDate::DATE_T_TIME)
        );
        if ($this->getTransactionID() !== null) {
            $this->getTransactionID()->createXmlNode($workNode);
        }

        $workNode->addChild(static::N_CUSTOMERID, $this->getCustomerID());

        if (\count($this->getLine()) === 0) {
            $msg = "Line stack in WorkDocument can not be empty";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        foreach ($this->getLine() as $line) {
            /* @var $line \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
            $line->createXmlNode($workNode);
        }

        $this->getDocumentTotals()->createXmlNode($workNode);

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
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_WORKDOCUMENT, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        parent::parseXmlNode($node);

        $this->setDocumentNumber((string) $node->{static::N_DOCUMENTNUMBER});
        $docStatus = new DocumentStatus();
        $docStatus->parseXmlNode($node->{DocumentStatus::N_DOCUMENTSTATUS});
        $this->setDocumentStatus($docStatus);
        $this->setWorkDate(
            RDate::parse(RDate::SQL_DATE, (string) $node->{static::N_WORKDATE})
        );
        $this->setWorkType(new WorkType((string) $node->{static::N_WORKTYPE}));
        for ($n = 0; $n < $node->{Line::N_LINE}->count(); $n++) {
            $line = new Line();
            $line->parseXmlNode($node->{Line::N_LINE}[$n]);
            $this->addToLine($line);
        }
        $docTotals = new DocumentTotals();
        $docTotals->parseXmlNode($node->{DocumentTotals::N_DOCUMENTTOTALS});
        $this->setDocumentTotals($docTotals);
    }
}