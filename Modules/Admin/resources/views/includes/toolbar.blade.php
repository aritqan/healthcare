@isset($options)
    @php
        $VALUE = array_merge([
            'title'             => trans('admin::dashboard.aside_menu.home'),
            'withBreadcrumb'    => true,
            'backUrl'           => url()->previous(),
            'backTitle'         => trans('admin::base.back'),
            'saveTitle'         => trans('admin::base.save'),
            'filterModalId'     => 'data-table-filter',
            'actions'           => [
                'filter'        => false,
                'search'        => false,
                'save'          => false,
                'back'          => false,
            ],
        ], $options);

    @endphp

    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{ $VALUE['title'] }}

                    <!--begin::Breadcrumb-->
                    @if($VALUE['withBreadcrumb'])
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-300 border-start mx-4"></span>
                        <!--end::Separator-->
                        @include('admin::includes.breadcrumb')
                    @endif
					<!--end::Breadcrumb-->
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->

            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                @if (isset($VALUE['actions']['filter']) && $VALUE['actions']['filter'])
                    <div class="m-0">
                        @component('admin::components.other.hyperlink', [
                                'options'                       => [
                                    'id'                        => 'filter-dropdown-toggle',
                                    'class'                     => 'btn btn-sm btn-flex btn-light-primary fw-bolder',
                                    'attributes'                => [
                                        'data-kt-menu-trigger'  => 'click',
                                        'data-kt-menu-placement'=> 'bottom-end',
                                    ],
                                ]
                            ])
                            {!! config('admin.svgs.filter') !!}
                            @lang('admin::base.filter')
                        @endcomponent

                        @component('admin::components.datatables.filter', [
                                'options'           => [
                                    'id'            => $VALUE['filterModalId'],
                                ]
                            ])

                            @isset($filterContent)
                                @slot('filterContent')
                                    {!! $filterContent !!}
                                @endslot
                            @endisset
                        @endcomponent
                    </div>
                @endif

                @if (isset($VALUE['actions']['search']) && $VALUE['actions']['search'])
                    <div class="d-flex align-items-center position-relative m-0">
                        @include('admin::components.inputs.search', [
                            'options'           => [
                                'id'            => 'data-table-search',
                                'name'          => 'search',
                                'placeholder'   => trans('admin::base.search'),
                            ]
                        ])
                    </div>
                @endif

                @if (isset($VALUE['actions']['save']) && $VALUE['actions']['save'])
                    <div class="m-0">
                        @component('admin::components.buttons.submit', [
                                'options'           => [
                                    'id'            => 'form-submit-ajax',
                                    'label'         => $VALUE['saveTitle'],
                                    'class'         => 'btn-primary'
                                ]
                            ])
                        @endcomponent
                    </div>
                @endif

                @if (isset($VALUE['actions']['back']) && $VALUE['actions']['back'])
                    <div class="m-0">
                        @component('admin::components.other.hyperlink', [
                            'options'           => [
                                'id'            => 'form-back-button',
                                'title'         => $VALUE['backTitle'],
                                'href'          => $VALUE['backUrl'],
                                'class'         => 'btn btn-light-danger'
                            ]
                        ])
                        @endcomponent
                    </div>
                @endif

                @isset($otherActions)
                    {!! $otherActions !!}
                @endisset
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endisset

