<span class="back-to-top hide-mobile"><i class="material-icons">&#xE316;</i></span>
<script>var fuckAdBlock = undefined;</script>
<script src="{{ asset('/assets/default/js/main.js?v=2.3.4') }}"></script>
<script src="{{ asset('/assets/default/packages/sweetalert/sweetalert.js') }}"></script>
@include('sweet::alert')
<script src="{{ asset('/js/ckeditor/ckeditor.js?v=2.3.4') }}"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-54494799-1', 'auto');
    ga('send', 'pageview');
</script>
<script>
    // adblock detect
    var adBlockDetected = function() {
        console.log('AdBlock algılandı.');
    };
    var adBlockUndetected = function() {};
    if(typeof fuckAdBlock === 'undefined') {
        $(document).ready(adBlockDetected);
    } else {
        fuckAdBlock.on(true, adBlockDetected).on(false, adBlockUndetected);
    }
    // adblock detect end

    $(function(){
        $( document ).ajaxComplete(function( event, response, settings ) {
            if ( settings.url != "/check-notification?response=HTML" ) {
                $('.material').materialForm();
                $('.ripple').materialripple();
            }
        });

        $('.ripple').materialripple();
        $('.material').materialForm();

        function checkNotification(){
            $.ajax({
                type: "POST",
                url: "/ajax-notification",
                dataType: "HTML",
                data: {response: 'HTML'},
                success: function(data) {
                    var countNotification = $(data).find('.notification-dropdown__item').length;
                    if (countNotification > 0) {
                        $('.header__appbar--right__notice .count').css('display', 'block').html(countNotification);
                        $('.notification-dropdown').html(data);
                    }
                },
                error: function(error) {
                }
            });
        }
        //checkNotification();
        //setInterval(checkNotification, (3 * 60) * 1000);

        //
        $('body').on({
            click: function () {
                var contentNotification = getCookie('content-notification');
                var data = contentNotification ? contentNotification.split(',') : [];
                $('.notification-dropdown__item').each(function () {
                    var dataKey = $(this).data('key');
                    if (data.indexOf(dataKey) !== 1) {
                        data.push(dataKey);
                    }
                });

                data = data.join();
                createCookie('content-notification', data, 1, '/', null);
                $('.header__appbar--right__notice .count').css('display', 'none');
            }
        }, '.header__appbar--right__notice__button');
    });

    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";

    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var nextVideo = false;

    function onYouTubeIframeAPIReady() {
        $('.video-player').each(function(i){
            new YT.Player($(this).attr('id'), {
                height: $(this).data('height'),
                width: $(this).data('width'),
                videoId: $(this).data('id'),
                showinfo: 0,
                playerVars: {
                    autoplay: $(this).data('autoplay'),
                    showinfo: 0,
                    wmode: "opaque"
                },
                events: {
                    'onReady': $(this).data('ready') == true ? onPlayerAnnouncement : '',
                    'onStateChange': $(this).data('state') == true ? onPlayerStateChange : ''
                }
            });

            if (typeof $(this).data('next') !== 'undefined') {
                nextVideo = $(this).data('next');
            }

        });
    }

    // sidebar
    function onPlayerAnnouncement(event) {
        event.target.setVolume(0);

        $('.announcement__close').on('click', function(){
            event.target.pauseVideo();
        });
    }

    // video detail
    var autoPlay = getCookie('autoPlay');
    if (autoPlay == null) {
        createCookie('autoPlay','true',365);
        autoPlay = getCookie('autoPlay');
    }

    if (autoPlay == null || autoPlay == 'true') {
        $('.autoplay').attr('checked','checked');;
    }
    $(function(){
        $('.autoplay').click(function(){
            autoPlay = this.checked;
            createCookie('autoPlay',autoPlay,365);
            if (autoPlay == 'false' || autoPlay == false) {
                stopTimer();
            }
        });
    });

    var second = 5;
    var isVideoFinshed = false;

    function onPlayerStateChange(event) {
        if(event.data == 0) {
            if((autoPlay == 'true' || autoPlay == true) && nextVideo != false) {
                isVideoFinshed = true;
                startTimer();
            }
        }
    }

    function startTimer() {
        $('.video-showcase__content--right__options__title').show();
        videoInterval = setInterval(function(){
            second--;
            if(second == 0) {
                $('.video-showcase__content--right__options__title').html('Yönlendiriliyor..');
                window.location = nextVideo;
            }else if(second < 0) {
                $('.video-showcase__content--right__options__title').html('Yönlendiriliyor..');
            }else {
                $('.video-showcase__content--right__options__title').html(second+ ' saniye');
            }
        },1000);
    }

    function stopTimer() {
        clearInterval(videoInterval);
        $('.video-showcase__content--right__options__title').remove();
    }

    $(function(){
        $(window).on('scroll',function(){
            if(isVideoFinshed) {
                stopTimer();
            }
        });
    });

    $(function(){
        var $header = $('.header'),
            $announcement = $('.announcement'),
            $announcement__close = $('.announcement__close');

        $announcement__close.on('click', function(){
            function updateTopOffset() {
                var topValue = ($announcement.outerHeight()) * (-1);
                $announcement.css({
                    'margin-top': topValue,
                    'margin-bottom': $header.height()
                });
            }
            updateTopOffset();
            createCookie('announcement-' + $announcement.data('name'), false, 1);
            $(window).on('resize', function(){ updateTopOffset(); });

        });
    });

    // add class body for below ie 10
    navigator.sayswho= (function(){
        var ua = navigator.userAgent, tem,
            M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(M[1]=== 'MSIE'){
            return {browser: 'ie', version: M[2]};
        } else { return false; }
    })();

    if (navigator.sayswho['version'] && navigator.sayswho['browser']) {
        $('body').addClass(navigator.sayswho['browser']+navigator.sayswho['version']);
    }

    // header notification box
    if (!isMobile && !isiDevice) {
        $(window).load(function () {
            var isFirefox = typeof InstallTrigger !== 'undefined';
            var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);

            if (isFirefox == true || isChrome == true) {
                if (getCookie('push_notification_permission-v2') != 'later' && getCookie('push_notification_permission-v2') != 'block' && getCookie('push_notification_permission-v2') != 'allow') {
                    //appendBottomBarHTML();
                }
                function appendBottomBarHTML() {
                    var bottomBarHTML = '<div class="push-notification">';
                    bottomBarHTML += '    <div class="push-notification__container">';
                    bottomBarHTML += '        <div class="push-notification__row">';
                    bottomBarHTML += '           <div class="push-notification__image"><img src="assets/default/images/notification-logo.png" width="68"></div>';
                    bottomBarHTML += '           <div class="push-notification__content">';
                    bottomBarHTML += '               <div class="push-notification__title"><b>Önemli gelişmelerden anında haberdar olun!</b></div>';
                    bottomBarHTML += '               <div class="push-notification__text">Sanalyer ile güncel haberlerden anında haberdar olmak için bildirimleri açabilirsiniz.</div>';
                    bottomBarHTML += '           </div>';
                    bottomBarHTML += '        </div>';
                    bottomBarHTML += '        <div class="push-notification__buttons">';
                    bottomBarHTML += '           <span class="push-notification__close material-button">DAHA SONRA</span>';
                    bottomBarHTML += '           <span class="push-notification__open material-button material-shadow--2dp ripple">BİLDİRİMLERİ AÇ</span>';
                    bottomBarHTML += '        </div>';
                    bottomBarHTML += '    </div>';
                    bottomBarHTML += '</div>';

                    $('body').append(bottomBarHTML);
                }

                var pushNotification = $('.push-notification');
                var closeButton = $('.push-notification__close');
                var pushButton = $('.push-notification__open');

                if (pushButton.length > 0) {
                    pushButton.on('click', function () {
                        createCookie('push_notification_permission-v2', 'allow', 365);
                        window.open('https://notif.webtekno.com/notification-index', "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=250, left=500, width=550, height=500");
                        if (pushNotification) {
                            pushNotification.css('top', '-200px');
                        }
                    });
                }

                if (closeButton.length > 0) {
                    closeButton.on('click', function () {
                        createCookie('push_notification_permission-v2', 'later', 2);
                        pushNotification.css('top', '-200px');
                    });
                }
            }
        });
    }
