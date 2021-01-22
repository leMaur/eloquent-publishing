<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Schema\Blueprint;

final class DropPublishesTz
{
    /**
     * @return \Illuminate\Support\Fluent
     */
    public function __construct()
    {
        return new DropPublishes();
    }
}
