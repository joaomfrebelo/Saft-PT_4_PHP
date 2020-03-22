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

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class representing CustomsDetailsType
 * <pre>
 *   <!-- Estrutura de caraterizacao aduaneira de produtos-->
 *   <xs:complexType name="CustomsDetails">
 *       <xs:sequence>
 *           <xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/>
 *           <xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/>
 *       </xs:sequence>
 *   </xs:complexType>
 * </pre>
 * XSD Type: CustomsDetails
 * @since 1.0.0
 */
class CustomsDetails
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * <xs:complexType name="CustomsDetails">
     * @since 1.0.0
     */
    const N_CUSTOMSDETAILS = "CustomsDetails";

    /**
     * <xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_CNCODE = "CNCode";

    /**
     * <xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded">
     * @since 1.0.0
     */
    const N_UNNUMBER = "UNNumber";

    /**
     * <pre>
     * <xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="CNCode" type="SAFPTCNCode"/>
     * <xs:simpleType name="SAFPTCNCode">
     * <xs:restriction base="xs:string">
     * <xs:pattern value="[0-9]{8}"/>
     * <xs:length value="8"/>
     * </xs:restriction>
     * </xs:simpleType>
     * </pre>
     * @var string[] $cNCode
     * @since 1.0.0
     */
    private array $cNCode = array();

    /**
     * <!-- Numero ONU para substancias perigosas -->
     * <pre>
     * <xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="UNNumber" type="SAFPTUNNumber"/>
     * <xs:simpleType name="SAFPTUNNumber">
     *       <xs:restriction base="xs:string">
     *           <xs:pattern value="[0-9]{4}"/>
     *           <xs:length value="4"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @var string[] $uNNumber
     * @since 1.0.0
     */
    private array $uNNumber = array();

    /**
     * Adds as cNCode
     *
     * <pre>
     * <xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="CNCode" type="SAFPTCNCode"/>
     * <xs:simpleType name="SAFPTCNCode">
     * <xs:restriction base="xs:string">
     * <xs:pattern value="[0-9]{8}"/>
     * <xs:length value="8"/>
     * </xs:restriction>
     * </xs:simpleType>
     * </pre>
     * @param string $cNCode
     * @return int
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addToCNCode(string $cNCode): int
    {
        $regexp = "/^([0-9]{8})$/";
        if (\preg_match($regexp, $cNCode) !== 1)
        {
            $msg = sprintf("CNcode doesn't match regexp '%s'", $regexp);
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if (\count($this->cNCode) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->cNCode);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->cNCode[$index] = $cNCode;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " CNcode add to index " . \strval($index));

        return $index;
    }

    /**
     * isset cNCode
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetCNCode(int $index): bool
    {
        return isset($this->cNCode[$index]);
    }

    /**
     * unset cNCode
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetCNCode(int $index): void
    {
        unset($this->cNCode[$index]);
    }

    /**
     * Gets as cNCode
     *
     * <pre>
     * <xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="CNCode" type="SAFPTCNCode"/>
     * <xs:simpleType name="SAFPTCNCode">
     * <xs:restriction base="xs:string">
     * <xs:pattern value="[0-9]{8}"/>
     * <xs:length value="8"/>
     * </xs:restriction>
     * </xs:simpleType>
     * </pre>
     * @return string[]
     * @since 1.0.0
     */
    public function getCNCode(): array
    {
        return $this->cNCode;
    }

    /**
     * Sets a new cNCode
     *
     * @param string[] $cNCode
     * @return void
     * @since 1.0.0
     */
    public function setCNCode(array $cNCode): void
    {
        foreach ($cNCode as $code)
        {
            $this->addToCNCode($code);
        }
    }

    /**
     * Adds as uNNumber
     * <!-- Numero ONU para substancias perigosas -->
     * <pre>
     * <xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="UNNumber" type="SAFPTUNNumber"/>
     * <xs:simpleType name="SAFPTUNNumber">
     *       <xs:restriction base="xs:string">
     *           <xs:pattern value="[0-9]{4}"/>
     *           <xs:length value="4"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @param string $uNNumber
     * @return int
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addToUNNumber(string $uNNumber): int
    {
        $regexp = "/^([0-9]{4})$/";
        if (\preg_match($regexp, $uNNumber) !== 1)
        {
            $msg = sprintf("UN Number doesn't match regexp '%s'", $regexp);
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }
        if (\count($this->uNNumber) == 0)
        {
            $index = 0;
        }
        else
        {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->uNNumber);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->uNNumber[$index] = $uNNumber;
        \Logger::getLogger(\get_class($this))->debug(
            __METHOD__, " UN number add to index " . \strval($index));

        return $index;
    }

    /**
     * isset uNNumber
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetUNNumber(int $index): bool
    {
        return isset($this->uNNumber[$index]);
    }

    /**
     * unset uNNumber
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetUNNumber(int $index): void
    {
        unset($this->uNNumber[$index]);
    }

    /**
     * Gets as uNNumber
     * <!-- Numero ONU para substancias perigosas -->
     * <pre>
     * <xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/>
     * <xs:element name="UNNumber" type="SAFPTUNNumber"/>
     * <xs:simpleType name="SAFPTUNNumber">
     *       <xs:restriction base="xs:string">
     *           <xs:pattern value="[0-9]{4}"/>
     *           <xs:length value="4"/>
     *       </xs:restriction>
     *   </xs:simpleType>
     * </pre>
     * @return string[]
     * @since 1.0.0
     */
    public function getUNNumber(): array
    {
        return $this->uNNumber;
    }

    /**
     * Sets a new uNNumber
     *
     * @param string[] $uNNumber
     * @return void
     * @since 1.0.0
     */
    public function setUNNumber(array $uNNumber): void
    {
        foreach ($uNNumber as $number)
        {
            $this->addToUNNumber($number);
        }
    }

    /**
     * Create the xml node for CustomDetails
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        if (\count($this->cNCode) === 0 && \count($this->uNNumber) === 0)
        {
            \Logger::getLogger(\get_class($this))->debug(__METHOD__,
                                                         " No details to create node");
            return $node;
        }

        $cusDetail = $node->addChild(static::N_CUSTOMSDETAILS);

        foreach ($this->cNCode as $cNode)
        {
            $cusDetail->addChild(static::N_CNCODE, $cNode);
        }
        foreach ($this->uNNumber as $number)
        {
            $cusDetail->addChild(static::N_UNNUMBER, $number);
        }
        return $cusDetail;
    }

    /**
     * Pasrse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_CUSTOMSDETAILS)
        {
            $msg = sprinf("Node name should be '%s' and not '%s'",
                          static::N_CUSTOMSDETAILS, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new AuditFileException($msg);
        }

        $countCnCode = $node->{static::N_CNCODE}->count();
        for ($y = 0; $y < $countCnCode; $y++)
        {
            $cnNode = $node->{static::N_CNCODE}[$y];
            $this->addToCNCode((string) $cnNode);
        }
        $countUNNumber = $node->{static::N_UNNUMBER}->count();
        for ($z = 0; $z < $countUNNumber; $z++)
        {
            $unNum = $node->{static::N_UNNUMBER}[$z];
            $this->addToUNNumber((string) $unNum);
        }
    }

}
