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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\WorkingDocuments;

use Rebelo\SaftPt\AuditFile\ErrorRegister;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * WorkDocument Line
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Line extends \Rebelo\SaftPt\AuditFile\SourceDocuments\ALineInvoiveAndWorking
{

    /**
     * WorkDocument Line<br>
     * <pre>
     * &lt;xs:element name="Line" maxOccurs="unbounded"&gt;
     *  &lt;xs:complexType&gt;
     *      &lt;xs:sequence&gt;
     *          &lt;xs:element ref="LineNumber"/&gt;
     *          &lt;xs:element name="OrderReferences"
     *                      type="OrderReferences" minOccurs="0"
     *                      maxOccurs="unbounded"/&gt;
     *          &lt;xs:element ref="ProductCode"/&gt;
     *          &lt;xs:element ref="ProductDescription"/&gt;
     *          &lt;xs:element ref="Quantity"/&gt;
     *          &lt;xs:element ref="UnitOfMeasure"/&gt;
     *          &lt;xs:element ref="UnitPrice"/&gt;
     *          &lt;xs:element ref="TaxBase" minOccurs="0"/&gt;
     *          &lt;xs:element ref="TaxPointDate"/&gt;
     *          &lt;xs:element name="References" type="References"
     *                      minOccurs="0" maxOccurs="unbounded"/&gt;
     *          &lt;xs:element ref="Description"/&gt;
     *          &lt;xs:element name="ProductSerialNumber"
     *                      type="ProductSerialNumber" minOccurs="0"/&gt;
     *          &lt;xs:choice&gt;
     *              &lt;xs:element ref="DebitAmount"/&gt;
     *              &lt;xs:element ref="CreditAmount"/&gt;
     *          &lt;/xs:choice&gt;
     *          &lt;xs:element name="Tax" type="Tax"/&gt;
     *          &lt;xs:element ref="TaxExemptionReason" minOccurs="0"/&gt;
     *          &lt;xs:element ref="TaxExemptionCode" minOccurs="0"/&gt;
     *          &lt;xs:element ref="SettlementAmount" minOccurs="0"/&gt;
     *          &lt;xs:element name="CustomsInformation"
     *                      type="CustomsInformation" minOccurs="0"/&gt;
     *      &lt;/xs:sequence&gt;
     *  &lt;/xs:complexType&gt;
     * &lt;/xs:element&gt;
     * </pre>
     * @param \Rebelo\SaftPt\AuditFile\ErrorRegister $errorRegister
     * @since 1.0.0
     */
    public function __construct(ErrorRegister $errorRegister)
    {
        parent::__construct($errorRegister);
    }

    /**
     * Create XML node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\SaftPt\AuditFile\AuditFileException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        if ($node->getName() !== WorkDocument::N_WORKDOCUMENT) {
            $msg = \sprintf(
                "Node name should be '%s' but is '%s",
                WorkDocument::N_WORKDOCUMENT, $node->getName()
            );
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new AuditFileException($msg);
        }
        return parent::createXmlNode($node);
    }
}