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

namespace Rebelo\SaftPt\AuditFile\MasterFiles;

use Decimal\Decimal;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\Pattern;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
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
class TaxTableEntry extends AAuditFile
{
    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_TABLE_ENTRY = "TaxTableEntry";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_TYPE = "TaxType";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_COUNTRY_REGION = "TaxCountryRegion";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_CODE = "TaxCode";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_DESCRIPTION = "Description";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_EXPIRATION_DATE = "TaxExpirationDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_PERCENTAGE = "TaxPercentage";

    /**
     * Node Name
     * @since 1.0.0
     */
    const string N_TAX_AMOUNT = "TaxAmount";

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
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode|string $taxCode
     * @since 1.0.0
     */
    private TaxCode|string $taxCode;

    /**
     * &lt;xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/&gt;
     * @var string $description
     * @since 1.0.0
     */
    private string $description;

    /**
     *  &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     *
     * @var \Rebelo\Date\Date|null $taxExpirationDate
     * @since 1.0.0
     */
    private ?RDate $taxExpirationDate = null;

    /**
     * <pre>
     * &lt;xs:choice&gt;
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @var \Decimal\Decimal|null $taxPercentage
     * @since 1.0.0
     */
    private Decimal|null $taxPercentage = null;

    /**
     * <pre>
     * &lt;xs:choice&gt;
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * &lt;xs:element ref="TaxAmount"/&gt;
     * &lt;/xs:choice&gt;
     * </pre>
     * @var \Decimal\Decimal|null $taxAmount
     * @since 1.0.0
     */
    private Decimal|null $taxAmount = null;

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
        AAuditFile::$logger?->info(\sprintf(__METHOD__." get '%s'", $this->taxType->value));
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
        AAuditFile::$logger?->debug(\sprintf(__METHOD__." set to '%s'", $this->taxType->value));
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
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
                $this->taxCountryRegion->value
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
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." set to '%s'",
                $this->taxCountryRegion->value
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
     * @return \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode|string
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxCode(): TaxCode|string
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
                (is_string($this->taxCode) ? $this->taxCode : $this->taxCode->value)
            )
        );
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
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode|string $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(TaxCode|string $taxCode): void
    {
        $this->taxCode = $taxCode;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." set to '%s'",
                is_string($this->taxCode)
                        ? $this->taxCode
                        : $this->taxCode->value
            )
        );
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
        AAuditFile::$logger?->info(\sprintf(__METHOD__." get '%s'", $this->description));
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
     * @since 1.0.0
     */
    public function setDescription(string $description): bool
    {
        try {
            $this->description = static::valTextMandatoryMaxCar(
                $description, 255, __METHOD__
            );
            $return            = true;
        } catch (AuditFileException $e) {
            $this->description = $description;
            AAuditFile::$logger?->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_Description_not_valid");
            $return            = false;
        }
        AAuditFile::$logger?->debug(\sprintf(__METHOD__." get '%s'", $this->description));
        return $return;
    }

    /**
     * Generate the tax entry field, however is only works for TaxType IVA
     * and TaxCode ISE, RED, INT, NOR. Only use this method after the
     * TaxType,  TaxCode and TaxCountryRegion are set
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

        switch ($this->getTaxType()) {
            case TaxType::IS:
                $this->setDescription("");
                break;
            case TaxType::IVA:
                switch ($this->getTaxCode()) {
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
            /** @noinspection PhpUnusedSwitchBranchInspection */
            default :
                return $this->setDescription("");
        }

        return match ($this->getTaxCountryRegion()) {
            TaxCountryRegion::ISO_PT_AC => $this->setDescription(
                $this->getDescription() . " Região autónoma dos Açores"
            ),
            TaxCountryRegion::ISO_PT_MA => $this->setDescription(
                $this->getDescription() . " Região autónoma da Madeira"
            ),
            TaxCountryRegion::ISO_PT => $this->setDescription(
                $this->getDescription() . " Portugal continental"
            ),
            default => $this->setDescription(""),
        };
    }

    /**
     * Get TaxExpiration<br>
     * The last legal date to apply the tax rate, in the case of alteration
     * of the same, at the time of the taxation period in force.
     * <pre>
     * &lt;xs:element ref="TaxExpirationDate" minOccurs="0"/&gt;
     * </pre>
     *
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getTaxExpirationDate(): ?RDate
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
                $this->taxExpirationDate === null ?
                    "null" : $this->taxExpirationDate->format(Pattern::SQL_DATE)
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
     *
     * @param \Rebelo\Date\Date|null $taxExpirationDate
     *
     * @return void
     * @since 1.0.0
     */
    public function setTaxExpirationDate(?RDate $taxExpirationDate): void
    {
        $this->taxExpirationDate = $taxExpirationDate;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." get '%s'",
                $this->taxExpirationDate === null ?
                    "null" : $this->taxExpirationDate->format(Pattern::SQL_DATE)
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
     * @return Decimal|null
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxPercentage(): Decimal|null
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
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
     * @param Decimal|null $taxPercentage
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxPercentage(Decimal|null $taxPercentage): bool
    {
        try {
            if ($this->getTaxAmount() !== null) {
                $msg = "TaxAmount is already set, is only "
                    ."possible to set one of TaxAmount or TaxPercentage";
                AAuditFile::$logger?->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            if ($taxPercentage !== null && $taxPercentage->compareTo("0.0") < 0) {
                $msg = "TaxPercentage must be equal or greater than zero";
                AAuditFile::$logger?->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            $return = false;
            AAuditFile::$logger?->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_TaxPercentage_not_valid");
        }
        $this->taxPercentage = $taxPercentage;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." get '%s'",
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
     * @return Decimal|null
     * @since 1.0.0
     */
    public function getTaxAmount(): Decimal|null
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
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
     * @param Decimal|null $taxAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxAmount(Decimal|null $taxAmount): bool
    {
        try {
            if ($this->getTaxPercentage() !== null) {
                $msg = "TaxPercentage is already set, is only "
                    ."possible to set one of TaxAmount or TaxPercentage";
                AAuditFile::$logger?->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }

            if ($taxAmount !== null && $taxAmount->compareTo("0.0") < 0) {
                $msg = "TaxAmount must be equal or greater than zero";
                AAuditFile::$logger?->debug(__METHOD__." ".$msg);
                throw new AuditFileException($msg);
            }
            $return = true;
        } catch (AuditFileException $e) {
            $return = false;
            AAuditFile::$logger?->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("TaxEntry_TaxAmount_not_valid");
        }
        $this->taxAmount = $taxAmount;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." get '%s'",
                $this->taxAmount === null ?
                    "null" : \strval($this->taxAmount)
            )
        );
        return $return;
    }

    /**
     * Create the TaxTableEntry node in the TaxTable node
     *
     * @param \SimpleXMLElement $node The TaxTable node
     *
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        AAuditFile::$logger?->info(__METHOD__);
        if ($node->getName() !== MasterFiles::N_TAX_TABLE) {
            $msg = sprintf(
                "The node name where '%s' is created must be '%s', but '%s' node was passed as argument",
                static::N_TAX_TABLE_ENTRY, MasterFiles::N_TAX_TABLE,
                $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $taxTableEntryNode = $node->addChild(static::N_TAX_TABLE_ENTRY);

        if (isset($this->taxType)) {
            $taxTableEntryNode->addChild(
                static::N_TAX_TYPE, $this->getTaxType()->value
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAX_TYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_TaxType_not_valid");
        }

        if (isset($this->taxCountryRegion)) {
            $taxTableEntryNode->addChild(
                static::N_TAX_COUNTRY_REGION, $this->getTaxCountryRegion()->value
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAX_COUNTRY_REGION);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_CountryRegion_not_valid");
        }

        if (isset($this->taxCode)) {
            $taxTableEntryNode->addChild(
                static::N_TAX_CODE,
                is_string($this->getTaxCode())
                    ? $this->getTaxCode()
                    : $this->getTaxCode()->value
            );
        } else {
            $taxTableEntryNode->addChild(static::N_TAX_CODE);
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
                static::N_TAX_EXPIRATION_DATE,
                $this->getTaxExpirationDate()->format(Pattern::SQL_DATE)
            );
        }

        if ($this->getTaxPercentage() !== null && $this->getTaxAmount() !== null) {
            $msg = sprintf(
                "Only one of both must be set '%s' or '%s'",
                static::N_TAX_AMOUNT, static::N_TAX_PERCENTAGE
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxEntry_TaxAmount_and_Percentage_set");
        }

        if ($this->getTaxPercentage() !== null) {
            $taxTableEntryNode->addChild(
                static::N_TAX_PERCENTAGE,
                \strval($this->getTaxPercentage())
            );
        }

        if ($this->getTaxAmount() !== null) {
            $taxTableEntryNode->addChild(
                static::N_TAX_AMOUNT,
                \strval($this->getTaxAmount())
            );
        }

        return $taxTableEntryNode;
    }

    /**
     * Parse the TaxTableEntry node
     *
     * @param \SimpleXMLElement $node
     *
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);
        if ($node->getName() !== static::N_TAX_TABLE_ENTRY) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s",
                static::N_TAX_TABLE_ENTRY, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxType(TaxType::from((string) $node->{static::N_TAX_TYPE}));
        $this->setTaxCountryRegion(TaxCountryRegion::from((string) $node->{static::N_TAX_COUNTRY_REGION}));
        $this->setTaxCode(TaxCode::tryFrom((string)$node->{static::N_TAX_CODE}) ?? (string)$node->{static::N_TAX_CODE});
        $this->setDescription((string) $node->{static::N_DESCRIPTION});

        if ($node->{static::N_TAX_EXPIRATION_DATE}->count() > 0) {
            $date = RDate::parse(
                Pattern::SQL_DATE,
                (string) $node->{static::N_TAX_EXPIRATION_DATE}
            );
            $this->setTaxExpirationDate($date);
        } else {
            $this->setTaxExpirationDate(null);
        }

        if ($node->{static::N_TAX_PERCENTAGE}->count() > 0 && $node->{static::N_TAX_AMOUNT}->count()
            > 0) {
            $msg = sprintf(
                "Only one of both must be set '%s' or '%s'",
                static::N_TAX_AMOUNT, static::N_TAX_PERCENTAGE
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        if ($node->{static::N_TAX_PERCENTAGE}->count() > 0) {
            $this->setTaxPercentage(new Decimal((string) $node->{static::N_TAX_PERCENTAGE}));
        } elseif ($node->{static::N_TAX_AMOUNT}->count() > 0) {
            $this->setTaxAmount(new Decimal((string) $node->{static::N_TAX_AMOUNT}));
        } else {
            $msg = sprintf(
                "One of both must be set '%s' or '%s'",
                static::N_TAX_AMOUNT, static::N_TAX_PERCENTAGE
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
    }
}
