@isset($options)
    @php
        $VALUE = array_merge([
            'id'                    => null,
            'name'                  => null,
            'subText'               => null,
            'label'                 => null,
            'uploadUrl'             => route('base.empty_data'),
            'class'                 => null,
            'maxFiles'              => 5,
            'maxFilesize'           => config('base.file.image.max_size'),
            'acceptedFiles'         => getImageTypes(),
            'method'                => 'POST',
            'multiple'              => true,
            'autoProcessQueue'      => false,
            'addRemoveLinks'        => true,
            'headerText'            => trans('admin::strings.drop_your_files_here'),
            'uploadText'            => trans('admin::strings.upload_files', ['maxFile' => $options['maxFiles'] ?? 10]),
            'existingFiles'         => [],
            'deleteUrl'             => '#',
        ], $options);

        $VALUE['id']        = !empty($VALUE['id']) ? $VALUE['id'] : $VALUE['name'];

    @endphp

    <div class="dropzone" id="{{ $VALUE['id'] }}" data-name="{{ $VALUE['name'] }}" data-upload-url="{{ $VALUE['uploadUrl'] }}"
        data-max-files="{{ $VALUE['maxFiles'] }}" data-max-filesize="{{ $VALUE['maxFilesize'] }}"
        data-accepted-files="{{ $VALUE['acceptedFiles'] }}" data-method="{{ $VALUE['method'] }}"
        data-multiple="{{ $VALUE['multiple'] }}" data-auto-process-queue="{{ $VALUE['autoProcessQueue'] }}"
        data-add-remove-links="{{ $VALUE['addRemoveLinks'] }}" data-existing-files="{{ json_encode($VALUE['existingFiles']) }}" data-delete-url="{{ $VALUE['deleteUrl'] }}">

        <div class="dz-message needsclick">
            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
            <div class="ms-4">
                <h3 class="fs-5 fw-bolder text-gray-900 mb-1">
                    {{ $VALUE['headerText'] }}
                </h3>
                <span class="fs-7 fw-bold text-gray-400">
                    {{ $VALUE['uploadText'] }}
                </span>
            </div>
        </div>
    </div>

    @if (!empty($VALUE['subText']))
        <span class="form-text text-muted">{{$VALUE['subText']}}</span>
    @endif
@endisset
