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

require \join(DIRECTORY_SEPARATOR, [__DIR__, 'Rebelo', 'SaftPt', 'CommuneTest.php']);
require \join(DIRECTORY_SEPARATOR, [__DIR__, 'Rebelo', 'SaftPt', 'TXmlTest.php']);

spl_autoload_register(
    function ($class)
    {
        if (str_starts_with("\\", $class)) {
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
        }
    }
);

const IS_UNIT_TEST = true;

/**
 * The tests resources directory
 */
const SAFT4PHP_TEST_RESSOURCES_DIR = __DIR__ . DIRECTORY_SEPARATOR . "Ressources";
