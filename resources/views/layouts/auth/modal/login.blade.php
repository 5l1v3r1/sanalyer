<div class="user">
    <div class="header__appbar--right__user header__appbar--right__user--avatar has-dropdown" data-target="user-dropdown" data-align="right-bottom">
        <div class="header__appbar--right__user__button material-button material-button--icon ripple">
            <img class="avatar avatar-image"
                 src="{{ $userPhoto }}"
                 title="{{ $user->username }}">
        </div>
        <div class="user-dropdown dropdown-container">
            <ul>
                <li class="dropdown-container__item ripple"><a class="modal-button" href="{{ route('new_post') }}" title="Yazı Ekle">Yazı Ekle</a></li>
                <li class="dropdown-container__item ripple"><a class="modal-button" href="{{ route('new_video') }}" title="Video Ekle">Video Ekle</a></li>
                <li class="dropdown-container__item ripple"><a class="modal-button" href="{{ route('threads') }}" title="İçeriklerim">İçeriklerim</a></li>
                {{--<li class="dropdown-container__item ripple"><a class="modal-button" data-modal="modal-edit-profile" title="Profilimi Düzenle">Profilimi Düzenle</a></li>--}}
                <li class="dropdown-container__item ripple"><a class="modal-button" href="{{ env('FORUM_URL').'/account/account-details' }}" title="Profilimi Düzenle">Profilimi Düzenle</a></li>
                @if($user->is_admin)
                    <li class="dropdown-container__item ripple"><a class="modal-button" href="{{ route('admin::index') }}" target="_blank">Admin</a></li>
                @endif
                <li class="dropdown-container__item ripple"><a href="{{ $logout }}" title="Çıkış Yap">Çıkış Yap</a></li>
            </ul>
        </div>
        <span class="header__appbar--right__user__text hide-mobile">
            <i class="username-text">{{ $user->username }}</i> <i class="material-icons">&#xE5C5;</i>
        </span>
    </div>
    {{--<div class="login modal" id="modal-edit-profile">
        <div class="modal-overlay"></div>
        <div class="login__container">
            <div class="login__header">
                <span class="login__title">Profilimi Düzenle</span>
                <span class="login__close modal-close"><i class="material-icons">&#xE5CD;</i></span>
                <div class="login__avatar">
                        <span class="login__avatar__image">
                                <img class="avatar-image"
                                     src="{{ Auth::user()->userPp() }}">
                        </span>
                    <span class="login__avatar__edit">
                            <form method="POST" enctype="multipart/form-data" id="fileUploadForm">
                                {{ csrf_field() }}
                                <input type="file" name="photo" id="file" class="login__avatar__input avatar-input">
                                <label for="file" class="login__avatar__icon"><i class="material-icons">&#xE254;</i></label>
                            </form>
                        </span>
                </div>
                <div class="login__notice hide"></div>
            </div>
            <div class="login__form">
                <div class="login__form-element required">
                    <input type="text" name="name" value="{{ Auth::user()->name }}" placeholder="Kullanıcı adınız" disabled>
                </div>
                <div class="login__form-element required">
                    <input type="text" name="firstname" value="{{ Auth::user()->firstname }}" placeholder="İsminiz" data-validation="required">
                </div>
                <div class="login__form-element required">
                    <input type="text" name="lastname" value="{{ Auth::user()->lastname }}" placeholder="Soyisminiz" data-validation="required">
                </div>
                <div class="login__form-element login__form-element--textarea">
                    <textarea name="biography" placeholder="Biyografi">{{ Auth::user()->biography }}</textarea>
                </div>


                <div class="login__form-element">
                    <button class="login__form-button login__form-button--signin ripple" type="submit">GÜNCELLE</button>
                </div>
            </div>
        </div>
    </div>--}}
</div>