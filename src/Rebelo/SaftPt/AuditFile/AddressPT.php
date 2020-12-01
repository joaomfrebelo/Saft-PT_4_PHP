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

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\SaftPt\AuditFile\PostalCodePT;

/**
 * AddressPT<br>
 * Portuguese address structur<br>
 * &lt;xs:complexType name="AddressStructurePT">
 * @author João Rebelo
 * @since 1.0.0
 */
class AddressPT extends AAddress
{
    /**
     * &lt;xs:element name="PostalCode" type="PostalCodePT"/&gt;
     * @var string
     * @since 1.0.0
     */
    protected string $postalCodePT;

    /**
     * &lt;xs:complexType name="AddressStructurePT">
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
        $this->country = new Country(Country::ISO_PT);
    }

    /**
     * Get Country<br>
     * &lt;xs:element ref="Country"/&gt;
     * @return \Rebelo\SaftPt\AuditFile\Country
     * @since 1.0.0
     */
    public function getCountry(): Country
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->country->get()));
        return $this->country;
    }

    /**
     * Get PostalCode<br>
     * This get will create the PostalCodePT instance
     * &lt;xs:element name="PostalCode" type="PostalCodePT"/&gt;     *
     * @return string
     * @throws \Error
     * @since 1.0.0
     */
    public function getPostalCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->postalCodePT));
        return $this->postalCodePT;
    }

    /**
     * Get if is set PostalCode
     * @return bool
     * @since 1.0.0
     */
    public function issetPostalCode(): bool
    {
        return isset($this->postalCodePT);
    }

    /**
     * Set PostalCode
     * &lt;xs:simpleType name="PostalCodePT"><br>
     * &lt;xs:pattern value="([0-9]{4}-[0-9]{3})"/&gt;<br>     *
     * @param string $postalCode
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function setPostalCode(string $postalCode): bool
    {
        if (\preg_match("/^([0-9]{4}-[0-9]{3})$/", $postalCode) !== 1) {
            $msg    = "PostalCodePT must respect /^([0-9]{4}-[0-9]{3})$/";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            $return = false;
        } else {
            $return = true;
        }
        $this->postalCodePT = $postalCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." set to '%s'", $this->postalCodePT));
        return $return;
    }

    /**
     * Create the child nodes to the address<br>
     * In the case of this nodeXml, address only will create the address
     * child node, the address root node will be created by the invoker
     * because cane be CompanyAddress or SupplierAddres or CustomerAddress, etc
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::createXmlNode($node);
        $node->addChild(static::N_COUNTRY, $this->getCountry()->get());
        return $node;
    }

    /**
     * Parse XML node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::parseXmlNode($node);
        $this->setPostalCode(
            (string) $node->{static::N_POSTALCODE}
        );
    }
}