@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.user_management.users')
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.user_management.users'),
                'actions'           => [
                    'filter'        => true,
                    'search'        => true,
                ],
            ]
        ])

        @slot('filterContent')

            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'status',
                        'label'         => trans('admin::datatable.base_columns.status'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'clearable'     => true,
                        'data'          => $adminStatuses,
                        'text'          => function ($key, $value) { return $value; },
                        'values'        => function ($key, $value) { return $key; },
                    ]
                ])
            </div>
        @endslot
    @endcomponent
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
                            'title' => trans('admin::datatable.users.list_title'),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-reverse">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'               => [
                            'role'              => $createPermission,
                            'multiActions'      => $bulkActionDropdown,
                            'route'             => route('auth.users.create'),
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
                            'url'           => route('auth.users.datatable'),
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        <th style="width: 30%"> @lang('admin::datatable.admins.columns.user') </th>
                        <th> @lang('admin::datatable.base_columns.phone_number') </th>
                        <th style="width: 8%"> @lang('admin::datatable.base_columns.status') </th>
                        <th> @lang('admin::datatable.admins.columns.joined_date') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data: 'full_name',
                                name: 'full_name',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="javascript:;">
                                                    <div class="symbol-label">
                                                        <img src="{{ asset('images/default/avatars/user.png') }}" alt="${row.full_name}" class="w-100" />
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">${row.full_name}</a>
                                                <span class="text-muted">${row.email}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                            },
                            {
                                data        : 'phone_number',
                                name        : 'phone_number',
                                orderable   : false,
                                searchable  : false,
                                render      : function (data, type, row, meta) {
                                    return isEmpty(data) ? "{{ DEFAULT_PHONE }}" :
                                    `
                                        <a href="tel:${data}" class="text-dark fw-bolder text-hover-primary">${data}</a>
                                    `;
                                }
                            },
                            {
                                data : 'status_format',
                                name : 'status_format',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="btn btn-sm btn-font-sm btn-label-${data.color} text-center w-100">${data.label}</span>
                                    `;
                                }
                            },
                            {
                                data : 'created_at_format',
                                name : 'created_at_format',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="text-gray-600 fw-semibold">${data}</span>
                                    `;
                                }
                            },
                        @endslot
                    </script>

                @endcomponent
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection
