@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.activity_log_management.activity_logs'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.activity_log_management.activity_logs'),
                'actions'           => [
                    'filter'        => false,
                    'search'        => false,
                ],
            ]
        ])

        @slot('filterContent')
        @endslot
    @endcomponent
@endsection

@push('style')
    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            font-weight: bold;
        }
        .alert {
            background-color: #f9f9f9;
        }
        .alert .fa-pen {
            font-size: 1.2rem;
        }

    </style>
@endpush

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card shadow-sm ">

            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    @include('admin::components.datatables.header.title', [
                        'options'                   => [
                            'withSwitchArchive'     => false,
                            'title'                 => trans('admin::dashboard.aside_menu.activity_log_management.activity_log_type', [
                                'type'              => $modelName,
                                'id'                => $modelId,
                            ]),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-reverse">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'               => [
                            'withAddButton'     => false,
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
                            'url'               => route('log.activity_log.datatable', [
                                'type'          => $modelType,
                                'model'         => $modelId,
                            ]),
                            'withCheckbox'      => false,
                            'filter'            => false,
                            'withCreatedAt'     => true,
                            'createdAtColumn'   => trans('admin::datatable.activity_logs.columns.action_date')
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        <th style="width: 40%"> @lang('admin::datatable.activity_logs.columns.user_made_action') </th>
                        <th> @lang('admin::datatable.activity_logs.columns.event') </th>
                        <th> @lang('admin::datatable.base_columns.ip_address') </th>
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
                                        return `--`;
                                    }
                                    return `
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">
                                                    ${data.full_name} - ${data.email}
                                                </a>
                                                <span class="text-muted">@lang('admin::datatable.activity_logs.columns.user_type') : ${row.user_type_name}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                            },
                            {
                                data: 'event_format',
                                name: 'event_format',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="btn btn-sm btn-font-sm btn-label-${data.color} text-center w-100">${data.label}</span>
                                    `;
                                }
                            },
                            {
                                data: 'ip_address',
                                name: 'ip_address',
                                orderable: false,
                                searchable: false,
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
            'id'    => 'activityLogViewModal',
            'title' => trans('log::strings.view_modal_title.activity_log'),
        ]
    ])
@endsection

@push('script')

@endpush
