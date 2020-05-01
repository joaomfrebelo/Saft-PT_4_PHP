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
 * Estrutura de Moradas para Portugal<br>
 * <xs:complexType name="AddressStructurePT">
 * @author João Rebelo
 * @since 1.0.0
 */
class AddressPT extends AAddress
{
    /**
     * <xs:element name="PostalCode" type="PostalCodePT"/>
     * @var PostalCodePT
     * @since 1.0.0
     */
    private PostalCodePT $postalCodePT;

    /**
     * <xs:complexType name="AddressStructurePT">
     * @since 1.0.0
     */
    function __construct()
    {
        parent::__construct();
        $this->country = new Country(Country::ISO_PT);
    }

    /**
     * <xs:element ref="Country"/>
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
     * <xs:element name="PostalCode" type="PostalCodePT"/>
     *
     * @return \Rebelo\SaftPt\AuditFile\PostalCodePT
     * @since 1.0.0
     */
    public function getPostalCode(): PostalCodePT
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'",
                    $this->postalCodePT->getPostalCode()));
        return $this->postalCodePT;
    }

    /**
     * <xs:element name="PostalCode" type="PostalCodePT"/>

     * @param \Rebelo\SaftPt\AuditFile\PostalCodePT $postalCodePT
     * @return void
     * @since 1.0.0
     */
    public function setPostalCode(PostalCodePT $postalCodePT): void
    {
        $this->postalCodePT = $postalCodePT;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'",
                    $this->postalCodePT->getPostalCode()));
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
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        parent::parseXmlNode($node);
        $this->setPostalCode(
            new PostalCodePT(
                $node->{static::N_POSTALCODE}->__toString()
            )
        );
    }
}