@php
    use Modules\Cms\Models\Content;
@endphp

@extends('admin::layouts.master', [
    'title' => Content::getTypeTitle($type),
])

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => Content::getTypeTitle($type),
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
                            'title' => trans('admin::datatable.contents.' . $type . '.list_title'),
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
                            'route'             => route('cms.contents.create', ['type' => $type]),
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
                            'url'           => route('cms.contents.datatable', ['type' => $type]),
                            'withCheckbox'  => true,
                            'filter'        => true,
                            'withCreatedAt' => true,
                        ]
                    ])
                    @slot('columns')
                        {{-- Datatable Columns --}}
                        @if(Content::typeHasField($type, 'image'))
                            <th style="width: 10%"> @lang('admin::datatable.base_columns.image') </th>
                        @endif
                        @if(Content::typeHasField($type, 'title'))
                            <th style="width: 40%"> @lang('admin::datatable.base_columns.title') </th>
                        @endif
                        @if(Content::typeHasField($type, 'slug'))
                            <th> @lang('admin::datatable.content_categories.columns.slug') </th>
                        @endif
                        @if(Content::typeHasField($type, 'placement'))
                            <th style="width:15%"> @lang('admin::datatable.contents.sliders.columns.placement') </th>
                        @endif
                        @if(Content::typeHasField($type, 'published_at'))
                            <th> @lang('admin::datatable.contents.columns.published_at') </th>
                        @endif
                    @endslot

                    <script>
                        @slot('jsColumns')
                            @if(Content::typeHasField($type, 'image'))
                                {
                                    data        : 'image_url',
                                    name        : 'image_url',
                                    orderable   : false,
                                    searchable  : false,
                                    render      : function (data, type, row, meta) {
                                        return `
                                            <a class="d-block overlay" data-fslightbox="lightbox-basic" href="${row.orginal_image_url}">
                                                <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-contain card-rounded min-h-100px"
                                                    style="background-image:url('${row.image_url}')">
                                                </div>

                                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                    <i class="bi bi-eye-fill text-white fs-3x"></i>
                                                </div>
                                            </a>
                                        `;
                                    }
                                },
                            @endif
                            @if(Content::typeHasField($type, 'title'))
                                {
                                    data: 'title',
                                    name: 'title',
                                    orderable: false,
                                    searchable: false
                                },
                            @endif
                            @if(Content::typeHasField($type, 'slug'))
                                {
                                    data        : 'slug',
                                    name        : 'slug',
                                    orderable   : false,
                                    searchable  : false,
                                },
                            @endif
                            @if(Content::typeHasField($type, 'placement'))
                                {
                                    data        : 'placement',
                                    name        : 'placement',
                                    orderable   : false,
                                    searchable  : false,
                                    render      : function (data, type, row, meta) {
                                        return `
                                        <span class="btn btn-sm btn-font-sm btn-label-info text-center w-100">
                                                ${data}
                                            </span>
                                        `;
                                    }
                                },
                            @endif
                            @if(Content::typeHasField($type, 'published_at'))
                                { data: 'published_at_format', name: 'published_at_format' },
                            @endif
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
