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

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 *
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
 * <p>
 * Chave única do movimento contabilístico (TransactionID)<br>
 * Deve ser construída de forma a ser única e a corresponder ao número
 * de documento contabilístico, que é  utilizado para detetar o documento
 * físico no arquivo, pelo que, deve resultar de uma concatenação,
 * separada por espaços, entre os seguintes valores: data do
 * documento, identificador do diário e número de arquivo do documento
 * (TransactionDate, JournalID e DocArchivalNumber).
 * </p>
 * @author João Rebelo
 * @since 1.0.0
 */
class TransactionID extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_TRANSACTIONID = "TransactionID";

    /**
     * Transaction date
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $date;

    /**
     * Regexp [^ ]{1,30}
     * @var string
     * @since 1.0.0
     */
    private string $journalID;

    /**
     * [^ ]{1,20}
     * @var String
     * @since 1.0.0
     */
    private string $docArchivalNumber;

    /**
     * TransactionID
     * <p>
     * The key must be built in such a way that it is unique and the only one that
     *  corresponds to the number of the accounting document,
     *  which is used to detect the physical document in the archive.
     *  So it shall result from linking together, separated by spaces,
     *  the following elements: TransactionDate, JournalID and DocArchivalNumber.
     * </p>
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
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get transaction date
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->date->format(RDate::SQL_DATE)
                )
            );
        return $this->date;
    }

    /**
     * Set transaction date
     * @param \Rebelo\Date\Date $date
     * @return void
     * @since 1.0.0
     */
    public function setDate(RDate $date): void
    {
        $this->date = $date;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->date->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Set Journal ID
     * Regexp [^ ]{1,30}
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getJournalID(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->journalID));
        return $this->journalID;
    }

    /**
     * Set Journal ID
     * Regexp [^ ]{1,30}
     * @param string $journalID
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setJournalID(string $journalID): bool
    {
        if (\preg_match("/^[^ ]{1,30}$/", $journalID) !== 1) {
            $msg    = "JournalID must respect regexp '^[^ ]{1,30}$'";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("JournalID_not_valid");
        } else {
            $return = true;
        }
        $this->journalID = $journalID;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->journalID));
        return $return;
    }

    /**
     * Get DocArchivalNumber<br>
     * [^ ]{1,20}
     * @return string
     * @since 1.0.0
     */
    public function getDocArchivalNumber(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->docArchivalNumber));
        return $this->docArchivalNumber;
    }

    /**
     * Set DocArchivalNumber<br>
     * [^ ]{1,20}
     * @param string $docArchivalNumber
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDocArchivalNumber(string $docArchivalNumber): bool
    {
        if (\preg_match("/^[^ ]{1,20}$/", $docArchivalNumber) !== 1) {
            $msg    = "DocArchivalNumber must respect regexp '^[^ ]{1,20}$'";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("DocArchivalNumber_not_valid");
        } else {
            $return = true;
        }
        $this->docArchivalNumber = $docArchivalNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->docArchivalNumber
                )
            );
        return $return;
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if (isset($this->date) && isset($this->docArchivalNumber) && isset($this->journalID)) {
            $tran = \sprintf(
                "%s %s %s", $this->getDate()->format(RDate::SQL_DATE),
                $this->getJournalID(), $this->getDocArchivalNumber()
            );
            return $node->addChild(static::N_TRANSACTIONID, $tran);
        }

        $this->getErrorRegistor()->addOnCreateXmlNode("TransactionID_not_valid");
        return $node->addChild(static::N_TRANSACTIONID);
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws AuditFileException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_TRANSACTIONID) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_TRANSACTIONID, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $tran = \explode(" ", (string) $node);
        $this->setDate(RDate::parse(RDate::SQL_DATE, $tran[0]));
        $this->setJournalID($tran[1]);
        $this->setDocArchivalNumber($tran[2]);
    }
}