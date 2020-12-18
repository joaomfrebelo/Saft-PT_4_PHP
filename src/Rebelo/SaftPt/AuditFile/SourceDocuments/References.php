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

use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\SourceDocuments\A2Line;

/**
 * References<br>
 * References to invoices on the correspondent correcting documents.
 * If there is a need to make more than one reference,
 * this structure can be generated as many times as necessary
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class References extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * &lt;xs:complexType name="References">
     * Node name
     * @since 1.0.0
     */
    const N_REFERENCES = "References";

    /**
     * &lt;xs:element ref="Reference" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_REFERENCE = "Reference";

    /**
     * &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     * Node name
     * @since 1.0.0
     */
    const N_REASON = "Reason";

    /**
     * &lt;xs:element ref = "Reference" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reference" type = "SAFPTtextTypeMandatoryMax60Car"/&gt;
     * @var string|null
     */
    private ?string $reference = null;

    /**
     * &lt;xs:element ref = "Reason" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reason" type = "SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @var string|null
     */
    private ?string $reason = null;

    /**
     * References<br>
     * References to invoices on the correspondent correcting documents.
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary
     * <pre>
     *   &lt;xs:complexType name="References"&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="Reference" minOccurs="0"/&gt;
     *           &lt;xs:element ref="Reason" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     * @param ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get Reference<br>
     * Reference to the invoice or simplified invoice, using its unique
     * identification if existing in the respective systems.
     * The numbering structure of the field of origin shall be used.<br>
     * &lt;xs:element ref = "Reference" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reference" type = "SAFPTtextTypeMandatoryMax60Car"/&gt;     *
     * @return string|null
     * @since 1.0.0
     */
    public function getReference(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->reference === null ? "null" : $this->reference
                )
            );
        return $this->reference;
    }

    /**
     * Set Reference<br>
     * Reference to the invoice or simplified invoice, using its unique
     * identification if existing in the respective systems.
     * The numbering structure of the field of origin shall be used.<br>
     * &lt;xs:element ref = "Reference" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reference" type = "SAFPTtextTypeMandatoryMax60Car"/&gt;
     *
     * @param string|null $reference
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setReference(?string $reference): bool
    {
        try {
            $this->reference = $reference === null ? null :
                $this->valTextMandMaxCar($reference, 60, __METHOD__);
            $return          = true;
        } catch (AuditFileException $e) {
            $this->reference = $reference;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Reference_not_valid");
            $return          = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->reference === null ? "null" : $this->reference
                )
            );
        return $return;
    }

    /**
     * Get Reason<br>
     * Reason for issuing the document<br>
     * &lt;xs:element ref = "Reason" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reason" type = "SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->reason === null ? "null" : $this->reason
                )
            );
        return $this->reason;
    }

    /**
     * Set Reason<br>
     * Reason for issuing the document<br>
     * &lt;xs:element ref = "Reason" minOccurs = "0"/&gt;<br>
     * &lt;xs:element name = "Reason" type = "SAFPTtextTypeMandatoryMax50Car"/&gt;
     * @param string|null $reason
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setReason(?string $reason): bool
    {
        try {
            $this->reason = $reason === null ? null :
                $this->valTextMandMaxCar($reason, 50, __METHOD__);
            $return       = true;
        } catch (AuditFileException $e) {
            $this->reason = $reason;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("Reference_not_valid");
            $return       = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->reason === null ? "null" : $this->reason
                )
            );
        return $return;
    }

    /**
     * Create Xml node
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

        $refNode = $node->addChild(static::N_REFERENCES);

        if ($this->getReference() !== null) {
            $refNode->addChild(static::N_REFERENCE, $this->getReference());
        }

        if ($this->getReason() !== null) {
            $refNode->addChild(static::N_REASON, $this->getReason());
        }

        return $refNode;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_REFERENCES) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                static::N_REFERENCES, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_REFERENCE}->count() > 0) {
            $this->setReference((string) $node->{static::N_REFERENCE});
        }

        if ($node->{static::N_REASON}->count() > 0) {
            $this->setReason((string) $node->{static::N_REASON});
        }
    }
}
