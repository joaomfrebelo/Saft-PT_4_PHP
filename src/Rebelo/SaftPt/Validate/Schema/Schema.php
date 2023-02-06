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

namespace Rebelo\SaftPt\Validate\Schema;

/**
 * Description of Schema
 *
 * @author João Rebelo
 */
class Schema
{
    /**
     * The original AT schema fil that not work with libXml becauss:<br>
     * 1º Has tags of the xml 1.1 and libxml only works with xml 1.0, this tagd are &lt;xs:assert&gt;<br>
     * 2º The AT's original schema file have a error using the tag &lt;all&gt;,
     * that error is not detected in Java (used by AT) but is detected in libxml
     */
    const ORIGINAL_AT_XSD = __DIR__.DIRECTORY_SEPARATOR."SAFTPT_1_04_01.xsd";

    /**
     * Xsd without assert but with tagl all, xml v 1.0
     */
    const XSD_V_1_0 = __DIR__.DIRECTORY_SEPARATOR."SAFTPT_1_04_01_NO_ASSERT.xsd";

    /**
     * The complete xsd validation, because of the libxml do not validade assert
     * of xml 1.1 and because a xsd error with tag all, we have to use this
     * modified xsd file
     */
    const GLOBAL_XSD = __DIR__.DIRECTORY_SEPARATOR."SAFTPT_1_04_01_NO_ASSERT_NO_ALL.xsd";

    /**
     * The permissive xsd validation
     */
    const PERMISSIVE_XSD = __DIR__.DIRECTORY_SEPARATOR."SAFTPT_1_04_01_permissivo.xsd";

}
