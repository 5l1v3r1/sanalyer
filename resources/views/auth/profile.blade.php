@extends('layouts.master')
@section('css')
    <link href="{{ asset('/assets/default/css/content-list.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection
@section('content')
    <div class="global-container container">
        <div class="content">
            <h1 class="global-title mt0 mb0">Editör Hakkında</h1>
            <div class="content-author" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                <div class="content-author__image" style="background-image: url('{{ Image::url(asset($user->photo ? '/rk_content/images/user-profile/' . $user->photo : '/rk_content/images/noavatar.png'), 72, 72, array('crop')) }}')"></div>
                <div class="content-author__detail">
                    <a href="{{ route('show_profile',$user->name.'-'.$user->id) }}" itemprop="name" class="content-author__detail__name"><span class="underline">{{ $user->firstname.' '.$user->lastname }}</span></a>
                    <div class="content-author__detail__social-media">
                        {{ $user->biography }}
                    </div>
                </div>
            </div>
            <div class="content-timeline ">
                <div class="content-timeline__tab">
                    <h3 class="content-timeline__tab__title global-title">Editörün İçerikleri</h3>
                </div>
                <div class="content-timeline__list">
                    @foreach($posts as $item)
                        @include('frontend.inc.widgets.posts')
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