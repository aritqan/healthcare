<select @class(['form-select', 'form-select-solid' => $VALUE['solid'], 'select2-ajax' => $VALUE['isAjax'], $VALUE['class']])
    name="{{ $VALUE['name'] }}" id="{{ $VALUE['id'] }}" @if($VALUE['multiple']) multiple @endif
    data-placeholder="{{ $VALUE['placeholder'] }}"  data-allow-clear="{{ $VALUE['clearable'] }}"
    @if($VALUE['isAjax'])
        data-url="{{ $VALUE['url'] }}"
        data-selected="{{ json_encode($VALUE['selected']) }}"
    @endif
    data-with-img="{{ $VALUE['withImg'] }}"
    @if($VALUE['select2']) data-control="select2" @endif data-dropdown-parent="{{ $VALUE['dropdownParent'] }}"
    data-hide-search="{{ $VALUE['searchable'] }}" @disabled($VALUE['disabled'])>

    @if(!empty($VALUE['placeholder']) && !$VALUE['multiple'])
        <option value></option>
    @endif

    @foreach ($VALUE['data'] as $key => $item)
        <option value="{{$VALUE['values']($key, $item)}}"
            @if($VALUE['select']($key, $item, $VALUE['value']))
                selected
            @endif>

            {{$VALUE['text']($key, $item)}}
        </option>
    @endforeach
</select>
