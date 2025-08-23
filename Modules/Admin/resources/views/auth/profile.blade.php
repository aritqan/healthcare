@php
    use Modules\Admin\Enums\AdminStatus;
@endphp

@extends('admin::layouts.master', ['title' => trans('admin::auth.profile_page.edit_profile')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::auth.profile_page.edit_profile'),
            'backUrl'           => redirect()->back()->getTargetUrl(),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@section('content')
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            @include('admin::components.other.image', [
                                'options'       => [
                                    'src'       => app('admin')->avatar_url,
                                    'alt'       => app('admin')->username,
                                    'onError'   => asset('images/default/avatars/mr_admin.png'),
                                ]
                            ])
                            @if(app('admin')->isActive())
                                <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px"></div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">

                                <div class="d-flex align-items-center mb-2">
                                    @include('admin::components.other.hyperlink', [
                                        'options'           => [
                                            'title'         => app('admin')->full_name,
                                            'class'         => 'text-gray-900 text-hover-primary fs-2 fw-bolder me-1',
                                        ]
                                    ])

                                    @if(app('admin')->email_verified_at)
                                        <a href="javascript:;">
                                            {!! config('admin.svgs.verified_icon') !!}
                                        </a>
                                    @endif

                                </div>

                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <a href="javascript:;" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <span>
                                            {!! config('admin.svgs.person') !!}
                                        </span>
                                        <span>
                                            {{ app('admin')->role_name }}
                                        </span>
                                    </a>

                                    <a href="mailto:{{ app('admin')->email }}" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <span>
                                            {!! config('admin.svgs.email') !!}
                                        </span>
                                        <span>
                                            {{ app('admin')->email }}
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                <span class="btn btn-sm btn-font-sm btn-label-{{ AdminStatus::getStatusColor(app('admin')->status) }} text-center w-100">
                                    @lang('admin::statuses.admin.' . app('admin')->status)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Navbar-->

        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">
                        @lang('admin::auth.profile_page.profile_details')
                    </h3>
                </div>
            </div>

            <div id="kt_account_settings_profile_details" class="collapse show">
                <div class="card-body border-top p-9">
                    @component('admin::components.forms.form', [
                            'options'       => [
                                'isAjax'    => true,
                                'action'    => route('admin.profile.update'),
                                'method'    => 'PUT',
                            ]
                        ])

                        @slot('fields')
                            <div class="row mb-6">
                                @include('admin::components.inputs.image', [
                                    'options'       => [
                                        'label'     => trans('admin::auth.profile_page.profile_image'),
                                        'name'      => 'avatar',
                                        'isAvatar'  => true,
                                        'width'     => '125',
                                        'height'    => '125',
                                        'view'      => 'INLINE',
                                        'input_size'=> 'col-lg-8',
                                        'label_size'=> 'col-lg-4',
                                        'subText'   => trans('admin::inputs.admin_crud.avatar.subText', [
                                            'types' => getImageTypes(true),
                                            'size'  => config('base.file.image.max_size'),
                                        ]),
                                        'accept'    => getImageTypes(),
                                        'value'     => !empty(app('admin')->avatar) ? app('admin')->avatar_url : null,
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.text', [
                                    'options'           => [
                                        'name'          => 'full_name',
                                        'label'         => trans('admin::inputs.admin_crud.full_name.label'),
                                        'placeholder'   => trans('admin::inputs.admin_crud.full_name.placeholder'),
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                        'value'         => app('admin')->full_name,
                                    ],
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.text', [
                                    'options'           => [
                                        'name'          => 'username',
                                        'label'         => trans('admin::inputs.admin_crud.username.label'),
                                        'placeholder'   => trans('admin::inputs.admin_crud.username.placeholder'),
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                        'value'         => app('admin')->username,
                                    ],
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.select', [
                                    'options'           => [
                                        'name'          => 'gender',
                                        'label'         => trans('admin::inputs.admin_crud.gender.label'),
                                        'placeholder'   => trans('admin::inputs.admin_crud.gender.placeholder'),
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                        'data'          => $genderTypes,
                                        'text'          => fn($key, $value) => $value,
                                        'values'        => fn($key, $value) => $key,
                                        'select'        => fn($key, $value, $selected) => $key == $selected,
                                        'value'         => app('admin')->gender,
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.text', [
                                    'options'           => [
                                        'name'          => 'email',
                                        'label'         => trans('admin::inputs.base_crud.email.label'),
                                        'placeholder'   => trans('admin::inputs.base_crud.email.placeholder'),
                                        'required'      => true,
                                        'emailMask'     => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                        'value'         => app('admin')->email,
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.phone', [
                                    'options'               => [
                                        'name'              => 'phone',
                                        'fullNumberName'    => 'phone_number',
                                        'label'             => trans('admin::inputs.admin_crud.phone_number.label'),
                                        'required'          => true,
                                        'view'              => 'INLINE',
                                        'input_size'        => 'col-lg-8',
                                        'label_size'        => 'col-lg-4',
                                        'value'             => app('admin')->phone_number,
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.select', [
                                    'options'           => [
                                        'name'          => 'lang',
                                        'label'         => trans('admin::inputs.base_crud.lang.label'),
                                        'placeholder'   => trans('admin::inputs.base_crud.lang.placeholder'),
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                        'data'          => $_ALL_LOCALE_,
                                        'text'          => fn($key, $value) => $value['native'],
                                        'values'        => fn($key, $value) => $key,
                                        'select'        => fn($key, $value, $selected) => $key == $selected,
                                        'value'         => app('admin')->lang,
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.password', [
                                    'options'           => [
                                        'name'          => 'current_password',
                                        'label'         => trans('admin::inputs.base_crud.current_password.label'),
                                        'placeholder'   => trans('admin::inputs.base_crud.current_password.placeholder'),
                                        'help'          => trans('admin::inputs.base_crud.current_password.help'),
                                        'required'      => true,
                                        'highlight'     => false,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                    ]
                                ])
                            </div>

                            <div class="row mb-3">
                                @include('admin::components.inputs.password', [
                                    'options'           => [
                                        'name'          => 'password',
                                        'label'         => trans('admin::inputs.base_crud.password.label'),
                                        'placeholder'   => trans('admin::inputs.base_crud.password.placeholder'),
                                        'help'          => trans('admin::inputs.base_crud.password.help'),
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                    ]
                                ])
                            </div>

                            <div class="row mb-6">
                                @include('admin::components.inputs.password', [
                                    'options'           => [
                                        'name'          => 'password_confirmation',
                                        'label'         => trans('admin::inputs.base_crud.password_confirmation.label'),
                                        'placeholder'   => trans('admin::inputs.base_crud.password_confirmation.placeholder'),
                                        'help'          => trans('admin::inputs.base_crud.password_confirmation.help'),
                                        'highlight'     => false,
                                        'required'      => true,
                                        'view'          => 'INLINE',
                                        'input_size'    => 'col-lg-8',
                                        'label_size'    => 'col-lg-4',
                                    ]
                                ])
                            </div>
                        @endslot

                    @endcomponent
                </div>
            </div>
        </div>
        <!--end::Basic info-->
    </div>
@endsection
