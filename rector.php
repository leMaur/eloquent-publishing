<?php

declare(strict_types=1);

return Rector\Config\RectorConfig::configure()
    ->withPaths([__DIR__.'/src'])
    ->withParallel()
    ->withPhpSets()
    ->withImportNames(
        removeUnusedImports: \true,
    )
    ->withPreparedSets(
        deadCode: \true,
        codeQuality: \true,
        codingStyle: \true,
        typeDeclarations: \true,
        naming: \true,
        instanceOf: \true,
        earlyReturn: \true,
        strictBooleans: \true,
        carbon: \true,
        rectorPreset: \true,
    )
    ->withPHPStanConfigs([__DIR__.'/phpstan.neon.dist'])
    ->withSets([
        RectorLaravel\Set\LaravelSetList::LARAVEL_110,
        RectorLaravel\Set\LaravelSetList::LARAVEL_CODE_QUALITY,
    ]);
