@isset($item)
    @if ($item['type'] == 'item' && is_null($item['parent_id']))
        <div @class(['menu-item', 'here show' => $item['is_active']])>
            @if (isset($item['link']) && !empty($item['link']))
                <a class="menu-link" href="{{ $item['link'] }}">
                    @include('admin::includes.aside_menu.icon', [
                        'item' => $item,
                    ])
                    @include('admin::includes.aside_menu.title', [
                        'item' => $item,
                    ])
                </a>
            @endif
        </div>
    @elseif($item['type'] == 'header' && is_null($item['parent_id']) && count($item['children']) > 0)
        <div class="menu-item">
            <div class="menu-content pt-8 pb-2">
                <span class="menu-section text-uppercase fs-8 ls-1"> {{ $item['title'] }}</span>
            </div>
        </div>

        @if (isset($item['children']) && count($item['children']) > 0)
            @foreach ($item['children'] as $subItem)
                @if (isset($subItem['children']) && count($subItem['children']) > 0)
                    <div data-kt-menu-trigger="click" @class([
                        'menu-item',
                        'menu-accordion',
                        'here show' => $subItem['is_active'],
                    ])>
                        <span class="menu-link">
                            @include('admin::includes.aside_menu.icon', [
                                'item' => $subItem,
                            ])
                            @include('admin::includes.aside_menu.title', [
                                'item' => $subItem,
                            ])
                            <span class="menu-arrow"></span>
                        </span>

                        @foreach ($subItem['children'] as $children)
                            <div class="menu-sub menu-sub-accordion">
                                <div @class(['menu-item', 'here show' => $children['is_active']])>
                                    <a class="menu-link" href="{{ $children['link'] }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        @include('admin::includes.aside_menu.title', [
                                            'item' => $children,
                                        ])
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    @endif
@endisset
