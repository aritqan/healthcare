@extends('admin::layouts.master', ['title' => trans('admin::cruds.pharmacies.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.pharmacy_management.pharmacies'),
            'backUrl'           => route('nabd.pharmacies.index'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@push('style')

@endpush

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.pharmacies.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('nabd.pharmacies.postUpdate', [$model->id]),
                                    'method'    => 'PUT',
                                ]
                            ])
                            @slot('fields')

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'           => [
                                                'name'          => [
                                                    'show'      => true,
                                                    'required'  => true,
                                                    'value'     => function($model, $locale) {
                                                        return $model->smartTrans('name', $locale, true);
                                                    },
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'state_id',
                                                'label'         => trans('admin::inputs.clinic_crud.state_id.label'),
                                                'placeholder'   => trans('admin::inputs.clinic_crud.state_id.placeholder'),
                                                'help'          => trans('admin::inputs.clinic_crud.state_id.help'),
                                                'required'      => true,
                                                'data'          => getStatesForCountry('SYR'),
                                                'text'          => fn($key, $value) => $value->smartTrans('name'),
                                                'values'        => fn($key, $value) => $value->id,
                                                'select'        => fn($key, $value, $selected) => $value->id == $selected,
                                                'value'         => $model->state_id,
                                            ]
                                        ])
                                    </div>
                                </div>

                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="col-lg-3"></div>
        </div>
    </div>
@endsection

@push('script')

@endpush
