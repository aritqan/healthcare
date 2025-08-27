<input @class(['form-control', 'intl-tel-input', 'form-control-solid' => $VALUE['solid'], $VALUE['class']])
type="tel" name="{{$VALUE['name']}}" id="{{$VALUE['id']}}" inputmode="{{$VALUE['inputmode']}}"
value="{{$VALUE['value']}}" @readonly($VALUE['readonly']) @disabled($VALUE['disabled'])
data-full-number-name="{{$VALUE['fullNumberName']}}" data-value="{{$VALUE['value']}}"
@if($VALUE['data']) @foreach($VALUE['data'] as $key => $val) data-{{$key}}="{{$val}}" @endforeach @endif/>
