<?php

namespace Modules\Nabd\Database\Seeders;

use Illuminate\Database\Seeder;

class NabdDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            MedicalSpecialtySeeder::class,
        ]);
    }
}
