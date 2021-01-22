<?php

declare(strict_types=1);

namespace Lemaur\Publishing;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Lemaur\Publishing\Database\Schema\Blueprint as SchemaBlueprint;

class PublishingServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        /** Add a "published at" timestamp for the table. */
        Blueprint::macro('publishes', new SchemaBlueprint\Publishes());

        /** Add a "published at" timestamp for the table. */
        Blueprint::macro('publishesTz', new SchemaBlueprint\PublishesTz());

        /** Indicate that the publish column should be dropped. */
        Blueprint::macro('dropPublishes', new SchemaBlueprint\DropPublishes());

        /** Indicate that the publish column should be dropped. */
        Blueprint::macro('dropPublishesTz', new SchemaBlueprint\DropPublishesTz());
    }
}
