<?php

namespace Modules\Permission\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Role;
use Modules\Permission\Enums\SystemDefaultRoles;

class RoleSeeder extends Seeder
{
    public $data = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedRoles();
    }

    /**
     * Create roles with translations
     *
     * @param  string $roleName
     * @return \Modules\Permission\Models\Role
     */
    public function createRoles(string $roleName)
    {
        $roleKey        = strtolower($roleName);
        $roleCapital    = strtoupper($roleName);

        $title          = createTranslateArray('title', 'seeder.roles.' . $roleKey . '.title');
        $desc           = createTranslateArray('description', 'seeder.roles.' . $roleKey . '.description');
        $data           = array_merge_recursive($title, $desc);

        $this->data[$roleCapital . '_ROLE'] = Role::withoutGlobalScope('withoutRoot')->firstOrCreate(
            [
                'name'  => trans('permission::seeder.roles.'.$roleKey.'.name', [], 'en'),
            ],
            $data
        );

        return $this->data[$roleCapital . '_ROLE'];
    }

    /**
     * Create system roles
     *
     * @return void
     */
    public function seedRoles()
    {
        foreach(SystemDefaultRoles::all() as $role) {
            $this->createRoles($role);
        }
    }
}
