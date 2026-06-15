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

namespace Rebelo\SaftPt\Validate;

use Decimal\Decimal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Commune;

/**
 * Class AuditFileTest
 *
 * @author João Rebelo
 */
class ValidationConfigTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     */
    #[Test]
    public function testReflection(): void
    {
        (new Commune(ValidationConfig::class))->testReflection(ValidationConfig::class);
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testInstance(): void
    {
        $conf = new ValidationConfig();
        $this->assertInstanceOf(ValidationConfig::class, $conf);
        $this->assertFalse($conf->getAllowDebitAndCredit());
        $this->assertTrue($conf->getContinuesLines());
        $this->assertSame(0.01, $conf->getDeltaCurrency()->toFloat());
        $this->assertSame(0.01, $conf->getDeltaTable()->toFloat());
        $this->assertSame(0.01, $conf->getDeltaTotalDoc()->toFloat());
        $this->assertTrue($conf->getSignValidation());
        $this->assertTrue($conf->getSchemaValidate());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testAllowDebitAndCredit(): void
    {
        $conf = new ValidationConfig();
        $conf->setAllowDebitAndCredit(true);
        $this->assertTrue($conf->getAllowDebitAndCredit());
        $conf->setAllowDebitAndCredit(false);
        $this->assertFalse($conf->getAllowDebitAndCredit());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testContinuesLines(): void
    {
        $conf = new ValidationConfig();
        $conf->setContinuesLines(false);
        $this->assertFalse($conf->getAllowDebitAndCredit());
        $conf->setAllowDebitAndCredit(true);
        $this->assertTrue($conf->getAllowDebitAndCredit());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testDeltaCurrency(): void
    {
        $conf  = new ValidationConfig();
        $delta = new Decimal("0.99");
        $conf->setDeltaCurrency($delta);
        $this->assertSame($delta->toFloat(), $conf->getDeltaCurrency()->toFloat());

        $conf->setDeltaCurrency($delta->mul(-1));
        $this->assertSame($delta->toFloat(), $conf->getDeltaCurrency()->toFloat());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testDeltaLine(): void
    {
        $conf  = new ValidationConfig();
        $delta = new Decimal("0.99");
        $conf->setDeltaLine($delta);
        $this->assertSame($delta->toFloat(), $conf->getDeltaLine()->toFloat());

        $conf->setDeltaLine($delta->mul(-1));
        $this->assertSame($delta->toFloat(), $conf->getDeltaLine()->toFloat());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testDeltaTable(): void
    {
        $conf  = new ValidationConfig();
        $delta = new Decimal("0.99");
        $conf->setDeltaTable($delta);
        $this->assertSame($delta->toFloat(), $conf->getDeltaTable()->toFloat());

        $conf->setDeltaTable($delta->mul(-1));
        $this->assertSame($delta->toFloat(), $conf->getDeltaTable()->toFloat());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testDeltaTotalDoc(): void
    {
        $conf  = new ValidationConfig();
        $delta = new Decimal("0.99");
        $conf->setDeltaTotalDoc($delta);
        $this->assertSame($delta->toFloat(), $conf->getDeltaTotalDoc()->toFloat());

        $conf->setDeltaTotalDoc($delta->mul(-1));
        $this->assertSame($delta->toFloat(), $conf->getDeltaTotalDoc()->toFloat());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSignValidation(): void
    {
        $conf = new ValidationConfig();
        $conf->setSignValidation(false);
        $this->assertFalse($conf->getSignValidation());
        $conf->setSignValidation(true);
        $this->assertTrue($conf->getSignValidation());
    }

    /**
     * @author João Rebelo
     */
    #[Test]
    public function testSchemaValidate(): void
    {
        $conf = new ValidationConfig();
        $conf->setSchemaValidate(false);
        $this->assertFalse($conf->getSchemaValidate());
        $conf->setSchemaValidate(true);
        $this->assertTrue($conf->getSchemaValidate());
    }
}
