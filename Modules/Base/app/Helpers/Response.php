<?php

use Illuminate\Validation\Validator;
use Modules\Base\Enums\HttpStatusCode;

if(!function_exists('sendSuccessResponse'))
{
    function sendSuccessResponse(string|null $redirectTo = null, string|null $message = null, bool $withIntended = false, $customMessage = null)
    {
        return app('response')
        ->success()
        ->code(HttpStatusCode::OK)
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->redirectTo($redirectTo, $withIntended)
        ->send();
    }
}

if(!function_exists('sendUnauthorizedResponse'))
{
    function sendUnauthorizedResponse(string|null $redirectTo = null, string $message = 'login_required')
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::UNAUTHORIZED)
        ->withDefaultMessage($message)
        ->redirectTo($redirectTo)
        ->send();
    }
}

if(!function_exists('sendFailResponse'))
{
    function sendFailResponse(string|null $message = null, string|null $customMessage = null)
    {
        return app('response')
        ->fail()
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->code(HttpStatusCode::BAD_REQUEST)
        ->send();
    }
}

if(!function_exists('sendValidationResponse'))
{
    function sendValidationResponse(Validator $validator)
    {
        return app('response')
        ->fail()
        ->validationErrors($validator->errors())
        ->withDefaultMessage()
        ->code(HttpStatusCode::UNPROCESSABLE_ENTITY)
        ->send();
    }
}

if(!function_exists('sendExceptionResponse'))
{
    function sendExceptionResponse(Exception $e)
    {
        return app('response')
        ->fail()
        ->withDefaultMessage()
        ->exception($e)
        ->code(HttpStatusCode::INTERNAL_SERVER_ERROR)
        ->send();
    }
}

if(!function_exists('sendNotFoundResponse'))
{
    function sendNotFoundResponse(string $message = 'record_not_found')
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::NOT_FOUND)
        ->view('admin::errors.404')
        ->withDefaultMessage($message)
        ->send();
    }
}

if(!function_exists('sendServerErrorResponse'))
{
    function sendServerErrorResponse(string|null $message = null)
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::INTERNAL_SERVER_ERROR)
        ->view('admin::errors.500')
        ->withDefaultMessage($message)
        ->send();
    }
}

if(!function_exists('sendMaintenanceModeResponse'))
{
    function sendMaintenanceModeResponse(string $message = 'under_maintenance')
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::SERVICE_UNAVAILABLE)
        ->view('admin::errors.503')
        ->withDefaultMessage($message)
        ->send();
    }
}

if(!function_exists('sendDontHavePermissionResponse'))
{
    function sendDontHavePermissionResponse(string $message = 'dont_have_permission')
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::FORBIDDEN)
        ->withDefaultMessage($message)
        ->withData(['error' => trans('admin::messages.web_response_messages.dont_have_permission')])
        ->send();
    }
}

if(!function_exists('sendMethodNotAllowedResponse'))
{
    function sendMethodNotAllowedResponse(string $message = 'method_not_allowed')
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::METHOD_NOT_ALLOWED)
        ->view('admin::errors.405')
        ->withCustomMessage($message)
        ->send();
    }
}

if(!function_exists('sendSuccessInternalResponse'))
{
    function sendSuccessInternalResponse(string|null $message = null, array $data = [], string|null $customMessage = null)
    {
        return app('response')
        ->success()
        ->code(HttpStatusCode::OK)
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->withData($data)
        ->send(isInternal: true);
    }
}

if(!function_exists('sendFailInternalResponse'))
{
    function sendFailInternalResponse(string|null $message = null, array $errors = [], string|null $customMessage = null)
    {
        return app('response')
        ->fail()
        ->code(HttpStatusCode::BAD_REQUEST)
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->withErrors($errors)
        ->send(isInternal: true);
    }
}

if(!function_exists('sendApiSuccessResponse'))
{
    function sendApiSuccessResponse(string $message = 'data_loaded_successfully', string|null $customMessage = null, array $data = [])
    {
        return app('response')
        ->success()
        ->code(HttpStatusCode::OK)
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->withData($data)
        ->send();
    }
}

if(!function_exists('sendApiFailResponse'))
{
    function sendApiFailResponse(string|null $message = null, string|null $customMessage = null, array $errors = [], array $data = [])
    {
        return app('response')
        ->fail()
        ->withDefaultMessage($message)
        ->withCustomMessage($customMessage)
        ->code(HttpStatusCode::BAD_REQUEST)
        ->withErrors($errors)
        ->withData($data)
        ->send();
    }
}
