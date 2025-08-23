<?php

namespace Modules\Notification\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Notification\Enums\NotificationAddedBy;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationPriority;
use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Events\EmailSentFailedEvent;
use Modules\Notification\Events\FcmNotificationFailed;
use Modules\Notification\Http\Services\FirebaseService;
use Modules\Notification\Http\Services\SendGridService;
use Modules\Notification\Events\EmailSentSuccessfullyEvent;
use Modules\Notification\Http\Services\NotificationService;
use Modules\Notification\Listeners\EmailSentFailedListener;
use Modules\Notification\Events\FcmNotificationSentSuccessfully;
use Modules\Notification\Listeners\EmailSentSuccessfullyListener;
use Modules\Notification\Listeners\UpdateFcmNotificationStatusFailed;
use Modules\Notification\Listeners\UpdateFcmNotificationStatusSuccess;

class NotificationServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Notification';

    protected string $moduleNameLower = 'notification';

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
        $this->registerObserver();
        $this->registerVars();
        $this->registerEvents();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(FirebaseService::class, function ($app) {
            return new FirebaseService;
        });

        $this->app->bind(SendGridService::class, function ($app) {
            return new SendGridService;
        });

        $this->app->bind(NotificationService::class, function ($app) {
            return new NotificationService($app->make(FirebaseService::class));
        });
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
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
        view()->share('notificationChannels'    , NotificationChannels::getChannels());
        view()->share('notificationPriorities'  , NotificationPriority::getPriorities());
        view()->share('notificationStatuses'    , NotificationStatuses::getStatuses());
        view()->share('notificationAddedBy'     , NotificationAddedBy::getAddedBy());
    }

    private function registerObserver()
    {
        \Modules\Notification\Models\FirebaseToken::observe(\Modules\Notification\Observers\FirebaseTokenObserver::class);
    }

    private function registerEvents()
    {
        $events = [
            FcmNotificationSentSuccessfully::class => [
                UpdateFcmNotificationStatusSuccess::class,
            ],
            FcmNotificationFailed::class => [
                UpdateFcmNotificationStatusFailed::class,
            ],
            EmailSentSuccessfullyEvent::class => [
                EmailSentSuccessfullyListener::class,
            ],
            EmailSentFailedEvent::class => [
                EmailSentFailedListener::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ((array) $listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
