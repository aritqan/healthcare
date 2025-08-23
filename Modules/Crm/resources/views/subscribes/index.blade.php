@extends('admin::layouts.master', [
    'title' => trans('admin::dashboard.aside_menu.subscribe_management.subscribes'),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.subscribe_management.subscribes'),
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
                            'title' => trans('admin::datatable.subscribes.list_title'),
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
                            'url'               => route('crm.subscribes.datatable'),
                            'withCheckbox'      => true,
                            'withCreatedAt'     => true,
                            'createdAtColumn'   => trans('admin::datatable.subscribes.columns.subscription_date'),
                            'filter'            => true,
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        <th> @lang('admin::datatable.base_columns.email') </th>
                    @endslot

                    <script>
                        @slot('jsColumns')
                            // Datatable Columns
                            {
                                data: 'email',
                                name: 'email',
                                orderable: true,
                                searchable: true,
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
