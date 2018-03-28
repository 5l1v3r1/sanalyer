@extends('layouts.master')
@section('css')
    <link href="{{ asset('/assets/default/css/other-pages.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-title"><h1>İletişim</h1></div>
            <div class="content-body">
                <div class="content-body__detail">
                    <p><strong>Adres</strong>:&nbsp;Çalıkuşu Mahallesi 3178 sk. No:7/A
                        &nbsp;Karabağlar /
                        İzmir</p>
                    <p><strong>Telefon</strong> : +90 545 787 89 18</p>
                    <p><em>( Sayın okurumuz ürün satışı, teknik destek vb. konularda lütfen aramayınız,
                            sanalyer.com'da bu gibi hizmetler verilmemektedir.&nbsp;)</em></p>
                    <p><strong>Eposta</strong> : info@sanalyer.com</p>

                </div>
            </div>
        </div>
        @include('frontend.static_page.inc.rightMenu')
        <div class="sidebar hide-mobile">
        </div>
    </div>
@endsection