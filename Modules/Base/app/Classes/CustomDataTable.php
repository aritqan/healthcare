<?php

namespace Modules\Base\Classes;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Trait\Disableable;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomDataTable
{
    public $moduleRoutePrefix = 'admin';

    /**
     * @var Model
     */
    public $model;

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
     * @var array
     */
    public $actions = [];

    /**
     * To Get Route Prefix
     *
     * @return string
     */
    public function getRoutePrefix() : string
    {
        return $this->moduleRoutePrefix;
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
     *
     * @param array $parameters
     * @return $this
     */
    public function setRouteParameters(array $parameters) : self
    {
        $this->routeParameters = $parameters;

        return $this;
    }

    /**
     * To Set Model and Model Name
     *
     * @param Model $model
     * @param string $modelName
     * @return $this
     */
    public function of(Model $model, string $modelName) : self
    {
        $this->model        = $model;
        $this->modelName    = $modelName;

        return $this;
    }

    /**
     * To Add Action
     *
     * @param string $action
     * @param string $icon
     * @param int $order
     * @param string $label
     * @param string $type
     * @param string $color
     * @param bool $withConfirm
     * @param string $route
     * @return array
     */
    public function addAction(string $action, string $icon, int $order, mixed $modelId = null, string|null $label = null, string $type = 'button', string $color = 'primary', bool $withConfirm = false, string|null $route = null) : array
    {
        $routeAction = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $action))));

        $this->actions[$action] = [
            'action'            => $action,
            'type'              => $type,
            'label'             => $label ?? trans('admin::cruds.' . $action . '.title'),
            'icon'              => $icon,
            'color'             => $color,
            'withConfirm'       => $withConfirm,
            'model'             => $modelId ?? $this->model->id,
            'order'             => $order,
            'route'             => $route ?? route($this->moduleRoutePrefix . '.' . $routeAction, $this->routeParameters),
        ];

        return $this->actions[$action];
    }

    /**
     * To Exclude Actions
     *
     * @param array $actions
     * @return $this
     */
    public function excludeActions(array $actions) : self
    {
        $this->excludeActions = $actions;

        return $this;
    }

    /**
     * To Check User ability
     *
     * @param string $ability
     * @return bool
     */
    public function can(string $ability) : bool
    {
        if (in_array($ability, $this->excludeActions) || empty($ability)) {
            return false;
        }

        if (app('owner') || app('admin')->can(strtoupper($ability . '_' . $this->modelName))) {
            return true;
        }

        return false;
    }

    /**
     * Add Read Action
     *
     * @return $this
     */
    public function addView()
    {
        if ($this->can(VIEW_ACTION))
            $this->addAction(VIEW_ACTION, 'bi-eye', 1);

        return $this;
    }

    /**
     * Add Update Action
     *
     * @return $this
     */
    public function addUpdate()
    {
        if ($this->can(UPDATE_ACTION))
            $this->addAction(UPDATE_ACTION, 'bi-pencil-square', 2);

        return $this;
    }

    /**
     * Add Show Log Action
     *
     * @return $this
     */
    public function addShowLog()
    {
        if ($this->can(SHOW_LOG_ACTION) && in_array(Auditable::class, class_uses($this->model)) &&  $this->model->audits()->exists())
            $this->addAction(SHOW_LOG_ACTION, 'fa-solid fa-clock-rotate-left', 3, route: route('log.activity_log.index', array_merge($this->routeParameters, ['type'  => strtolower(str_replace('_', '-', $this->modelName))])));

        return $this;
    }

    /**
     * Add Disable Action
     *
     * @return $this
     */
    public function addDisable()
    {
        if ($this->can(DISABLE_ACTION) && in_array(Disableable::class, class_uses($this->model)) && !is_null($this->model) && $this->model->isEnabled())
            $this->addAction(DISABLE_ACTION, 'bi-ban', 4, withConfirm: true);

        return $this;
    }

    /**
     * Add Enable Action
     *
     * @return $this
     */
    public function addEnable()
    {
        if ($this->can(ENABLE_ACTION) && in_array(Disableable::class, class_uses($this->model)) && !is_null($this->model) && $this->model->isDisabled())
            $this->addAction(ENABLE_ACTION, 'bi-check-lg', 5, withConfirm: true);

        return $this;
    }

    /**
     * Add Soft Delete Action
     *
     * @return $this
     */
    public function addSoftDelete()
    {
        if ($this->can(SOFT_DELETE_ACTION) && in_array(SoftDeletes::class, class_uses($this->model)) && !is_null($this->model) && !$this->model->trashed())
            $this->addAction(SOFT_DELETE_ACTION, 'bi-trash-fill', 6, withConfirm: true);

        return $this;
    }

    /**
     * Add Restore Action
     *
     * @return $this
     */
    public function addRestore()
    {
        if ($this->can(RESTORE_ACTION) && in_array(SoftDeletes::class, class_uses($this->model)) && !is_null($this->model) && $this->model->trashed())
            $this->addAction(RESTORE_ACTION, 'bi-arrow-clockwise', 7, withConfirm: true);

        return $this;
    }

    /**
     * Add Hard Delete Action
     *
     * @return $this
     */
    public function addHardDelete()
    {
        if ($this->can(HARD_DELETE_ACTION))
            $this->addAction(HARD_DELETE_ACTION, 'bi-trash-fill', 8, withConfirm: true);

        return $this;
    }

    /**
     * View As Modal
     *
     * @param string $route
     * @param mixed $modelId
     * @param string $modalTargetID
     * @param string $modalTitle
     * @param string $action
     * @param string $icon
     * @param int $order
     * @return array
     */
    public function viewAsModal(string $route, mixed $modelId, string $modalTargetID, string $modalTitle, string $action = 'view', string $icon = 'bi-eye', int $order = 1) : array
    {
        if($this->can(VIEW_ACTION))
            return array_merge($this->addAction($action, $icon, $order, $modelId, type: 'modal', route: $route), [
                'modal_title'   => $modalTitle,
                'target_id'     => $modalTargetID,
            ]);

        return  [];
    }

    /**
     * To Get Main Crud Actions
     *
     * @return array
     */
    public function modelMainCrudActions() : array
    {
        $this->routeParameters = array_merge($this->routeParameters, ['model' => $this->model->id]);

        $this->addView();
        $this->addUpdate();
        $this->addShowLog();
        $this->addDisable();
        $this->addEnable();
        $this->addSoftDelete();
        $this->addRestore();
        $this->addHardDelete();

        if(isset($this->actions[UPDATE_ACTION]) || isset($this->actions[VIEW_ACTION]) || isset($this->actions[SHOW_LOG_ACTION])) {
            $this->actions['divider'] = [
                'action' => 'divider',
                'type'   => 'divider',
                'order'  => 3
            ];
        }

        return $this->actions;
    }

    /**
     * To Get Datatable Actions
     *
     * @param array $additionalActions
     * @param bool $withMainCrudActions
     * @return array
     */
    public function getDatatableActions(array $additionalActions = [], bool $withMainCrudActions = true) : array
    {
        $this->actions = []; // Reset Actions array

        $allActions = $withMainCrudActions ? array_merge($this->modelMainCrudActions(), $additionalActions) : $additionalActions;

        if (empty($allActions)) {
            return [];
        }

        return [
            'type'  => 'dropdown',
            'icon'  => 'bi bi-gear',
            'color' => 'primary',
            'items' => $allActions,
        ];
    }
}
