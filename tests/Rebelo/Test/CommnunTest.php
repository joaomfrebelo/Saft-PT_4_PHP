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

namespace Rebelo\Test;

use PHPUnit\Framework\TestCase;

/**
 * Class CommnunTest
 *
 * @author João Rebelo
 */
class CommnunTest
    extends TestCase
{

    public function testReflection(string $class)
    {
        $refClas  = new \ReflectionClass($class);
        $classDoc = $refClas->getDocComment();

        $this->assertEquals(1, \preg_match("/@since(.*)/", $classDoc),
                                           sprintf("Class '%s' doesn't have the @since tag",
                                                   $refClas->getName()));

        $this->testConstant($refClas, $class);

        $this->testProperties($refClas);

        $this->testMethods($refClas);
    }

    public function testConstant(\ReflectionClass $refClas, string $class)
    {
        foreach ($refClas->getConstants() as $constName => $constValue)
        {
            $refConst = new \ReflectionClassConstant($class, $constName);
            $consDoc  = $refConst->getDocComment();
            if ($consDoc == false)
            {
                $this->fail(sprintf("Constant '%s' of class '%s' doen't have doc comment"),
                                    $constName, $class);
            }
            $this->assertEquals(1, \preg_match("/@since(.*)/", $consDoc),
                                               sprintf("Constant '%s' with value '%s' doesn't have the @since tag",
                                                       $constName, $constValue));
        }
    }

    public function testProperties(\ReflectionClass $refClas)
    {
        /* @var $prop \ReflectionProperty */
        foreach ($refClas->getProperties() as $prop)
        {

            $this->assertTrue($prop->hasType(),
                              sprintf("propertie '%s' doesn't have a type defined",
                                      $prop->getName()));
        }
    }

    public function testMethods(\ReflectionClass $refClas)
    {
        /* @var $meth \ReflectionMethod */
        foreach ($refClas->getMethods() as $meth)
        {
            $continue = [
                "__construct",
                "__clone"];
            if (\in_array($meth->getName(), $continue))
            {
                continue;
            }

            $doc = $meth->getDocComment();

            // Verify if has return type
            $this->assertTrue($meth->hasReturnType(),
                              sprintf("method '%s' doesn't have a return type defined",
                                      $meth->getName()));

            //verify return type
            $returnMatch     = null;
            $this->assertEquals(1,
                                \preg_match("/@return(.*)/", $doc, $returnMatch),
                                            sprintf("Method '%s' doesn't have return type documentation",
                                                    $meth->getName()));
            $returnMatchPart = \explode(" ", $returnMatch[0]);

            if ($meth->getReturnType()->getName() === "array")
            {
                if ($meth->getReturnType()->allowsNull())
                {
                    $regexp = "/(\[\]\|null)$/";
                }
                else
                {
                    $regexp = "/(\[\])$/";
                }
                $this->assertEquals(1,
                                    \preg_match($regexp, $returnMatchPart[1]),
                                                sprintf("Method '%s' return type doesn't have same type in documentation",
                                                        $meth->getName()));
            }
            else
            {

                $returnType = $meth->getReturnType()->getName() . ( $meth->getReturnType()->allowsNull()
                    ? "|null"
                    : "");

                $this->assertTrue($returnMatchPart[1] === $returnType || $returnMatchPart[1] === "\\" . $returnType,
                                  sprintf("Method '%s' return type doesn't have same type in documentation",
                                          $meth->getName()));
            }
            //Verify if has the @since tag
            $this->assertEquals(1, \preg_match("/@since(.*)/", $doc),
                                               sprintf("Method '%s' doesn't have the @since tag",
                                                       $meth->getName()));

            /* @var $param \ReflectionParameter */
            foreach ($meth->getParameters() as $param)
            {
                //Verify if parameters have type
                $this->assertTrue($param->hasType(),
                                  sprintf("parameter '%s' of method '%s' doesn't have a type defined",
                                          $param->getName(), $meth->getName()));

                $paramMatch     = null;
                $paramPattern   = "/@param(.*)\\$" . $param->getName() . "/";
                $this->assertEquals(1,
                                    \preg_match($paramPattern, $doc, $paramMatch),
                                                sprintf("parameter '%s' of method '%s' doesn't have documentation",
                                                        $param->getName(),
                                                        $meth->getName()));
                $paramMatchPart = \explode(" ", $paramMatch[0]);

                if ($param->getType()->getName() === "array")
                {
                    $parmType = "/\[\]/";
                    $this->assertEquals(1,
                                        \preg_match($parmType,
                                                    $paramMatchPart[1]),
                                                    sprintf("parameter '%s' of method '%s' doesn't have the indication of array in documentation",
                                                            $param->getName(),
                                                            $meth->getName())
                    );
                }
                else
                {

                    $parmType = $param->getType()->getName() . ( $param->allowsNull()
                        ? "|null"
                        : "");


                    $this->assertTrue($paramMatchPart[1] === $parmType || $paramMatchPart[1] === "\\" . $parmType,
                                      sprintf("parameter '%s' of method '%s' doesn't have same type in documentation",
                                              $param->getName(),
                                              $meth->getName()));
                }
            }
        }
    }

}
