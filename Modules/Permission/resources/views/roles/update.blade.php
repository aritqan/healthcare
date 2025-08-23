@extends('admin::layouts.master', ['title' => trans('admin::cruds.roles.edit')])

@push('style')
    <style>
        .permissions .invalid-feedback {
            margin-top: 0;
            margin-left: 5px;
            margin-right: 5px
        }
    </style>
@endpush

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.user_management.roles'),
            'backUrl'           => route('permission.roles.index'),
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
                            @lang('admin::cruds.roles.edit')
                        </h3>
                    </div>

                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'method'    => 'PUT',
                                    'action'    => route('permission.roles.postUpdate', $model->id),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'code',
                                                'readonly'      => true,
                                                'label'         => trans('admin::inputs.role_crud.code.label'),
                                                'placeholder'   => trans('admin::inputs.role_crud.code.placeholder'),
                                                'subText'       => trans('admin::inputs.role_crud.code.help'),
                                                'required'      => true,
                                                'class'         => 'to-upper space-to-underscore only-english-letters',
                                                'value'         => $model->name,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'           => [
                                                'title'         => [
                                                    'show'      => true,
                                                    'required'  => true,
                                                    'value'     => function($model, $locale) {
                                                        return $model->smartTrans('title', $locale, true);
                                                    },
                                                ],
                                                'description'   => [
                                                    'show'      => true,
                                                    'required'  => true,
                                                    'value'     => function($model, $locale) {
                                                        return $model->smartTrans('description', $locale, true);
                                                    },
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row permission-accordion">
                                    <span class="fw-bold mb-2 fs-6 text-dark ">
                                        @lang('admin::strings.define_permissions_for_role')
                                    </span>
                                    <div class="accordion" id="kt_accordion_1">
                                        @foreach ($abilityGroups as $abilityGroup)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="kt_accordion_1_header_{{$abilityGroup->id}}">
                                                    <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_{{$abilityGroup->id}}" aria-expanded="true" aria-controls="kt_accordion_1_body_{{$abilityGroup->id}}">
                                                        <i class="{{$abilityGroup->icon}}"></i>
                                                        {{$abilityGroup->smartTrans('title')}}
                                                    </button>
                                                </h2>
                                                <div id="kt_accordion_1_body_{{$abilityGroup->id}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_{{$abilityGroup->id}}" data-bs-parent="#kt_accordion_1">
                                                    <div class="accordion-body row form-group">
                                                        <div class="pb-5 border-gray-300 border-bottom-dashed border-1">
                                                            @include('admin::components.buttons.button', [
                                                                'options'           => [
                                                                    'title'         => trans('admin::base.select_all'),
                                                                    'class'         => 'btn btn-bg-light btn-color-success me-3 permission-select-all',
                                                                    'targetModal'   => false,
                                                                ]
                                                            ])
                                                            @include('admin::components.buttons.button', [
                                                                'options'           => [
                                                                    'title'         => trans('admin::base.select_none'),
                                                                    'class'         => 'btn btn-bg-light btn-color-danger permission-select-none',
                                                                    'targetModal'   => false,
                                                                ]
                                                            ])
                                                        </div>
                                                        @foreach ($abilityGroup->abilities as $ability)
                                                            <div class="mb-5 mt-5 col-md-6 col-12 permissions">
                                                                @include('admin::components.inputs.checkbox', [
                                                                    'options'           => [
                                                                        'name'          => 'permissions[' . $ability->id . ']',
                                                                        'label'         => $ability->smartTrans('title'),
                                                                        'value'         => $ability->id,
                                                                        'class'         => 'permissions-checkbox',
                                                                        'checked'       => $model->abilities->contains($ability->id),
                                                                    ]
                                                                ])
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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

@once
    @push('script')
        <script>
            $(document).ready(function () {
                $('.permission-select-all').on('click', function () {
                    $(this).parent().siblings().find('.permissions-checkbox').prop('checked', true);
                });

                $('.permission-select-none').on('click', function () {
                    $(this).parent().siblings().find('.permissions-checkbox').prop('checked', false);
                });
            });
        </script>
    @endpush
@endonce

