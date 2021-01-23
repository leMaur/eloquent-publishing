<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Carbon;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Mockery as m;

class DatabasePublishingTraitTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testPublish()
    {
        $model = m::mock(DatabasePublishingTraitStub::class);
        $model->makePartial();
        $model->shouldReceive('fireModelEvent')->with('publishing')->andReturn(true);
        $model->shouldReceive('save')->once();
        $model->shouldReceive('fireModelEvent')->with('published', false)->andReturn(true);

        $model->publish();

        $this->assertTrue($model->isPublished());
        $this->assertFalse($model->isNotPublished());
        $this->assertFalse($model->isPlanned());
        $this->assertTrue($model->isNotPlanned());
        $this->assertInstanceOf(Carbon::class, $model->published_at);
    }

    public function testPublishingInTheFuture()
    {
        $model = m::mock(DatabasePublishingTraitStub::class);
        $model->makePartial();
        $model->shouldReceive('fireModelEvent')->with('publishing')->andReturn(true);
        $model->shouldReceive('save')->once();
        $model->shouldReceive('fireModelEvent')->with('published', false)->andReturn(true);

        $model->publish(Carbon::now()->addDays(5));

        $this->assertFalse($model->isPublished());
        $this->assertTrue($model->isNotPublished());
        $this->assertTrue($model->isPlanned());
        $this->assertFalse($model->isNotPlanned());
        $this->assertInstanceOf(Carbon::class, $model->published_at);
    }

    public function testUnpublish()
    {
        $model = m::mock(DatabasePublishingTraitStub::class);
        $model->makePartial();
        $model->shouldReceive('fireModelEvent')->with('unpublishing')->andReturn(true);
        $model->shouldReceive('save')->once();
        $model->shouldReceive('fireModelEvent')->with('unpublished', false)->andReturn(true);

        $model->unpublish();

        $this->assertFalse($model->isPublished());
        $this->assertTrue($model->isNotPublished());
        $this->assertFalse($model->isPlanned());
        $this->assertFalse($model->isNotPlanned());
        $this->assertNull($model->published_at);
    }
}

class DatabasePublishingTraitStub
{
    use Publishes;

    public $published_at;
    public $updated_at;
    public $timestamps = true;

    public function newQuery()
    {
        //
    }

    public function getKey()
    {
        return 1;
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function save()
    {
        //
    }

    public function fireModelEvent()
    {
        //
    }

    public function freshTimestamp()
    {
        return Carbon::now();
    }

    public function fromDateTime()
    {
        return 'date-time';
    }

    public function getUpdatedAtColumn()
    {
        return defined('static::UPDATED_AT') ? static::UPDATED_AT : 'updated_at';
    }

    public function setKeysForSaveQuery($query)
    {
        $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());

        return $query;
    }

    protected function getKeyForSaveQuery()
    {
        return 1;
    }
}
