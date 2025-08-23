<?php

namespace Modules\Admin\Providers;

use Modules\Admin\Enums\AdminStatus;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Modules\Admin\Classes\AdminHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Modules\Admin\Events\RoleChangedEvent;
use Modules\Admin\Listeners\RoleChangedListener;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdminServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Admin';

    protected string $moduleNameLower = 'admin';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->registerVars();
        $this->registerPassword();
        $this->registerEvents();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton('adminHelper', function () {
            return new AdminHelper;
        });
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/frontend.php'), $this->moduleNameLower . '.frontend');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/svgs.php'), $this->moduleNameLower . '.svgs');
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace') . '\\' . $this->moduleName . '\\' . config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }

    private function registerVars()
    {
        view()->share('_ALL_LOCALE_'        , LaravelLocalization::getLocalesOrder());
        view()->share('_ALL_LOCALE_KEY_'    , LaravelLocalization::getSupportedLanguagesKeys());
        view()->share('_CUR_LOCALE_NAME_'   , LaravelLocalization::getCurrentLocaleNative());
        view()->share('_DIR_'               , LaravelLocalization::getCurrentLocaleDirection());
        view()->share('_LOCALE_'            , app()->getLocale());
        view()->share('_ASSETS_EXT_'        , LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl.' : '');
        view()->share('_STYLE_VER_'         , config('admin.style_version'));
        view()->share('adminStatuses'       , AdminStatus::getStatuses());
        view()->share('systemMainRoles'     , config('admin.main_roles'));
    }

    private function registerPassword()
    {
        Password::defaults(function () {
            return Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised();
        });
    }

    private function registerEvents()
    {
        $events = [
            RoleChangedEvent::class => [
                RoleChangedListener::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ((array) $listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
