@isset($options)
    @php
        $VALUE = array_merge([
            'tabId' => 'tab_',
            'href' => '#kt_tab_pane_',
        ], $options);
    @endphp
@endisset
<ul class="nav nav-tabs  mb-5 fs-6">
    @foreach ($_ALL_LOCALE_ as $locale => $lang)
        @php
            $active = $locale == $_LOCALE_ ? 'active' : '';
        @endphp
        <li class="nav-item">
            @component('admin::components.other.hyperlink', [
                    'options'                   => [
                        'id'                    => $VALUE['tabId'] . $locale,
                        'href'                  => $VALUE['href'] . $locale,
                        'class'                 => 'nav-link ' . $active,
                        'attributes'            => [
                            'data-bs-toggle'    => 'tab',
                            'role'              => 'tab',
                            'aria-selected'     => 'true',
                        ]
                    ]
                ])
                @include('admin::components.other.image', [
                    'options'   => [
                        'class' => 'w-20px h-20px rounded-1 ',
                        'src'   => config('admin.frontend.country_flag.' . $locale),
                        'alt'   => 'current local flag',
                    ]
                ])
                <span id="language-{{$locale}}" class="small">{{$lang['native']}}</span>
            @endcomponent
        </li>
    @endforeach
</ul>
