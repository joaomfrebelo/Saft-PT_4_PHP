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

namespace Rebelo\Test\SaftPt\Bin;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Bin\Validate;
use Symfony\Component\Process\Process;

/**
 * Class ValidateTest
 *
 * @author João Rebelo
 */
class ValidateTest extends TestCase
{

    /**
     * @author João Rebelo
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $scan = \scandir(SAFT4PHP_TMP_DIR);
        if ($scan === false) {
            throw new \Exception(
                \sprintf("Error scanninig dir '%s'", SAFT4PHP_TMP_DIR)
            );
        }
        foreach ($scan as $file) {
            if (\preg_match("/(_STDERR.txt|_STDOUT.txt)$/", $file) === 1) {
                \unlink(SAFT4PHP_TMP_DIR.DIRECTORY_SEPARATOR.$file);
            }
        }
        \file_put_contents(
            SAFT4PHP_TMP_DIR.DIRECTORY_SEPARATOR."saft4phpTest.log", ""
        );
    }

    /**
     * @author João Rebelo
     * @return array
     */
    public function getBaseArgV(): array
    {
        return [
            0 => './saft4php',
            1 => 'validate'
        ];
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseFloat(): void
    {
        $val = new Validate();

        $fValues = [0.01, "0.01", "=0.01"];
        foreach ($fValues as $float) {
            $this->assertSame(0.01, $val->parseFloat($float, "option"));
        }

        $iValues = [1, "1", "=1", '=1'];
        foreach ($iValues as $int) {
            $this->assertSame(\floatval(1), $val->parseFloat($int, "option"));
        }

        try {
            $val->parseFloat("A", "option");
            $this->fail("Parse float should throw Exception on no float value");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Exception::class, $ex);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testParseBool(): void
    {
        $val = new Validate();

        $trueVal = [true, "true", "=true", "on", "=on", "yes", "=yes", "1", "=1"];
        foreach ($trueVal as $true) {
            $this->assertTrue($val->parseBool($true, "option"));
        }

        $falseVal = [false, "false", "=false", "off", "=off", "no", "=no", "0", "=0"];
        foreach ($falseVal as $false) {
            $this->assertFalse($val->parseBool($false, "option"));
        }

        try {
            $val->parseBool("2", "option");
            $this->fail("Parse bool should throw Exception on no bool value");
        } catch (\Exception | \Error $ex) {
            $this->assertInstanceOf(\Exception::class, $ex);
        }
    }

    /**
     * Register the output process command test
     * @param Process $proc
     * @param string $method
     */
    public function resultRegister(Process $proc, string $method): void
    {
        $fileOut = SAFT4PHP_TMP_DIR
            .DIRECTORY_SEPARATOR
            .\str_replace(["\\", "::"], ["_", "-"], $method)
            ."_STDOUT.txt";

        $fileErr = SAFT4PHP_TMP_DIR
            .DIRECTORY_SEPARATOR
            .\str_replace(["\\", "::"], ["_", "-"], $method)
            ."_STDERR.txt";

        $now = (new \Rebelo\Date\Date())->format(\Rebelo\Date\Date::RFC3339_EXTENDED);

        \file_put_contents($fileOut, $now."\n");
        \file_put_contents($fileOut, "Command:\n", FILE_APPEND);
        \file_put_contents($fileOut, $proc->getCommandLine()."\n", FILE_APPEND);
        \file_put_contents($fileOut, $proc->getOutput(), FILE_APPEND);

        \file_put_contents($fileErr, $now."\n");
        \file_put_contents($fileErr, "Command:\n", FILE_APPEND);
        \file_put_contents($fileErr, $proc->getCommandLine()."\n", FILE_APPEND);
        \file_put_contents($fileErr, $proc->getErrorOutput(), FILE_APPEND);
    }

    /**
     * Pass the option and argumensts, the method will prefix 
     * with the PHP Binary path, the commad file path and the command name
     * @param array $argAndOpt
     * @return array
     */
    public function getBuildPrecessCommand(array $argAndOpt): array
    {
        return $command = \array_merge(
            [
                PHP_BINARY,
                SAFT4PHP_BASE_DIR.DIRECTORY_SEPARATOR."saft4php",
                Validate::COMMAND_NAME
            ], $argAndOpt
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testOnlySaftPath(): void
    {
        $command = $this->getBuildPrecessCommand(
            [
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShort(): void
    {
        $command = $this->getBuildPrecessCommand(
            [
            \sprintf("-%s=ptpt", Validate::OPT_LANG_SHORT),
            \sprintf("-%s=%s", Validate::OPT_PUB_KEY_PATH_SHORT, PUBLIC_KEY_PATH),
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testWrongLang(): void
    {
        $command = $this->getBuildPrecessCommand(
            [
            \sprintf("-%s=ptptpt", Validate::OPT_LANG_SHORT),
            \sprintf("-%s=%s", Validate::OPT_PUB_KEY_PATH_SHORT, PUBLIC_KEY_PATH),
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testOptionLong(): void
    {
        $command = $this->getBuildPrecessCommand(
            [
            \sprintf("--%s=ptpt", Validate::OPT_LANG),
            \sprintf("--%s=%s", Validate::OPT_PUB_KEY_PATH, PUBLIC_KEY_PATH),
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testAllOption(): void
    {
        $logConf = SAFT4PHP_TEST_RESSOURCES_DIR.DIRECTORY_SEPARATOR."log4php.php";

        $command = $this->getBuildPrecessCommand(
            [
            \sprintf("--%s=ptpt", Validate::OPT_LANG),
            \sprintf("--%s=%s", Validate::OPT_PUB_KEY_PATH, PUBLIC_KEY_PATH),
            \sprintf("--%s=%s", Validate::OPT_LOG4PHP_CONG, $logConf),
            \sprintf("--%s=no", Validate::OPT_DEBIT_CREDIT),
            \sprintf("--%s=0.002", Validate::OPT_DELTA_CURRENCY),
            \sprintf("--%s=0.005", Validate::OPT_DELTA_LINES),
            \sprintf("--%s=0.009", Validate::OPT_DELTA_TABLE),
            \sprintf("--%s=0.007", Validate::OPT_DELTA_TABLE),
            \sprintf("--%s=false", Validate::OPT_SHOW_WARNINGS),
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testShortLogAndBoolAndWarnings(): void
    {
        $logConf = SAFT4PHP_TEST_RESSOURCES_DIR.DIRECTORY_SEPARATOR."log4php.php";

        $command = $this->getBuildPrecessCommand(
            [
            \sprintf("--%s=ptpt", Validate::OPT_LANG),
            \sprintf("--%s=%s", Validate::OPT_PUB_KEY_PATH, PUBLIC_KEY_PATH),
            \sprintf("-%s=%s", Validate::OPT_LOG4PHP_CONG_SHORT, $logConf),
            \sprintf("--%s", Validate::OPT_DEBIT_CREDIT),
            \sprintf("--%s=0.002", Validate::OPT_DELTA_CURRENCY),
            \sprintf("--%s=0.005", Validate::OPT_DELTA_LINES),
            \sprintf("--%s=0.009", Validate::OPT_DELTA_TABLE),
            \sprintf("--%s=0.007", Validate::OPT_DELTA_TABLE),
            \sprintf("-%s=true", Validate::OPT_SHOW_WARNINGS_SHORT),
            SAFT_DEMO_PATH
            ]
        );

        $proc = new Process($command);
        try {
            $proc->run();
            $this->assertTrue($proc->isSuccessful());
        } catch (\Exception | \Error $ex) {
            $this->fail($ex->getMessage());
        } finally {
            $this->resultRegister($proc, __METHOD__);
        }
    }
}
