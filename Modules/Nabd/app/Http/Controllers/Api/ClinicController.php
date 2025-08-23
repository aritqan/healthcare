<?php

namespace Modules\Nabd\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\Clinic;
use Modules\Nabd\Http\Services\ClinicService;
use Modules\Nabd\Http\Requests\ClinicRequest;
use Modules\Nabd\Resources\ClinicResource;


class ClinicController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = ClinicResource::class;

    protected $modelRequest = ClinicRequest::class;

    protected $isPaginate = true;

    public function __construct(Clinic $model, ClinicService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([

        ]);
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'id'      => $request->id,
        ]);
    }
}
