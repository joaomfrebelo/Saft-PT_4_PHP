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
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxType;
use Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;

/**
 * Tax
 * &lt;xs:complexType name="Tax"&gt;
 *     &lt;xs:sequence&gt;
 *         &lt;xs:element ref="TaxType"/&gt;
 *         &lt;xs:element ref="TaxCountryRegion"/&gt;
 *         &lt;xs:element ref="TaxCode"/&gt;
 *         &lt;xs:choice&gt;
 *             &lt;xs:element ref="TaxPercentage"/&gt;
 *             &lt;xs:element ref="TaxAmount"/&gt;
 *         &lt;/xs:choice&gt;
 *     &lt;/xs:sequence&gt;
 * &lt;/xs:complexType&gt;
 * @author João Rebelo
 * @since 1.0.0
 */
class Tax extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAX = "Tax";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXTYPE = "TaxType";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXCOUNTRYREGION = "TaxCountryRegion";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXCODE = "TaxCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXPERCENTAGE = "TaxPercentage";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_TAXAMOUNT = "TaxAmount";

    /**
     * &lt;xs:element ref="TaxType"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType
     * @since 1.0.0
     */
    private TaxType $taxType;

    /**
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    private TaxCountryRegion $taxCountryRegion;

    /**
     * &lt;xs:element ref="TaxCode"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode
     * @since 1.0.0
     */
    private TaxCode $taxCode;

    /**
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    private ?float $taxPercentage = null;

    /**
     * &lt;xs:element ref="TaxAmount"/&gt;
     * @var float|null
     * @since 1.0.0
     */
    private ?float $taxAmount = null;

    /**
     * Tax
     * <pre>
     * &lt;xs:complexType name="Tax"&gt;
     *     &lt;xs:sequence&gt;
     *         &lt;xs:element ref="TaxType"/&gt;
     *         &lt;xs:element ref="TaxCountryRegion"/&gt;
     *         &lt;xs:element ref="TaxCode"/&gt;
     *         &lt;xs:choice&gt;
     *             &lt;xs:element ref="TaxPercentage"/&gt;
     *             &lt;xs:element ref="TaxAmount"/&gt;
     *         &lt;/xs:choice&gt;
     *     &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
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
     * “IVA” - Value Added Tax;<br>
     * “IS” - Stamp Duty;<br>
     * “NS” - Not subject IVA or IS.<br>
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
     * Set Tax Type<br>
     * This field shall be filled in with the tax type:
     * “IVA” - Value Added Tax;<br>
     * “IS” - Stamp Duty;<br>
     * “NS” - Not subject IVA or IS.<br>
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxType $taxType
     * @return void
     * @since 1.0.0
     */
    public function setTaxType(TaxType $taxType): void
    {
        $this->taxType = $taxType;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->taxType->get()));
    }

    /**
     * Get TaxCountryRegion<br>
     * The field shall be filled in according to norm ISO 3166-1-alpha-2.<br>
     * In the case of the Autonomous Regions of the Azores and Madeira Island
     * it must be filled in with:<br>
     * “PT-AC” - Fiscal area of the Autonomous Region of the Azores;<br>
     * “PT-MA” - Fiscal area of the Autonomous Region of the Madeira Island.<br>
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
     * The field shall be filled in according to norm ISO 3166-1-alpha-2.<br>
     * In the case of the Autonomous Regions of the Azores and Madeira Island
     * it must be filled in with:<br>
     * “PT-AC” - Fiscal area of the Autonomous Region of the Azores;<br>
     * “PT-MA” - Fiscal area of the Autonomous Region of the Madeira Island.<br>
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
                    __METHOD__." set to '%s'",
                    $this->taxCountryRegion->get()
                )
            );
    }

    /**
     * Get Tax Code<br>
     * Tax rate code in the table of taxes.<br>
     * In case field 4.1.4.19.15.1. - TaxType = IVA, the field must be filled in with:<br>
     * “RED” - Reduced tax rate;<br>
     * “INT” - Intermediate tax rate;<br>
     * “NOR” - Normal tax rate;<br>
     * “ISE” - Exempted;<br>
     * “OUT” - Others, applicable to the special VAT regimes.<br>
     * In case field 4.1.4.19.15.1 TaxType = IS, the field shall be filled in with:
     * The correspondent code of the Stamp Duty’s table;<br>
     * “ISE” - Exempted.<br>
     * In case it is not subject to tax, fill in with “NS”.
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
     * Set Tax Code<br>
     * Tax rate code in the table of taxes.<br>
     * In case field 4.1.4.19.15.1. - TaxType = IVA, the field must be filled in with:<br>
     * “RED” - Reduced tax rate;<br>
     * “INT” - Intermediate tax rate;<br>
     * “NOR” - Normal tax rate;<br>
     * “ISE” - Exempted;<br>
     * “OUT” - Others, applicable to the special VAT regimes.<br>
     * In case field 4.1.4.19.15.1 TaxType = IS, the field shall be filled in with:
     * The correspondent code of the Stamp Duty’s table;<br>
     * “ISE” - Exempted.<br>
     * In case it is not subject to tax, fill in with “NS”.
     * @param \Rebelo\SaftPt\AuditFile\MasterFiles\TaxCode $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(TaxCode $taxCode): void
    {
        $this->taxCode = $taxCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->taxCode->get()));
    }

    /**
     * Get Tax Percentage<br>
     * When it deals with a tax rate percentage it is required to fill in this field.
     * Percentage of the tax rate corresponding to the tax applicable to
     * field 4.4.4.19.13. - DebitAmount or to field 4.1.4.19.14. - CreditAmount.
     * In case of exemption or not subject to tax, fill in with “0” (zero).
     * @return float|null
     * @since 1.0.0
     */
    public function getTaxPercentage(): ?float
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->taxPercentage === null ?
                    "null" : \strval($this->taxPercentage)
                )
            );
        return $this->taxPercentage;
    }

    /**
     * Set Tax Percentage<br>
     * When it deals with a tax rate percentage it is required to fill in this field.
     * Percentage of the tax rate corresponding to the tax applicable to
     * field 4.4.4.19.13. - DebitAmount or to field 4.1.4.19.14. - CreditAmount.
     * In case of exemption or not subject to tax, fill in with “0” (zero).
     * @param float|null $taxPercentage
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxPercentage(?float $taxPercentage): bool
    {
        if ($this->getTaxAmount() !== null && $taxPercentage !== null) {
            $msg    = "Tax Percentage and Tax Amount can not be set at the same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxAmount_and_Percentage_setted");
        } elseif ($taxPercentage < 0.0 || $taxPercentage > 100.00) {
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxPercentage_not_valid");
        } else {
            $return = true;
        }
        $this->taxPercentage = $taxPercentage;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->taxPercentage === null ?
                    "null" : \strval($this->taxPercentage)
                )
            );
        return $return;
    }

    /**
     * Get Tax Amount<br>
     * The filling is required, in the case of a fixed unitary fee of stamp duty.
     * This value, multiplied by the Quantity contributes for the TaxPayable field.
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
     * Set Tax Amout<br>
     * The filling is required, in the case of a fixed unitary fee of stamp duty.
     * This value, multiplied by the Quantity contributes for the TaxPayable field.
     * @param float|null $taxAmount
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxAmount(?float $taxAmount): bool
    {
        if ($this->getTaxPercentage() !== null && $taxAmount !== null) {
            $msg    = "Tax Percentage and Tax Amount can not be set at the same time";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxAmount_and_Percentage_setted");
        } elseif ($taxAmount < 0.0) {
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxAmount_not_valid");
        } else {
            $return = true;
        }
        $this->taxAmount = $taxAmount;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->taxAmount === null ?
                    "null" : \strval($this->taxAmount)
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
                "Node name should be '%s' but is '%s",
                A2Line::N_LINE, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $taxNode = $node->addChild(static::N_TAX);

        if (isset($this->taxType)) {
            $taxNode->addChild(static::N_TAXTYPE, $this->getTaxType()->get());
        } else {
            $taxNode->addChild(static::N_TAXTYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxType_not_valid");
        }

        if (isset($this->taxCountryRegion)) {
            $taxNode->addChild(
                static::N_TAXCOUNTRYREGION, $this->getTaxCountryRegion()->get()
            );
        } else {
            $taxNode->addChild(static::N_TAXCOUNTRYREGION);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxCountryRegion_not_valid");
        }

        if (isset($this->taxCode)) {
            $taxNode->addChild(static::N_TAXCODE, $this->getTaxCode()->get());
        } else {
            $taxNode->addChild(static::N_TAXCODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxCode_not_valid");
        }

        if ($this->getTaxPercentage() !== null) {
            $taxNode->addChild(
                static::N_TAXPERCENTAGE,
                $this->floatFormat($this->getTaxPercentage())
            );
        }

        if ($this->getTaxAmount() !== null) {
            $taxNode->addChild(
                static::N_TAXAMOUNT, $this->floatFormat($this->getTaxAmount())
            );
        }

        return $taxNode;
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

        if ($node->getName() !== static::N_TAX) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s", static::N_TAX,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $this->setTaxCode(new TaxCode((string) $node->{static::N_TAXCODE}));
        $this->setTaxType(new TaxType((string) $node->{static::N_TAXTYPE}));

        $this->setTaxCountryRegion(
            new TaxCountryRegion((string) $node->{static::N_TAXCOUNTRYREGION})
        );

        if ($node->{static::N_TAXPERCENTAGE}->count() > 0) {
            $this->setTaxPercentage((float) $node->{static::N_TAXPERCENTAGE});
        }

        if ($node->{static::N_TAXAMOUNT}->count() > 0) {
            $this->setTaxAmount((float) $node->{static::N_TAXAMOUNT});
        }
    }
}
