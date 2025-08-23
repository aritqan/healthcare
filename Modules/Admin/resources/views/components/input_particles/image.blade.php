<div @class(['image-input image-input-outline','image-input-empty' => ! $VALUE['value'] ,'image-input-circle' => $VALUE['circle']]) data-kt-image-input="true"
    @style(['background-position:center', 'background-size:contain', 'background-image:url('.$VALUE['default'].')' ]) >
    <!--begin::Preview existing avatar-->
    <div @class(['image-input-wrapper'])  @style(['width:' . $VALUE['width'], 'height:' . $VALUE['height'] , 'background-image:url('.$VALUE['value'].')' ])></div>
    <!--end::Preview existing avatar-->

    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="{{ $VALUE['uploadText'] }}">
        <i class="bi bi-pencil-fill fs-7"></i>
        <!--begin::Inputs-->
        <input type="file" id="{{ $VALUE['id'] }}" name="{{ $VALUE['name'] }}" accept="{{ $VALUE['accept'] }}"/>
        <input type="hidden" name="avatar_remove" />
        <!--end::Inputs-->
    </label>

    <!--begin::Cancel-->
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="{{ $VALUE['cancelText'] }}">
        <i class="bi bi-x fs-2"></i>
    </span>
    <!--end::Cancel-->

    @if($VALUE['canRemove'])
        <!--begin::Remove-->
        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="{{ $VALUE['removeText'] }}">
            <i class="bi bi-x fs-2"></i>
        </span>
        <!--end::Remove-->
    @endif
</div>
