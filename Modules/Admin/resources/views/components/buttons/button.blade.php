@isset($options)
    @php
        $VALUE = array_merge([
            'id'                => null,
            'type'              => 'button',
            'title'             => null,
            'class'             => 'btn-light-primary',
            'icon'              => null,
            'targetModal'       => true,
            'targetModalId'     => null,
            'menuTrigger'       => false,
            'menuPlacement'     => 'bottom-end',
        ], $options);
    @endphp

    <button type="{{$VALUE['type']}}" id="{{$VALUE['id']}}" @class(['btn', $VALUE['class']])
        @if ($VALUE['targetModal']) data-bs-toggle="modal" data-bs-target="#{{$VALUE['targetModalId']}}" @endif
        @if($VALUE['menuTrigger']) data-kt-menu-trigger="click" data-kt-menu-placement="{{ $VALUE['menuPlacement'] }}" @endif >

        @if(isset($VALUE['icon']) && !empty($VALUE['icon']))
            {!! $VALUE['icon'] !!}
        @endif

        {{ $VALUE['title'] }}
    </button>
@endisset
