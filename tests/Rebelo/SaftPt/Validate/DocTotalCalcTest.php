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

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Commune;

/**
 * DocTotalCalcTest
 *
 * @author João Rebelo
 */
class DocTotalCalcTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(DocTotalCalc::class))->testReflection(DocTotalCalc::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $docCalc = new DocTotalCalc();
        $this->assertInstanceOf(
            DocTotalCalc::class, $docCalc
        );

        $this->assertNull($docCalc->getGrossTotal());
        $this->assertNull($docCalc->getGrossTotalFromCurrency());
        $this->assertNull($docCalc->getNetTotal());
        $this->assertNull($docCalc->getTaxPayable());
        $this->assertEmpty($docCalc->getLineTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testGrossTotal(): void
    {
        $docCalc = new DocTotalCalc();

        $value = new Decimal("999.99");
        $docCalc->setGrossTotal($value);
        $this->assertSame($value, $docCalc->getGrossTotal());

        $docCalc->setGrossTotal($value->mul("-1"));
        $this->assertSame($value->mul("-1")->toFloat(), $docCalc->getGrossTotal()?->toFloat());

        $docCalc->setGrossTotal(null);
        $this->assertNull($docCalc->getGrossTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testGrossTotalFromCurrency(): void
    {
        $docCalc = new DocTotalCalc();

        $value = new Decimal("999.99");
        $docCalc->setGrossTotalFromCurrency($value);
        $this->assertSame($value, $docCalc->getGrossTotalFromCurrency());

        $docCalc->setGrossTotalFromCurrency($value->mul("-1"));
        $this->assertSame($value->mul("-1")->toFloat(), $docCalc->getGrossTotalFromCurrency()?->toFloat());

        $docCalc->setGrossTotalFromCurrency(null);
        $this->assertNull($docCalc->getGrossTotalFromCurrency());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testNetTotal(): void
    {
        $docCalc = new DocTotalCalc();

        $value = new Decimal("999.99");
        $docCalc->setNetTotal($value);
        $this->assertSame($value, $docCalc->getNetTotal());

        $docCalc->setNetTotal($value->mul("-1"));
        $this->assertSame($value->mul("-1")->toFloat(), $docCalc->getNetTotal()?->toFloat());

        $docCalc->setNetTotal(null);
        $this->assertNull($docCalc->getNetTotal());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testTaxPayable(): void
    {
        $docCalc = new DocTotalCalc();

        $value = new Decimal("999.99");
        $docCalc->setTaxPayable($value);
        $this->assertSame($value, $docCalc->getTaxPayable());

        $docCalc->setTaxPayable($value->mul("-1"));
        $this->assertSame($value->mul("-1")->toFloat(), $docCalc->getTaxPayable()?->toFloat());

        $docCalc->setTaxPayable(null);
        $this->assertNull($docCalc->getTaxPayable());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testLineTotal(): void
    {
        $docCalc = new DocTotalCalc();

        $nMax = 9;
        for ($n = 1; $n <= $nMax; $n++) {
            $value = (new Decimal("9999.99"))->mul((string)$n);
            $docCalc->addLineTotal($n, $value);
            $this->assertSame(
                $value, $docCalc->getLineTotal()[$n]
            );
        }
        $this->assertSame($nMax, \count($docCalc->getLineTotal()));
    }
}
