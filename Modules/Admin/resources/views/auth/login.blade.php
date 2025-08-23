@extends('admin::auth.layouts.master')

@section('title', trans('admin::auth.login_page.meta_title'))

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
            <form class="form w-100" id="login" action="{{route('admin.auth.authenticate')}}" method="POST">
                @csrf
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">
                        @lang('admin::auth.login_page.sign_in_to_account')
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->

                <div class="fv-row mb-10">
                    @include('admin::components.inputs.text', [
                        'options'           => [
                            'name'          => 'email',
                            'label'         => trans('admin::inputs.base_crud.email.label'),
                            'placeholder'   => trans('admin::inputs.base_crud.email.placeholder'),
                        ]
                    ])
                </div>

                <div class="row mb-10">
                    @include('admin::components.inputs.password', [
                        'options'           => [
                            'name'          => 'password',
                            'label'         => trans('admin::inputs.base_crud.password.label'),
                            'placeholder'   => trans('admin::inputs.base_crud.password.placeholder'),
                            'highlight'     => false,
                        ]
                    ])
                </div>

                <!--begin::Actions-->
                <div class="text-center">
                    @include('admin::components.buttons.submit', [
                        'options'               => [
                            'id'                => 'login_submit',
                            'label'             => trans('admin::auth.login_page.sign_in'),
                            'progress_label'    => trans('admin::base.verifying'),
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
