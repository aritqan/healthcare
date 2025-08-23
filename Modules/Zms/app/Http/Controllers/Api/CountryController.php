<?php

namespace Modules\Zms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Zms\Models\Country;
use Modules\Base\Http\Controllers\BaseApiController;
use Modules\Zms\Resources\CountryResource;

class CountryController extends BaseApiController
{
    protected $model;

    protected $modelResource = CountryResource::class;

    protected $isPaginate = true;

    public function __construct(Country $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'id'      => $request->id,
        ]);
    }
}
