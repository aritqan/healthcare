@extends('admin::layouts.master', [
    'title' => trans('admin::cruds.countries.edit')
])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.country_management.countries'),
            'backUrl'           => route('zms.countries.index'),
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
                            @lang('admin::cruds.countries.edit')
                        </h3>
                    </div>

                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'method'    => 'PUT',
                                    'action'    => route('zms.countries.postUpdate', $model->id),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
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
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'phone_code',
                                                'required'      => true,
                                                'isPhone'       => true,
                                                'maxlength'     => 10,
                                                'label'         => trans('admin::inputs.country_crud.phone_code.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.phone_code.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.phone_code.help'),
                                                'value'         => $model->phone_code,
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'iso2',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.iso2.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.iso2.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.iso2.help'),
                                                'class'         => 'to-upper only-english-letters',
                                                'maxlength'     => 2,
                                                'value'         => $model->iso2,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'iso3',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.iso3.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.iso3.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.iso3.help'),
                                                'maxlength'     => 3,
                                                'class'         => 'to-upper only-english-letters',
                                                'value'         => $model->iso3,
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'currency',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.currency.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.currency.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.currency.help'),
                                                'value'         => $model->currency,
                                            ]
                                        ])
                                    </div>
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'currency_symbol',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.country_crud.currency_symbol.label'),
                                                'placeholder'   => trans('admin::inputs.country_crud.currency_symbol.placeholder'),
                                                'subText'       => trans('admin::inputs.country_crud.currency_symbol.help'),
                                                'value'         => $model->currency_symbol,
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
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

                                    <div class="col-lg-6 col-12 mb-10 form-group">
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
                                    <div class="col-12">
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
                                </div>

                                <div class="separator separator-dashed my-5"></div>
                            @endslot
                        @endcomponent

                        <div class="row">
                            <div class="col-12 mb-10">
                                @include('admin::components.buttons.button', [
                                    'options'           => [
                                        'id'            => 'load_states',
                                        'targetModal'   => false,
                                        'title'         => trans('admin::strings.load_states'),
                                    ]
                                ])
                            </div>
                        </div>

                        <div class="row states-block d-none">
                            <div class="col-12">
                                @component('admin::components.datatables.table', [
                                        'options'           => [
                                            'id'            => 'states-table',
                                            'search'        => true,
                                        ],
                                    ])

                                    @slot('columns')
                                        <th> @lang('admin::datatable.base_columns.name') </th>
                                        <th> @lang('admin::datatable.countries.columns.cities_count') </th>
                                    @endslot

                                    <script>
                                        @slot('jsColumns')
                                            {
                                                data        : 'name',
                                                name        : 'translations.name',
                                                orderable   : false,
                                            },
                                            {
                                                data        : 'cities_count',
                                                name        : 'cities_count',
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
                $('#load_states').on('click', function () {
                    $('.states-block').removeClass('d-none');
                    $('#states-table').DataTable().ajax.url("{{ route('zms.states.datatable', ['country_id' => $model->id]) }}").load();
                    $(this).prop('disabled', true);
                });
            });
        </script>
    @endpush
@endonce
