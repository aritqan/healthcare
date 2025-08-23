@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.content_category_management.content_categories'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.content_category_management.content_categories'),
                'actions'           => [
                    'filter'        => false,
                    'search'        => true,
                ],
            ]
        ])
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
                            'title' => trans('admin::datatable.content_categories.list_title'),
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
                            'route'             => route('cms.content_categories.create'),
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
                            'url'           => route('cms.content_categories.datatable'),
                            'withCheckbox'  => true,
                            'filter'        => true,
                        ]
                    ])
                    @slot('columns')
                        <th style="width:50%"> @lang('admin::datatable.base_columns.title') </th>
                        <th> @lang('admin::datatable.content_categories.columns.parent') </th>
                        <th> @lang('admin::datatable.content_categories.columns.can_be_deleted') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            {
                                data        : 'title',
                                name        : 'title',
                                orderable   : false,
                                searchable  : false,
                            },
                            {
                                data        : 'category_parents_name',
                                name        : 'category_parents_name',
                                orderable   : false,
                                searchable  : false,
                                render: function (data, type, row, meta) {
                                    return `
                                       <span class="btn btn-sm btn-font-sm btn-label-info text-center w-100">
                                            ${data}
                                        </span>
                                    `;
                                }
                            },
                            {
                                data        : 'can_be_deleted_format',
                                name        : 'can_be_deleted_format',
                                orderable   : false,
                                searchable  : false,
                                render: function (data, type, row, meta) {
                                    return `
                                       <span class="btn btn-sm btn-font-sm btn-label-secondary text-center w-50">
                                            ${data}
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

@push('script')

@endpush