</script>
<script>
    (function ($, window, document) {

        var StickyScroll = {

            init: function (options, $el) {
                var base = this;

                // options
                base.options = options;

                // elements
                base.$sticky = $el;

                // functions
                base.onScroll();
            },

            onScroll : function () {
                var base = this;
                base.scrollState = 'default';

                $(window).on('scroll', function(){
                    base.scrollTop = $(this).scrollTop();

                    if (base.scrollState != 'fixed') {
                        base.breakpoint = base.$sticky.offset().top - base.options.threshold;
                    }
                    if (base.scrollTop >= base.breakpoint && base.scrollState != 'fixed') {
                        base.onFixed();
                    }
                    if (base.scrollTop < base.breakpoint && base.scrollState != 'default') {
                        base.onDefault();
                    }
                });
            },

            onFixed : function () {
                var base = this;
                base.scrollState = 'fixed';
                base.$sticky.css({
                    position: 'fixed',
                    top: base.options.threshold,
                    'margin-top': 0
                });
            },

            onDefault : function () {
                var base = this;
                base.scrollState = 'default';
                base.$sticky.css({
                    position: 'relative',
                    top: 'auto',
                    'margin-top': base.options.top
                });
            }

        }

        $.fn.wtStickyScroll = function (options) {
            var stickyScroll = Object.create(StickyScroll);
            if ($(this).length > 0){
                stickyScroll.init(options, $(this));
            }
        };


    }(jQuery, window, document));
