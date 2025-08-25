<?php

namespace Modules\Nabd\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\Doktor;
use Modules\Nabd\Http\Services\DoktorService;
use Modules\Nabd\Http\Requests\DoktorRequest;
use Modules\Nabd\Resources\DoktorResource;


class DoktorController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = DoktorResource::class;

    protected $modelRequest = DoktorRequest::class;

    protected $isPaginate = true;

    public function __construct(Doktor $model, DoktorService $modelService)
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
