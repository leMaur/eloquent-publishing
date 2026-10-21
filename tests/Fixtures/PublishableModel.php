<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Lemaur\Publishing\Database\Eloquent\Publishes;

/**
 * Minimal model that uses the Publishes trait, so static analysis checks the trait body.
 */
final class PublishableModel extends Model
{
    use Publishes;
}