</script><script>
    /*
    $('.sidebar').wtStickyScroll({
        container: '.global-container',
        sidebar_fixed: '.sidebar--fixed',
        content: '.content',
        header: '.header',
        content_header: '.content-header',
        topSpace: 20
    });
    */
    $('.sidebar-sticky').wtStickyScroll({
        top: 32,
        threshold: 92
    });
</script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type: "POST",
        url: "/ajax/header",
        data: {_token: CSRF_TOKEN},
        dataType: "HTML",
        success: function(e) {
            var t = $(e).find(".header__appbar--right__user"),
                n = $(e).find(".login");
            $(t).insertAfter(".header__appbar--right__search"), $(n).insertAfter("#modal-signup")
        },
        error: function(e, t, n) {
            console.log(t)
        }
    });

    $(function(){

        //facebook
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '1037724072951294',
                xfbml      : true,
                version    : 'v2.5'
            });
        };

        // share scroll
        if ($('.content-sticky').length > 0) {
            if ($(window).width() >= 768) {
                $(window).on('scroll', function () {
                    var scrollTop = $(this).scrollTop();
                    $('article').each(function () {
                        if (scrollTop >= ($(this).find('.content-body').offset().top - 76)) {
                            $(this).find('.content-sticky').addClass('sticky');
                            if (scrollTop >= ($(this).find('.content-body').offset().top + $(this).find('.content-body').height() - ($(this).find('.content-sticky').height() + 92))) {
                                $(this).find('.content-sticky').removeClass('sticky');
                                $(this).find('.content-sticky').css({'bottom': '0px', 'top': 'auto'});
                            } else {
                                $(this).find('.content-sticky').addClass('sticky').css({
                                    'bottom': 'initial',
                                    'top': '76px'
                                });
                            }
                        } else {
                            $(this).find('.content-sticky').removeClass('sticky').css({'bottom': 'auto', 'top': '0'});
                        }
                    });
                });
            }
        }

        // share click
        $('body').on({
            click: function (){
                var $this = $(this),
                    dataShareType = $this.attr('data-share-type'),
                    dataType = $this.attr('data-type'),
                    dataId = $this.attr('data-id'),
                    dataPostUrl = $this.attr('data-post-url'),
                    dataTitle = $this.attr('data-title'),
                    dataSef = $this.attr('data-sef');

                switch(dataShareType) {
                    case 'facebook':
                        FB.ui({
                            method: 'share',
                            href: dataSef,
                        }, function(response){
                            if (response && !response.error_message) {
                                updateHit();
                            }
                        });

                        break;

                    case 'twitter':
                        shareWindow('https://twitter.com/intent/tweet?via=sanalyer&text='+encodeURIComponent(dataTitle) + ' %E2%96%B6 ' + encodeURIComponent(dataSef));
                        updateHit();
                        break;

                    case 'gplus':
                        shareWindow('https://plus.google.com/share?url=' + encodeURIComponent(dataSef));
                        updateHit();
                        break;

                    case 'mail':
                        window.location.href = 'mailto:?subject=' + encodeURIComponent(dataTitle) +'&body='+ encodeURIComponent(dataSef);
                        //updateHit();
                        break;

                    case 'whatsapp':
                        window.location.href = 'whatsapp://send?text=' + encodeURIComponent(dataTitle) +' %E2%96%B6 '+ encodeURIComponent(dataSef);
                        updateHit();
                        break;
                }

                function shareWindow (url) {
                    window.open(url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                }

                function updateHit () {
                    $.ajax({
                        type: "POST",
                        url: dataPostUrl,
                        data: {contentId: dataId, contentType: dataType, shareType: dataShareType},
                        success: function(data) {

                            if ($('.video-showcase').length > 0) {
                                var $container = $('.video-showcase');
                            } else if ($('article[data-id="' + dataId + '"]').length > 0) {
                                var $container = $('article[data-id="' + dataId + '"]');
                            } else if ($('.wt-share-item[data-id="' + dataId + '"]').length > 0) {
                                var $container = $('.wt-share-item[data-id="' + dataId + '"]');
                            } else {
                                $container = null;
                            }

                            //var $container = dataType == 'video' ? $('.video-showcase') : $('article[data-id="' + dataId + '"]');

                            if ( $container != null && $container.length > 0 ) {
                                var $badged = $container.find('.wt-share-badge-' + dataShareType);

                                var $headerCount = $('.content-header').find('.wt-share-count'),
                                    $containerCount = $container.find('.wt-share-count'),
                                    value = parseInt($containerCount.html()) + 1;

                                $container.data('share', value);
                                //$containerCount.html(value);

                                if ($headerCount.length > 0) {
                                    //$headerCount.html(value);
                                }

                                if ( $badged.length > 0 && (dataShareType == 'facebook' || dataShareType == 'twitter')) {
                                    if ($badged.hasClass('is-visible')) {
                                        //$badged.html(data);
                                    } else {
                                        //$badged.addClass('is-visible').html(data);
                                    }
                                }
                            }

                        }
                    });
                }
            }
        }, '.wt-share-button')
    });
