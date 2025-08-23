@extends('admin::layouts.master', ['title' => trans('admin::cruds.content_tags.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.content_tag_management.content_tags'),
            'backUrl'           => route('cms.content_tags.index'),
            'actions'           => [
                'save'          => true,
                'back'          => true,
            ],
        ]
    ])
@endsection

@push('style')

@endpush

@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-3"></div>

            <div class="col-xxl-6 col-12">
                <div class="card card-bordered mb-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('admin::cruds.content_tags.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('cms.content_tags.postUpdate', [$model->id]),
                                    'method'    => 'PUT',
                                ]
                            ])
                            @slot('fields')

                            {{-- <div class="separator separator-dashed my-5"></div> --}}

                            <div class="row">
                                <div class="col-12">
                                    @include('admin::components.other.lang_crud', [
                                        'options'           => [
                                            'title'         => [
                                                'show'      => true,
                                                'required'  => true,
                                                'value'     => function($model, $locale) {
                                                    return $model->smartTrans('title', $locale, true);
                                                },
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

@push('script')

@endpush
