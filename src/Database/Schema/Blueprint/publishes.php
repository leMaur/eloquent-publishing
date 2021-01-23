<?php

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Blueprint;

/**
 * Add a "published at" timestamp for the table.
 */
Blueprint::macro('publishes', function (string $column = 'published_at', int $precision = 0) {
    return $this->timestamp($column, $precision)->nullable();
});
