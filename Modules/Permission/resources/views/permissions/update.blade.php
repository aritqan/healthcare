@extends('admin::layouts.master', ['title' => trans('admin::cruds.permissions.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.user_management.permissions'),
            'backUrl'           => route('permission.permissions.index'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@once
    @push('style')
        <style>
            :dir(ltr) input[type="checkbox"] ~ .invalid-feedback{
                margin-left: 20px;
                margin-top: 0;
            }

            :dir(rtl) input[type="checkbox"] ~ .invalid-feedback{
                margin-right: 20px;
                margin-top: 0;
            }
        </style>
    @endpush
@endonce

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.permissions.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'                   => [
                                    'isAjax'                => true,
                                    'method'                => 'PUT',
                                    'checkForEmptyCheckbox' => true,
                                    'action'                => route('permission.permissions.postUpdate', $model->id),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'permission_type',
                                                'type'          => 'hidden',
                                                'value'         => 'group_permission',
                                            ]
                                        ])
                                        @include('admin::components.inputs.text', [
                                                'options'           => [
                                                    'name'          => 'ability_group_code',
                                                    'label'         => trans('admin::inputs.permission_crud.code.label'),
                                                    'placeholder'   => trans('admin::inputs.permission_crud.code.placeholder'),
                                                    'help'          => trans('admin::inputs.permission_crud.code.help'),
                                                    'class'         => 'to-upper space-to-underscore only-english-letters',
                                                    'readonly'      => true,
                                                    'value'         => $model->code,
                                                ]
                                        ])
                                    </div>
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                       @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'ability_group_icon',
                                                'label'         => trans('admin::inputs.permission_crud.icon.label'),
                                                'placeholder'   => trans('admin::inputs.permission_crud.icon.placeholder'),
                                                'help'          => trans('admin::inputs.permission_crud.icon.help'),
                                                'value'         => $model->icon,
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
                                                    'required'  => false,
                                                    'value'     => function($model, $locale) {
                                                        return $model->smartTrans('description', $locale, true);
                                                    },
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row mb-10">
                                    <span class="fw-bold mb-2 fs-6 text-dark ">
                                        @lang('admin::strings.permissions_group')
                                    </span>

                                    <div class="pb-5 mt-2">
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

                                    @foreach ($allPermissions as $permission)
                                        @php
                                            $substringToDelete  = '_' . $model->code;
                                            $title              = str_replace($substringToDelete, "", $permission);
                                            $title              = Str::lower($title);

                                            $ability = $model->abilities()->where('name', $permission)->first();
                                        @endphp
                                        <div class="mb-5 mt-5 col-6 permissions form-group">
                                            @include('admin::components.inputs.checkbox', [
                                                'options'           => [
                                                    'name'          => 'ability_types[' . $title . ']',
                                                    'label'         => !empty($ability) ? $ability->smartTrans('title') : trans('admin::cruds.' . $title . '.title'),
                                                    'value'         => $title,
                                                    'class'         => 'permissions-checkbox',
                                                    'checked'       => !empty($ability) ? true : false,
                                                ]
                                            ])
                                        </div>
                                        <div class="col-6 form-group">
                                            @include('admin::components.inputs.text', [
                                                'options'       => [
                                                    'name'      => 'abilities[' . $title . ']',
                                                    'label'     => trans('admin::inputs.permission_crud.code.label'),
                                                    'value'     => $permission,
                                                    'data'      => [
                                                        'ability-code' => $permission,
                                                    ],
                                                    'readonly'  => true,
                                                ]
                                            ])
                                        </div>

                                        @if (! $loop->last)
                                            <div class="separator separator-dashed my-5"></div>
                                        @endif
                                    @endforeach
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
