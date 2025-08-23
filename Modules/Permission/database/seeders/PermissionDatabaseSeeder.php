<?php

namespace Modules\Permission\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Cms\Traits\ContentTrait;
use Modules\Permission\database\seeders\RoleSeeder;
use Modules\Permission\trait\PermissionSeederTrait;
use Modules\Permission\Classes\PermissionSeederInitializer;
class PermissionDatabaseSeeder extends Seeder
{
    use ContentTrait, PermissionSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        DB::transaction(function () {
            $this->seedPermissions($this->getModelConfigurations());
            $this->seedBaseContentPermissions();
        });
    }

    /**
     * Get model configurations.
     */
    private function getModelConfigurations(): array
    {
        return config('permission.models', []);
    }

    public function seedPermissions(array $models)
    {
        foreach ($models as $model) {
            $config = PermissionSeederInitializer::initialize(
                $model['name'],
                $model['icon'],
                $model['additionalPermissions'] ?? [],
                $model['additionalExcludePermissionsFromAdmin'] ?? [],
                $model['withMainCrudAbility'] ?? true
            );

            $this->seedModelPermissions($config);
        }
    }

    private function seedBaseContentPermissions()
    {
        foreach (self::$typeList as $type => $content) {
            $config = PermissionSeederInitializer::initialize(
                $type,
                $content['icon'],
            );

            $this->seedModelPermissions($config);
        }
    }
}
