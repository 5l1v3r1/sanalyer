<!doctype html>
<html amp lang="tr-TR">
<head>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="amp-google-client-id-api" content="googleanalytics">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {!! app('seotools')->generate() !!}
    <script custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js" async></script>
    <script custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js" async></script>
    <script custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js" async></script>
    <script custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"
            async></script>
    {{--<script custom-element="amp-facebook-like" src="https://cdn.ampproject.org/v0/amp-facebook-like-0.1.js"
            async></script>--}}
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>
    <script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
    <script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-dailymotion" src="https://cdn.ampproject.org/v0/amp-dailymotion-0.1.js"></script>
    <script async custom-element="amp-vimeo" src="https://cdn.ampproject.org/v0/amp-vimeo-0.1.js"></script>
    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <script custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js" async></script>
    <script src="https://cdn.ampproject.org/v0.js" async></script>
    <style amp-custom>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
        amp-sticky-ad {
            z-index: 9999
        }

        .ampforwp-custom-banner-ad {
            text-align: center
        }

        .amp-ad-wrapper {
            padding-bottom: 15px
        }

        .amp_ad_2, .amp_ad_3, .amp_ad_4 {
            margin-top: 15px
        }

        td, th {
            text-align: left
        }

        a, a:active, a:visited {
            text-decoration: underline
        }

        .amp-wp-content.widget-wrapper {
            margin: 20px 17px 17px 17px
        }

        body {
            font: 16px/1.4 Sans-serif
        }

        a {
            color: #312C7E;
            text-decoration: none
        }

        .clearfix, .cb {
            clear: both
        }

        .alignleft {
            margin-right: 12px;
            margin-bottom: 5px;
            float: left
        }

        .alignright {
            float: right;
            margin-left: 12px;
            margin-bottom: 5px
        }

        .aligncenter {
            text-align: center;
            margin: 0 auto
        }

        #statcounter {
            width: 1px;
            height: 1px
        }

        amp-anim {
            max-width: 100%
        }
        @font-face {
            font-family: PTSans;
            font-style: normal;
            font-weight: 400;
            src: url('{{ asset('assets/default/fonts/pt_sans-web-regular-webfont.eot?') }}');
            src: local("PTSans"), local("PTSans"),
            url('{{ asset('assets/dewfault/fonts/pt_sans-web-regular-webfont.eot?#iefix') }}') format("embedded-opentype"),
            url('{{ asset('assets/default/fonts/pt_sans-web-regular-webfont.woff2') }}') format("woff2"),
            url('{{ asset('assets/default/fonts/pt_sans-web-regular-webfont.woff') }}') format("woff"),
            url('{{ asset('assets/default/fonts/pt_sans-web-regular-webfont.ttf') }}') format("truetype"),
            url('{{ asset('assets/default/fonts/pt_sans-web-regular-webfont.svg#PTSans') }}') format("svg")
        }

        @font-face {
            font-family: PTSans;
            font-style: italic;
            font-weight: 400;
            src: url({{ asset('assets/default/fonts/pt_sans-web-italic-webfont.eot') }});
            src: local("PTSans"), local("PTSans"),
            url('{{ asset('assets/default/fonts/pt_sans-web-italic-webfont.eot?#iefix') }}') format("embedded-opentype"),
            url('{{ asset('assets/default/fonts/pt_sans-web-italic-webfont.woff2') }}') format("woff2"),
            url('{{ asset('assets/default/fonts/pt_sans-web-italic-webfont.woff') }}') format("woff"),
            url('{{ asset('assets/default/fonts/pt_sans-web-italic-webfont.ttf') }}') format("truetype"),
            url('{{ asset('assets/default/fonts/pt_sans-web-italic-webfont.svg#PTSans') }}') format("svg")
        }


        @font-face {
            font-family: TMSans;
            src: url('{{ asset('assets/default/fonts/TMSans-Regular.eot?#iefix') }}') format("embedded-opentype"),
            url('{{ asset('assets/default/fonts/TMSans-Regular.woff') }}') format("woff"),
            url('{{ asset('assets/default/fonts/TMSans-Regular.ttf') }}') format("truetype"),
            url('{{ asset('assets/default/fonts/TMSans-Regular.svg#TMSans-Regular') }}') format("svg");
            font-weight: 400;
            font-style: normal
        }

        @font-face {
            font-family: TMSans;
            src: url('{{ asset('assets/default/fonts/TMSans-Bold.eot?#iefix') }}') format("embedded-opentype"),
            url('{{ asset('assets/default/fonts/TMSans-Bold.woff') }}') format("woff"),
            url('{{ asset('assets/default/fonts/TMSans-Bold.ttf') }}') format("truetype"),
            url('{{ asset('assets/default/fonts/TMSans-Bold.svg#TMSans-Bold') }}') format("svg");
            font-weight: 700;
            font-style: normal
        }



        .amp-wp-content, .amp-wp-title-bar div {
            max-width: 1000px;
            margin: 0 auto
        }

        amp-sidebar {
            width: 280px;
            background: #131313;
            font-family: 'TMSans', serif
        }

        .amp-sidebar-image {
            line-height: 100px;
            vertical-align: middle
        }

        .amp-close-image {
            top: 15px;
            left: 225px;
            cursor: pointer
        }

        .navigation_heading {
            padding: 20px 20px 15px 20px;
            color: #aaa;
            font-size: 10px;
            font-family: sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #555;
            display: inline-block;
            width: 100%
        }

        .toggle-navigationv2 ul {
            list-style-type: none;
            margin: 15px 0 0 0;
            padding: 0
        }

        .toggle-navigationv2 ul li a {
            padding: 10px 15px 10px 20px;
            display: inline-block;
            font-size: 14px;
            color: #eee;
            width: 94%
        }

        .amp-menu li {
            position: relative
        }

        .toggle-navigationv2 ul li a:hover {
            background: #666;
            color: #fff
        }

        .amp-menu li.menu-item-has-children ul {
            display: none;
            margin: 0;
            background: #222
        }

        .amp-menu li.menu-item-has-children ul ul {
            background: #444
        }

        .amp-menu li.menu-item-has-children ul ul ul {
            background: #666
        }

        .amp-menu li.menu-item-has-children:hover > ul {
            display: block
        }

        .amp-menu li.menu-item-has-children:after {
            content: '\25be';
            position: absolute;
            padding: 10px 15px 10px 30px;
            right: 0;
            font-size: 18px;
            color: #ccc;
            top: 0;
            z-index: 10000;
            line-height: 1
        }

        .toggle-navigationv2 .social_icons {
            margin-top: 25px;
            border-top: 1px solid #555;
            padding: 25px 0px;
            color: #fff;
            width: 100%
        }

        .menu-all-pages-container:after {
            content: "";
            clear: both
        }

        .toggle-text {
            color: #fff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 3px;
            display: inherit;
            text-align: center
        }

        .toggle-text:before {
            content: "...";
            font-size: 32px;
            font-family: georgia;
            line-height: 0px;
            margin-left: 0px;
            letter-spacing: 1px;
            top: -3px;
            position: relative;
            padding-right: 10px
        }

        .toggle-navigation:hover, .toggle-navigation:active, .toggle-navigation:focus {
            display: inline-block;
            width: 100%
        }

        .sticky_social {
            width: 100%;
            bottom: 0;
            display: block;
            left: 0;
            box-shadow: 0px 4px 7px #000;
            background: #fff;
            padding: 7px 0px 0px 0px;
            position: fixed;
            margin: 0;
            z-index: 10;
            text-align: center
        }

        .amp-social-icon {
            width: 50px;
            height: 28px;
            display: inline-block;
            background: #5cbe4a;
            position: relative;
            top: -8px;
            padding-top: 0px
        }

        .amp-social-icon amp-img {
            top: 4px
        }

        .custom-amp-socialsharing-line {
            background: #00b900
        }

        .sticky_social .whatsapp-share-icon {
            padding: 4px 0px 14px 0px;
            height: 28px;
            top: -4px;
            position: relative
        }

        .sticky_social .line-share-icon {
            padding: 4px 0px 14px 0px;
            height: 28px;
            top: -4px;
            position: relative
        }

        #header {
            background: #fff;
            text-align: center;
            height: 50px;
            box-shadow: 0 0 32px rgba(0, 0, 0, .15)
        }

        header {
            padding-bottom: 50px
        }

        #headerwrap {
            position: fixed;
            z-index: 1000;
            width: 100%;
            top: 0
        }

        #header h1 {
            text-align: center;
            font-size: 16px;
            position: relative;
            font-weight: bold;
            line-height: 50px;
            padding: 0;
            margin: 0;
            text-transform: uppercase
        }

        main .amp-wp-content {
            font-size: 18px;
            line-height: 29px;
            color: #111
        }

        .single-post main .amp-wp-article-content h1 {
            font-size: 2em
        }

        .single-post main .amp-wp-article-content h1, .single-post main .amp-wp-article-content h2, .single-post main .amp-wp-article-content h3, .single-post main .amp-wp-article-content h4, .single-post main .amp-wp-article-content h5, .single-post main .amp-wp-article-content h6 {
            font-family: 'TMSans', serif;
            margin: 0px 0px 5px 0px;
            line-height: 1.6
        }

        .home-post_image {
            float: left;
            width: 33%;
            padding-right: 2%;
            overflow: hidden;
            max-height: 225px
        }

        .amp-wp-title {
            margin-top: 0px
        }

        h2.amp-wp-title {
            font-family: 'TMSans', serif;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 7px;
            line-height: 1.3
        }

        h2.amp-wp-title a {
            color: #000
        }

        .amp-wp-tags {
            list-style-type: none;
            padding: 0;
            margin: 0 0 9px 0;
            display: inline-flex
        }

        .amp-wp-tags li {
            display: inline;
            background: #F6F6F6;
            color: #9e9e9e;
            line-height: 1;
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 12px;
            margin-right: 8px;
            top: -3px;
            position: relative
        }

        .amp-loop-list {
            position: relative;
            border-bottom: 1px solid #ededed;
            padding: 25px 15px 25px 15px
        }

        body .amp-loop-list-noimg .amp-wp-post-content {
            width: 100%
        }

        .amp-loop-list .amp-wp-post-content {
            float: left;
            width: 65%
        }

        .amp-loop-list .featured_time {
            color: #b3b3b3;
            padding-left: 0
        }

        .amp-wp-post-content p {
            color: grey;
            line-height: 1.5;
            font-size: 14px;
            margin: 8px 0 10px;
            font-family: 'TMSans', serif
        }

        #footer {
            background: #151515;
            color: #eee;
            font-size: 13px;
            text-align: center;
            letter-spacing: 0.2px;
            padding: 35px 0 35px 0;
            margin-top: 30px
        }

        #footer a {
            color: #fff
        }

        #footer p:first-child {
            margin-bottom: 12px
        }

        #footer .social_icons {
            margin: 0px 20px 25px 20px;
            border-bottom: 1px solid #3c3c3c;
            padding-bottom: 25px
        }

        #footer p {
            margin: 0
        }

        .back-to-top {
            padding-bottom: 8px
        }

        .rightslink, #footer .rightslink a {
            font-size: 13px;
            color: #999
        }

        .poweredby {
            padding-top: 10px;
            font-size: 10px
        }

        #footer .poweredby a {
            color: #666
        }

        .footer_menu ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            margin: 0px 20px 25px 20px;
            line-height: 27px;
            font-size: 13px
        }

        .footer_menu ul li {
            display: inline;
            margin: 0 10px
        }

        .footer_menu ul li:first-child {
            margin-left: 0
        }

        .footer_menu ul li:last-child {
            margin-right: 0
        }

        .footer_menu ul ul {
            display: none
        }

        .single-post main {
            margin: 20px 17px 17px 17px
        }

        .amp-wp-article-content {
            font-family: 'TMSans', serif;
            padding:5px;
        }

        .single-post .post-featured-img {
            margin: 0 -17px 0px -17px
        }

        .amp-wp-article-featured-image.wp-caption .wp-caption-text, .ampforwp-gallery-item .wp-caption-text {
            color: #696969;
            font-size: 11px;
            line-height: 1.5;
            background: #eee;
            margin: 0;
            padding: .66em .75em;
            text-align: center
        }

        .ampforwp-gallery-item.amp-carousel-slide {
            padding-bottom: 20px
        }

        .ampforwp-title {
            padding: 0px 0px 0px 0px;
            margin-top: 12px;
            margin-bottom: 12px
        }

        .comment-button-wrapper {
            margin-bottom: 50px;
            margin-top: 30px;
            text-align: center
        }

        .comment-button-wrapper a {
            color: #fff;
            background: #312c7e;
            font-size: 14px;
            padding: 12px 22px 12px 22px;
            font-family: 'TMSans', serif;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 1px
        }

        h1.amp-wp-title {
            margin: 0;
            color: #333333;
            font-size: 30px;
            line-height: 32px;
            font-family: 'TMSans', serif
        }

        .post-pagination-meta {
            min-height: 75px
        }

        .single-post .post-pagination-meta {
            font-size: 15px;
            font-family: sans-serif;
            min-height: auto;
            margin-top: -5px;
            line-height: 26px
        }

        .single-post .post-pagination-meta span {
            font-weight: bold
        }

        .single-post .amp_author_area .amp_author_area_wrapper {
            display: inline-block;
            width: 100%;
            line-height: 1.4;
            margin-top: 22px;
            font-size: 16px;
            color: #333;
            font-family: sans-serif
        }

        .single-post .amp_author_area amp-img {
            margin: 0;
            float: left;
            margin-right: 12px;
            border-radius: 60px
        }

        .amp-wp-article-tags .ampforwp-tax-tag, .amp-wp-article-tags .ampforwp-tax-tag a {
            font-size: 12px;
            color: #555;
            font-family: sans-serif;
            text-align: center;
            margin: 20px 0 0 0
        }

        .amp-wp-article-tags span {
            background: #eee;
            margin-right: 10px;
            padding: 5px 12px 5px 12px;
            border-radius: 3px;
            display: inline-block;
            margin: 5px
        }

        .ampforwp-social-icons {
            /*margin-bottom: 70px;*/
            margin-top: 25px;
            min-height: 40px;
            text-align: center;
        }

        .ampforwp-social-icons amp-social-share {
            border-radius: 60px;
            background-size: 22px;
            margin-right: 6px
        }

        .amp-social-icon-rounded {
            padding: 11px 12px 9px 12px;
            top: -13px;
            position: relative;
            line-height: 1;
            display: inline-block;
            height: inherit;
            border-radius: 60px
        }

        .amp-social-line {
            background: #00b900
        }

        .amp-social-vk {
            background: #45668e
        }

        .amp-social-odnoklassniki {
            background: #ed812b
        }

        .amp-social-reddit {
            background: #ff4500
        }

        .amp-social-telegram {
            background: #0088cc
        }

        .amp-social-tumblr {
            background: #35465c
        }

        .amp-social-digg {
            background: #005be2
        }

        .amp-social-stumbleupon {
            background: #eb4924
        }

        .amp-social-wechat {
            background: #7bb32e
        }

        .amp-social-viber {
            background: #8f5db7
        }

        .amp-social-facebook {
            background: #3b5998
        }

        .amp-social-twitter {
            background: #1da1f2
        }

        .amp-social-gplus {
            background: #dd4b39
        }

        .amp-social-email {
            background: #000000
        }

        .amp-social-pinterest {
            background: #bd081c
        }

        .amp-social-linkedin {
            background: #0077b5
        }

        .amp-social-whatsapp {
            background: #5cbe4a
        }

        .ampforwp-custom-social {
            display: inline-block;
            margin-bottom: 5px
        }

        .amp-wp-tax-tag {
            list-style: none;
            display: inline-block
        }

        figure {
            margin: 0 0 20px 0
        }

        figure amp-img {
            max-width: 100%
        }

        figcaption {
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 11px;
            background: #eee;
            padding: 6px 8px
        }

        .amp-wp-byline amp-img {
            display: none
        }

        .amp-wp-author {
            margin-right: 1px
        }

        .amp-wp-meta, .amp-wp-meta a {
            font-size: 13px;
            color: #acacac;
            margin: 20px 0px 20px 0px;
            padding: 0
        }

        .amp-ad-wrapper {
            text-align: center
        }

        .the_content p {
            margin-top: 0px;
            margin-bottom: 30px
        }

        .amp-wp-tax-tag {
        }

        main .amp-wp-content.featured-image-content {
            padding: 0px;
            border: 0;
            margin-bottom: 0;
            box-shadow: none
        }

        .amp-wp-content .amp-wp-article-featured-image amp-img {
            margin: 0 auto
        }

        .single-post .amp-wp-article-content amp-img {
            max-width: 100%
        }

        main .amp-wp-content.relatedpost {
            background: none;
            box-shadow: none;
            padding: 0px 0 0 0;
            margin: 1.8em auto 1.5em auto
        }

        .single-post main, .related-title, .single-post .comments_list h3 {
            font-size: 20px;
            color: #777;
            font-family: 'TMSans', serif;
            border-bottom: 1px solid #eee;
            font-weight: 400;
            padding-bottom: 1px;
            margin-bottom: 10px
        }

        .related-title {
            display: block
        }

        .related_posts ol {
            list-style-type: none;
            margin: 0;
            padding: 0
        }

        .related_posts ol li {
            display: inline-block;
            width: 100%;
            margin-bottom: 12px;
            padding: 0px
        }

        .related_posts .related_link a {
            color: #444;
            font-size: 16px;
            font-family: 'TMSans', serif;
            font-weight: 600
        }

        .related_posts ol li amp-img {
            float: left;
            margin-right: 15px
        }

        .related_posts ol li p {
            font-size: 12px;
            color: #999;
            line-height: 1.2;
            margin: 12px 0 0 0
        }

        .no_related_thumbnail {
            padding: 15px 18px
        }

        .no_related_thumbnail .related_link {
            margin: 16px 18px 20px 19px
        }

        .related-post_image {
            float: left;
            padding-right: 2%;
            width: 31.6%;
            overflow: hidden;
            margin-right: 15px;
            max-height: 122px;
            max-width: 110px
        }

        .related-post_image amp-img {
            width: 144%;
            left: -20%
        }

        .amp_ad_1 {
            margin-top: 15px;
            margin-bottom: 10px
        }

        .single-post .amp_ad_1 {
            margin-bottom: -15px
        }

        .amp-ad-2 {
            margin-bottom: -5px;
            margin-top: 20px
        }

        html .single-post .ampforwp-incontent-ad-1 {
            margin-bottom: 10px
        }

        .amp-ad-3 {
            margin-bottom: 10px
        }

        .amp-ad-4 {
            margin-top: 2px
        }

        .amp-wp-content blockquote {
            background-color: #fff;
            border-left: 3px solid;
            margin: 0;
            padding: 15px 20px;
            background: #f3f3f3
        }

        .amp-wp-content blockquote p {
            margin-bottom: 0
        }

        pre {
            white-space: pre-wrap
        }

        #designthree {
            background-color: #FFF;
            overflow: visible
        }

        #sidebar[aria-hidden="false"] + #designthree {
            max-height: 100vh;
            overflow: hidden;
            animation: opening .3s normal forwards ease-in-out;
            -webkit-transform: translate3d(60%, 0, 0) scale(0.8);
            transform: translate3d(60%, 0, 0) scale(0.8)
        }

        @keyframes opening {
            0% {
                transform: translate3d(0, 0, 0) scale(1)
            }
            100% {
                transform: translate3d(60%, 0, 0) scale(0.8)
            }
        }

        @keyframes closing {
            0% {
                transform: translate3d(60%, 0, 0) scale(0.8)
            }
            100% {
                transform: translate3d(0, 0, 0) scale(1)
            }
        }

        @keyframes closingFix {
            0% {
                max-height: 100vh;
                overflow: hidden
            }
            100% {
                max-height: none;
                overflow: visible
            }
        }

        .hamburgermenu {
            float: left;
            position: relative;
            z-index: 9999
        }

        .amp-category-block {
            margin: 30px 0px 10px 0px
        }

        .amp-category-block a {
            color: #666
        }

        .amp-category-block ul {
            list-style-type: none
        }

        .category-widget-gutter h4 {
            margin-bottom: 0px
        }

        .category-widget-gutter ul {
            margin-top: 10px;
            list-style-type: none;
            padding: 0
        }

        .amp-category-block-btn {
            display: block;
            text-align: center;
            font-size: 13px;
            margin-top: 15px;
            border-bottom: 1px solid #f1f1f1;
            text-decoration: none;
            padding-bottom: 8px
        }

        .design_2_wrapper .amp-category-block {
            max-width: 840px;
            margin: 1.5em auto
        }

        .amp-category-block ul, .category-widget-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0px 15px 5px 15px
        }

        .amp-category-post {
            width: 32%;
            display: inline-block;
            word-wrap: break-word;
            float: left
        }

        .amp-category-post amp-img {
            margin-bottom: 5px
        }

        .amp-category-block li:nth-child(3) {
            margin: 0 1%
        }

        .searchmenu {
            margin-right: 15px;
            margin-top: 11px;
            position: absolute;
            top: 0;
            right: 0
        }

        .searchmenu button {
            background: transparent;
            border: none
        }

        .amp-logo amp-img {
            margin: 0 auto;
            position: relative;
            top: 9px
        }

        .headerlogo {
            margin: 0 auto;
            width: 80%;
            text-align: center
        }

        .headerlogo a {
            color: #F42
        }

        .toast {
            display: block;
            position: relative;
            height: 50px;
            padding-left: 20px;
            padding-right: 15px;
            width: 49px;
            background: none;
            border: 0
        }

        .toast:after, .toast:before, .toast span {
            position: absolute;
            display: block;
            width: 19px;
            height: 2px;
            border-radius: 2px;
            background-color: #F42;
            -webkit-transform: translate3d(0, 0, 0) rotate(0deg);
            transform: translate3d(0, 0, 0) rotate(0deg)
        }

        .toast:after, .toast:before {
            content: '';
            left: 20px;
            -webkit-transition: all ease-in .4s;
            transition: all ease-in .4s
        }

        .toast span {
            opacity: 1;
            top: 24px;
            -webkit-transition: all ease-in-out .4s;
            transition: all ease-in-out .4s
        }

        .toast:before {
            top: 17px
        }

        .toast:after {
            top: 31px
        }

        #sidebar[aria-hidden="false"] + #designthree .toast span {
            opacity: 0;
            -webkit-transform: translate3d(200%, 0, 0);
            transform: translate3d(200%, 0, 0)
        }

        #sidebar[aria-hidden="false"] + #designthree .toast:before {
            -webkit-transform-origin: left bottom;
            transform-origin: left bottom;
            -webkit-transform: rotate(43deg);
            transform: rotate(43deg)
        }

        #sidebar[aria-hidden="false"] + #designthree .toast:after {
            -webkit-transform-origin: left top;
            transform-origin: left top;
            -webkit-transform: rotate(-43deg);
            transform: rotate(-43deg)
        }

        [class*=icono-] {
            display: inline-block;
            vertical-align: middle;
            position: relative;
            font-style: normal;
            color: #f42;
            text-align: left;
            text-indent: -9999px;
            direction: ltr
        }

        [class*=icono-]:after, [class*=icono-]:before {
            content: '';
            pointer-events: none
        }

        .icono-search {
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%)
        }

        .icono-share {
            height: 9px;
            position: relative;
            width: 9px;
            color: #dadada;
            border-radius: 50%;
            box-shadow: inset 0 0 0 32px, 22px -11px 0 0, 22px 11px 0 0;
            top: -15px;
            margin-right: 35px
        }

        .icono-share:after, .icono-share:before {
            position: absolute;
            width: 24px;
            height: 1px;
            box-shadow: inset 0 0 0 32px;
            left: 0
        }

        .icono-share:before {
            top: 0px;
            -webkit-transform: rotate(-25deg);
            -ms-transform: rotate(-25deg);
            transform: rotate(-25deg)
        }

        .icono-share:after {
            top: 8px;
            -webkit-transform: rotate(25deg);
            -ms-transform: rotate(25deg);
            transform: rotate(25deg)
        }

        .icono-search {
            border: 1px solid;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
            margin: 4px 4px 8px 8px
        }

        .icono-search:before {
            position: absolute;
            left: 50%;
            -webkit-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            transform: rotate(270deg);
            width: 2px;
            height: 9px;
            box-shadow: inset 0 0 0 32px;
            top: 0px;
            border-radius: 0 0 1px 1px;
            left: 14px
        }

        .closebutton {
            background: transparent;
            border: 0;
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 30px;
            width: 32px;
            height: 32px;
            font-size: 12px;
            text-align: center;
            position: absolute;
            top: 12px;
            right: 20px;
            outline: none
        }

        amp-lightbox {
            background: rgba(0, 0, 0, 0.85)
        }

        .searchform label {
            color: #f7f7f7;
            display: block;
            font-size: 10px;
            letter-spacing: 0.3px;
            line-height: 0;
            opacity: 0.6
        }

        .searchform {
            background: transparent;
            left: 20%;
            position: absolute;
            top: 35%;
            width: 60%;
            max-width: 100%;
            transition-delay: 0.5s
        }

        .searchform input {
            background: transparent;
            border: 1px solid #666;
            color: #f7f7f7;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: 0.3px;
            text-transform: capitalize;
            padding: 20px 0px 20px 30px;
            margin-top: 15px;
            width: 100%
        }

        #searchsubmit {
            opacity: 0
        }

        .featured_time {
            font-size: 12px;
            color: #fff;
            opacity: 0.8;
            padding-left: 20px
        }

        .archives_body main {
            margin-top: 30px
        }

        .taxonomy-description p {
            margin-top: 5px;
            font-size: 14px;
            line-height: 1.5
        }

        .amp-sub-archives li {
            width: 50%
        }

        .amp-sub-archives ul {
            padding: 0;
            list-style: none;
            display: flex;
            font-size: 12px;
            line-height: 1.2;
            margin: 5px 0 10px 0px
        }

        .author-img amp-img {
            border-radius: 50%;
            margin: 0px 12px 10px 0px;
            display: block;
            width: 50px
        }

        .author-img {
            float: left;
            padding-bottom: 25px
        }

        table {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            overflow-x: auto
        }

        table a:link {
            font-weight: bold;
            text-decoration: none
        }

        table a:visited {
            color: #999999;
            font-weight: bold;
            text-decoration: none
        }

        table a:active, table a:hover {
            color: #bd5a35;
            text-decoration: underline
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            font-size: 12px;
            text-shadow: 1px 1px 0px #fff;
            background: #eee;
            margin: 0px;
            width: 100%
        }

        table th {
            padding: 21px 25px 22px 25px;
            border-top: 1px solid #fafafa;
            border-bottom: 1px solid #e0e0e0;
            background: #ededed;
            background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
            background: -moz-linear-gradient(top, #ededed, #ebebeb)
        }

        table th:first-child {
            text-align: left;
            padding-left: 20px
        }

        table tr:first-child th:first-child {
            -moz-border-radius-topleft: 3px;
            -webkit-border-top-left-radius: 3px;
            border-top-left-radius: 3px
        }

        table tr:first-child th:last-child {
            -moz-border-radius-topright: 3px;
            -webkit-border-top-right-radius: 3px;
            border-top-right-radius: 3px
        }

        table tr {
            text-align: center;
            padding-left: 20px
        }

        table td:first-child {
            text-align: left;
            padding-left: 20px;
            border-left: 0
        }

        table td {
            padding: 18px;
            border-top: 1px solid #ffffff;
            border-bottom: 1px solid #e0e0e0;
            border-left: 1px solid #e0e0e0;
            background: #fafafa;
            background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
            background: -moz-linear-gradient(top, #fbfbfb, #fafafa)
        }

        table tr.even td {
            background: #f6f6f6;
            background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
            background: -moz-linear-gradient(top, #f8f8f8, #f6f6f6)
        }

        table tr:last-child td {
            border-bottom: 0
        }

        table tr:last-child td:first-child {
            -moz-border-radius-bottomleft: 3px;
            -webkit-border-bottom-left-radius: 3px;
            border-bottom-left-radius: 3px
        }

        table tr:last-child td:last-child {
            -moz-border-radius-bottomright: 3px;
            -webkit-border-bottom-right-radius: 3px;
            border-bottom-right-radius: 3px
        }

        table tr:hover td {
            background: #f2f2f2;
            background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
            background: -moz-linear-gradient(top, #f2f2f2, #f0f0f0)
        }

        @media screen and (min-width: 650px) {
            table {
                display: inline-table
            }
        }

        @media screen and (max-width: 768px) {
            .amp-wp-meta {
                margin: 10px 0px 15px 0px
            }

            .archive-heading {
                padding: 0 15px
            }

            .home-post_image {
                width: 40%
            }

            .amp-loop-list .amp-wp-post-content {
                width: 58%
            }

            .amp-loop-list .featured_time {
                line-height: 1
            }

            .single-post main .amp-wp-content h1 {
                line-height: 1.4;
                font-size: 30px
            }
        }

        @media screen and (max-width: 600px) {
            .amp-loop-list .amp-wp-tags {
                display: none
            }
        }

        @media screen and (max-width: 530px) {
            .home-post_image {
                width: 35%
            }

            .amp-loop-list .amp-wp-post-content {
                width: 63%
            }

            .amp-wp-post-content p {
                font-size: 12px
            }

            .related_posts ol li p {
                line-height: 1.6;
                margin: 7px 0 0 0
            }

            .comments_list ul li .comment-body {
                width: auto
            }

            .amp-category-block li:nth-child(3) {
                margin: 0
            }
        }

        @media screen and (max-width: 425px) {
            .home-post_image {
                width: 31.6%;
                overflow: hidden;
                margin-right: 3%;
                max-height: 122px
            }

            .home-post_image amp-img {
                width: 144%;
                left: -20%
            }

            h2.amp-wp-title {
                margin-bottom: 7px;
                line-height: 1.31578947;
                font-size: 19px;
                position: relative;
                top: -3px
            }

            h2.amp-wp-title a {
                color: #262626
            }

            .amp-loop-list {
                padding: 25px 15px 22px 15px
            }

            .amp-loop-list .amp-wp-post-content {
                width: 63%
            }

            .amp-loop-list .amp-wp-post-content .large-screen-excerpt-design-3, .related_posts .related_link p {
                display: none
            }

            .amp-loop-list .amp-wp-post-content .small-screen-excerpt-design-3 {
                display: block
            }

            .related_posts .related_link a {
                font-size: 18px;
                line-height: 1.7
            }

            .ampforwp-tax-category {
                padding-bottom: 0
            }

            .amp-wp-byline {
                padding: 0
            }

            .related_posts .related_link a {
                font-size: 17px;
                line-height: 1.5
            }

            .single-post main .amp-wp-content h1 {
                line-height: 1.3;
                font-size: 26px
            }

            .icono-share {
                display: none
            }

            .ampforwp-social-icons amp-social-share {
                margin-right: 3px
            }

            main .amp-wp-content {
                font-size: 16px;
                padding:5px;
                line-height: 26px
            }

            .single-post .amp_author_area .amp_author_area_wrapper {
                font-size: 13px
            }

            .amp-category-post {
                font-size: 12px;
                color: #666
            }
        }

        @media screen and (max-width: 400px) {
            .amp-wp-title {
                font-size: 19px
            }
        }

        @media screen and (max-width: 375px) {
            .single-post main .amp-wp-content h1 {
                line-height: 1.3;
                font-size: 24px
            }

            .amp-carousel-slide h1 {
                font-size: 28px;
                line-height: 32px
            }

            #pagination .next a, #pagination .prev a {
                color: #666;
                font-size: 14px;
                padding: 15px 0px;
                margin-top: -5px
            }

            .related-title, .comments_list h3 {
                margin-top: 15px
            }

            #pagination .next {
                margin-bottom: 15px
            }

            .related_posts .related_link a {
                font-size: 15px;
                line-height: 1.6
            }
        }

        @media screen and (max-width: 340px) {
            .single-post main .amp-wp-content h1 {
                line-height: 1.3;
                font-size: 22px
            }

            .amp-loop-list {
                padding: 20px 15px 18px 15px
            }

            h2.amp-wp-title {
                line-height: 1.31578947;
                font-size: 17px
            }

            .related_posts .related_link a {
                font-size: 15px
            }

            .the_content .amp-ad-wrapper {
                text-align: center;
                margin-left: -13px
            }
        }

        @media screen and (max-width: 320px) {
            .related_posts .related_link a {
                font-size: 13px
            }

            .ampforwp-social-icons amp-social-share {
                margin-right: 1px
            }
        }

        .entry-content amp-anim {
            display: table-cell
        }

        a {
            color: #0070CA
        }

        body a {
            color: #0070CA
        }

        .amp-wp-content blockquote {
            border-color: #0070CA
        }

        amp-user-notification {
            border-color: #0070CA
        }

        amp-user-notification button {
            background-color: #0070CA
        }

        .single-post footer {
            padding-bottom: 41px
        }

        .amp-wp-author:before {
            content: " Yayınlayan "
        }

        .ampforwp-tax-category span:last-child:after {
            content: ' '
        }

        .ampforwp-tax-category span:after {
            content: ','
        }

        .amp-wp-article-content img {
            max-width: 100%;
        }



        .icon-twitter:before {
            content: "\f099";
            background: #1da1f2
        }

        .icon-facebook:before {
            content: "\f09a";
            background: #3b5998
        }

        .icon-facebook-f:before {
            content: "\f09a";
            background: #3b5998
        }

        .icon-pinterest:before {
            content: "\f0d2";
            background: #bd081c
        }

        .icon-google-plus:before {
            content: "\f0d5";
            background: #dd4b39
        }

        .icon-linkedin:before {
            content: "\f0e1";
            background: #0077b5
        }

        .icon-youtube-play:before {
            content: "\f16a";
            background: #cd201f
        }

        .icon-instagram:before {
            content: "\f16d";
            background: #c13584
        }

        .icon-tumblr:before {
            content: "\f173";
            background: #35465c
        }

        .icon-vk:before {
            content: "\f189";
            background: #45668e
        }

        .icon-whatsapp:before {
            content: "\f232";
            background: #075e54
        }

        .icon-reddit-alien:before {
            content: "\f281";
            background: #ff4500
        }

        .icon-snapchat-ghost:before {
            content: "\f2ac";
            background: #fffc00
        }

        .social_icons {
            font-size: 15px;
            display: inline-block
        }

        .social_icons ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center
        }

        .social_icons li {
            box-sizing: initial;
            display: inline-block;
            margin: 5px
        }

        .social_icons li:before {
            box-sizing: initial;
            color: #fff;
            padding: 10px;
            display: inline-block;
            border-radius: 70px;
            width: 18px;
            height: 18px;
            line-height: 20px;
            text-align: center
        }

        #ampsomething {
            display: none
        }

        #header, .headerlogo a {
            background: #ffffff
        }

        .comment-button-wrapper a, #pagination .next a, #pagination .prev a {
            background: #0070CA
        }

        .toast:after, .toast:before, .toast span {
            background: #0070CA
        }

        [class*=icono-], .headerlogo a {
            color: #0070CA
        }

        #pagination .next a, #pagination .prev a, #pagination .next a, #pagination .prev a, .comment-button-wrapper a {
            color: #fff
        }

        .breadcrumb {
            line-height: 1;
            margin-bottom: 6px
        }

        .breadcrumb ul, .category-single ul {
            padding: 0;
            margin: 0
        }

        .breadcrumb ul li {
            display: inline
        }

        .breadcrumb ul li a {
            font-size: 12px
        }

        .breadcrumb ul li a::after {
            content: "►";
            display: inline-block;
            font-size: 8px;
            padding: 0 6px 0 7px;
            vertical-align: middle;
            opacity: 0.5;
            position: relative;
            top: -1px
        }

        .breadcrumb ul li:hover a::after {
            color: #c3c3c3
        }

        .breadcrumb ul li:last-child a::after {
            display: none
        }

        .amp-menu > li > a > amp-img, .sub-menu > li > a > amp-img {
            display: inline-block;
            margin-right: 4px
        }

        .menu-item amp-img {
            width: 16px;
            height: 11px;
            display: inline-block;
            margin-right: 5px
        }

        .amp-carousel-container {
            position: relative;
            width: 100%;
            height: 100%
        }

        .amp-carousel-img img {
            object-fit: contain
        }

        .amp-logo amp-img {
            max-width: 190px;
        }

        amp-web-push-widget button.subscribe {
            display: inline-flex;
            align-items: center;
            border-radius: 2px;
            border: 0;
            box-sizing: border-box;
            margin: 0;
            padding: 10px 15px;
            cursor: pointer;
            outline: none;
            font-size: 15px;
            font-weight: 400;
            background: #4A90E2;
            color: white;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.5);
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
        }

        amp-web-push-widget button.subscribe .subscribe-icon {
            margin-right: 10px
        }

        amp-web-push-widget button.subscribe:active {
            transform: scale(0.99)
        }

        amp-web-push-widget button.unsubscribe {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            border: 0;
            margin: 0;
            cursor: pointer;
            outline: none;
            font-size: 15px;
            font-weight: 400;
            background: #4a90e2;
            color: #fff;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            box-sizing: border-box;
            padding: 10px 15px
        }

        amp-web-push-widget.amp-invisible {
            display: none
        }

        .amp-web-push-container {
            width: 245px;
            margin: 0 auto
        }

        .amp_pb {
            display: inline-block;
            width: 100%
        }

        .row {
            display: inline-flex;
            width: 100%
        }

        .col-2 {
            width: 50%;
            float: left
        }

        .amp_blurb {
            text-align: center
        }

        .amp_blurb amp-img {
            margin: 0 auto
        }

        .amp_btn {
            text-align: center
        }

        .amp_btn a {
            background: #f92c8b;
            color: #fff;
            padding: 9px 20px;
            border-radius: 3px;
            display: inline-block;
            box-shadow: 1px 1px 4px #ccc
        }
    </style>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript></head>
