@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/news-detail.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
    <link rel="amphtml" href="{{ route('show_post_amp',str_slug($posts->title).'-'.$posts->id) }}">
@endsection

@php
    $word = str_word_count(strip_tags($posts->content));
    $m = floor($word / 200);
    $s = floor($word % 200 / (200 / 60));
    $est = $m;
    if($est == 0){
        $est = '1';
    }
@endphp

@section('content')
    <style>
        .flash-success {
            background: #DCEDC8;
            position: relative;
            width: 100%;
            display: block;
            padding: 14px 16px;
            color: #689F38;
            border-radius: 2px;
            margin-bottom: 10px;
            font-size: .875em;
            line-height: normal;
            font-weight: 500;
        }
    </style>

    <div class="global-container container">
        <div class="content">
            <div class="news content-detail-page">
                <article role="main" itemscope itemtype="http://schema.org/NewsArticle" class="news__item"
                         data-prevTitle="{{ $prev->title }}"
                         data-prevImage="{{ Image::url(\App\checkImage($prev->image), 96, 96, array('crop')) }}"
                         data-type="news" data-id="{{ $prev->id }}"
                         data-url="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}"
                         data-title="{{ $posts->title }}"
                         data-description="{{ $postDesc }}"
                         data-keywords="" data-share="0" data-amp="http://www.sanalyer.com/haber/amp/{{ $prev->id }}">
                    <meta itemprop="mainEntityOfPage"
                          content="{{ route('show_post',str_slug($prev->title).'-'.$prev->id) }}">
                    <meta itemprop="datePublished" content="{{ $prev->created_at->format(DateTime::ATOM) }}"/>
                    <meta itemprop="dateModified" content="{{ $prev->updated_at->format(DateTime::ATOM) }}"/>
                    <meta itemprop="inLanguage" content="tr-TR"/>
                    <meta itemprop="genre" content="news" name="medium"/>


                    <div class="content-breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        {{ Breadcrumbs::render('post', $posts->id) }}
                    </div>

                    <div class="content-title">
                        <h1 itemprop="headline"><a
                                    href="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}"
                                    title="{{ $posts->title }}">{{ $posts->title }}</a></h1>
                    </div>

                    <div class="content-info clearfix">
                        <div class="content-author">
                            <span itemprop="author" itemscope itemtype="http://schema.org/Person"><a
                                        href="{{ env('APP_URL') }}/yazar/enesk" itemprop="name"
                                        class="content-info__author">{{ $posts->user->firstname }}</a></span>
                            <span class="content-info__line">—</span>
                            <time class="content-info__date" itemprop="datePublished"
                                  datetime="{{ $posts->created_at->format(DateTime::ATOM) }}">{{ $posts->created_at->diffForHumans() }}
                            </time>
                        </div>
                        <div class="content-esimited-read">
                            {{ $est }} dk okuma süresi
                        </div>
                        <div class="content-font hide-phone">
                            <div class="content-font__item" data-action="minus">
                                <span class="content-font__item__icon content-font__item__icon--minus"></span>
                            </div>
                            <div class="content-font__item" data-action="plus">
                                <span class="content-font__item__icon content-font__item__icon--plus"></span>
                            </div>
                        </div>
                    </div>

                    <div class="content-body">
                        <div class="content-body--left hide-phone">
                            <div class="content-sticky">
                                <div class="content-share">
                                    <a class="content-share__item facebook wt-share-button" data-share-type="facebook"
                                       data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                       data-title="{{ $posts->title }}"
                                       data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                        <div class="content-share__icon facebook-white"></div>
                                        <div class="content-share__badge wt-share-badge-facebok  hide-phone"></div>
                                    </a>
                                    <div class="content-share__item facebook-save has-dropdown"
                                         data-target="facebook-save-dropdown-{{ $posts->id }}" data-align="left-bottom">
                                        <i class="material-icons">&#xE866;</i>
                                    </div>
                                    <div class="facebook-save-dropdown facebook-save-dropdown-{{ $posts->id }} dropdown-container">
                                        <ul>
                                            <li class="dropdown-container__item">
                                                <div class="fb-save"
                                                     data-uri="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}"></div>
                                            </li>
                                        </ul>
                                    </div>
                                    <a class="content-share__item twitter wt-share-button" data-share-type="twitter"
                                       data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                       data-title="{{ $posts->title }}"
                                       data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                        <div class="content-share__icon twitter-white"></div>
                                        <div class="content-share__badge wt-share-badge-twitter  hide-phone"></div>
                                    </a>
                                    <a class="content-share__item whatsapp wt-share-button visible-phone"
                                       data-type="news" data-id="{{ $posts->id }}" data-share-type="whatsapp"
                                       data-post-url="/update-share" data-title="{{ $posts->title }}"
                                       data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                        <div class="content-share__icon whatsapp-white"></div>
                                    </a>
                                </div>

                                {{--
                                 <div class="content-smile hide-phone" data-content-type="news"
                                      data-content-id="{{ $posts->id }}">
                                     <div class="content-smile__item" data-content-id="{{ $posts->id }}"
                                          data-content-type="news"
                                          data-smile-type="sad">
                                         <span class="content-smile__icon content-smile__icon--active content-smile__icon--sad"></span>
                                         <span class="content-smile__count">5</span>
                                     </div>
                                     <div class="content-smile__list">
                                         <div class="content-smile__list__item content-smile__item"
                                              data-content-id="{{ $posts->id }}"
                                              data-content-type="news"
                                              data-smile-type="laugh">
                                             <span class="content-smile__icon content-smile__icon--laugh"></span>
                                             <span class="content-smile__count">4</span>
                                         </div>
                                         <div class="content-smile__list__item content-smile__item"
                                              data-content-id="{{ $posts->id }}"
                                              data-content-type="news"
                                              data-smile-type="amazing">
                                             <span class="content-smile__icon content-smile__icon--amazing"></span>
                                             <span class="content-smile__count">2</span>
                                         </div>
                                         <div class="content-smile__list__item content-smile__item"
                                              data-content-id="{{ $posts->id }}"
                                              data-content-type="news"
                                              data-smile-type="shy">
                                             <span class="content-smile__icon content-smile__icon--shy"></span>
                                             <span class="content-smile__count">1</span>
                                         </div>
                                         <div class="content-smile__list__item content-smile__item"
                                              data-content-id="{{ $posts->id }}"
                                              data-content-type="news"
                                              data-smile-type="angry">
                                             <span class="content-smile__icon content-smile__icon--angry"></span>
                                             <span class="content-smile__count">0</span>
                                         </div>
                                     </div>
                                 </div>--}}


                            </div>
                        </div>
                        <div class="content-body--right">
                            <figure class="content-body__image" itemprop="image" itemscope=""
                                    itemtype="https://schema.org/ImageObject">
                                <img class="lazyloaded"
                                     data-original="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}"
                                     alt="{{ $posts->title }}" width="788"
                                     src="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}"
                                     style="display: block;">
                                <meta itemprop="url"
                                      content="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}">
                                <meta itemprop="width" content="788">
                                <meta itemprop="height" content="443">
                            </figure>
                            <div class="content-body__description" itemprop="description">
                                {!! $postDesc !!}
                            </div>


                            <div id="vidout_inread"></div>
                            <div class="content-body__detail" itemprop="articleBody">
                                <!-- content-ads -->
                                <div class="ads ads-300x250-content visible-mobile">
                                    Mobil Reklam
                                </div>
                                {!! $postContent !!}
                                <div class="bottom-new-video"></div>


                                <div class="hide-mobile" style="margin: 10px 0px">
                                    reklalm
                                </div>


                                <div class="visible-mobile">
                                    reklam mobil
                                </div>

                            </div>

                        {{--
                        <!-- source url -->
                        <div class="content-source-url clearfix">
                            <span class="content-source-url__subtitle">Kaynak : </span>
                            <span class="content-source-url__content">https://www.engadget.com/2018/01/08/facebook-m-ai-assistant-closure/</span>
                        </div>
                        --}}


                        @if($posts->tag != null)
                            <!-- tags -->
                                <div class="content-tags hide-mobile">
                                    <b>Etiketler: </b>
                                    @foreach(explode(',', $posts->tag) as $info)
                                        <a href="/etiket/{{ str_slug($info) }}" title="{{ $info }}">{{ $info }}</a>,
                                    @endforeach
                                </div>
                        @endif

                        <!-- publisher -->
                            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                    <meta itemprop="url" content="{{ asset('icon-logo.png') }}"/>
                                    <meta itemprop="width" content="256"/>
                                    <meta itemprop="height" content="256"/>
                                </div>
                                <meta itemprop="name" content="{{ env('APP_NAME') }}"/>
                            </div>

                            <div class="content-share-mobile visible-phone">
                                <div class="content-sticky">
                                    <div class="content-share">
                                        <a class="content-share__item facebook wt-share-button"
                                           data-share-type="facebook" data-type="news" data-id="{{ $posts->id }}"
                                           data-post-url="/update-share" data-title="{{ $posts->title }}"
                                           data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                            <div class="content-share__icon facebook-white"></div>
                                            <div class="content-share__badge wt-share-badge-facebok  hide-phone"></div>
                                        </a>
                                        <div class="content-share__item facebook-save has-dropdown"
                                             data-target="facebook-save-dropdown-{{ $posts->id }}"
                                             data-align="left-bottom">
                                            <i class="material-icons">&#xE866;</i>
                                        </div>
                                        <div class="facebook-save-dropdown facebook-save-dropdown-{{ $posts->id }} dropdown-container">
                                            <ul>
                                                <li class="dropdown-container__item">
                                                    <div class="fb-save"
                                                         data-uri="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}"></div>
                                                </li>
                                            </ul>
                                        </div>
                                        <a class="content-share__item twitter wt-share-button" data-share-type="twitter"
                                           data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                           data-title="{{ $posts->title }}"
                                           data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                            <div class="content-share__icon twitter-white"></div>
                                            <div class="content-share__badge wt-share-badge-twitter  hide-phone"></div>
                                        </a>
                                        <a class="content-share__item whatsapp wt-share-button visible-phone"
                                           data-type="news" data-id="{{ $posts->id }}" data-share-type="whatsapp"
                                           data-post-url="/update-share" data-title="{{ $posts->title }}"
                                           data-sef="{{ route('show_post',str_slug($posts->title).'-'.$posts->id) }}">
                                            <div class="content-share__icon whatsapp-white"></div>
                                        </a>
                                    </div>
                                    <div class="content-favorite hide-phone">
                                        <span class="content-favorite__button favorite" data-action="add"
                                              data-type="news" data-id="{{ $posts->id }}"><i class="material-icons">&#xE866;</i></span>
                                    </div>
                                    <div class="content-smile hide-phone" data-content-type="news"
                                         data-content-id="{{ $posts->id }}">
                                        <div class="content-smile__item" data-content-id="{{ $posts->id }}"
                                             data-content-type="news"
                                             data-smile-type="sad">
                                            <span class="content-smile__icon content-smile__icon--active content-smile__icon--sad"></span>
                                            <span class="content-smile__count">5</span>
                                        </div>
                                        <div class="content-smile__list">
                                            <div class="content-smile__list__item content-smile__item"
                                                 data-content-id="{{ $posts->id }}"
                                                 data-content-type="news"
                                                 data-smile-type="laugh">
                                                <span class="content-smile__icon content-smile__icon--laugh"></span>
                                                <span class="content-smile__count">4</span>
                                            </div>
                                            <div class="content-smile__list__item content-smile__item"
                                                 data-content-id="{{ $posts->id }}"
                                                 data-content-type="news"
                                                 data-smile-type="amazing">
                                                <span class="content-smile__icon content-smile__icon--amazing"></span>
                                                <span class="content-smile__count">2</span>
                                            </div>
                                            <div class="content-smile__list__item content-smile__item"
                                                 data-content-id="{{ $posts->id }}"
                                                 data-content-type="news"
                                                 data-smile-type="shy">
                                                <span class="content-smile__icon content-smile__icon--shy"></span>
                                                <span class="content-smile__count">1</span>
                                            </div>
                                            <div class="content-smile__list__item content-smile__item"
                                                 data-content-id="{{ $posts->id }}"
                                                 data-content-type="news"
                                                 data-smile-type="angry">
                                                <span class="content-smile__icon content-smile__icon--angry"></span>
                                                <span class="content-smile__count">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {{--
                       <div class="content-smile visible-phone clearfix" data-content-type="news"
                            data-content-id="{{ $posts->id }}">
                           <div class="content-smile__title global-title">Emoji İle Tepki Ver</div>
                           <div class="content-smile__item" data-content-id="{{ $posts->id }}"
                                data-content-type="news"
                                data-smile-type="sad">
                               <span class="content-smile__icon content-smile__icon--active content-smile__icon--sad"></span>
                               <span class="content-smile__count">5</span>
                           </div>
                           <div class="content-smile__list">
                               <div class="content-smile__list__item content-smile__item"
                                    data-content-id="{{ $posts->id }}"
                                    data-content-type="news"
                                    data-smile-type="laugh">
                                   <span class="content-smile__icon content-smile__icon--laugh"></span>
                                   <span class="content-smile__count">4</span>
                               </div>
                               <div class="content-smile__list__item content-smile__item"
                                    data-content-id="{{ $posts->id }}"
                                    data-content-type="news"
                                    data-smile-type="amazing">
                                   <span class="content-smile__icon content-smile__icon--amazing"></span>
                                   <span class="content-smile__count">2</span>
                               </div>
                               <div class="content-smile__list__item content-smile__item"
                                    data-content-id="{{ $posts->id }}"
                                    data-content-type="news"
                                    data-smile-type="shy">
                                   <span class="content-smile__icon content-smile__icon--shy"></span>
                                   <span class="content-smile__count">1</span>
                               </div>
                               <div class="content-smile__list__item content-smile__item"
                                    data-content-id="{{ $posts->id }}"
                                    data-content-type="news"
                                    data-smile-type="angry">
                                   <span class="content-smile__icon content-smile__icon--angry"></span>
                                   <span class="content-smile__count">0</span>
                               </div>
                           </div>
                       </div>--}}

                        <!-- new comment -->
                            <div class="thread-section" data-thread-id="{{ $posts->id }}"></div>

                        </div>
                    </div>
                </article>


            </div>
            <div class="content-spinner is-visible">
                <svg class="spinner-container" width="45px" height="45px" viewBox="0 0 52 52">
                    <circle class="path" cx="26px" cy="26px" r="20px" fill="none" stroke-width="4px"></circle>
                </svg>
            </div>
        </div>

        @include('layouts.partials.sidebar')
    </div>

    <div class="content-bottom">
        <div class="content-bottom__container">
            <a href="{{ route('show_post',str_slug($prev->title).'-'.$prev->id) }}"
               title="{{ $prev->title }}">
                <div class="content-bottom__image prev-image">
                    <img src="{{ Image::url(\App\checkImage($prev->image), 96, 96, array('crop')) }}"
                         alt="{{ $prev->title }}"></div>
                <div class="content-bottom__detail">
                    <span class="content-bottom__title">sıradaki haber:</span>
                    <span class="content-bottom__content-title prev-title">{{ $prev->title }}</span>
                    <div class="content-bottom__progress prev-progress"></div>
                </div>
            </a>
        </div>
    </div>

@endsection

@section('script')


    <script>
        if (isMobile) {
            $(function () {
                $(".news").wtScroll({item: ".news__item"});
            });
        }
        {{--
                $(function () {
                    var limit = $('.gallery-widget__container__item').length;
                    var current = 0;
                    $('.gallery-widget__prev').click(function () {
                        if (current > 0) {
                            current--;
                        } else {
                            current = limit - 1;
                        }

                        $('.gallery-widget__container__item').removeClass('is-active');
                        $('.gallery-widget__container__item').eq(current).addClass('is-active');
                        $('.gallery-widget__counter span').html(current + 1);
                    });
                    $('.gallery-widget__next').click(function () {
                        if (current >= limit - 1) {
                            current = 0;
                        } else {
                            current++;
                        }
                        $('.gallery-widget__container__item').removeClass('is-active');
                        $('.gallery-widget__container__item').eq(current).addClass('is-active');
                        $('.gallery-widget__counter span').html(current + 1);
                    });
                });
     --}}
    </script>

@endsection
