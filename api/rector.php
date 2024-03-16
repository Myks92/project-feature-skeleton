<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
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
        __DIR__ . '/migrations',
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])
    ->withCache(__DIR__ . '/var/cache/rector')
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
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,

        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ])
    ->withSkip([
        ReturnNeverTypeRector::class,
        AddSeeTestAnnotationRector::class,
        StringableForToStringRector::class,
        RemoveExtraParametersRector::class,
        PreferPHPUnitThisCallRector::class,
        DisallowedEmptyRuleFixerRector::class,
        NullToStrictStringFuncCallArgRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class => [__DIR__ . '/src/**/Entity'],
        NameImportingPostRector::class => [__DIR__ . '/src/Infrastructure/Notifier/config.php'],
        StringClassNameToClassConstantRector::class => [__DIR__ . '/migrations', __DIR__ . '/src/**/Migration'],
    ])
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_Infrastructure_KernelDevDebugContainer.xml')
    ->withSymfonyContainerPhp(__DIR__ . '/tests/container.php');