</script>
<footer class="footer"></footer>
<script>
    /*$(".lazy").lazyload({
        threshold : 300,
        failure_limit : 20
    }).removeClass('lazy').addClass('lazyloaded');*/
    $(".lazy").lazyload({
        threshold : 200,
        failure_limit : 20
    }).removeClass('lazy').addClass('lazyloaded');

    // smartbanner cookie control
    $(document).on('ready',function(){
        if (getCookie('android-dropdown-v4') != 'false') {
            $('.header__appbar--right__android__button').addClass('is-active');
            $('.android-dropdown').addClass('is-visible');
        }


        setTimeout(function(){
            $.smartbanner({
                url:'https://play.google.com/store/apps/details?id=com.sanalyer&hl=tr',
                title: 'Sanalyer', // What the title of the app should be in the banner (defaults to <title>)
                author: null, // What the author of the app should be in the banner (defaults to <meta name="author"> or hostname)
                price: null, // Price of the app
                inGooglePlay: "<strong>UYGULAMAMIZ YENİLENDİ!</strong> <strong>Hemen İndir!</strong>", // Text of price for Android
                icon: '{{ asset("assets/default/images/android-app.png") }}', // The URL of the icon (defaults to <meta name="apple-touch-icon">)
                button: '', // Text for the install button
                daysHidden: 1, // Duration to hide the banner after being closed (0 = always show banner)
                daysReminder: 7, // Duration to hide the banner after "VIEW" is clicked *separate from when the close button is clicked* (0 = always show banner)
                iOSUniversalApp: false, // If the iOS App is a universal app for both iPad and iPhone, display Smart Banner to iPad users, too.
                onInstall: function() {
                },
                onClose: function() {
                }
            })
        }, 3000);

    });
