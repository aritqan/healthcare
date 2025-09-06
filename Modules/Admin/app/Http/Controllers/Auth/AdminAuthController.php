<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Admin\Http\Requests\UpdatePasswordRequest;

class AdminAuthController extends BaseController
{
    /**
     * Show login form
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    /**
     * Authenticate user
     *
     * @param Request $request
     */
    public function authenticate(Request $request)
    {
        // credentials return array of email and hashed password
        $credentials = $request->validate([
            'email'     => ['required', 'email', 'exists:admins,email'],
            'password'  => ['required', 'string'],
        ]);

        if (auth()->guard('admin')->attempt([
            'email'     => $credentials['email'],
            'password'  => $credentials['password'],
            fn($query)  => $query->active()
        ])) {
            $request->session()->regenerate();

            $admin                  = auth()->guard('admin')->user();
            $admin->last_login_at   = now();
            $admin->ip_address      = $request->ip();
            $admin->save();

            return sendSuccessResponse(route('admin.dashboard.index'), 'login_success', true);
        }

        return sendFailResponse('login_failed_wrong_password_or_inactive');
    }

    /**
     * Logout user
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        if(!empty(session('OLD_ADMIN_ID'))){
            session()->forget('OLD_ADMIN_ID');
        }

        foreach(app('admin')->fcmTokens ?? [] as $token){
            $token->delete();
        }

        auth()->guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return sendSuccessResponse(route('admin.auth.login'), 'logout_success');
    }

    public function changePassword(Request $request)
    {
        if(! auth()->guard('admin')->user()->password_is_temp){
            return redirect()->route('admin.auth.login');
        }

        return view('admin::auth.change_password');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        if(! $user->password_is_temp){
            return sendFailResponse('password_is_not_temp');
        }

        if(Hash::check($request->password, $user->password)){
            return sendFailResponse('new_password_cannot_be_same_as_old_password');
        }

        $user->update([
            'password'          => $request->password,
            'password_is_temp'  => false
        ]);

        return sendSuccessResponse(route('admin.dashboard.index'), 'password_updated_successfully');
    }
}
