<?php

namespace Modules\Notification\Http\Controllers\Api;

use Modules\Auth\Resources\UserResource;
use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Notification\Enums\FirebaseTopics;
use Modules\Notification\Http\Requests\FirebaseTokenRequest;
use Modules\Notification\Http\Services\FirebaseService;

class FirebaseTokenController extends BaseApiController
{
    public function __construct(protected FirebaseService $FirebaseService)
    {
        parent::__construct();
    }

    public function saveFcmToken(FirebaseTokenRequest $request)
    {
        $user = $request->user();

        $result = $this->FirebaseService->saveFcmToken($user, $request->validated(), FirebaseTopics::USER);

        return sendApiSuccessResponse($result['message'], data: [
            'user' => new UserResource($user),
        ]);
    }
}
