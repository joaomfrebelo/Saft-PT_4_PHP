<?php /** @noinspection PhpUnused */
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
$resourceFilePath = __DIR__ . DIRECTORY_SEPARATOR . "Resources"  .DIRECTORY_SEPARATOR."Resources.php";

if (is_file($resourceFilePath)) {
    require_once $resourceFilePath;
}

require_once __DIR__
    .DIRECTORY_SEPARATOR.".."
    .DIRECTORY_SEPARATOR."vendor"
    .DIRECTORY_SEPARATOR."autoload.php";

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Info));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Alert));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Debug));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Notice));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Error));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Emergency));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Critical));
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Warning));
$logger->pushHandler(new \Monolog\Handler\FirePHPHandler());

Rebelo\SaftPt\AuditFile\AAuditFile::$logger = $logger;

require join(DIRECTORY_SEPARATOR, [__DIR__, 'Rebelo', 'SaftPt', 'Commune.php']);
require join(DIRECTORY_SEPARATOR, [__DIR__, 'Rebelo', 'SaftPt', 'TXmlTest.php']);

spl_autoload_register(
    function ($class)
    {
        if (str_starts_with("\\", $class)) {
            $class = substr($class, 1, strlen($class) - 1);
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
const SAFT4PHP_TEST_RESOURCES_DIR = __DIR__ . DIRECTORY_SEPARATOR . "Resources";
