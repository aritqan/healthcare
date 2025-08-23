<?php

namespace Modules\Permission\Trait;

use Modules\Permission\Enums\SystemDefaultRoles;
use Silber\Bouncer\BouncerFacade;

trait PermissionSeederTrait
{
    /**
     * Assigning permissions to the root and the system administrator
     */
    public function seedAssignePermissionsForAdmin($abilities, $allExcludePermissionsFromAdmin)
    {
        foreach($abilities as $ability) {
            $isAbilityNotToBeAddedToSystemAdmin = in_array($ability->name, $allExcludePermissionsFromAdmin);

            if(! $isAbilityNotToBeAddedToSystemAdmin ) {
                BouncerFacade::allow(SystemDefaultRoles::SYSTEM_ADMIN_ROLE)->to($ability->name);
            }
        }
    }

    public function seedModelPermissions($config)
    {
        $permissionSeederService = $config['permissionSeederService'];
        $abilityGroup            = $permissionSeederService->createAbilityGroup($config['modelIcon']);
        $abilities               = $permissionSeederService->createAbilities($abilityGroup, $config['additionalPermissions'], $config['withMainCrudAbility']);

        $this->seedAssignePermissionsForAdmin($abilities, array_merge($config['excludeMainPermissions'], $config['additionalExcludePermissions']));
    }
}
