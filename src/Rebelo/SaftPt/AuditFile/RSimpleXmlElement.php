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
 * Description of RSimpleXmlElement
 * This class exists to resove this 'feature' documented in:
 * @link https://stackoverflow.com/questions/552957/rationale-behind-simplexmlelements-handling-of-text-values-in-addchild-and-adda
 * @author João Rebelo
 * @since 1.0.0
 */
class RSimpleXmlElement extends \SimpleXMLElement
{
    /**
     * Will be not escaped, you have to escape when set the properties in class
     * @since 1.0.0
     */
    const NO_ESCAPE_HTML = 0;

    /**
     * Will it be escape by \SimpleXMLElement lib<br>
     * Afer the string being escaped the string length it will be grater
     * and can overload the max size for that node, in this case the
     * exportation of the xml will throw an Exception
     * @since 1.0.0
     */
    const FULL_ESCAPE_HTML = 1;

    /**
     * Only some entities will be escaped<br>
     * Afer the string being escaped the string length it will be grater
     * and can overload the max size for that node, in this case the
     * exportation of the xml will throw an Exception
     * @since 1.0.0
     */
    const PARTIAL_ESCAPE_HTML = 2;

    /**
     * The type of escape to be used
     * @var string
     * @since 1.0.0
     */
    public static $escapeHtml = self::PARTIAL_ESCAPE_HTML;

    /**
     * Creates a new SimpleXMLElement object
     * <p>Creates a new SimpleXMLElement object.</p>
     * @param string $data <p>A well-formed XML string or the path or URL to an XML document if <code>data_is_url</code> is <b><code>TRUE</code></b>.</p>
     * @param int $options <p>Optionally used to specify additional Libxml parameters.</p> <p><b>Note</b>:</p><p>It may be necessary to pass <b><code>LIBXML_PARSEHUGE</code></b> to be able to process deeply nested XML or very large text nodes.</p>
     * @param bool $data_is_url <p>By default, <code>data_is_url</code> is <b><code>FALSE</code></b>. Use <b><code>TRUE</code></b> to specify that <code>data</code> is a path or URL to an XML document instead of <code>string</code> data.</p>
     * @param string $ns <p>Namespace prefix or URI.</p>
     * @param bool $is_prefix <p><b><code>TRUE</code></b> if <code>ns</code> is a prefix, <b><code>FALSE</code></b> if it's a URI; defaults to <b><code>FALSE</code></b>.</p>
     * @return self <p>Returns a <code>SimpleXMLElement</code> object representing <code>data</code>.</p>
     * @link http://php.net/manual/en/simplexmlelement.construct.php
     * @see simplexml_load_string(), simplexml_load_file(), libxml_use_internal_errors()
     * @link https://stackoverflow.com/a/4347610/6397645 based on this example
     * @since 1.0.0
     */
    public static function getInstance($data, $options = 0,
                                       $data_is_url = FALSE, $ns = "",
                                       $is_prefix = FALSE)
    {
        return new self($data, $options, $data_is_url, $ns, $is_prefix);
    }

    /**
     * Adds a child element to the XML node
     * <p>Adds a child element to the node and returns a SimpleXMLElement of the child.</p>
     * @param string $name <p>The name of the child element to add.</p>
     * @param string $value <p>If specified, the value of the child element.</p>
     * @return SimpleXMLElement <p>The <i>addChild</i> method returns a <code>SimpleXMLElement</code> object representing the child added to the XML node.</p>
     * @link http://php.net/manual/en/simplexmlelement.addchild.php
     * @since 1.0.0
     */
    public function addChild($name, $value = NULL, $ns = null)
    {
        if ($ns !== null) {
            throw new NotImplemented("Name Space not implemented in addChild");
        }
        if ($value === null || static::$escapeHtml === static::NO_ESCAPE_HTML) {
            return parent::addChild($name, $value);
        }

        if (static::$escapeHtml === static::PARTIAL_ESCAPE_HTML) {
            $from    = array();
            $from[0] = '&';
            $from[1] = '"';
            $from[2] = '\'';
            $from[3] = '<';
            $from[4] = '>';
            $from[5] = '\\';

            $to    = array();
            $to[0] = '&amp;';
            $to[1] = '&quot;';
            $to[2] = '&#39;';
            $to[3] = '&lt;';
            $to[4] = '&gt;';
            $to[5] = '&#92;';

            $esacped = str_replace($from, $to, $value);
            return parent::addChild($name, $esacped);
        }

        $this->{$name}[] = $value;
        return $this->{$name};
    }
}