@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.notification_template_management.notification_templates'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.notification_template_management.notification_templates'),
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
                        'name'          => 'channels',
                        'label'         => trans('admin::inputs.notification_template_crud.channels.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $notificationChannels,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $value,
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'priority',
                        'label'         => trans('admin::inputs.notification_template_crud.priority.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $notificationPriorities,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $key,
                        'clearable'     => true,
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
                            'title' => trans('admin::datatable.notification_templates.list_title'),
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
                            'route'             => route('notification.notification_templates.create'),
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
                            'url'           => route('notification.notification_templates.datatable'),
                            'withCheckbox'  => true,
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        <th> @lang('admin::datatable.base_columns.name') </th>
                        <th> @lang('admin::datatable.base_columns.title') </th>
                        <th> @lang('admin::datatable.notification_templates.columns.channels') </th>
                        <th> @lang('admin::datatable.notification_templates.columns.variables') </th>
                        <th> @lang('admin::datatable.notification_templates.columns.priority') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data: 'name',
                                name: 'name',
                            },
                            {
                                data: 'title',
                                name: 'title',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'channels',
                                name: 'channels',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'variables',
                                name: 'variables',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'priority_format',
                                name: 'priority_format',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                       <span class="btn btn-sm btn-font-sm btn-label-${data.color} text-center w-100">
                                            ${data.label}
                                        </span>
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
