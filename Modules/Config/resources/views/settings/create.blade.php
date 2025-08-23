@extends('admin::layouts.master', ['title' => trans('admin::cruds.settings.add')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.setting_management.settings'),
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
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.settings.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'                   => [
                                    'isAjax'                => true,
                                    'method'                => 'POST',
                                    'action'                => route('config.settings.postCreate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-5">
                                        @include('admin::components.alerts.alert', [
                                            'options'           => [
                                                'color'         => 'primary',
                                                'description'   => trans('admin::strings.add_setting_note', ['url' => route('config.settings.index')]),
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'key',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.key.label'),
                                                'placeholder'   => trans('admin::inputs.setting_crud.key.placeholder'),
                                                'help'          => trans('admin::inputs.setting_crud.key.help'),
                                                'class'         => 'to-lower space-to-underscore only-english-letters-and-numbers',
                                            ]
                                        ])
                                    </div>

                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'type'          => 'number',
                                                'name'          => 'order',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.order.label'),
                                                'placeholder'   => trans('admin::inputs.setting_crud.order.placeholder'),
                                                'help'          => trans('admin::inputs.setting_crud.order.help'),
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'group',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.group.label'),
                                                'help'          => trans('admin::inputs.setting_crud.group.help'),
                                                'data'          => $groups,
                                                'text'          => function($key, $value) {return $value;},
                                                'values'        => function($key, $value) {return $key;},
                                            ]
                                        ])
                                    </div>

                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'type',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.type.label'),
                                                'help'          => trans('admin::inputs.setting_crud.type.help'),
                                                'data'          => $types,
                                                'text'          => function($key, $value) {return $value;},
                                                'values'        => function($key, $value) {return $value;},
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'is_required',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.is_required.label'),
                                                'help'          => trans('admin::inputs.setting_crud.is_required.help'),
                                                'data'          => YES_NO_DATA,
                                                'text'          => function($key, $value) {return trans('base::base.yes_no.' . $value['text']);},
                                                'values'        => function($key, $value) {return $value['value'];},
                                            ]
                                        ])
                                    </div>

                                    <div class="col-md-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'translatable',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.setting_crud.translatable.label'),
                                                'help'          => trans('admin::inputs.setting_crud.translatable.help'),
                                                'data'          => YES_NO_DATA,
                                                'text'          => function($key, $value) {return trans('base::base.yes_no.' . $value['text']);},
                                                'values'        => function($key, $value) {return $value['value'];},
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group d-none">
                                        @include('admin::components.inputs.textarea', [
                                            'options'           => [
                                                'name'          => 'options',
                                                'label'         => trans('admin::inputs.setting_crud.options.label'),
                                                'placeholder'   => trans('admin::inputs.setting_crud.options.placeholder'),
                                                'help'          => trans('admin::inputs.setting_crud.options.help'),
                                                'value'         => "{}",
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'           => [
                                                'title'         => [
                                                    'show'      => true,
                                                    'required'  => true,
                                                    'value'     => null,
                                                ],
                                                'description'   => [
                                                    'show'      => true,
                                                    'required'  => false,
                                                    'value'     => null,
                                                ],
                                            ]
                                        ])
                                    </div>
                                </div>

                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="col-lg-3"></div>
        </div>
    </div>
@endsection

@once
    @push('script')
        <script>
            $(document).ready(function() {
                // Show/Hide Options Field
                $('select[name="type"]').on('change', function() {
                    var type = $(this).val();
                    var optionsField = $('textarea[name="options"]').closest('.form-group');

                    if (type === 'select') {
                        optionsField.removeClass('d-none');
                    } else {
                        optionsField.addClass('d-none');
                    }
                });
            });
        </script>
    @endpush
@endonce

