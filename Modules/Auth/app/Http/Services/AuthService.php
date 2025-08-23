<?php

namespace Modules\Auth\Http\Services;

use Exception;
use Carbon\Carbon;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Enums\AdminStatus;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Resources\UserResource;
use Modules\Auth\Resources\TokenResource;
use Modules\Base\Http\Services\BaseApiService;

class AuthService extends BaseApiService
{
    protected $unnecessaryFieldsForCrud = [];

    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        $modelData                  = $this->prepareModelData($data);
        $modelData['status']        = AdminStatus::ACTIVE;

        DB::transaction(function () use($modelData, $data) {
            $this->data['user']   = User::create($modelData);
            $this->data['token']  = $this->createToken($this->data['user'] , $this->data['user']->email);
        });

        return sendSuccessInternalResponse('user_registered_successfully', [
            'user'  => new UserResource($this->data['user']),
            'token' => $this->data['token'],
        ]);
    }

    /**
     * Login user
     *
     * @param array $data
     * @return array
     */
    public function login(array $data, bool $checkPassword = true): array
    {
        $user = User::where('email', $data['email'])->first();

        $checkUser = $this->checkUserForLogin($user, $data['password'], $checkPassword);

        if(! $checkUser['success']) {
            return $checkUser;
        }

        $token = $this->createToken($user, $user->email);

        return sendSuccessInternalResponse('login_successfully', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Forget password
     *
     * @param array $data
     * @return array
     */
    public function forgetPassword(array $data): array
    {
        $user = User::where('phone_number', $data['phone_number'])->first();

        $checkUser = $this->checkUserForChangePassword($user, $data['new_password']);

        if(! $checkUser['success']) {
            return $checkUser;
        }

        $user->update([
            'password' => $data['new_password'],
        ]);

        return sendSuccessInternalResponse('password_changed_successfully');
    }

    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout($user): array
    {
        $user->tokens()->delete();

        $user->fcmTokens()->delete();

        return sendSuccessInternalResponse('logout_successfully');
    }

    /**
     * Create Api token
     *
     * @param User $user
     * @param string $tokenName
     * @return TokenResource
     */
    private function createToken($user, $tokenName): TokenResource
    {
        $exiprationTime = Carbon::now()->addMinutes((int) config('sanctum.expiration'));

        $token = $user->createToken($tokenName, ['*'], $exiprationTime);

        return new TokenResource($token);
    }

    /**
     * Check if user is active
     *
     * @param User $user
     * @return array
     */
    private function checkUserIfActive($user): array
    {
        if (!$user) {
            return sendFailInternalResponse('user_not_found');
        }

        if(! $user->isActive()) {
            return sendFailInternalResponse('your_account_is_not_active');
        }

        return sendSuccessInternalResponse('can_login');
    }

    /**
     * Check user for login
     *
     * @param User $user
     * @param string $password
     * @return array
     */
    private function checkUserForLogin(User $user, string $password, bool $checkPassword): array
    {
        $checkUser = $this->checkUserIfActive($user);

        if(! $checkUser['success']) {
            return $checkUser;
        }

        if ($checkPassword && !Hash::check($password, $user->password)) {
            return sendFailInternalResponse('password_not_correct');
        }

        return sendSuccessInternalResponse('can_login');
    }

    /**
     * Check user for change password
     *
     * @param User $user
     * @param string $password
     * @return array
     */
    private function checkUserForChangePassword($user, $password): array
    {
        $checkUser = $this->checkUserIfActive($user);

        if(! $checkUser['success']) {
            return $checkUser;
        }

        if(Hash::check($password, $user->password)) {
            return sendFailInternalResponse('new_password_cannot_be_same_as_old_password');
        }

        return sendSuccessInternalResponse('can_change_password');
    }

    /**
     * Social register or login
     *
     * @param  mixed $provider
     * @return void
     */
    public function socialRegisterOrLogin($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return sendFailInternalResponse(customMessage: $e->getMessage());
        }

        $name = explode(' ', $socialUser->name);
        $data = [
            'email'             => $socialUser->email,
            'first_name'        => $name[0],
            'last_name'         => $name[1] ?? null,
            'provider'          => $provider,
            'provider_id'       => $socialUser->id,
            'password'          => $socialUser->token,
            'lang'              => app()->getLocale(),
            'email_verified_at' => Carbon::now(),
        ];

        $user = User::where('email', $socialUser->email)->first();

        if($user) {
            return $this->login(['email' => $user->email, 'password' => $user->password], false);
        } else {
            return $this->register($data);
        }
    }
}
