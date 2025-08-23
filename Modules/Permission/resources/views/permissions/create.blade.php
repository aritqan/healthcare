@extends('admin::layouts.master', ['title' => trans('admin::cruds.permissions.add')])

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
                            @lang('admin::cruds.permissions.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'                   => [
                                    'isAjax'                => true,
                                    'checkForEmptyCheckbox' => true,
                                    'action'                => route('permission.permissions.postCreate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'permission_type',
                                                'label'         => trans('admin::inputs.permission_crud.permission_type.label'),
                                                'help'          => trans('admin::inputs.permission_crud.permission_type.help'),
                                                'data'          => [
                                                    [
                                                        'value'     => 'group_permission',
                                                        'text'      => trans('admin::inputs.permission_crud.permission_type.data.group_permission'),
                                                    ],
                                                    [
                                                        'value'     => 'sigle_permission',
                                                        'text'      => trans('admin::inputs.permission_crud.permission_type.data.sigle_permission'),
                                                    ],
                                                ],
                                                'text'          => function($key, $value) {return $value['text'];},
                                                'values'        => function($key, $value) {return $value['value'];},
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row single-permission-fields" @style(['display:none'])>
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'permission_group_code',
                                                'label'         => trans('admin::inputs.permission_crud.permission_group.label'),
                                                'help'          => trans('admin::inputs.permission_crud.permission_group.help'),
                                                'data'          => $abilityGroups,
                                                'text'          => function($key, $value) {return $value['code'];},
                                                'values'        => function($key, $value) {return $value['code'];},
                                            ]
                                        ])
                                    </div>

                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                             'options'           => [
                                                'name'          => 'permission_name',
                                                'label'         => trans('admin::inputs.permission_crud.permission_name.label'),
                                                'placeholder'   => trans('admin::inputs.permission_crud.permission_name.placeholder'),
                                                'help'          => trans('admin::inputs.permission_crud.permission_name.help'),
                                                'class'         => 'to-upper space-to-underscore only-english-letters',
                                            ]
                                        ])
                                     </div>
                                </div>

                                <div class="row group-permission-fields">
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                       @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'ability_group_code',
                                                'label'         => trans('admin::inputs.permission_crud.code.label'),
                                                'placeholder'   => trans('admin::inputs.permission_crud.code.placeholder'),
                                                'help'          => trans('admin::inputs.permission_crud.code.help'),
                                                'class'         => 'to-upper space-to-underscore only-english-letters',
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
                                                    'value'     => null,
                                                ],
                                                'description'   => [
                                                    'show'      => true,
                                                    'required'  => false,
                                                    'value'     => null,
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row mb-10 group-permission-fields">
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

                                    @foreach (CRUD_TYPES as $type)
                                        <div class="mb-5 mt-5 col-6 permissions form-group">
                                            @include('admin::components.inputs.checkbox', [
                                                'options'           => [
                                                    'name'          => 'ability_types[' . $type . ']',
                                                    'label'         => trans('admin::cruds.' . $type . '.title'),
                                                    'value'         => $type,
                                                    'class'         => 'permissions-checkbox',
                                                ]
                                            ])
                                        </div>
                                        <div class="col-6 form-group">
                                            @include('admin::components.inputs.text', [
                                                'options'       => [
                                                    'name'      => 'abilities[' . $type . ']',
                                                    'class'     => 'ability-code',
                                                    'label'     => trans('admin::inputs.permission_crud.code.label'),
                                                    'value'     => Str::upper($type) . '_',
                                                    'data'      => [
                                                        'ability-code' => Str::upper($type) . '_',
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

                $('select[name=permission_type]').on('change', function () {
                    let value = $(this).val();
                    let singlePermissionFields = $('.single-permission-fields');
                    let groupPermissionFields = $('.group-permission-fields');

                    if (value === 'sigle_permission') {
                        singlePermissionFields.show();
                        groupPermissionFields.hide();
                    } else {
                        singlePermissionFields.hide();
                        groupPermissionFields.show();
                    }
                });

                $('input[name=ability_group_code]').on('input', function () {
                    let value = $(this).val();
                    let abilityCode = $('.ability-code');

                    abilityCode.each(function () {
                        let abilityCodeValue = $(this).attr('data-ability-code');
                        $(this).val(abilityCodeValue + value);
                    });
                });

            });
        </script>
    @endpush
@endonce
