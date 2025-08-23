<div>
    <div class="row">
        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'disabled'      => true,
                    'label'         => trans('admin::datatable.base_columns.created_at'),
                    'value'         => $model->created_at_format,
                ],
            ])
        </div>

        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'disabled'      => true,
                    'label'         => trans('admin::datatable.base_columns.created_by'),
                    'value'         => $model->user?->full_name ?? trans('log::strings.system'),
                ],
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'disabled'      => true,
                    'label'         => trans('admin::inputs.api_log_crud.service_name.label'),
                    'value'         => $model->service_name,
                ],
            ])
        </div>

        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'disabled'      => true,
                    'label'         => trans('admin::inputs.api_log_crud.method.label'),
                    'value'         => $model->method,
                ],
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'label'         => trans('admin::inputs.base_crud.status.label'),
                    'disabled'      => true,
                    'value'         => $model->status_format['label'],
                ],
            ])
        </div>

        <div class="col-lg-6 col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'label'         => trans('admin::inputs.api_log_crud.status_code.label'),
                    'disabled'      => true,
                    'value'         => $model->status_code,
                ],
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-10 form-group">
            @include('admin::components.inputs.text', [
                'options'           => [
                    'label'         => trans('admin::inputs.api_log_crud.endpoint.label'),
                    'disabled'      => true,
                    'value'         => $model->endpoint,
                ],
            ])
        </div>
    </div>


    <div class="row">
        <div class="col-12 mb-10 form-group">
            @include('admin::components.inputs.textarea', [
                'options'           => [
                    'label'         => trans('admin::inputs.api_log_crud.request.label'),
                    'disabled'      => true,
                    'value'         => $model->request,
                ],
            ])
        </div>
    </div>


    <div class="row">
        <div class="col-12 mb-10 form-group">
            @include('admin::components.inputs.textarea', [
                'options'           => [
                    'label'         => trans('admin::inputs.api_log_crud.response.label'),
                    'disabled'      => true,
                    'value'         => $model->response,
                ],
            ])
        </div>
    </div>
</div>
