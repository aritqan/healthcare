<?php

namespace Modules\Crm\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Crm\Models\Contactus;

class CrmDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedFakeContactuses();
    }

    private function seedFakeContactuses(): void
    {
        Contactus::factory()
            ->count(1000)
            ->create();
    }
}
