@extends('admin::layouts.master', ['title' => trans('admin::cruds.subscribes.edit')])

@section('toolbar')
    @include('admin::includes.toolbar', [
        'options'               => [
            'title'             => trans('admin::dashboard.aside_menu.subscribe_management.subscribes'),
            'backUrl'           => route('crm.subscribes.index'),
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
                            @lang('admin::cruds.subscribes.edit')
                        </h3>
                    </div>
                    <div class="card-body">
                        @component('admin::components.forms.form', [
                                'options'       => [
                                    'isAjax'    => true,
                                    'action'    => route('crm.subscribes.postUpdate', [$model->id]),
                                    'method'    => 'PUT',
                                ]
                            ])
                            @slot('fields')

                                <div class="row">
                                    <div class="col-lg-6 col-12 mb-10 form-group">

                                    </div>

                                    <div class="col-lg-6 col-12 mb-10 form-group">

                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

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
