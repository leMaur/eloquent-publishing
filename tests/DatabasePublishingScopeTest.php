<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Mockery as m;
use Lemaur\Publishing\Database\Eloquent\PublishingScope;

class DatabasePublishingScopeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $this->model = m::mock(Model::class);
        $this->model->makePartial();
        $this->scope = m::mock(PublishingScope::class . '[publish]');
        $this->scope->extend($this->builder);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testOnlyPlannedAndPublishedExtension()
    {
        $callback = $this->builder->getMacro('onlyPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testWithoutPlannedAndPublishedExtension()
    {
        $callback = $this->builder->getMacro('withoutPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNull')->once()->with('table.published_at');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOnlyPlannedExtension()
    {
        $callback = $this->builder->getMacro('onlyPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('table.published_at', '>', 'datetime');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOnlyPublishedExtension()
    {
        $callback = $this->builder->getMacro('onlyPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('table.published_at', '<=', 'datetime');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testLatestPublishedExtension()
    {
        $callback = $this->builder->getMacro('latestPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('orderBy')->once()->with('table.published_at', 'desc');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOldestPublishedExtension()
    {
        $callback = $this->builder->getMacro('oldestPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $this->model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('orderBy')->once()->with('table.published_at', 'asc');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testLatestPlannedExtension()
    {
        $callback = $this->builder->getMacro('latestPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $givenBuilder->shouldReceive('latestPublished')->once();
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOldestPlannedExtension()
    {
        $callback = $this->builder->getMacro('oldestPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($this->model);
        $givenBuilder->shouldReceive('oldestPublished')->once();
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }
}
