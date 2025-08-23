<?php

namespace Modules\Base\Http\Responses;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Base\Enums\HttpStatusCode;

class ResponseHelper
{
    /**
     * @var int
     */
    public $code = HttpStatusCode::OK;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $module = 'admin';

    /**
     * @var Exception
     */
    public $exception;

    /**
     * ResponseHelper constructor.
     */
    public function __construct()
    {
        $this->data['notify_type']    = 'toastr';
    }

    /**
     * Set module name
     *
     * @param string $module
     * @return $this
     */
    public function module(string $module = 'admin')
    {
        $this->module = strtolower($module);
        return $this;
    }

    /**
     * Set notify type
     *
     * @param string $type
     * @return $this
     */
    public function notifyType(string $type)
    {
        $this->data['notify_type'] = strtolower($type);
        return $this;
    }

    /**
     * Set success flag
     *
     * @return $this
     */
    public function success()
    {
        $this->data['success']  = true;
        $this->data['code']     = HttpStatusCode::OK;
        return $this;
    }

    /**
     * Set fail flag
     *
     * @return $this
     */
    public function fail()
    {
        $this->data['success']  = false;
        $this->data['code']     = HttpStatusCode::UNPROCESSABLE_ENTITY;

        return $this;
    }

    /**
     * Set code
     *
     * @param int $code
     * @return $this
     */
    public function code(int $code)
    {
        $this->code         = $code;
        $this->data['code'] = $code;

        return $this;
    }

    /**
     * Set redirect url
     *
     * @param string $url
     * @param bool $withIntended
     * @return $this
     */
    public  function redirectTo(string|null $url = null, bool $withIntended = false)
    {
        $this->data['redirect']     = $url;
        $this->data['withIntended'] = $withIntended;
        return $this;
    }

    /**
     * Set view
     *
     * @param string $view
     * @param array $data
     * @return $this
     */
    public function view(string $view, $data = [])
    {
        $this->data['view'] = $view;
        $this->data['data'] = $data;
        return $this;
    }

    /**
     * Set validation errors
     *
     * @param $errors
     * @return $this
     */
    public function validationErrors($errors)
    {
        $this->data['errors'] = $errors;

        return $this;
    }

    /**
     * Set default message
     *
     * @param string|null $description
     * @return $this
     */
    public function withDefaultMessage(string|null $description = null)
    {
        $translateKey = request()->is('api/*') ? 'api_response_messages' : 'web_response_messages';

        if($this->data['success'] === true) {
            $this->data['message'] = [
                'type'          => 'success',
                'title'         => trans("{$this->module}::messages.response_message_types.success.title"),
                'description'   => is_null($description) ? trans("{$this->module}::messages.response_message_types.success.description") : trans("{$this->module}::messages.".$translateKey.".{$description}"),
            ];
        } else {

            $this->data['message'] = [
                'type'          => 'error',
                'title'         => trans("{$this->module}::messages.response_message_types.error.title"),
                'description'   => is_null($description) ? trans("{$this->module}::messages.response_message_types.error.description") : trans("{$this->module}::messages.".$translateKey.".{$description}"),
            ];

            if(is_null($description) && isset($this->data['errors'])) {
                $this->data['message']['description'] = trans('admin::messages.web_response_messages.validation_error');
            }
        }

        return $this;
    }

    /**
     * Set custom message
     *
     * @param string $type
     * @param string $file
     * @param string $title
     * @param string $description
     * @return $this
     */
    public function withCustomMessage(string|null $message, string|null $title = null)
    {
        if(empty($message)) return $this;

        $title = empty($title) ? ($this->data['success'] === true ? trans('admin::messages.response_message_types.success.title') : trans('admin::messages.response_message_types.error.title')) : $title;

        // if(! isset($this->data['message'])) {
            $this->data['message'] = [
                'type'          => $this->data['success'] === true ? 'success' : 'error',
                'title'         => $title,
                'description'   => $message,
            ];
        // }

        return $this;
    }

