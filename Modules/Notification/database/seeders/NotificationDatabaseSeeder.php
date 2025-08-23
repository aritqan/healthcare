<?php

namespace Modules\Notification\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Models\NotificationTemplate;

class NotificationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedNotificationTemplateData();
        });
    }

    /**
     * Seed Notification Template Data
     */
    private function seedNotificationTemplateData(): void
    {
        $this->seedOrderStatusNotificationTemplateData();
    }

    private function seedOrderStatusNotificationTemplateData(): void
    {
        $welcomeTitle          = createTranslateArray('title', 'notifications.notification_templates.welcome_in_our_platform.title', 'notification');
        $welcomeDescription    = createTranslateArray('description', 'notifications.notification_templates.welcome_in_our_platform.description', 'notification');
        $welcomeShortTemplate  = createTranslateArray('short_template', 'notifications.notification_templates.welcome_in_our_platform.short_template', 'notification');
        $welcomeLongTemplate   = createTranslateArray('long_template', 'notifications.notification_templates.welcome_in_our_platform.long_template', 'notification');

        NotificationTemplate::updateOrCreate(
            [
                'name'  => 'welcome_in_our_platform',
            ],
            [
                'channels'  => ['fcm_mobile', 'fcm_web'],
                'variables' => ['username'],
            ] + array_merge_recursive($welcomeTitle, $welcomeDescription, $welcomeShortTemplate, $welcomeLongTemplate)
        );
    }
}
