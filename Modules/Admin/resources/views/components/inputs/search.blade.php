@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'type'                  => 'text',
            'placeholder'           => null,
            'help'                  => null,
            'class'                 => null,
            'withIcon'              => true,
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id'] = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
    @endphp

    {{-- Icon --}}
    @if ($VALUE['withIcon'])
        <span class="position-absolute ms-6">
            {!! config('admin.svgs.search') !!}
        </span>
    @endif

    {{-- Input --}}
    <input @class(['form-control w-200px ps-15 custom-search-input form-control-solid'])
    type="text" name="{{$VALUE['name']}}" id="{{$VALUE['id']}}" value="{{old($VALUE['name'])}}" placeholder="{{$VALUE['placeholder']}}"/>
@endisset
