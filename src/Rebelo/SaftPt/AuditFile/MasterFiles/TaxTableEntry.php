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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

use Rebelo\Date\Date as RDate;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * TaxTableEntry
 * <pre>
 * &lt;xs:sequence&gt;
 *    &lt;xs:element ref="TaxType"/&gt;
 *    &lt;xs:element ref="TaxCountryRegion"/&gt;
 *    &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
 *    &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
 *    &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
 *    &lt;xs:choice&gt;
 *        &lt;xs:element ref="TaxPercentage"/&gt;
 *        &lt;xs:element ref="TaxAmount"/&gt;
 *    &lt;/xs:choice&gt;
 *    &lt;/xs:sequence&gt;
 * &lt;/pre&gt;
 * @author João Rebelo
 * @since 1.0.0
 */
class TaxTableEntry
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXTABLEENTRY = "TaxTableEntry";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXTYPE = "TaxType";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXCOUNTRYREGION = "TaxCountryRegion";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXCODE = "TaxCode";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXEXPIRATIONDATE = "TaxExpirationDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXPERCENTAGE = "TaxPercentage";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXAMOUNT = "TaxAmount";

    /**
     * &lt;xs:element ref="TaxType"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType $taxType
     * @since 1.0.0
     */
    private TaxType $taxType;

    /**
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     *      *
     * @var \Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion
     * @since 1.0.0
     */
    private TaxCountryRegion $taxCountryRegion;

    /**
     * &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode $taxCode
     * @since 1.0.0
     */
    private TaxCode $taxCode;

    /**
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * @var string $description
     * @since 1.0.0
     */
    private string $description;

    /**
     *  &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     *
     * @var \Rebelo\Date\Date $taxExpirationDate
     * @since 1.0.0
     */
    private ?\Rebelo\Date\Date $taxExpirationDate = null;

    /**
     * <pre>
     * &lt;xs:choice&gt;
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @var float|null $taxPercentage
     * @since 1.0.0
     */
    private ?float $taxPercentage = null;

    /**
     * <pre>
     * &lt;xs:choice&gt;
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @var float|null $taxAmount
     * @since 1.0.0
     */
    private ?float $taxAmount = null;

    /**
     * <pre>
     * &lt;xs:sequence&gt;
     *    &lt;xs:element ref="TaxType"/&gt;
     *    &lt;xs:element ref="TaxCountryRegion"/&gt;
     *    &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
     *    &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     *    &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     *    &lt;xs:choice&gt;
     *        &lt;xs:element ref="TaxPercentage"/&gt;
     *        &lt;xs:element ref="TaxAmount"/&gt;
     *    &lt;/xs:choice&gt;
     *    &lt;/xs:sequence&gt;
     * </pre>;
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get TaxType
     * <pre>
     * &lt;xs:element ref="TaxType"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType
     * @since 1.0.0
     */
    public function getTaxType(): TaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->taxType->get()));
        return $this->taxType;
    }

    /**
     * Set TaxType
     * <pre>
     * &lt;xs:element ref="TaxType"/&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType $taxType
     * @return void
     * @since 1.0.0
     */
    public function setTaxType(TaxType $taxType): void
    {
        $this->taxType = $taxType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->taxType->get()));
    }

    /**
     * Get TaxCountryRegion
     * <pre>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    public function getTaxCountryRegion(): \Rebelo\SaftPt\AuditFile\TaxCountryRegion
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->taxCountryRegion->get()));
        return $this->taxCountryRegion;
    }

    /**
     * Set TaxCountryRegion
     * <pre>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion
     * @return void
     * @since 1.0.0
     */
    public function setTaxCountryRegion(\Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion): void
    {
        $this->taxCountryRegion = $taxCountryRegion;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->taxCountryRegion->get()));
    }

    /**
     * Get TaxCode
     * <pre>
     * &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode
     * @since 1.0.0
     */
    public function getTaxCode(): TaxCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->taxCode->get()));
        return $this->taxCode;
    }

    /**
     * Set TaxCode
     * <pre>
     * &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(TaxCode $taxCode): void
    {
        $this->taxCode = $taxCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->taxCode->get()));
    }

    /**
     * Get description
     * <pre>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * </pre>
     * @return string
     * @since 1.0.0
     */
    public function getDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->description));
        return $this->description;
    }

    /**
     * Set description
     * <pre>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * </pre>
     * @param string $description
     * @return void
     * @since 1.0.0
     */
    public function setDescription(string $description): void
    {
        $this->description = static::valTextMandMaxCar($description, 255,
                                                       __METHOD__);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " getted '%s'", $this->description));
    }

    /**
     * Get TaxExpiration
     * <pre>
     * &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     * </pre>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getTaxExpirationDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->taxExpirationDate === null
                        ?
                        "null"
                        : $this->taxExpirationDate->format(RDate::SQL_DATE)));
        return $this->taxExpirationDate;
    }

    /**
     * Set TaxExpiration
     * <pre>
     * &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     * </pre>
     * @param \Rebelo\Date\Date|null $taxExpirationDate
     * @return void
     * @since 1.0.0
     */
    public function setTaxExpirationDate(?RDate $taxExpirationDate): void
    {
        $this->taxExpirationDate = $taxExpirationDate;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " getted '%s'",
                             $this->taxExpirationDate === null
                        ?
                        "null"
                        : $this->taxExpirationDate->format(RDate::SQL_DATE)));
    }

    /**
     * Get taxPercentage
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxPercentage(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->taxPercentage === null
                        ? "null"
                        :
                        \strval($this->taxPercentage)));
        return $this->taxPercentage;
    }

    /**
     * Get taxPercentage
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @param float|null $taxPercentage
     * @return void
     * @since 1.0.0
     */
    public function setTaxPercentage(?float $taxPercentage): void
    {
        if ($this->getTaxAmount() !== null)
        {
            $msg = "TaxAmount is already setted, is only "
                . "possible to set one of TaxAmount or TaxPercentage";
            \Logger::getLogger(\get_class($this))
                ->debug(__METHOD__ . " " . $msg);
            throw new AuditFileException($msg);
        }

        if ($taxPercentage !== null && $taxPercentage < 0.0)
        {
            $msg = "TaxPercentage must be equal or greater than zero";
            \Logger::getLogger(\get_class($this))
                ->debug(__METHOD__ . " " . $msg);
            throw new AuditFileException($msg);
        }

        $this->taxPercentage = $taxPercentage;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " getted '%s'",
                             $this->taxPercentage === null
                        ?
                        "null"
                        : \strval($this->taxPercentage)));
    }

    /**
     * Get TaxAmount
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxAmount(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->taxAmount === null
                        ?
                        "null"
                        : \strval($this->taxAmount)));
        return $this->taxAmount;
    }

    /**
     * Set TaxAmount
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @param float|null $taxAmount
     * @return void
     * @since 1.0.0
     */
    public function setTaxAmount(?float $taxAmount): void
    {
        if ($this->getTaxPercentage() !== null)
        {
            $msg = "TaxPercentage is already setted, is only "
                . "possible to set one of TaxAmount or TaxPercentage";
            \Logger::getLogger(\get_class($this))
                ->debug(__METHOD__ . " " . $msg);
            throw new AuditFileException($msg);
        }

        if ($taxAmount !== null && $taxAmount < 0.0)
        {
            $msg = "TaxAmount must be equal or greater than zero";
            \Logger::getLogger(\get_class($this))
                ->debug(__METHOD__ . " " . $msg);
            throw new AuditFileException($msg);
        }

        $this->taxAmount = $taxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " getted '%s'",
                             $this->taxAmount === null
                        ?
                        "null"
                        : \strval($this->taxAmount)));
    }

    /**
     * Create the TaxTableEntry node in the TaxTable node
     * @param \SimpleXMLElement $node The TaxTable node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($node->getName() !== MasterFiles::N_MASTERFILES)
        {
            $msg = sprintf("The node name where '%s' must be '%s', but '%s' was passed as argument",
                           static::N_TAXTABLEENTRY, MasterFiles::N_TAXTABLE,
                           $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $taxTableEntryNode = $node->addChild(static::N_TAXTABLEENTRY);
        $taxTableEntryNode->addChild(static::N_TAXTYPE,
                                     $this->getTaxType()->get());
        $taxTableEntryNode->addChild(static::N_TAXCOUNTRYREGION,
                                     $this->getTaxCountryRegion()->get());
        $taxTableEntryNode->addChild(static::N_TAXCODE,
                                     $this->getTaxCode()->get());
        $taxTableEntryNode->addChild(static::N_DESCRIPTION,
                                     $this->getDescription());
        if ($this->getTaxExpirationDate() !== null)
        {
            $taxTableEntryNode->addChild(static::N_TAXEXPIRATIONDATE,
                                         $this->getTaxExpirationDate()->format(RDate::SQL_DATE));
        }

        if ($this->getTaxPercentage() !== null && $this->getTaxAmount() !== null)
        {
            $msg = sprintf("Only one of both must be setted '%s' or '%s'",
                           static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($this->getTaxPercentage() !== null)
        {
            $taxTableEntryNode->addChild(static::N_TAXPERCENTAGE,
                                         \strval($this->getTaxPercentage()));
        }
        elseif ($this->getTaxAmount() !== null)
        {
            $taxTableEntryNode->addChild(static::N_TAXAMOUNT,
                                         \strval($this->getTaxAmount()));
        }
        else
        {
            $msg = sprintf("One of both must be setted '%s' or '%s'",
                           static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        return $taxTableEntryNode;
    }

    /**
     * Parse the TaxTableEntry node
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if ($node->getName() !== static::N_TAXTABLEENTRY)
        {
            $msg = sprinf("Node name should be '%s' but is '%s",
                          static::N_TAXTABLEENTRY, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxType(new TaxType($node->{static::N_TAXTYPE}));
        $this->setTaxCountryRegion(new TaxCountryRegion($node->{static::N_TAXCOUNTRYREGION}));
        $this->setTaxCode(new TaxCode($node->{static::N_TAXCODE}));
        $this->setDescription($node->{static::N_DESCRIPTION});
        if ($node->{static::N_TAXEXPIRATIONDATE}->count() > 0)
        {
            $date = RDate::parse(RDate::SQL_DATE,
                                 $node->{static::N_TAXEXPIRATIONDATE});
            $this->setTaxExpirationDate($date);
        }
        else
        {
            $this->setTaxExpirationDate(null);
        }


        if ($node->{static::N_TAXPERCENTAGE}->count() > 0 && $node->{static::N_TAXAMOUNT}->count() > 0)
        {
            $msg = sprintf("Only one of both must be setted '%s' or '%s'",
                           static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_TAXPERCENTAGE}->count() > 0)
        {
            $this->setTaxPercentage(\floatval($node->{static::N_TAXPERCENTAGE}));
        }
        elseif ($node->{static::N_TAXAMOUNT}->count() > 0)
        {
            $this->setTaxAmount(\floatval($node->{static::N_TAXAMOUNT}));
        }
        else
        {
            $msg = sprintf("One of both must be setted '%s' or '%s'",
                           static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
    }

}
