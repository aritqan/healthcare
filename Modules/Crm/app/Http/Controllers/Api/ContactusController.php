<?php

namespace Modules\Crm\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Crm\Models\Contactus;
use Modules\Crm\Http\Services\ContactusService;
use Modules\Crm\Http\Requests\Api\ContactusRequest;
use Modules\Crm\Resources\ContactusResource;
class ContactusController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = ContactusResource::class;

    protected $modelRequest = ContactusRequest::class;

    public function __construct(Contactus $model, ContactusService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

}
