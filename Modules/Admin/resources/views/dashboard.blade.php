@php
    use Carbon\Carbon;
@endphp

@extends('admin::layouts.master')

@section('toolbar')
    @component('admin::includes.toolbar', [
            'options'               => [
                'title'             => trans('admin::dashboard.aside_menu.home'),
                'actions'           => [
                    'filter'        => false,
                    'search'        => false,
                ],
            ]
        ])

        @slot('otherActions')
            @component('admin::components.forms.form', [
                    'options'               => [
                        'id'                => 'dashboard-form',
                        'isAjax'            => false,
                        'action'            => route('admin.dashboard.index'),
                        'changeTracking'    => false,
                    ]
                ])
                @slot('fields')
                    <div class="d-flex align-items-center gap-2 gap-lg-4" id="dashboard-filter-form">
                        @include('admin::components.inputs.date_range_picker', [
                            'options'           => [
                                'name'          => 'date_range',
                                'startDate'     => Carbon::parse('01-01-2024')->format('Y-m-d'),
                                'endDate'       => Carbon::now()->format('Y-m-d'),
                            ]
                        ])

                        @component('admin::components.buttons.submit', [
                            'options'               => [
                                'label'             => trans('admin::base.filter'),
                                'class'             => 'btn-primary',
                                'progress_label'    => trans('admin::messages.select2_messages.searching')
                            ]
                        ])
                        @endcomponent
                    </div>
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

@push('style')
    <style>
        #kt_content_container i {
            font-size: 40px;
            color: var(--primary-admin-color);
        }
        #dashboard-filter-form {
            width: 400px;
        }
        #dashboard-filter-form  button[type="submit"] {
            width: 75%;
        }

    </style>
@endpush

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
            @foreach ($statistics ?? [] as $key => $statistic)
                <div class="row gy-5 g-xl-10">
                    <h1 class="d-flex text-dark fw-bolder fs-3 flex-column mb-0">
                        @lang('admin::cruds.'.$key.'.title')
                    </h1>
                    @foreach($statistic ?? [] as $stat)
                        @empty($stat) @continue @endempty
                        <div class="col-12 col-sm-6 col-md-6 col-xl-3 mb-xl-10">
                            <div class="card h-lg-100">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </div>
                                    <a class="d-flex flex-column my-7 cursor-pointer" href="{{ $stat['route'] }}">
                                        <span id="stat-{{ $stat['key'] }}" class="fw-bold fs-3x text-gray-800 lh-1 ls-n2">
                                            {{ $stat['count'] }}
                                        </span>
                                        <div class="m-0">
                                            <span class="fw-bold fs-6 text-gray-400">
                                                {{ $stat['label'] }}
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('#dashboard-form').on('submit', function (e) {
                e.preventDefault();
                let form        = $(this);
                let url         = form.attr('action');
                let data        = form.serialize();
                let method      = form.attr('method');
                let button      = form.find('button[type="submit"]');

                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    beforeSend: function () {
                        button.attr('data-kt-indicator', 'on');
                        button.attr('disabled', true);
                    },
                }).done(function(response) {
                    if (response.success && response.data.statistics) {
                        Object.values(response.data.statistics).forEach(group => {
                            group.forEach(stat => {
                                let el = $('#stat-' + stat.key);
                                if (el.length) {
                                    el.text(stat.count);
                                }
                            });
                        });
                    }
                }).fail(function (response) {
                    GLOBAL.TOASTR.INIT('error');
                }).always(function () {
                    button.attr('data-kt-indicator', 'of');
                    button.attr('disabled', false);
                });
            });
        });
    </script>
@endpush
