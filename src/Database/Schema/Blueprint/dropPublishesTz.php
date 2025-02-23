<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Blueprint;

/**
 * Indicate that the publish column should be dropped.
 */
Blueprint::macro('dropPublishesTz', fn (string $column = 'published_at') => $this->dropPublishes($column));
