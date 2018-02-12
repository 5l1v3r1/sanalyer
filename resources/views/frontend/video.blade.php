@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/content-list.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <div class="global-container container">
        <div class="content">

            <div class="content-timeline ">
                <div class="content-timeline__tab">
                    <h3 class="content-timeline__tab__title global-title">Tüm Videolar</h3>
                    <div class="content-timeline__tab__rss hide-phone"><a href="{{ env('APP_URL') }}/rss.xml"
                                                                          target="_blank"><i class="material-icons">&#xE0E5;</i></a>
                    </div>

                </div>
                <div class="content-timeline__list">
                    @foreach($posts as $item)
                        @include('frontend.inc.widgets.postsSmallVideo')
                    @endforeach
                </div>
                <center>
                    {{ $posts->links('vendor.pagination.sanalyer') }}
                </center>
            </div>
        </div>
        @include('layouts.partials.sidebar')
    </div>
@endsection

@section('script')

@endsection