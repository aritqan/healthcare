<div @class(['position-relative', 'mb-3' => $VALUE['highlight']])>
    <input @class(['form-control form-control-lg', 'form-control-solid' => $VALUE['solid'], $VALUE['class'], 'has-icon' => $VALUE['visibleToggle'] ])
    placeholder="{{$VALUE['placeholder']}}" type="password" name="{{$VALUE['name']}}" id="{{$VALUE['id']}}" inputmode="{{$VALUE['inputmode']}}"
    @readonly($VALUE['readonly']) @disabled($VALUE['disabled']) autocomplete="off">

    @if($VALUE['visibleToggle'])
        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
            data-kt-password-meter-control="visibility">
            <i class="bi bi-eye-slash fs-2"></i>
            <i class="bi bi-eye fs-2 d-none"></i>
        </span>
    @endif
</div>

@if($VALUE['highlight'])
    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
    </div>
@endif
