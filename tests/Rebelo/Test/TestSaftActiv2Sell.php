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

use PHPUnit\Framework\TestCase;

/**
 * Class TestSaftActiv2Sell
 *
 * @author João Rebelo
 */
class TestSaftActiv2Sell
    extends TestCase
{

    const XSD_PATH  = __DIR__ . "/../../Ressources/saftpt1.04_01.xsd";
    const SAFT_PATH = "D:/Downloads/saft_test.xml";
    const OUT_PATH  = "D:/Downloads/saft_out.xml";

    public function testMbString()
    {
        if (\is_file(self::OUT_PATH))
        {
            \unlink(self::OUT_PATH);
        }
        if (\is_file(self::SAFT_PATH) == false)
        {
            $this->fail("SAFT path is not a file");
        }
        $a = self::XSD_PATH;
        if (\is_file(self::XSD_PATH) == false)
        {
            $this->fail("XSD path is not a file");
        }
        $xml = \file_get_contents(self::SAFT_PATH);
        $this->assertEquals("UTF-8",
                            \mb_detect_encoding($xml, "UTF-8, ISO-8859-1"));
        \file_put_contents(self::OUT_PATH,
                           \mb_convert_encoding($xml, "ISO-8859-1", "UTF-8"));

        $this->assertEquals("ISO-8859-1",
                            \mb_detect_encoding(
                \file_get_contents(self::OUT_PATH), "ISO-8859-1, UTF-8"
        ));
    }

    /**
     *
     */
    public function testStructur()
    {
        try
        {
            \libxml_use_internal_errors(true);
            $validate = true;
            $dom      = new \DOMDocument("1.0", "ISO-8859-1");
            $dom->load("D:/Downloads/saftpt1.04_01.xsd");
            $validate = $dom->schemaValidate(self::XSD_PATH);
        }
        catch (\Exception $e)
        {
            $allErrors = libxml_get_errors();
            $a         = $e->getMessage();
        }
    }

}
