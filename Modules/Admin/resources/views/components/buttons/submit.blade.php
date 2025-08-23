@isset($options)
    @php
        $VALUE = array_merge([
            'id'                => null,
            'type'              => 'submit',
            'class'             => 'btn-primary w-100',
            'label'             => null,
            'progress_label'    => trans('admin::base.please_wait_dot'),
        ], $options);
    @endphp

    <button type="{{$VALUE['type']}}" id="{{$VALUE['id']}}" @class(['btn', $VALUE['class']])>
        <span class="indicator-label">
            {{$VALUE['label']}}
        </span>
        <span class="indicator-progress">
            {{$VALUE['progress_label'] }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
@endisset
