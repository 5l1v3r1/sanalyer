@extends('layouts.master')
@section('css')
    <link href="{{ \App\cdnAsset('/assets/default/css/other-pages.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-title"><h1>Hakkımızda</h1></div>
            <div class="content-body">
                <div class="content-body__detail">
                    Sanalyer Bir <a href="https://www.radkod.com" target="_blank" title="Freelance Web Tasarım">RadKod</a> Projesi olup sürekli geliştirme aşamasındadır.<a href="http://sanalyer.com" target="_blank" title=""></a> <br>
                    <br>
                    Türkiye ve Dünyayı yakından takip eden bir aile ve RadKod ekibi olarak Türkiye, İstanbul’dan Türkiye'den ve dünyadan tüm <a href="{{ route('home') }}" title="teknoloji haberleri">teknoloji haberleri</a> , son dakika gelişmelerini ve haber niteliği taşıyan tüm bilgileri okuyucularımızla paylaşmak ve onlara doğru ve tarafsız haberleri sunmak amacı ile yola koyulduk.<br>
                    <br>
                    Amacımız kaliteli ve sorunsuz hizmet vermeye çalışmaktır. sanalyer.com sizlere web site konusunda aklınıza gelecek,haber sitelerinden birini olmasını temenni ediyoruz. <br>
                    <br>
                    Sitemizin tanınması,hit kazanması ve ziyaretçilerimizin beğen görmesi sitenin ve bizim için en önemli unsur olup,Bu nedenle web sitemizin reklama ve desteğe ihtiyacı olmaktadır.Sizlerinde desteği ile büyük bir arşiv olmayı ümit ediyoruz... <br>
                    <br>
                    Ayrıca web sitemizin hakkında görüş,şikayet,öneri vs. işlemleri için İletişim sayfasından bildirmeniz,sitedeki hata ve eksikliklerin giderilmesinde daha kaliteli bir noktaya getirecektir. <br>
                    <br>
                    Web sitemizin yapısına gelince kullanımı kolay ve her yaş grubuna hitap edildiğini düşünüyoruz. Web sitemizin kullanım alanları ise Teknoloji Haberleri,Gündem deki haberler, Spor Haberleri ve makalelerden yararlana bileceksiniz.
                </div>
            </div>
        </div>
        @include('frontend.static_page.inc.rightMenu')
        <div class="sidebar hide-mobile">
        </div>
    </div>
@endsection