<?php

namespace Modules\Nabd\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\Pharmacy;
use Modules\Nabd\Http\Services\PharmacyService;
use Modules\Nabd\Http\Requests\PharmacyRequest;
use Modules\Nabd\Resources\PharmacyResource;


class PharmacyController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = PharmacyResource::class;

    protected $modelRequest = PharmacyRequest::class;

    protected $isPaginate = true;

    public function __construct(Pharmacy $model, PharmacyService $modelService)
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
