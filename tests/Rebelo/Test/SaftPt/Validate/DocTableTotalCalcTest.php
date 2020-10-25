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

namespace Rebelo\Test\SaftPt\Validate;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\DocTableTotalCalc;

/**
 * DocTotalCalcTest
 *
 * @author João Rebelo
 */
class DocTableTotalCalcTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(DoctableTotalCalc::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $docCalc = new DocTableTotalCalc();
        $this->assertInstanceOf(
            DocTableTotalCalc::class, $docCalc
        );

        $this->assertNull($docCalc->getNumberOfEntries());
        $this->assertNull($docCalc->getTotalCredit());
        $this->assertNull($docCalc->getTotalDebit());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfEntries(): void
    {
        $docCalc = new DocTableTotalCalc();

        $value = 999;
        $docCalc->setNumberOfEntries($value);
        $this->assertSame($value, $docCalc->getNumberOfEntries());

        $docCalc->setNumberOfEntries(null);
        $this->assertNull($docCalc->getNumberOfEntries());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalCredit(): void
    {
        $docCalc = new DocTableTotalCalc();

        $value = 999.99;
        $docCalc->setTotalCredit($value);
        $this->assertSame($value, $docCalc->getTotalCredit());

        $docCalc->setTotalCredit(null);
        $this->assertNull($docCalc->getTotalCredit());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalDebit(): void
    {
        $docCalc = new DocTableTotalCalc();

        $value = 999.99;
        $docCalc->setTotalDebit($value);
        $this->assertSame($value, $docCalc->getTotalDebit());

        $docCalc->setTotalDebit(null);
        $this->assertNull($docCalc->getTotalDebit());
    }
}