@extends('admin::layouts.master', ['title' => trans('admin::cruds.clinics.add')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.clinic_management.clinics'),
            'backUrl'           => route('nabd.clinics.index'),
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
                            @lang('admin::cruds.clinics.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('nabd.clinics.postCreate'),
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
                                                    'value'     => null,
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