<body class="body design_3_wrapper">
@php
    $categoryJson = \App\vendorBladeTemplate(\Radkod\Posts\Models\Category::where('parent_id',0)->get(),[]);
    $categoryArray = json_decode($categoryJson, true);
@endphp
<amp-sidebar id='sidebar' layout="nodisplay" side="left">
    <div class="toggle-navigationv2">
        <div class="navigation_heading">Menü</div>
        <nav id="primary-amp-menu" itemscope="" itemtype="https://schema.org/SiteNavigationElement">
            <div class="menu-mainmenu-container">
                <ul id="menu-mainmenu" class="amp-menu">
                    @foreach($categoryArray as $cat)
                        <li id="menu-item"
                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-4365">
                            <a href="{{ route('show_category',str_slug($cat['title']).'-'.$cat['id']) }}" itemprop="url">
                                <span itemprop="name">{{ $cat['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>

    </div>
</amp-sidebar>
<div id="designthree" class="designthree main_container">
    <header class="container">
        <div id="headerwrap">
            <div id="header">
                <div class="hamburgermenu">
                    <button class="toast pull-left" on='tap:sidebar.toggle'><span></span></button>
                </div>
                <div class="headerlogo">
                    <div class="amp-logo"><h1><a href="{{ env('APP_URL') }}/">sanalyer</a></h1></div>
                </div>
                <div class="searchmenu">
                    <button on="tap:search-icon"><i class="icono-search"></i></button>
                </div>
            </div>
        </div>
    </header>
    <div class="amp-wp-content widget-wrapper">
        <div class="amp_widget_below_the_header"></div>
    </div>
    <main>
        <article class="amp-wp-article">
            <header class="amp-wp-content amp-wp-article-header ampforwp-title"><h1 class="amp-wp-title"> {{ $posts->title }} </h1></header>
            <div class="amp-wp-article-featured-image amp-wp-content featured-image-content">
                <div class="post-featured-img">
                    <figure class="amp-wp-article-featured-image wp-caption">
                        <amp-img width="618" height="348"
                                 src="{{ \App\checkImage($posts->image) }}"
                                 class="attachment-large size-large wp-post-image amp-wp-enforced-sizes" alt=""
                                 srcset="{{ \App\checkImage($posts->image) }} 640w, {{ \App\checkImage($posts->image) }} 300w"
                                 sizes="(min-width: 618px) 618px, 100vw"></amp-img>
                    </figure>
                </div>
            </div>
            <div class="amp-wp-article-content">
                <div class="amp-wp-content the_content">
                   <h3> {!! $postDesc !!}</h3>
                    {!! $postContent !!}
                </div>
                <div class="amp-wp-content amp-wp-article-tags amp-wp-article-category ampforwp-meta-taxonomy">
                    <div class="amp-wp-meta amp-wp-content ampforwp-tax-tag">
                        @foreach(explode(',', $posts->tag) as $info)
                            <span class="amp-tag-5396">
                                <a href="{{ route("tag",$info) }}">{{ $info }}</a>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="amp-wp-content ampforwp-social-icons-wrapper ampforwp-social-icons"><i class="icono-share"></i>
                    <div class="ampforwp-custom-social"><a
                                href="https://twitter.com/intent/tweet?url={{ route('show_post_amp',$posts->full_url) }}&text={{ $posts->title }}&via=@{{ env('APP_TWITTER_USERNAME') }}"
                                class="amp-social-icon-rounded amp-social-twitter">
                            <amp-img
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQwLjAxNzEgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA1MzAuMTU1YzEwLjQyIDEuMDE1IDIwLjgyNiAxLjU0OCAzMS4yMiAxLjU0OCA2MS4wNSAwIDExNS41MjgtMTguNzMgMTYzLjM4Ny01Ni4xNy0yOC40MjQtLjM1Mi01My45MzMtOS4wNC03Ni40NzctMjYuMDQzLTIyLjU3LTE2Ljk5LTM3Ljk4NC0zOC42NzUtNDYuMzIzLTY1LjA1NiA2LjkzMyAxLjQxOCAxNS4xMDIgMi4wOTUgMjQuNDU2IDIuMDk1IDEyLjE1IDAgMjMuNzY3LTEuNTc1IDM0Ljg2Mi00LjY4NC0zMC41MTctNS44NjctNTUuNzY2LTIwLjg5Mi03NS43MS00NC45OTctMTkuOTU0LTI0LjEzMi0yOS45Mi01MS45Ny0yOS45Mi04My41Mjh2LTEuNTc0YzE4LjM5NiAxMC40MiAzOC4zMTIgMTUuODA2IDU5LjgyOCAxNi4xMy0xOC4wMTctMTEuNzk4LTMyLjM0LTI3LjMwNC00Mi45MTUtNDYuNTctMTAuNTc2LTE5LjI0LTE1Ljg3LTQwLjEzLTE1Ljg3LTYyLjY3NCAwLTIzLjU5OCA2LjA4Ny00NS42MDggMTguMjEtNjYuMDk2IDMyLjYgNDAuNTg2IDcyLjQyIDcyLjkzOCAxMTkuNDMyIDk3LjA1NiA0NyAyNC4wOSA5Ny4zNyAzNy41MyAxNTEuMTU4IDQwLjMyNi0yLjQzMi0xMS40NDctMy42NTUtMjEuNTE2LTMuNjU1LTMwLjE4IDAtMzYuMDg1IDEyLjg0LTY2Ljk1NCAzOC41MDUtOTIuNjIgMjUuNjgtMjUuNjY2IDU2LjcwNC0zOC41MDUgOTMuMTUzLTM4LjUwNSAzNy43OSAwIDY5LjcwMiAxMy44OCA5NS43MyA0MS42NCAzMC4xNjgtNi4yNTcgNTcuOTI4LTE3LjAxNSA4My4yNTYtMzIuMjYtOS43MTggMzEuNTU4LTI4LjgxNSA1NS44NDUtNTcuMjM4IDcyLjg0NyAyNS4zMjgtMy4xMSA1MC4zMDQtMTAuMDU2IDc0LjkzLTIwLjgxNC0xNi42NTIgMjYuMDE3LTM4LjMzNyA0OC43NDItNjUuMDU3IDY4LjE1MnYxNy4xOTdjMCAzNC45OTItNS4xMjQgNzAuMTI4LTE1LjM0OCAxMDUuMzU1LTEwLjIxMiAzNS4yMTQtMjUuODUgNjguODUzLTQ2LjgzIDEwMC45NzItMjAuOTk2IDMyLjA2NS00Ni4wNSA2MC42Mi03NS4xOSA4NS41Ny0yOS4xMjYgMjQuOTc2LTY0LjA4IDQ0Ljg1My0xMDQuODUgNTkuNTktNDAuNzU0IDE0Ljc1My04NC41NTMgMjIuMDktMTMxLjM5NyAyMi4wOUMxMjguODYyIDU4OC45NCA2MS43NCA1NjkuMzUgMCA1MzAuMTU0eiI+PC9wYXRoPjwvc3ZnPg=="
                                    width="16" height="16"/>
                        </a></div>
                    <div class="ampforwp-custom-social"><a
                                href="https://plus.google.com/share?url={{ route('show_post_amp',$posts->full_url) }}"
                                class="amp-social-icon-rounded amp-social-gplus">
                            <amp-img
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMzQuOTQyIDRIMTguMTk2QzEwLjY4OCA0IDMuNjIzIDkuNjg4IDMuNjIzIDE2LjI3NmMwIDYuNzMzIDUuMTE4IDEyLjE2NyAxMi43NTUgMTIuMTY3LjUzIDAgMS4wNDctLjAxIDEuNTUzLS4wNDctLjQ5NS45NS0uODUgMi4wMTgtLjg1IDMuMTI4IDAgMS44NyAxLjAwNyAzLjM4OCAyLjI4IDQuNjI3LS45NjIgMC0xLjg5LjAzLTIuOTAzLjAzQzcuMTU3IDM2LjE4IDAgNDIuMSAwIDQ4LjI0MmMwIDYuMDUgNy44NDcgOS44MzIgMTcuMTQ3IDkuODMyIDEwLjYwMiAwIDE2LjQ1Ny02LjAxNSAxNi40NTctMTIuMDY0IDAtNC44NS0xLjQzLTcuNzU0LTUuODU1LTEwLjg4Mi0xLjUxNS0xLjA3Mi00LjQxLTMuNjc3LTQuNDEtNS4yMSAwLTEuNzk0LjUxMy0yLjY3OCAzLjIxNS00Ljc5IDIuNzctMi4xNjMgNC43My01LjIwNSA0LjczLTguNzQ0IDAtNC4yMTMtMS44NzYtOC4zMi01LjM5OC05LjY3M2g1LjMxbDMuNzQ4LTIuNzA4em0tNS44NSA0MC45NjZjLjEzNC41Ni4yMDYgMS4xMzguMjA2IDEuNzI3IDAgNC44ODgtMy4xNSA4LjcwNy0xMi4xODYgOC43MDctNi40MjggMC0xMS4wNy00LjA3LTExLjA3LTguOTU2IDAtNC43OSA1Ljc1OC04Ljc3OCAxMi4xODUtOC43MDggMS41LjAxNiAyLjg5OC4yNTcgNC4xNjcuNjY4IDMuNDkgMi40MjcgNS45OTIgMy43OTggNi42OTggNi41NjN6bS0xMC4yOS0xOC4yM2MtNC4zMTYtLjEzLTguNDE2LTQuODI3LTkuMTYtMTAuNDktLjc0NS01LjY2OCAyLjE0OC0xMC4wMDQgNi40NjItOS44NzUgNC4zMTMuMTMgOC40MTUgNC42NzcgOS4xNiAxMC4zNDJzLTIuMTUgMTAuMTU0LTYuNDYyIDEwLjAyNHpNNTIgMTZWNGgtNHYxMkgzNnY0aDEydjEyaDRWMjBoMTJ2LTR6Ij48L3BhdGg+PC9zdmc+"
                                    width="16" height="16"/>
                        </a></div>
                    <div class="ampforwp-custom-social"><a
                                href="mailto:?subject={{ $posts->title }}&body={{ route('show_post_amp',$posts->full_url) }}"
                                class="amp-social-icon-rounded amp-social-email">
                            <amp-img
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgODk2IDEwMjYiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCAxOTN2NjQwaDg5NlYxOTNIMHptNzY4IDY0TDQ0OCA1MjEgMTI4IDI1N2g2NDB6TTY0IDMyMWwyNTIuMDMgMTkxLjYyNUw2NCA3MDVWMzIxem02NCA0NDhsMjU0LTIwNi4yNUw0NDggNjEzbDY1Ljg3NS01MC4xMjVMNzY4IDc2OUgxMjh6bTcwNC02NEw1NzkuNjI1IDUxMi45MzggODMyIDMyMXYzODR6Ij48L3BhdGg+PC9zdmc+"
                                    width="16" height="16"/>
                        </a></div>
                    <div class="ampforwp-custom-social"><a
                                href="https://pinterest.com/pin/create/bookmarklet/?media='.$image.' &url={{ route('show_post_amp',$posts->full_url) }}&description={{ $posts->title }}"
                                class="amp-social-icon-rounded amp-social-pinterest">
                            <amp-img
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNOC42MTcgMTMuMjI3QzguMDkgMTUuOTggNy40NSAxOC42MiA1LjU1IDIwYy0uNTg3LTQuMTYyLjg2LTcuMjg3IDEuNTMzLTEwLjYwNS0xLjE0Ny0xLjkzLjEzOC01LjgxMiAyLjU1NS00Ljg1NSAyLjk3NSAxLjE3Ni0yLjU3NiA3LjE3MiAxLjE1IDcuOTIyIDMuODkuNzggNS40OC02Ljc1IDMuMDY2LTkuMkMxMC4zNy0uMjc0IDMuNzA4IDMuMTggNC41MjggOC4yNDZjLjIgMS4yMzggMS40NzggMS42MTMuNTEgMy4zMjItMi4yMy0uNDk0LTIuODk2LTIuMjU0LTIuODEtNC42LjEzOC0zLjg0IDMuNDUtNi41MjcgNi43Ny02LjkgNC4yMDItLjQ3IDguMTQ1IDEuNTQzIDguNjkgNS40OTQuNjEzIDQuNDYyLTEuODk2IDkuMjk0LTYuMzkgOC45NDYtMS4yMTctLjA5NS0xLjcyNy0uNy0yLjY4LTEuMjh6Ij48L3BhdGg+PC9zdmc+"
                                    width="16" height="16"/>
                        </a></div>
                    <div class="ampforwp-custom-social"><a
                                href="whatsapp://send?text={{ route('show_post_amp',$posts->full_url) }}"
                                class="amp-social-icon-rounded amp-social-whatsapp">
                            <amp-img
                                    src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K"
                                    width="16" height="16"/>
                        </a></div>
                </div>
                <div class="amp-wp-content amp-wp-article-tags amp-wp-article-category ampforwp-meta-taxonomy">
                    <div class="amp-wp-meta amp-wp-content ampforwp-tax-tag">
                        <span class="amp-tag-5396">
                            <a href="{{route('show_post', $posts->full_url)}}#commentArea">Yorumları Göster</a>
                        </span>
                        <span class="amp-tag-5396">
                             <a href="{{route('show_post', $posts->full_url)}}#commentArea">Yorum yap</a>
                        </span>
                    </div>
                </div>
            </div>
        </article>
    </main>
    <div class="amp-wp-content widget-wrapper">
        <div class="amp_widget_above_the_footer"></div>
    </div>
    <footer class="footer_wrapper container">
        <div id="footer">
            <div class="footer_menu">
                <nav itemscope="" itemtype="https://schema.org/SiteNavigationElement">
                    <div class="menu-ust-menu-yapisi-container">
                        <ul id="menu-ust-menu-yapisi" class="menu">
                            <li id="menu-item-5089"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-5089"><a
                                        title="Forum" href="{{ env('APP_URL') }}/forum/"
                                        itemprop="url"><span itemprop="name">Forum</span></a></li>
                        </ul>
                    </div>
                </nav>
            </div>

            <p class="rightslink"> Tüm hakkı saklıdır.</p>
        </div>
    </footer>
</div>
<amp-lightbox id="search-icon" layout="nodisplay">
    <form role="search" method="get" id="searchform" class="searchform" target="_top" action="{{ route('search') }}">
        <div>
            <label class="screen-reader-text" for="s"></label>
            <input type="text" placeholder="Buraya Yaz" value="" name="q" id="s"/>
            <input type="submit"  id="searchsubmit"  value="Ara"/></div>
    </form>
    <button on="tap:search-icon.close" class="closebutton">X</button>
    <i class="icono-cross"></i></amp-lightbox>
<div class="sticky_social">
    <amp-social-share type="facebook" data-param-app_id="1550042825268207" width="50" height="28"></amp-social-share>
    <amp-social-share type="twitter" width="50" height="28" data-param-url=""
                      data-param-text="TITLE {{ route('show_post_amp',$posts->full_url) }} yoluyla {{ env('APP_TWITTER_USERNAME') }}"></amp-social-share>
    <amp-social-share type="gplus" width="50" height="28"></amp-social-share>
    <amp-social-share type="email" width="50" height="28"></amp-social-share>
    <amp-social-share type="pinterest" width="50" height="28"></amp-social-share>
    <a href="whatsapp://send?text={{ route('show_post_amp',$posts->full_url) }}">
        <div class="amp-social-icon">
            <amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K"
                    width="50" height="20"/>
        </div>
    </a></div>
<amp-analytics type="googleanalytics" id="analytics1">
    <script type="application/json">
        {"vars":{"account":"UA-54494799-1"},"triggers":{"trackPageview":{"on":"visible","request":"pageview"}}}
    </script>
</amp-analytics> <!-- ngg_resource_manager_marker --></body>
</html>
