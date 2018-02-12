<div class="content-timeline__item">
    <div class="content-timeline--left">
        <div class="content-timeline__time">
            <a class="content-timeline__time__link" href="{{ route('video') }}">
                <span class="content-timeline__time__icon content-timeline__time__icon--video tooltip"
                      data-tooltip="Video"><i class="material-icons">&#xE038;</i></span>
            </a>
            <span class="content-timeline__time__timeago"><time
                        datetime="{{ $item->created_at->format(DateTime::ATOM) }}">{{ $item->created_at->diffForHumans() }}</time></span>
        </div>
    </div>
    <div class="content-timeline--right">
        <div class="content-timeline__media media-video">
            <a href="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}"
               class="content-timeline__link clearfix" title="{{ $item->title }}">
                <div class="content-timeline__media--inset">
                    <img class="content-timeline__media__image lazy"
                         data-original="{{ Image::url(\App\checkImage($item->image), 788, 443, array('crop')) }}"
                         width="788" height="443">
                </div>
            </a>
            <span class="content-timeline__media__icon content-timeline__media__icon--play-button"
                  data-for="video-{{ $item->id }}"><i class="material-icons">&#xE037;</i></span>
            <div class="content-timeline__media__iframe hide-mobile" id="video-{{ $item->id }}">
                <iframe frameborder="0" allowfullscreen="1" title="{{ $item->title }}" width="100%" height="100%"
                        src="https://www.youtube.com/embed/{{ \App\YoutubeID($item->video) }}"></iframe>
            </div>
            <div class="content-timeline__media__detail hide-phone">
                <div class="detail-content">
                    <div class="detail-content__row">
                        <div class="detail-content__category"><h5>{{ $item->category()->first()->title }}</h5></div>
                        {{-- <div class="detail-content__time"><time>{{ $item->created_at->diffForHumans() }}</time></div> --}}
                    </div>
                    <div class="detail-content__row">
                        <div class="detail-content__title"><h3>{{ $item->title }}</h3></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-timeline__detail hide visible-phone">
            <div class="content-timeline__detail__container">
                <a href="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}" class="clearfix"
                   title="{{ $item->title }}">
                    <div class="content-timeline__detail--top">
                        <h5 class="content-timeline__detail__category">{{ $item->category()->first()->title }}</h5>
                        <span class="content-timeline__detail__time hide"><time>{{ $item->created_at->diffForHumans() }}</time></span>
                    </div>
                    <h3 class="content-timeline__detail__title">{{ $item->title }}</h3>
                </a>
                <div class="content-timeline__detail__social-media has-dropdown"
                     data-target="share-dropdown--{{ $item->id }}" data-align="right-bottom">
                    <i class="material-icons">&#xE15E;</i>
                    <div class="share-dropdown share-dropdown--{{ $item->id }}  dropdown-container">
                        <ul>
                            <li class="dropdown-container__item ripple wt-share-button" data-share-type="facebook"
                                data-type="video" data-id="{{ $item->id }}" data-post-url="/update-share"
                                data-title="{{ $item->title }}"
                                data-sef="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}">
                                <span class="share-dropdown__icon share-dropdown__icon--facebook"></span>
                                <span class="share-dropdown__title">Facebook</span>
                            </li>
                            <li class="dropdown-container__item ripple wt-share-button" data-share-type="twitter"
                                data-type="video" data-id="{{ $item->id }}" data-post-url="/update-share"
                                data-title="{{ $item->title }}"
                                data-sef="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}">
                                <span class="share-dropdown__icon share-dropdown__icon--twitter"></span>
                                <span class="share-dropdown__title">Twitter</span>
                            </li>
                            <li class="dropdown-container__item ripple wt-share-button hide-phone"
                                data-share-type="gplus" data-type="video" data-id="{{ $item->id }}"
                                data-post-url="/update-share" data-title="{{ $item->title }}"
                                data-sef="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}">
                                <span class="share-dropdown__icon share-dropdown__icon--google"></span>
                                <span class="share-dropdown__title">Google +</span>
                            </li>
                            <li class="dropdown-container__item ripple wt-share-button visible-phone"
                                data-share-type="whatsapp" data-type="video" data-id="{{ $item->id }}"
                                data-post-url="/update-share" data-title="{{ $item->title }}"
                                data-sef="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}">
                                <span class="share-dropdown__icon share-dropdown__icon--whatsapp"></span>
                                <span class="share-dropdown__title">Whatsapp</span>
                            </li>
                            <li class="dropdown-container__item ripple wt-share-button" data-share-type="mail"
                                data-type="video" data-id="{{ $item->id }}" data-post-url="/update-share"
                                data-title="{{ $item->title }}"
                                data-sef="{{ route('show_video',str_slug($item->title).'-'.$item->id) }}">
                                <span class="share-dropdown__icon share-dropdown__icon--mail"></span>
                                <span class="share-dropdown__title">Email</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>