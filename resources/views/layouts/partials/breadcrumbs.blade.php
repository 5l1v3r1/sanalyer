@if (count($breadcrumbs))


    <ul class="content-breadcrumb__list">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li class="content-breadcrumb__list__item">
                    <a itemprop="url" href="{{ $breadcrumb->url }}" title="{{ $breadcrumb->title }}"><span
                                itemprop="title">{{ $breadcrumb->title }}</span></a>
                </li>
            @else
                <li class="content-breadcrumb__list__item">
                    <span itemprop="title">{{ $breadcrumb->title }}</span>
                </li>
            @endif

        @endforeach
    </ul>

@endif