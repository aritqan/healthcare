@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.api_log_management.api_logs'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.api_log_management.api_logs'),
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
                        'name'          => 'service_name',
                        'label'         => trans('admin::inputs.api_log_crud.service_name.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $serviceNames,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $value,
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'method',
                        'label'         => trans('admin::inputs.api_log_crud.method.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => API_METHODS,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $value,
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'status',
                        'label'         => trans('admin::inputs.base_crud.status.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => API_LOG_STATUSES,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $value,
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'status_code',
                        'label'         => trans('admin::inputs.api_log_crud.status_code.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $statusCodes,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $value,
                        'clearable'     => true,
                    ]
                ])
            </div>
        @endslot
    @endcomponent
@endsection

@push('style')

@endpush

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
                            'title' => trans('admin::datatable.api_logs.list_title'),
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
                            'url'           => route('log.api_logs.datatable'),
                            'withCheckbox'  => true,
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        <th> @lang('admin::datatable.admins.columns.user') </th>
                        <th> @lang('admin::datatable.api_logs.columns.service_name') </th>
                        <th> @lang('admin::datatable.api_logs.columns.method') </th>
                        <th> @lang('admin::datatable.api_logs.columns.endpoint') </th>
                        <th> @lang('admin::datatable.base_columns.status') </th>
                        <th> @lang('admin::datatable.api_logs.columns.status_code') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            // Datatable Columns
                            {
                                data: 'user',
                                name: 'user',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    if(!data) {
                                        return `
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="javascript:;">
                                                        <div class="symbol-label">
                                                            <img src="{{ asset('images/default/system.png') }}" alt="System" class="w-100"/>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">{{ trans('log::strings.system') }}</a>
                                                    <span class="text-muted text-hover-primary"></span>
                                                </div>
                                            </div>`;
                                    }
                                    return `
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="javascript:;">
                                                    <div class="symbol-label">
                                                        <img src="${data.avatar_url}" alt="${data.full_name}" class="w-100" onerror="this.onerror=null; this.src='{{ asset('images/default/avatars/mr_admin.png') }}';"/>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">${data.full_name}</a>
                                                <span class="text-muted">${data.email}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                            },
                            {
                                data: 'service_name_format',
                                name: 'service_name_format',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'method',
                                name: 'method',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'endpoint_format',
                                name: 'endpoint_format',
                                orderable: false,
                                searchable: false,
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
                                data: 'status_code',
                                name: 'status_code',
                            },
                        @endslot
                    </script>

                @endcomponent
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection

@section('modal')
    @include('admin::components.modals.view_modal', [
        'options'   => [
            'id'    => 'apiLogViewModal',
            'title' => trans('log::strings.view_modal_title.api_log'),
        ]
    ])
@endsection

@push('script')

@endpush
