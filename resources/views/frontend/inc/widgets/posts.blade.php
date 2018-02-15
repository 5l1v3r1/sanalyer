<div class="content-timeline__item">
    <div class="content-timeline--left">
        <div class="content-timeline__time">
            <a class="content-timeline__time__link"
               href="{{ route('show_category',$item->category()->first()->full_url) }}">
                <span class="content-timeline__time__icon content-timeline__time__icon--news tooltip"
                      data-tooltip="{{ $item->category()->first()->title }}">
                    <i class="material-icons">&#xE8B0;</i>
                </span>
            </a>
            <span class="content-timeline__time__timeago"><time
                        datetime="{{ $item->created_at->format(DateTime::ATOM) }}">{{ $item->created_at->diffForHumans() }}</time></span>
        </div>
    </div>
    <div class="content-timeline--right">
        <div class="content-timeline__media">
            <a href="{{ route('show_post',$item->full_url) }}">
                <div class="content-timeline__media--inset">
                    {{--  <img class="content-timeline__media__image lazy" data-original="{{ \App\checkImage($item->image) }}"
                          width="262" height="147">--}}
                    <img class="content-timeline__media__image lazy"
                         data-original="{{ Image::url(\App\checkImage($item->image), 262, 147, array('crop')) }}"
                         width="262" height="147"/>
                </div>
            </a>
        </div>
        <div class="content-timeline__detail">
            <div class="content-timeline__detail__container">
                <div class="content-timeline__detail--top">
                    <a href="{{ route('show_category',$item->category()->first()->full_url) }}"
                       class="content-timeline__link clearfix"
                       title="{{ $item->category()->first()->title }}">
                        <h5 class="content-timeline__detail__category">{{ $item->category()->first()->title }}</h5>
                    </a>
                    <span class="content-timeline__detail__time hide"><time
                                datetime="{{ $item->created_at->format(DateTime::ATOM) }}">{{ $item->created_at->diffForHumans() }}</time></span>
                </div>
                <a href="{{ route('show_post',$item->full_url) }}">
                    <h3 class="content-timeline__detail__title"><span
                                class="content-timeline--underline">{{ $item->title }}</span></h3>
                </a>
                <div class="content-timeline__detail--bottom">
                    <a class="content-timeline__detail__author hide-phone"
                       title="{{ $item->user()->first()->firstname }}">
                        {{ $item->user()->first()->firstname }}</a>

                    <div class="content-timeline__detail__social-media">
                        <span class="share-dropdown-button has-dropdown" data-target="share-dropdown--{{ $item->id }}"
                              data-align="left-bottom"><i class="material-icons">&#xE15E;</i></span>
                        <div class="share-dropdown share-dropdown--{{ $item->id }}  dropdown-container">
                            <ul>
                                <li class="dropdown-container__item ripple wt-share-button" data-share-type="facebook"
                                    data-type="news" data-id="{{ $item->id }}" data-post-url="/update-share"
                                    data-title="{{ $item->title }}"
                                    data-sef="{{ route('show_post',$item->full_url) }}">
                                    <span class="share-dropdown__icon share-dropdown__icon--facebook"></span>
                                    <span class="share-dropdown__title">Facebook</span>
                                </li>
                                <li class="dropdown-container__item ripple wt-share-button" data-share-type="twitter"
                                    data-type="news" data-id="{{ $item->id }}" data-post-url="/update-share"
                                    data-title="{{ $item->title }}"
                                    data-sef="{{ route('show_post',$item->full_url) }}">
                                    <span class="share-dropdown__icon share-dropdown__icon--twitter"></span>
                                    <span class="share-dropdown__title">Twitter</span>
                                </li>
                                <li class="dropdown-container__item ripple wt-share-button hide-phone"
                                    data-share-type="gplus" data-type="news" data-id="{{ $item->id }}"
                                    data-post-url="/update-share" data-title="{{ $item->title }}"
                                    data-sef="{{ route('show_post',$item->full_url) }}">
                                    <span class="share-dropdown__icon share-dropdown__icon--google"></span>
                                    <span class="share-dropdown__title">Google +</span>
                                </li>
                                <li class="dropdown-container__item ripple wt-share-button visible-phone"
                                    data-share-type="whatsapp" data-type="news" data-id="{{ $item->id }}"
                                    data-post-url="/update-share" data-title="{{ $item->title }}"
                                    data-sef="{{ route('show_post',$item->full_url) }}">
                                    <span class="share-dropdown__icon share-dropdown__icon--whatsapp"></span>
                                    <span class="share-dropdown__title">Whatsapp</span>
                                </li>
                                <li class="dropdown-container__item ripple wt-share-button" data-share-type="mail"
                                    data-type="news" data-id="{{ $item->id }}" data-post-url="/update-share"
                                    data-title="{{ $item->title }}"
                                    data-sef="{{ route('show_post',$item->full_url) }}">
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
</div>



