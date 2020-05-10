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

use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * ProductSerialNumber
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class ProductSerialNumber extends \Rebelo\SaftPt\AuditFile\AAuditFile
{
    /**
     * Node name
     * @since 1.0.0
     */
    const N_PRODUCTSERIALNUMBER = "ProductSerialNumber";

    /**
     * Node name
     * @since 1.0.0
     */
    const N_SERIALNUMBER = "SerialNumber";

    /**
     * <pre>
     * <xs:sequence&gt;
     *       &lt;xs:element ref="SerialNumber" maxOccurs="unbounded"/&gt;
     *  &lt;/xs:sequence&gt;
     * &lt;xs:element name="SerialNumber" type="SAFPTtextTypeMandatoryMax100Car"/>
     * </pre>
     * @var string[]
     * @since 1.0.0
     */
    private array $serialNumber = array();

    /**
     *
     * <!-- Estrutura de numero de serie do produto-->
     * <pre>
     * &lt;xs:complexType name="ProductSerialNumber"&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element ref="SerialNumber" maxOccurs="unbounded"/&gt;
     *    &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * Get SerialNumber stack<br>     *
     * <pre>
     * &lt;xs:complexType name="ProductSerialNumber"&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element ref="SerialNumber" maxOccurs="unbounded"/&gt;
     *    &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @return string[]
     * @since 1.0.0
     */
    public function getSerialNumber(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(__METHOD__." getted");
        return $this->serialNumber;
    }

    /**
     * Add SerialNumber to the stack<br>
     * <pre>
     * &lt;xs:complexType name="ProductSerialNumber"&gt;
     *    &lt;xs:sequence&gt;
     *        &lt;xs:element ref="SerialNumber" maxOccurs="unbounded"/&gt;
     *    &lt;/xs:sequence&gt;
     * &lt;/xs:complexType&gt;
     * </pre>
     * @param string $serialNumber
     * @return int
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function addToSerialNumber(string $serialNumber): int
    {
        $serialNumber = $this->valTextMandMaxCar($serialNumber, 100, __METHOD__);

        if (\count($this->serialNumber) === 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->serialNumber);
            $index = $keys[\count($keys) - 1] + 1;
        }
        $this->serialNumber[$index] = $serialNumber;
        \Logger::getLogger(\get_class($this))
            ->debug(__METHOD__." add to stack");
        return $index;
    }

    /**
     * isset SerialNumber index
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetSerialNumber(int $index): bool
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        return isset($this->serialNumber[$index]);
    }

    /**
     * unset SerialNumber
     *
     * @param int $index
     * @return void
     * @since 1.0.0
     */
    public function unsetSerialNumber(int $index): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);
        unset($this->serialNumber[$index]);
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== A2Line::N_LINE) {
            $msg = sprintf(
                "Node name should be '%s' but is '%s", A2Line::N_LINE,
                $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        $psnNode = $node->addChild(static::N_PRODUCTSERIALNUMBER);
        foreach ($this->getSerialNumber() as $serial) {
            $psnNode->addChild(static::N_SERIALNUMBER, $serial);
        }
        return $psnNode;
    }

    /**
     * Parse the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->trace(__METHOD__);

        if ($node->getName() !== static::N_PRODUCTSERIALNUMBER) {
            $msg = sprintf("Node name should be '%s' but is '%s",
                static::N_PRODUCTSERIALNUMBER, $node->getName());
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }

        for ($n = 0; $n < $node->{static::N_SERIALNUMBER}->count(); $n++) {
            $this->addToSerialNumber((string) $node->{static::N_SERIALNUMBER}[$n]);
        }
    }
}