<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Lemaur\Publishing\Database\Eloquent\Publishes;

class DatabasePublishingTest extends TestCase
{
    public function test_published_at_is_added_to_casts_as_default_type()
    {
        $model = new PublishingModel;

        $this->assertArrayHasKey('published_at', $model->getCasts());
        $this->assertSame('datetime', $model->getCasts()['published_at']);
    }

    public function test_published_at_is_cast_to_carbon_instance()
    {
        Carbon::setTestNow(Carbon::now());
        $expected = Carbon::createFromFormat('Y-m-d H:i:s', '2021-01-01 12:59:39');
        $model = new PublishingModel(['published_at' => $expected->format('Y-m-d H:i:s')]);

        $this->assertInstanceOf(Carbon::class, $model->published_at);
        $this->assertTrue($expected->eq($model->published_at));
    }

    public function test_existing_cast_overrides_added_date_cast()
    {
        $model = new class(['published_at' => '2021-01-01 12:59:39']) extends PublishingModel
        {
            protected $casts = ['published_at' => 'bool'];
        };

        $this->assertTrue($model->published_at);
    }

    public function test_existing_mutator_overrides_added_date_cast()
    {
        $model = new class(['published_at' => '2021-01-01 12:59:39']) extends PublishingModel
        {
            protected function getPublishedAtAttribute()
            {
                return 'expected';
            }
        };

        $this->assertSame('expected', $model->published_at);
    }

    public function test_casting_to_string_overrides_automatic_date_casting_to_retain_previous_behaviour()
    {
        $model = new class(['published_at' => '2021-01-01 12:59:39']) extends PublishingModel
        {
            protected $casts = ['published_at' => 'string'];
        };

        $this->assertSame('2021-01-01 12:59:39', $model->published_at);
    }
}

class PublishingModel extends Model
{
    use Publishes;

    protected $guarded = [];

    protected $dateFormat = 'Y-m-d H:i:s';
}
