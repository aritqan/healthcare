@isset($options)
    @php
        $VALUE = array_merge([
            'role'              => null,
            'title'             => null,
            'withSwitchArchive' => true,
            'class'             => 'd-flex align-items-center position-relative my-1 fw-bolder fs-3',
        ], $options);
    @endphp

    <div class="{{$VALUE['class']}}">
        @if (!empty($VALUE['title']))
            <span class="me-5">
                {{$VALUE['title']}}
            </span>
        @endif

        @if ($VALUE['withSwitchArchive'] &&  (app('owner') || $VALUE['role']))
            <input type="checkbox" id="switch-archive">
        @endif
    </div>
@endisset
