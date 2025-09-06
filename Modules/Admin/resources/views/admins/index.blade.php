@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.user_management.' . $roleName)
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.user_management.' . $roleName),
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
                        'name'          => 'role',
                        'label'         => trans('admin::datatable.admins.columns.role'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'clearable'     => true,
                        'searchable'    => true,
                        'data'          => $roles->map(fn($role) => $role->formAjaxArray() ),
                        'text'          => function ($key, $role) { return $role['title']; },
                        'values'        => function ($key, $role) { return $role['id']; },
                    ]
                ])
            </div>

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

            <div class="mb-5">
                @include('admin::components.inputs.select', [
                    'options'           => [
                        'name'          => 'gender',
                        'label'         => trans('admin::datatable.base_columns.gender'),
                        'placeholder'   => trans('admin::base.all_results'),
                        'clearable'     => true,
                        'data'          => $genderTypes,
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
                            'title' => trans('admin::datatable.'.$roleName.'.list_title'),
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
                            'route'             => route('admin.admins.create', ['role' => e(request('role'))]),
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
                            'url'           => route('admin.admins.datatable', ['role' => e(request('role'))]),
                            'withCheckbox'  => true,
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        <th style="width: 25%"> @lang('admin::datatable.admins.columns.user') </th>
                        <th> @lang('admin::datatable.base_columns.phone_number') </th>
                        <th> @lang('admin::datatable.base_columns.username') </th>
                        <th style="width: 10%"> @lang('admin::datatable.base_columns.status') </th>
                        @if(checkIfRoleStateRequired(e(request('role'))))
                            <th> @lang('admin::inputs.clinic_crud.state_id.label') </th>
                        @endif
                        @if(checkIfRoleMedicalSpecialityRequired(e(request('role'))))
                            <th> @lang('admin::inputs.doctor_crud.medical_speciality.label') </th>
                        @endif
                        @if(checkIfRoleCanSelectMedicalFacility(app('admin')) && checkIfRoleMedicalFacilityRequired(e(request('role'))))
                            <th>@lang('admin::inputs.'.strtolower(e(request('role'))).'_crud.medical_facility.label')</th>
                        @endif
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
                                                        <img src="${row.avatar_url}" alt="${row.full_name}" class="w-100" onerror="this.onerror=null; this.src='{{ asset('images/default/avatars/mr_admin.png') }}';"/>
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
                                data : 'username',
                                name : 'username',
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
                            @if(checkIfRoleStateRequired(e(request('role'))))
                            {
                                data : 'profile',
                                name : 'profile',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="btn btn-sm btn-font-sm btn-label-primary text-center w-100">${row.profile.state_name}</span>
                                    `;
                                }
                            },
                            @endif
                            @if(checkIfRoleMedicalSpecialityRequired(e(request('role'))))
                            {
                                data : 'profile',
                                name : 'profile',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="btn btn-sm btn-font-sm btn-label-info text-center w-100">${row.profile.medical_specialty_name}</span>
                                    `;
                                }
                            },
                            @endif
                            @if(checkIfRoleCanSelectMedicalFacility(app('admin')) && checkIfRoleMedicalFacilityRequired(e(request('role'))))
                            {
                                data : 'medical_facility_name',
                                name : 'medical_facility_name',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `
                                        <span class="btn btn-sm btn-font-sm btn-label-info text-center w-100">${data}</span>
                                    `;
                                }
                            },
                            @endif
                        @endslot
                    </script>

                @endcomponent
            </div>
            <!--end::Card body-->
        </div>
    </div>
@endsection
