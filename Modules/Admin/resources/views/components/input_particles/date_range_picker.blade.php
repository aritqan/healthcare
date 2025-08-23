<input id="{{ $VALUE['id'] }}" @class(['kt_daterangepicker form-control', 'form-control-solid' => $VALUE['solid'], $VALUE['class']])
placeholder="{{$VALUE['placeholder']}}" name="{{$VALUE['name']}}" inputmode="{{$VALUE['inputmode']}}"
data-start-date="{{$VALUE['startDate']}}" data-end-date="{{$VALUE['endDate']}}" data-date-format="{{$VALUE['dateFormat']}}"
value="{{$VALUE['value']}}" @readonly($VALUE['readonly']) @disabled($VALUE['disabled']) />
