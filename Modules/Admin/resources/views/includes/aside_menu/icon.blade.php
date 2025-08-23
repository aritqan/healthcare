<span class="menu-icon">
    @if (isset($item['icon']) && !empty($item['icon']))
        @if ($item['icon_type'] == 'svg')
            @php
                $svgType = getType($item['icon']);
            @endphp
            @if ($svgType == 'string')
                {!! $item['icon'] !!}
            @else
                {{ $item['icon'] }}
            @endif
        @else
            <i class="{{ $item['icon'] }}"></i>
        @endif
    @endif
</span>
