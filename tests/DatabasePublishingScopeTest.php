<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Lemaur\Publishing\Database\Eloquent\PublishingScope;
use Mockery as m;
use stdClass;

class DatabasePublishingScopeTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testOnlyPlannedAndPublishedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('published_at');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testWithoutPlannedAndPublishedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('withoutPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('whereNull')->once()->with('published_at');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOnlyPlannedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('published_at', '>', 'datetime');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOnlyPublishedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('published_at', '<=', 'datetime');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testLatestPublishedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('latestPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('orderBy')->once()->with('published_at', 'desc');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOldestPublishedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('oldestPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn($model = m::mock(stdClass::class));
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('published_at');
        $givenBuilder->shouldReceive('orderBy')->once()->with('published_at', 'asc');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testLatestPlannedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('latestPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn(m::mock(stdClass::class));
        $givenBuilder->shouldReceive('latestPublished')->once();
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }

    public function testOldestPlannedExtension()
    {
        $builder = new EloquentBuilder(new BaseBuilder(
            m::mock(ConnectionInterface::class),
            m::mock(Grammar::class),
            m::mock(Processor::class)
        ));
        $scope = new PublishingScope;
        $scope->extend($builder);
        $callback = $builder->getMacro('oldestPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getModel')->andReturn(m::mock(stdClass::class));
        $givenBuilder->shouldReceive('oldestPublished')->once();
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }
}
