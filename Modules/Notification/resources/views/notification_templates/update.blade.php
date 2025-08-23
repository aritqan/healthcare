@extends('admin::layouts.master', ['title' => trans('admin::cruds.notification_templates.edit')])

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
                            @lang('admin::cruds.notification_templates.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('notification.notification_templates.postUpdate', [$model->id]),
                                    'method'    => 'PUT',
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'name',
                                                'readonly'      => true,
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.name.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.name.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.name.subText'),
                                                'class'         => 'to-lower space-to-underscore only-english-letters',
                                                'value'         => $model->name,
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
                                                'value'         => $model->channels,
                                                'select'        => function($key, $value, $selected) {
                                                    return in_array($value, $selected);
                                                },
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'variables',
                                                'readonly'      => true,
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.notification_template_crud.variables.label'),
                                                'placeholder'   => trans('admin::inputs.notification_template_crud.variables.placeholder'),
                                                'help'          => trans('admin::inputs.notification_template_crud.variables.subText'),
                                                'class'         => 'to-lower space-to-comma only-english-letters-comma-underscore',
                                                'value'         => implode(',', $model->variables),
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
                                                'value'         => $model->priority,
                                                'select'        => fn($key, $value, $selected) => $key == $selected,
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
                                                    'value'         => fn($model, $locale) => $model->smartTrans('title', $locale, true),
                                                ],
                                                'description'       => [
                                                    'show'          => true,
                                                    'required'      => false,
                                                    'value'         => fn($model, $locale) => $model->smartTrans('description', $locale, true),
                                                ],
                                                'short_description' => [
                                                    'name'          => 'short_template',
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.short_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.short_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.short_template.subText'),
                                                    'value'         => fn($model, $locale) => $model->smartTrans('short_template', $locale, true),
                                                ],
                                                'long_description'  => [
                                                    'name'          => 'long_template',
                                                    'show'          => true,
                                                    'required'      => true,
                                                    'value'         => null,
                                                    'label'         => trans('admin::inputs.notification_template_crud.long_template.label'),
                                                    'placeholder'   => trans('admin::inputs.notification_template_crud.long_template.placeholder'),
                                                    'subText'       => trans('admin::inputs.notification_template_crud.long_template.subText'),
                                                    'value'         => fn($model, $locale) => $model->smartTrans('long_template', $locale, true),
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
