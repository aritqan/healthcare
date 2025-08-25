<?php

namespace Modules\Nabd\Http\Controllers\Api;

use Modules\Base\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\MedicalSpecialty;
use Modules\Nabd\Http\Services\MedicalSpecialtyService;
use Modules\Nabd\Http\Requests\MedicalSpecialtyRequest;
use Modules\Nabd\Resources\MedicalSpecialtyResource;


class MedicalSpecialtyController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = MedicalSpecialtyResource::class;

    protected $modelRequest = MedicalSpecialtyRequest::class;

    protected $isPaginate = false;

    public function __construct(MedicalSpecialty $model, MedicalSpecialtyService $modelService)
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
