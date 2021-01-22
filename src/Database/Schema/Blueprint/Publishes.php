<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Schema\Blueprint;

use Illuminate\Database\Schema\ColumnDefinition;

final class Publishes
{
    /**
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function __construct()
    {
        return new ColumnDefinition([
            'type' => 'timestamp',
            'name' => 'published_at',
            'precision' => 0
        ]);
    }
}
