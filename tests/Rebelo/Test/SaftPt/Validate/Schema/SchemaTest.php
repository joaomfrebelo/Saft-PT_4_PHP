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

namespace Rebelo\Test\SaftPt\Validate\Schema;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\Schema\Schema;

/**
 * Class HeaderTest
 *
 * @author João Rebelo
 */
class SchemaTest extends TestCase
{

    public function testXsdFilePath(): void
    {
        $this->assertTrue(\is_file(Schema::GLOBAL_XSD));
        $xsdGlobal = \simplexml_load_file(Schema::GLOBAL_XSD);
        if ($xsdGlobal === false) {
            $this->fail(
                \sprintf("XSD schema file '%s' not loaded", Schema::GLOBAL_XSD)
            );
        }

        $this->assertSame(
            "1.04_01", (string) $xsdGlobal->attributes()->{'version'}
        );

        $this->assertTrue(\is_file(Schema::PERMISSIVE_XSD));
        $xsdPer = \simplexml_load_file(Schema::PERMISSIVE_XSD);
        if ($xsdPer === false) {
            $this->fail(
                \sprintf(
                    "XSD schema file '%s' not loaded", Schema::PERMISSIVE_XSD
                )
            );
        }

        $this->assertSame("1.04_01", (string) $xsdPer->attributes()->{'version'});
    }
}
