@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.content_tag_management.content_tags'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.content_tag_management.content_tags'),
                'actions'           => [
                    'filter'        => false,
                    'search'        => true,
                ],
            ]
        ])

        @slot('filterContent')
            {{-- Filter Contetn --}}
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
                            'title' => trans('admin::datatable.content_tags.list_title'),
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
                            'route'             => route('cms.content_tags.create'),
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
                            'url'           => route('cms.content_tags.datatable'),
                            'withCheckbox'  => true,
                            'filter'        => true,
                            'withCreatedAt' => true,
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        <th> @lang('admin::datatable.base_columns.title') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            // Datatable Columns
                            {
                                data: 'title',
                                name: 'title',
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

@push('script')

@endpush
