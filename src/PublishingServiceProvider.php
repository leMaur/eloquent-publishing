<?php

declare(strict_types=1);

namespace Lemaur\Publishing;

use Illuminate\Support\ServiceProvider;

class PublishingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        collect(glob(__DIR__ . '/Database/Schema/Blueprint/*.php'))
            ->each(function ($path) {
                require $path;
            });
    }
}
