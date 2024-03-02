<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use Webmozart\Glob\Glob;

return static function (Configuration $config): Configuration {
    $config
        ->setAdditionalFilesFor('myks92/project-feature-skeleton', [
            __FILE__,
            ...Glob::glob(__DIR__ . '/config/*.php'),
        ]);

    $config->addNamedFilter(NamedFilter::fromString('symfony/property-access'));
    $config->addNamedFilter(NamedFilter::fromString('phpdocumentor/reflection-docblock'));
    $config->addNamedFilter(NamedFilter::fromString('symfony/runtime'));
    $config->addNamedFilter(NamedFilter::fromString('symfony/flex'));
    $config->addNamedFilter(NamedFilter::fromString('symfony/http-client'));
    $config->addNamedFilter(NamedFilter::fromString('league/flysystem-memory'));

    return $config;
};
