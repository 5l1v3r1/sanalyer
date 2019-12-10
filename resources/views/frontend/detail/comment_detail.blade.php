@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/news-detail.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('/assets/default/css/content-list.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-timeline ">
                <div class="content-timeline__tab">
                    <h3 class="content-timeline__tab__title global-title">
                        <a href="{{ route('show_post',$post->full_url) }}"
                           title="{{ $post->title }}">{{ $post->title }}</a> Yorumları
                    </h3>
                </div>
                <div class="content-comments">
                    <div class="content-comments__list" id="thread">
                        @foreach($comments as $comment)
                            <div class="content-comments__item clearfix" id="comment-thread-{{ $comment->id }}">
                                @include('frontend.inc.widgets.comment')
                                @if($comment->children->count() > 0)
                                    @include('frontend.inc.widgets.commentChildren')
                                @endif
                            </div>
                    </div>
                    @endforeach
                    <center>
                        {{ $comments->links('vendor.pagination.sanalyer') }}
                    </center>
                </div>
            </div>

        </div>
        @include('layouts.partials.sidebar')
    </div>
@endsection

@section('script')

@endsection