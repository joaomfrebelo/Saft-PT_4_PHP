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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\SourceDocuments\{
    SalesInvoices\SalesInvoices,
    MovementOfGoods\MovementOfGoods,
    WorkingDocuments\WorkingDocuments,
    Payments\Payments
};

/**
 * Description of SourceDocuments
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SourceDocuments extends AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEDOCUMENTS = "SourceDocuments";

    /**
     *  &lt;xs:element name="SalesInvoices" minOccurs="0"&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices|null
     * @since 1.0.0
     */
    private ?SalesInvoices $salesInvoices = null;

    /**
     * &lt;xs:element name="MovementOfGoods" minOccurs="0"&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods|null
     * @since 1.0.0
     */
    private ?MovementOfGoods $movementOfGoods = null;

    /**
     * &lt;xs:element name="WorkingDocuments" minOccurs="0"&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments|null
     * @since 1.0.0
     */
    private ?WorkingDocuments $workingDocuments = null;

    /**
     * &lt;xs:element name="Payments" minOccurs="0"&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments|null
     * @since 1.0.0
     */
    private ?Payments $payments = null;

    /**
     *
     * @param ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get SalesInvoices<br>
     * If $create is true a new instance is created if wasn't before, add to the stack
     * than returned to be populated<br>
     * &lt;xs:element name="SalesInvoices" minOccurs="0"&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices|null
     * @param bool $create if true a new Instance will be created if wasn't before
     * @since 1.0.0
     */
    public function getSalesInvoices(bool $create = true): ?SalesInvoices
    {
        if ($create && $this->salesInvoices === null) {
            $this->salesInvoices = new SalesInvoices($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
        return $this->salesInvoices;
    }

    /**
     * Set SalesInvoices As Null
     * @return void
     * @since 1.0.0
     */
    public function setSalesInvoicesAsNull(): void
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set as mull");
        $this->salesInvoices = null;
    }

    /**
     * Set MovementOfGoods<br>
     * If $create is true a new instance is created if wasn't before, add to the stack
     * than returned to be populated<br>
     * &lt;xs:element name="MovementOfGoods" minOccurs="0"&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods|null
     * @param bool $create if true a new Instance will be created if wasn't before
     * @since 1.0.0
     */
    public function getMovementOfGoods(bool $create = true): ?MovementOfGoods
    {
        if ($create && $this->movementOfGoods === null) {
            $this->movementOfGoods = new MovementOfGoods($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
        return $this->movementOfGoods;
    }

    /**
     * Set MovementOfGoods As Null
     * @return void
     * @since 1.0.0
     */
    public function setMovementOfGoodsAsNull(): void
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set as mull");
        $this->movementOfGoods = null;
    }

    /**
     * Set WorkingDocuments<br>
     * If $create is true a new instance is created if wasn't before, add to the stack
     * than returned to be populated<br>
     * &lt;xs:element name="WorkingDocuments" minOccurs="0"&gt;
     * @param bool $create if true a new Instance will be created if wasn't before
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments|null
     * @since 1.0.0
     */
    public function getWorkingDocuments(bool $create = true): ?WorkingDocuments
    {
        if ($create && $this->workingDocuments === null) {
            $this->workingDocuments = new WorkingDocuments($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
        return $this->workingDocuments;
    }

    /**
     * Set WorkingDocuments As Null
     * @return void
     * @since 1.0.0
     */
    public function setWorkingDocumentsAsNull(): void
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set as mull");
        $this->workingDocuments = null;
    }

    /**
     * Set Payments<br>
     * If $create is true a new instance is created if wasn't before, add to the stack
     * than returned to be populated<br>
     * &lt;xs:element name="Payments" minOccurs="0"&gt;
     * @param bool $create if true a new Instance will be created if wasn't before
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments|null
     * @throws \Error
     * @since 1.0.0
     */
    public function getPayments(bool $create = true): ?Payments
    {
        if ($create && $this->payments === null) {
            $this->payments = new Payments($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." get");
        return $this->payments;
    }

    /**
     * Set Payments As Null
     * @return void
     * @since 1.0.0
     */
    public function setPaymentsAsNull(): void
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." set as mull");
        $this->payments = null;
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

        if ($node->getName() !== AuditFile::N_AUDITFILE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                AuditFile::N_AUDITFILE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $souDocNode = $node->addChild(static::N_SOURCEDOCUMENTS);

        $this->getSalesInvoices(false)?->createXmlNode($souDocNode);
        $this->getMovementOfGoods(false)?->createXmlNode($souDocNode);
        $this->getWorkingDocuments(false)?->createXmlNode($souDocNode);
        $this->getPayments(false)?->createXmlNode($souDocNode);

        return $souDocNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SOURCEDOCUMENTS) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SOURCEDOCUMENTS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{SalesInvoices::N_SALESINVOICES}->count() > 0) {
            $this->getSalesInvoices()?->parseXmlNode(
                $node->{SalesInvoices::N_SALESINVOICES}
            );
        }

        if ($node->{MovementOfGoods::N_MOVEMENTOFGOODS}->count() > 0) {
            $this->getMovementOfGoods()?->parseXmlNode(
                $node->{MovementOfGoods::N_MOVEMENTOFGOODS}
            );
        }

        if ($node->{WorkingDocuments::N_WORKINGDOCUMENTS}->count() > 0) {
            $this->getWorkingDocuments()?->parseXmlNode(
                $node->{WorkingDocuments::N_WORKINGDOCUMENTS}
            );
        }

        if ($node->{Payments::N_PAYMENTS}->count() > 0) {
            $this->getPayments()?->parseXmlNode($node->{Payments::N_PAYMENTS});
        }
    }
}
