@extends('admin::layouts.master', ['title' => trans('admin::dashboard.aside_menu.user_management.roles')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.user_management.roles'),
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
                        'options'                   => [
                            'withSwitchArchive'     => false,
                            'title'                 => trans('admin::datatable.roles.list_title'),
                        ]
                    ])
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    @include('admin::components.datatables.header.toolbar', [
                        'options'       => [
                            'role'      => $createPermission,
                            'route'     => route('permission.roles.create'),
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
                            'url'           => route('permission.roles.datatable'),
                        ]
                    ])
                    @slot('columns')
                        <th> @lang('admin::datatable.roles.columns.code') </th>
                        <th> @lang('admin::datatable.base_columns.title') </th>
                        <th> @lang('admin::datatable.base_columns.description') </th>
                        <th> @lang('admin::datatable.roles.columns.permissions') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data        : 'name',
                                name        : 'name',
                            },
                            {
                                data        : 'title',
                                name        : 'translations.title',
                                orderable   : false,
                            },
                            {
                                data        : 'description',
                                name        : 'translations.description',
                                orderable   : false,
                            },
                            {
                                data        : 'permissions',
                                name        : 'permissions',
                                orderable   : false,
                                render      : function (data, type, row) {

                                    if (data.length === 0) {
                                        return '<span class="fw-bold">{{ trans('admin::strings.no_permissions_for_this_role') }}</span>';
                                    }

                                    let output = '<div class="kt_docs_jstree_"><ul>';

                                    $.each(data, function (index, group) {
                                        output += `<li data-jstree='{ "icon": "${group.icon } text-primary" }'><span class="fw-bold text-primary">&nbsp;${group.title}</span><ul>`;
                                        $.each(group.permissions, function (index, permission) {
                                            output += `<li data-jstree='{ "type": "check" }'>${permission.title}</li>`;
                                        });
                                        output += '</ul></li>';
                                    });

                                    output += '</ul></div>';

                                    return output;
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
