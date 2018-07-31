<ul>
    @foreach($data["categories"] as $cat)
        <li class="dropdown-container__item ripple">
            <a href="{{ route('show_category',$cat->full_url) }}"
               title="{{ $cat->title }}">{{ $cat->title }}</a>
        </li>
    @endforeach
</ul>