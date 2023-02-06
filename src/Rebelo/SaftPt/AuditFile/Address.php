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
 * &lt;xs:complexType name="AddressStructure">
 * @author João Rebelo
 * @since 1.0.0
 */
class Address extends AAddress
{
    /**
     * &lt;xs:element ref="PostalCode"/&gt;
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     * @var string
     * @since 1.0.0
     */
    private string $postalCode;

    /**
     * Address<br>
     * &lt;xs:complexType name="AddressStructure">
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Get PostalCode<br>
     * &lt;xs:element ref="PostalCode"/&gt;<br>
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;
     * @return string
     * @throws \Error
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
     * Set PostalCode<br>
     * &lt;xs:element ref="PostalCode"/&gt;<br>
     * &lt;xs:element name="PostalCode" type="SAFPTtextTypeMandatoryMax20Car"/&gt;<br>
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
     * &lt;xs:element ref="Country"/&gt;<br>
     * @param \Rebelo\SaftPt\AuditFile\Country $country
     * @return void
     * @since 1.0.0
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->country->get()));
    }

    /**
     * Get Country
     * &lt;xs:element ref="Country"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\Country
     * @throws \Error
     * @since 1.0.0
     */
    public function getCountry(): Country
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." get '%s'", $this->country->get()));
        return $this->country;
    }

    /**
     * Get if is set Country
     * @return bool
     * @since 1.0.0
     */
    public function issetCountry(): bool
    {
        return isset($this->country);
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
        if (isset($this->country)) {
            $node->addChild(static::N_COUNTRY, $this->getCountry()->get());
        } else {
            $node->addChild(static::N_COUNTRY);
            $this->getErrorRegistor()->addOnCreateXmlNode("AddressDetail_not_valid");
        }
        return $node;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::parseXmlNode($node);
        $this->setPostalCode((string) $node->{static::N_POSTALCODE});
        $this->setCountry(new Country((string) $node->{static::N_COUNTRY}));
    }
}
