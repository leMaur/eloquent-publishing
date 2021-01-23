<?php

declare(strict_types=1);

namespace Lemaur\Publishing;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class PublishingServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        /** Add a "published at" timestamp for the table. */
        Blueprint::macro('publishes', function (string $column = 'published_at', int $precision = 0) {
            $this->timestamp($column, $precision)->nullable();
        });

        /** Add a "published at" timestamp for the table. */
        Blueprint::macro('publishesTz', function (string $column = 'published_at', int $precision = 0) {
            $this->timestampTz($column, $precision)->nullable();
        });

        /** Indicate that the publish column should be dropped. */
        Blueprint::macro('dropPublishes', function (string $column = 'published_at') {
            $this->dropColumn($column);
        });

        /** Indicate that the publish column should be dropped. */
        Blueprint::macro('dropPublishesTz', function (string $column = 'published_at') {
            $this->dropPublishes($column);
        });
    }
}
