<div class="sidebar hide-mobile">

    @include('ads.sidebar_top_300_250')
{{--
    <div class="sidebar-block">
        <div class="sidebar-teknostore">
            <div class="sidebar-teknostore__head">
                <a href="https://www.teknostore.com/?utm_source=sanalyer-sidebar&utm_medium=sidebar" target="_blank" title="Teknostore - İlginç Teknolojik Ürünler ve Hediyeler"><span class="sidebar-teknostore__logo"></span></a>
                <span class="sidebar-teknostore__title">bir promega projesidir.</span>
            </div>
            <div class="sidebar-teknostore__slider">
                <ul class="sidebar-teknostore__products">
                    <li class="sidebar-teknostore__products__item">
                        <figure>
                            <a href="https://www.teknostore.com/products/aksiyon-kamera-paketi-hediye-sepeti?utm_source=sanalyer-sidebar&utm_medium=sidebar" class="sidebar-teknostore__products__link" target="_blank" title="Aksiyon Kamera Paketi - Hediye Sepeti">
                                <img src="https://cdn.shopify.com/s/files/1/1316/3851/products/aksiyon-kamera-paketi-hediye-sepeti-01_large.jpg?v=1513670846" class="sidebar-teknostore__products__image" alt="Aksiyon Kamera Paketi - Hediye Sepeti">
                                <figcaption class="sidebar-teknostore__products__detail">
                                    <h6 class="sidebar-teknostore__products__title">Aksiyon Kamera Paketi - Hediye Sepeti</h6>
                                    <div class="sidebar-teknostore__products__bottom">
                                        <span class="sidebar-teknostore__products__discount">425.<sup>00 TL</sup></span>
                                        <span class="sidebar-teknostore__products__price">454.<sup>70 TL</sup></span>
                                        <span class="sidebar-teknostore__products__buy">İNCELE</span>
                                    </div>
                                </figcaption>
                            </a>
                        </figure>
                    </li>
                </ul>
                <span class="sidebar-teknostore__slider__nav sidebar-teknostore__slider__nav--prev" data-nav="prev"><i class="material-icons">&#xE408;</i></span>
                <span class="sidebar-teknostore__slider__nav sidebar-teknostore__slider__nav--next" data-nav="next"><i class="material-icons">&#xE409;</i></span>
            </div>
        </div>
    </div>
    --}}
    <div class="sidebar-block">
        <div class="sidebar-title global-title">Son Eklenen Videolar</div>
        <ol class="sidebar-trend">
            @widget("TrendVideos")
        </ol>
    </div>
    <div class="sidebar-block">
        <div class="sidebar-title global-title">Sponsor</div>
        <div>
            <a href="https://www.bitcofly.com/" target="_blank" title="bitcofly">
                <img src="https://i.hizliresim.com/Yd233l.png" alt="bitcofly">
            </a>
        </div>
    </div>
    <div class="sidebar-block clearfix">
        <div class="sidebar-title global-title">En Çok Okunanlar</div>
        <ol class="sidebar-mosts sidebar-mosts--readed">
            @widget("MostReadPost")
        </ol>
    </div>
    <div class="sidebar-block clearfix">
        <div class="sidebar-title global-title">Bu Hafta En Çok Okunanlar</div>
        <ol class="sidebar-mosts sidebar-mosts--shared">
            @widget("MostWeekReadPost")
        </ol>
    </div>

    <div class="sidebar-block clearfix">
        <div class="sidebar-title global-title">Bizi Takip Et</div>
        <ul class="sidebar-social">
            <li class="sidebar-social__item">
                <a class="sidebar-social__item__link" href="https://www.facebook.com/sanalyer" target="_blank" title="Facebook'ta Takip Edin">
                    <div class="sidebar-social__item__media sidebar-social__item__media--facebook">
                        <span class="sidebar-social__item__media__icon"></span>
                    </div>
                    <div class="sidebar-social__item__caption">
                        <span class="sidebar-social__item__count">Facebook</span>
                        <span class="sidebar-social__item__title">BEĞENİ</span>
                    </div>
                </a>
            </li>
            <li class="sidebar-social__item">
                <a class="sidebar-social__item__link"  href="https://twitter.com/sanalyer" target="_blank" title="Twitter'da Takip Edin">
                    <div class="sidebar-social__item__media sidebar-social__item__media--twitter">
                        <span class="sidebar-social__item__media__icon"></span>
                    </div>
                    <div class="sidebar-social__item__caption">
                        <span class="sidebar-social__item__count">Twitter</span>
                        <span class="sidebar-social__item__title">TAKİPÇİ</span>
                    </div>
                </a>
            </li>
            <li class="sidebar-social__item">
                <a class="sidebar-social__item__link" href="https://www.youtube.com/channel/UCgzkm9WUK7dDX4X5GBaRJCg" target="_blank" title="Youtube'ta Takip Edin">
                    <div class="sidebar-social__item__media sidebar-social__item__media--youtube">
                        <span class="sidebar-social__item__media__icon"></span>
                    </div>
                    <div class="sidebar-social__item__caption">
                        <span class="sidebar-social__item__count">YouTube</span>
                        <span class="sidebar-social__item__title">ABONE</span>
                    </div>
                </a>
            </li>
            <li class="sidebar-social__item">
                <a class="sidebar-social__item__link" href="https://www.instagram.com/sanalyer/" target="_blank" title="Instagram'da Takip Edin">
                    <div class="sidebar-social__item__media sidebar-social__item__media--instagram">
                        <span class="sidebar-social__item__media__icon"></span>
                    </div>
                    <div class="sidebar-social__item__caption">
                        <span class="sidebar-social__item__count">Instagram</span>
                        <span class="sidebar-social__item__title">TAKİPÇİ</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-sticky">
        @include('ads.sidebar_footer_300_250')

        <div style="width: 125px !important;height: 80px !important;">
            <div style="display: block;padding-left: 65px;">
                <a href="http://yazarkafe.hurriyet.com.tr" class="BoomadsButtonLink144" target="_blank" rel="nofollow">
                    <img src="https://www.sanalyer.com/upload/upload/bumerang-yazarkafe-yazarlari-12580-oval.png"
                         alt="Bumerang - Yazarkafe"/>
                </a>
            </div>
        </div>
        <script type="text/javascript">
            boomads_widget_client = "3a93ef16265747eebde71dcdd122d546";
            boomads_widget_id = "144";
            boomads_widget_width = "0";
            boomads_widget_height = "0";
            boomads_widget_trackingparameter = "https://yazarkafe.hurriyet.com.tr";
        </script>
        <script type="text/javascript" src="https://widget.boomads.com/scripts/widget.js"></script>
    </div>

</div>