</script>

<script>
    $(function () {

        $('.wt-masthead__close').on('click', function () {
            var date = new Date();
            date.setTime(date.getTime() + (6 * 60 * 60 * 1000));
            //var expires = "; expires="+date.toGMTString();
            //document.cookie = "masthead-v2=hidden"+expires+";";

            $('.wt-masthead').remove();
            $('.wt-container').css({
                'padding-top': '60px'
            });
            $('.headline').css({
                'margin-top': '-60px',
                'padding-top': '64px'
            });
        });

    });
</script>
<script src="{{ asset('/assets/default/js-packages/components/timeline.js') }}"></script>

<script>
    if(isMobile) {
        head.load("{{ asset('/assets/default/js-packages/components/swipe.js') }}","{{ asset('/assets/default/js-packages/components/slider.js') }}");
    }
</script>


<script>
    $(function(){
        var $sliderItem = $('.sidebar-teknostore__products__item');
        var sliderItemCount = $sliderItem.length;
        var itemWidth = 236;
        var fullWidth = itemWidth * sliderItemCount;
        var $sliderProducts = $('.sidebar-teknostore__products');
        var $sliderNav = $('.sidebar-teknostore__slider__nav');

        function sliderPosition(_width, _index) {
            var sliderPositionVal = (_width * _index) * -1;
            var sliderWidth = _width * sliderItemCount;
            $sliderProducts.css({"transform": "translate3d("+ sliderPositionVal +"px, 0px, 0px)", "width": sliderWidth+"px",  "display": "block"});
        }

        function sliderNavigation(_nav){
            var activeSliderIndex = $('.sidebar-teknostore__products__item.active').index();
            if (_nav == 'next') {
                if (activeSliderIndex != (sliderItemCount - 1)) {
                    $sliderItem.removeClass('active').eq(activeSliderIndex + 1).addClass('active');
                    sliderPosition(itemWidth, activeSliderIndex + 1);
                } else {
                    $sliderItem.removeClass('active').eq(0).addClass('active');
                    sliderPosition(itemWidth, 0);
                }

            } else if (_nav == 'prev') {
                if (activeSliderIndex != 0) {
                    $sliderItem.removeClass('active').eq(activeSliderIndex - 1).addClass('active');
                    sliderPosition(itemWidth, activeSliderIndex - 1);
                } else {
                    $sliderItem.removeClass('active').eq(sliderItemCount - 1).addClass('active');
                    sliderPosition(itemWidth, sliderItemCount - 1);
                }
            }
        }

        function sliderAnimateAction() {
            sliderNavigation('next');
        }

        function sliderInit() {
            $sliderItem.first().addClass('active');
            sliderPosition(itemWidth, 0);

            $sliderNav.on('click', function(){
                sliderNavigation($(this).data('nav'))
            });


            var sliderAnimate = setInterval(sliderAnimateAction, 5000);
            $('.sidebar-teknostore__slider').on('mouseover mouseout', function(event){
                if (event.type == 'mouseout') {
                    sliderAnimate = setInterval(sliderAnimateAction, 5000);
                } else {
                    clearInterval(sliderAnimate);
                }
            });



        }

        sliderInit();

    });
</script>
@yield('script')

<div class="overlay"></div>
</body>
</html>