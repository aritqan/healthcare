<div id="{{ $VALUE['id'] }}" class="quill-text-editor" style="height: {{ $VALUE['height'] }}">
    {!! $VALUE['value'] !!}
</div>

<textarea class="d-none" name="{{ $VALUE['name'] }}" data-text-id="{{ $VALUE['id'] }}-textarea">{!! $VALUE['value'] !!}</textarea>
