@extends('layouts.master')

@section('css')
    <link href="{{ asset('/assets/default/css/user-profile.css?v=2.3.4') }}" rel="stylesheet" media="all"/>
@endsection

@section('content')
    <style>
        .flash-success {
            background: #DCEDC8;
            position: relative;
            width: 100%;
            display: block;
            padding: 14px 16px;
            color: #689F38;
            border-radius: 2px;
            margin-bottom: 10px;
            font-size: .875em;
            line-height: normal;
            font-weight: 500;
        }
    </style>

    <div class="global-container container">
        <div class="content-profile">
        <div class="content-profile__title"><h1>Şifremi Unuttum</h1></div>
        <div class="content-profile__notice">
            Yeni şifre belirlemek için kayıtlı e-posta adresinizi yazınız.<br>
            Şifre değiştirme linkini e-posta adresinize göndereceğiz.
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        <div class="content-profile__form">
            <form class="no-enter" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="content-profile__form-element required">
                    <input class="email" type="text" name="email"  value="{{ old('email') }}" placeholder="E-posta adresi" data-validation="server required email" data-validation-url="{{ route('ajax::email_check') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="content-profile__form-element">
                    <button class="content-profile__form-button content-profile__form-button--signin ripple" type="submit">GÖNDER</button>
                </div>
            </form>
        </div>
    </div>
    </div>

@endsection