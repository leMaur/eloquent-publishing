<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Blueprint;

/**
 * Add a "published at" timestamp for the table.
 */
Blueprint::macro('publishes', fn (string $column = 'published_at', int $precision = 0) => $this->timestamp($column, $precision)->nullable());
