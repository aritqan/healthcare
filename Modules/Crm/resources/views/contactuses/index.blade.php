@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.contactus_management.contactus'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.contactus_management.contactus'),
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
                        'data'          => $contactusRequestStatuses,
                        'text'          => function ($key, $value) { return $value; },
                        'values'        => function ($key, $value) { return $key; },
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
                            'title' => trans('admin::datatable.contactuses.list_title'),
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
                        'options'               => [
                            'url'               => route('crm.contactuses.datatable'),
                            'withCheckbox'      => true,
                            'filter'            => true,
                            'withCreatedAt'     => true,
                            'createdAtColumn'   => trans('admin::datatable.contactuses.columns.submission_date')
                        ]
                    ])
                    @slot('columns')
                        <th> @lang('admin::datatable.base_columns.name') </th>
                        <th> @lang('admin::datatable.base_columns.email') </th>
                        <th> @lang('admin::datatable.base_columns.phone_number') </th>
                        <th> @lang('admin::datatable.base_columns.status') </th>
                        <th style="width: 20%"> @lang('admin::datatable.contactuses.columns.message') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data        : 'full_name',
                                name        : 'full_name',
                                orderable   : false,
                                searchable  : false,
                                render      : function (data, type, row, meta) {
                                    return `
                                        <span class="text-dark">${data}</span>
                                    `;
                                }
                            },
                            {
                                data        : 'email',
                                name        : 'email',
                                orderable   : false,
                                searchable  : false,
                                render      : function (data, type, row, meta) {
                                    return `
                                        <a href="mailto:${data}" class="text-dark">${data}</a>
                                    `;
                                }
                            },
                            {
                                data        : 'phone',
                                name        : 'phone',
                                orderable   : false,
                                searchable  : false,
                                render      : function (data, type, row, meta) {
                                    return isEmpty(data) ? "{{ DEFAULT_PHONE }}" :
                                    `
                                        <a href="tel:${data}" class="text-dark">${data}</a>
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
                                data        : 'message_text',
                                name        : 'message_text',
                                orderable   : false,
                                searchable  : false,
                                render      : function (data, type, row, meta) {
                                    return `
                                        <span class="text-dark">${data}</span>
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

@push('script')

@endpush
