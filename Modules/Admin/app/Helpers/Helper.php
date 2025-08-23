<?php

use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

if (!function_exists('dashboardSetItem')) {
    /**
     * @param string $key
     * @param string $label
     * @param string $modelClass
     * @param string|null $fromDate
     * @param string|null $toDate
     * @param Closure $customQuery
     * @param bool $hideIfEmpty
     * @param string|null $icon
     * @param string|null $route
     * @param Closure|null $customQuery
     * @return ?array
     */
    function dashboardSetItem(
        string $key,
        string $label,
        string $modelClass,
        ?string $fromDate       = null,
        ?string $toDate         = null,
        ?Closure $customQuery   = null,
        bool $hideIfEmpty       = false,
        ?string $icon           = null,
        ?string $route          = null
    ): ?array {
        $query = $modelClass::query();

        if ($fromDate && $toDate) $query->whereBetween('created_at', [$fromDate, $toDate]);

        if ($customQuery) $query = $customQuery($query);

        $count = $query->count();

        if ($hideIfEmpty && $count === 0) return null;

        return [
            'key'   => $key,
            'label' => $label,
            'icon'  => $icon ?? getDashboardIcon($key),
            'route' => $route ?? getDashboardRoute($modelClass, $key),
            'count' => $count,
        ];
    }
}

if (!function_exists('getDashboardIcon')) {
    /**
     * @param string $key
     * @return string
     */
    function getDashboardIcon(string $key): string
    {
        return config('permission.models.' . Str::singular($key) . '.icon') ?? 'fas fa-circle-info';
    }
}

if (!function_exists('getDashboardRoute')) {
    /**
     * @param string $modelClass
     * @param string $key
     */
    function getDashboardRoute(string $modelClass, string $key): string
    {
        $module = getModuleNameFromModel($modelClass);

        return $module ? route($module . '.' . Str::plural($key) . '.index') : '#';
    }
}

if (!function_exists('getModuleNameFromModel')) {
    /**
     * @param string $modelClass
     * @return ?string
     */
    function getModuleNameFromModel(string $modelClass): ?string
    {
        try {
            $reflection = new ReflectionClass($modelClass);
            $modelPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $reflection->getFileName());

            foreach (Module::all() as $module) {
                $modulePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $module->getPath());
                if (str_contains($modelPath, $modulePath)) {
                    return $module->getLowerName(); // returns in lowercase: "cms", "blog"
                }
            }
        } catch (\ReflectionException $e) {
            return null;
        }

        return null;
    }
}
