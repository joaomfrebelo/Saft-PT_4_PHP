<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
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

spl_autoload_register(function ($class) {
    if (\strpos("\\", $class) === 0) {
        /** @var string Class name Striped of the first blackslash */
        $class = \substr($class, 1, \strlen($class) - 1);
    }

    $path = __DIR__
        .DIRECTORY_SEPARATOR
        .".."
        .DIRECTORY_SEPARATOR
        ."src"
        .DIRECTORY_SEPARATOR
        .$class
        .".php";
    if (is_file($path)) {
        require_once $path;
    }
});

// Define SAFT Demo path
define(
    "SAFT_DEMO_PATH",
    __DIR__.DIRECTORY_SEPARATOR."Ressources"
    .DIRECTORY_SEPARATOR."saft_idemo599999999.xml"
);

define("IS_UNIT_TEST", true);
