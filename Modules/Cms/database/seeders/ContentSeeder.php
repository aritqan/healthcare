<?php

namespace Modules\Cms\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Cms\Models\Content;
use Modules\Cms\Enums\contents\BasePageSlugs;
use Modules\Cms\Enums\contents\BaseContentTypes;
use Modules\Cms\Models\ContentTranslation;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedMedicalSpecialties();
        $this->seedBasePageContent();
        $this->seedFakeContent();
    }

    private function seedBasePageContent()
    {
        Content::updateOrCreate(
            [
                'type'      => BaseContentTypes::PAGES,
                'sub_type'  => BasePageSlugs::PRIVACY_POLICY,
            ],
            [
                'can_be_deleted' => false,
            ] + createTranslateArray('title', 'contents.pages.privacy_policy', 'cms'),
        );

        Content::updateOrCreate(
            [
                'type'      => BaseContentTypes::PAGES,
                'sub_type'  => BasePageSlugs::TERMS_AND_CONDITIONS,
            ],
            [
                'can_be_deleted' => false,
            ] + createTranslateArray('title', 'contents.pages.terms_and_conditions', 'cms'),
        );

        Content::updateOrCreate(
            [
                'type'      => BaseContentTypes::PAGES,
                'sub_type'  => BasePageSlugs::ABOUT_US,
            ],
            [
                'can_be_deleted' => false,
            ] + createTranslateArray('title', 'contents.pages.about_us', 'cms'),
        );
    }

    private function seedFakeContent()
    {
        Content::factory()
        ->count(10)
        ->create();
    }

    private function seedMedicalSpecialties()
    {
        $mdecialSpecialties  = Content::byType(BaseContentTypes::MEDICAL_SPECIALTIES)->count();

        if($mdecialSpecialties > 0) return;

        $medicalSpecialties              = require module_path('Nabd', 'database/seeders/Templates/medical_specialties.php');
        $medicalSpecialtyTranslations    = require module_path('Nabd', 'database/seeders/Templates/medical_specialty_translations.php');

        foreach(collect($medicalSpecialties)->chunk(100) as $chunkedMedicalSpecialties) {
            Content::insert($chunkedMedicalSpecialties->toArray());
        }
        foreach(collect($medicalSpecialtyTranslations)->chunk(100) as $chunkedMedicalSpecialtyTranslations) {
            ContentTranslation::insert($chunkedMedicalSpecialtyTranslations->toArray());
        }
    }
}
