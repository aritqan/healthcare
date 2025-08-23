<?php

namespace Modules\Permission\ModelServices;

use Illuminate\Support\Str;
use Modules\Permission\Models\AbilityGroup;

class PermissionSeederService
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $modelName;

    /**
     * @param  string  $model
     *
     * @return void
     */
    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Create Ability Group Wtih Transaltions
     *
     * @param  string  $icon
     *
     * @return \Modules\Permission\Models\AbilityGroup
     *
    */
    public function createAbilityGroup(string $icon = 'bi-stack')
    {
        $abilityGroupData           = createTranslateArray('title', 'seeder.ability_group.' . strtolower($this->modelName) . '_management');
        $abilityGroupData['icon']   = $icon;

        $this->data[$this->modelName . '_ABILITY_GROUP'] = AbilityGroup::updateOrCreate(
            [
                'code' => strtoupper($this->modelName)
            ],
            $abilityGroupData
        );

        return $this->data[$this->modelName . '_ABILITY_GROUP'];
    }

    /**
     * Create abilities
     *
     * @param  Modules\Permission\Models\AbilityGroup  $abilityGroup
     * @param  array  $abilities
     * @param  bool   $withMainCrudAbility
     *
    * @return \Modules\Permission\Models\Ability
     */
    public function createAbilities(AbilityGroup $abilityGroup, array $abilities = [], bool $withMainCrudAbility = true)
    {
        $abilityData  = [];
        $allAbilities = $withMainCrudAbility ? array_merge(CRUD_TYPES, $abilities) : $abilities;

        foreach($allAbilities ?? [] as $code) {
            $modelName      = strtoupper($this->modelName);
            $permissionName = Str::endsWith($code, '_' . $modelName) ? substr($code, 0, -strlen('_' . $modelName)) : $code;
            $abilityData    = createTranslateArray(field: 'title', key: 'cruds.' . strtolower($permissionName) . '.title', module: 'admin');
            $code           = strtoupper($code);
            $fullCodeName   = Str::endsWith($code, '_' . $modelName) ? $code : $code . '_' . $modelName;

            $this->data[$this->modelName . '_ABILITY'][$fullCodeName] = $abilityGroup->abilities()->updateOrCreate(
                [
                    'name'  => $fullCodeName
                ],
                $abilityData
            );
        }

        return $this->data[$this->modelName . '_ABILITY'];
    }
}
