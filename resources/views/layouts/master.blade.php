<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {!! app('seotools')->generate() !!}
    <base href="{{ route('home') }}" >
    @include('frontend.inc.feature')
    <link rel="stylesheet" href="{{ asset('assets/default/packages/sweetalert/sweetalert.css') }}">
    <script src="{{ asset("js/tinymce/tinymce.min.js") }}"></script>
    <script src="{{ asset("js/tinymce/init-tinymce-radkod.js?v=2") }}"></script>
    </head>
<body>
@include('layouts.partials.header')
<div class="wt-container">
    @yield('content')
</div>

@include('layouts.partials.footer')