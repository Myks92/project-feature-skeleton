<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/translations'
    ])
    ->withCache(__DIR__ . '/var/cache/rector', FileCacheStorage::class)
    ->withPhpSets()
    ->withRootFiles()
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true
    )
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        phpunit: true
    )
    ->withSets([
        SymfonySetList::SYMFONY_64,
        SymfonySetList::CONFIGS,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_DBAL_40,
        DoctrineSetList::DOCTRINE_ORM_214,

        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ])
    ->withSkip([
        ReturnNeverTypeRector::class,
        AddSeeTestAnnotationRector::class,
        RemoveExtraParametersRector::class,
        PreferPHPUnitThisCallRector::class,
        DisallowedEmptyRuleFixerRector::class,
        NullToStrictStringFuncCallArgRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        NameImportingPostRector::class => [__DIR__ . '/src/Infrastructure/Notifier/config.php'],
        StringClassNameToClassConstantRector::class => [__DIR__ . '/migrations', __DIR__ . '/src/**/Migration'],
    ])
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_Infrastructure_KernelDevDebugContainer.xml')
    ->withSymfonyContainerPhp(__DIR__ . '/tests/container.php');
