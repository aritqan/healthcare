<?php

use Illuminate\Support\Str;
use Modules\Admin\Models\Admin;
use Nwidart\Modules\Facades\Module;
use Illuminate\Auth\Middleware\Authenticate;
use Modules\Permission\Enums\SystemDefaultRoles;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Admin\Enums\permissions\ClinicPermissions;
use Modules\Admin\Enums\permissions\DoctorPermissions;
use Modules\Admin\Enums\permissions\PharmacyPermissions;

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
        ?string $route          = null,
        array $routeParameters  = []
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
            'route' => $route ?? getDashboardRoute($modelClass, $key, $routeParameters),
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
    function getDashboardRoute(string $modelClass, string $key, array $routeParameters = []): string
    {
        $module = getModuleNameFromModel($modelClass);

        return $module ? route($module . '.' . Str::plural($key) . '.index', $routeParameters) : '#';
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

if(! function_exists('adminPermissionClassMapping')) {
    /**
     * @param string $roleName
     * @return mixed
     */
    function adminPermissionClassMapping(string $roleName): mixed
    {
        switch (strtoupper($roleName)) {
            case SystemDefaultRoles::SYSTEM_ADMIN_ROLE:
                return AdminPermissions::class;
            case SystemDefaultRoles::CLINIC:
                return ClinicPermissions::class;
            case SystemDefaultRoles::PHARMACY:
                return PharmacyPermissions::class;
            case SystemDefaultRoles::DOCTOR:
                return DoctorPermissions::class;
            case SystemDefaultRoles::PHARMACIST:
                return PharmacyPermissions::class;
            default:
                return null;
        }
    }
}

if(! function_exists('checkIfRoleStateRequired')) {
    /**
     * @param string $roleName
     * @return bool
     */
    function checkIfRoleStateRequired(string $roleName): bool
    {
        return in_array(strtoupper($roleName), [SystemDefaultRoles::CLINIC, SystemDefaultRoles::PHARMACY]);
    }
}

if(! function_exists('checkIfRoleGenderRequired')) {
    /**
     * @param string $roleName
     * @return bool
     */
    function checkIfRoleGenderRequired(string $roleName): bool
    {
        return in_array(strtoupper($roleName), [SystemDefaultRoles::DOCTOR, SystemDefaultRoles::PHARMACIST]);
    }
}

if(! function_exists('checkIfRoleMedicalFacilityRequired')) {
    /**
     * @param string $roleName
     * @return bool
     */
    function checkIfRoleMedicalFacilityRequired(string $roleName): bool
    {
        return in_array(strtoupper($roleName), [SystemDefaultRoles::DOCTOR, SystemDefaultRoles::PHARMACIST]);
    }
}

if(! function_exists('checkIfRoleCanSelectMedicalFacility')) {
    /**
     * @param string $roleName
     * @return bool
     */
    function checkIfRoleCanSelectMedicalFacility(Authenticate|Admin $user): bool
    {
        return $user->isA(SystemDefaultRoles::SYSTEM_ADMIN_ROLE) || $user->isA(SystemDefaultRoles::ROOT_ROLE);
    }
}
