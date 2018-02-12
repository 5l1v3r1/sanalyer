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
            <div class="content-profile__title"><h1>Giriş</h1></div>
            <div class="content-profile__notice">
                Sanalyer Alemine Giriş Yap
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <div class="content-profile__form">

                <form class="no-enter" method="POST" action="{{ route('loginPost') }}">
                    {{ csrf_field() }}
                    <div class="login__form-element">
                        <input name="email" placeholder="E-Posta Adresi" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block"> <strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>

                    <div class="login__form-element">
                        <input type="password" name="password" placeholder="Şifre" value="{{ old('password') }}">
                    </div>
                    <div class="login__form-element">
                        <label>
                            <input type="checkbox" style="width: 5%;"
                                   name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Beni Hatırla
                        </label>
                    </div>
                    <div class="login__form-element"><a href="{{ route('password.request') }}">Şifremi unuttum</a></div>
                    <div class="login__form-element">
                        <button class="login__form-button login__form-button--signin ripple" type="submit">GİRİŞ YAP</button>
                    </div>
                    <div class="login__form-signup">Üyeliğiniz yoksa <a href="{{ route('register') }}" class="modal-button" >yeni üyelik oluşturun</a>.</div>
                </form>
            </div>
        </div>
    </div>

@endsection