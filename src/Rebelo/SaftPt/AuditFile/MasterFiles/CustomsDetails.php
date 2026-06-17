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

use Rebelo\SaftPt\AuditFile\AAuditFile;
use Rebelo\SaftPt\AuditFile\AuditFileException;
use Rebelo\SaftPt\AuditFile\ErrorRegister;

/**
 * Class representing CustomsDetailsType
 * <pre>
 *   &lt;xs:complexType name="CustomsDetails">
 *       &lt;xs:sequence>
 *           &lt;xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
 * </pre>
 * XSD Type: CustomsDetails
 *
 * @since 1.0.0
 */
class CustomsDetails extends AAuditFile
{
    /**
     * &lt;xs:complexType name="CustomsDetails">
     *
     * @since 1.0.0
     */
    const string N_CUSTOMS_DETAILS = "CustomsDetails";

    /**
     * &lt;xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/&gt;
     *
     * @since 1.0.0
     */
    const string N_CN_CODE = "CNCode";

    /**
     * &lt;xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded">
     *
     * @since 1.0.0
     */
    const string N_UN_NUMBER = "UNNumber";

    /**
     * <pre>
     * &lt;xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="CNCode" type="SAFPTCNCode"/&gt;
     * &lt;xs:simpleType name="SAFPTCNCode">
     * &lt;xs:restriction base="xs:string">
     * &lt;xs:pattern value="[0-9]{8}"/&gt;
     * &lt;xs:length value="8"/&gt;
     * &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @var string[] $cNCode
     * @since 1.0.0
     */
    private array $cNCode = array();

    /**
     * <!-- Numero ONU para substancias perigosas -->
     * <pre>
     * &lt;xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="UNNumber" type="SAFPTUNNumber"/&gt;
     * &lt;xs:simpleType name="SAFPTUNNumber">
     *       &lt;xs:restriction base="xs:string">
     *           &lt;xs:pattern value="[0-9]{4}"/&gt;
     *           &lt;xs:length value="4"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @var string[] $uNNumber
     * @since 1.0.0
     */
    private array $uNNumber = array();

    /**
     * CustomsDetails
     *
     * @param ErrorRegister $errorRegister *
     *
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Adds CNCode<br>
     * Fill in with the European Union Combined Nomenclature code.
     * there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     *
     * <pre>
     * &lt;xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="CNCode" type="SAFPTCNCode"/&gt;
     * &lt;xs:simpleType name="SAFPTCNCode">
     * &lt;xs:restriction base="xs:string">
     * &lt;xs:pattern value="[0-9]{8}"/&gt;
     * &lt;xs:length value="8"/&gt;
     * &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType>
     * </pre>
     *
     * @param string $cNCode
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function addCNCode(string $cNCode): bool
    {
        $regexp = "/^([0-9]{8})$/";
        if (\preg_match($regexp, $cNCode) !== 1) {
            $msg = sprintf("CNcode doesn't match regexp '%s'", $regexp);
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("CNCode_not_valid");
        } else {
            $return = true;
        }

        $this->cNCode[] = $cNCode;
        AAuditFile::$logger?->debug(sprintf(__METHOD__ . " CNcode '%s' add", $cNCode));

        return $return;
    }

    /**
     * Gets CNCode<br>
     * Fill in with the European Union Combined Nomenclature code.
     * there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     *
     * <pre>
     * &lt;xs:element ref="CNCode" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="CNCode" type="SAFPTCNCode"/&gt;
     * &lt;xs:simpleType name="SAFPTCNCode">
     * &lt;xs:restriction base="xs:string">
     * &lt;xs:pattern value="[0-9]{8}"/&gt;
     * &lt;xs:length value="8"/&gt;
     * &lt;/xs:restriction&gt;
     * &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @return string[]
     * @since 1.0.0
     */
    public function getCNCode(): array
    {
        return $this->cNCode;
    }

