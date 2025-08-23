<input @class(['form-control', 'form-control-solid' => $VALUE['solid'], $VALUE['class'], 'has-icon' => !empty($VALUE['icon']), 'email-mask-input' => $VALUE['emailMask']])
placeholder="{{$VALUE['placeholder']}}" type="{{$VALUE['type']}}" name="{{$VALUE['name']}}" id="{{$VALUE['id']}}" inputmode="{{$VALUE['inputmode']}}"
value="{{$VALUE['value']}}" @readonly($VALUE['readonly']) @disabled($VALUE['disabled'])
@if($VALUE['onlyDigit']) onkeypress="return isNumberKey(event)" @endif
@if($VALUE['isPhone']) onkeypress="return isPhoneKey(event)" @endif
@if($VALUE['maxlength']) maxlength="{{$VALUE['maxlength']}}" @endif
@if($VALUE['type'] == 'number')
    step="{{$VALUE['step']}}"
    @if($VALUE['min']) min="{{$VALUE['min']}}" @endif
    @if($VALUE['max']) max="{{$VALUE['max']}}" @endif
@endif
@if($VALUE['data']) @foreach($VALUE['data'] as $key => $val) data-{{$key}}="{{$val}}" @endforeach @endif/>

@if (!empty($VALUE['icon']))
    <div class="position-absolute translate-middle-y top-50 start-0 ms-3 input-icon-{{$VALUE['type']}}">
        {!! $VALUE['icon'] !!}
    </div>
@endif
