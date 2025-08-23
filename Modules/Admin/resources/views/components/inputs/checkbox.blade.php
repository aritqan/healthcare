@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'type'                  => 'checkbox',
            'label'                 => null,
            'class'                 => null,
            'color'                 => 'primary',
            'solid'                 => true,
            'require'               => false,
            'checked'               => false,
            'value'                 => null,
            'disabled'              => false,
            'input_size'            => 'col-lg-8',
            'label_size'            => 'col-lg-12',
        ], $options);

        $VALUE['id'] = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];
    @endphp

    <div @class([
            'form-check form-switch form-check-custom',
            'form-check-solid' => $VALUE['solid'],
            'form-check-' . $VALUE['color'],
        ])>
        <input class="form-check-input w-50px h-25px {{$VALUE['class']}}" type="{{$VALUE['type']}}" value="{{$VALUE['value']}}" id="{{$VALUE['id']}}"
        name="{{$VALUE['name']}}" @checked($VALUE['checked']) @disabled($VALUE['disabled'])/>
        <label class="form-check-label" for="{{$VALUE['id']}}">
            {{$VALUE['label']}}
        </label>
    </div>
@endisset
