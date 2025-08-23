<input id="{{ $VALUE['id'] }}" @class(['form-control', 'date-picker-input', 'form-control-solid' => $VALUE['solid'], $VALUE['class']])
placeholder="{{$VALUE['placeholder']}}" name="{{$VALUE['name']}}" inputmode="{{$VALUE['inputmode']}}"
data-with-time="{{$VALUE['withTime']}}" data-date-format="{{$VALUE['dateFormat']}}" data-mode="{{$VALUE['mode']}}"
value="{{$VALUE['value']}}" @readonly($VALUE['readonly']) @disabled($VALUE['disabled']) />
