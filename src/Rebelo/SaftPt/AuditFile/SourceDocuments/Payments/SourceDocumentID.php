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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * SourceDocumentID
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SourceDocumentID extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_SOURCEDOCUMENTID = "SourceDocumentID";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ORIGINATINGON = "OriginatingON";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_INVOICEDATE = "InvoiceDate";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * <pre>
     * <xs:element ref="OriginatingON"/>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $originatingON;

    /**
     * <xs:element ref="InvoiceDate"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $invoiceDate;

    /**
     * <pre>
     * <xs:element ref="Description" minOccurs="0"/>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * </pre>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $description = null;

    /**
     * SourceDocumentID<br>
     * <pre>
     * &lt;xs:element name="SourceDocumentID" maxOccurs="unbounded"&gt;
     *    &lt;xs:complexType&gt;
     *        &lt;xs:sequence&gt;
     *            &lt;xs:element ref="OriginatingON"/&gt;
     *            &lt;xs:element ref="InvoiceDate"/&gt;
     *            &lt;xs:element ref="Description" minOccurs="0"/&gt;
     *        &lt;/xs:sequence&gt;
     *    &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get OriginatingON<br>
     * <pre>
     * <xs:element ref="OriginatingON"/>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getOriginatingON(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->originatingON));
        return $this->originatingON;
    }

    /**
     * Set OriginatingON<br>
     * <pre>
     * <xs:element ref="OriginatingON"/>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     * </pre>
     * @param string $originatingON
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setOriginatingON(string $originatingON): void
    {
        $this->originatingON = $this->valTextMandMaxCar($originatingON, 60,
            __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->originatingON));
    }

    /**
     * Get Invoice Date<br>
     * <xs:element ref="InvoiceDate"/>
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getInvoiceDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)));
        return $this->invoiceDate;
    }

    /**
     * Set Invoice Date<br>
     * <xs:element ref="InvoiceDate"/>
     * @param \Rebelo\Date\Date $invoiceDate
     * @return void
     * @since 1.0.0
     */
    public function setInvoiceDate(RDate $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->invoiceDate->format(RDate::SQL_DATE)));
    }

    /**
     * Get description<br>
     * <pre>
     * <xs:element ref="Description" minOccurs="0"/>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * </pre>
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->description === null ? "null" : $this->description));
        return $this->description;
    }

    /**
     * Set description<br>
     * <pre>
     * <xs:element ref="Description" minOccurs="0"/>
     * <xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/>
     * </pre>
     * @param string|null $description
     * @return void
     * @since 1.0.0
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description === null ? null :
            $this->valTextMandMaxCar($description, 200, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->description === null ? "null" : $this->description));
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

        if ($node->getName() !== Line::N_LINE) {
            $msg = \sprintf("Node name should be '%s' but is '%s", Line::N_LINE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $sourceIdNode = $node->addChild(static::N_SOURCEDOCUMENTID);

        $sourceIdNode->addChild(
            static::N_ORIGINATINGON, $this->getOriginatingON()
        );
        $sourceIdNode->addChild(
            static::N_INVOICEDATE,
            $this->getInvoiceDate()->format(RDate::SQL_DATE)
        );
        if ($this->getDescription() !== null) {
            $sourceIdNode->addChild(
                static::N_DESCRIPTION, $this->getDescription()
            );
        }
        return $sourceIdNode;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SOURCEDOCUMENTID) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_SOURCEDOCUMENTID, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setOriginatingON(
            (string) $node->{static::N_ORIGINATINGON}
        );

        $this->setInvoiceDate(
            RDate::parse(
                RDate::SQL_DATE, (string) $node->{static::N_INVOICEDATE}
            )
        );

        if ($node->{static::N_DESCRIPTION}->count() > 0) {
            $this->setDescription(
                (string) $node->{static::N_DESCRIPTION}
            );
        } else {
            $this->setDescription(null);
        }
    }
}