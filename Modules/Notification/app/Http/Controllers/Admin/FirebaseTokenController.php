<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseController;
use Modules\Notification\Enums\FirebaseTopics;
use Modules\Notification\Http\Requests\FirebaseTokenRequest;
use Modules\Notification\Http\Services\FirebaseService;

class FirebaseTokenController extends BaseController
{
    public function __construct(protected FirebaseService $FirebaseService)
    {
        parent::__construct();
    }

    public function saveFcmToken(FirebaseTokenRequest $request)
    {
        $user = app('admin');

        $result = $this->FirebaseService->saveFcmToken($user, $request->validated(), FirebaseTopics::ADMIN);

        return response()->json(['message' => $result['message']]);
    }
}
