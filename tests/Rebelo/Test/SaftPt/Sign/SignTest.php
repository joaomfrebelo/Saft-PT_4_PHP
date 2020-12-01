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

namespace Rebelo\Test\SaftPt\Sign;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Sign\Sign;
use Rebelo\Date\Date as RDate;

/**
 * Class SignTest
 *
 * @author João Rebelo
 */
class SignTest extends TestCase
{

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(\Rebelo\SaftPt\AuditFile\Header::class);
        $this->assertTrue(true);
    }

    /**
     *
     * @return array
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
     * @param string $lastHash
     * @return void
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidation($docDate, $systemEntryDate,
                                                   $doc, $grossTotal, $lastHash): void
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

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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
     * @param string $lastHash
     * @return void
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidationKeyFilePath($docDate,
                                                              $systemEntryDate,
                                                              $doc, $grossTotal,
                                                              $lastHash): void
    {

        $sign = new \Rebelo\SaftPt\Sign\Sign();
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
        $this->expectException(\Rebelo\SaftPt\Sign\SignException::class);
        $sign = new \Rebelo\SaftPt\Sign\Sign();
        @$sign->setPrivateKeyFilePath(PRIVATE_KEY_PATH."a");
    }

    /**
     *
     * @return void
     * @author João Rebelo
     * @test
     */
    public function testWrongFilePublicKeyFilePath(): void
    {
        $this->expectException(\Rebelo\SaftPt\Sign\SignException::class);
        $sign = new \Rebelo\SaftPt\Sign\Sign();
        @$sign->setPublicKeyFilePath(PUBLIC_KEY_PATH."a");
    }

    /**
     *
     * @param \Rebelo\Date\Date $docDate
     * @param \Rebelo\Date\Date $systemEntryDate
     * @param string $doc
     * @param float $grossTotal
     * @param string $lastHash
     * @return void
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testValidSignAndSignValidationSetKeyConstructur($docDate,
                                                                    $systemEntryDate,
                                                                    $doc,
                                                                    $grossTotal,
                                                                    $lastHash): void
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


        $sign = new \Rebelo\SaftPt\Sign\Sign($priKey, $pubKey);

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
     * @param string $lastHash
     * @return void
     * @author João Rebelo
     * @dataProvider dataProvider
     * @test
     */
    public function testWrogSignAndSignValidation($docDate, $systemEntryDate,
                                                  $doc, $grossTotal, $lastHash): void
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

        $sign = new \Rebelo\SaftPt\Sign\Sign($priKey, $pubKey);

        $hash = $sign->createSignature(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        $this->assertFalse(
            $sign->verifySignature(
                $hash, $docDate, $systemEntryDate, $doc, $grossTotal,
                $lastHash."a"
            )
        );
    }

}