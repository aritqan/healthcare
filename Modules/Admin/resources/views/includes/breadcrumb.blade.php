@if ($listCount = count(app('adminHelper')->getBreadcrumbs()) > 1)
    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
        @foreach (app('adminHelper')->getBreadcrumbs() ?? [] as $breadcrumb)
            @if (! $loop->last)
                <li class="breadcrumb-item text-muted">
                    <a href="{{$breadcrumb['link']}}" class="text-muted text-hover-primary">{{$breadcrumb['title']}}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
            @else
                <li class="breadcrumb-item text-primary fw-bold">
                    {{$breadcrumb['title']}}
                </li>
            @endif
        @endforeach
    </ul>
@endif
