<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Admin</title>
    <base href="{{ route('home') }}" >
    <link rel="stylesheet" href="{{ asset('assets/default/packages/sweetalert/sweetalert.css') }}">
</head>
<body>
<div class="container">
    @yield('content')
</div>
<script src="{{ asset('/assets/default/packages/sweetalert/sweetalert.js') }}"></script>
@include('sweet::alert')
</body>
</html>