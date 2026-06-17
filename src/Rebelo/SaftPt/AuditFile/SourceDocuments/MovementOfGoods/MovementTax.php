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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

use Decimal\Decimal;
use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\TaxCountryRegion;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * MovementTax
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class MovementTax extends AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const string N_TAX = "Tax";

    /**
     * Node name
     * @since 1.0.0
     */
    const string N_TAX_TYPE = "TaxType";

    /**
     * Node name
     * @since 1.0.0
     */
    const string N_TAX_COUNTRY_REGION = "TaxCountryRegion";

    /**
     * Node name
     * @since 1.0.0
     */
    const string N_TAX_CODE = "TaxCode";

    /**
     * Node name
     * @since 1.0.0
     */
    const string N_TAX_PERCENTAGE = "TaxPercentage";

    /**
     * &lt;xs:element name="TaxType" type="SAFTPTMovementTaxType"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType
     * @since 1.0.0
     */
    private MovementTaxType $taxType;

    /**
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\TaxCountryRegion
     * @since 1.0.0
     */
    private TaxCountryRegion $taxCountryRegion;

    /**
     * &lt;xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/&gt;
     *
     * @var \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode
     * @since 1.0.0
     */
    private MovementTaxCode $taxCode;

    /**
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * @var \Decimal\Decimal
     * @since 1.0.0
     */
    private Decimal $taxPercentage;

    /**
     * MovementTax
     * <pre>
     * &lt;xs:complexType name="MovementTax"&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element name="TaxType" type="SAFTPTMovementTaxType"/&gt;
     *        &lt;xs:element ref="TaxCountryRegion"/&gt;
     *        &lt;xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/&gt;
     *        &lt;xs:element ref="TaxPercentage"/&gt;
     *    &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get TaxType<br>
     * This field shall be filled in with:<br>
     * “IVA” – Value Added Tax;<br>
     * “NS” – Not subject to VAT.<br>
     * &lt;xs:element name="TaxType" type="SAFTPTMovementTaxType"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxType(): MovementTaxType
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
     * This field shall be filled in with:<br>
     * “IVA” – Value Added Tax;<br>
     * “NS” – Not subject to VAT.<br>
     * &lt;xs:element name="TaxType" type="SAFTPTMovementTaxType"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxType $taxType
     * @return void
     * @since 1.0.0
     */
    public function setTaxType(MovementTaxType $taxType): void
    {
        $this->taxType = $taxType;
        AAuditFile::$logger?->debug(\sprintf(__METHOD__." set to '%s'", $this->taxType->value));
    }

    /**
     * Get TaxCountryRegion<br>
     * The field shall be filled in according to norm ISO 3166-1-alpha-2.
     * In the case of the Autonomous Regions of the Azores and Madeira Island it must be filled in with:
     * “PT-AC” - Fiscal area of the Autonomous Region of the Azores;
     * “PT-MA” - Fiscal area of the Autonomous Region of the Madeira Island.<br>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
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
     * The field shall be filled in according to norm ISO 3166-1-alpha-2.
     * In the case of the Autonomous Regions of the Azores and Madeira Island it must be filled in with:
     * “PT-AC” - Fiscal area of the Autonomous Region of the Azores;
     * “PT-MA” - Fiscal area of the Autonomous Region of the Madeira Island.<br>
     * &lt;xs:element ref="TaxCountryRegion"/&gt;
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
     * Tax rate code in the table of taxes.<br>
     * Shall be filled in with:<br>
     * “RED” - Reduced tax rate;<br>
     * “INT” - Intermediate tax rate;<br>
     * “NOR” - Normal tax rate;<br>
     * “ISE” - Exempted;<br>
     * “OUT” - Other, applicable to the special VAT regimes.<br>
     * In case of not subject to tax, to fill in with “NS”.<br>
     * &lt;xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode
     * @since 1.0.0
     */
    public function getTaxCode(): MovementTaxCode
    {
        AAuditFile::$logger?->info(\sprintf(__METHOD__." get '%s'", $this->taxCode->value));
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

    /**     *
     * Set TaxCode<br>
     * Tax rate code in the table of taxes.<br>
     * Shall be filled in with:<br>
     * “RED” - Reduced tax rate;<br>
     * “INT” - Intermediate tax rate;<br>
     * “NOR” - Normal tax rate;<br>
     * “ISE” - Exempted;<br>
     * “OUT” - Other, applicable to the special VAT regimes.<br>
     * In case of not subject to tax, to fill in with “NS”.<br>
     * &lt;xs:element name="TaxCode" type="SAFTPTMovementTaxCode"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods\MovementTaxCode $taxCode
     * @return void
     * @since 1.0.0
     */
    public function setTaxCode(MovementTaxCode $taxCode): void
    {
        $this->taxCode = $taxCode;
        AAuditFile::$logger?->debug(\sprintf(__METHOD__." set to '%s'", $this->taxCode->value));
    }

    /**
     * Get TaxPercentage<br>
     * Percentage of the tax rate corresponding to the tax applicable to the field 4.2.3.21.10. – DebitAmount or to field 4.2.3.21.11. - CreditAmount.
     * In case of exemption or not subject to tax, fill in with “0” (zero).<br>
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * @return \Decimal\Decimal
     * @throws \Error
     * @since 1.0.0
     */
    public function getTaxPercentage(): Decimal
    {
        AAuditFile::$logger?->info(
            \sprintf(
                __METHOD__." get '%s'",
                \strval($this->taxPercentage)
            )
        );
        return $this->taxPercentage;
    }

    /**
     * Get if is set TaxPercentage
     * @return bool
     * @since 1.0.0
     */
    public function issetTaxPercentage(): bool
    {
        return isset($this->taxPercentage);
    }

    /**
     * Set TaxPercentage<br>
     * Percentage of the tax rate corresponding to the tax applicable to the field 4.2.3.21.10. – DebitAmount or to field 4.2.3.21.11. - CreditAmount.
     * In case of exemption or not subject to tax, fill in with “0” (zero).<br>
     * &lt;xs:element ref="TaxPercentage"/&gt;
     * @param Decimal $taxPercentage
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setTaxPercentage(Decimal $taxPercentage): bool
    {
        if ($taxPercentage->compareTo("0.0") < 0) {
            $msg    = "TaxPercentage can not be negative";
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("TaxPercentage_not_valid");
        } else {
            $return = true;
        }
        $this->taxPercentage = $taxPercentage;
        AAuditFile::$logger?->debug(
            \sprintf(
                __METHOD__." set to '%s'",
                \strval($this->taxPercentage)
            )
        );
        return $return;
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
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== Line::N_LINE) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s", Line::N_LINE,
                $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $movTaxNode = $node->addChild(static::N_TAX);

        if (isset($this->taxType)) {
            $movTaxNode->addChild(
                static::N_TAX_TYPE, $this->getTaxType()->value
            );
        } else {
            $movTaxNode->addChild(static::N_TAX_TYPE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxType_not_valid");
        }

        if (isset($this->taxCountryRegion)) {
            $movTaxNode->addChild(
                static::N_TAX_COUNTRY_REGION, $this->getTaxCountryRegion()->value
            );
        } else {
            $movTaxNode->addChild(static::N_TAX_COUNTRY_REGION);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxCountryRegion_not_valid");
        }

        if (isset($this->taxCode)) {
            $movTaxNode->addChild(
                static::N_TAX_CODE, $this->getTaxCode()->value
            );
        } else {
            $movTaxNode->addChild(static::N_TAX_CODE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxCode_not_valid");
        }

        if (isset($this->taxPercentage)) {
            $movTaxNode->addChild(
                static::N_TAX_PERCENTAGE,
                $this->floatFormat($this->getTaxPercentage())
            );
        } else {
            $movTaxNode->addChild(static::N_TAX_PERCENTAGE);
            $this->getErrorRegistor()->addOnCreateXmlNode("TaxPercentage_not_valid");
        }

        return $movTaxNode;
    }

    /**
     * Parse the XML node
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== static::N_TAX) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s'",
                static::N_TAX, $node->getName()
            );
            AAuditFile::$logger?->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $type = MovementTaxType::from((string) $node->{static::N_TAX_TYPE});

        $this->setTaxType($type);

        $country = TaxCountryRegion::from((string) $node->{static::N_TAX_COUNTRY_REGION});
        $this->setTaxCountryRegion($country);

        $code = MovementTaxCode::from((string) $node->{static::N_TAX_CODE});
        $this->setTaxCode($code);

        $this->setTaxPercentage(new Decimal((string) $node->{static::N_TAX_PERCENTAGE}));
    }
}
