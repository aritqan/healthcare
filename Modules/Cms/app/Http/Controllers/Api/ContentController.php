<?php

namespace Modules\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Cms\Models\Content;
use Modules\Cms\Resources\ContentResource;
use Modules\Cms\Http\Services\ContentService;
use Modules\Base\Http\Controllers\BaseApiController;

class ContentController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = ContentResource::class;

    protected $isPaginate = true;

    public function __construct(Content $model, ContentService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([
            'type' => e($request->type),
        ]);
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'type'  => e($request->type),
            'slug'  => e($request->slug),
        ]);
    }
}
