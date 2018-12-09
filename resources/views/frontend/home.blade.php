@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/home.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    @include('layouts.partials.slider')
    <div class="global-container container">
        <div class="content">

            <div class="content-timeline ">
                <div class="content-timeline__tab">
                    <h3 class="content-timeline__tab__title global-title">Teknoloji Haberleri</h3>
                    <div class="content-timeline__tab__rss hide-phone"><a href="{{ env('APP_URL') }}/rss.xml"
                                                                          target="_blank"><i class="material-icons">&#xE0E5;</i></a>
                    </div>
                    <div class="content-timeline__tab__filter">
                        <span class="content-timeline__tab__filter__title hide-mobile">Filtrele:</span>
                        <select id="timeline-filter" class="content-timeline__tab__filter__select"
                                placeholder="Filtrele:" type="radio" name="category">
                            <option value="all" selected>TÜMÜ</option>
                            <option value="news">HABER</option>
                            <option value="video">VİDEO</option>
                        </select>
                    </div>
                </div>
                <div class="content-timeline__list">
                    @foreach($posts as $item)
                        @if($item->type == 0)
                            @include('frontend.inc.widgets.posts')
                        @elseif($item->type == 1)
                            @include('frontend.inc.widgets.posts_video')
                        @endif
                    @endforeach
                </div>
                <div class="content-timeline__more">
                    <i class="content-timeline__more__icon material-icons">&#xE5D5;</i>
                    <span class="content-timeline__more__text">DAHA FAZLA GÖSTER</span>
                </div>
                <div class="content-spinner">
                    <svg class="spinner-container" width="45px" height="45px" viewBox="0 0 52 52">
                        <circle class="path" cx="26px" cy="26px" r="20px" fill="none" stroke-width="4px"></circle>
                    </svg>
                </div>
            </div>
        </div>
        @include('layouts.partials.sidebar')
    </div>
@endsection

@section('script')
    <script>
        $(function () {

            $('.content-timeline__item').wtTimeline({
                header: '.header',
                container: '.content-timeline__list',
                filter: '#timeline-filter',
                spinner: '.content-spinner',
                loadMore: '.content-timeline__more',
                path: '{{ route('ajax::get_content') }}',
                limit: 3,
                filterType: 'all'
            });

        });
    </script>
@endsection