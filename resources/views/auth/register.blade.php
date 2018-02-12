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
            <div class="content-profile__title"><h1>Üye Ol</h1></div>
            <div class="content-profile__notice">
                Sanalyer Alemine Üye Ol
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <div class="content-profile__form">

                <form class="no-enter" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="login__form-element required">
                        <input type="text" name="firstname" placeholder="İsim" data-validation="required" value="{{ old('firstname') }}">
                        @if ($errors->has('firstname'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="login__form-element required">
                        <input type="text" name="lastname" placeholder="Soy İsim" data-validation="required" value="{{ old('lastname') }}">
                        @if ($errors->has('lastname'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="login__form-element required">
                        <input class="username" type="text" name="name" placeholder="Kullanıcı adı" data-validation="server required length" data-validation-length="max15" value="{{ old('name') }}" data-validation-url="{{ route('ajax::username_check') }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="login__form-element required">
                        <input class="email" type="text" name="email" placeholder="E-posta adresi" data-validation="server required email"  value="{{ old('email') }}" data-validation-url="{{ route('ajax::email_check') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="login__form-element required">
                        <input type="password" name="password" placeholder="Şifre" data-validation="required length" data-validation-length="min6">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="login__form-element required">
                        <input type="password" name="password_confirmation" placeholder="Şifreyi Tekrarla" data-validation="required length" data-validation-length="min6">

                    </div>

                    <div class="login__form-element login__form-agreement required">
                        <input type="checkbox" id="user-agreement2" data-validation="required">
                        <label for="user-agreement2"><a href="{{ route('memberAgree') }}" target="_blank">Üyelik sözleşmesini</a> kabul ediyorum.</label>
                    </div>
                    <div class="login__form-element">
                        <button class="login__form-button login__form-button--signin ripple" type="submit">ÜYE OL</button>
                    </div>
                    <div class="login__form-signup">Üyeliğiniz varsa <a href="{{ route('login') }}" class="modal-button" >giriş yapın</a>.</div>
                </form>
            </div>
        </div>
    </div>

@endsection