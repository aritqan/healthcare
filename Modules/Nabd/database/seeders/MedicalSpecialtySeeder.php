<?php

namespace Modules\Nabd\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Nabd\Models\MedicalSpecialty;
use Modules\Nabd\Models\MedicalSpecialtyTranslation;

class MedicalSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedMedicalSpecialties();
    }

    public function seedMedicalSpecialties()
    {
        $medicalSpecialties  = MedicalSpecialty::count();

        if ($medicalSpecialties > 0) return;

        $medicalSpecialties              = require module_path('Nabd', 'database/seeders/Templates/medical_specialties.php');
        $medicalSpecialtyTranslations    = require module_path('Nabd', 'database/seeders/Templates/medical_specialty_translations.php');

        foreach(collect($medicalSpecialties)->chunk(100) as $chunkedMedicalSpecialties) {
            MedicalSpecialty::insert($chunkedMedicalSpecialties->toArray());
        }
        foreach(collect($medicalSpecialtyTranslations)->chunk(100) as $chunkedMedicalSpecialtyTranslations) {
            MedicalSpecialtyTranslation::insert($chunkedMedicalSpecialtyTranslations->toArray());
        }
    }
}
