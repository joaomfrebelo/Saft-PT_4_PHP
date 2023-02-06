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

namespace Rebelo\SaftPt\Validate;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\CommuneTest;

/**
 * MovOfGoodsTableTotalCalcTest
 *
 * @author João Rebelo
 */
class MovOfGoodsTableTotalCalcTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(MovOfGoodsTableTotalCalc::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $docCalc = new MovOfGoodsTableTotalCalc();
        $this->assertInstanceOf(
            MovOfGoodsTableTotalCalc::class, $docCalc
        );

        $this->assertNull($docCalc->getNumberOfMovementLines());
        $this->assertNull($docCalc->getTotalQuantityIssued());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testNumberOfMovementLines(): void
    {
        $docCalc = new MovOfGoodsTableTotalCalc();

        $value = 999;
        $docCalc->setNumberOfMovementLines($value);
        $this->assertSame($value, $docCalc->getNumberOfMovementLines());

        $docCalc->setNumberOfMovementLines(null);
        $this->assertNull($docCalc->getNumberOfMovementLines());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testTotalQuantityIssued(): void
    {
        $docCalc = new MovOfGoodsTableTotalCalc();

        $value = 999.99;
        $docCalc->setTotalQuantityIssued($value);
        $this->assertSame($value, $docCalc->getTotalQuantityIssued());

        $docCalc->setTotalQuantityIssued(null);
        $this->assertNull($docCalc->getTotalQuantityIssued());
    }
}
