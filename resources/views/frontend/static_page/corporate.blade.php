@extends('layouts.master')
@section('css')
    <link href="{{ asset('/assets/default/css/other-pages.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <div class="global-container container">
        <div class="content">
            <div class="content-title"><h1>Künye</h1></div>
            <div class="content-body">
                <div class="content-body__detail">
                    <div class="copyright--row">
                        <span class="copyright--row__label">Ünvanı:</span>
                        <a href="https://www.radkod.com" target="_blank" class="copyright--row__text">RadKod</a>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">Yazışma Adresi:</span>
                        <span class="copyright--row__text">Çalıkuşu Mahallesi 3178 sk. No:7/A</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">Telefon</span>
                        <span class="copyright--row__text">0545 787 8918</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">E-Posta Adresi:</span>
                        <span class="copyright--row__text">info@sanalyer.com</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">İmtiyaz Sahibi:</span>
                        <span class="copyright--row__text">Furkan Yurdakul</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">Editörler:</span>
                        <span class="copyright--row__text">Abdullah Bozdağ</span>
                        <span class="copyright--row__text">Murat Özakçil</span>
                        <span class="copyright--row__text">Selim Doyranlı</span>
                        <span class="copyright--row__text">Cihangir Karamuk</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">Yazılım:</span>
                        <span class="copyright--row__text">Abdullah Bozdağ</span>
                    </div>
                    <div class="copyright--row">
                        <span class="copyright--row__label">Tasarım:</span>
                        <span class="copyright--row__text">Selim Doyranlı</span>
                        <span class="copyright--row__text">Murat Baysal</span>
                        <span class="copyright--row__text">Mehmet Ali Öztekin</span>
                    </div>
                </div>
            </div>
        </div>
        @include('frontend.static_page.inc.rightMenu')
        <div class="sidebar hide-mobile">
        </div>
    </div>
@endsection