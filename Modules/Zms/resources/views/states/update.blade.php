@extends('admin::layouts.master', [
    'title' => trans('admin::cruds.states.edit')
])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.state_management.states'),
            'backUrl'           => route('zms.countries.update', $model->country_id),
            'backTitle'         => trans('admin::strings.back_to_country_page'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">

            <div class="col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.states.edit')
                        </h3>
                    </div>

                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'method'    => 'PUT',
                                    'action'    => route('zms.states.postUpdate', $model->id),
                                ]
                            ])
                            @slot('fields')
                                <div class="row mb-10">
                                    <div class="col-xl-4 col-lg-12">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'native_name',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.native_name.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.native_name.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.native_name.help'),
                                                'value'         => $model->native_name,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-12">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'number',
                                                'name'          => 'lat',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.lat.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.lat.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.lat.help'),
                                                'inputmode'     => 'decimal',
                                                'step'          => '0.000001',
                                                'min'           => '-90',
                                                'max'           => '90',
                                                'value'         => $model->lat_format,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-12">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'number',
                                                'name'          => 'lng',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.lng.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.lng.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.lng.help'),
                                                'inputmode'     => 'decimal',
                                                'step'          => '0.000001',
                                                'min'           => '-180',
                                                'max'           => '180',
                                                'value'         => $model->lng_format,
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    @include('admin::components.other.lang_crud', [
                                        'options'           => [
                                            'name'          => [
                                                'show'      => true,
                                                'required'  => true,
                                                'value'     => function($model, $locale) {
                                                    return $model->smartTrans('name', $locale, true);
                                                },
                                            ]
                                        ]
                                    ])
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                            @endslot
                        @endcomponent

                        <div class="row mb-10">
                            <div class="col-12">
                                @include('admin::components.buttons.button', [
                                    'options'           => [
                                        'id'            => 'load_cities',
                                        'targetModal'   => false,
                                        'title'         => trans('admin::strings.load_cities'),
                                    ]
                                ])
                            </div>
                        </div>

                        <div class="row cities-block d-none">
                            <div class="col-12">
                                @component('admin::components.datatables.table', [
                                        'options'           => [
                                            'id'            => 'cities-table',
                                            'search'        => true,
                                        ],
                                    ])

                                    @slot('columns')
                                        <th> @lang('admin::datatable.base_columns.name') </th>
                                    @endslot

                                    <script>
                                        @slot('jsColumns')
                                            {
                                                data        : 'name',
                                                name        : 'translations.name',
                                                orderable   : false,
                                            },
                                        @endslot
                                    </script>

                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@once
    @push('script')
        <script>
            $(document).ready(function () {
                $('#load_cities').on('click', function () {
                    $('.cities-block').removeClass('d-none');
                    $('#cities-table').DataTable().ajax.url("{{ route('zms.cities.datatable', ['state_id' => $model->id]) }}").load();
                    $(this).prop('disabled', true);
                });
            });
        </script>
    @endpush
@endonce
