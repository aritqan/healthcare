<?php

namespace Modules\Log\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Log\Models\ApiLog as CrudModel;
use Modules\Base\Http\Services\BaseCrudService;

class ApiLogService extends BaseCrudService
{
    public static function log($serviceName, $method, $endpoint, $request, $response, $user = null)
    {
        if (!config('log.enable_api_logs')) {
            return null;
        }
        try {
            $user ??= app()->bound('user') ? app('user') : null;

            $model = DB::transaction(function()  use ($user, $serviceName, $method, $endpoint, $request, $response) {
                return CrudModel::create([
                    'user_type'     => $user ? get_class($user) : null,
                    'user_id'       => $user ? $user->id : null,
                    'service_name'  => $serviceName,
                    'method'        => $method,
                    'endpoint'      => $endpoint,
                    'request'       => json_encode($request),
                    'response'      => json_encode($response->json()),
                    'status'        => $response->successful() ? SUCCESS_STATUS : FAILED_STATUS,
                    'status_code'   => $response->status(),
                ]);

            });
        } catch (\Exception $e) {
            Log::error('Error while logging API request', [
                'error' => $e->getMessage(),
            ]);
        }

        return $model ?? null;
    }
}
