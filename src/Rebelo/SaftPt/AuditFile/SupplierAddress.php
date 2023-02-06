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

namespace Rebelo\SaftPt\AuditFile;

/**
 * AddressPT<br>
 * Estrutura de Moradas para Portugal<br>
 * &lt;xs:complexType name="SupplierAddressStructure">
 * @author João Rebelo
 * @since 1.0.0
 */
class SupplierAddress extends AAddress
{
    /**
     * &lt;xs:element ref="Country"/&gt;
     * @var \Rebelo\SaftPt\AuditFile\SupplierCountry
     * @since 1.0.0
     */
    protected SupplierCountry $suplierCountry;

    /**
     * &lt;xs:element ref="PostalCode"/&gt;
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     * @var string
     * @since 1.0.0
     */
    private string $postalCode;

    /**
     * &lt;xs:complexType name="SupplierAddressStructure">
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get PostalCode<br>
     * &lt;xs:element ref="PostalCode"/&gt;
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     * @return string
     * @since 1.0.0
     */
    public function getPostalCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->postalCode));
        return $this->postalCode;
    }

    /**
     * Get if is set PostalCode
     * @return bool
     * @since 1.0.0
     */
    public function issetPostalCode(): bool
    {
        return isset($this->postalCode);
    }

    /**
     * &lt;xs:element ref="PostalCode"/&gt;
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     *
     * @param string $postalCode
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setPostalCode(string $postalCode): bool
    {
        try {
            $this->postalCode = static::valTextMandMaxCar(
                $postalCode, 20, __METHOD__
            );
            $return           = true;
        } catch (AuditFileException $e) {
            $this->postalCode = $postalCode;
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__."  '%s'", $e->getMessage()));
            $this->getErrorRegistor()->addOnSetValue("PostalCode_not_valid");
            $return           = false;
        }
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->postalCode));
        return $return;
    }

    /**
     * Set Country<br>
     * &lt;xs:element ref="Country"/&gt;
     * @param \Rebelo\SaftPt\AuditFile\SupplierCountry $country
     * @return void
     * @since 1.0.0
     */
    public function setCountry(SupplierCountry $country): void
    {
        $this->suplierCountry = $country;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." set to '%s'",
                    $this->suplierCountry->get()
                )
            );
    }

    /**
     * Get Country
     * &lt;xs:element ref="Country"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\SupplierCountry
     * @throws \Error
     * @since 1.0.0
     */
    public function getCountry(): SupplierCountry
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." get '%s'",
                    $this->suplierCountry->get()
                )
            );
        return $this->suplierCountry;
    }

    /**
     * Get if is set Country
     * @return bool
     * @since 1.0.0
     */
    public function issetCountry(): bool
    {
        return isset($this->suplierCountry);
    }

    /**
     * Create the child nodes to the address<br>
     * In the case of this nodeXml, address only will create the address
     * child node, the address root node will be created by the invoker
     * because cane be CompanyAddress or SupplierAddres or CustomerAddress, etc
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::createXmlNode($node);

        if (isset($this->suplierCountry)) {
            $node->addChild(static::N_COUNTRY, $this->getCountry()->get());
        } else {
            $node->addChild(static::N_COUNTRY);
            $this->getErrorRegistor()->addOnCreateXmlNode("Country_not_valid");
        }
        return $node;
    }

    /**
     * Parse Xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::parseXmlNode($node);
        $this->setPostalCode((string) $node->{static::N_POSTALCODE});
        $this->setCountry(new SupplierCountry((string) $node->{static::N_COUNTRY}));
    }
}
