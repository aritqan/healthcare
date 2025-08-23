<?php

namespace Modules\Permission\Classes;

use Modules\Permission\ModelServices\PermissionSeederService;

class PermissionSeederInitializer
{
    public static function initialize($modelName, $modelIcon, $additionalPermissions = [], $additionalExcludePermissions = [], $withMainCrudAbility = true)
    {
        $permissionSeederService = app()->makeWith(PermissionSeederService::class, ['modelName' => $modelName]);

        $excludeMainPermissions = [
            'HARD_DELETE_' . strtoupper($modelName),
        ]; 

        return [
            'permissionSeederService'       => $permissionSeederService,
            'modelName'                     => $modelName,
            'modelIcon'                     => $modelIcon,
            'additionalPermissions'         => $additionalPermissions,
            'additionalExcludePermissions'  => $additionalExcludePermissions,
            'excludeMainPermissions'        => $excludeMainPermissions,
            'withMainCrudAbility'           => $withMainCrudAbility,
        ];
    }
}
