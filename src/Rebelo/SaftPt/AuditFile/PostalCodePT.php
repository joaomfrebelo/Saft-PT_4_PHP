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

/**
 * PostalCodePT
 * <pre>
 * <xs:simpleType name="PostalCodePT">
 * <xs:restriction base="xs:string">
 * <xs:pattern value="([0-9]{4}-[0-9]{3})"/>
 * </pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class PostalCodePT
    extends AAuditFile
{

    /**
     * <pre>
     * <xs:pattern value="([0-9]{4}-[0-9]{3})"/>
     * </pre>
     * @var string
     * @since 1.0.0
     */
    private string $postalCodePT;

    /**
     * <xs:simpleType name="PostalCodePT"><br>
     * <xs:pattern value="([0-9]{4}-[0-9]{3})"/><br>
     * @param string $postalCodePT
     * @since 1.0.0
     */
    public function __construct(string $postalCodePT = null)
    {
        parent::__construct();
        if ($postalCodePT !== null)
        {
            $this->setPostalCode($postalCodePT);
        }
    }

    /**
     * <xs:simpleType name="PostalCodePT"><br>
     * <xs:pattern value="([0-9]{4}-[0-9]{3})"/><br>
     * @return string
     * @since 1.0.0
     */
    public function getPostalCode(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->postalCodePT));
        return $this->postalCodePT;
    }

    /**
     * <xs:simpleType name="PostalCodePT"><br>
     * <xs:pattern value="([0-9]{4}-[0-9]{3})"/><br>     *
     * @param string $postalCode
     * @return void
     * @since 1.0.0
     */
    public function setPostalCode(string $postalCode): void
    {
        if (\preg_match("/^([0-9]{4}-[0-9]{3})$/", $postalCode) !== 1)
        {
            $msg = "PostalCodePT must respect /^([0-9]{4}-[0-9]{3})$/";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $this->postalCodePT = $postalCode;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->postalCodePT));
    }

    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $msg = "The xml node is created in the aadress class"
            . $node->__toString();
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__ . " '%s'", $msg));
        throw new AuditFileException($msg);
    }

    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        $msg = "The xml parse node is created in the aadress class "
            . $node->__toString();
        \Logger::getLogger(\get_class($this))
            ->error(\sprintf(__METHOD__ . " '%s'", $msg));
        throw new AuditFileException($msg);
    }

}
