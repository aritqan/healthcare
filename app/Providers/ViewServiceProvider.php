<?php

namespace App\Providers;

use Modules\Cms\Models\Content;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Enums\permissions\UserPermissions;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Cms\Enums\permissions\ContentCategoryPermissions;
use Modules\Cms\Enums\permissions\ContentTagPermissions;
use Modules\Config\Enums\permissions\SettingPermissions;
use Modules\Permission\Enums\permissions\RolePermissions;
use Modules\Permission\Enums\permissions\AbilityPermissions;
use Modules\Zms\Enums\permissions\CountryPermissions;
use Modules\Crm\Enums\permissions\ContactusPermissions;
use Modules\Crm\Enums\permissions\SubscribePermissions;
use Modules\Log\Enums\permissions\ApiLogPermissions;
use Modules\Notification\Enums\permissions\NotificationPermissions;
use Modules\Notification\Enums\permissions\NotificationTemplatePermissions;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('admin::includes.aside_menu.menu', function ($view) {
            $this->registerDashboardAsideMenu();
            $this->registerAdminAsideMenu();
            $this->registerPermissionAsideMenu();
            $this->regiserCmsAsideMenu();
            $this->registerZmsAsideMenu();
            $this->registerConfigAsideMenu();
            $this->registerNotificationAsideMenu();
            $this->registerCrmAsideMenu();
            $this->registerLogAsideMenu();
        });
    }

    private function registerDashboardAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'        => 'dashboard',
            'type'      => 'item',
            'link'      => route('admin.dashboard.index'),
            'title'     => trans('admin::dashboard.aside_menu.dashboard'),
            'icon'      => 'fa-solid fa-chart-pie',
            'order'     => -99999,
        ]);
    }

    private function registerAdminAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'user_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.user_management.title'),
            'order' => 1,
        ]);

        // Start Admin Section
        app('adminHelper')->asideMenu([
            'id'        => 'admins_section',
            'parent_id' => 'user_management',
            'type'      => 'item',
            'icon'      => 'fa-solid fa-user-lock',
            'title'     => trans('admin::dashboard.aside_menu.user_management.admins'),
            'order'     => 7,
        ]);

        if (app('owner') || app('admin')->can(AdminPermissions::READ)) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_admins',
                'parent_id' => 'admins_section',
                'type'      => 'item',
                'link'      => route('admin.admins.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(AdminPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_admins',
                'parent_id' => 'admins_section',
                'type'      => 'item',
                'link'      => route('admin.admins.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 4,
            ]);
        }
        // End Admin Section

        // Start User Section
        app('adminHelper')->asideMenu([
            'id'        => 'users_section',
            'parent_id' => 'user_management',
            'type'      => 'item',
            'icon'      => 'fas fa-users',
            'title'     => trans('admin::dashboard.aside_menu.user_management.users'),
            'order'     => 8,
        ]);

        if (app('owner') || app('admin')->can(UserPermissions::READ)) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_users',
                'parent_id' => 'users_section',
                'type'      => 'item',
                'link'      => route('auth.users.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(AdminPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_users',
                'parent_id' => 'users_section',
                'type'      => 'item',
                'link'      => route('auth.users.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 4,
            ]);
        }
        // End User Section
    }

    private function registerZmsAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'zone_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.zone_management.title'),
            'order' => 10,
        ]);

        // Start Role Section
        app('adminHelper')->asideMenu([
            'id'        => 'countries_section',
            'parent_id' => 'zone_management',
            'type'      => 'item',
            'icon'      => 'bi bi-globe-americas',
            'title'     => trans('admin::dashboard.aside_menu.country_management.countries'),
            'order'     => 3,
        ]);

        if (app('owner') || app('admin')->can(CountryPermissions::READ)) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_countries',
                'parent_id' => 'countries_section',
                'type'      => 'item',
                'link'      => route('zms.countries.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }
    }

    private function registerPermissionAsideMenu()
    {
        // Start Role Section
        app('adminHelper')->asideMenu([
            'id'        => 'roles_section',
            'parent_id' => 'user_management',
            'type'      => 'item',
            'icon'      => 'fas fa-users-cog',
            'title'     => trans('admin::dashboard.aside_menu.user_management.roles'),
            'order'     => 3,
        ]);

        if (app('owner') || app('admin')->can(RolePermissions::READ)) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_roles',
                'parent_id' => 'roles_section',
                'type'      => 'item',
                'link'      => route('permission.roles.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(RolePermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_roles',
                'parent_id' => 'roles_section',
                'type'      => 'item',
                'link'      => route('permission.roles.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 4,
            ]);
        }
        // End Role Section

        // Start Permission Section
        app('adminHelper')->asideMenu([
            'id'        => 'permissions_section',
            'parent_id' => 'user_management',
            'type'      => 'item',
            'icon'      => 'fa-solid fa-shield',
            'title'     => trans('admin::dashboard.aside_menu.user_management.permissions'),
            'order'     => 4,
        ]);

        if (app('owner') || app('admin')->can(AbilityPermissions::READ)) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_permissions',
                'parent_id' => 'permissions_section',
                'type'      => 'item',
                'link'      => route('permission.permissions.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(AbilityPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'CREATE_ACTIONs',
                'parent_id' => 'permissions_section',
                'type'      => 'item',
                'link'      => route('permission.permissions.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 4,
            ]);
        }
    }

    private function regiserCmsAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'content_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.content_management.title'),
            'order' => 10,
        ]);

        // Start Content Category Section
        app('adminHelper')->asideMenu([
            'id'        => 'content_category_section',
            'parent_id' => 'content_management',
            'type'      => 'item',
            'icon'      => 'bi bi-layers-fill',
            'title'     => trans('admin::dashboard.aside_menu.content_category_management.content_categories'),
            'order'     => 1,
        ]);

        if (app('owner') || (app('admin')->can(ContentCategoryPermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_content_category',
                'parent_id' => 'content_category_section',
                'type'      => 'item',
                'link'      => route('cms.content_categories.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(ContentCategoryPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_content_category',
                'parent_id' => 'content_category_section',
                'type'      => 'item',
                'link'      => route('cms.content_categories.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 5,
            ]);
        }
        // End Content Category Section

        // Start Content Tag Section
        app('adminHelper')->asideMenu([
            'id'        => 'content_tag_section',
            'parent_id' => 'content_management',
            'type'      => 'item',
            'icon'      => 'fa-solid fa-tag',
            'title'     => trans('admin::dashboard.aside_menu.content_tag_management.content_tags'),
            'order'     => 20,
        ]);

        if (app('owner') || (app('admin')->can(ContentTagPermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_content_tag',
                'parent_id' => 'content_tag_section',
                'type'      => 'item',
                'link'      => route('cms.content_tags.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(ContentTagPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_content_tag',
                'parent_id' => 'content_tag_section',
                'type'      => 'item',
                'link'      => route('cms.content_tags.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 5,
            ]);
        }
        // End Content Tag Section

        // Start Content Section [Dynamic]
        foreach (Content::types() as $type => $typeData) {
            if(! Content::isVisibleInMenu($type)) continue;

            app('adminHelper')->asideMenu([
                'id'        => 'content_' . $type,
                'parent_id' => 'content_management',
                'type'      => 'item',
                'icon'      => $typeData['icon'],
                'title'     => Content::getTypeTitle($type),
                'order'     => 10,
            ]);

            if (app('owner') || app('admin')->can(strtoupper(CRUD_TYPES['read']) . '_' . $type)) {
                app('adminHelper')->asideMenu([
                    'id'        => 'view_content_' . $type,
                    'parent_id' => 'content_' . $type,
                    'type'      => 'item',
                    'link'      => route('cms.contents.index', ['type' => $type]),
                    'title'     => trans('admin::base.view_all'),
                    'order'     => 4,
                ]);
            }

            if (app('owner') || app('admin')->can(strtoupper(CRUD_TYPES['create']) . '_' . $type)) {
                app('adminHelper')->asideMenu([
                    'id'        => 'create_content_' . $type,
                    'parent_id' => 'content_' . $type,
                    'type'      => 'item',
                    'link'      => route('cms.contents.create', ['type' => $type]),
                    'title'     => trans('admin::base.create_new'),
                    'order'     => 4,
                ]);
            }
        }
        // End Content Section [Dynamic]
    }

    private function registerNotificationAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'notification_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.notification_management.title'),
            'order' => 10,
        ]);

        // Start Notification Template Section
        app('adminHelper')->asideMenu([
            'id'        => 'notification_template_section',
            'parent_id' => 'notification_management',
            'type'      => 'item',
            'icon'      => 'fas fa-envelope',
            'title'     => trans('admin::dashboard.aside_menu.notification_management.notification_templates'),
            'order'     => 3,
        ]);

        if (app('owner') || (app('admin')->can(NotificationTemplatePermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_notification_template',
                'parent_id' => 'notification_template_section',
                'type'      => 'item',
                'link'      => route('notification.notification_templates.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(NotificationTemplatePermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_notification_template',
                'parent_id' => 'notification_template_section',
                'type'      => 'item',
                'link'      => route('notification.notification_templates.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 5,
            ]);
        }
        // End Notification Template Section

        // Start Notification Section
        app('adminHelper')->asideMenu([
            'id'        => 'notification_section',
            'parent_id' => 'notification_management',
            'type'      => 'item',
            'icon'      => 'fas fa-bell',
            'title'     => trans('admin::dashboard.aside_menu.notification_management.notifications'),
            'order'     => 4,
        ]);

        if (app('owner') || (app('admin')->can(NotificationPermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_notifications',
                'parent_id' => 'notification_section',
                'type'      => 'item',
                'link'      => route('notification.notifications.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(NotificationPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_notification',
                'parent_id' => 'notification_section',
                'type'      => 'item',
                'link'      => route('notification.notifications.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 5,
            ]);
        }
        // End Notification Section
    }

    private function registerCrmAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'crm_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.crm_management.title'),
            'order' => 15,
        ]);

        // Start Contact us Section
        app('adminHelper')->asideMenu([
            'id'        => 'contactus_section',
            'parent_id' => 'crm_management',
            'type'      => 'item',
            'icon'      => 'fa-solid fa-paper-plane',
            'title'     => trans('admin::dashboard.aside_menu.crm_management.contactuses'),
            'order'     => 3,
        ]);

        if (app('owner') || (app('admin')->can(ContactusPermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_contactus',
                'parent_id' => 'contactus_section',
                'type'      => 'item',
                'link'      => route('crm.contactuses.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 1,
            ]);
        }
        // End Contact us Section

        // Start Subscribe Section
        app('adminHelper')->asideMenu([
            'id'        => 'subscribe_section',
            'parent_id' => 'crm_management',
            'type'      => 'item',
            'icon'      => 'fa-regular fa-newspaper',
            'title'     => trans('admin::dashboard.aside_menu.crm_management.subscribes'),
            'order'     => 4,
        ]);

        if (app('owner') || (app('admin')->can(SubscribePermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_subscribe',
                'parent_id' => 'subscribe_section',
                'type'      => 'item',
                'link'      => route('crm.subscribes.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 1,
            ]);
        }
        // End Subscribe Section
    }

    private function registerConfigAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'setting_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.setting_management.title'),
            'order' => 2000,
        ]);

        // Start Setting Section
        app('adminHelper')->asideMenu([
            'id'        => 'settingss_section',
            'parent_id' => 'setting_management',
            'type'      => 'item',
            'icon'      => 'fas fa-cog',
            'title'     => trans('admin::dashboard.aside_menu.setting_management.settings'),
            'order'     => 3,
        ]);

        if (app('owner') || (app('admin')->can(SettingPermissions::READ) && app('admin')->can(SettingPermissions::UPDATE))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_settings',
                'parent_id' => 'settingss_section',
                'type'      => 'item',
                'link'      => route('config.settings.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 4,
            ]);
        }

        if (app('owner') || app('admin')->can(SettingPermissions::CREATE)) {
            app('adminHelper')->asideMenu([
                'id'        => 'create_settings',
                'parent_id' => 'settingss_section',
                'type'      => 'item',
                'link'      => route('config.settings.create'),
                'title'     => trans('admin::base.create_new'),
                'order'     => 5,
            ]);
        }

    }

    private function registerLogAsideMenu()
    {
        app('adminHelper')->asideMenu([
            'id'    => 'log_management',
            'type'  => 'header',
            'title' => trans('admin::dashboard.aside_menu.log_management.title'),
            'order' => 1500,
        ]);

        // Start Api Log Section
        app('adminHelper')->asideMenu([
            'id'        => 'api_logs_section',
            'parent_id' => 'log_management',
            'type'      => 'item',
            'icon'      => 'fa-solid fa-clock-rotate-left',
            'title'     => trans('admin::dashboard.aside_menu.log_management.api_logs'),
            'order'     => 4,
        ]);

        if (app('owner') || (app('admin')->can(ApiLogPermissions::READ))) {
            app('adminHelper')->asideMenu([
                'id'        => 'view_api_logs',
                'parent_id' => 'api_logs_section',
                'type'      => 'item',
                'link'      => route('log.api_logs.index'),
                'title'     => trans('admin::base.view_all'),
                'order'     => 1,
            ]);
        }
        // End Api Log Section
    }
}
