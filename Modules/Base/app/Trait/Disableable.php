<?php

namespace Modules\Base\Trait;

use Modules\Base\scope\DisablingScope;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withDisabled(bool $withDisabled = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyDisabled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withoutDisabled()
 */

trait Disableable
{
    /**
     * Boot the soft disabling trait for a model.
     *
     * @return void
     */
    public static function bootDisableable()
    {
        static::addGlobalScope(new DisablingScope);
    }

    /**
     * Initialize the disabling trait for an instance.
     *
     * @return void
     */
    public function initializeDisableable()
    {
        if (! isset($this->casts[$this->getDisabledAtColumn()])) {
            $this->casts[$this->getDisabledAtColumn()] = 'datetime';
        }
    }

    /**
     * Perform the disable this model instance.
     *
     * @return void
     */
    public function disable()
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('disabling') === false) {
            return false;
        }

        $time = $this->freshTimestamp();

        $this->{$this->getDisabledAtColumn()} = $this->fromDateTime($time);

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('disabled', false);

        return $result;
    }

    /**
     * Enale a disabled model instance.
     *
     * @return bool
     */
    public function enable()
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('enabling') === false) {
            return false;
        }

        $this->{$this->getDisabledAtColumn()} = null;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('enabled', false);

        return $result;
    }

    /**
     * Determine if the model instance has been disabled.
     *
     * @return bool
     */
    public function isDisabled()
    {
        return ! is_null($this->{$this->getDisabledAtColumn()});
    }

    /**
     * Determine if the model is currently enable.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return ! $this->isDisabled();
    }

    /**
     * Register a "disabling" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function disabling($callback)
    {
        static::registerModelEvent('disabling', $callback);
    }

    /**
     * Register a "disabled" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function disabled($callback)
    {
        static::registerModelEvent('disabled', $callback);
    }

    /**
     * Register a "enabling" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function enabling($callback)
    {
        static::registerModelEvent('enabling', $callback);
    }

    /**
     * Register a "enabled" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function enabled($callback)
    {
        static::registerModelEvent('enabled', $callback);
    }

    /**
     * Get the name of the "disabled at" column.
     *
     * @return string
     */
    public function getDisabledAtColumn()
    {
        return defined(static::class.'::DISABLED_AT') ? static::DISABLED_AT : 'disabled_at';
    }

    /**
     * Get the fully qualified "disabled at" column.
     *
     * @return string
     */
    public function getQualifiedDisabledAtColumn()
    {
        return $this->qualifyColumn($this->getDisabledAtColumn());
    }
}
