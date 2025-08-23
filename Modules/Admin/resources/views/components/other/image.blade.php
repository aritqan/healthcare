@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'alt'                   => config('app.name'),
            'class'                 => '',
            'src'                   => '',
            'onError'               => config('admin.frontend.default_placeholder'),
        ], $options);

        if(empty($VALUE['src'])) {
            $VALUE['src'] = $VALUE['onError'];
        }
    @endphp

    <img alt="{{$VALUE['alt']}}" src="{{$VALUE['src']}}" onerror="this.src='{{$VALUE['onError']}}'" @class([
        $VALUE['class'],
    ]) />
@endisset
