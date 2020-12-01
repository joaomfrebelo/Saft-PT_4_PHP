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

use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\SourceDocuments;
use Rebelo\SaftPt\AuditFile\AuditFile;

/**
 * 4.3 - WorkingDocuments.<br>
 * In this table shall be exported any other documents issued,
 * apart from its designation, likely to be presented to the costumer for
 * the purpose of checking goods or provision of services,
 * even when subject to later invoicing.
 * This table shall not include the documents required to be exported in Tables
 * 4.1. – SalesInvoices or 4.2 – MovementOfGoods.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class WorkingDocuments extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ASourceDocuments
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_WORKINGDOCUMENTS = "WorkingDocuments";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_NUMBEROFENTRIES = "NumberOfEntries";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALDEBIT = "TotalDebit";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TOTALCREDIT = "TotalCredit";

    /**
     * &lt;xs:element ref="NumberOfEntries"/&gt;
     * @var int
     * @since 1.0.0
     */
    protected int $numberOfEntries;

    /**
     * &lt;xs:element ref="TotalDebit"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $totalDebit;

    /**
     * &lt;xs:element ref="TotalCredit"/&gt;
     * @var float
     * @since 1.0.0
     */
    protected float $totalCredit;

    /**
     * &lt;xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[]
     * @since 1.0.0
     */
    protected array $workDocument = array();

    /**
     * $array[type][serie][number] = $workDocument
     * \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[]
     * @var array
     */
    protected array $order = array();

    /**
     *
     * 4.3 - WorkingDocuments.<br>
     * In this table shall be exported any other documents issued,
     * apart from its designation, likely to be presented to the costumer for
     * the purpose of checking goods or provision of services,
     * even when subject to later invoicing.
     * This table shall not include the documents required to be exported in Tables
     * 4.1. – SalesInvoices or 4.2 – MovementOfGoods.<br>
     * <pre>
     * &lt;xs:element name="WorkingDocuments" minOccurs="0"&gt;
     * &lt;xs:complexType&gt;
     *     &lt;xs:sequence&gt;
     *         &lt;xs:element ref="NumberOfEntries"/&gt;
     *         &lt;xs:element ref="TotalDebit"/&gt;
     *         &lt;xs:element ref="TotalCredit"/&gt;
     *         &lt;xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded"&gt;&lt;/xs:element&gt;
     *     &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get NumberOfEntries<br>
     * The field shall contain the total number of documents,
     * including documents which content in field 4.3.4.3.1. – WorkStatus is “A”.<br>
     * &lt;xs:element ref="NumberOfEntries"/&gt;
     * @return int
     * @throws \Error
     * @since 1.0.0
     */
    public function getNumberOfEntries(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->numberOfEntries)
                )
            );
        return $this->numberOfEntries;
    }

    /**
     * Get if is set NumberOfEntries
     * @return bool
     * @since 1.0.0
     */
    public function issetNumberOfEntries(): bool
    {
        return isset($this->numberOfEntries);
    }

    /**
     * Set NumberOfEntries<br>NumberOfEntries<br>
     * The field shall contain the total number of documents,
     * including documents which content in field 4.3.4.3.1. – WorkStatus is “A”.<br>
     * &lt;xs:element ref="NumberOfEntries"/&gt;
     * @param int $numberOfEntries
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setNumberOfEntries(int $numberOfEntries): bool
    {
        if ($numberOfEntries < 0) {
            $msg    = "NumberOfEntries can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("NumberOfEntries_not_valid");
        } else {
            $return = true;
        }
        $this->numberOfEntries = $numberOfEntries;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->numberOfEntries)
                )
            );
        return $return;
    }

    /**
     * Set TotalDebit<br>
     * The field shall contain the control
     * sum of the field 4.3.4.14.13. -DebitAmount, excluding the documents
     * which content in field 4.3.4.3.1. - WorkStatus is “A”.<br>
     * &lt;xs:element ref="TotalDebit"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalDebit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->totalDebit)
                )
            );
        return $this->totalDebit;
    }

    /**
     * Get if is set TotalDebit
     * @return bool
     * @since 1.0.0
     */
    public function issetTotalDebit(): bool
    {
        return isset($this->totalDebit);
    }

    /**
     * Get TotalDebit<br>
     * The field shall contain the control
     * sum of the field 4.3.4.14.13. -DebitAmount, excluding the documents
     * which content in field 4.3.4.3.1. - WorkStatus is “A”.<br>
     * &lt;xs:element ref="TotalDebit"/&gt;
     * @param float $totalDebit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalDebit(float $totalDebit): bool
    {
        if ($totalDebit < 0) {
            $msg    = "TotalDebit can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalDebit_not_valid");
        } else {
            $return = true;
        }
        $this->totalDebit = $totalDebit;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->totalDebit)
                )
            );
        return $return;
    }

    /**
     * Set TotalCredit<br>
     * The field shall contain the control sum of the
     * field 4.3.4.14.14. -CreditAmount, excluding the documents which
     * content in field 4.3.4.3.1. - WorkStatus is “A”.<br>
     * &lt;xs:element ref="TotalCredit"/&gt;
     * @return float
     * @throws \Error
     * @since 1.0.0
     */
    public function getTotalCredit(): float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    \strval($this->totalCredit)
                )
            );
        return $this->totalCredit;
    }

    /**
     * Get if is set TotalCredit
     * @return bool
     * @since 1.0.0
     */
    public function issetTotalCredit(): bool
    {
        return isset($this->totalCredit);
    }

    /**
     * Get TotalCredit<br>
     * The field shall contain the control sum of the
     * field 4.3.4.14.14. -CreditAmount, excluding the documents which
     * content in field 4.3.4.3.1. - WorkStatus is “A”.<br>
     * &lt;xs:element ref="TotalCredit"/&gt;
     * @param float $totalCredit
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTotalCredit(float $totalCredit): bool
    {
        if ($totalCredit < 0) {
            $msg    = "TotalCredit can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TotalCredit_not_valid");
        } else {
            $return = true;
        }
        $this->totalCredit = $totalCredit;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    \strval($this->totalCredit)
                )
            );
        return $return;
    }

    /**
     * Get WorkDocument Stack<br>
     * &lt;xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument[]
     * @since 1.0.0
     */
    public function getWorkDocument(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", "WorkDocument stack"));
        return $this->workDocument;
    }

    /**
     * When this method is invoked a new instance of WorkDocument will be created
     * and returned to be populate<br>
     * &lt;xs:element name="WorkDocument" minOccurs="0" maxOccurs="unbounded">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument
     * @since 1.0.0
     */
    public function addWorkDocument(): WorkDocument
    {
        // Every time that a workdocument is add the order is reseted and is
        // contructed when called
        $this->order          = array();
        $workDocument         = new WorkDocument($this->getErrorRegistor());
        $this->workDocument[] = $workDocument;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__."WorkDocument add to stack "
        );
        return $workDocument;
    }


    /**
     * Get invoices order by type/serie/number<br>
     * Ex: $stack[type][serie][InvoiceNo] = Invvoice<br>
     * If a error exist, th error is add to ValidationErrors stack
     * @return array<string, array<string , array<int, \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument>>>
     * @since 1.0.0
     */
    public function getOrder(): array
    {
        if (\count($this->order) > 0) {
            return $this->order;
        }

        foreach ($this->getWorkDocument() as $k => $workDoc) {
            /* @var $workDoc \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkDocument */
            if ($workDoc->issetDocumentNumber() === false) {
                $msg = \sprintf(
                    AuditFile::getI18n()->get("workdoc_at_index_no_number"), $k
                );
                $this->getErrorRegistor()->addValidationErrors($msg);
                $workDoc->addError($msg, WorkDocument::N_DOCUMENTNUMBER);
                \Logger::getLogger(\get_class($this))->error($msg);
                continue;
            }

            list($type, $serie, $no) = \explode(
                " ", \str_replace("/", " ", $workDoc->getDocumentNumber())
            );

            $type = \strval($type);
            $serie = \strval($serie);
            
            if (\array_key_exists($type, $this->order)) {
                if (\array_key_exists($serie, $this->order[$type])) {
                    if (\array_key_exists(
                        \intval($no), $this->order[$type][$serie]
                    )
                    ) {
                        $msg = \sprintf(
                            AuditFile::getI18n()->get("duplicated_workdoc"),
                            $workDoc->getDocumentNumber()
                        );
                        $this->getErrorRegistor()->addValidationErrors($msg);
                        $workDoc->addError($msg, WorkDocument::N_DOCUMENTNUMBER);
                        \Logger::getLogger(\get_class($this))->error($msg);
                    }
                }
            }
            $this->order[$type][$serie][\intval($no)] = $workDoc;
        }

        $cloneOrder = $this->order;

        foreach (\array_keys($cloneOrder) as $type) {
            foreach (\array_keys($cloneOrder[$type]) as $serie) {
                ksort($this->order[$type][$serie], SORT_NUMERIC);
            }
            ksort($this->order[$type], SORT_STRING);
        }
        ksort($this->order, SORT_STRING);

        return $this->order;
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

        if ($node->getName() !== SourceDocuments::N_SOURCEDOCUMENTS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                SourceDocuments::N_SOURCEDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $workingNode = $node->addChild(static::N_WORKINGDOCUMENTS);

        if (isset($this->numberOfEntries)) {
            $workingNode->addChild(
                static::N_NUMBEROFENTRIES, \strval($this->getNumberOfEntries())
            );
        } else {
            $workingNode->addChild(static::N_NUMBEROFENTRIES);
            $this->getErrorRegistor()->addOnCreateXmlNode("NumberOfEntries_not_valid");
        }

        if (isset($this->totalDebit)) {
            $workingNode->addChild(
                static::N_TOTALDEBIT, $this->floatFormat($this->getTotalDebit())
            );
        } else {
            $workingNode->addChild(static::N_TOTALDEBIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalDebit_not_valid");
        }

        if (isset($this->totalCredit)) {
            $workingNode->addChild(
                static::N_TOTALCREDIT,
                $this->floatFormat($this->getTotalCredit())
            );
        } else {
            $workingNode->addChild(static::N_TOTALCREDIT);
            $this->getErrorRegistor()->addOnCreateXmlNode("TotalCredit_not_valid");
        }

        foreach ($this->getWorkDocument() as $workDocument) {
            /* @var $workDocument WorkDocument */
            $workDocument->createXmlNode($workingNode);
        }

        return $workingNode;
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_WORKINGDOCUMENTS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_WORKINGDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setNumberOfEntries((int) $node->{static::N_NUMBEROFENTRIES});
        $this->setTotalDebit((float) $node->{static::N_TOTALDEBIT});
        $this->setTotalCredit((float) $node->{static::N_TOTALCREDIT});

        $nMax = $node->{WorkDocument::N_WORKDOCUMENT}->count();
        for ($n = 0; $n < $nMax; $n++) {
            $this->addWorkDocument()->parseXmlNode(
                $node->{WorkDocument::N_WORKDOCUMENT}[$n]
            );
        }
    }
}