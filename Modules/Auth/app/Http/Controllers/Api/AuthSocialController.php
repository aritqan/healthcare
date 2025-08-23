<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Http\Services\AuthService;
use Modules\Base\Http\Controllers\BaseApiController;

class AuthSocialController extends BaseApiController
{

    public function __construct(protected AuthService $authService)
    {
        parent::__construct();
    }


    /**
     * redirectToProvider
     *
     * @param  mixed $provider
     * @return void
     */
    public function redirectToProvider($provider)
    {
        $redirectUrl = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return sendApiSuccessResponse(data: ['redirect_url' => $redirectUrl]);
    }


    /**
     * handlProviderCallback
     *
     * @param  mixed $request
     * @param  mixed $provider
     * @return void
     */
    public function handlProviderCallback(Request $request, $provider)
    {
        $response = $this->authService->socialRegisterOrLogin($provider);

        if(! $response['success']) {
            return sendApiFailResponse(customMessage: $response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);

    }
}
