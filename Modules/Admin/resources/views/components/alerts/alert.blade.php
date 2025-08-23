@isset($options)
    @php
        $VALUE = array_merge([
            'id'                => null,
            'color'             => 'primary',
            'icon'              => config('admin.svgs.exclamation_mark'),
            'title'             => null,
            'description'       => null,
            'dismissible'       => false,
        ], $options);
    @endphp

    <div @class(['d-flex align-items-center p-5 alert alert-' . $VALUE['color'], 'alert-dismissible' => $VALUE['dismissible']])>
        <span class="svg-icon svg-icon-2hx svg-icon-{{ $VALUE['color'] }} me-3">
            {!! $VALUE['icon'] !!}
        </span>

        <div class="d-flex flex-column">
            <h4 class="mb-1 text-dark">
                {{ $VALUE['title'] }}
            </h4>

            <span class="text-gray-700 fw-bold fs-6">
                {!! $VALUE['description'] !!}
            </span>
        </div>
    </div>
@endisset
