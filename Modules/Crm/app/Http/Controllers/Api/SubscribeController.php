<?php

namespace Modules\Crm\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Crm\Models\Subscribe;
use Modules\Crm\Http\Services\SubscribeService;
use Modules\Crm\Http\Requests\Api\SubscribeRequest;
use Modules\Crm\Resources\SubscribeResource;

class SubscribeController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = SubscribeResource::class;

    protected $modelRequest = SubscribeRequest::class;

    public function __construct(Subscribe $model, SubscribeService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }
}