    /**
     * Adds UNNumber<br>
     * Fill in with the UN [United Nations] number for dangerous products.
     * If there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     * <pre>
     * &lt;xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="UNNumber" type="SAFPTUNNumber"/&gt;
     * &lt;xs:simpleType name="SAFPTUNNumber">
     *       &lt;xs:restriction base="xs:string">
     *           &lt;xs:pattern value="[0-9]{4}"/&gt;
     *           &lt;xs:length value="4"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @param string $uNNumber
     *
     * @return bool true if the value is valid
     * @since 1.0.0
     */
    public function addUNNumber(string $uNNumber): bool
    {
        $regexp = "/^([0-9]{4})$/";
        if (\preg_match($regexp, $uNNumber) !== 1) {
            $msg = sprintf("UN Number doesn't match regexp '%s'", $regexp);
            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));
            $return = false;
            $this->getErrorRegistor()->addOnSetValue("UNNumber_not_valid");
        } else {
            $return = true;
        }

        $this->uNNumber[] = $uNNumber;
        AAuditFile::$logger?->debug(\sprintf(__METHOD__ . " UN number add '%s'", $uNNumber));

        return $return;
    }

    /**
     * Gets as uNNumber<br>
     * Fill in with the UN [United Nations] number for dangerous products.
     * If there is a need to make more than one reference,
     * this field can be generated as many times as necessary.
     * <pre>
     * &lt;xs:element ref="UNNumber" minOccurs="0" maxOccurs="unbounded"/&gt;
     * &lt;xs:element name="UNNumber" type="SAFPTUNNumber"/&gt;
     * &lt;xs:simpleType name="SAFPTUNNumber">
     *       &lt;xs:restriction base="xs:string">
     *           &lt;xs:pattern value="[0-9]{4}"/&gt;
     *           &lt;xs:length value="4"/&gt;
     *       &lt;/xs:restriction&gt;
     *   &lt;/xs:simpleType&gt;
     * </pre>
     *
     * @return string[]
     * @since 1.0.0
     */
    public function getUNNumber(): array
    {
        return $this->uNNumber;
    }

    /**
     * Create the xml node for CustomDetails
     *
     * @param \SimpleXMLElement $node
     *
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        AAuditFile::$logger?->info(__METHOD__);

        if (\count($this->cNCode) === 0 && \count($this->uNNumber) === 0) {
            AAuditFile::$logger?->debug(__METHOD__ . " No details to create node");
            return $node;
        }

        $cusDetail = $node->addChild(static::N_CUSTOMS_DETAILS);

        foreach ($this->cNCode as $cNode) {
            $cusDetail->addChild(static::N_CN_CODE, $cNode);
        }
        foreach ($this->uNNumber as $number) {
            $cusDetail->addChild(static::N_UN_NUMBER, $number);
        }
        return $cusDetail;
    }

    /**
     * Pasrse the xml node
     *
     * @param \SimpleXMLElement $node
     *
     * @return void
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        AAuditFile::$logger?->info(__METHOD__);

        if ($node->getName() !== static::N_CUSTOMS_DETAILS) {
            $msg = sprintf(
                "Node name should be '%s' and not '%s'",
                static::N_CUSTOMS_DETAILS, $node->getName()
            );

            AAuditFile::$logger?->error(\sprintf(__METHOD__ . " '%s'", $msg));

            throw new AuditFileException($msg);
        }

        $countCnCode = $node->{static::N_CN_CODE}->count();
        for ($y = 0; $y < $countCnCode; $y++) {
            $cnNode = $node->{static::N_CN_CODE}[$y];
            $this->addCNCode((string)$cnNode);
        }
        $countUNNumber = $node->{static::N_UN_NUMBER}->count();
        for ($z = 0; $z < $countUNNumber; $z++) {
            $unNum = $node->{static::N_UN_NUMBER}[$z];
            $this->addUNNumber((string)$unNum);
        }
    }
}
