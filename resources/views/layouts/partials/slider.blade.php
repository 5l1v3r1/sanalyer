<section class="headline hide-phone">
    @foreach($slider as $key=>$post)
        @php
            if($post->type == 0){
                $link = route('show_post',str_slug($post->title).'-'.$post->id);
            }elseif($post->type == 1){
                $link = route('show_video',str_slug($post->title).'-'.$post->id);
            }
            if($key == 0){
                $image = Image::url(\App\checkImage($post->image), 788, 443, array('crop'));
            }elseif ($key == 1){
                $image = Image::url(\App\checkImage($post->image), 465, 396, array('crop'));
            }elseif ($key == 2 || $key == 3){
                $image = Image::url(\App\checkImage($post->image), 465, 194, array('crop'));
            }
        @endphp
        <article
                class="headline__blocks headline__blocks--{{ $key == 0 ? "large" : "" }}{{ $key == 1 ? "tall" : "" }}{{ $key == 2 ? "small" : "" }}{{ $key == 3 ? "small" : "" }}">
            <div class="headline__blocks__image"
                 style="background-image: url('{{ $image }}')"></div>
            <a class="headline__blocks__link" href="{{ $link }}" title="{{ $post->title }}"></a>
            <header class="headline__blocks__header">
                <h5 class="headline__blocks__header__category headline__blocks__header__category--{{ $post->type == 0 ? "news" : "" }}{{ $post->type == 1 ? "video" : "" }}">{{ $post->type == 0 ? "HABER" : "" }}{{ $post->type == 1 ? "VİDEO" : "" }}</h5>
                <h2 class="headline__blocks__header__title headline__blocks__header__title--{{ $key == 0 ? "large" : ""  }}{{ $key == 1 ? "tall" : "" }}{{ $key == 2 ? "small" : "" }}{{ $key == 3 ? "small" : "" }}">{{ $post->title }}</h2>
                <ul class="headline__blocks__header__other">
                    <li class="headline__blocks__header__other__author">{{ $post->user()->first()->firstname }}</li>
                    <li class="headline__blocks__header__other__date"><i class="material-icons">&#xE192;</i>
                        <time datetime="{{ $post->created_at->format(DateTime::ATOM) }}">{{ $post->created_at->diffForHumans() }}</time>
                    </li>
                </ul>
            </header>
        </article>
    @endforeach
</section>

<section class="headline visible-phone">
    <div class="slider" id="headline-slider" data-pagination="true" data-navigation="false">
        <div class="slider__list">
            @foreach($slider as $key=>$post)
                @php
                    if($post->type == 0){
                        $link = route('show_post',str_slug($post->title).'-'.$post->id);
                    }elseif($post->type == 1){
                        $link = route('show_video',str_slug($post->title).'-'.$post->id);
                    }
                @endphp
                <article class="slider__item headline__blocks headline__blocks--phone">
                    <div class="headline__blocks__image"
                         style="background-image: url('{{ Image::url(\App\checkImage($post->image), 352, 194, array('crop')) }}')"></div>
                    <a class="headline__blocks__link" href="{{ $link }}" title="{{ $post->title }}"></a>
                    <header class="headline__blocks__header">
                        <h5 class="headline__blocks__header__category headline__blocks__header__category--{{ $post->type == 0 ? "news" : "" }}{{ $post->type == 1 ? "video" : "" }}">{{ $post->type == 0 ? "HABER" : "" }}{{ $post->type == 1 ? "VİDEO" : "" }}</h5>
                        <h2 class="headline__blocks__header__title headline__blocks__header__title--phone">{{ $post->title }}</h2>
                        <ul class="headline__blocks__header__other">
                            <li class="headline__blocks__header__other__author">{{ $post->user()->first()->firstname }}</li>
                            <li class="headline__blocks__header__other__date"><i class="material-icons">&#xE192;</i>
                                <time datetime="{{ $post->created_at->format(DateTime::ATOM) }}">{{ $post->created_at->diffForHumans() }}</time>
                            </li>
                        </ul>
                    </header>
                </article>
            @endforeach
        </div>
    </div>
</section>
