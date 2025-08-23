@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.country_management.countries')
])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.country_management.countries'),
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
                        'options'   => [
                            'role'  => $viewTrashPermission,
                            'title' => trans('admin::datatable.countries.list_title'),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-reverse">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'               => [
                            'withAddButton'     => false,
                            'multiActions'      => $bulkActionDropdown,
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
                            'url'           => route('zms.countries.datatable'),
                            'withCheckbox'  => true,
                            'orderType'     => 'asc',
                        ]
                    ])
                    @slot('columns')
                        <th> @lang('admin::datatable.base_columns.name') </th>
                        <th> @lang('admin::datatable.countries.columns.phone_code') </th>
                        <th> @lang('admin::datatable.countries.columns.currency') </th>
                        <th> @lang('admin::datatable.countries.columns.states_count') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data        : 'name',
                                name        : 'translations.name',
                                orderable   : false,
                            },
                            {
                                data        : 'phone_code',
                                name        : 'phone_code',
                            },
                            {
                                data        : 'currency',
                                name        : 'currency',
                            },
                            {
                                data        : 'states_count',
                                name        : 'states_count',
                            },
                        @endslot
                    </script>

                @endcomponent
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection
