<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Modules\Permission\Models\Role;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Schema\Blueprint;
use Modules\Permission\Models\Ability;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\Events\QueryExecuted;
use Modules\Auth\Models\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        BouncerFacade::useRoleModel(Role::class);
        BouncerFacade::useAbilityModel(Ability::class);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if(isDev()) {
            $this->registerQueryInfo();
            Model::preventSilentlyDiscardingAttributes();
        }

        $this->createDirectives();

        Blueprint::macro('disableable', function() {
            return $this->timestamp('disabled_at')->nullable();
        });

        Blueprint::macro('dropDisableable', function() {
            return $this->dropColumn('disabled_at');
        });

        $this->checkDebugBar();
    }

    private function createDirectives()
    {
        Blade::directive('notEmpty', function($expression) {
            return "<?php if(!empty($expression)): ?>";
        });

        Blade::directive('endNotEmpty', function() {
            return "<?php endif; ?>";
        });
    }

    private function registerQueryInfo()
    {
        //Lisiting For Query Events that takes longer than 500ms (0.5s)
        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $query) {
            Log::channel('query')->info('Query Sql is : ' . $query->sql);
            Log::channel('query')->info('Query Binding is : ' . implode(',', $query->bindings));
            Log::channel('query')->info('Query Time is : ' . $query->time . ' ms');
        });
    }

    private function checkDebugBar()
    {
        $allowDebugForCustomIp  = getSetting('allow_debug_for_custom_ip', false);
        $ipAddresses            = getSetting('custom_ips', '[]');
        $ipAddresses            = explode(',', $ipAddresses);
        $currentIp              = request()->ip();

        if ($allowDebugForCustomIp && in_array($currentIp, $ipAddresses)) {
            Debugbar::enable();
            config(['app.debug' => true]);
        }
    }
}
