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

/**
 * CustomsInformation
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class CustomsInformation extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_CUSTOMSINFORMATION = "CustomsInformation";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_ARCNO = "ARCNo";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_IECAMOUNT = "IECAmount";

    /**
     * &lt;xs:element ref="ARCNo" minOccurs="0" maxOccurs="unbounded"/&gt;<br>
     * &lt;xs:element name="ARCNo" type="SAFPTtextTypeMandatoryMax21Car"/&gt;
     * @var string[]
     * @since 1.0.0
     */
    private array $arcNo = array();

    /**
     * <code>
     * &lt;xs:element ref="IECAmount" minOccurs="0"/&gt;<br>
     * &lt;xs:element name="IECAmount" type="SAFmonetaryType"/&gt;
     * </code>
     * @var float|null
     * @since 1.0.0
     */
    private ?float $iecAmount = null;

    /**
     * CustomsInformation<br>
     * &lt;xs:complexType name="CustomsInformation"&gt;
     *  &lt;xs:sequence&gt;
     *      &lt;xs:element ref="ARCNo" minOccurs="0" maxOccurs="unbounded"/&gt;
     *      &lt;xs:element ref="IECAmount" minOccurs="0"/&gt;
     *  &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * @param ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get arcNo stack<br>
     * Fill in with the code assigned after the validation of the
     * electronic administrative document e-DA [Customs].
     * If there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     * <pre>
     * &lt;xs:element ref="ARCNo" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="ARCNo" type="SAFPTtextTypeMandatoryMax21Car"/&gt;
     * </pre>
     * @return string[]
     * @since 1.0.0
     */
    public function getArcNo(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->arcNo;
    }

    /**
     * Add ARCNo to the stack<br>
     * Fill in with the code assigned after the validation of the
     * electronic administrative document e-DA [Customs].
     * If there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     * <pre>
     * &lt;xs:element ref="ARCNo" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="ARCNo" type="SAFPTtextTypeMandatoryMax21Car"/&gt;
     * </pre>
     * @param string $arcNo
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function addARCNo(string $arcNo): bool
    {
        try {
            $val    = $this->valTextMandMaxCar($arcNo, 21, __METHOD__);
            $return = true;
        } catch (AuditFileException $e) {
            $val    = $arcNo;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("ARCNo_not_valid");
            $return = false;
        }
        $this->arcNo[] = $val;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." add to stack");
        return $return;
    }

    /**
     * Get Iec Amount<br>
     * Amount of excise duty contained in the taxable base of the
     * document line if it is not shown separately
     * in the document with the "ProductType" = E.
     * @return float|null
     * @throws \Error
     * @since 1.0.0
     */
    public function getIecAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->iecAmount === null ? "null" :
                    \strval($this->iecAmount)
                )
            );
        return $this->iecAmount;
    }

    /**
     * Set Ice Amount<br>
     * Amount of excise duty contained in the taxable base of the
     * document line if it is not shown separately
     * in the document with the "ProductType" = E.
     * @param float|null $iecAmount
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setIecAmount(?float $iecAmount): bool
    {
        if ($iecAmount !== null && $iecAmount < 0.0) {
            $msg    = "IECAmount can not be negative";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("IecAmount_not_valid");
        } else {
            $return = true;
        }
        $this->iecAmount = $iecAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->iecAmount === null ? "null" :
                    \strval($this->iecAmount)
                )
            );
        return $return;
    }

    /**
     * Create xml node
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
                "Node name should be '%s' or but is '%s",
                A2Line::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $ciNode = $node->addChild(static::N_CUSTOMSINFORMATION);
        foreach ($this->getArcNo() as $arcNo) {
            $ciNode->addChild(static::N_ARCNO, $arcNo);
        }

        if ($this->getIecAmount() !== null) {
            $ciNode->addChild(
                static::N_IECAMOUNT, \strval($this->getIecAmount())
            );
        }

        return $ciNode;
    }

    /**
     * Parse xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_CUSTOMSINFORMATION) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_CUSTOMSINFORMATION, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        for ($n = 0; $n < $node->{static::N_ARCNO}->count(); $n++) {
            $this->addARCNo((string) $node->{static::N_ARCNO}[$n]);
        }

        if ($node->{static::N_IECAMOUNT}->count() > 0) {
            $this->setIecAmount((float) $node->{static::N_IECAMOUNT});
        }
    }
}
