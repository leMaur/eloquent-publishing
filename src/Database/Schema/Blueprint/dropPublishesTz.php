<?php

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Blueprint;

/**
 * Indicate that the publish column should be dropped.
 */
Blueprint::macro('dropPublishesTz', function (string $column = 'published_at') {
    return $this->dropPublishes($column);
});
