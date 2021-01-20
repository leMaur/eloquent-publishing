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
        $model = m::mock(Model::class);
        $model->makePartial();
        $scope = m::mock(PublishingScope::class . '[update]');
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($model);
        $givenBuilder->shouldReceive('withoutGlobalScope')->with($scope)->andReturn($givenBuilder);
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at');
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
        $model = m::mock(Model::class);
        $model->makePartial();
        $scope = m::mock(PublishingScope::class . '[update]');
        $scope->extend($builder);
        $callback = $builder->getMacro('withoutPlannedAndPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($model);
        $givenBuilder->shouldReceive('withoutGlobalScope')->with($scope)->andReturn($givenBuilder);
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNull')->once()->with('table.published_at');
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
        $model = m::mock(Model::class);
        $model->makePartial();
        $scope = m::mock(PublishingScope::class . '[update]');
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPlanned');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($model);
        $givenBuilder->shouldReceive('withoutGlobalScope')->with($scope)->andReturn($givenBuilder);
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('table.published_at', '>', 'datetime');
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
        $model = m::mock(Model::class);
        $model->makePartial();
        $scope = m::mock(PublishingScope::class . '[update]');
        $scope->extend($builder);
        $callback = $builder->getMacro('onlyPublished');
        $givenBuilder = m::mock(EloquentBuilder::class);
        $givenBuilder->shouldReceive('getQuery')->andReturn($query = m::mock(stdClass::class));
        $givenBuilder->shouldReceive('getModel')->andReturn($model);
        $givenBuilder->shouldReceive('withoutGlobalScope')->with($scope)->andReturn($givenBuilder);
        $model->shouldReceive('getQualifiedPublishedAtColumn')->andReturn('table.published_at');
        $givenBuilder->shouldReceive('whereNotNull')->once()->with('table.published_at')->andReturn($givenBuilder);
        $givenBuilder->shouldReceive('where')->once()->with('table.published_at', '<=', 'datetime');
        $result = $callback($givenBuilder);

        $this->assertEquals($givenBuilder, $result);
    }
}
