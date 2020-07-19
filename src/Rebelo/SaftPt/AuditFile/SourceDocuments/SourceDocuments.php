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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\SaftPt\AuditFile\ExportType;
use Rebelo\SaftPt\AuditFile\AuditFile;
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
class SourceDocuments extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEDOCUMENTS = "SourceDocuments";

    /**
     *  <xs:element name="SalesInvoices" minOccurs="0">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices
     * @since 1.0.0
     */
    private SalesInvoices $salesInvoices;

    /**
     * <xs:element name="MovementOfGoods" minOccurs="0">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods
     * @since 1.0.0
     */
    private MovementOfGoods $movementOfGoods;

    /**
     * <xs:element name="WorkingDocuments" minOccurs="0">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments
     * @since 1.0.0
     */
    private WorkingDocuments $workingDocuments;

    /**
     * <xs:element name="Payments" minOccurs="0">
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments
     * @since 1.0.0
     */
    private Payments $payments;

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get SalesInvoices<br>
     * <xs:element name="SalesInvoices" minOccurs="0">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices
     * @throws \Error
     * @since 1.0.0
     */
    public function getSalesInvoices(): SalesInvoices
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->salesInvoices;
    }

    /**
     * Get SalesInvoices<br>
     * <xs:element name="SalesInvoices" minOccurs="0">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\SalesInvoices\SalesInvoices $salesInvoices
     * @return void
     * @since 1.0.0
     */
    public function setSalesInvoices(SalesInvoices $salesInvoices): void
    {
        $this->salesInvoices = $salesInvoices;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
    }

    /**
     * Set MovementOfGoods<br>
     * <xs:element name="MovementOfGoods" minOccurs="0">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods
     * @throws \Error
     * @since 1.0.0
     */
    public function getMovementOfGoods(): MovementOfGoods
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->movementOfGoods;
    }

    /**
     * Set MovementOfGoods<br>
     * <xs:element name="MovementOfGoods" minOccurs="0">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementOfGoods $movementOfGoods
     * @return void
     * @since 1.0.0
     */
    public function setMovementOfGoods(MovementOfGoods $movementOfGoods): void
    {
        $this->movementOfGoods = $movementOfGoods;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
    }

    /**
     * Set WorkingDocuments<br>
     * <xs:element name="WorkingDocuments" minOccurs="0">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments
     * @throws \Error
     * @since 1.0.0
     */
    public function getWorkingDocuments(): WorkingDocuments
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->workingDocuments;
    }

    /**
     * Set WorkingDocuments<br>
     * <xs:element name="WorkingDocuments" minOccurs="0">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments\WorkingDocuments $workingDocuments
     * @return void
     * @since 1.0.0
     */
    public function setWorkingDocuments(WorkingDocuments $workingDocuments): void
    {
        $this->workingDocuments = $workingDocuments;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
    }

    /**
     * Set Payments<br>
     * <xs:element name="Payments" minOccurs="0">
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments
     * @throws \Error
     * @since 1.0.0
     */
    public function getPayments(): Payments
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->payments;
    }

    /**
     * Set Payments<br>
     * <xs:element name="Payments" minOccurs="0">
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Payments\Payments $payments
     * @return void
     * @since 1.0.0
     */
    public function setPayments(Payments $payments): void
    {
        $this->payments = $payments;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." setted");
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

        if ($node->getName() !== AuditFile::N_AUDITFILE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                AuditFile::N_AUDITFILE, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $souDocNode = $node->addChild(static::N_SOURCEDOCUMENTS);

        if (isset($this->salesInvoices)) {
            $this->getSalesInvoices()->createXmlNode($souDocNode);
        }

        //Simplifeid saft only export SalesInvoices
        if ($this->getExportType()->isEqual(ExportType::S)) {
            return $souDocNode;
        }

        if (isset($this->movementOfGoods)) {
            $this->getMovementOfGoods()->createXmlNode($souDocNode);
        }

        if (isset($this->workingDocuments)) {
            $this->getWorkingDocuments()->createXmlNode($souDocNode);
        }

        if (isset($this->payments)) {
            $this->getPayments()->createXmlNode($souDocNode);
        }

        return $souDocNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SOURCEDOCUMENTS) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_SOURCEDOCUMENTS, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{SalesInvoices::N_SALESINVOICES}->count() > 0) {
            $salesInv = new SalesInvoices();
            $salesInv->parseXmlNode($node->{SalesInvoices::N_SALESINVOICES});
            $this->setSalesInvoices($salesInv);
        }

        if ($node->{MovementOfGoods::N_MOVEMENTOFGOODS}->count() > 0) {
            $movOfGod = new MovementOfGoods();
            $movOfGod->parseXmlNode($node->{MovementOfGoods::N_MOVEMENTOFGOODS});
            $this->setMovementOfGoods($movOfGod);
        }

        if ($node->{WorkingDocuments::N_WORKINGDOCUMENTS}->count() > 0) {
            $workDoc = new WorkingDocuments();
            $workDoc->parseXmlNode($node->{WorkingDocuments::N_WORKINGDOCUMENTS});
            $this->setWorkingDocuments($workDoc);
        }

        if ($node->{Payments::N_PAYMENTS}->count() > 0) {
            $pay = new Payments();
            $pay->parseXmlNode($node->{Payments::N_PAYMENTS});
            $this->setPayments($pay);
        }
    }
}