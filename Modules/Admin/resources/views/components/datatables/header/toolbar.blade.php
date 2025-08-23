@isset($options)
    @php
        $VALUE = array_merge([
            'role'              => null,
            'withAddButton'     => true,
            'route'             => 'javascript:;',
            'title'             => trans('admin::datatable.buttons.add_new'),
            'multiActions'      => [],
        ], $options);
    @endphp

    @if ($VALUE['withAddButton'] && (app('owner') || $VALUE['role']))
        <div class="ms-5" data-kt-customer-table-toolbar="base">
            @component('admin::components.other.hyperlink', [
                    'options'           => [
                        'href'          => $VALUE['route'],
                        'class'         => 'btn btn-primary'
                    ]
                ])
                <i class="fas fa-plus"></i>
                {{$VALUE['title']}}
            @endcomponent
        </div>
    @endif

    @if (count($VALUE['multiActions']) > 0)
        <div data-kt-customer-table-toolbar="base">
            @component('admin::components.menu.dropdown_menu', [
                    'options'           => [
                        'id'            => 'kt_datatable_selected_records_action',
                        'class'         => 'btn-danger',
                        'title'         => trans('admin::datatable.buttons.select_action'),
                        'subMenuTitle'  => trans('admin::datatable.buttons.select_action'),
                        'multiActions'  => $VALUE['multiActions'],
                    ]
                ])
            @endcomponent

        </div>
    @endif

    @if (isset($slot))
        {!! $slot !!}
    @endif
@endisset
