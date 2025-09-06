@extends('admin::auth.layouts.master')

@section('title', trans('admin::auth.change_password_page.meta_title'))

@push('style')
    <style>
        @media (max-width: 991px) {
            #login {
                width: 500px !important;
            }
        }
        @media (max-width: 600px) {
            #login {
                width: 350px !important;
            }

            #img-logo {
                width: 150px !important;
            }
        }
    </style>
@endpush

@section('mainContent')
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid">
        <div class="">
            <a href="javascript:;" class="py-9 mb-5">
                @include('admin::components.other.image', [
                    'options' => [
                        'id'    => 'img-logo',
                        'class' => 'w-200px',
                        'src'   => config('admin.frontend.logos.auth_page'),
                        'alt'   => 'Logo',
                    ]
                ])
            </a>
        </div>

        <!--begin::Wrapper-->
        <div class="w-lg-500px p-10 p-lg-15 mx-auto">
            <!--begin::Form-->
            <form class="form w-100" id="login" action="{{route('admin.auth.updatePassword')}}" method="POST">
                @csrf
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">
                        @lang('admin::auth.change_password_page.change_password')
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->

                <div class="fv-row mb-10">
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

                <div class="row mb-10">
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

                <!--begin::Actions-->
                <div class="text-center">
                    @include('admin::components.buttons.submit', [
                        'options'               => [
                            'id'                => 'reset_password_submit',
                            'label'             => trans('admin::auth.change_password_page.reset_password'),
                            'progress_label'    => trans('admin::base.please_wait_dot'),
                        ]
                    ])
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
@endsection
