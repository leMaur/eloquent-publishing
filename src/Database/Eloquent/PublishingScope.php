<?php

declare(strict_types=1);

namespace Lemaur\Publishing\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Date;

class PublishingScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = [
        'OnlyPlannedAndPublished',
        'WithoutPlannedAndPublished',
        'OnlyPlanned',
        'OnlyPublished',
        'LatestPublished',
        'OldestPublished',
        'LatestPlanned',
        'OldestPlanned',
    ];

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
    }

    /**
     * Extend the query builder with the needed functions.
     */
    public function extend(Builder $builder): void
    {
        foreach ($this->extensions as $extension) {
            $this->{'add'.$extension}($builder);
        }
    }

    /**
     * Get the "published at" column for the builder.
     */
    protected function getPublishedAtColumn(Builder $builder): string
    {
        if ((array) $builder->getQuery()->joins !== []) {
            return $builder->getModel()->getQualifiedPublishedAtColumn();
        }

        return $builder->getModel()->getPublishedAtColumn();
    }

    /**
     * Add the only published extension to the builder.
     */
    protected function addOnlyPublished(Builder $builder): void
    {
        $builder->macro('onlyPublished', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->whereNotNull(
                $model->getQualifiedPublishedAtColumn()
            )->where(
                $model->getQualifiedPublishedAtColumn(),
                '<=',
                Date::now()
            );

            return $builder;
        });
    }

    /**
     * Add the only planned extension to the builder.
     */
    protected function addOnlyPlanned(Builder $builder): void
    {
        $builder->macro('onlyPlanned', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->whereNotNull(
                $model->getQualifiedPublishedAtColumn()
            )->where(
                $model->getQualifiedPublishedAtColumn(),
                '>',
                Date::now()
            );

            return $builder;
        });
    }

    /**
     * Add the only planned and published extension to the builder.
     */
    protected function addOnlyPlannedAndPublished(Builder $builder): void
    {
        $builder->macro('onlyPlannedAndPublished', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->whereNotNull($model->getQualifiedPublishedAtColumn());

            return $builder;
        });
    }

    /**
     * Add the without planned and published extension to the builder.
     */
    protected function addWithoutPlannedAndPublished(Builder $builder): void
    {
        $builder->macro('withoutPlannedAndPublished', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->whereNull($model->getQualifiedPublishedAtColumn());

            return $builder;
        });
    }

    /**
     * Add the latest published extension to the builder.
     */
    protected function addLatestPublished(Builder $builder): void
    {
        $builder->macro('latestPublished', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->orderBy($model->getQualifiedPublishedAtColumn(), 'desc');

            return $builder;
        });
    }

    /**
     * Add the oldest published extension to the builder.
     */
    protected function addOldestPublished(Builder $builder): void
    {
        $builder->macro('oldestPublished', function (Builder $builder): Builder {
            $model = $builder->getModel();

            $builder->orderBy($model->getQualifiedPublishedAtColumn(), 'asc');

            return $builder;
        });
    }

    /**
     * Add the latest planned extension to the builder.
     */
    protected function addLatestPlanned(Builder $builder): void
    {
        $builder->macro('latestPlanned', function (Builder $builder): Builder {
            $builder->latestPublished();

            return $builder;
        });
    }

    /**
     * Add the oldest planned extension to the builder.
     */
    protected function addOldestPlanned(Builder $builder): void
    {
        $builder->macro('oldestPlanned', function (Builder $builder): Builder {
            $builder->oldestPublished();

            return $builder;
        });
    }
}
