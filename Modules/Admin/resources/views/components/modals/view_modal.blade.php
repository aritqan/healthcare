@isset($options)
    @php
        $VALUE = array_merge([
            'id'            => null,
            'title'         => null,
            'withFooter'    => true,
        ], $options);
    @endphp

    <div class="modal fade view-modal-content" id="{{ $VALUE['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $VALUE['title'] }}
                    </h3>

                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            {!! config('admin.svgs.close_modal') !!}
                        </span>
                    </div>
                </div>
                <div class="modal-spinner">
                    <img src="{{ asset('images/default/placeholder/spinner.gif') }}" alt="Loading...">
                </div>
                <div class="modal-body view-modal-body"></div>

                @if($VALUE['withFooter'])
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            @lang('admin::base.close')
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endisset
