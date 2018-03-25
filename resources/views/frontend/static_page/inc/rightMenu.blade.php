<div class="info-sidebar hide-mobile">
    <ul>
        <li class="info-sidebar__item {{ url()->full() == route('abouts') ? "is-active" : "" }}">
            <a href="{{ route('abouts') }}">Hakkımızda</a>
        </li>
        <li class="info-sidebar__item {{ url()->full() == route('corporate') ? "is-active" : "" }}">
            <a href="{{ route('corporate') }}">Künye</a>
        </li>
        <li class="info-sidebar__item {{ url()->full() == route('privacy') ? "is-active" : "" }}">
            <a href="{{ route('privacy') }}">Gizlilik Politikası</a>
        </li>
        <li class="info-sidebar__item {{ url()->full() == route('contact') ? "is-active" : "" }}">
            <a href="{{ route('contact') }}">İletişim</a>
        </li>
    </ul>
</div>