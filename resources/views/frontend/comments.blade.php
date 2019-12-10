<div class="content-comments" data-thread="{{ $thread }}">
    <meta itemprop="commentCount" content="{{ $commentsTotal }}"/>
    <div class="content-comments__title global-title">Yorumlar ({{ $commentsTotal }})</div>
    @if($userCheck)
        <div class="content-comments__form clearfix is-active" id="form-thread-0" data-login="yes">
            <div class="content-comments__item__avatar">
                <img src="{{ $pp }}">
            </div>

            <form class="content-comments__form__element" method="post">
                <div class="content-comments__form__row clearfix">
                <textarea class="content-comments__form__comment" name="content"
                          placeholder="Yorumunuzu yazın..."></textarea>
                </div>
                <div class="content-comments__form__row clearfix">
                    <div class="content-comments__form__max-char">
                        Yorumunuz minimum <strong>30</strong> karakter olmalıdır.(<span class="max-char">0</span>)
                    </div>
                    <div class="content-comments__form__notice">
                    </div>
                    <input type="hidden" name="thread_id" value="{{ $thread }}">
                    <button class="content-comments__form__send" type="submit">YORUMU GÖNDER</button>
                </div>
            </form>
        </div>
    @else
        <div class="content-comments__form clearfix is-active" id="form-thread-0" data-login="no">
            <div class="content-comments__item__avatar content-comments__item__avatar--guest"></div>
            <form class="content-comments__form__element" method="post">
                <div class="content-comments__form__row clearfix">
                <textarea class="content-comments__form__comment" name="content"
                          placeholder="Yorumunuzu yazın..."></textarea>
                </div>
                <div class="content-comments__form__row clearfix">
                    <div class="content-comments__form__max-char">
                        Yorumunuz minimum <strong>30</strong> karakter olmalıdır.(<span class="max-char">0</span>)
                    </div>
                    <div class="content-comments__form__notice">
                        <div class="content-comments__form__guest">
                            <b>Ziyaretçi</b> olarak yorum yapıyorsun, dilersen <span
                                    class="content-comments__form__login modal-button"
                                    data-modal="modal-signin">giriş yap</span>.
                        </div>
                    </div>
                    <input type="hidden" name="thread_id" value="{{ $thread }}">
                    <button class="content-comments__form__send" type="submit">YORUMU GÖNDER</button>
                </div>
            </form>
        </div>
    @endif
    <div class="content-comments__sub-title"><span>Yorumlar</span></div>
    <div class="content-comments__list" id="thread">
        @foreach($comments as $comment)
            <div class="content-comments__item clearfix" id="comment-thread-{{ $comment->id }}">
                @include('frontend.inc.widgets.comment')
                @if($comment->children->count() > 0)
                    @include('frontend.inc.widgets.commentChildren')
                @endif
            </div>
    </div>
    @endforeach
    <script>
        var $comments = $($('body').find('[data-thread="{{ $thread }}"]')),
            $more = $comments.find('.content-comments__load-more');

        if (!$more.is(':visible')) {
            $more.show();
        }
    </script>
</div>
@if($commentsTotal > 10)
    <div class="content-comments__load-more" data-current-page="1">
        <i class="content-comments__load-more__icon material-icons">&#xE5D5;</i>
        <span class="content-comments__load-more__text">DAHA FAZLA YORUM GÖSTER</span>
    </div>
    <div class="content-spinner">
        <svg class="spinner-container" width="45px" height="45px" viewBox="0 0 52 52">
            <circle class="path" cx="26px" cy="26px" r="20px" fill="none" stroke-width="4px"></circle>
        </svg>
    </div>
@endif
