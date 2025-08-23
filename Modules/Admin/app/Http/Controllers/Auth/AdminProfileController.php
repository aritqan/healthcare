<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;
use Modules\Base\Enums\HttpStatusCode;
use Modules\Admin\Http\Services\AdminCrudService;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Admin\Http\Requests\UpdateAuthInfoRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdminProfileController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AdminCrudService $adminCrudService, protected Admin $adminModel)
    {}

    /**
     * Show profile edit form
     */
    public function editProfile()
    {
        return view('admin::auth.profile' , $this->data);
    }

    /**
     * Update profile
     *
     * @param Request $request
     */
    public function updateProfile(UpdateAuthInfoRequest $request)
    {
        try
        {
            $this->adminCrudService->updateModel(auth('admin')->user(), $request->validated());
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(back()->getTargetUrl());
    }

    /**
     * Check if can login to another account
     *
     * @param Admin $model
     * @return array
     */
    private function checkIfCanLoginToAnotherAccount($model) : array
    {
        if($model->id == auth()->guard('admin')->id()){
            return sendFailInternalResponse('cant_login_to_same_account');
        }
        if($model->isRoot()){
            return sendFailInternalResponse('cant_login_to_root_account');
        }
        if(! $model->isActive()) {
            return sendFailInternalResponse('cant_login_to_inactive_account');
        }

        return sendSuccessInternalResponse();
    }

    /**
     * Login to another account
     */
    public function loginToAnotherAccount(Request $request)
    {
        $this->data['model'] = $this->adminModel->getModel(id:$request->model, withTrashed: true, withDisabled: true);

        $result = $this->checkIfCanLoginToAnotherAccount($this->data['model']);

        if(! $result['success']){
            return sendFailResponse(customMessage: $result['message']);
        }

        try
        {
            session([
                'OLD_ADMIN_ID' => auth()->guard('admin')->id(),
            ]);

            auth()->guard('admin')->loginUsingId($request->model);
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route('admin.profile.edit'), 'login_to_another_account_success');
    }

    /**
     * Back to old account
     */
    public function backToOldAccount()
    {
        if(!session()->has('OLD_ADMIN_ID')){
            return sendFailResponse('old_admin_account_not_found');
        }

        try
        {
            auth()->guard('admin')->loginUsingId(session('OLD_ADMIN_ID'));
            session()->forget('OLD_ADMIN_ID');
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route('admin.profile.edit'), 'back_to_previos_account_success');
    }

    /**
     * Update Language
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'lang' => ['required', 'string', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
        ]);

        $user = app('admin');

        $user->update(['lang' => $request->lang]);

        $fcmToken = $user->fcmTokens()->first();

        if($fcmToken){
            $extraData = $fcmToken->extra_data ?? [];

            $extraData['topic'] = 'admin_' . $request->lang;

            // Save the updated extra_data
            $fcmToken->update([
                'extra_data' => $extraData,
            ]);
        }

        return app('response')
            ->success()
            ->code(HttpStatusCode::OK)
            ->withDefaultMessage('language_updated_successfully')
            ->send(asAjax: true);
    }
}
