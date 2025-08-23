@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'title'                 => null,
            'href'                  => 'javascript:;',
            'isAjax'                => false,
            'method'                => null,
            'menuLink'              => false,
            'withConfirmDialog'     => false,
            'showCanceledDialog'    => true,
            'onClick'               => null,
            'class'                 => '',
            'keyName'               => 'model',
            'keyValue'              => null,
            'attributes'            => [],
            'withIndicator'         => false,
            'dialogTitle'           => trans('admin::confirmations.confirm.base.title'),
            'dialogDesc'            => trans('admin::confirmations.confirm.base.desc'),
            'dialogConfirmButton'   => trans('admin::confirmations.yes'),
            'dialogCancelButton'    => trans('admin::confirmations.no'),
        ], $options);

        $VALUE['preventDefault']    = $VALUE['isAjax'] ? 'true' : 'false';
        $VALUE['date-href']         = $VALUE['href'];
        $VALUE['href']              = $VALUE['isAjax'] ? 'javascript:;' : $VALUE['href'];
    @endphp

    <a id="{{ $VALUE['id'] }}" href="{{ $VALUE['href'] }}" @class([
            'menu-link'             => $VALUE['menuLink'],
            'px-5'                  => $VALUE['menuLink'],
            'request-ajax'          => $VALUE['isAjax'],
            'request-with-dialog'   => $VALUE['withConfirmDialog'],
            $VALUE['class']         => true,
        ])
        data-prevent="{{ $VALUE['preventDefault'] }}" data-method="{{ $VALUE['method'] }}" data-key-value="{{ $VALUE['keyValue'] }}"
        data-key-name="{{ $VALUE['keyName'] }}"
        data-href="{{ $VALUE['date-href'] }}" data-with-dialog="{{ $VALUE['withConfirmDialog'] }}"
        data-dialog-title="{{ $VALUE['dialogTitle'] }}" data-dialog-desc="{{ $VALUE['dialogDesc'] }}"
        data-dialog-confirm-button="{{ $VALUE['dialogConfirmButton'] }}" data-dialog-cancel-button="{{ $VALUE['dialogCancelButton'] }}"
        data-show-canceled-dialog="{{ $VALUE['showCanceledDialog'] }}"
        @foreach ($VALUE['attributes'] as $key => $value)
            {{ $key }}="{{ $value }}"
        @endforeach
        @if (!empty($VALUE['onClick'])) onclick="{{ $VALUE['onClick'] }}" @endif>

        @if (isset($VALUE['title']) && !empty($VALUE['title']))

            @if ($VALUE['withIndicator'])
                <span class="indicator-label">
                    {{ $VALUE['title'] }}
                </span>
            @else
                {{ $VALUE['title'] }}
            @endif

        @else
            {{ $slot }}
        @endif

        @if ($VALUE['withIndicator'])
            <span class="indicator-progress">
                {{trans('admin::base.please_wait_dot')}}
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        @endif
    </a>

    @if ($VALUE['withIndicator'])
        <script>
            var button = document.querySelector("#{{ $VALUE['id'] }}");
            button.addEventListener("click", function() {
                button.setAttribute("data-kt-indicator", "on");

                // Disable indicator after 10 seconds
                // setTimeout(function() {
                //     button.removeAttribute("data-kt-indicator");
                // }, 10000);
            });
        </script>
    @endif
@endisset

