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
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class ALineInvoiveAndWorking extends A2Line
{
    /**
     * <xs:element ref="TaxBase" minOccurs="0"/>
     * Node name
     * @since 1.0.0
     */
    const N_TAXBASE = "TaxBase";

    /**
     * <xs:element ref="TaxPointDate"/>
     * Node name
     * @since 1.0.0
     */
    const N_TAXPOINTDATE = "TaxPointDate";

    /**
     * <xs:element name="References" type="References" minOccurs="0" maxOccurs="unbounded"/>
     * Node name
     * @since 1.0.0
     */
    const N_REFERENCES = "References";

    /**
     * <xs:element name="Tax" type="Tax"/>
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     * <xs:element ref="TaxBase" minOccurs="0"/><br>
     * <xs:element name="TaxBase" type="SAFmonetaryType"/>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $taxBase = null;

    /**
     * <xs:element ref="TaxPointDate"/><br>
     * <xs:element name="TaxPointDate" type="SAFdateType"/>
     * @var \Rebelo\Date\Date
     * @since 1.0.0
     */
    private RDate $taxPointDate;

    /**
     * <!-- Estrutura de Referencias ao documento de origem--><br>
     * <xs:element name="References" type="References" minOccurs="0" maxOccurs="unbounded"/>
     * <pre>
     *   &lt;xs:complexType name="OrderReferences"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;
     *           &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\OrderReferences[]
     * @since 1.0.0
     */
    private array $references = array();

    /**
     * <xs:element name="Tax" type="Tax"/>
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax
     * @since 1.0.0
     */
    private Tax $tax;

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Tax Base
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxBase(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxBase === null ? "null" :
                        \strval($this->taxBase)));
        return $this->taxBase;
    }

    /**
     *
     * @param float|null $taxBase
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setTaxBase(?float $taxBase): void
    {
        if ($taxBase !== null && $taxBase < 0.0) {
            $msg = "Tax Base can not be less than 0.0";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->taxBase = $taxBase;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxBase === null ? "null" :
                        \strval($this->taxBase)));
    }

    /**
     *
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getTaxPointDate(): RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->taxPointDate->format(RDate::SQL_DATE)));
        return $this->taxPointDate;
    }

    /**
     *
     * @param \Rebelo\Date\Date $taxPointDate
     * @return void
     * @since 1.0.0
     */
    public function setTaxPointDate(RDate $taxPointDate): void
    {
        $this->taxPointDate = $taxPointDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->taxPointDate->format(RDate::SQL_DATE)));
    }

    /**
     * Get Refrences stack
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\References[]
     * @since 1.0.0
     */
    public function getReferences(): array
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->references;
    }

    /**
     * Add Reference to the stack
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\References $references
     * @return int
     * @since 1.0.0
     */
    public function addToReferences(References $references): int
    {
        if (\count($this->references) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->references);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->references[$index] = $references;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
        return $index;
    }

    /**
     * isset References
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetReferences(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->references[$index]);
    }

    /**
     * unset References
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetReferences(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->references[$index]);
    }

    /**
     *
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax
     * @since 1.0.0
     */
    public function getTax(): Tax
    {
        \Logger::getLogger(\get_class($this))->info(__METHOD__." getted");
        return $this->tax;
    }

    /**
     *
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\Tax $tax
     * @return void
     * @since 1.0.0
     */
    public function setTax(Tax $tax): void
    {
        $this->tax = $tax;
        \Logger::getLogger(\get_class($this))->debug(__METHOD__." setted");
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
            $lineNode->addChild(static::N_TAXBASE,
                $this->floatFormat($this->getTaxBase()));
        }

        $lineNode->addChild(static::N_TAXPOINTDATE,
            $this->getTaxPointDate()->format(RDate::SQL_DATE));

        foreach ($this->getReferences() as $references) {
            /* @var $references \Rebelo\SaftPt\AuditFile\SourceDocuments\References */
            $references->createXmlNode($lineNode);
        }

        $lineNode->addChild(static::N_DESCRIPTION, $this->getDescription());

        if ($this->getProductSerialNumber() !== null) {
            $this->getProductSerialNumber()->createXmlNode($lineNode);
        }

        parent::createXmlNodeDebitCreditNode($lineNode);

        $this->getTax()->createXmlNode($lineNode);

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
            RDate::parse(RDate::SQL_DATE,
                (string) $node->{static::N_TAXPOINTDATE})
        );

        for ($n = 0; $n < $node->{static::N_REFERENCES}->count(); $n++) {
            $references = new References();
            $references->parseXmlNode($node->{static::N_REFERENCES}[$n]);
            $this->addToReferences($references);
        }

        $tax = new Tax();
        $tax->parseXmlNode($node->{self::N_TAX});
        $this->setTax($tax);
    }
}