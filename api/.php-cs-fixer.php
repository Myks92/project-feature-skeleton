<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPyh\CodingStandard\PhpCsFixerCodingStandard;

$finder = Finder::create()
    ->in([
        __DIR__ . '/bin',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->append([
        __FILE__,
    ]);

$config = (new Config())
    ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
    ->setFinder($finder);

(new PhpCsFixerCodingStandard())->applyTo($config, [
    '@PHPUnit100Migration:risky' => true,
]);

return $config;
