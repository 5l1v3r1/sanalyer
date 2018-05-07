@foreach($data["posts"] as $post)
    <li class="sidebar-trend__item">
        <div class="sidebar-trend__item__body">
            <a class="sidebar-trend__item__link" href="{{ route('show_video', str_slug($post->title) . '-' . $post->id) }}" title="{{ $post->title }}" >
                <div class="sidebar-trend__item__image lazy" data-original="{{ Image::url(\App\checkImage($post->image), 300, 170, array('crop')) }}">
                    <span class="sidebar-trend__item__icon"><i class="material-icons">&#xE037;</i></span>
                    <span class="sidebar-trend__item__duration">{{ $post->category()->first()->title }}</span>
                </div>
            </a>
            <div class="sidebar-trend__item__caption">
                <h3 class="sidebar-trend__item__title">
                    <a class="sidebar-trend__item__link" href="{{ route('show_video', str_slug($post->title) . '-' . $post->id) }}" title="{{ $post->title }}" >
                        <span class="underline">{{ $post->title }}</span>
                    </a>
                </h3>
            </div>
        </div>
    </li>
@endforeach
