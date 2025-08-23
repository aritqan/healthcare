@extends('admin::layouts.master', ['title' => trans('admin::dashboard.aside_menu.user_management.permissions')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.user_management.permissions'),
            'actions'           => [
                'filter'        => false,
                'search'        => true,
            ],
        ]
    ])
@endsection

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card shadow-sm ">

            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    @include('admin::components.datatables.header.title', [
                        'options'               => [
                            'withSwitchArchive' => false,
                            'title'             => trans('admin::datatable.permissions.list_title'),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'       => [
                            'role'      => $createPermission,
                            'route'     => route('permission.permissions.create'),
                        ]
                    ])
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body  py-4">
                @component('admin::components.datatables.table', [
                        'options'           => [
                            'url'           => route('permission.permissions.datatable'),
                        ]
                    ])
                    @slot('columns')
                        <th> @lang('admin::datatable.roles.columns.code') </th>
                        <th> @lang('admin::datatable.base_columns.title') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data        : 'code',
                                name        : 'code',
                            },
                            {
                                data        : 'title',
                                name        : 'translation.title',
                                orderable   : false,
                                searchable  : false,
                            },
                        @endslot
                    </script>

                @endcomponent
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection
