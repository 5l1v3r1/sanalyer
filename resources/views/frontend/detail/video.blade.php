@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/video-detail.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
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

    <div class="video-showcase">
        <div class="video-showcase__content">
            <div class="content-breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                {{ Breadcrumbs::render('post', $posts->id) }}
            </div>
            <div class="video-showcase__content__title">
                <h1>{{ $posts->title }}</h1>
                {{--   <div class="content-favorite visible-phone">
                      <span class="content-favorite__button favorite" data-action="add" data-type="video" data-id="{{ $posts->id }}">
                          <i class="material-icons">&#xE867;</i>
                          <!-- <i class="material-icons">&#xE866;</i> -->
                      </span>
                  </div>--}}
            </div>
            <div class="video-showcase__content__row">
                <div class="video-showcase__content--left ">
                    <div class="video-showcase__content--left__video">
                        <div class="video-showcase__content--left__video__player">
                            <div class="video-showcase__content--left__video__player__item video-player"
                                 id="video-player" data-autoplay="1" data-width="100%" data-height="100%"
                                 data-id="{{ \App\YoutubeID($posts->video) }}" data-state="true"
                                 data-next="{{ route('show_video',str_slug($prev[0]->title).'-'.$prev[0]->id) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="video-showcase__content--right">
                    <div class="video-showcase__content--right__options hide-mobile">
                        <div class="video-showcase__content--right__options__subscribe">
                            <div class="g-ytsubscribe" data-channel="sanalyertv" data-layout="default"
                                 data-theme="default" data-count="hidden"></div>
                        </div>
                        <div class="material-switch material-switch--small">
                            <label>
                                Otomatik oynat
                                <input class="autoplay" type="checkbox">
                                <span class="lever"></span>
                            </label>
                        </div>

                        <div class="countdown-container" style="display:none"><span class="countdown">5</span> saniye
                            içinde yeni videoya yönlendirileceksiniz
                        </div>
                    </div>
                    <div class="video-showcase__content--right__scrollable">
                        <ul class="video-showcase__content--right__video-list">
                            <li class="video-showcase__content--right__video-list__item active"
                                id="video-{{ $posts->id }}">
                                <a href="{{ route('show_video',$posts->full_url) }}">
                                    <figure class="video-showcase__content--right__video-list__item__image">
                                        <img src="{{ Image::url(\App\checkImage($posts->image), 100, 56, array('crop')) }}"
                                             alt="{{ $posts->title }}">
                                        <span class="video-showcase__content--right__video-list__item__image__icon"><i
                                                    class="material-icons">&#xE039;</i></span>
                                    </figure>
                                    <div class="video-showcase__content--right__video-list__item__title">
                                        {{ $posts->title }}
                                    </div>
                                </a>
                            </li>
                            @foreach($prev as $item)
                                <li class="video-showcase__content--right__video-list__item "
                                    id="video-{{ $item->id }}">
                                    <a href="{{ route('show_video',$item->full_url) }}">
                                        <figure class="video-showcase__content--right__video-list__item__image">
                                            <img src="{{ Image::url(\App\checkImage($item->image), 100, 56, array('crop')) }}"
                                                 alt="{{ $item->title }}">
                                            <span class="video-showcase__content--right__video-list__item__image__icon"><i
                                                        class="material-icons">&#xE039;</i></span>
                                        </figure>
                                        <div class="video-showcase__content--right__video-list__item__title">
                                            {{ $item->title }}
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="global-container container">
        <div class="content">

            <article role="main" itemscope itemtype="http://schema.org/VideoObject" class="video" data-type="video"
                     data-id="{{ $posts->id }}">
                <meta itemprop="mainEntityOfPage"
                      content="{{ route('show_video',$posts->full_url) }}">
                <meta itemprop="url"
                      content="{{ route('show_video',$posts->full_url) }}"/>
                <meta itemprop="thumbnailUrl"
                      content="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}"/>
                <meta itemprop="thumbnail"
                      content="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}">
                <meta itemprop="image"
                      content="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}"/>
                <meta itemprop="uploadDate" content="{{ $posts->created_at->format(DateTime::ATOM) }}"/>
                <meta itemprop="embedURL" content="{{ 'http://www.youtube.com/embed/'.\App\YoutubeID($posts->video) }}">
                <meta itemprop="description"
                      content="{{ $postDesc }}">
                <meta itemprop="datePublished" content="{{ $posts->created_at->format(DateTime::ATOM) }}"/>
                <meta itemprop="dateModified" content="{{ $posts->updated_at->format(DateTime::ATOM) }}"/>
                <meta itemprop="headline"
                      content="{{ $posts->title }}">
                <meta itemprop="name"
                      content="{{ $posts->title }}">


                <div class="content-info">
                   <span itemprop="author" itemscope itemtype="http://schema.org/Person"><a
                               href="{{ $posts->user->profileUrl() }}" itemprop="name"
                               class="content-info__author">{{ $posts->user->firstname }}</a></span>
                    <span class="content-info__line">—</span>
                    <time class="content-info__date" itemprop="datePublished"
                          datetime="{{ $posts->created_at->format(DateTime::ATOM) }}">{{ $posts->created_at->diffForHumans() }}
                    </time>
                </div>

                <div class="content-body">
                    <div class="content-body--left hide-phone">
                        <div class="content-sticky">
                            <div class="content-share">
                                <a class="content-share__item facebook wt-share-button" data-share-type="facebook"
                                   data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                   data-title="{{ $posts->title }}"
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
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
                                                 data-uri="{{ route('show_video',$posts->full_url) }}"></div>
                                        </li>
                                    </ul>
                                </div>
                                <a class="content-share__item twitter wt-share-button" data-share-type="twitter"
                                   data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                   data-title="{{ $posts->title }}"
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
                                    <div class="content-share__icon twitter-white"></div>
                                    <div class="content-share__badge wt-share-badge-twitter  hide-phone"></div>
                                </a>
                                <a class="content-share__item whatsapp wt-share-button visible-phone"
                                   data-type="news" data-id="{{ $posts->id }}" data-share-type="whatsapp"
                                   data-post-url="/update-share" data-title="{{ $posts->title }}"
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
                                    <div class="content-share__icon whatsapp-white"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body--right">
                    <figure class="content-body__image" itemprop="image" itemscope=""
                            itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url"
                              content="{{ Image::url(\App\checkImage($posts->image), 788, 443, array('crop')) }}">
                        <meta itemprop="width" content="788">
                        <meta itemprop="height" content="443">
                    </figure>
                    <div class="content-body__description" itemprop="description">
                        {!! $postDesc !!}
                    </div>
                    <div class="content-body__detail" itemprop="articleBody">
                        <!-- content-ads -->
                        <div class="ads ads-300x250-content visible-mobile">
                            @include('ads.post_mobile_300_250')
                        </div>
                        {!! $postContent !!}
                        <div class="bottom-new-video"></div>


                        <div class="hide-mobile" style="margin: 10px 0px">
                            @include('ads.video_footer_728_90')
                        </div>


                        <div class="visible-mobile">
                            @include('ads.video_footer_728_90')
                        </div>

                    </div>


                @if($posts->tag != null)
                    <!-- tags -->
                        <div class="content-tags hide-mobile">
                            <b>Etiketler: </b>
                            @foreach(explode(',', $posts->tag) as $info)
                                <a href="{{ route("tag",$info) }}" title="{{ $info }}">{{ $info }}</a>,
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
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
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
                                                 data-uri="{{ route('show_video',$posts->full_url) }}"></div>
                                        </li>
                                    </ul>
                                </div>
                                <a class="content-share__item twitter wt-share-button" data-share-type="twitter"
                                   data-type="news" data-id="{{ $posts->id }}" data-post-url="/update-share"
                                   data-title="{{ $posts->title }}"
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
                                    <div class="content-share__icon twitter-white"></div>
                                    <div class="content-share__badge wt-share-badge-twitter  hide-phone"></div>
                                </a>
                                <a class="content-share__item whatsapp wt-share-button visible-phone"
                                   data-type="news" data-id="{{ $posts->id }}" data-share-type="whatsapp"
                                   data-post-url="/update-share" data-title="{{ $posts->title }}"
                                   data-sef="{{ route('show_video',$posts->full_url) }}">
                                    <div class="content-share__icon whatsapp-white"></div>
                                </a>
                            </div>
                            <div class="content-favorite hide-phone">
                                        <span class="content-favorite__button favorite" data-action="add"
                                              data-type="news" data-id="{{ $posts->id }}"><i class="material-icons">&#xE866;</i></span>
                            </div>

                        </div>
                    </div>

                    <div class="thread-section" data-thread-id="{{ $posts->id }}"></div>

                </div>
            </article>
        </div>


        @include('layouts.partials.sidebar')
    </div>



@endsection

@section('script')


    <script>


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

    </script>

@endsection