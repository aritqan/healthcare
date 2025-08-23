<?php

namespace Modules\Admin\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);
        $this->seedFakeAdmins();
    }

    private function seedFakeAdmins(): void
    {
        Admin::factory()
        ->count(10)
        ->create();
    }
}
