<?php

namespace Modules\Config\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Config\Enums\SettingGroups;
use Modules\Config\Enums\SettingTypes;
use Modules\Config\Models\Setting;
class ConfigDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedBaseSettings();
        });
    }

    /**
     * Seed the application base settings.
     */
    private function seedBaseSettings(): void
    {
        $this->seedGeneralSettings();
        $this->seedSocailMediaSettings();
        $this->seedContactInfoSettings();
        $this->seedMediaSettings();
        $this->seedDevelopersSettings();
    }

    /**
     * Seed the general settings.
     */
    private function seedGeneralSettings(): void
    {
        Setting::updateOrCreate(
            [
                'group'         => SettingGroups::GENERAL,
                'key'           => 'app_name',
            ],
            [
                'type'          => SettingTypes::TEXT,
                'order'         => 1,
                'is_required'   => true,
                'translatable'  => true,
            ] + createTranslateArray('title', 'settings.groups.general.fields.app_name', 'config')
        );

        Setting::updateOrCreate(
            [
                'group'         => SettingGroups::GENERAL,
                'key'           => 'app_default_language',
            ],
            [
                'type'          => SettingTypes::SELECT,
                'order'         => 2,
                'is_required'   => true,
                'options'       => LaravelLocalization::getSupportedLocales(),
            ] + createTranslateArray('title', 'settings.groups.general.fields.app_default_language', 'config')
        );

        Setting::updateOrCreate(
            [
                'group'         => SettingGroups::GENERAL,
                'key'           => 'maintenance_mode',
            ],
            [
                'type'          => SettingTypes::SWITCH,
                'order'         => 4,
                'value'         => 0,
            ] + createTranslateArray('title', 'settings.groups.general.fields.maintenance_mode', 'config')
        );
    }

    /**
     * Seed the social media settings.
     */
    private function seedSocailMediaSettings(): void
    {
        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'facebook',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 1,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.facebook', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'twitter',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 2,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.twitter', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'instagram',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 3,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.instagram', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'linkedin',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 4,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.linkedin', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'youtube',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 5,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.youtube', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::SOCIAL_MEDIA,
                'key'   => 'tiktok',
            ],
            [
                'type'  => SettingTypes::URL,
                'order' => 6,
            ] + createTranslateArray('title', 'settings.groups.social_media.fields.tiktok', 'config')
        );
    }

    /**
     * Seed the contact info settings.
     */
    private function seedContactInfoSettings(): void
    {
        Setting::updateOrCreate(
            [
                'group' => SettingGroups::CONTACT_INFO,
                'key'   => 'phone',
            ],
            [
                'type'  => SettingTypes::PHONE,
                'order' => 1,
            ] + createTranslateArray('title', 'settings.groups.contact_info.fields.phone', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::CONTACT_INFO,
                'key'   => 'email',
            ],
            [
                'type'  => SettingTypes::TEXT,
                'order' => 2,
            ] + createTranslateArray('title', 'settings.groups.contact_info.fields.email', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::CONTACT_INFO,
                'key'   => 'address',
            ],
            [
                'type'  => SettingTypes::TEXTAREA,
                'order' => 3,
            ] + createTranslateArray('title', 'settings.groups.contact_info.fields.address', 'config')
        );
    }

    /**
     * Seed the Media settings.
     */
    private function seedMediaSettings(): void
    {
        Setting::updateOrCreate(
            [
                'group' => SettingGroups::MEDIA,
                'key'   => 'app_logo',
            ],
            [
                'type'  => SettingTypes::IMAGE,
                'order' => 1,
            ] + createTranslateArray('title', 'settings.groups.media.fields.app_logo', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::MEDIA,
                'key'   => 'app_mobile_logo',
            ],
            [
                'type'  => SettingTypes::IMAGE,
                'order' => 2,
            ] + createTranslateArray('title', 'settings.groups.media.fields.app_mobile_logo', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::MEDIA,
                'key'   => 'email_logo',
            ],
            [
                'type'  => SettingTypes::IMAGE,
                'order' => 3,
            ] + createTranslateArray('title', 'settings.groups.media.fields.email_logo', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::MEDIA,
                'key'   => 'app_favicon',
            ],
            [
                'type'  => SettingTypes::IMAGE,
                'order' => 4,
            ] + createTranslateArray('title', 'settings.groups.media.fields.app_favicon', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::MEDIA,
                'key'   => 'app_placeholder',
            ],
            [
                'type'  => SettingTypes::IMAGE,
                'order' => 5,
            ] + createTranslateArray('title', 'settings.groups.media.fields.app_placeholder', 'config')
        );
    }

    /**
     * Seed the developers settings.
     */
    private function seedDevelopersSettings(): void
    {
        $translatableSessionLifeTimeTitle          = createTranslateArray('title', 'settings.groups.developers.fields.session_lifetime.title', 'config');
        $translatableSessionLifeTimeDescription    = createTranslateArray('description', 'settings.groups.developers.fields.session_lifetime.description', 'config');

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'session_lifetime',
            ],
            [
                'type'  => SettingTypes::NUMBER,
                'order' => 1,
            ] + array_merge_recursive($translatableSessionLifeTimeTitle, $translatableSessionLifeTimeDescription)
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'allow_debug_for_custom_ip',
            ],
            [
                'type'  => SettingTypes::SWITCH,
                'order' => 2,
                'value' => 0,
            ] + createTranslateArray('title', 'settings.groups.developers.fields.allow_debug_for_custom_ip', 'config')
        );

        $translatableCustomIpTitle          = createTranslateArray('title', 'settings.groups.developers.fields.custom_ips.title', 'config');
        $translatableCustomIpDescription    = createTranslateArray('description', 'settings.groups.developers.fields.custom_ips.description', 'config');

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'custom_ips',
            ],
            [
                'type'  => SettingTypes::TEXTAREA,
                'order' => 3,
            ] + array_merge_recursive($translatableCustomIpTitle, $translatableCustomIpDescription)
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'clear_cache',
            ],
            [
                'type'  => SettingTypes::BUTTON,
                'order' => 4,
                'value' => 'base.clearCache'
            ] + createTranslateArray('title', 'settings.groups.developers.fields.clear_cache', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'clear_logs',
            ],
            [
                'type'  => SettingTypes::BUTTON,
                'order' => 5,
                'value' => 'base.clearLogs'
            ] + createTranslateArray('title', 'settings.groups.developers.fields.clear_logs', 'config')
        );

        Setting::updateOrCreate(
            [
                'group' => SettingGroups::DEVELOPERS,
                'key'   => 'reset_permissions',
            ],
            [
                'type'  => SettingTypes::BUTTON,
                'order' => 6,
                'value' => 'base.resetPermissions'
            ] + createTranslateArray('title', 'settings.groups.developers.fields.reset_permissions', 'config')
        );
    }
}
