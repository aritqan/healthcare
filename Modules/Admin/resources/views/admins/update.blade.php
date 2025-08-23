@extends('admin::layouts.master', ['title' => trans('admin::cruds.admins.edit')])

@php
    use Modules\Admin\Models\Admin;
@endphp

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.user_management.admins'),
            'backUrl'           => route('admin.admins.index'),
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
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.admins.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('admin.admins.postUpdate', [$model->id]),
                                    'method'    => 'PUT',
                                ]
                            ])

                            @slot('fields')
                                <div class="fv-row d-flex justify-content-center">
                                    <div class="d-flex flex-column align-items-center mb-10 form-group">
                                        @include('admin::components.inputs.image', [
                                            'options'       => [
                                                'name'      => 'avatar',
                                                'isAvatar'  => true,
                                                'width'     => '125',
                                                'height'    => '125',
                                                'circle'    => true,
                                                'subText'   => trans('admin::inputs.admin_crud.avatar.subText', [
                                                    'types' => getImageTypes(true),
                                                    'size'  => config('base.file.image.max_size'),
                                                ]),
                                                'accept'    => getImageTypes(),
                                                'value'     => $model->getFirstMedia(Admin::MEDIA_COLLECTION)?->getUrl(),
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'status',
                                                'label'         => trans('admin::inputs.base_crud.status.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.status.placeholder'),
                                                'help'          => trans('admin::inputs.base_crud.status.help'),
                                                'required'      => true,
                                                'data'          => $adminStatuses,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $key,
                                                'select'        => fn($key, $value, $selected) => $key == $selected,
                                                'value'         => $model->status,
                                            ]
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'lang',
                                                'label'         => trans('admin::inputs.base_crud.lang.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.lang.placeholder'),
                                                'help'          => trans('admin::inputs.base_crud.lang.help'),
                                                'required'      => true,
                                                'data'          => $_ALL_LOCALE_,
                                                'text'          => fn($key, $value) => $value['native'],
                                                'values'        => fn($key, $value) => $key,
                                                'select'        => fn($key, $value, $selected) => $key == $selected,
                                                'value'         => $model->lang,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'full_name',
                                                'label'         => trans('admin::inputs.admin_crud.full_name.label'),
                                                'placeholder'   => trans('admin::inputs.admin_crud.full_name.placeholder'),
                                                'required'      => true,
                                                'value'         => $model->full_name,
                                            ],
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'username',
                                                'label'         => trans('admin::inputs.admin_crud.username.label'),
                                                'placeholder'   => trans('admin::inputs.admin_crud.username.placeholder'),
                                                'help'          => trans('admin::inputs.admin_crud.username.help'),
                                                'required'      => true,
                                                'class'         => 'to-lower space-to-underscore only-english-letters-and-numbers',
                                                'value'         => $model->username,
                                            ],
                                        ])
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'email',
                                                'label'         => trans('admin::inputs.base_crud.email.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.email.placeholder'),
                                                'required'      => true,
                                                'emailMask'     => true,
                                                'value'         => $model->email,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.phone', [
                                            'options'               => [
                                                'name'              => 'phone',
                                                'fullNumberName'    => 'phone_number',
                                                'label'             => trans('admin::inputs.admin_crud.phone_number.label'),
                                                'required'          => true,
                                                'value'             => $model->phone_number,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.password', [
                                            'options'           => [
                                                'name'          => 'password',
                                                'label'         => trans('admin::inputs.base_crud.password.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.password.placeholder'),
                                                'help'          => trans('admin::inputs.base_crud.password.help'),
                                                'required'      => true,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.password', [
                                            'options'           => [
                                                'name'          => 'password_confirmation',
                                                'label'         => trans('admin::inputs.base_crud.password_confirmation.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.password_confirmation.placeholder'),
                                                'help'          => trans('admin::inputs.base_crud.password_confirmation.help'),
                                                'highlight'     => false,
                                                'required'      => true,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'gender',
                                                'label'         => trans('admin::inputs.admin_crud.gender.label'),
                                                'placeholder'   => trans('admin::inputs.admin_crud.gender.placeholder'),
                                                'help'          => trans('admin::inputs.admin_crud.gender.help'),
                                                'required'      => true,
                                                'data'          => $genderTypes,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $key,
                                                'select'        => fn($key, $value, $selected) => $key == $selected,
                                                'value'         => $model->gender,
                                            ]
                                        ])
                                    </div>

                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="mb-7 form-group">
                                    <label class="required fw-bold fs-6 mb-5">
                                        {{ trans_choice('admin::cruds.roles.title', 1) }}
                                    </label>

                                    @foreach ($roles as $role)
                                        <div class="d-flex fv-row">
                                            <div class="form-check form-check-custom form-check-solid">
                                                @include('admin::components.inputs.radio', [
                                                    'options'           => [
                                                        'id'            => $role->id,
                                                        'value'         => $role->id,
                                                        'name'          => 'role',
                                                        'checked'       => $model->isA($role->name),
                                                        'label'         =>
                                                        '<div class="fw-bolder text-gray-800">'. $role->smartTrans('title') .'</div>
                                                        <div class="text-gray-600">'. $role->smartTrans('description') .'</div>'
                                                    ]
                                                ])
                                            </div>
                                        </div>

                                        @if(!$loop->last)
                                            <div class='separator separator-dashed my-5'></div>
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
