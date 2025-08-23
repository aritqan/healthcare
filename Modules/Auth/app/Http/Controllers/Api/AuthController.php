<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Auth\Http\Services\AuthService;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Auth\Http\Requests\ForgetPasswordRequest;

class AuthController extends BaseApiController
{

    public function __construct(protected AuthService $authService)
    {
        parent::__construct();
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->validated());

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse(customMessage: $response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $response = $this->authService->forgetPassword($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse(customMessage: $response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $response = $this->authService->logout($user);

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

}
