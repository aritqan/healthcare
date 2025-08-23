<?php

namespace Modules\Base\Classes;

use InvalidArgumentException;

class BulkActionDropdown
{
    public $moduleRoutePrefix = 'admin';

    /**
     * @var array
     */
    public $actions = [];

    /**
     * @var string
     */
    public $modelName;

    /**
     * @var array
     */
    public $excludeActions = [];

    /**
     *  @var array
     */
    public $routeParameters = [];

    /**
     * To Set Model and Model Name
     *
     * @param string $modelName
     * @return $this
     */
    public function of(string $modelName) : self
    {
        $this->modelName            = $modelName;

        return $this;
    }

    /**
     * To Set Route Prefix
     *
     * @param string $moduleRoutePrefix
     * @return $this
     */
    public function routePrefix(string $moduleRoutePrefix) : self
    {
        $this->moduleRoutePrefix = $moduleRoutePrefix;

        return $this;
    }

    /**
     * To Set Route Parameters
     */
    public function setRouteParameters(array $parameters) : self
    {
        $this->routeParameters = $parameters;

        return $this;
    }


    /**
     * To Add Action
     *
     * @param string $action
     * @param string $label
     * @param string $icon
     * @param string $class
     * @return $this
     */
    public function addAction(string $action, string $label, string $icon, string $class = '') : self
    {
        $this->actions[] = [
            'action'    => $action,
            'label'     => $label,
            'icon'      => $icon,
            'class'     => $class,
            'route'     => route($this->moduleRoutePrefix . '.' . $action, $this->routeParameters),
            'method'    => $action == 'bulkSoftDelete' || $action == 'bulkHardDelete' ? 'DELETE' : 'POST'
        ];

        return $this;
    }

    /**
     * To Check User ability
     *
     * @param string $ability
     */
    public function can(string $ability) : bool
    {
        if (empty($ability)) {
            return false;
        }

        if (app('owner') || app('admin')->can(strtoupper($ability . '_' . $this->modelName))) {
            return true;
        }

        return false;
    }

    public function addSoftDelete()
    {
        if($this->can(SOFT_DELETE_ACTION))
            $this->addAction('bulkSoftDelete', trans('admin::cruds.'.SOFT_DELETE_ACTION.'.title'), 'fa fa-trash', 'text-danger');

        return $this;
    }

    public function addHardDelete()
    {
        if($this->can(HARD_DELETE_ACTION))
            $this->addAction('bulkHardDelete', trans('admin::cruds.'.HARD_DELETE_ACTION.'.title'), 'fa fa-trash', 'text-danger');

        return $this;
    }

    public function addRestore()
    {
        if($this->can(RESTORE_ACTION))
            $this->addAction('bulkRestore', trans('admin::cruds.'.RESTORE_ACTION.'.title'), 'fa fa-trash-restore', 'text-danger');

        return $this;
    }

    public function addDisable()
    {
        if($this->can(DISABLE_ACTION))
            $this->addAction('bulkDisable', trans('admin::cruds.'.DISABLE_ACTION.'.title'), 'fa fa-ban', 'text-danger');

        return $this;
    }

    public function addEnable()
    {
        if($this->can(ENABLE_ACTION))
            $this->addAction('bulkEnable', trans('admin::cruds.'.ENABLE_ACTION.'.title'), 'fa fa-check', 'text-danger');

        return $this;
    }

    /**
     * Execute actions based on a given array
     *
     * @param array $actions
     * @return array
     */
    public function executeActions(array $actions) : array
    {
        foreach ($actions as $action) {
            $method = 'add' . ucfirst($action);
            if (method_exists($this, $method)) {
                $this->{$method}();
            } else {
                throw new InvalidArgumentException("Action method [$method] does not exist.");
            }
        }

        return $this->actions;
    }
}
