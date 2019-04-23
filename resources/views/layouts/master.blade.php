<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {!! app('seotools')->generate() !!}
    <meta name="google-play-app" content="app-id=com.radkod.sanalyer">
    <base href="{{ route('home') }}">
    @include('frontend.inc.feature')
    <link rel="stylesheet" href="{{ asset('assets/default/packages/sweetalert/sweetalert.css') }}">
    <script src="{{ asset("js/tinymce/tinymce.min.js") }}"></script>
    <script src="{{ asset("js/tinymce/init-tinymce-radkodV5.js") }}"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <link rel="dns-prefetch" href="https://cdn.sanalyer.com"/>
    <link rel="preconnect" href="https://cdn.sanalyer.com"/>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <!-- global reklam -->
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-2344798961183900",
            enable_page_level_ads: true
        });
        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];
    </script>
	
	<!-- Admatic Scroll 300x250 Ad Code START -->
	<ins data-publisher="adm-pub-177662955945" data-ad-type="Scroll" class="adm-ads-area" data-ad-network="160412231757" data-ad-sid="304" data-ad-width="300" data-ad-height="250"></ins>
	<script src="//cdn2.admatic.com.tr/showad/showad.js" async></script>
	<!-- Admatic Scroll 300x250 Ad Code END -->

    <!-- Admatic imageplus x Ad Code START -->
    <ins data-publisher="adm-pub-177662955945" data-ad-type="imageplus" class="adm-ads-area" data-ad-network="160412231757" data-ad-sid="400"></ins>
    <script src="//cdn2.admatic.com.tr/showad/showad.js" async></script>
    <!-- Admatic imageplus x Ad Code END -->

</head>
<body>
@include('ads.body_970_250')
@include('layouts.partials.header')
<div class="wt-container">
    @yield('content')
</div>

@include('layouts.partials.footer')
