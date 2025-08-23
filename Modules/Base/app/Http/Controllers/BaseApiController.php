<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Admin\Resources\PaginateResource;

class BaseApiController extends BaseController
{
    public $data = [];

    protected $model;

    protected $modelResource;

    protected $modelService;

    protected $modelRequest;

    public $supportedLang;

    public $defaultLocale;

    protected $isPaginate;

    public $locale;

    public function __construct()
    {
        $this->defaultLocale = getSetting('app_default_language', 'ar');

        $this->supportedLang = view()->shared('_ALL_LOCALE_KEY_');

        $this->locale = in_array(request()->header('locale', $this->defaultLocale), $this->supportedLang) ? request()->header('locale', $this->defaultLocale) : getSetting('app_default_language', 'ar');

        app()->setLocale($this->locale);

        // get plural name of the model
        $this->data['model_plural'] = Str::plural(Str::snake(class_basename($this->model)));

        // get singular name of the model
        $this->data['model_singular'] = Str::singular(Str::snake(class_basename($this->model)));

        parent::__construct();
    }

    /**
     *
     * Merge Data To Request For Collection
     */
    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([]);
    }

    /**
     * Merge Data To Request For Show, Update And Destroy
     */
    public function mergeDataToRequest(Request $request)
    {
        $request->merge([]);
    }

    /**
     * Send a listing of the resource to ajax.
     */
    public function list(Request $request)
    {
        $this->mergeDataToRequestForCollection($request);

        $this->data['model']    = $this->model->getDataForApi($request->all(), isCollection: true);
        $this->data['page']     = $request->has('page') ? $request->page : 1;
        $this->data['data']     = $this->isPaginate ? $this->data['model']->paginate(NUMBER_OF_RECORDS_PER_PAGE, ['*'], 'page', $this->data['page']) : $this->data['model']->get();
        $this->data['paginate'] = $this->isPaginate ? new PaginateResource($this->data['data']) : null;

        return sendApiSuccessResponse(data: [
            $this->data['model_plural'] => $this->modelResource::collection($this->data['data']),
            'paginate'                  => $this->data['paginate'],
        ]);
    }

    /**
     * Display the specified resource to api.
     */
    public function show(Request $request)
    {
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        return sendApiSuccessResponse(data: [
            $this->data['model_singular'] => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        app($this->modelRequest);

        $result = $this->modelService->createModel(app($this->modelRequest)->validated());

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse(customMessage: $result['message'], errors: $result['errors']);
        }

        return sendApiSuccessResponse('created_successfully', data: [
            $this->data['model_singular'] => new $this->modelResource($result),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        app($this->modelRequest);

        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        $this->modelService->updateModel($this->data['model'], app($this->modelRequest)->validated());

        return sendApiSuccessResponse('updated_successfully', data: [
            $this->data['model_singular'] => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        $this->data['model']->delete();

        return sendApiSuccessResponse('deleted_successfully');
    }
}
