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

namespace Rebelo\Test\SaftPt\AuditFile;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\PostalCodePT;
use Rebelo\SaftPt\AuditFile\AuditFileException;

/**
 * Class PostalCodePTTest
 *
 * @author João Rebelo
 */
class PostalCodePTTest
    extends TestCase
{

    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())->testReflection(PostalCodePT::class);
    }

    public function testSetGet()
    {
        $posCod = new PostalCodePT();
        $this->assertInstanceOf(PostalCodePT::class, $posCod);

        try
        {
            $posCod->getPostalCode();
            $this->fail("getPostalCode should throw Error whene not initialized");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\Error::class, $e);
        }
        $code = "1999-999";
        $posCod->setPostalCode($code);
        $this->assertEquals($code, $posCod->getPostalCode());

        try
        {
            $posCod->setPostalCode("999-000");
            $this->fail("setPostalCode should throw AuditFileException whene not initialized");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(AuditFileException::class, $e);
        }
        try
        {
            $posCod->setPostalCode(null);
            $this->fail("setPostalCode should throw TypeError whene setted to null");
        }
        catch (\Exception | \Error $e)
        {
            $this->assertInstanceOf(\TypeError::class, $e);
        }
    }

}
