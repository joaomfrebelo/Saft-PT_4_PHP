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

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Test validation of portuguese TIN
 */
class TinPTTest extends TestCase
{

    public function validDataProvider(): array
    {
        $stack = [];

        $stack[] = [179708970];
        $stack[] = [275349764];
        $stack[] = [385542283];
        $stack[] = [457344920];
        $stack[] = [567267890];
        $stack[] = [639905587];
        $stack[] = [795577990];
        $stack[] = [713451424];
        $stack[] = [724711767];
        $stack[] = [742290859];
        $stack[] = [757627978];
        $stack[] = [775029645];
        $stack[] = [782728367];
        $stack[] = [790024470];
        $stack[] = [807881155];
        $stack[] = [908981589];
        $stack[] = [911776117];
        $stack[] = [987814320];
        $stack[] = [999368435];

        return $stack;
    }

    public function notValidDataProvider(): array
    {
        $stack = [];

        $stack[] = [17970897];
        $stack[] = [2753497649];
        $stack[] = [385542282];

        return $stack;
    }

    /**
     * @param int $tin
     * @dataProvider validDataProvider
     * @return void
     */
    public function testValidationAsValid(int $tin): void
    {
        Assert::assertTrue(AAuditFile::valPortugueseVatNumber($tin));
    }

    /**
     * @param int $tin
     * @dataProvider notValidDataProvider
     * @return void
     */
    public function testValidationAsNotValid(int $tin): void
    {
        Assert::assertFalse(AAuditFile::valPortugueseVatNumber($tin));
    }

}
