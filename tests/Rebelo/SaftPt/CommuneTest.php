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

namespace Rebelo\SaftPt;

use PHPUnit\Framework\TestCase;

/**
 * Class CommnunTest
 *
 * @author João Rebelo
 */
class CommuneTest extends TestCase
{

    /**
     *
     * @param class-string $class
     * @return void
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testReflection(string $class): void
    {
        $refClas  = new \ReflectionClass($class);
        $classDoc = $refClas->getDocComment();

        if ($classDoc === false) {
            $this->fail(
                \sprintf(
                    "Class '%s' doesn't have documentation",
                    $refClas->getName()
                )
            );
        }

        $this->assertEquals(
            1, \preg_match("/@since(.*)/", $classDoc),
            \sprintf(
                "Class '%s' doesn't have the @since tag", $refClas->getName()
            )
        );

        $this->testConstant($refClas, $class);

        $this->testProperties($refClas);

        $this->testMethods($refClas);

        $this->testStrict($refClas);
    }

    public function testConstant(\ReflectionClass $refClas, string $class): void
    {
        foreach ($refClas->getConstants() as $constName => $constValue) {
            $refConst = new \ReflectionClassConstant($class, $constName);
            $consDoc  = $refConst->getDocComment();
            if ($consDoc === false) {
                $this->fail(
                    \sprintf(
                        "Constant '%s' of class '%s' doesn't have doc comment",
                        $constName, $class
                    )
                );
            }
            $this->assertEquals(
                1, \preg_match("/@since(.*)/", $consDoc),
                sprintf(
                    "Constant '%s' with value '%s' doesn't have the @since tag",
                    $constName, $constValue
                )
            );
        }
    }

    public function testProperties(\ReflectionClass $refClas): void
    {
        foreach ($refClas->getProperties() as $prop) {

            $this->assertTrue(
                $prop->hasType(),
                sprintf(
                    "propertie '%s' doesn't have a type defined",
                    $prop->getName()
                )
            );
        }
    }

    public function testMethods(\ReflectionClass $refClas): void
    {
        foreach ($refClas->getMethods() as $meth) {
            $continue = [
                "__construct",
                "__clone"];
            if (\in_array($meth->getName(), $continue)) {
                continue;
            }

            $doc = $meth->getDocComment();

            if ($doc === false) {
                $this->fail(
                    \sprintf(
                        "The method '%s' of class '%s' does not have documentation",
                        $meth->getName(), $refClas->getName()
                    )
                );
            }

            // Verify if it has return type
            $this->assertTrue(
                $meth->hasReturnType(),
                sprintf(
                    "method '%s' doesn't have a return type defined",
                    $meth->getName()
                )
            );

            //verify return type
            $returnMatch     = null;
            $this->assertEquals(
                1,
                \preg_match("/@return(.*)/", $doc, $returnMatch),
                sprintf(
                    "Method '%s' doesn't have return type documentation",
                    $meth->getName()
                )
            );

            //Verify if it has the @since tag
            $this->assertEquals(
                1, \preg_match("/@since(.*)/", $doc),
                sprintf(
                    "Method '%s' doesn't have the @since tag",
                    $meth->getName()
                )
            );

            foreach ($meth->getParameters() as $param) {
                //Verify if parameters have type
                $this->assertTrue(
                    $param->hasType(),
                    sprintf(
                        "parameter '%s' of method '%s' doesn't have a type defined",
                        $param->getName(), $meth->getName()
                    )
                );

                $paramMatch     = null;
                $paramPattern   = "/@param(.*)\\$".$param->getName()."/";
                $this->assertEquals(
                    1,
                    \preg_match($paramPattern, $doc, $paramMatch),
                    sprintf(
                        "parameter '%s' of method '%s' doesn't have documentation",
                        $param->getName(), $meth->getName()
                    )
                );
                $paramMatchPart = \explode(" ", $paramMatch[0]);

                /** @phpstan-ignore-next-line */
                if ($param->getType()->getName() === "array") {
                    $parmType = "/array|\[\]/";
                    $this->assertEquals(
                        1,
                        \preg_match($parmType, $paramMatchPart[1]),
                        sprintf(
                            "parameter '%s' of method '%s' doesn't have the indication of array in documentation",
                            $param->getName(), $meth->getName()
                        )
                    );
                } else {

                    /** @phpstan-ignore-next-line */
                    $parmType = $param->getType()->getName().( $param->allowsNull()
                            ? "|null" : "");


                    $this->assertTrue(
                        $paramMatchPart[1] === $parmType || $paramMatchPart[1]
                        === "\\".$parmType,
                        sprintf(
                            "parameter '%s' of method '%s' doesn't have same type in documentation",
                            $param->getName(), $meth->getName()
                        )
                    );
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testStrict(\ReflectionClass $refClas): void
    {
        $path             = $refClas->getFileName();
        if ($path === false) {
            throw new \Exception("Can not get class file path");
        }

        $strFile = \file_get_contents($path);
        if ($strFile === false) {
            throw new \Exception("Can not get class file contents");
        }

        $patternEquals    = "/ == /";
        $patternNotEquals = "/ != /";

        if (preg_match($patternEquals, $strFile) !== 0) {
            $this->fail("The 'strict' test fail, please switch the '==' for '==='");
        }

        if (preg_match($patternNotEquals, $strFile) !== 0) {
            $this->fail("The 'strict' test fail, please switch the '!=' for '!=='");
        }
    }
}
