@isset($options)
    @php
        $VALUE = array_merge([
            'id'                => 'data-table-filter',
            'withResetButton'   => true,
            'withApplyButton'   => true,
        ], $options);
    @endphp

    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="filter-dropdown">
        <!--begin::Header-->
        <div class="px-7 py-5">
            <div class="fs-5 text-dark fw-bolder">
                @lang('admin::base.filter_options')
            </div>
        </div>
        <!--end::Header-->

        <div class="separator border-gray-200"></div>

        <!--begin::Form-->
        <div class="px-7 py-5">

            @component('admin::components.forms.form', [
                    'options'               => [
                        'id'                => $VALUE['id'],
                        'changeTracking'    => false,
                        'isAjax'            => false,
                    ]
                ])

                @slot('fields')
                    @isset($filterContent)
                        {!! $filterContent !!}
                    @endisset
                @endslot

                @slot('submit')
                    <div class="d-flex justify-content-end">
                        @if($VALUE['withResetButton'])
                            <button id="reset-filter" type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                data-kt-menu-dismiss="true">@lang('admin::base.reset')</button>
                        @endif
                        @if($VALUE['withApplyButton'])
                            <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">@lang('admin::base.apply')</button>
                        @endif
                    </div>
                @endslot

            @endcomponent
        </div>
        <!--end::Form-->
    </div>
@endisset
