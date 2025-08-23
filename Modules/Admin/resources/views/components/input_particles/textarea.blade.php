<textarea @class(['form-control', 'form-control-solid' => $VALUE['solid'], $VALUE['class']])
placeholder="{{$VALUE['placeholder']}}" name="{{$VALUE['name']}}" id="{{$VALUE['id']}}"
rows="{{$VALUE['rows']}}" cols="{{$VALUE['cols']}}" maxlength="{{$VALUE['maxlength']}}"
@readonly($VALUE['readonly']) @disabled($VALUE['disabled'])>{{$VALUE['value']}}</textarea>

@if (!empty($VALUE['icon']))
    <div class="position-absolute translate-middle-y top-50 start-0 ms-3 input-icon">
        {!! $VALUE['icon'] !!}
    </div>
@endif
