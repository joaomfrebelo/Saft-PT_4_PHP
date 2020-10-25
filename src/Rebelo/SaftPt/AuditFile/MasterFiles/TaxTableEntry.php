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
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * TaxTableEntry<br>
 * TaxTable [Table of taxes].<br>
 * This table shows the VAT regimes applied in each fiscal area and the
 * different types of stamp duty to be paid, applicable to the lines
 * of documents recorded in Table 4. – SourceDocuments.
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
class TaxTableEntry extends \Rebelo\SaftPt\AuditFile\AAuditFile
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
     * TaxTable [Table of taxes].<br>
     * This table shows the VAT regimes applied in each fiscal area and the
     * different types of stamp duty to be paid, applicable to the lines
     * of documents recorded in Table 4. – SourceDocuments.
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
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get TaxType<br>
     * This field shall be filled in with the tax type:
     * “IVA” – Value Added Tax;
     * “IS” – Stamp Duty;
     * “NS” – Not subject to VAT or Stamp Duty.
     * <pre>
     * &lt;xs:element ref="TaxType"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxType(): TaxType
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxType->get()));
        return $this->taxType;
    }

    /**
     * Get if is set TaxType
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxType(): bool
    {
        return isset($this->taxType);
    }

    /**
     * Set TaxType<br>
     * This field shall be filled in with the tax type:
     * “IVA” – Value Added Tax;
     * “IS” – Stamp Duty;
     * “NS” – Not subject to VAT or Stamp Duty.
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
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxType->get()));
    }

    /**
     * Get TaxCountryRegion<br>
     * This field must be filled in with the norm ISO 3166-1-alpha-2.<br>
     * In the case of the Autonomous Regions of the Azores and Madeira Island
     * the field must be filled in with:<br>
     * “PT-AC” – Fiscal area of the Autonomous Region of the Azores;<br>
     * “PT-MA” – Fiscal area of the Autonomous Region of the Madeira Island.<br>
     * <pre>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxCountryRegion(): TaxCountryRegion
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxCountryRegion->get()
                )
            );
        return $this->taxCountryRegion;
    }

    /**
     * Get if is set TaxCountryRegion
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxCountryRegion(): bool
    {
        return isset($this->taxCountryRegion);
    }

    /**
     * Set TaxCountryRegion<br>
     * This field must be filled in with the norm ISO 3166-1-alpha-2.<br>
     * In the case of the Autonomous Regions of the Azores and Madeira Island
     * the field must be filled in with:<br>
     * “PT-AC” – Fiscal area of the Autonomous Region of the Azores;<br>
     * “PT-MA” – Fiscal area of the Autonomous Region of the Madeira Island.<br>
     * <pre>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\TaxCountryRegion $taxCountryRegion
     * @return void
     * @since 1.0.0
     */
    public function setTaxCountryRegion(TaxCountryRegion $taxCountryRegion): void
    {
        $this->taxCountryRegion = $taxCountryRegion;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->taxCountryRegion->get()
                )
            );
    }

    /**
     * Get TaxCode<br>
     * In case field 2.5.1.1. – TaxType = IVA, the field must be filled in with:<br>
     * “RED” – Reduced tax rate;<br>
     * “INT” – Intermediate tax rate;<br>
     * “NOR” – Normal tax rate;<br>
     * “ISE” – Exempted;<br>
     * “OUT” – Others, applicable to the special VAT regimes.<br>
     * In case field 2.5.1.1. – TaxType = IS, it shall be filled in with:<br>
     * The correspondent code of the Stamp Duty’s table;<br>
     * “ISE” – Exempted.<br>
     * In case it is not subject to tax it shall be filled in with “NS”.
     * In receipts issued without tax discriminated it shall be filled in with “NA”.
     * <pre>
     * &lt;xs:element name="TaxCode" type="TaxTableEntryTaxCode"/&gt;
     * </pre>
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxCode(): TaxCode
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->taxCode->get()));
        return $this->taxCode;
    }

    /**
     * Get if is set TaxCode
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxCode(): bool
    {
        return isset($this->taxCode);
    }

    /**
     * Set TaxCode<br>
     * In case field 2.5.1.1. – TaxType = IVA, the field must be filled in with:<br>
     * “RED” – Reduced tax rate;<br>
     * “INT” – Intermediate tax rate;<br>
     * “NOR” – Normal tax rate;<br>
     * “ISE” – Exempted;<br>
     * “OUT” – Others, applicable to the special VAT regimes.<br>
     * In case field 2.5.1.1. – TaxType = IS, it shall be filled in with:<br>
     * The correspondent code of the Stamp Duty’s table;<br>
     * “ISE” – Exempted.<br>
     * In case it is not subject to tax it shall be filled in with “NS”.
     * In receipts issued without tax discriminated it shall be filled in with “NA”.
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
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->taxCode->get()));
    }

    /**
     * Get description<br>
     * In the case of Stamp Duty, the field shall be filled in with the
     * respective table code description.
     * <pre>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * </pre>
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getDescription(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->description));
        return $this->description;
    }

    /**
     * Get if is set Description
     * @return bool
     * @since 1.0.0
     */
    public function issetDescription(): bool
    {
        return isset($this->description);
    }

    /**
     * Set description<br>
     * In the case of Stamp Duty, the field shall be filled in with the
     * respective table code description.
     * <pre>
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * </pre>
     * @param string $description
     * @return bool true if the value is valid
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function setDescription(string $description): bool
    {
        try {
            $this->description = static::valTextMandMaxCar(
                $description, 255, __METHOD__
            );
            $return            = true;
        } catch (AuditFileException $e) {
            $this->description = $description;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_Description_not_valid");
            $return            = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." getted '%s'", $this->description));
        return $return;
    }

    /**
     * Generate the tax entry field, how ever is only works for TaxType IVA
     * and TaxCode ISE, RED, INT, NOR. Only use this method after the
     * TaxType,  TaxCode and TaxCountryRegion are setted
     * @return bool
     * @since 1.0.0
     */
    public function autoGenerateDescription(): bool
    {
        if (isset($this->taxType) === false ||
            isset($this->taxCountryRegion) === false ||
            isset($this->taxCode) === false) {
            return $this->setDescription("");
        }

        switch ($this->getTaxType()->get()) {
            case TaxType::IS:
                $this->setDescription("");
                break;
            case TaxType::IVA:
                switch ($this->getTaxCode()->get()) {
                    case TaxCode::ISE:
                        $this->setDescription("Isento -");
                        break;
                    case TaxCode::RED:
                        $this->setDescription("Reduzido -");
                        break;
                    case TaxCode::INT:
                        $this->setDescription("Intermédio -");
                        break;
                    case TaxCode::NOR:
                        $this->setDescription("Normal -");
                        break;
                    default :
                        $this->setDescription("");
                }
                break;
            case TaxType::NS:
                $this->setDescription("Não sujeição -");
                break;
            default :
                return $this->setDescription("");
        }

        switch ($this->getTaxCountryRegion()->get()) {
            case TaxCountryRegion::PT_AC:
                return $this->setDescription(
                    $this->getDescription()." Região autónoma dos Açores"
                );
            case TaxCountryRegion::PT_MA:
                return $this->setDescription(
                    $this->getDescription()." Região autónoma da Madaeira"
                );
            case TaxCountryRegion::ISO_PT:
                return $this->setDescription(
                    $this->getDescription()." Portugal continental"
                );
            default :
                return $this->setDescription("");
        }
    }

    /**
     * Get TaxExpiration<br>
     * The last legal date to apply the tax rate, in the case of alteration
     * of the same, at the time of the taxation period in force.
     * <pre>
     * &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     * </pre>
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getTaxExpirationDate(): ?RDate
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxExpirationDate === null ?
                    "null" : $this->taxExpirationDate->format(RDate::SQL_DATE)
                )
            );
        return $this->taxExpirationDate;
    }

    /**
     * Set TaxExpiration
     * The last legal date to apply the tax rate, in the case of alteration
     * of the same, at the time of the taxation period in force.
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
            ->debug(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxExpirationDate === null ?
                    "null" : $this->taxExpirationDate->format(RDate::SQL_DATE)
                )
            );
    }

    /**
     * Get taxPercentage<br>
     * It is required to fill in this field, if we are dealing with a tax percentage.
     * In case of exemption or not subject to tax, fill in with “0” (zero).
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @return float|null
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxPercentage(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxPercentage === null ? "null" :
                    \strval($this->taxPercentage)
                )
            );
        return $this->taxPercentage;
    }

    /**
     * Get taxPercentage<br>
     * It is required to fill in this field, if we are dealing with a tax percentage.
     * In case of exemption or not subject to tax, fill in with “0” (zero).
     * <pre>
     * &lt;xs:choice&gt;
     *    &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @param float|null $taxPercentage
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxPercentage(?float $taxPercentage): bool
    {
        try {
            if ($this->getTaxAmount() !== null) {
                $msg = "TaxAmount is already setted, is only "
                    ."possible to set one of TaxAmount or TaxPercentage";
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            if ($taxPercentage !== null && $taxPercentage < 0.0) {
                $msg = "TaxPercentage must be equal or greater than zero";
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            $return = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_TaxPercentage_not_valid");
        }
        $this->taxPercentage = $taxPercentage;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxPercentage === null ?
                    "null" : \strval($this->taxPercentage)
                )
            );
        return $return;
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
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxAmount === null ?
                    "null" : \strval($this->taxAmount)
                )
            );
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
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxAmount(?float $taxAmount): bool
    {
        try {
            if ($this->getTaxPercentage() !== null) {
                $msg = "TaxPercentage is already setted, is only "
                    ."possible to set one of TaxAmount or TaxPercentage";
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }

            if ($taxAmount !== null && $taxAmount < 0.0) {
                $msg = "TaxAmount must be equal or greater than zero";
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            $return = false;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_TaxAmount_not_valid");
        }
        $this->taxAmount = $taxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxAmount === null ?
                    "null" : \strval($this->taxAmount)
                )
            );
        return $return;
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
        if ($node->getName() !== MasterFiles::N_TAXTABLE) {
            $msg = sprintf(
                "The node name where '%s' is created must be '%s', but '%s' node was passed as argument",
                static::N_TAXTABLEENTRY, MasterFiles::N_TAXTABLE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $taxTableEntryNode = $node->addChild(static::N_TAXTABLEENTRY);

        if (isset($this->taxType)) {
            $taxTableEntryNode->addChild(
                static::N_TAXTYPE, $this->getTaxType()->get()
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAXTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_TaxType_not_valid");
        }

        if (isset($this->taxCountryRegion)) {
            $taxTableEntryNode->addChild(
                static::N_TAXCOUNTRYREGION, $this->getTaxCountryRegion()->get()
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAXCOUNTRYREGION);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_CountryRegion_not_valid");
        }

        if (isset($this->taxCode)) {
            $taxTableEntryNode->addChild(
                static::N_TAXCODE, $this->getTaxCode()->get()
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAXCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_TaxCode_not_valid");
        }

        if (isset($this->description) === false) {
            $this->autoGenerateDescription();
        }

        $taxTableEntryNode->addChild(
            static::N_DESCRIPTION, $this->getDescription()
        );

        if ($this->getTaxExpirationDate() !== null) {
            $taxTableEntryNode->addChild(
                static::N_TAXEXPIRATIONDATE,
                $this->getTaxExpirationDate()->format(RDate::SQL_DATE)
            );
        }

        if ($this->getTaxPercentage() !== null && $this->getTaxAmount() !== null) {
            $msg = sprintf(
                "Only one of both must be setted '%s' or '%s'",
                static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_TaxAmount_and_Percentage_setted");
        }

        if ($this->getTaxPercentage() !== null) {
            $taxTableEntryNode->addChild(
                static::N_TAXPERCENTAGE,
                \strval($this->getTaxPercentage())
            );
        }

        if ($this->getTaxAmount() !== null) {
            $taxTableEntryNode->addChild(
                static::N_TAXAMOUNT,
                \strval($this->getTaxAmount())
            );
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
        if ($node->getName() !== static::N_TAXTABLEENTRY) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_TAXTABLEENTRY, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxType(new TaxType((string) $node->{static::N_TAXTYPE}));
        $this->setTaxCountryRegion(new TaxCountryRegion((string) $node->{static::N_TAXCOUNTRYREGION}));
        $this->setTaxCode(new TaxCode((string) $node->{static::N_TAXCODE}));
        $this->setDescription((string) $node->{static::N_DESCRIPTION});

        if ($node->{static::N_TAXEXPIRATIONDATE}->count() > 0) {
            $date = RDate::parse(
                RDate::SQL_DATE,
                (string) $node->{static::N_TAXEXPIRATIONDATE}
            );
            $this->setTaxExpirationDate($date);
        } else {
            $this->setTaxExpirationDate(null);
        }

        if ($node->{static::N_TAXPERCENTAGE}->count() > 0 && $node->{static::N_TAXAMOUNT}->count()
            > 0) {
            $msg = sprintf(
                "Only one of both must be setted '%s' or '%s'",
                static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_TAXPERCENTAGE}->count() > 0) {
            $this->setTaxPercentage((float) $node->{static::N_TAXPERCENTAGE});
        } elseif ($node->{static::N_TAXAMOUNT}->count() > 0) {
            $this->setTaxAmount((float) $node->{static::N_TAXAMOUNT});
        } else {
            $msg = sprintf(
                "One of both must be setted '%s' or '%s'",
                static::N_TAXAMOUNT, static::N_TAXPERCENTAGE
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
    }
}