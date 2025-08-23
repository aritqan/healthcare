@extends('admin::layouts.master', ['title' => trans('admin::cruds.content_categories.add')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.content_category_management.content_categories'),
            'backUrl'           => route('cms.content_categories.index'),
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
                            @lang('admin::cruds.content_categories.add')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('cms.content_categories.postCreate'),
                                ]
                            ])
                            @slot('fields')
                                <div class="row">
                                    <div class="col-12 mb-5">
                                        @include('admin::components.alerts.alert', [
                                            'options'           => [
                                                'color'         => 'primary',
                                                'description'   => trans('admin::strings.category_cannot_be_update_parent_after_create'),
                                            ]
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'parent_id',
                                                'label'         => trans('admin::inputs.content_category_crud.parent_id.label'),
                                                'placeholder'   => trans('admin::inputs.content_category_crud.parent_id.placeholder'),
                                                'url'           => route('cms.content_categories.ajaxList'),
                                                'clearable'     => true,
                                                'searchable'    => true,
                                                'isAjax'        => true,
                                            ]
                                        ])
                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">
                                        @include('admin::components.inputs.select', [
                                            'options'           => [
                                                'name'          => 'can_be_deleted',
                                                'required'      => true,
                                                'label'         => trans('admin::inputs.content_category_crud.can_be_deleted.label'),
                                                'help'          => trans('admin::inputs.content_category_crud.can_be_deleted.help'),
                                                'data'          => YES_NO_DATA,
                                                'text'          => function($key, $value) {return trans('base::base.yes_no.' . $value['text']);},
                                                'values'        => function($key, $value) {return $value['value'];},
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
                                                ]
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
