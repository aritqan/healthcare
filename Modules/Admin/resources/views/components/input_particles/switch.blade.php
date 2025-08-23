<input class="form-check-input {{$VALUE['class']}}" type="checkbox"  id="{{$VALUE['id']}}"
name="{{$VALUE['name']}}" @checked($VALUE['checked']) @disabled($VALUE['disabled'])
data-toggle="switchbutton" data-size="{{ $VALUE['size'] }}" data-onstyle="{{ $VALUE['onColor'] }}" data-offstyle="{{ $VALUE['offColor'] }}"
data-onlabel="{!! $VALUE['onLabel'] !!}" data-offlabel="{!! $VALUE['offLabel'] !!}" value="{{ $VALUE['value'] }}"/>
