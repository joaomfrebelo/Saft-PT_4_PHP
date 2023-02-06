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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\Payments;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * SourceDocumentID<br>
 * If there is a need to make more than one reference,
 * this structure can be generated as many times as necessary.
 * In case of integrated accounting and invoicing program,
 * the numbering structure of the source field shall be used.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class SourceDocumentID extends AAuditFile
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
     * &lt;xs:element ref="OriginatingON"/&gt;
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $originatingON;

    /**
     * &lt;xs:element ref="InvoiceDate"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $invoiceDate;

    /**
     * <pre>
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * </pre>
     * @var string|null
     * @since 1.0.0
     */
    private ?string $description = null;

    /**
     * SourceDocumentID<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.
     * In case of integrated accounting and invoicing program,
     * the numbering structure of the source field shall be used.
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get OriginatingON<br>
     * Indicate type, series and number of the invoice or document amending the latter,
     * to be paid. In case the mentioned document is included in the SAF-T (PT)
     * the number structure of field 4.1.4.1. – InvoiceNo on table 4.1. – SalesInvoices should be used.
     * <pre>
     * &lt;xs:element ref="OriginatingON"/&gt;
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getOriginatingON(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->originatingON));
        return $this->originatingON;
    }

    /**
     * Get if is set ProductNumberCode
     * @return bool
     * @since 1.0.0
     */
    public function issetOriginatingON(): bool
    {
        return isset($this->originatingON);
    }

    /**
     * Set OriginatingON<br><br>
     * Indicate type, series and number of the invoice or document amending the latter,
     * to be paid. In case the mentioned document is included in the SAF-T (PT)
     * the number structure of field 4.1.4.1. – InvoiceNo on table 4.1. – SalesInvoices should be used.
     * <pre>
     * &lt;xs:element ref="OriginatingON"/&gt;
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * </pre>
     * @param string $originatingON
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setOriginatingON(string $originatingON): bool
    {
        try {
            $this->originatingON = $this->valTextMandMaxCar(
                $originatingON, 60,
                __METHOD__
            );
            $return              = true;
        } catch (AuditFileException $e) {
            $this->originatingON = $originatingON;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("OriginatingON_not_valid");
            $return              = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->originatingON));
        return $return;
    }

    /**
     * Get Invoice Date<br>
     * Mention the date on the invoice or any amendment document for payment.
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
     * Set Invoice Date<br>
     * Mention the date on the invoice or any amendment document for payment.
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
     * Get description<br>
     * Description line of the receipt.
     * <pre>
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * </pre>
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->description === null ? "null" : $this->description
                )
            );
        return $this->description;
    }

    /**
     * Set description<br>
     * Description line of the receipt.
     * <pre>
     * &lt;xs:element ref="Description" minOccurs="0"/&gt;
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax200Car"/&gt;
     * </pre>
     * @param string|null $description
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setDescription(?string $description): bool
    {
        try {
            $this->description = $description === null ? null :
                $this->valTextMandMaxCar($description, 200, __METHOD__);
            $return            = true;
        } catch (AuditFileException $e) {
            $this->description = $description;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Description_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->description === null ? "null" : $this->description
                )
            );
        return $return;
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== Line::N_LINE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", Line::N_LINE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $sourceIdNode = $node->addChild(static::N_SOURCEDOCUMENTID);

        if (isset($this->originatingON)) {
            $sourceIdNode->addChild(
                static::N_ORIGINATINGON, $this->getOriginatingON()
            );
        } else {
            $sourceIdNode->addChild(static::N_ORIGINATINGON);
            $this->getErrorRegistor()->addOnCreateXmlNode("OriginatingON_not_valid");
        }

        if (isset($this->invoiceDate)) {
            $sourceIdNode->addChild(
                static::N_INVOICEDATE,
                $this->getInvoiceDate()->format(RDate::SQL_DATE)
            );
        } else {
            $sourceIdNode->addChild(static::N_INVOICEDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("InvoiceDate_not_valid");
        }

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
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_SOURCEDOCUMENTID) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_SOURCEDOCUMENTID, $node->getName()
            );
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
