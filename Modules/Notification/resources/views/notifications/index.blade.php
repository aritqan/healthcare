@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.notification_management.notifications'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.notification_management.notifications'),
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
                        'name'          => 'added_by',
                        'label'         => trans('admin::inputs.notification_crud.added_by.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $notificationAddedBy,
                        'text'          => fn($key, $value) => $value,
                        'values'        => fn($key, $value) => $key,
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'group',
                        'label'         => trans('admin::inputs.notification_crud.groups.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'data'          => $systemMainRoles,
                        'text'          => function($key, $value) {return trans('permission::seeder.main_roles.' . $value);},
                        'values'        => function($key, $value) {return $key;},
                        'clearable'     => true,
                    ]
                ])
            </div>
            <div class="mb-5 user-list d-none">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'id'            => 'users_id',
                        'name'          => 'user_id',
                        'label'         => trans('admin::datatable.admins.columns.user'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'url'           => route('auth.users.ajaxList'),
                        'clearable'     => true,
                        'isAjax'        => true,
                    ]
                ])
            </div>
            <div class="mb-5 admin-list d-none">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'id'            => 'admins_id',
                        'name'          => 'admin_id',
                        'label'         => trans('admin::inputs.notification_crud.admins.label'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'url'           => route('admin.admins.ajaxList'),
                        'clearable'     => true,
                        'isAjax'        => true,
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
                        'options'               => [
                            'withSwitchArchive' => false,
                            'title'             => trans('admin::datatable.notifications.list_title'),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-reverse">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'               => [
                            'role'              => $createPermission,
                            'route'             => route('notification.notifications.create'),
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
                            'url'           => route('notification.notifications.datatable'),
                            'withCheckbox'  => false,
                            'withAction'    => false,
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        <th> @lang('admin::datatable.notifications.columns.recipient') </th>
                        <th> @lang('admin::datatable.notifications.columns.added_by') </th>
                        <th> @lang('admin::datatable.base_columns.title') </th>
                        <th> @lang('admin::datatable.notification_templates.columns.channels') </th>
                        <th> @lang('admin::datatable.notifications.columns.sent_at') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data: 'notifiable',
                                name: 'notifiable',
                                searchable: false,
                                orderable: false,
                                render: function (data, type, row, meta) {
                                    if (data !== null) {
                                        return `
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">${data.full_name}</a>
                                                    <span class="text-muted">${data.email}</span>
                                                </div>
                                            </div>
                                        `;
                                    } else {
                                        return `
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="javascript:;" class="text-dark fw-bolder text-hover-primary mb-1">Topic</a>
                                                    <span class="text-muted">${row.topic}</span>
                                                </div>
                                            </div>
                                        `;
                                    }
                                }
                            },
                            {
                                data: 'added_by',
                                name: 'added_by',
                                searchable: false,
                                orderable: false,
                            },
                            {
                                data: 'title',
                                name: 'title',
                                searchable: false,
                                orderable: false,
                            },
                            {
                                data: 'channels',
                                name: 'channels',
                                searchable: false,
                                orderable: false,
                            },
                            {
                                data: 'created_at_format',
                                name: 'created_at_format',
                                searchable: false,
                                orderable: false,
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
    <script>
        $(document).ready(function() {
            $('select[name="group"]').on('change', function() {
                let group = $(this).val();
                if (group === 'users') {
                    $('.user-list').removeClass('d-none');
                    $('.admin-list').addClass('d-none');
                    // Reset the admin list
                    $('#admins_id').val(null).trigger('change');
                } else if (group === 'admins') {
                    $('.user-list').addClass('d-none');
                    $('.admin-list').removeClass('d-none');
                    // Reset the user list
                    $('#users_id').val(null).trigger('change');
                } else {
                    $('.user-list').addClass('d-none');
                    $('.admin-list').addClass('d-none');
                    $('#users_id').val(null).trigger('change');
                    $('#admins_id').val(null).trigger('change');
                }
            });

            // when clearers are clicked, reset the select2
            $('select[name="group"]').on('select2:unselect', function() {
                $('.user-list').addClass('d-none');
                $('.admin-list').addClass('d-none');
                $('#users_id').val(null).trigger('change');
                $('#admins_id').val(null).trigger('change');
            });
        });
    </script>
@endpush
