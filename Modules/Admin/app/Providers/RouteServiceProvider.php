<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Admin\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();

        Route::pattern('model', '[0-9]+');
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware(['web', 'locale'])
            ->namespace($this->moduleNamespace)
            ->prefix(LaravelLocalization::setLocale() . '/admin')
            ->name('admin.')
            ->group(module_path('Admin', '/routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Admin', '/routes/api.php'));
    }
}
