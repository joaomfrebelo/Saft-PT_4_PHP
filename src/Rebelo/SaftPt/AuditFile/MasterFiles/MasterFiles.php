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

use Rebelo\SaftPt\AuditFile\AAuditFile;

/**
 * MasterFiles
 *
 * <pre>
 * &lt;xs:element name="MasterFiles"&gt;
 *   &lt;xs:complexType&gt;
 *       &lt;xs:sequence&gt;
 *           &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
 *           &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
 *           &lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
 *       &lt;/xs:sequence&gt;
 *   &lt;/xs:complexType&gt;
 * </pre>
 * @author João Rebelo
 */
class MasterFiles
    extends AAuditFile
{

    /**
     * <xs:element name="MasterFiles">
     * @since 1.0.0
     */
    const N_MASTERFILES = "MasterFiles";

    /**
     * <xs:element ref="GeneralLedgerAccounts" minOccurs="0"/>
     * @since 1.0.0
     */
    const N_GENERALLEDGERACCOUNTS = "GeneralLedgerAccounts";

    /**
     * <xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_CUSTOMER = "Customer";

    /**
     * <xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_SUPPLIER = "Supplier";

    /**
     * <xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/>
     * @since 1.0.0
     */
    const N_PRODUCT = "Product";

    /**
     * <xs:element ref="TaxTable" minOccurs="0"/>
     * @since 1.0.0
     */
    const N_TAXTABLE = "TaxTable";

    /**
     *
     * <pre>
     * &lt;xs:element name="MasterFiles"&gt;
     *   &lt;xs:complexType&gt;
     *       &lt;xs:sequence&gt;
     *           &lt;xs:element ref="GeneralLedgerAccounts" minOccurs="0"/&gt;
     *           &lt;xs:element ref="Customer" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="Supplier" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="Product" minOccurs="0" maxOccurs="unbounded"/&gt;
     *           &lt;xs:element ref="TaxTable" minOccurs="0"/&gt;
     *       &lt;/xs:sequence&gt;
     *   &lt;/xs:complexType&gt;
     * </pre>
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {

    }

    public function parseXmlNode(\SimpleXMLElement $node): void
    {

    }

}
