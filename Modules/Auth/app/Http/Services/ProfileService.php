<?php

namespace Modules\Auth\Http\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Http\Services\BaseApiService;

class ProfileService extends BaseApiService
{
    protected $unnecessaryFieldsForCrud = [];

    public function changeLanguage($user, $data)
    {
        $user->update($data);

        return sendSuccessInternalResponse('language_changed_successfully');
    }

    public function changePassword($user, $data)
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            return sendFailInternalResponse('old_password_is_incorrect');
        }

        if(Hash::check($data['new_password'], $user->password)) {
            return sendFailInternalResponse('new_password_cannot_be_same_as_old_password');
        }

        $user->update([
            'password' => $data['new_password']
        ]);

        return sendSuccessInternalResponse('password_changed_successfully');
    }

    public function updateProfile($user, $data)
    {
        $user->update($data);

        return sendSuccessInternalResponse('profile_updated_successfully');
    }
}
