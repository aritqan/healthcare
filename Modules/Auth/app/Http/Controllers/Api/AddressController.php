<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Auth\Models\Address;
use Modules\Auth\Resources\AddressResource;
use Modules\Auth\Http\Requests\AddressRequest;
use Modules\Auth\Http\Services\AddressService;
use Modules\Base\Http\Controllers\BaseApiController;

class AddressController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = AddressResource::class;

    protected $modelRequest = AddressRequest::class;

    protected $isPaginate = true;

    public function __construct(Address $model, AddressService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([
            'user_id' => $request->user()->id,
        ]);
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'user_id' => $request->user()->id,
            'id'      => $request->id,
        ]);
    }
}
