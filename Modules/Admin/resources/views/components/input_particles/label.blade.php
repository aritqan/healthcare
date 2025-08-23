@isset($VALUE['label'])
    <label for="{{$VALUE['id']}}" @class(['form-label', 'fw-bolder' , 'required' => $VALUE['required'], $VALUE['label_size']])>
        {{$VALUE['label']}}
        @isset($VALUE['help'])
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{{ $VALUE['help'] }}"></i>
        @endisset
    </label>
@endisset
