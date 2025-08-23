@foreach($_ALL_LOCALE_ as $localeCode => $properties)
    <div class="menu-item px-3">
        @php
            $isActive = $localeCode == $_LOCALE_ ? true : false;
            $isArabic = $localeCode == 'ar' ? true : false;
            $updateInDb = app('admin') ? 'update-auth-language' : '';
        @endphp
        @component('admin::components.other.hyperlink', [
                'options'               => [
                    'id'                => 'language-' . $localeCode,
                    'menuLink'          => true,
                    'href'              => LaravelLocalization::getLocalizedURL($localeCode, null, [], true),
                    'class'             => $updateInDb . ' px-1 py-3 d-flex' . ($isActive ? ' active' : ''),
                    'attributes'        => [
                        'data-language' => $localeCode,
                    ],
                ]
            ])
            <span class="symbol symbol-20px me-4">
                @include('admin::components.other.image', [
                    'options'   => [
                        'class' => 'rounded-1',
                        'src'   => config('admin.frontend.country_flag.' . $localeCode),
                        'alt'   => $properties['native'] . ' flag',
                    ]
                ])
            </span>
            {{ $properties['native'] }}
        @endcomponent
    </div>
@endforeach
