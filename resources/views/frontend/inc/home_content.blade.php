@foreach($posts as $item)
    @if($item->type == 0)
        @include('frontend.inc.widgets.posts')
    @elseif($item->type == 1)
        @include('frontend.inc.widgets.posts_video')
    @endif
@endforeach