@extends('admin::layouts.master', ['title' => trans('admin::cruds.notification_templates.add')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.notification_template_management.notification_templates'),
            'backUrl'           => route('notification.notification_templates.index'),
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
                            @lang('admin::cruds.notification_templates.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('notification.notification_templates.postCreate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-5">
                                        @include('admin::components.alerts.alert', [
                                            'options'           => [
                                                'color'         => 'primary',
                                                'description'   => trans('admin::strings.variables_and_name_cant_edit'),
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'name',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.name.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.name.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.name.subText'),
                                                'class'         => 'to-lower space-to-underscore only-english-letters',
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'channels',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.channels.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.channels.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.channels.help'),
                                                'data'          => $notificationChannels,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $value,
                                                'clearable'     => true,
                                                'multiple'      => true,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'variables',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.variables.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.variables.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.variables.subText'),
                                                'class'         => 'to-lower space-to-comma only-english-letters-comma-underscore',
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'priority',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.priority.label'),
                                                'subText'       => trans('admin::inputs.notification_template_crud.priority.help'),
                                                'data'          => $notificationPriorities,
                                                'text'          => fn($key, $value) => $value,
                                                'values'        => fn($key, $value) => $key,
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-12">
                                        @include('admin::components.other.lang_crud', [
                                            'options'               => [
                                                'title'             => [
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                ],
                                                'description'       => [
                                                    'show'          => true,
                                                    'required'      => false,
                                                    'value'         => null,
                                                ],
                                                'short_description' => [
                                                    'name'          => 'short_template',
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.short_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.short_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.short_template.subText'),
                                                ],
                                                'long_description'  => [
                                                    'name'          => 'long_template',
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.long_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.long_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.long_template.subText'),
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
