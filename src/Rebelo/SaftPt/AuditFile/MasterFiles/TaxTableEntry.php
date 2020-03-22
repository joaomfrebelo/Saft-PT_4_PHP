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

/**
 * TaxTableEntry
 * <pre>
 * <xs:sequence>
 *    <xs:element ref="TaxType"/>
 *    <xs:element ref="TaxCountryRegion"/>
 *    <xs:element name="TaxCode" type="TaxTableEntryTaxCode"/>
 *    <xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/>
 *    <xs:element ref="TaxExpirationDate" minOccurs="0"/>
 *    <xs:choice>
 *        <xs:element ref="TaxPercentage"/>
 *        <xs:element ref="TaxAmount"/>
 *    </xs:choice>
 *    </xs:sequence>
 * </pre>
 * @author João Rebelo
 * @since 1.0.0
 */
class TaxTableEntry
    extends \Rebelo\SaftPt\AuditFile\AAuditFile
{

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXTABLEENTRY = "TaxTableEntry";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXTYPE = "TaxType";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXCOUNTRYREGION = "TaxCountryRegion";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXCODE = "TaxCode";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_DESCRIPTION = "Description";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXEXPIRATIONDATE = "TaxExpirationDate";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXPERCENTAGE = "TaxPercentage";

    /**
     * Node Name
     * @since 1.0.0
     */
    const N_TAXAMOUNT = "TaxAmount";

    /**
     * <pre>
     * <xs:sequence>
     *    <xs:element ref="TaxType"/>
     *    <xs:element ref="TaxCountryRegion"/>
     *    <xs:element name="TaxCode" type="TaxTableEntryTaxCode"/>
     *    <xs:element name="Description" type="SAFPTtextTypeMandatoryMax255Car"/>
     *    <xs:element ref="TaxExpirationDate" minOccurs="0"/>
     *    <xs:choice>
     *        <xs:element ref="TaxPercentage"/>
     *        <xs:element ref="TaxAmount"/>
     *    </xs:choice>
     *    </xs:sequence>
     * </pre>
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function parseXmlNode(\SimpleXMLElement $node): void
    {

    }

}
