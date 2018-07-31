<div class="login modal no-enter" id="modal-signin">
    <div class="modal-overlay"></div>
    <div class="login__container">
        <div class="login__header">
            <span class="login__title">Giriş Yap</span>
            <span class="login__close modal-close"><i class="material-icons">&#xE5CD;</i></span>
            <div class="login__notice"></div>
        </div>
        <div class="login__form">
            <form id="modal-signin-form" method="post" action="{{ route('loginPost') }}">
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
                    <button class="login__form-button login__form-button--signin ripple" type="submit">GİRİŞ YAP
                    </button>
                </div>
                <div class="login__form-signup">Üyeliğiniz yoksa <a href="#!" class="modal-button"
                                                                    data-modal="modal-signup">yeni üyelik oluşturun</a>.
                </div>
            </form>
        </div>
        {{--
        <div class="login__or"><span>veya</span></div>
        <a class="login__form-button login__form-button--fb-signin ripple" href="{{ env('APP_URL') }}/uye/facebook">FACEBOOK
            İLE GİRİŞ YAP</a>
            --}}
    </div>
</div>
<div class="login modal" id="modal-signup">
    <div class="modal-overlay"></div>
    <div class="login__container">
        <div class="login__header">
            <span class="login__title">Üye Ol</span>
            <span class="login__close modal-close"><i class="material-icons">&#xE5CD;</i></span>
            <div class="login__notice"></div>
        </div>
        <div class="login__form">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <script src="{{ asset('assets/default/packages/sweetalert/sweetalert.js') }}"></script>

                            <script>
                               swal ( "Oops" , "{{ $error }}" ,  "error" );
                           </script>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="no-enter" method="post" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="login__form-element required">
                    <input type="text" name="firstname" placeholder="İsim" data-validation="required"
                           value="{{ old('firstname') }}">
                    @if ($errors->has('firstname'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="login__form-element required">
                    <input type="text" name="lastname" placeholder="Soy İsim" data-validation="required"
                           value="{{ old('lastname') }}">
                    @if ($errors->has('lastname'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="login__form-element required">
                    <input class="username" type="text" name="name" placeholder="Kullanıcı adı"
                           data-validation="server required length" data-validation-length="max15"
                           value="{{ old('name') }}" data-validation-url="{{ route('ajax::username_check') }}">
                    @if ($errors->has('name'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="login__form-element required">
                    <input class="email" type="text" name="email" placeholder="E-posta adresi"
                           data-validation="server required email" value="{{ old('email') }}"
                           data-validation-url="{{ route('ajax::email_check') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="login__form-element required">
                    <input type="password" name="password" placeholder="Şifre" data-validation="required length"
                           data-validation-length="min6">
                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="login__form-element required">
                    <input type="password" name="password_confirmation" placeholder="Şifreyi Tekrarla"
                           data-validation="required length" data-validation-length="min6">

                </div>

                <div class="login__form-element login__form-agreement required">
                    <input type="checkbox" id="user-agreement" data-validation="required">
                    <label for="user-agreement"><a href="{{ route('memberAgree') }}" target="_blank">Üyelik
                            sözleşmesini</a> kabul ediyorum.</label>
                </div>
                <div class="login__form-element">
                    <button class="login__form-button login__form-button--signin ripple" type="submit">ÜYE OL</button>
                </div>
                <div class="login__form-signup">Üyeliğiniz varsa <a href="#!" class="modal-button"
                                                                    data-modal="modal-signin">giriş yapın</a>.
                </div>
            </form>
        </div>
        {{--
        <div class="login__or"><span>veya</span></div>
        <a class="login__form-button login__form-button--fb-signin ripple" href="{{ env('APP_URL') }}/uye/facebook">FACEBOOK
            İLE KAYIT OL</a>
            --}}
    </div>
</div>
<div class="login modal no-enter" id="modal-guest">
    <span class="modal-button" data-modal="modal-guest"></span>
    <div class="modal-overlay"></div>
    <div class="login__container">
        <div class="login__header">
            <span class="login__title">Yorum gönder</span>
            <span class="login__close modal-close"><i class="material-icons">&#xE5CD;</i></span>
            <div class="login__notice">
                Ziyaretçi olarak yorum yapıyorsun.<br>
                Yorumlarında adının ve profil resminin görülebilmesi için <strong>üye olman</strong> veya <strong>giriş
                    yapman gerekiyor.</strong>
            </div>
        </div>
        <div class="login__form">
            <div class="login__form-element">
                <button class="login__form-button login__form-button--send-comment ripple" type="submit">YORUMU GÖNDER
                </button>
            </div>
            <div class="login__form-element">
                <button class="login__form-button login__form-button--signin modal-button ripple"
                        data-modal="modal-signin" type="button">GİRİŞ YAP
                </button>
            </div>
            <div class="login__form-signup">Üyeliğiniz yoksa <a href="#!" class="modal-button"
                                                                data-modal="modal-signup">yeni üyelik oluşturun</a>.
            </div>
        </div>
        <div class="login__or"><span>veya</span></div>
        <a class="login__form-button login__form-button--fb-signin ripple" href="{{ env('APP_URL') }}/uye/facebook">FACEBOOK
            İLE GİRİŞ YAP</a>
    </div>
</div>
<div id="fb-root"></div>
<header class="header">
    <div class="header__appbar">
        <div class="header__appbar--left">
            <div class="header__appbar--left__nav">
                <i class="material-icons">&#xE5D2;</i>
            </div>
            <div class="header__appbar--left__logo">
                <h1><a href="{{ route('home') }}" title="{{ env('APP_NAME') }} - {{ env('APP_SEO') }}"></a></h1>
            </div>
            <div class="header__appbar--left__menu hide-mobile">
                <ul class="header__appbar--left__menu__list">
                    <li class="header__appbar--left__menu__list__item">
                        <a href="{{ route('home') }}"
                           class="ripple {{ url()->full() == route('home') ? "is-active" : "" }}">ANASAYFA</a>
                    </li>
                    <li class="header__appbar--left__menu__list__item">
                        <a href="{{ route('news') }}"
                           class="ripple {{ url()->full() == route('news') ? "is-active" : "" }}">HABER</a>
                    </li>
                    <li class="header__appbar--left__menu__list__item">
                        <a href="{{ route('video') }}"
                           class="ripple {{ url()->full() == route('video') ? "is-active" : "" }}">VİDEO</a>
                    </li>
                    <li class="header__appbar--left__menu__list__item">
                        <a class="category-dropdown ripple has-dropdown" data-target="category-dropdown"
                           data-align="left-bottom">
                            KATEGORİLER <i class="material-icons">&#xE5C5;</i>

                            <div class="category-dropdown dropdown-container">
                                @widget('HeaderCategories')
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="header__appbar--right">
            <div class="header__appbar--right__search">
                <form action="{{ route('home') }}/ara" method="get" id="header-search">
                    <input class="header__appbar--right__search__input" id="search" type="search" required="" name="q"
                           placeholder="Ara..." autocomplete="off">
                    <label></label>
                </form>
                <div class="header__appbar--right__search__button search-submit material-button material-button--icon ripple">
                    <i class="material-icons">&#xE8B6;</i></div>
                <div class="header__appbar--right__search__button search-close material-button material-button--icon ripple">
                    <i class="material-icons">&#xE5CD;</i></div>
            </div>

            {{-- <div class="header__appbar--right__user">
                 <div class="header__appbar--right__user__button material-button material-button--icon modal-button ripple" data-modal="modal-signin">
                     <i class="material-icons">&#xE7FD;</i>
                 </div>
             </div>--}}
            <div class="header__appbar--right__settings">
                <div class="header__appbar--right__settings__button material-button material-button--icon ripple has-dropdown"
                     data-target="settings-dropdown" data-align="right-bottom"><i class="material-icons">&#xE5D4;</i>
                </div>
                <div class="settings-dropdown dropdown-container">
                    <ul>
                        <li class="dropdown-container__item ripple">
                            <a href="{{ route('home') }}/hakkimizda" title="Sanalyer Hakkında">Hakkımızda</a>
                        </li>
                        <li class="dropdown-container__item ripple">
                            <a href="{{ route('home') }}/kunye" title="Sanalyer Künye">Künye</a>
                        </li>
                        <li class="dropdown-container__item ripple">
                            <a href="{{ route('home') }}/gizlilik" title="Sanalyer Gizlilik">Gizlilik</a>
                        </li>
                        <li class="dropdown-container__item ripple">
                            <a href="{{ route('home') }}/iletisim" title="Sanalyer İletişim">İletişim</a>
                        </li>
                    </ul>
                </div>
            </div>
            {{--  <div class="header__appbar--right__android hide-mobile">
                 <div class="header__appbar--right__android__button material-button material-button--icon ripple">
                     <a href="{{ route('home') }}/uye/favorilerim" title="Favorilerim"><i class="material-icons">&#xE866;</i></a>
                 </div>
             </div>--}}
        </div>
    </div>
</header>

{{--<div class="banner" style="height:80px; background: #000000 url({{ asset("assets/default/images/black-friday-bg.jpg") }}) no-repeat top center;">
<a href="{{ route('home') }}" target="_blank" title="Black Friday" style="display:block;position:relative;height:80px">
    <div class="container">
        <div id="counter-black-friday">
            <div id="counter-numbers">
                <span id="hours">1</span>
                <span id="minutes">2</span>
                <span id="seconds">3</span>
            </div>
            <div id="counter-titles">
                <span id="hours-title">Saat</span>
                <span id="minutes-title">Dakika</span>
                <span id="seconds-title">Saniye</span>
            </div>
        </div>
    </div>
</a>
</div>--}}

<div class="banner" style="height:65px; background: #007EE7 url(https://www.sanalyer.com/resimler/sanalyer_app.jpg) no-repeat top center;    background-size: 780px !important;background-position: center -15px !important;">
    <a href="https://play.google.com/store/apps/details?id=com.radkod.sanalyer" target="_blank" title="Teknoloji Haberleri" style="display:block;position:relative;height:65px">
    </a>
</div>

<div class="drawer">
    <div class="drawer__header clearfix">
        <a href="{{ env('APP_URL') }}/" class="drawer__header__logo"></a>
        <span class="drawer__header__close"><i class="material-icons">&#xE408;</i></span>
    </div>
    <ul class="drawer__menu">
        <li class="drawer__menu__item {{ url()->full() == route('home') ? "drawer__menu__item--active" : "" }}">
            <a class="drawer__menu__item__link" href="{{ route('home') }}">
                <span class="drawer__menu__item__icon"><i class="material-icons">&#xE88A;</i></span>
                <span class="drawer__menu__item__title">Anasayfa</span>
            </a>
        </li>
        <li class="drawer__menu__item {{ url()->full() == route('news') ? "drawer__menu__item--active" : "" }}">
            <a class="drawer__menu__item__link" href="{{ route('news') }}">
                <span class="drawer__menu__item__icon"><i class="material-icons">&#xE8B0;</i></span>
                <span class="drawer__menu__item__title">Haber</span>
            </a>
        </li>
        <li class="drawer__menu__item {{ url()->full() == route('video') ? "drawer__menu__item--active" : "" }}">
            <a class="drawer__menu__item__link" href="{{ route('video') }}">
                <span class="drawer__menu__item__icon"><i class="material-icons">&#xE038;</i></span>
                <span class="drawer__menu__item__title">Video</span>
            </a>
        </li>
        <li class="drawer__menu__item">
            <a class="drawer__menu__item__link" href="{{ url('/forum') }}" title="Forum Sanalyer" target="_blank">
                <span class="drawer__menu__item__icon"><i class="material-icons">&#xE0BF;</i></span>
                <span class="drawer__menu__item__title">Forum</span>
            </a>
        </li>


        <li class="drawer__menu__item drawer__menu__item--border">
            <a href="{{ env('APP_URL') }}/hakkimizda" title="Sanalyer Hakkında" class="drawer__menu__item__link">
                <span class="drawer__menu__item__title pl0">Hakkımızda</span>
            </a>
        </li>
        <li class="drawer__menu__item">
            <a href="{{ env('APP_URL') }}/kunye" title="Sanalyer Künye" class="drawer__menu__item__link">
                <span class="drawer__menu__item__title pl0">Künye</span>
            </a>
        </li>
        <li class="drawer__menu__item">
            <a href="{{ env('APP_URL') }}/gizlilik" title="Sanalyer Gizlilik" class="drawer__menu__item__link">
                <span class="drawer__menu__item__title pl0">Gizlilik</span>
            </a>
        </li>
        <li class="drawer__menu__item">
            <a href="{{ env('APP_URL') }}/iletisim" title="Sanalyer İletişim" class="drawer__menu__item__link">
                <span class="drawer__menu__item__title pl0">İletişim</span>
            </a>
        </li>
        <li class="drawer__menu__item drawer__menu__item--border">
            <a href="{{ route('docs') }}" title="API Documentation" class="drawer__menu__item__link">
                <span class="drawer__menu__item__title pl0">API Documentation</span>
            </a>
        </li>

    </ul>
    <div class="drawer__social">
        <a class="drawer__social__item drawer__social__item--facebook" href="https://www.facebook.com/Sanalyer"
           target="_blank" title="Facebook'ta Takip Edin"></a>
        <a class="drawer__social__item drawer__social__item--twitter" href="https://twitter.com/Sanalyer"
           target="_blank" title="Twitter'da Takip Edin"></a>
        <a class="drawer__social__item drawer__social__item--youtube"
           href="https://www.youtube.com/channel/UCgzkm9WUK7dDX4X5GBaRJCg" target="_blank"
           title="Youtube'ta Takip Edin"></a>
        <a class="drawer__social__item drawer__social__item--instagram" href="https://www.instagram.com/Sanalyer/"
           target="_blank" title="Instagram'da Takip Edin"></a>
    </div>
</div>