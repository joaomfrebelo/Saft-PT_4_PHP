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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class ALineInvoiveAndWorking extends A2Line
{
    /**
     * &lt;xs:element ref="TaxBase" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TAXBASE = "TaxBase";

    /**
     * &lt;xs:element ref="TaxPointDate"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TAXPOINTDATE = "TaxPointDate";

    /**
     * &lt;xs:element name="References" type="References" minOccurs="0" maxOccurs="unbounded"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_REFERENCES = "References";

    /**
     * &lt;xs:element name="Tax" type="Tax"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     * &lt;xs:element ref="TaxBase" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="TaxBase" type="SAFmonetaryType"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    protected ?float $taxBase = null;

    /**
     * &lt;xs:element ref="TaxPointDate"/&gt;<br>
     * &lt;xs:element name="TaxPointDate" type="SAFdateType"/&gt;
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    protected RDate $taxPointDate;

    /**
     *
     * &lt;xs:element name="References" type="References" minOccurs="0" maxOccurs="unbounded"/&gt;
     * <pre>
     *   &lt;xs:complexType name="OrderReferences"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;
     *           &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\References[]
     * @since 1.0.0
     */
    protected array $references = array();

    /**
     * &lt;xs:element name="Tax" type="Tax"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax
     * @since 1.0.0
     */
    protected Tax $tax;

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get TaxBase<br>
     * Unit taxable amount that does not contribute for the NetTotal field.
     * This value is the basis for calculating the line taxes.
     * The sign (debit or credit) with which the tax, thus,
     * calculated contributes for the TaxPayable field results from the
     * existence within the line of the DebitAmount or CreditAmount fields.
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxBase(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxBase === null ? "null" :
                    \strval($this->taxBase)
                )
            );
        return $this->taxBase;
    }

    /**
     * Set TaxBase<br>
     * Unit taxable amount that does not contribute for the NetTotal field.
     * This value is the basis for calculating the line taxes.
     * The sign (debit or credit) with which the tax, thus,
     * calculated contributes for the TaxPayable field results from the
     * existence within the line of the DebitAmount or CreditAmount fields.
     * @param float|null $taxBase
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxBase(?float $taxBase): bool
    {
        if ($taxBase !== null && $taxBase < 0.0) {
            $msg    = "Tax Base can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxBase_not_valid");
        } else {
            $return = true;
        }
        $this->taxBase = $taxBase;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->taxBase === null ? "null" :
                    \strval($this->taxBase)
                )
            );
        return $return;
    }

    /**
     * Get TaxPointDate<br>
     * Date of the dispatch of the goods or of the delivery of the service.
     * @return \Rebelo\Date\Date
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxPointDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxPointDate->format(RDate::SQL_DATE)
                )
            );
        return $this->taxPointDate;
    }

    /**
     * Set TaxPointDate <br>
     * Date of the dispatch of the goods or of the delivery of the service.
     * @param \Rebelo\Date\Date $taxPointDate
     * @return void
     * @since 1.0.0
     */
    public function setTaxPointDate(RDate $taxPointDate): void
    {
        $this->taxPointDate = $taxPointDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->taxPointDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Get is is set TaxPointDate
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxPointDate(): bool
    {
        return isset($this->taxPointDate);
    }

    /**
     * Get Refrences stack<br>
     * References to invoices on the correspondent correcting documents.
     * If there is a need to make more than one reference, this structure can be
     * generated as many times as necessary.<br>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\References[]
     * @since 1.0.0
     */
    public function getReferences(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->references;
    }

    /**
     * Create a new Reference instance and add to the stack then return for populate with values<br>
     * References to invoices on the correspondent correcting documents.
     * If there is a need to make more than one reference, this structure can be
     * generated as many times as necessary.<br>
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\References
     * @since 1.0.0
     */
    public function addReferences(): References
    {
        $references         = new References($this->getErrorRegistor());
        $this->references[] = $references;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
        return $references;
    }

    /**
     * Get Tax<br>
     * If the instance is not created, a Tax instance will be created and
     * returned to be populated<br>
     * This structure shall only be created for documents with a value in the database.
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax
     * @since 1.0.0
     */
    public function getTax(): Tax
    {
        if (isset($this->tax) === false) {
            $this->tax = new Tax($this->getErrorRegistor());
        }
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->tax;
    }

    /**
     * Get if Tax is setted
     * @return bool
     * @since 1.0.0
     */
    public function issetTax(): bool
    {
        return isset($this->tax);
    }

    /**
     * Create the XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        $lineNode = parent::createXmlNode($node);

        if ($this->getTaxBase() !== null) {
            $lineNode->addChild(
                static::N_TAXBASE, $this->floatFormat($this->getTaxBase())
            );
        }

        if (isset($this->taxPointDate)) {
            $lineNode->addChild(
                static::N_TAXPOINTDATE,
                $this->getTaxPointDate()->format(RDate::SQL_DATE)
            );
        } else {
            $lineNode->addChild(static::N_TAXPOINTDATE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxPointDate_not_valid");
        }

        foreach ($this->getReferences() as $references) {
            /* @var $references \Rebelo\SaftPt\AuditFile\SourceDocuments\References */
            $references->createXmlNode($lineNode);
        }

        if (isset($this->description)) {
            $lineNode->addChild(static::N_DESCRIPTION, $this->getDescription());
        } else {
            $lineNode->addChild(static::N_DESCRIPTION);
            $this->getErrorRegistor()->addOnCreateXmlNode("Description_not_valid");
        }


        if ($this->getProductSerialNumber(false) !== null) {
            $this->getProductSerialNumber()?->createXmlNode($lineNode);
        }

        parent::createXmlNodeDebitCreditNode($lineNode);

        if (isset($this->tax)) {
            $this->getTax()->createXmlNode($lineNode);
        } else {
            $this->getErrorRegistor()->addOnCreateXmlNode("Tax_not_valid");
        }

        parent::createXmlNodeTaxExcSettAndCustoms($lineNode);

        return $lineNode;
    }

    /**
     * Parse the xml represented in thist trati
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        // Test node name and parse LineNumber, credit and debit
        parent::parseXmlNode($node);

        if ($node->{static::N_TAXBASE}->count() > 0) {
            $this->setTaxBase((float) $node->{static::N_TAXBASE});
        }

        $this->setTaxPointDate(
            RDate::parse(
                RDate::SQL_DATE,
                (string) $node->{static::N_TAXPOINTDATE}
            )
        );

        for ($n = 0; $n < $node->{static::N_REFERENCES}->count(); $n++) {
            $this->addReferences()->parseXmlNode($node->{static::N_REFERENCES}[$n]);
        }

        $this->getTax()->parseXmlNode($node->{self::N_TAX});
    }
}
