<?php

namespace Modules\Base\Providers;

use Modules\Base\Enums\Gender;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Modules\Base\Console\TestCommand;
use Modules\Base\Enums\HttpStatusCode;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Console\ResetDatabase;
use Modules\Base\Classes\CustomDataTable;
use Modules\Base\Console\LogCleanCommand;
use Modules\Base\Classes\BulkActionDropdown;
use Modules\Base\Events\UpdateTranslationEvent;
use Modules\Base\Http\Responses\ResponseHelper;
use Modules\Base\Listeners\UpdateTranslationListener;

class BaseServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Base';

    protected string $moduleNameLower = 'base';

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
        $this->registerVars();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->registerEvents();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton('response', function () {
            return new ResponseHelper;
        });

        $this->app->singleton('customDataTable', function () {
            return new CustomDataTable;
        });

        $this->app->singleton('bulkActionDropdown', function () {
            return new BulkActionDropdown;
        });
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([
            LogCleanCommand::class,
            TestCommand::class,
            ResetDatabase::class,
        ]);
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
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

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
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.config('modules.paths.generator.component-class.path'));
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
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }

    private function registerVars()
    {
        view()->share('genderTypes'      , Gender::getGenders());
        view()->share('statusCodes'      , HttpStatusCode::all());
        view()->share('successCode'      , HttpStatusCode::OK);
        view()->share('validationCode'   , HttpStatusCode::UNPROCESSABLE_ENTITY);
    }

    private function registerEvents()
    {
        $events = [
            UpdateTranslationEvent::class => [
                UpdateTranslationListener::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ((array) $listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
