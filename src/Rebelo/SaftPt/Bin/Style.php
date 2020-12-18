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

namespace Rebelo\SaftPt\Bin;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;

/**
 * This class extends SynfonyStyle adding a method to facilitate the use of
 * multiple ProgressBar
 *
 * @author João Rebelo
     * @since 1.0.0
 */
class Style extends SymfonyStyle
{
    /**
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     * @since 1.0.0
     */
    protected InputInterface $in;
    
    /**
     *
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     * @since 1.0.0
     */
    protected ConsoleOutput $out;

    /**
     * This class extends SynfonyStyle adding a method to facilitate the use of
     * multiple ProgressBar
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\ConsoleOutput $output
     * @since 1.0.0
     */
    public function __construct(InputInterface $input, ConsoleOutput $output)
    {
        $this->in  = $input;
        $this->out = $output;
        parent::__construct($input, $output);
    }

    /**
     * Add a Progess bar to a OutputSection. The section argument is passed
     * as refrence to be possible to pass the Output console or to get it into.
     * This method is to be possible the use of multiple ProgessBar
     * @param \Symfony\Component\Console\Output\ConsoleSectionOutput|null $section
     * @return \Symfony\Component\Console\Helper\ProgressBar
     * @since 1.0.0
     */
    public function addProgressBar(?ConsoleSectionOutput &$section = null) : ?ProgressBar 
    {
        if ($section === null) {
            $section = $this->out->section();
        }
        
        $progBar = new ProgressBar($section);
        return $progBar;
    }

    /**
     * Get the Console Input 
     * @return \Symfony\Component\Console\Input\InputInterface
     * @since 1.0.0
     */
    public function getInput(): InputInterface
    {
        return $this->in;
    }

    /**
     * Get the ConsoleOutput
     * @return \Symfony\Component\Console\Output\ConsoleOutput
     * @since 1.0.0
     */
    public function getOutput(): ConsoleOutput
    {
        return $this->out;
    }
}
