<?php

namespace Modules\Cms\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Cms\Models\Content;
use Modules\Cms\Enums\contents\BasePageSlugs;
use Modules\Cms\Enums\contents\BaseContentTypes;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
}
