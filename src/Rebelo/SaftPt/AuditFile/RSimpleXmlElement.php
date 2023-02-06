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

namespace Rebelo\SaftPt\AuditFile;

/**
 * Description of RSimpleXmlElement
 * This class exists to resolve this 'feature' documented in:
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
     * After the string being escaped the string length it will be grater
     * and can overload the max size for that node, in this case the
     * exportation of the xml will throw an Exception
     * @since 1.0.0
     */
    const FULL_ESCAPE_HTML = 1;

    /**
     * Only some entities will be escaped<br>
     * After the string being escaped the string length it will be grater
     * and can overload the max size for that node, in this case the
     * exportation of the xml will throw an Exception
     * @since 1.0.0
     */
    const PARTIAL_ESCAPE_HTML = 2;

    /**
     * The type of escape to be used
     * @var int
     * @since 1.0.0
     */
    public static int $escapeHtml = self::PARTIAL_ESCAPE_HTML;

    /**
     * Creates a new SimpleXMLElement child object with encoding converter to UTF-8.
     * Possibility of escape html and resolve the issue of some character
     * in add child in SimpleXMLElement native class
     * <p>Creates a new SimpleXMLElement object.</p>
     * @param string $data <p>A well-formed XML string or the path or URL to an XML document if <code>data_is_url</code> is <b><code>TRUE</code></b>.</p>
     * @param int $options <p>Optionally used to specify additional Libxml parameters.</p> <p><b>Note</b>:</p><p>It may be necessary to pass <b><code>LIBXML_PARSEHUGE</code></b> to be able to process deeply nested XML or very large text nodes.</p>
     * @param bool $dataIsUrl <p>By default, <code>data_is_url</code> is <b><code>FALSE</code></b>. Use <b><code>TRUE</code></b> to specify that <code>data</code> is a path or URL to an XML document instead of <code>string</code> data.</p>
     * @param string $ns <p>Namespace prefix or URI.</p>
     * @param bool $isPrefix <p><b><code>TRUE</code></b> if <code>ns</code> is a prefix, <b><code>FALSE</code></b> if it's a URI; defaults to <b><code>FALSE</code></b>.</p>
     * @return self <p>Returns a <code>SimpleXMLElement</code> object representing <code>data</code>.</p>
     * @throws AuditFileException
     * @throws \Exception
     * @link http://php.net/manual/en/simplexmlelement.construct.php
     * @see simplexml_load_string(), simplexml_load_file(), libxml_use_internal_errors()
     * @link https://stackoverflow.com/a/4347610/6397645 based on this example
     * @since 1.0.0
     */
    public static function getInstance(
        string $data,
        int    $options = 0,
        bool   $dataIsUrl = FALSE,
        string $ns = "",
        bool   $isPrefix = FALSE
    ): self
    {

        if (false === $encode = \mb_detect_encoding(
            $data,
            ["UTF-8", "Windows-1252", "ISO-8859-1", ...\mb_list_encodings()],
            true
        )) {
            throw new AuditFileException("Data encoded not detected");
        }

        $dataUtf8 = ("UTF-8" !== $encode) ?
            \mb_convert_encoding($data, "UTF-8", $encode) : $data;

        return new self($dataUtf8, $options, $dataIsUrl, $ns, $isPrefix);
    }

    /**
     * Adds a child element to the XML node
     * <p>Adds a child element to the node and returns a SimpleXMLElement of the child.</p>
     * @param string $qualifiedName <p>The name of the child element to add.</p>
     * @param string $value <p>If specified, the value of the child element.</p>
     * @param string $namespace <p>Namespace (Not implemented)</p>
     * @return \SimpleXMLElement
     * @throws NotImplemented
     * @since 1.0.0
     * @link http://php.net/manual/en/simplexmlelement.addchild.php
     */
    public function addChild(string $qualifiedName, $value = null, $namespace = null): \SimpleXMLElement
    {
        if ($namespace !== null) {
            throw new NotImplemented("Namespace not implemented in addChild");
        }
        if ($value === null || static::$escapeHtml === static::NO_ESCAPE_HTML) {
            return parent::addChild($qualifiedName, $value);
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
            return parent::addChild($qualifiedName, $esacped);
        }

        $this->{$qualifiedName}[] = $value;
        return $this->{$qualifiedName};
    }
}
