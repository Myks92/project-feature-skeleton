<?php

declare(strict_types=1);

use PHPyh\CodingStandard\PhpCsFixerCodingStandard;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/bin',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->append([
        __FILE__,
    ]);

$config = (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
    ->setFinder($finder);

(new PhpCsFixerCodingStandard())->applyTo($config);

return $config;
