@extends('admin::layouts.master', ['title' => trans('admin::cruds.contactuses.view')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.contactus_management.contactus'),
            'backUrl'           => route('crm.contactuses.index'),
            'saveTitle'         => trans('admin::base.reply'),
            'actions'           => [
                'save'          => $model->canReply() ? true : false,
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
                            @lang('admin::cruds.contactuses.view')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('crm.contactuses.sendReply', [$model->id]),
                                    'method'    => 'POST',
                                ]
                            ])
                            @slot('fields')

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'created_at',
                                                'label'         => trans('admin::inputs.contactus_crud.submission_date.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.submission_date.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->created_at->format('Y-m-d H:i:s'),
                                            ],
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'status',
                                                'label'         => trans('admin::inputs.base_crud.status.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.status.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->status_format['label'],
                                            ],
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'first_name',
                                                'label'         => trans('admin::inputs.contactus_crud.first_name.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.first_name.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->first_name,
                                            ],
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'last_name',
                                                'label'         => trans('admin::inputs.contactus_crud.last_name.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.last_name.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->last_name,
                                            ],
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'email',
                                                'label'         => trans('admin::inputs.base_crud.email.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.email.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->email,
                                            ],
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'phone',
                                                'label'         => trans('admin::inputs.base_crud.phone.label'),
                                                'placeholder'   => trans('admin::inputs.base_crud.phone.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->phone,
                                            ],
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'ip_address',
                                                'label'         => trans('admin::inputs.contactus_crud.ip_address.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.ip_address.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->ip_address,
                                            ],
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.text', [
                                            'options'           => [
                                                'name'          => 'user_agent',
                                                'label'         => trans('admin::inputs.contactus_crud.user_agent.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.user_agent.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->user_agent,
                                            ],
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.textarea', [
                                            'options'           => [
                                                'name'          => 'message',
                                                'rows'          => 10,
                                                'label'         => trans('admin::inputs.contactus_crud.message.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.message.placeholder'),
                                                'disabled'      => true,
                                                'value'         => $model->message,
                                            ],
                                        ])
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-12 mb-10 form-group">
                                        @include('admin::components.inputs.textarea', [
                                            'options'           => [
                                                'name'          => 'reply',
                                                'rows'          => 10,
                                                'label'         => trans('admin::inputs.contactus_crud.reply.label'),
                                                'placeholder'   => trans('admin::inputs.contactus_crud.reply.placeholder'),
                                                'disabled'      => $model->canReply() ? false : true,
                                                'value'         => $model->reply,
                                            ],
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
