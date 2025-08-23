<?php

namespace  Modules\Base\scope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class DisablingScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = ['Disable', 'Enable', 'WithDisabled', 'WithoutDisabled', 'OnlyDisabled'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder
     * @param  Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedDisabledAtColumn());
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Get the "disabled at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getDisabledAtColumn(Builder $builder)
    {
        if (count((array) $builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDisabledAtColumn();
        }

        return $builder->getModel()->getDisabledAtColumn();
    }

    /**
     * Add the disable extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addDisable(Builder $builder)
    {
        $builder->macro('disable', function (Builder $builder) {
            return $builder->update([$builder->getModel()->getDisabledAtColumn() => $builder->getModel()->freshTimestamp()]);
        });
    }

    /**
     * Add the restore extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addEnable(Builder $builder)
    {
        $builder->macro('enable', function (Builder $builder) {
            $builder->withDisabled();

            return $builder->update([$builder->getModel()->getDisabledAtColumn() => null]);
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithDisabled(Builder $builder)
    {
        $builder->macro('withDisabled', function (Builder $builder, $WithDisabled = true) {
            if (! $WithDisabled) {
                return $builder->withoutDisabled();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutDisabled(Builder $builder)
    {
        $builder->macro('withoutDisabled', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $model->getQualifiedDisabledAtColumn()
            );

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyDisabled(Builder $builder)
    {
        $builder->macro('onlyDisabled', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedDisabledAtColumn()
            );

            return $builder;
        });
    }
}
