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

namespace Rebelo\SaftPt\Sign;

use PHPUnit\Framework\TestCase;
use Rebelo\Date\Date as RDate;
use Rebelo\Date\DateParseException;
use Rebelo\SaftPt\AuditFile\Header;
use Rebelo\SaftPt\CommuneTest;

/**
 * Class SignTest
 *
 * @author João Rebelo
 */
class SignTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new CommuneTest())
            ->testReflection(Header::class);
        $this->assertTrue(true);
    }

    /**
     *
     * @return array
     * @throws DateParseException
     */
    public function dataProvider(): array
    {
        return array(
            array(
                RDate::parse(RDate::SQL_DATE, "2020-10-05"), new RDate(), "FT FT/1",
                999.09, null
            ),
            array(new RDate(), new RDate(), "FT FT/2", 1999.09, ""),
            array(new RDate(), new RDate(), "FT FT/2", 1999.09, "AnyHash"),
        );
    }

    /**
     *
     * @param \Rebelo\Date\Date $docDate
     * @param \Rebelo\Date\Date $systemEntryDate
     * @param string $doc
     * @param float $grossTotal
     * @param string|null $lastHash
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidation(
        RDate   $docDate,
        RDate   $systemEntryDate,
        string  $doc,
        float   $grossTotal,
        ?string $lastHash
    ): void
    {

        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open public key file path '%s'",
                    PUBLIC_KEY_PATH
                )
            );
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open private key file path '%s'",
                    PRIVATE_KEY_PATH
                )
            );
        }

        $sign = new Sign();
        $sign->setPublicKey($pubKey);
        $sign->setPrivateKey($priKey);

        $hash = $sign->createSignature(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        $this->assertTrue(
            $sign->verifySignature(
                $hash, $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
            )
        );
    }

    /**
     *
     * @param \Rebelo\Date\Date $docDate
     * @param \Rebelo\Date\Date $systemEntryDate
     * @param string $doc
     * @param float $grossTotal
     * @param string|null $lastHash
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidationKeyFilePath(
        RDate   $docDate,
        RDate   $systemEntryDate,
        string  $doc,
        float   $grossTotal,
        ?string $lastHash
    ): void
    {

        $sign = new Sign();
        $sign->setPublicKeyFilePath(PUBLIC_KEY_PATH);
        $sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH);

        $hash = $sign->createSignature(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        $this->assertTrue(
            $sign->verifySignature(
                $hash, $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
            )
        );
    }

    /**
     *
     * @return void
     * @author João Rebelo
     * @test
     */
    public function testWrongFilePrivateKeyFilePath(): void
    {
        $this->expectException(SignException::class);
        $sign = new Sign();
        @$sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH . "a");
    }

    /**
     *
     * @return void
     * @author João Rebelo
     * @test
     */
    public function testWrongFilePublicKeyFilePath(): void
    {
        $this->expectException(SignException::class);
        $sign = new Sign();
        @$sign->setPublicKeyFilePath(PUBLIC_KEY_PATH . "a");
    }

    /**
     *
     * @param \Rebelo\Date\Date $docDate
     * @param \Rebelo\Date\Date $systemEntryDate
     * @param string $doc
     * @param float $grossTotal
     * @param string|null $lastHash
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidationSetKeyConstructor(
        RDate   $docDate,
        RDate   $systemEntryDate,
        string  $doc,
        float   $grossTotal,
        ?string $lastHash
    ): void
    {

        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open public key file path '%s'",
                    PUBLIC_KEY_PATH
                )
            );
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open private key file path '%s'",
                    PRIVATE_KEY_PATH
                )
            );
        }


        $sign = new Sign($priKey, $pubKey);

        $hash = $sign->createSignature(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        $this->assertTrue(
            $sign->verifySignature(
                $hash, $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
            )
        );
    }

    /**
     *
     * @param \Rebelo\Date\Date $docDate
     * @param \Rebelo\Date\Date $systemEntryDate
     * @param string $doc
     * @param float $grossTotal
     * @param string|null $lastHash
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testWrongSignAndSignValidation(
        RDate   $docDate,
        RDate   $systemEntryDate,
        string  $doc,
        float   $grossTotal,
        ?string $lastHash
    ): void
    {
        $pubKey = \file_get_contents(PUBLIC_KEY_PATH);
        if ($pubKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open public key file path '%s'",
                    PUBLIC_KEY_PATH
                )
            );
        }

        $priKey = \file_get_contents(PRIVATE_KEY_PATH);
        if ($priKey === false) {
            $this->fail(
                \sprintf(
                    "It was not possible to open private key file path '%s'",
                    PRIVATE_KEY_PATH
                )
            );
        }

        $sign = new Sign($priKey, $pubKey);

        $hash = $sign->createSignature(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        $this->assertFalse(
            $sign->verifySignature(
                $hash, $docDate, $systemEntryDate, $doc, $grossTotal,
                $lastHash . "a"
            )
        );
    }

}
