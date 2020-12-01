<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
$resourceFilePath = __DIR__
    .DIRECTORY_SEPARATOR."Ressources"
    .DIRECTORY_SEPARATOR."Ressources.php";

if (is_file($resourceFilePath)) {
    require_once $resourceFilePath;
}

require_once __DIR__
    .DIRECTORY_SEPARATOR.".."
    .DIRECTORY_SEPARATOR."vendor"
    .DIRECTORY_SEPARATOR."autoload.php";

require __DIR__
    .DIRECTORY_SEPARATOR.'Rebelo'
    .DIRECTORY_SEPARATOR.'Test'
    .DIRECTORY_SEPARATOR.'CommnunTest.php';

require __DIR__
    .DIRECTORY_SEPARATOR.'Rebelo'
    .DIRECTORY_SEPARATOR.'Test'
    .DIRECTORY_SEPARATOR.'TXmlTest.php';

spl_autoload_register(
    function ($class)
    {
        if (\strpos("\\", $class) === 0) {
            /** @var string Class name Striped of the first blackslash */
            $class = \substr($class, 1, \strlen($class) - 1);
        }

        $pathBase = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
        $pathSrc  = $pathBase."src".DIRECTORY_SEPARATOR.$class.".php";
        if (is_file($pathSrc)) {
            require_once $pathSrc;
            return;
        }

        $pathTests = $pathBase."tests".DIRECTORY_SEPARATOR.$class.".php";
        if (is_file($pathTests)) {
            require_once $pathTests;
            return;
        }
    }
);

define("IS_UNIT_TEST", true);

/**
 * The tests resources directory
 */
define("SAFT4PHP_TEST_RESSOURCES_DIR", __DIR__.DIRECTORY_SEPARATOR."Ressources");
