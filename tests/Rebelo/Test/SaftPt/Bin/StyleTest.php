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
use \Rebelo\SaftPt\Bin\Style;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\ConsoleOutput;
use \Symfony\Component\Console\Helper\ProgressBar;
use \Symfony\Component\Console\Output\ConsoleSectionOutput;

/**
 * Class StyleTest
 *
 * @author João Rebelo
 */
class StyleTest extends TestCase
{
    /**
     * 
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected static InputInterface $input;

    /**
     *
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     */
    protected static ConsoleOutput $output;

    /**
     * @author João Rebelo
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        static::$input = new class implements \Symfony\Component\Console\Input\InputInterface {

            public function bind(\Symfony\Component\Console\Input\InputDefinition $definition)/** @phpstan-ignore-line */
            {
                
            }

            public function getArgument(string $name)/** @phpstan-ignore-line */
            {
                
            }

            public function getArguments(): array
            {
                
            }

            public function getFirstArgument()/** @phpstan-ignore-line */
            {
                
            }

            public function getOption(string $name)/** @phpstan-ignore-line */
            {
                
            }

            public function getOptions(): array
            {
                
            }

            public function getParameterOption($values, $default = false,
                                               bool $onlyParams = false)/** @phpstan-ignore-line */
            {
                
            }

            public function hasArgument($name): bool
            {
                
            }

            public function hasOption(string $name): bool
            {
                
            }

            public function hasParameterOption($values, bool $onlyParams = false): bool
            {
                
            }

            public function isInteractive(): bool
            {
                
            }

            public function setArgument($name, $value)/** @phpstan-ignore-line */
            {
                
            }

            public function setInteractive(bool $interactive)/** @phpstan-ignore-line */
            {
                
            }

            public function setOption($name, $value)/** @phpstan-ignore-line */
            {
                
            }
            /* @phpstan-ignore-next-line */

            public function validate()
            {
                
            }
        };

        static::$output = new ConsoleOutput();
    }

    /**
     * @author João Rebelo
     * @return void
     */
    public function testInstance(): void
    {
        $style = new \Rebelo\SaftPt\Bin\Style(static::$input, static::$output);
        $this->assertInstanceOf(Style::class, $style);
        $this->assertInstanceOf(InputInterface::class, $style->getInput());
        $this->assertInstanceOf(ConsoleOutput::class, $style->getOutput());

        $section = null;
        $this->assertInstanceOf(
            ProgressBar::class,
            $style->addProgressBar($section)
        );
        $this->assertInstanceOf(ConsoleSectionOutput::class, $section);

        $newSection  = clone $section;
        $copySection = $newSection;
        $this->assertInstanceOf(
            ProgressBar::class,
            $style->addProgressBar($newSection)
        );
        $this->assertSame($copySection, $newSection);
    }
}