@isset($options)
    @php
        $VALUE = array_merge([
            'id'            => null,
            'class'         => 'btn-light-primary',
            'title'         => null,
            'menuPlacement' => 'bottom-end',
            'icon'          => null,
            'subMenuTitle'  => null,
            'multiActions'  => null,
        ], $options);
    @endphp

    <div class="action-dropdown-menu d-none">
        @component('admin::components.buttons.button', [
            'options'           => [
                'menuTrigger'   => true,
                'targetModal'   => false,
                'id'            => $VALUE['id'],
                'class'         => $VALUE['class'],
                'title'         => $VALUE['title'],
                'menuPlacement' => $VALUE['menuPlacement'],
                'icon'          => $VALUE['icon'],
            ]
        ])
        @endcomponent

        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">

            @if(isset($VALUE['subMenuTitle']))
                <div class="menu-item px-3">
                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                        {{$VALUE['subMenuTitle']}}
                    </div>
                </div>

                <div class="separator mb-3 opacity-75"></div>
            @endif

            <div class="menu-item px-3" id="multi-action-dropdown">
                @foreach ($VALUE['multiActions'] as $action)
                    @component('admin::components.other.hyperlink', [
                            'options'               => [
                                'href'              => $action['route'],
                                'class'             => 'menu-link px-3',
                                'withConfirmDialog' => true,
                                'isAjax'            => true,
                                'method'            => $action['method'],
                                'keyName'           => 'ids',
                                'attributes'        => [
                                    'data-multi-action' => 'true',
                                ],
                            ]
                        ])
                        <span class="{{ $action['class'] }}">
                            <i class="{{ $action['icon'] }} me-2"></i>
                            <span>{{ $action['label'] }}</span>
                        </span>
                    @endcomponent
                @endforeach
            </div>
        </div>
    </div>
@endisset
