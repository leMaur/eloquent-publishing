# Easily make your eloquent model publishable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lemaur/eloquent-publishing.svg?style=flat-square)](https://packagist.org/packages/lemaur/eloquent-publishing)
[![Total Downloads](https://img.shields.io/packagist/dt/lemaur/eloquent-publishing.svg?style=flat-square)](https://packagist.org/packages/lemaur/eloquent-publishing)
[![License](https://img.shields.io/packagist/l/lemaur/eloquent-publishing.svg?style=flat-square&color=yellow)](https://github.com/leMaur/eloquent-publishing/blob/main/LICENSE.md)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lemaur/eloquent-publishing/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/leMaur/eloquent-publishing/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/lemaur/eloquent-publishing/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/leMaur/eloquent-publishing/actions/workflows/fix-php-code-style-issues.yml)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/lemaur?style=flat-square&color=ea4aaa)](https://github.com/sponsors/leMaur)
[![Trees](https://img.shields.io/badge/dynamic/json?color=yellowgreen&style=flat-square&label=Trees&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Flemaur%2Ftrees)](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9)

This package provides a trait that will help you publishing eloquent models.
```php
use Lemaur\Publishing\Database\Eloquent\Publishes;

class Post extends Model
{
    use Publishes;
}
```
It also includes custom schema builder blueprint methods to help you setting up your migrations with ease.

## Support Me

Hey folks,

Do you like this package? Do you find it useful and it fits well in your project?

I am glad to help you, and I would be so grateful if you considered supporting my work.

You can even choose 😃:
* You can [sponsor me 😎](https://github.com/sponsors/leMaur) with a monthly subscription.
* You can [buy me a coffee ☕ or a pizza 🍕](https://github.com/sponsors/leMaur?frequency=one-time&sponsor=leMaur) just for this package.
* You can [plant trees 🌴](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9). By using this link we will both receive 30 trees for free and the planet (and me) will thank you. 
* You can "Star ⭐" this repository (it's free 😉).

## Installation

You can install the package via composer:

```bash
composer require lemaur/eloquent-publishing
```

## Usage
Your eloquent models should use the `Lemaur\Publishing\Database\Eloquent\Publishes` trait.

Your migration files should have a field to save the publishing date.

Here's a real-life example of how to implement the trait on a Post model.

[_(jump to all the available methods)_](#available-methods)
```php
/** app\Models\Post.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lemaur\Publishing\Database\Eloquent\Publishes;

class Post extends Model
{
    use Publishes;
}
```

```php
/** database\migrations\create_posts_table.php */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->longText('body')->nullable();
            $table->timestamps();

            $table->publishes(); // equivalent to `$table->timestamp('published_at')->nullable();`
        });
    }

    ...
}
```

### Available methods

#### Using in your migration files.
```php
/** add a nullable timestamp column named "published_at"  */
$table->publishes();

/** it may accepts a custom column name and an optional precision (total digits) */
$table->publishes('published_at', $precision = 0);

/** add a nullable timestampTz column named "published_at"  */
$table->publishesTz();

/** it may accepts a custom column name and an optional precision (total digits) */
$table->publishesTz('published_at', $precision = 0);

/** drop the column named "published_at"  */
$table->dropPublishes();

/** it may accepts a custom column name */
$table->dropPublishes('published_at');

/** drop the column named "published_at"  */
$table->dropPublishesTz();

/** it may accepts a custom column name */
$table->dropPublishesTz('published_at');
```
> For more information about timestamps, refer to the [Laravel Documentation](https://laravel.com/docs/8.x/migrations#column-method-timestamp)

[_(jump to the customize section)_](#customize)

#### Using in your controllers, actions or whatever you need

```php
// Publish your model (this set the publish date at the current date time)
$post->publish();

// Publish your model with custom date time (can be in the future or in the past, as you wish. It accepts a class that implement \DatetimeInterface)
$post->publish(Carbon::parse('tomorrow'));

// Unpublish your model
$post->unpublish();

// Check if the model is published (current date time or in the past)
$bool = $post->isPublished();

// Check if the model is not published
$bool = $post->isNotPublished();

// Check if the model is published with a date time in the future
$bool = $post->isPlanned();

// Check if the model is not planned
$bool = $post->isNotPlanned();

// Show only published posts
$onlyPublishedPosts = Post::onlyPublished()->get();

// Show only planned posts
$onlyPlannedPosts = Post::onlyPlanned()->get();

// Show only planned and published posts
$onlyPlannedAndPublishedPosts = Post::onlyPlannedAndPublished()->get();

// Show only posts not planned nor published
$withoutPlannedAndPublishedPosts = Post::withoutPlannedAndPublished()->get();

// Order by latest published posts
$latestPublishedPosts = Post::latestPublished()->get();

// Order by oldest published posts
$oldestPublishedPosts = Post::oldestPublished()->get();

// Order by latest planned posts
$latestPlannedPosts = Post::latestPlanned()->get();

// Order by oldest planned posts
$oldestPlannedPosts = Post::oldestPlanned()->get();

// or you can combine them together...

// Get only published posts ordered by latest published
$posts = Post::onlyPublished()->latestPublished()->get();

// Get only planned posts ordered by latest planned
$posts = Post::onlyPlanned()->latestPlanned()->get();

```

## Customize

If you want to change the column name, you need to specify it in your model and in your migration file. Let me show you:
```php
// in your model

class Post extends Model
{
    use Publishes;

    /**
     * The custom name of the "published at" column.
     *
     * @var string
     */
    const PUBLISHED_AT = 'publish_date';
}

// in your migration file

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            ...
            $table->publishes('publish_date');
        });
    }

    ...
}
```

## Events

When you publish or unpublish a model, the package dispatches several events: `publishing`, `published`, `unpublishing`, `unpublished`.

The `publishing` / `published` events will dispatch when a model is published.
The `unpublishing` / `unpublished` events will dispatch when a model is unpublished.

> For more information about the events, refer to the [Laravel Documentation](https://laravel.com/docs/8.x/eloquent#events)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerability

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maurizio](https://github.com/lemaur)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
