<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Auth\Resources\UserResource;
use Modules\Auth\Http\Services\ProfileService;
use Modules\Auth\Http\Requests\ChangeLocaleRequest;
use Modules\Auth\Http\Requests\ChangeProfileRequest;
use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Auth\Http\Requests\ChangePasswordRequest;

class ProfileController extends BaseApiController
{

    public function __construct(protected ProfileService $profileService)
    {
        parent::__construct();
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return sendApiSuccessResponse(data: [
            'user' => new UserResource($user),
        ]);
    }

    public function changeLanguage(ChangeLocaleRequest $request)
    {
        $user = $request->user();

        $response = $this->profileService->changeLanguage($user, $request->validated());

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $response = $this->profileService->changePassword($user, $request->validated());

        if(! $response['success']) {
            return sendApiFailResponse(customMessage: $response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }

    public function updateProfile(ChangeProfileRequest $request)
    {
        $user = $request->user();

        $response = $this->profileService->updateProfile($user, $request->validated());

        if(! $response['success']) {
            return sendApiFailResponse(customMessage: $response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse(customMessage: $response['message'], data: $response['data']);
    }
}
