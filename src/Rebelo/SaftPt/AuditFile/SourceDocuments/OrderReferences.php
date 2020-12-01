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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * OrderReferences<br>
 * If there is a need to make more than one reference,
 * this structure can be generated as many times as necessary.
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
     * &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @var string|null
     * @since 1.0.0
     */
    private ?string $originatingON = null;

    /**
     * &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="OrderDate" type="SAFdateType"/&gt;
     * @var \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    private ?RDate $orderDate = null;

    /**
     * OrderReferences<br>
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.
     * <pre>
     * &lt;xs:complexType name="OrderReferences"&gt;
     *  &lt;xs:sequence&gt;
     *      &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;
     *      &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     *  &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @param ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get OriginatingON<br>
     * In case the document is included in SAF-T (PT)
     * the number structure of the field of origin should be used.<br>
     * &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getOriginatingON(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->originatingON === null ?
                    "null" : $this->originatingON
                )
            );

        return $this->originatingON;
    }

    /**
     * Set OriginatingON<br>
     * In case the document is included in SAF-T (PT)
     * the number structure of the field of origin should be used.<br>
     * &lt;xs:element ref="OriginatingON" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="OriginatingON" type="SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @param string|null $originatingON
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setOriginatingON(?string $originatingON): bool
    {
        try {
            $this->originatingON = $originatingON === null ?
                null :
                $this->valTextMandMaxCar($originatingON, 60, __METHOD__);
            $return              = true;
        } catch (AuditFileException $e) {
            $this->originatingON = $originatingON;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("OriginatingON_not_valid");
            $return              = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->originatingON === null ?
                    "null" : $this->originatingON
                )
            );
        return $return;
    }

    /**
     * Get OrderDate<br>
     * &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getOrderDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->orderDate === null ?
                    "null" : $this->orderDate->format(RDate::SQL_DATE)
                )
            );

        return $this->orderDate;
    }

    /**
     * Set OrderDate<br>
     * &lt;xs:element ref="OrderDate" minOccurs="0"/&gt;
     * @param \Rebelo\Date\Date|null $orderDate
     * @return void
     * @since 1.0.0
     */
    public function setOrderDate(?RDate $orderDate): void
    {
        $this->orderDate = $orderDate;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->orderDate === null ?
                    "null" : $this->orderDate->format(RDate::SQL_DATE)
                )
            );
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
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
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
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_ORDERREFERENCES, $node->getName()
            );
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