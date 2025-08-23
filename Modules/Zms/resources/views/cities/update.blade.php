@extends('admin::layouts.master', [
    'title' => trans('admin::cruds.cities.edit')
])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.city_management.cities'),
            'backUrl'           => route('zms.states.update', $model->state_id),
            'backTitle'         => trans('admin::strings.back_to_state_page'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-3">
            </div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.cities.edit')
                        </h3>
                    </div>

                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'method'    => 'PUT',
                                    'action'    => route('zms.cities.postUpdate', $model->id),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'native_name',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.native_name.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.native_name.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.native_name.help'),
                                                'value'         => $model->native_name,
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'number',
                                                'name'          => 'lat',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.lat.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.lat.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.lat.help'),
                                                'inputmode'     => 'decimal',
                                                'step'          => '0.000001',
                                                'min'           => '-90',
                                                'max'           => '90',
                                                'value'         => $model->lat_format,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'number',
                                                'name'          => 'lng',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.lng.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.lng.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.lng.help'),
                                                'inputmode'     => 'decimal',
                                                'step'          => '0.000001',
                                                'min'           => '-180',
                                                'max'           => '180',
                                                'value'         => $model->lng_format,
                                            ]
                                        ])
                                    </div>
                                </div>
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
                                                ]
                                            ]
                                        ])
                                    </div>
                                </div>
                            @endslot
                        @endcomponent

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
            </div>
        </div>
    </div>
@endsection

