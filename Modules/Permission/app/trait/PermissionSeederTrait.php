<?php

namespace Modules\Permission\Trait;

use Illuminate\Support\Str;
use Silber\Bouncer\BouncerFacade;
use Modules\Permission\Enums\SystemDefaultRoles;

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

            // if ability ends with DOCTOR adn ability name its not HARD_DELETE then assign it to clinic role
            if (Str::endsWith($ability->name, SystemDefaultRoles::DOCTOR) && ! Str::startsWith($ability->name, strtoupper(HARD_DELETE_ACTION))) {
                BouncerFacade::allow(SystemDefaultRoles::CLINIC)->to($ability->name);
            }

            // if ability ends with PHARMACIST adn ability name its not HARD_DELETE then assign it to pharmacy role
            if (Str::endsWith($ability->name, SystemDefaultRoles::PHARMACIST) && ! Str::startsWith($ability->name, strtoupper(HARD_DELETE_ACTION))) {
                BouncerFacade::allow(SystemDefaultRoles::PHARMACY)->to($ability->name);
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