    /**
     * Set exception
     *
     * @param Exception $exception
     * @return $this
     */
    public function exception(Exception $exception)
    {
        $this->exception        = $exception;

        if(! is_null($this->exception)) {
            $this->data['exception'] = [
                'message'   => $this->exception->getMessage(),
                'file'      => $this->exception->getFile(),
                'line'      => $this->exception->getLine(),
                // 'trace'     => $this->exception->getTrace(),
            ];

            if(debugEnabled() && isDev() && isset($this->data['message']['description'])) {
                $this->data['message']['description'] = ! is_null($this->exception) ? 'Message : ' . $this->exception->getMessage() . ' [in File : ' . $this->exception->getFile() . '] - on line : ' . $this->exception->getLine() : null;
            }
        }

        $this->exceptionLog();

        return $this;
    }

    /**
     * Log error
     */
    public function exceptionLog()
    {
        Log::channel('daily')->error([
            'request'   => request()->all(),
            'exception' => isset($this->data['exception']) ? $this->data['exception'] : null,
            'module'    => $this->module,
        ]);
    }

    /**
     * Set data
     *
     * @param array $data
     * @return $this
     */
    public function withData(array $data)
    {
        $this->data['data'] = !empty($data) ? (object) $data : null;
        return $this;
    }

    /**
     * Set errors
     *
     * @param array $errors
     * @return $this
     */
    public function withErrors(array $errors)
    {
        $this->data['errors'] = !empty($errors) ? (object) $errors : null;
        return $this;
    }

    /**
     * Send response
     *
     * @param bool $asAjax
     * @param bool $isInternal
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response | array
     */
    public function send(bool $isInternal = false, bool $asAjax = false)
    {
        if($isInternal) {
            return $this->handleInternalResponse();
        }

        if(request()->is('api/*')) {
            return $this->handleApiResponse();
        }

        if($asAjax || request()->ajax()) {
            return $this->handleAjaxRequest();
        }

        return $this->handleNonAjaxRequest();
    }

    /**
     * Handle ajax request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleAjaxRequest()
    {
        if(isset($this->data['redirect']) && !empty($this->data['redirect'])) {
            session()->flash('message', $this->data);
        }
        if(isset($this->data['view'])) {
            unset($this->data['view']);
        }
        return response()->json($this->data, $this->code);
    }

    /**
     * Handle non ajax request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleNonAjaxRequest()
    {
        if(isset($this->data['redirect']) && !empty($this->data['redirect'])) {

            if(isset($this->data['withIntended']) && $this->data['withIntended'] === true) {
                return redirect()
                    ->intended($this->data['redirect'])
                    ->with('message', $this->data);
            }

            return redirect()
                ->to($this->data['redirect'])
                ->with('message', $this->data);
        }
        elseif(isset($this->data['view']) && !empty($this->data['view'])) {
            return response()->view($this->data['view'], $this->data['data']);
        }

        return back()
            ->with('message', $this->data)
            ->withInput()
            ->withErrors($this->data['errors'] ?? []);
    }

    /**
     * Handle internal response
     */
    public function handleInternalResponse()
    {
        $data = [
            'success'   => $this->data['success'],
            'code'      => $this->code,
            'message'   => $this->data['message']['description'] ?? '',
            'errors'    => $this->data['errors'] ?? [],
            'data'      => $this->data['data'] ?? [],
        ];

        // type casting errors and data to array
        $data['errors'] = (array) $data['errors'];
        $data['data']   = (array) $data['data'];

        return $data;
    }

    /**
     * Handle Api response
     */
    public function handleApiResponse()
    {
        // unset unnecessary data
        unset($this->data['notify_type']);
        unset($this->data['view']);
        unset($this->data['withIntended']);
        unset($this->data['redirect']);

        // handle message
        if(isset($this->data['message'])) {
            $this->data['message'] = $this->data['message']['description'];
            $this->data = array_merge(['message' => $this->data['message']], $this->data);
        }

        if(! isset($this->data['message'])) {
            $this->data['message'] = '';
        }

        if(! isset($this->data['errors']) || empty($this->data['errors'])) {
            $this->data['errors'] = (object) [];
        }

        if(! isset($this->data['data']) || empty($this->data['data'])) {
            $this->data['data'] = (object) [];
        }

        return response()->json($this->data, $this->code);
    }
}
