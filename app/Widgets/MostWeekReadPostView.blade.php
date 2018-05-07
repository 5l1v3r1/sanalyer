@foreach($data["posts"] as $post)
    <li class="sidebar-mosts__item sidebar-mosts__item--1">
        <div class="sidebar-mosts__item__body">
            <a class="sidebar-mosts__item__link" href="{{ route('show_post', str_slug($post->title) . '-' . $post->id) }}" title="Başlık" >
                <div class="sidebar-mosts__item__image lazy" data-original="{{ Image::url(\App\checkImage($post->image), 96, 96, array('crop')) }}"></div>
            </a>
            <div class="sidebar-mosts__item__content">
                <a class="sidebar-mosts__item__link" href="{{ route('show_post', str_slug($post->title) . '-' . $post->id) }}" title="{{ $post->title }}" >
                    <h3 class="sidebar-mosts__item__title"><span class="underline">{{ $post->title }}</span></h3>
                </a>
                <span class="sidebar-mosts__item__icon"><i class="material-icons">&#xE8E5;</i></span>
                <span class="sidebar-mosts__item__count">{{ \App\numberFormat($post->views) }}</span>
            </div>
        </div>
    </li>
@endforeach
