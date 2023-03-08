<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Eloquent;

use DateTimeInterface;
use Illuminate\Support\Facades\Date;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder OnlyPlannedAndPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder WithoutPlannedAndPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder OnlyPlanned()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder OnlyPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder LatestPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder OldestPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder LatestPlanned()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder OldestPlanned()
 */
trait Publishes
{
    /**
     * Boot the publishing trait for a model.
     */
    public static function bootPublishes(): void
    {
        static::addGlobalScope(new PublishingScope());
    }

    /**
     * Initialize the publishes trait for an instance.
     */
    public function initializePublishes(): void
    {
        if (! isset($this->casts[$this->getPublishedAtColumn()])) {
            $this->casts[$this->getPublishedAtColumn()] = 'datetime';
        }
    }

    /**
     * Publish the model instance.
     */
    public function publish(?DateTimeInterface $datetime = null): ?bool
    {
        // If the publishing event does not return false, we will proceed with this
        // publish operation. Otherwise, we bail out so the developer will stop
        // the publish totally. We will clear the published timestamp and save.
        if ($this->fireModelEvent('publishing') === false) {
            return false;
        }

        $this->{$this->getPublishedAtColumn()} = $datetime ?? $this->freshTimestamp();

        // Once we have saved the model, we will fire the "published" event so this
        // developer will do anything they need to after a publish operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('published', false);

        return $result;
    }

    /**
     * Unpublish the model instance.
     */
    public function unpublish(): ?bool
    {
        // If the unpublishing event does not return false, we will proceed with this
        // unpublish operation. Otherwise, we bail out so the developer will stop
        // the unpublish totally.
        if ($this->fireModelEvent('unpublishing') === false) {
            return false;
        }

        $this->{$this->getPublishedAtColumn()} = null;

        // Once we have saved the model, we will fire the "unpublished" event so this
        // developer will do anything they need to after an unpublish operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('unpublished', false);

        return $result;
    }

    /**
     * Determine if the model instance is published.
     */
    public function isPublished(): bool
    {
        return $this->{$this->getPublishedAtColumn()} !== null
            && $this->{$this->getPublishedAtColumn()} <= Date::now();
    }

    /**
     * Determine if the model instance is not published.
     */
    public function isNotPublished(): bool
    {
        return ! $this->isPublished();
    }

    /**
     * Determine if the model instance is planned.
     */
    public function isPlanned(): bool
    {
        return $this->{$this->getPublishedAtColumn()} !== null
            && $this->{$this->getPublishedAtColumn()} > Date::now();
    }

    /**
     * Determine if the model instance is not planned.
     */
    public function isNotPlanned(): bool
    {
        return $this->{$this->getPublishedAtColumn()} !== null
            && $this->{$this->getPublishedAtColumn()} <= Date::now();
    }

    /**
     * Register a publishing model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function publishing($callback): void
    {
        static::registerModelEvent('publishing', $callback);
    }

    /**
     * Register a published model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function published($callback): void
    {
        static::registerModelEvent('published', $callback);
    }

    /**
     * Register an unpublishing model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function unpublishing($callback): void
    {
        static::registerModelEvent('unpublishing', $callback);
    }

    /**
     * Register an unpublished model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function unpublished($callback): void
    {
        static::registerModelEvent('unpublished', $callback);
    }

    /**
     * Get the name of the "published at" column.
     */
    public function getPublishedAtColumn(): string
    {
        return defined('static::PUBLISHED_AT') ? static::PUBLISHED_AT : 'published_at';
    }

    /**
     * Get the fully qualified "published at" column.
     */
    public function getQualifiedPublishedAtColumn(): string
    {
        return $this->qualifyColumn($this->getPublishedAtColumn());
    }
}
