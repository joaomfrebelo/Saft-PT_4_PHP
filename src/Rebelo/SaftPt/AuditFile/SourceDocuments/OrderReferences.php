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

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * Description of OrderReferences
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class OrderReferences extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_ORDERREFERENCES = "OrderReferences";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ORIGINATINGON = "OriginatingON";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ORDERDATE = "OrderDate";

    /**
     * <xs:element ref="OriginatingON" minOccurs="0"/>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     *
     * @var string|null
     * @since 1.0.0
     */
    private ?string $originatingON = null;

    /**
     * <xs:element ref="OrderDate" minOccurs="0"/><br>
     * <xs:element name="OrderDate" type="SAFdateType"/>
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $orderDate = null;

    /**
     * &lt;!-- Estrutura de Referencias ao documento de origem--&gt;
     * &lt;xs:complexType name="OrderReferences"&gt;
     *  &lt;xs:sequence&gt;
     *      &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;
     *      &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     *  &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get OriginatingON<br>
     * <xs:element ref="OriginatingON" minOccurs="0"/><br>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @return string|null
     * @since 1.0.0
     */
    public function getOriginatingON(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->originatingON === null ?
                        "null" : $this->originatingON));

        return $this->originatingON;
    }

    /**
     * Set OriginatingON<br>
     * <xs:element ref="OriginatingON" minOccurs="0"/><br>
     * <xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/>
     * @param string|null $originatingON
     * @return void
     * @since 1.0.0
     */
    public function setOriginatingON(?string $originatingON): void
    {
        $this->originatingON = $originatingON === null ?
            null :
            $this->valTextMandMaxCar($originatingON, 60, __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->originatingON === null ?
                        "null" : $this->originatingON));
    }

    /**
     * Get OrderDate<br>
     * <xs:element ref="OrderDate" minOccurs="0"/>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getOrderDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->orderDate === null ?
                        "null" : $this->orderDate->format(RDate::SQL_DATE)));

        return $this->orderDate;
    }

    /**
     * Set OrderDate<br>
     * <xs:element ref="OrderDate" minOccurs="0"/>
     * @param \Rebelo\Date\Date|null $orderDate
     * @return void
     * @since 1.0.0
     */
    public function setOrderDate(?RDate $orderDate): void
    {
        $this->orderDate = $orderDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->orderDate === null ?
                        "null" : $this->orderDate->format(RDate::SQL_DATE)));
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

        if ($node->getName() !== A2Line::N_LINE) {
            $msg = \sprintf("Node name should be '%s' but is '%s",
                A2Line::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $ordeRefNode = $node->addChild(static::N_ORDERREFERENCES);

        if ($this->getOriginatingON() !== null) {
            $ordeRefNode->addChild(
                static::N_ORIGINATINGON, $this->getOriginatingON()
            );
        }
        if ($this->getOrderDate() !== null) {
            $ordeRefNode->addChild(
                static::N_ORDERDATE,
                $this->getOrderDate()->format(RDate::SQL_DATE)
            );
        }
        return $ordeRefNode;
    }

    /**
     * Parse XML node
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_ORDERREFERENCES) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_ORDERREFERENCES, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_ORIGINATINGON}->count() > 0) {
            $this->setOriginatingON(
                (string) $node->{static::N_ORIGINATINGON}
            );
        } else {
            $this->setOriginatingON(null);
        }

        if ($node->{static::N_ORDERDATE}->count() > 0) {
            $this->setOrderDate(
                RDate::parse(
                    RDate::SQL_DATE, (string) $node->{static::N_ORDERDATE}
                )
            );
        } else {
            $this->setOrderDate(null);
        }
    }
}