<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Support\Fluent;

final class DropPublishes
{
    /**
     * @return \Illuminate\Support\Fluent
     */
    public function __construct()
    {
        return new Fluent([
            'name' => 'dropColumn',
            'columns' => 'published_at'
        ]);
    }
}
