<?php

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Blueprint;

/**
 * Indicate that the publish column should be dropped.
 */
Blueprint::macro('dropPublishes', function (string $column = 'published_at') {
    return $this->dropColumn($column);
});
