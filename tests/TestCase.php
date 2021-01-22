<?php

declare(strict_types=1);

namespace Tests;

use Lemaur\Publishing\PublishingServiceProvider;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

abstract class TestCase extends FrameworkTestCase
{
    protected function getPackageProviders($app): array
    {
        return [PublishingServiceProvider::class];
    }
}
