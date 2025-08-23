@isset($options)

    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'class'                 => '',
            'method'                => 'POST',
            'action'                => '#',
            'enctype'               => 'application/x-www-form-urlencoded"',
            'autocomplete'          => 'off',
            'isAjax'                => true,
            'changeTracking'        => true,
            'checkForEmptyCheckbox' => false,
            'addEmptyCheckbox'      => false,
            'isMultiPart'           => false,
        ], $options);

        $VALUE['name']          = $VALUE['name'] ?? 'form-' . Str::random(5);
        $VALUE['id']            = $VALUE['id'] ?? $VALUE['name'];
        $VALUE['formMethod']    = $VALUE['method'] === 'GET' ? 'GET' : 'POST';
        $VALUE['enctype']       = $VALUE['isMultiPart'] ? 'multipart/form-data' : $VALUE['enctype'];
    @endphp


    <form id="{{$VALUE['id']}}" name="{{$VALUE['name']}}" @class(['ajax-form' => $VALUE['isAjax'], 'change-tracking-form' => $VALUE['changeTracking'] , 'check-empty-checkbox' => $VALUE['checkForEmptyCheckbox'] , 'add-empty-checkbox' => $VALUE['addEmptyCheckbox'] , $VALUE['class']])
        action="{{$VALUE['action']}}"  method="{{$VALUE['formMethod']}}" autocomplete="{{$VALUE['autocomplete']}}"
        enctype="{{$VALUE['enctype']}}">

        @method($VALUE['method'])

        @if ($VALUE['method'] !== 'GET')
            @csrf
        @endif

        @isset($fields)
            {!! $fields !!}
        @endisset

        @isset($submit)
            {!! $submit !!}
        @endisset
    </form>
@endisset
